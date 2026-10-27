<?php

use App\Mail\SellerPaymentFailedMail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

const WH_SECRET = 'whsec_test_secret';

beforeEach(function () {
    config(['services.stripe.webhook_secret' => WH_SECRET]);
    Mail::fake();
    Cache::flush();
});

/** POST a correctly signed Stripe event. */
function whSend($test, string $type, array $object, ?int $created = null, string $id = null, ?string $secret = WH_SECRET)
{
    $payload = json_encode([
        'id' => $id ?? 'evt_' . uniqid(), 'object' => 'event', 'type' => $type, 'created' => $created ?? time(),
        'livemode' => false, 'data' => ['object' => $object],
    ]);
    $t = time();
    $sig = "t={$t},v1=" . hash_hmac('sha256', "{$t}.{$payload}", $secret ?? 'x');

    return $test->call('POST', '/stripe/webhook', [], [], [], ['HTTP_STRIPE_SIGNATURE' => $sig, 'CONTENT_TYPE' => 'application/json'], $payload);
}

function whSeller(array $over = []): Seller
{
    $user = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);

    return Seller::create($over + [
        'user_id' => $user->id, 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done',
        'stripe_customer_id' => 'cus_1', 'stripe_subscription_id' => 'sub_1', 'store_name' => 'Maple', 'slug' => 'maple',
    ]);
}

// ── landing + pricing ────────────────────────────────────────────────────────────────

it('shows the landing page with the one plan, 0% commission and the US-only rule', function () {
    $this->get('/sell')->assertOk()
        ->assertSee('Keep 100% of every sale')->assertSee('$19.99')->assertSee('0% commission')
        ->assertSee('US-registered businesses only')->assertSee('width=device-width', false)
        ->assertSee('<link rel="canonical"', false)->assertSee('og:title', false)
        ->assertSee('2.9% + $0.30')->assertSee(route('seller.signup'), false)
        ->assertDontSee('[Confirm');
});

it('shows the pricing page with a single plan and the failed-payment rule', function () {
    $this->get('/sell/pricing')->assertOk()
        ->assertSee('ONE PLAN. NO COMMISSION.')->assertSee('$19.99')->assertSee('hidden from shoppers')
        ->assertSee('Billed monthly in USD');
});

it('keeps the title tag within a sensible length on both pages', function () {
    foreach (['/sell', '/sell/pricing'] as $url) {
        preg_match('/<title>(.*?)<\/title>/s', $this->get($url)->getContent(), $m);
        expect(strlen($m[1]))->toBeLessThanOrEqual(80);
    }
});

// ── webhook security ─────────────────────────────────────────────────────────────────

it('refuses an unsigned or wrongly signed event and changes nothing', function () {
    $seller = whSeller();
    $obj = ['id' => 'sub_1', 'customer' => 'cus_1', 'status' => 'canceled'];

    whSend($this, 'customer.subscription.deleted', $obj, secret: 'whsec_wrong')->assertStatus(400);
    $this->postJson('/stripe/webhook', ['type' => 'customer.subscription.deleted'])->assertStatus(400);

    expect($seller->fresh()->subscription_status)->toBe('active');
});

// ── subscription lifecycle ───────────────────────────────────────────────────────────

it('hides the store when payment fails and brings it back automatically when paid', function () {
    $seller = whSeller();
    $cat = Category::create(['name' => 'Home', 'slug' => 'home', 'is_active' => true]);
    $p = Product::create(['category_id' => $cat->id, 'name' => 'Mug', 'price' => 10, 'is_active' => true, 'seller_id' => $seller->id]);
    expect(Product::visible()->pluck('id')->all())->toBe([$p->id]);

    whSend($this, 'invoice.payment_failed', ['customer' => 'cus_1', 'subscription' => 'sub_1'], time())->assertOk();
    expect($seller->fresh()->subscription_status)->toBe('past_due')
        ->and(Product::visible()->count())->toBe(0);
    Mail::assertSent(SellerPaymentFailedMail::class, 1);

    whSend($this, 'invoice.paid', ['customer' => 'cus_1', 'subscription' => 'sub_1'], time() + 5)->assertOk();
    expect($seller->fresh()->subscription_status)->toBe('active')
        ->and(Product::visible()->pluck('id')->all())->toBe([$p->id]);
});

it('emails once even when Stripe redelivers the same failure event', function () {
    whSeller();

    foreach (range(1, 3) as $_) {
        whSend($this, 'invoice.payment_failed', ['customer' => 'cus_1', 'subscription' => 'sub_1'], time(), 'evt_same')->assertOk();
    }

    Mail::assertSent(SellerPaymentFailedMail::class, 1);
});

it('ignores an older event that arrives after a newer one', function () {
    $seller = whSeller();
    $now = time();

    whSend($this, 'invoice.paid', ['customer' => 'cus_1', 'subscription' => 'sub_1'], $now)->assertOk();
    // A failure from before the payment shows up late: the store must stay live.
    whSend($this, 'invoice.payment_failed', ['customer' => 'cus_1', 'subscription' => 'sub_1'], $now - 3600)->assertOk();

    expect($seller->fresh()->subscription_status)->toBe('active');
});

it('cancels on subscription.deleted and ignores events of an old subscription', function () {
    $seller = whSeller(['stripe_subscription_id' => 'sub_NEW']);

    whSend($this, 'customer.subscription.deleted', ['id' => 'sub_OLD', 'customer' => 'cus_1', 'status' => 'canceled'])->assertOk();
    expect($seller->fresh()->subscription_status)->toBe('active');

    whSend($this, 'customer.subscription.deleted', ['id' => 'sub_NEW', 'customer' => 'cus_1', 'status' => 'canceled'], time() + 10)->assertOk();
    expect($seller->fresh()->subscription_status)->toBe('cancelled');
});

it('maps Stripe subscription statuses', function () {
    $seller = whSeller();
    $t = time();

    whSend($this, 'customer.subscription.updated', ['id' => 'sub_1', 'customer' => 'cus_1', 'status' => 'past_due'], $t)->assertOk();
    expect($seller->fresh()->subscription_status)->toBe('past_due');

    whSend($this, 'customer.subscription.updated', ['id' => 'sub_1', 'customer' => 'cus_1', 'status' => 'active'], $t + 1)->assertOk();
    expect($seller->fresh()->subscription_status)->toBe('active');

    // An unknown status changes nothing rather than guessing.
    whSend($this, 'customer.subscription.updated', ['id' => 'sub_1', 'customer' => 'cus_1', 'status' => 'paused'], $t + 2)->assertOk();
    expect($seller->fresh()->subscription_status)->toBe('active');
});

it('leaves a shopper who is not a seller alone', function () {
    $seller = whSeller();

    whSend($this, 'invoice.payment_failed', ['customer' => 'cus_SHOPPER', 'subscription' => null])->assertOk();

    expect($seller->fresh()->subscription_status)->toBe('active');
    Mail::assertNothingSent();
});
