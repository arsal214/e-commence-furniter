<?php

return [
    // Seller plan.
    'plan_price' => 19.99,

    // Card processing, charged by Stripe and taken out of what each seller earns on an order.
    'fee_percent' => (float) env('MARKET_FEE_PERCENT', 2.9),
    'fee_fixed' => (float) env('MARKET_FEE_FIXED', 0.30),

    // Without carrier tracking feeds, a shipped parcel counts as delivered this many days after it
    // shipped unless the buyer has an open claim. A buyer can also confirm delivery earlier.
    'auto_deliver_days' => (int) env('MARKET_AUTO_DELIVER_DAYS', 7),

    // Claims can be opened this many days after the order.
    'claim_window_days' => 90,

    // Rewards: sellers earn 1 point per $10 delivered; 100 points take $1 off the plan.
    'seller_points_per_dollar' => 0.1,
    'points_per_credit_dollar' => 100,

    // Promoted listings: a fixed price per real click, and the daily budget range a seller can choose.
    'ad_cost_per_click' => (float) env('MARKET_AD_CPC', 0.25),
    'ad_min_budget' => 1,
    'ad_max_budget' => 50,

    // Smallest payout worth a bank transfer.
    'min_payout' => 1.00,
];
