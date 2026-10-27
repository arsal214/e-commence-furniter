<?php

namespace App\Services;

use App\Mail\SellerPaymentFailedMail;
use App\Models\Seller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Applies Stripe subscription events to a seller. The subscription_status column is what
 * hides a store (Seller::live), so it must follow Stripe exactly, including when events
 * arrive twice or out of order.
 */
class SellerSubscriptionSync
{
    /** Stripe subscription status -> ours. Anything unlisted leaves the seller untouched. */
    protected const STATUS = [
        'active' => 'active', 'trialing' => 'active',
        'past_due' => 'past_due', 'unpaid' => 'past_due',
        'canceled' => 'cancelled', 'incomplete_expired' => 'cancelled',
    ];

    /** @return bool true when the event was a seller-subscription event we handled */
    public function handle(object $event): bool
    {
        $obj = $event->data->object;

        return match ($event->type) {
            'customer.subscription.updated' => $this->apply($obj->customer, $obj->id, self::STATUS[$obj->status] ?? null, $event),
            'customer.subscription.deleted' => $this->apply($obj->customer, $obj->id, 'cancelled', $event),
            'invoice.paid' => $this->apply($obj->customer, $obj->subscription ?? null, 'active', $event),
            'invoice.payment_failed' => $this->paymentFailed($obj, $event),
            default => false,
        };
    }

    protected function apply(?string $customerId, ?string $subscriptionId, ?string $status, object $event): bool
    {
        $seller = $customerId ? Seller::where('stripe_customer_id', $customerId)->first() : null;
        if (! $seller) {
            return false; // not a seller (e.g. a shopper's one-off charge)
        }

        // A cancelled-then-resubscribed seller has a new subscription; ignore the old one's events.
        if ($subscriptionId && $seller->stripe_subscription_id && $seller->stripe_subscription_id !== $subscriptionId) {
            return true;
        }
        // Late delivery: an older event must not undo a newer one.
        $at = \Illuminate\Support\Carbon::createFromTimestamp($event->created);
        if ($seller->subscription_event_at && $at->lt($seller->subscription_event_at)) {
            return true;
        }
        if ($status === null) {
            return true;
        }

        $seller->update(array_filter([
            'subscription_status' => $status,
            'stripe_subscription_id' => $subscriptionId ?: null,
            'subscription_event_at' => $at,
        ], fn ($v) => $v !== null));

        // The invited business may only now have paid; this is a no-op unless a referral is waiting on it.
        app(ReferralService::class)->creditFor($seller->fresh());

        return true;
    }

    protected function paymentFailed(object $invoice, object $event): bool
    {
        if (! $this->apply($invoice->customer, $invoice->subscription ?? null, 'past_due', $event)) {
            return false;
        }

        // Stripe redelivers the same event id when we are slow to answer: email once per event.
        if (Cache::add('stripe-event:' . $event->id, 1, now()->addDays(7))) {
            $seller = Seller::with('user')->where('stripe_customer_id', $invoice->customer)->first();
            try {
                Mail::to($seller->user->email)->send(new SellerPaymentFailedMail($seller));
            } catch (\Throwable $e) {
                Log::warning("Payment-failed email to seller {$seller->id} failed: " . $e->getMessage());
            }
        }

        return true;
    }
}
