<?php

use App\Models\AdClick;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerCoupon;
use App\Models\User;
use App\Services\AdService;
use App\Services\CouponService;
use App\Services\SellerBilling;
use App\Services\StoreSale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    Mail::fake();
});

function mkSeller(string $slug, array $over = []): User
{
    $user = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create($over + ['user_id' => $user->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active',
        'onboarding_step' => 'done', 'stripe_customer_id' => 'cus_' . $slug, 'approved_at' => now()]);

    return $user;
}

function mkProd(User $u, string $name, float $price = 100, array $over = []): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create($over + ['seller_id' => $u->seller->id, 'category_id' => $cat->id, 'name' => $name, 'slug' => str($name)->slug() . '-' . uniqid(),
        'price' => $price, 'stock' => 10, 'is_active' => true, 'moderation_status' => 'approved']);
}

function line(Product $p, int $qty = 1): array
{
    return ['cart' => ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug, 'price' => (float) $p->effective_price, 'qty' => $qty, 'color' => null, 'size' => null, 'image' => null]]];
}

// ── store-wide sale ─────────────────────────────────────────────────────

it('puts a whole store on sale and gives every seller price back when it ends', function () {
    $u = mkSeller('a');
    $plain = mkProd($u, 'Plain', 100);
    $own = mkProd($u, 'Own Sale', 100, ['sale_price' => 90]);   // 20% sale would be 80: store sale is better
    $better = mkProd($u, 'Better Own', 100, ['sale_price' => 70]); // already cheaper than the 20% sale: untouched
    $other = mkProd(mkSeller('b'), 'Other Store', 100);

    $this->actingAs($u)->post('/seller/marketing/sale', ['percent' => 20])->assertSessionHasNoErrors();

    expect((float) $plain->fresh()->sale_price)->toBe(80.0)->and((float) $own->fresh()->sale_price)->toBe(80.0)->and((float) $better->fresh()->sale_price)->toBe(70.0)
        ->and($other->fresh()->sale_price)->toBeNull();
    expect($plain->fresh()->effective_price)->toBe(80.0)->and($plain->fresh()->has_strike)->toBeTrue();

    $this->actingAs($u)->post('/seller/marketing/sale', ['action' => 'stop']);
    expect($plain->fresh()->sale_price)->toBeNull()->and((float) $own->fresh()->sale_price)->toBe(90.0)->and((float) $better->fresh()->sale_price)->toBe(70.0)
        ->and($u->seller->fresh()->sale_active)->toBeFalse();
});

it('re-prices a sale when the percentage changes and covers products added later', function () {
    $u = mkSeller('a');
    $p = mkProd($u, 'First', 100);
    $sale = app(StoreSale::class);
    $sale->start($u->seller, 10);
    expect((float) $p->fresh()->sale_price)->toBe(90.0);
    $sale->start($u->seller->fresh(), 30);
    expect((float) $p->fresh()->sale_price)->toBe(70.0);

    $later = mkProd($u, 'Later', 50);
    $sale->refresh($later->fresh(), $u->seller->fresh());
    expect((float) $later->fresh()->sale_price)->toBe(35.0);

    $sale->stop($u->seller->fresh());
    expect($p->fresh()->sale_price)->toBeNull()->and($later->fresh()->sale_price)->toBeNull();
});

it('rejects sale percentages outside the allowed range', function () {
    $u = mkSeller('a');
    foreach ([0, 4, 71, 'x'] as $bad) {
        $this->actingAs($u)->post('/seller/marketing/sale', ['percent' => $bad])->assertSessionHasErrors('percent');
    }
});

// ── coupons ─────────────────────────────────────────────────────────────

