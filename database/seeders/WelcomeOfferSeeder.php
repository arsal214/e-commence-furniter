<?php

namespace Database\Seeders;

use App\Models\WelcomeOffer;
use App\Models\WelcomeOfferTier;
use Illuminate\Database\Seeder;

/** The default welcome offer. Seeded INACTIVE: review it in admin, then switch it on. */
class WelcomeOfferSeeder extends Seeder
{
    public function run(): void
    {
        if (WelcomeOffer::exists()) {
            return;
        }

        $offer = WelcomeOffer::create(['name' => 'New customer welcome deal', 'is_active' => false, 'expiry_hours' => 72]);

        foreach ([
            [WelcomeOfferTier::FIXED, 5, 35, 1],
            [WelcomeOfferTier::FIXED, 10, 60, 2],
            [WelcomeOfferTier::FREE_SHIPPING, 0, 0, 3],
        ] as [$type, $amount, $min, $sort]) {
            $offer->tiers()->create(['type' => $type, 'amount' => $amount, 'min_subtotal' => $min, 'sort_order' => $sort]);
        }
    }
}
