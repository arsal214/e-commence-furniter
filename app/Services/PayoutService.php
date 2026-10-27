<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\Payout;
use App\Models\Seller;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** Pays sellers for delivered parcels once the hold for their level has passed. Run daily. */
class PayoutService
{
    public function __construct(protected StripeTransfers $stripe) {}

    /** @return int number of payouts that reached the bank (sent to Stripe) */
    public function releaseDue(): int
    {
        $sellers = Shipment::where('status', 'delivered')->whereNull('payout_id')->where('release_at', '<=', now())->distinct()->pluck('seller_id');
        $sent = 0;

        foreach (Seller::whereIn('id', $sellers)->get() as $seller) {
            if ($this->releaseFor($seller)) {
                $sent++;
            }
        }

        return $sent;
    }

    public function releaseFor(Seller $seller): bool
    {
        if (! $seller->stripe_account_id) {
            return false; // nothing to send to; the seller sees "add your bank" in Seller Center
        }

        $payout = DB::transaction(function () use ($seller) {
            // A parcel with an open claim waits; the claim decides whether it is paid or refunded.
            $held = Dispute::where('seller_id', $seller->id)->where('status', 'open')->pluck('order_id');
            $due = Shipment::where('seller_id', $seller->id)->where('status', 'delivered')->whereNull('payout_id')->where('release_at', '<=', now())
                ->whereNotIn('order_id', $held)->lockForUpdate()->get();
            $amount = round((float) $due->sum('net'), 2);
            if ($due->isEmpty() || $amount < config('marketplace.min_payout')) {
                return null;
            }

            $payout = Payout::create(['seller_id' => $seller->id, 'amount' => $amount, 'status' => 'scheduled', 'release_at' => now()]);
            Shipment::whereIn('id', $due->pluck('id'))->update(['payout_id' => $payout->id]);

            return $payout;
        });

        if (! $payout) {
            return false;
        }

        try {
            $id = $this->stripe->transfer($payout, $seller->stripe_account_id);
            $payout->update(['status' => 'paid', 'paid_at' => now(), 'stripe_transfer_id' => $id]);

            return true;
        } catch (\Throwable $e) {
            // Free the parcels so tomorrow's run tries again; the failed payout stays on record for staff.
            Log::error("Payout {$payout->id} for seller {$seller->id} failed: " . $e->getMessage());
            Shipment::where('payout_id', $payout->id)->update(['payout_id' => null]);
            $payout->update(['status' => 'failed', 'failure_reason' => mb_substr($e->getMessage(), 0, 250)]);

            return false;
        }
    }
}