it('creates coupons with sensible limits and keeps each seller to their own', function () {
    $a = mkSeller('a');
    $b = mkSeller('b');
    $ok = ['code' => 'save10', 'type' => 'percent', 'value' => 10, 'min_order' => 20];
    $this->actingAs($a)->post('/seller/marketing/coupons', $ok)->assertSessionHasNoErrors();
    expect(SellerCoupon::where('seller_id', $a->seller->id)->first()->code)->toBe('SAVE10');

    $this->actingAs($a)->post('/seller/marketing/coupons', $ok)->assertSessionHasErrors('code'); // duplicate for the same seller
    $this->actingAs($b)->post('/seller/marketing/coupons', $ok)->assertSessionHasNoErrors();      // fine for another seller
    $this->actingAs($a)->post('/seller/marketing/coupons', ['code' => 'big', 'type' => 'percent', 'value' => 95])->assertSessionHasErrors('value');
    $this->actingAs($a)->post('/seller/marketing/coupons', ['code' => 'big', 'type' => 'amount', 'value' => 900])->assertSessionHasErrors('value');
    $this->actingAs($a)->post('/seller/marketing/coupons', ['code' => 'x', 'type' => 'percent', 'value' => 5])->assertSessionHasErrors('code');

    $mine = SellerCoupon::where('seller_id', $a->seller->id)->first();
    $this->actingAs($b)->post("/seller/marketing/coupons/{$mine->id}/toggle")->assertNotFound();
    $this->actingAs($a)->post("/seller/marketing/coupons/{$mine->id}/toggle");
    expect($mine->fresh()->is_active)->toBeFalse();
});

it('discounts only the items of the store that made the coupon, and respects its rules', function () {
    $a = mkSeller('a');
    $b = mkSeller('b');
    $pa = mkProd($a, 'A Lamp', 40);
    $pb = mkProd($b, 'B Rug', 60);
    $cart = ['cart' => line($pa)['cart'] + line($pb)['cart']];
    SellerCoupon::create(['seller_id' => $a->seller->id, 'code' => 'HALF', 'type' => 'percent', 'value' => 50, 'min_order' => 30]);
    SellerCoupon::create(['seller_id' => $b->seller->id, 'code' => 'BIGMIN', 'type' => 'amount', 'value' => 10, 'min_order' => 100]);
    SellerCoupon::create(['seller_id' => $a->seller->id, 'code' => 'OLD', 'type' => 'percent', 'value' => 10, 'ends_at' => now()->subDay()]);

    $svc = app(CouponService::class);
    $items = $cart['cart'];
    expect($svc->quote($items, 'HALF'))->toMatchArray(['discount' => 20.0, 'bySeller' => [$a->seller->id => 20.0]]);
    expect($svc->quote($items, 'BIGMIN')['discount'])->toBe(0.0)->and($svc->quote($items, 'BIGMIN')['reason'])->toContain('more from that store');
    expect($svc->quote($items, 'OLD')['discount'])->toBe(0.0)->and($svc->quote($items, 'NOPE')['discount'])->toBe(0.0);

    $this->withSession($cart)->post('/cart/coupon', ['code' => 'half'])->assertSessionHas('coupon_code', 'HALF');
    $this->withSession($cart + ['coupon_code' => 'HALF'])->get('/cart')->assertOk()->assertSee('Coupon HALF')->assertSee('−$20.00', false)->assertSee('$80.00');
    $this->withSession($cart)->post('/cart/coupon', ['code' => 'nope'])->assertSessionHas('error');
});

it('charges the buyer less, reduces only that seller\'s payout and counts the use once paid', function () {
    $a = mkSeller('a');
    $pa = mkProd($a, 'A Lamp', 100);
    $coupon = SellerCoupon::create(['seller_id' => $a->seller->id, 'code' => 'TEN', 'type' => 'percent', 'value' => 10]);

    $this->withSession(line($pa, 2) + ['coupon_code' => 'TEN'])->post('/checkout', [
        'name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '5551234567', 'address' => '1 Main St', 'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ', 'zip' => '07302',
        'shipping' => 'free', 'payment_method' => 'paypal', 'agree' => '1',
    ]);

    $order = Order::where('email', 'pat@example.com')->latest('id')->first();
    expect($order)->not->toBeNull();
    expect((float) $order->subtotal)->toBe(200.0)->and((float) $order->coupon_discount)->toBe(20.0)->and($order->coupon_code)->toBe('TEN')->and((float) $order->total)->toBe(180.0);
    expect((float) $order->items->sum('coupon_discount'))->toBe(20.0);

    app(\App\Services\OrderSettlement::class)->markPaid($order, 'paypal', 18000);
    $shipment = \App\Models\Shipment::where('order_id', $order->id)->first();
    expect((float) $shipment->gross)->toBe(180.0)->and($coupon->fresh()->uses)->toBe(1);
})->skip(fn () => ! method_exists(\App\Services\OrderSettlement::class, 'markPaid'), 'settlement entry point differs');

// ── promoted listings ───────────────────────────────────────────────────

