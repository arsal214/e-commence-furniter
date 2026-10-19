<?php

namespace App\Services;

use App\Mail\OrderConfirmationMail;
use App\Models\EmailLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * The one place an order becomes "paid". The Stripe return page, the Stripe
 * webhook and the PayPal capture all call it, so whichever arrives first does the
 * work and the others find nothing left to do.
 */
class OrderSettlement
{
    public function __construct(protected WelcomeOfferService $welcome, protected MetaEventsService $meta) {}

    /**
     * Mark the order paid. Returns true only for the call that flipped it.
     *
     * When the provider reports an amount ($amountCents), it must equal the order
     * total recalculated from the database. A mismatch leaves the order unpaid and
     * logs loudly rather than shipping goods against the wrong amount.
     */
    public function markPaid(Order $order, string $method, ?int $amountCents = null, ?string $cardFingerprint = null, ?bool $livemode = null): bool
    {
        return DB::transaction(function () use ($order, $method, $amountCents, $cardFingerprint, $livemode) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->first();

            if (! $locked || $locked->payment_status === 'paid') {
                return false;
            }

            if ($amountCents !== null && $amountCents !== (int) round($locked->total * 100)) {
                Log::critical("Order {$locked->id}: provider amount {$amountCents} != order total {$locked->total}; NOT marked paid.");

                return false;
            }

            $locked->update([
                'payment_status' => 'paid',
                'status'         => 'processing',
                'payment_method' => $method,
            ] + ($method === 'stripe' && $livemode !== null ? ['stripe_livemode' => $livemode] : []));

            $this->welcome->markUsed($locked, $cardFingerprint);

            $order->refresh();

            return true;
        });
    }

    /** Send the confirmation email once, however many paths reach this. */
    public function sendConfirmation(Order $order): void
    {
        $claimed = Order::whereKey($order->id)->whereNull('confirmation_sent_at')->update(['confirmation_sent_at' => now()]);
        if (! $claimed) {
            return;
        }

        $order->load('items');
        try {
            Mail::to($order->email)->send(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            // Release the claim so the next path (webhook retry, return page) can try again.
            Order::whereKey($order->id)->update(['confirmation_sent_at' => null]);
            Log::warning('Order confirmation email failed for order #' . $order->tracking_number . ': ' . $e->getMessage());
            EmailLog::recordFailure($order->email, OrderConfirmationMail::class, $e->getMessage(), $order->user_id, $order->id);
        }
    }

    /** Server-side Purchase to Meta for a paid order. Safe to call from every path: it sends at most once. */
    public function reportPurchase(Order $order): void
    {
        $order->refresh();
        if ($order->payment_status === 'paid') {
            $this->meta->purchase($order);
        }
    }

    /** True for exactly one caller per order: the one that should fire the purchase events. */
    public function claimPurchaseTracking(Order $order): bool
    {
        return (bool) Order::whereKey($order->id)->whereNull('purchase_tracked_at')->update(['purchase_tracked_at' => now()]);
    }
}
