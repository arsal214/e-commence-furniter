<?php

namespace App\Services;

use App\Mail\SellerNoticeMail;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Shipment;
use App\Support\DisputeLabels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** Buyer Protection claims: opened by the buyer, decided by staff. */
class DisputeService
{
    public const REASONS = ['not_received' => 'Item not received', 'not_as_described' => 'Not as described', 'damaged' => 'Damaged on arrival', 'changed_mind' => 'I changed my mind', 'other' => 'Other reason'];

    public function __construct(protected StripeTransfers $stripe) {}

    /**
     * @return Dispute|string the claim, or the reason it cannot be opened
     */
    public function open(Order $order, int $sellerId, string $email, string $reason, string $details): Dispute|string
    {
        if (strcasecmp(trim($email), $order->email) !== 0) {
            return 'That email does not match this order.';
        }
        if ($order->payment_status !== 'paid') {
            return 'Only paid orders can be claimed.';
        }
        if ($order->created_at->lt(now()->subDays(config('marketplace.claim_window_days')))) {
            return 'This order is past the claim window.';
        }
        $shipment = Shipment::where('order_id', $order->id)->where('seller_id', $sellerId)->first();
        if (! $shipment || $shipment->status === 'refunded') {
            return 'There is nothing to claim for this seller.';
        }

        $result = DB::transaction(function () use ($order, $sellerId, $shipment, $reason, $details, $email) {
            if (Dispute::where('order_id', $order->id)->where('seller_id', $sellerId)->where('status', 'open')->lockForUpdate()->exists()) {
                return 'A claim is already open for this order.';
            }

            return Dispute::create(['order_id' => $order->id, 'seller_id' => $sellerId, 'user_id' => $order->user_id, 'email' => $email, 'reason' => $reason,
                'details' => $details, 'amount' => $shipment->gross, 'status' => 'open']);
        });

        if ($result instanceof Dispute) {
            $this->tellSeller($result);
        }

        return $result;
    }

    /**
     * Refund the buyer in full for this seller's items. $manual skips Stripe for PayPal orders refunded by hand.
     *
     * @return true|string true when decided, otherwise why not
     */
    public function refund(Dispute $dispute, bool $manual = false, bool $bySeller = false): true|string
    {
        $outcome = DB::transaction(function () use ($dispute, $manual) {
            $d = Dispute::whereKey($dispute->id)->lockForUpdate()->first();
            if ($d->status !== 'open') {
                return 'This claim was already decided.';
            }
            $shipment = Shipment::where('order_id', $d->order_id)->where('seller_id', $d->seller_id)->lockForUpdate()->first();
            $order = Order::find($d->order_id);

            $refundId = null;
            if (! $manual) {
                try {
                    $refundId = $this->stripe->refund($order, (float) $d->amount, "dispute-{$d->id}");
                } catch (\Throwable $e) {
                    return $e->getMessage();
                }
            }

            // Money back means no payout for these items. If the seller was already paid, staff recover it.
            $shipment?->update(['status' => 'refunded', 'net' => 0]);
            $shipment && app(RewardsService::class)->onRefund($shipment->fresh());
            $d->update(['status' => 'refunded', 'resolved_at' => now(), 'stripe_refund_id' => $refundId, 'resolution_note' => $shipment?->payout_id ? 'Seller was already paid: recover from the seller.' : null]);

            return true;
        });

        if ($outcome === true) {
            $bySeller
                ? $this->tell($dispute->fresh(), 'Your order was refunded', 'The seller refunded your order in full. The money returns to your original payment method within 5-10 business days.')
                : $this->tell($dispute->fresh(), 'Your claim was approved', 'We refunded you in full.');
        }

        return $outcome;
    }

    /**
     * The seller refunds their part of an order themselves, with or without a buyer request.
     * An open request for the same order is the one that gets resolved; otherwise a record is made so
     * the refund is tracked like any other and the payout, rewards and emails follow the same path.
     *
     * @return true|string true when refunded, otherwise why not
     */
    public function sellerRefund(Shipment $shipment): true|string
    {
        $order = Order::find($shipment->order_id);
        if (! $order || $order->payment_status !== 'paid') {
            return 'Only paid orders can be refunded.';
        }
        if ($shipment->status === 'refunded') {
            return 'This order was already refunded.';
        }
        if ($order->payment_method !== 'stripe' || ! $order->stripe_payment_intent) {
            return 'This order was not paid by card, so it cannot be refunded here. Contact support and we will do it for you.';
        }

        $created = false;
        $dispute = Dispute::where('order_id', $order->id)->where('seller_id', $shipment->seller_id)->where('status', 'open')->first();
        if (! $dispute) {
            $dispute = Dispute::create(['order_id' => $order->id, 'seller_id' => $shipment->seller_id, 'user_id' => $order->user_id, 'email' => $order->email, 'reason' => 'other',
                'details' => 'Refunded by the seller.', 'amount' => $shipment->gross, 'status' => 'open']);
            $created = true;
        }

        $result = $this->refund($dispute, false, true);
        if ($result !== true && $created) {
            $dispute->delete(); // nothing was refunded, so leave no open claim behind
        }

        return $result;
    }

    /** The seller says no to a request. It stays open so staff can decide, with the seller's reason in front of them. */
    public function decline(Dispute $dispute, string $note): true|string
    {
        $done = DB::transaction(function () use ($dispute, $note) {
            $d = Dispute::whereKey($dispute->id)->lockForUpdate()->first();
            if ($d->status !== 'open' || $d->seller_declined_at) {
                return false;
            }
            $d->update(['seller_declined_at' => now(), 'seller_note' => $note]);

            return true;
        });

        if (! $done) {
            return 'This request was already answered.';
        }
        $this->tell($dispute->fresh(), 'The seller replied to your refund request', 'The seller did not approve a refund. Our team will review your request and email you the decision within 3 business days.', $note);

        return true;
    }

    public function sideWithSeller(Dispute $dispute, ?string $note = null): true|string
    {
        $done = DB::transaction(function () use ($dispute, $note) {
            $d = Dispute::whereKey($dispute->id)->lockForUpdate()->first();
            if ($d->status !== 'open') {
                return false;
            }
            $d->update(['status' => 'closed_seller', 'resolved_at' => now(), 'resolution_note' => $note]);

            return true;
        });

        if (! $done) {
            return 'This claim was already decided.';
        }
        $this->tell($dispute->fresh(), 'Your claim was reviewed', 'After review we were unable to approve a refund for this order.');

        return true;
    }

    protected function tellSeller(Dispute $d): void
    {
        try {
            $email = $d->seller?->user?->email;
            $email && Mail::to($email)->send(new SellerNoticeMail('A buyer asked for a refund', 'A buyer asked for a refund', 'Order ' . $d->order?->tracking_number . ' · ' . DisputeLabels::reason($d->reason) . '. Open Seller Center › Orders to refund it or reply.', $d->details));
        } catch (\Throwable $e) {
            Log::warning("Dispute {$d->id} seller email failed: " . $e->getMessage());
        }
    }

    protected function tell(Dispute $d, string $subject, string $body, ?string $note = null): void
    {
        try {
            Mail::to($d->email ?: $d->order->email)->send(new SellerNoticeMail($subject, $subject, $body, $note ?? ($d->resolution_note && $d->status !== 'refunded' ? $d->resolution_note : null)));
        } catch (\Throwable $e) {
            Log::warning("Dispute {$d->id} email failed: " . $e->getMessage());
        }
    }
}