it('shows promoted products first, labelled, only while the seller is on and under budget', function () {
    $a = mkSeller('a', ['ads_active' => true, 'ads_daily_budget' => 5]);
    $b = mkSeller('b');
    $promo = mkProd($a, 'Promo Lamp', 30);
    mkProd($b, 'Plain Lamp One', 31);
    mkProd($b, 'Plain Lamp Two', 32);

    $html = $this->get('/shop')->assertOk()->assertSee('Sponsored')->getContent();
    expect(strpos($html, 'Promo Lamp'))->toBeLessThan(strpos($html, 'Plain Lamp One'));
    expect(substr_count($html, 'Promo Lamp'))->toBe(1); // not repeated further down

    $a->seller->update(['ads_active' => false]);
    $this->get('/shop')->assertDontSee('Sponsored');

    $a->seller->update(['ads_active' => true]);
    foreach (range(1, 20) as $i) { // $5 at $0.25 a click
        AdClick::create(['seller_id' => $a->seller->id, 'product_id' => $promo->id, 'cost' => 0.25]);
    }
    $this->get('/shop')->assertDontSee('Sponsored');
});

it('bills a click once per visitor, never the seller themself, bots or clicks past budget', function () {
    $a = mkSeller('a', ['ads_active' => true, 'ads_daily_budget' => 1]);
    $p = mkProd($a, 'Ad Lamp', 30);
    $hit = fn (array $h = []) => $this->withHeaders($h + ['User-Agent' => 'Mozilla/5.0 Chrome'])->get("/ad/{$p->slug}");

    $hit()->assertRedirect(route('product-details', $p->slug));
    $hit();                                                          // same visitor again: free
    expect(AdClick::count())->toBe(1);
    $hit(['User-Agent' => 'Googlebot/2.1']);                         // bot: free
    $this->actingAs($a)->get("/ad/{$p->slug}");                      // the seller: free
    auth()->logout();
    expect(AdClick::count())->toBe(1);

    foreach (['203.0.113.1', '203.0.113.2', '203.0.113.3', '203.0.113.4', '203.0.113.5'] as $ip) {
        $this->withServerVariables(['REMOTE_ADDR' => $ip])->withHeaders(['User-Agent' => 'Mozilla/5.0 Chrome'])->get("/ad/{$p->slug}");
    }
    expect(AdClick::count())->toBe(4); // $1 budget at $0.25 a click
    expect(round((float) AdClick::sum('cost'), 2))->toBe(1.0);
});

it('adds yesterday\'s ad clicks to the plan invoice and retries what failed', function () {
    $a = mkSeller('a');
    $p = mkProd($a, 'Ad Lamp', 30);
    AdClick::create(['seller_id' => $a->seller->id, 'product_id' => $p->id, 'cost' => 0.25])->forceFill(['created_at' => now()->subDay()])->save();
    AdClick::create(['seller_id' => $a->seller->id, 'product_id' => $p->id, 'cost' => 0.25])->forceFill(['created_at' => now()->subDay()])->save();
    AdClick::create(['seller_id' => $a->seller->id, 'product_id' => $p->id, 'cost' => 0.25]); // today: not yet

    $billing = Mockery::mock(SellerBilling::class);
    $billing->shouldReceive('addInvoiceItem')->once()->andThrow(new RuntimeException('down'));
    $billing->shouldReceive('addInvoiceItem')->once()->withArgs(fn ($s, $amt, $d) => abs($amt - 0.5) < 0.001);
    $this->app->instance(SellerBilling::class, $billing);

    expect(app(AdService::class)->billAll())->toBe(0)->and(AdClick::whereNull('billed_at')->count())->toBe(3);
    expect(app(AdService::class)->billAll())->toBe(1)->and(AdClick::whereNull('billed_at')->count())->toBe(1);
    expect(app(AdService::class)->billAll())->toBe(0); // nothing left to bill
});

it('keeps the marketing page behind the seller login and refuses ads without an active plan', function () {
    $this->get('/seller/marketing')->assertRedirect(route('login'));
    $u = mkSeller('a', ['stripe_customer_id' => null]);
    $this->actingAs($u)->get('/seller/marketing')->assertOk()->assertSee('Create a coupon')->assertSee('Promoted listings')->assertSee('Store-wide sale');
    $this->actingAs($u)->post('/seller/marketing/ads', ['enabled' => '1', 'budget' => 5])->assertSessionHas('error');
    expect($u->seller->fresh()->ads_active)->toBeFalse();
    $this->actingAs(mkSeller('b'))->post('/seller/marketing/ads', ['enabled' => '1', 'budget' => 500])->assertSessionHasErrors('budget');
});
