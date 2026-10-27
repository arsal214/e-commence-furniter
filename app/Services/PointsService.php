<?php

namespace App\Services;

use App\Models\PointEntry;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;

/** Seller reward points: 100 points take $1 off the monthly plan, as a credit on the next Stripe invoice. */
class PointsService
{
    public function __construct(protected SellerBilling $billing) {}

    /** @return int|string dollars credited, or why nothing was */
    public function redeem(Seller $seller): int|string
    {
        return DB::transaction(function () use ($seller) {
            $s = Seller::whereKey($seller->id)->lockForUpdate()->first();
            $dollars = intdiv($s->points, config('marketplace.points_per_credit_dollar'));
            if ($dollars < 1) {
                return 'You need at least 100 points to redeem.';
            }
            if (! $s->stripe_customer_id) {
                return 'Subscribe to the seller plan first.';
            }

            try {
                $this->billing->credit($s, $dollars);
            } catch (\Throwable $e) {
                report($e);

                return 'We could not apply the credit just now. Your points are unchanged. Try again in a moment.';
            }

            $used = $dollars * config('marketplace.points_per_credit_dollar');
            $s->update(['points' => $s->points - $used]);
            PointEntry::create(['seller_id' => $s->id, 'delta' => -$used, 'reason' => "Redeemed for \${$dollars} off your plan"]);

            return $dollars;
        });
    }
}
