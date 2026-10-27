<?php

use App\Models\Category;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ReferralService;
use App\Services\SellerBilling;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function siSeller(string $slug, array $over = []): User
{
    $user = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create($over + ['user_id' => $user->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active',
        'onboarding_step' => 'done', 'stripe_customer_id' => 'cus_' . $slug, 'approved_at' => now()]);

    return $user;
}

function siProduct(User $u, string $name = 'Mug'): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create(['seller_id' => $u->seller->id, 'category_id' => $cat->id, 'name' => $name, 'slug' => str($name)->slug() . '-' . uniqid(), 'price' => 20, 'stock' => 5, 'is_active' => true, 'moderation_status' => 'approved']);
}

function siReview(Product $p, int $rating, string $text = 'Nice'): Review
{
    return Review::create(['product_id' => $p->id, 'reviewer_name' => 'Pat', 'rating' => $rating, 'comment' => $text, 'is_verified' => true]);
}

// ── performance ─────────────────────────────────────────────────────────

it('shows a seller their own rating, health and reviews, and nobody else\'s', function () {
    $a = siSeller('a');
    $b = siSeller('b');
    siReview(siProduct($a, 'Mine'), 5, 'Loved it');
    siReview(siProduct($a, 'Mine Two'), 3, 'Okay');
    siReview(siProduct($b, 'Theirs'), 1, 'Secret complaint');

    $this->get('/seller/performance')->assertRedirect(route('login'));
    $this->actingAs($a)->get('/seller/performance')->assertOk()->assertSee('4 ★')->assertSee('Loved it')->assertSee('Account health')->assertDontSee('Secret complaint');
    $this->actingAs($a)->get('/seller/performance?filter=low')->assertSee('Okay')->assertDontSee('Loved it');
});

it('measures on-time shipping and disputes over the last 90 days', function () {
    $u = siSeller('a', ['handling_days' => 2]);
    $buyer = User::factory()->create();
    $mk = function (int $createdDaysAgo, ?int $shippedAfterDays) use ($u, $buyer) {
        $o = Order::forceCreate(['user_id' => $buyer->id, 'name' => 'x', 'email' => 'x@x.com', 'phone' => '1', 'address' => 'a', 'city' => 'c', 'state' => 'NJ', 'zip' => '07302', 'country' => 'US',
            'subtotal' => 20, 'total' => 20, 'payment_method' => 'stripe', 'payment_status' => 'paid', 'status' => 'processing', 'tracking_number' => 'FRN-' . uniqid(), 'shipping' => 'free', 'created_at' => now()->subDays($createdDaysAgo)]);

        return Shipment::forceCreate(['order_id' => $o->id, 'seller_id' => $u->seller->id, 'status' => $shippedAfterDays === null ? 'pending' : 'shipped', 'gross' => 20, 'net' => 20,
            'shipped_at' => $shippedAfterDays === null ? null : now()->subDays($createdDaysAgo)->addDays($shippedAfterDays), 'created_at' => now()->subDays($createdDaysAgo)]);
    };
    $mk(10, 1);      // on time
    $mk(10, 5);      // late
    $mk(10, null);   // never shipped, past due: late
    $mk(0, null);    // not due yet: ignored
    Dispute::forceCreate(['order_id' => Shipment::first()->order_id, 'seller_id' => $u->seller->id, 'reason' => 'damaged', 'details' => 'x', 'amount' => 20, 'status' => 'open']);

    $this->actingAs($u)->get('/seller/performance')->assertOk()->assertSee('33.3%')->assertSee('Needs attention');
});

it('lets a seller answer a review of their own product, and shoppers see the reply', function () {
    $a = siSeller('a');
    $b = siSeller('b');
    $p = siProduct($a, 'Reply Mug');
    $r = siReview($p, 2, 'Arrived late');

    $this->actingAs($b)->post("/seller/reviews/{$r->id}/reply", ['reply' => 'hijack'])->assertNotFound();
    $this->actingAs($a)->post("/seller/reviews/{$r->id}/reply", ['reply' => ''])->assertSessionHasErrors('reply');
    $this->actingAs($a)->post("/seller/reviews/{$r->id}/reply", ['reply' => 'Sorry about that, we fixed our carrier.'])->assertSessionHasNoErrors();

    expect($r->fresh()->seller_reply)->toBe('Sorry about that, we fixed our carrier.');
    $this->actingAs($a)->get('/seller/performance')->assertSee('Your public reply');
    auth()->logout();
    $this->get(route('product-details', $p->slug))->assertOk()->assertSee('Reply from A:')->assertSee('we fixed our carrier');
});

