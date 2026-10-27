<?php

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Shoppers and sellers are never told which company processes the payments.

it('never names the payment processor on pages shoppers and visitors can open', function () {
    foreach (['/', '/cart', '/sell', '/sell/pricing', '/seller-agreement', '/terms-and-conditions', '/privacy-policy', '/faq', '/sitemap'] as $url) {
        $this->get($url)->assertOk()->assertDontSee('Stripe');
    }
});

it('never names the payment processor in Seller Center or seller sign-up', function () {
    $u = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create(['user_id' => $u->id, 'store_name' => 'Maple', 'slug' => 'maple', 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done', 'stripe_account_id' => 'acct_1234ABCD']);

    foreach (['/seller', '/seller/payouts', '/seller/billing', '/seller/settings', '/seller/help', '/seller/levels'] as $url) {
        $this->actingAs($u)->get($url)->assertOk()->assertDontSee('Stripe');
    }
    $this->actingAs($u)->get('/seller/payouts')->assertSee('Account ••ABCD');
});

it('labels a card payment as Card, never by the processor', function () {
    expect((new \App\Models\Order(['payment_method' => 'stripe']))->paymentLabel())->toBe('Card')
        ->and((new \App\Models\Order(['payment_method' => 'paypal']))->paymentLabel())->toBe('PayPal')
        ->and((new \App\Models\Order(['payment_method' => 'gift_card']))->paymentLabel())->toBe('Gift card');
});
