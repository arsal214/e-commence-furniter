<?php

namespace App\Services;

use App\Mail\SellerNoticeMail;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** Buyer Protection claims: opened by the buyer, decided by staff. */
class DisputeService
{
    public const REASONS = ['not_received' => 'Item not received', 'not_as_described' => 'Not as described', 'damaged' => 'Damaged on arrival'];

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

        return DB::transaction(function () use ($order, $sellerId, $shipment, $reason, $details, $email) {
            if (Dispute::where('order_id', $order->id)->where('seller_id', $sellerId)->where('status', 'open')->lockForUpdate()->exists()) {
                return 'A claim is already open for this order.';
            }

            return Dispute::create(['order_id' => $order->id, 'seller_id' => $sellerId, 'user_id' => $order->user_id, 'email' => $email, 'reason' => $reason,
                'details' => $details, 'amount' => $shipment->gross, 'status' => 'open']);
        });
    }

    /**
     * Refund the buyer in full for this seller's items. $manual skips Stripe for PayPal orders refunded by hand.
     *
     * @return true|string true when decided, otherwise why not
     */
    public function refund(Dispute $dispute, bool $manual = false): true|string
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
            $this->tell($dispute->fresh(), 'Your claim was approved', 'We refunded you in full.');
        }

        return $outcome;
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

    protected function tell(Dispute $d, string $subject, string $body): void
    {
        try {
            Mail::to($d->email ?: $d->order->email)->send(new SellerNoticeMail($subject, $subject, $body, $d->resolution_note && $d->status !== 'refunded' ? $d->resolution_note : null));
        } catch (\Throwable $e) {
            Log::warning("Dispute {$d->id} email failed: " . $e->getMessage());
        }
    }
}