// ── referrals ───────────────────────────────────────────────────────────

it('gives each seller a stable invite link and lists who used it', function () {
    $a = siSeller('a');
    $page = $this->actingAs($a)->get('/seller/referrals')->assertOk()->assertSee('Invite a seller, get a month free');
    $code = $a->seller->fresh()->referral_code;
    expect($code)->toMatch('/^[A-Z0-9]{8}$/');
    $page->assertSee("/sell?ref={$code}", false);
    $this->actingAs($a)->get('/seller/referrals')->assertSee($code);
    expect($a->seller->fresh()->referral_code)->toBe($code);
});

it('remembers an invite link and attaches it to the sign-up that follows, ignoring bad codes', function () {
    $inviter = siSeller('inviter');
    $code = app(ReferralService::class)->codeFor($inviter->seller);

    $this->get('/sell?ref=NOTACODE1')->assertOk()->assertCookieMissing(ReferralService::COOKIE);
    $this->get("/sell?ref={$code}")->assertOk()->assertCookie(ReferralService::COOKIE);

    $this->withCookie(ReferralService::COOKIE, $code)->post('/sell/signup', ['first_name' => 'New', 'last_name' => 'Biz', 'email' => 'new@biz.com', 'phone' => '(929) 469-9864', 'password' => 'LongEnough#2026', 'agree' => '1']);
    expect(Seller::whereHas('user', fn ($q) => $q->where('email', 'new@biz.com'))->first()->referred_by)->toBe($inviter->seller->id);
});

it('credits one free month once the invited business is approved and has paid, never twice', function () {
    $billing = Mockery::mock(SellerBilling::class);
    $billing->shouldReceive('creditAmount')->once()->withArgs(fn ($seller, $amt, $why) => $seller->slug === 'inviter' && abs($amt - 19.99) < 0.001);
    $this->app->instance(SellerBilling::class, $billing);

    $inviter = siSeller('inviter');
    $svc = app(ReferralService::class);
    $referred = siSeller('newbie', ['referred_by' => $inviter->seller->id, 'status' => 'in_review', 'subscription_status' => 'active'])->seller;

    expect($svc->creditFor($referred))->toBeFalse(); // not approved yet
    $referred->update(['status' => 'approved', 'subscription_status' => 'past_due']);
    expect($svc->creditFor($referred->fresh()))->toBeFalse(); // not paid
    $referred->update(['subscription_status' => 'active']);
    expect($svc->creditFor($referred->fresh()))->toBeTrue()->and($referred->fresh()->referral_credited_at)->not->toBeNull();
    expect($svc->creditFor($referred->fresh()))->toBeFalse(); // already credited
});

it('leaves the referral uncredited when Stripe fails, so a later try can finish it', function () {
    $billing = Mockery::mock(SellerBilling::class);
    $billing->shouldReceive('creditAmount')->once()->andThrow(new RuntimeException('stripe down'));
    $this->app->instance(SellerBilling::class, $billing);
    $inviter = siSeller('inviter');
    $one = siSeller('one', ['referred_by' => $inviter->seller->id])->seller;

    expect(app(ReferralService::class)->creditFor($one))->toBeFalse()->and($one->fresh()->referral_credited_at)->toBeNull();
});

it('stops at twelve free months a year', function () {
    $billing = Mockery::mock(SellerBilling::class);
    $billing->shouldNotReceive('creditAmount');
    $this->app->instance(SellerBilling::class, $billing);
    $inviter = siSeller('inviter');
    foreach (range(1, 12) as $i) {
        siSeller("done{$i}", ['referred_by' => $inviter->seller->id, 'referral_credited_at' => now()->subDay()]);
    }
    $next = siSeller('next', ['referred_by' => $inviter->seller->id])->seller;

    $svc = app(ReferralService::class);
    expect($svc->usedThisYear($inviter->seller))->toBe(12)->and($svc->creditFor($next))->toBeFalse();
});
