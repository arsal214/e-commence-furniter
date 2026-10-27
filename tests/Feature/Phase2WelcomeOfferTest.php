<?php

use App\Mail\OrderConfirmationMail;
use App\Mail\WelcomeOfferMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\WelcomeClaim;
use App\Models\WelcomeOffer;
use App\Services\CartService;
use App\Services\WelcomeOfferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function p2Offer(bool $active = true): WelcomeOffer
{
    $offer = WelcomeOffer::create(['name' => 'Welcome', 'is_active' => $active, 'expiry_hours' => 72]);
    $offer->tiers()->create(['type' => 'fixed_amount', 'amount' => 5, 'min_subtotal' => 35, 'sort_order' => 1]);
    $offer->tiers()->create(['type' => 'fixed_amount', 'amount' => 10, 'min_subtotal' => 60, 'sort_order' => 2]);
    $offer->tiers()->create(['type' => 'free_shipping', 'amount' => 0, 'min_subtotal' => 0, 'sort_order' => 3]);

    return $offer;
}

function p2Product(float $price = 40.00, string $slug = 'lamp'): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create([
        'category_id' => $cat->id, 'name' => ucfirst($slug), 'slug' => $slug,
        'price' => $price, 'stock' => 20, 'is_active' => true,
    ]);
}

function p2Cart(Product $p, int $qty = 1): array
{
    return ['cart' => ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug,
        'price' => (float) $p->price, 'sku' => null, 'image' => null, 'qty' => $qty, 'color' => null, 'size' => null]]];
}

function p2Form(array $over = []): array
{
    return $over + [
        'name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '5551234567',
        'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ', 'zip' => '07302',
        'address' => '1 Main St', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1',
    ];
}

function p2PaidOrder(string $email, string $address = '9 Other Rd'): Order
{
    return Order::create([
        'name' => 'Old', 'email' => $email, 'phone' => '1', 'country' => 'US', 'address' => $address, 'zip' => '07302',
        'payment_method' => 'stripe', 'payment_status' => 'paid', 'shipping' => 'free', 'subtotal' => 10, 'shipping_cost' => 0,
        'total' => 10, 'status' => 'processing', 'tracking_number' => 'T-' . uniqid(),
    ]);
}

it('lets a new email claim with a real 72h expiry and emails the deal', function () {
    Mail::fake();
    p2Offer();

    $this->postJson(route('welcome.claim'), ['email' => 'Pat@Example.com'])
        ->assertOk()->assertJsonPath('claim.email', 'pat@example.com')->assertJsonPath('enabled', true);

    $claim = WelcomeClaim::first();
    expect($claim->expires_at->diffInHours($claim->claimed_at, true))->toBeBetween(71.9, 72.1);
    Mail::assertSent(WelcomeOfferMail::class, 1);
});

it('does not create a second claim or extend the expiry when the same inbox claims again', function () {
    Mail::fake();
    p2Offer();
    $this->postJson(route('welcome.claim'), ['email' => 'pat.buyer+a@gmail.com'])->assertOk();
    $first = WelcomeClaim::first();
    $this->travel(30)->hours();

    $this->postJson(route('welcome.claim'), ['email' => 'patbuyer@gmail.com'])->assertOk()->assertJsonPath('created', false);

    expect(WelcomeClaim::count())->toBe(1)->and(WelcomeClaim::first()->expires_at->equalTo($first->expires_at))->toBeTrue();
});

it('refuses disposable inboxes and customers who already paid', function () {
    p2Offer();
    p2PaidOrder('old@example.com');

    $this->postJson(route('welcome.claim'), ['email' => 'x@mailinator.com'])->assertStatus(422);
    $this->postJson(route('welcome.claim'), ['email' => 'old@example.com'])->assertStatus(422);
    expect(WelcomeClaim::count())->toBe(0);
});

it('applies the right tier by subtotal: 34.99 none, 35 gets $5, 60 gets $10 not $5', function () {
    $offer = p2Offer();
    $svc = app(WelcomeOfferService::class);

    expect($svc->bestTierFor($offer->fresh('tiers'), 34.99)['discount'])->toBe(0.0)
        ->and($svc->bestTierFor($offer->fresh('tiers'), 35.00)['discount'])->toBe(5.0)
        ->and($svc->bestTierFor($offer->fresh('tiers'), 60.00)['discount'])->toBe(10.0);
});

it('never lets a discount exceed the goods', function () {
    $offer = p2Offer();
    $offer->tiers()->update(['min_subtotal' => 0]);

    expect(app(WelcomeOfferService::class)->bestTierFor($offer->fresh('tiers'), 3.00)['discount'])->toBe(3.0);
});

it('discounts the order server-side and ignores tampered form fields', function () {
    Mail::fake();
    p2Offer();
    $item = p2Product(40.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);

    $this->withSession(p2Cart($item) + ['welcome_claim_id' => WelcomeClaim::first()->id])
        ->post(route('checkout.store'), p2Form(['total' => '0.01', 'discount' => '999', 'subtotal' => '1', 'price' => '0.01']))
        ->assertOk();

    $order = Order::latest('id')->first();
    expect((float) $order->subtotal)->toBe(40.0)->and((float) $order->discount)->toBe(5.0)->and((float) $order->total)->toBe(35.0);
    // The claim is spent when payment settles, not when the order is placed.
    expect(WelcomeClaim::first()->used_order_id)->toBeNull();
    payLatestOrder($this);
    expect(WelcomeClaim::first()->used_order_id)->toBe($order->id);
});

it('gives no discount below the minimum, to unclaimed emails, or when the offer is off', function () {
    Mail::fake();
    $offer = p2Offer();
    $cheap = p2Product(20.00, 'cheap');
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);

    $this->withSession(p2Cart($cheap))->post(route('checkout.store'), p2Form());
    expect((float) Order::latest('id')->first()->discount)->toBe(0.0);

    $item = p2Product(40.00);
    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form(['email' => 'stranger@example.com']));
    expect((float) Order::latest('id')->first()->discount)->toBe(0.0);

    $offer->update(['is_active' => false]);
    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form());
    expect((float) Order::latest('id')->first()->discount)->toBe(0.0);
    $this->getJson(route('welcome.status'))->assertJsonPath('enabled', false);
});

it('rejects an expired claim at checkout', function () {
    Mail::fake();
    p2Offer();
    $item = p2Product(40.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);
    $this->travel(73)->hours();

    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form());

    expect((float) Order::latest('id')->first()->discount)->toBe(0.0);
});

it('blocks a customer who paid before, even with a claim made earlier', function () {
    Mail::fake();
    p2Offer();
    $item = p2Product(40.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);
    p2PaidOrder('pat@example.com');

    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form());

    expect((float) Order::latest('id')->first()->discount)->toBe(0.0);
});

it('blocks the same normalized address under a different email', function () {
    Mail::fake();
    p2Offer();
    $item = p2Product(40.00);

    $this->postJson(route('welcome.claim'), ['email' => 'one@example.com']);
    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form(['email' => 'one@example.com', 'address' => '12 Elm Street', 'address2' => 'Apt 4']));
    expect((float) Order::latest('id')->first()->discount)->toBe(5.0);
    payLatestOrder($this);   // the address only counts as used once that order is paid

    $this->postJson(route('welcome.claim'), ['email' => 'two@example.com']);
    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form(['email' => 'two@example.com', 'address' => '12 ELM street.', 'address2' => '#4']));
    expect((float) Order::latest('id')->first()->discount)->toBe(0.0);
});

it('re-reads cart prices from the database', function () {
    $item = p2Product(40.00);
    $this->withSession(p2Cart($item));
    $item->update(['price' => 55.00]);

    expect(app(CartService::class)->total())->toBe(55.0);
});

function p2Webhook($test, array $payload, ?string $secret = 'whsec_test', ?string $sig = null)
{
    $body = json_encode($payload);
    $t = time();
    $sig ??= "t={$t},v1=" . hash_hmac('sha256', "{$t}.{$body}", (string) $secret);

    return $test->call('POST', '/stripe/webhook', [], [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_STRIPE_SIGNATURE' => $sig], $body);
}

function p2StripeEvent(string $intentId, int $amount): array
{
    return ['id' => 'evt_' . uniqid(), 'object' => 'event', 'type' => 'payment_intent.succeeded', 'livemode' => false,
        'data' => ['object' => ['id' => $intentId, 'object' => 'payment_intent', 'amount' => $amount, 'status' => 'succeeded']]];
}

it('marks a Stripe order paid once, spends the claim once, and survives webhook replay', function () {
    Mail::fake();
    config(['services.stripe.webhook_secret' => 'whsec_test', 'services.stripe.secret' => null]);
    $offer = p2Offer();
    $claim = WelcomeClaim::create(['welcome_offer_id' => $offer->id, 'email' => 'pat@example.com', 'email_key' => 'pat@example.com',
        'code' => 'WELCOME-AAAAAA', 'claimed_at' => now(), 'expires_at' => now()->addDay()]);
    $order = Order::create([
        'name' => 'Pat', 'email' => 'pat@example.com', 'phone' => '1', 'country' => 'US', 'address' => '1 Main St', 'zip' => '07302',
        'payment_method' => 'stripe', 'payment_status' => 'pending', 'shipping' => 'free', 'subtotal' => 40, 'shipping_cost' => 0,
        'discount' => 5, 'welcome_claim_id' => $claim->id, 'total' => 35, 'status' => 'pending',
        'tracking_number' => 'T-1', 'stripe_payment_intent' => 'pi_123',
    ]);

    $event = p2StripeEvent('pi_123', 3500);
    p2Webhook($this, $event)->assertOk();
    p2Webhook($this, $event)->assertOk(); // Stripe retries / replay

    expect($order->fresh()->payment_status)->toBe('paid')
        ->and($claim->fresh()->used_order_id)->toBe($order->id)
        ->and((float) $claim->fresh()->discount_given)->toBe(5.0);
    Mail::assertSent(OrderConfirmationMail::class, 1);
});

it('does not mark an order paid when the provider amount differs from the recomputed total', function () {
    Mail::fake();
    config(['services.stripe.webhook_secret' => 'whsec_test', 'services.stripe.secret' => null]);
    $order = Order::create([
        'name' => 'Pat', 'email' => 'pat@example.com', 'phone' => '1', 'country' => 'US', 'address' => '1 Main St',
        'payment_method' => 'stripe', 'payment_status' => 'pending', 'shipping' => 'free', 'subtotal' => 40, 'shipping_cost' => 0,
        'total' => 40, 'status' => 'pending', 'tracking_number' => 'T-2', 'stripe_payment_intent' => 'pi_456',
    ]);

    p2Webhook($this, p2StripeEvent('pi_456', 100))->assertOk();

    expect($order->fresh()->payment_status)->toBe('pending');
    Mail::assertNothingSent();
});

it('rejects unsigned or badly signed webhooks, and refuses all when no secret is set', function () {
    config(['services.stripe.webhook_secret' => 'whsec_test']);
    p2Webhook($this, p2StripeEvent('pi_1', 100), 'whsec_test', 't=1,v1=bad')->assertStatus(400);

    config(['services.stripe.webhook_secret' => null]);
    p2Webhook($this, p2StripeEvent('pi_1', 100))->assertStatus(503);
});

it('shows nothing and applies nothing when the offer is switched off', function () {
    p2Offer(false);

    $this->getJson(route('welcome.status'))->assertJsonPath('enabled', false);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com'])->assertStatus(422);
});

it('hides the offer from a logged-in customer who already paid', function () {
    p2Offer();
    $user = User::create(['name' => 'Old', 'email' => 'old@example.com', 'password' => 'x', 'role' => 'customer']);
    p2PaidOrder('old@example.com');

    $this->actingAs($user)->getJson(route('welcome.status'))->assertJsonPath('enabled', false);
});

it('rate-limits claims per IP', function () {
    Mail::fake();
    p2Offer();

    foreach (range(1, 5) as $i) {
        $this->postJson(route('welcome.claim'), ['email' => "u{$i}@example.com"])->assertOk();
    }
    $this->postJson(route('welcome.claim'), ['email' => 'u6@example.com'])->assertStatus(429);
});

it('sends guests from the cart straight to checkout, with no sign-in wall', function () {
    $item = p2Product(40.00);

    $html = $this->withSession(p2Cart($item))->get('/cart')->assertOk()->getContent();

    expect($html)->not->toContain('Sign in to check out')->and($html)->toContain(url('/checkout'));
});

it('shows the welcome deal as a line in the cart and checkout totals', function () {
    Mail::fake();
    p2Offer();
    $item = p2Product(40.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);
    $session = p2Cart($item) + ['welcome_claim_id' => WelcomeClaim::first()->id];

    $this->withSession($session)->get('/cart')->assertOk()->assertSee('Welcome deal')->assertSee('35.00');
    $this->withSession($session)->get('/checkout')->assertOk()->assertSee('Welcome deal (pat@example.com)')->assertSee('value="pat@example.com"', false);
});

it('reports cart progress toward the next tier from the server', function () {
    Mail::fake();
    p2Offer();
    $item = p2Product(40.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);

    $this->withSession(p2Cart($item) + ['welcome_claim_id' => WelcomeClaim::first()->id])
        ->getJson(route('welcome.status'))
        ->assertJsonPath('cart.discount', 5)->assertJsonPath('cart.next.need', 20)->assertJsonPath('cart.next.discount', 10);
});

it('hides the free-shipping ticket until shipping has a fee', function () {
    p2Offer();

    expect(collect($this->getJson(route('welcome.status'))->json('tiers'))->pluck('type')->all())->not->toContain('free_shipping');

    config(['checkout.shipping_fee_enabled' => true]);
    expect(collect($this->getJson(route('welcome.status'))->json('tiers'))->pluck('type')->all())->toContain('free_shipping');
});

it('renders the offer widget and the product-page placeholder', function () {
    p2Offer();
    $item = p2Product(40.00, 'widget-lamp');

    $this->get(route('product-details', $item->slug))->assertOk()->assertSee('data-welcome-product', false)->assertSee('/welcome/claim', false);
    $this->get('/checkout')->assertRedirect(); // empty cart; widget not needed there
});

it('lets an admin save the offer and its tiers, and snapshots the tier on redemption', function () {
    Mail::fake();
    $offer = p2Offer(false);
    $admin = User::create(['name' => 'A', 'email' => 'admin@example.com', 'password' => 'x', 'role' => 'admin']);

    $this->actingAs($admin)->get(route('admin.welcome-offer.index'))->assertOk()->assertSee('Welcome Offer');
    $this->actingAs($admin)->put(route('admin.welcome-offer.update'), [
        'name' => 'Welcome', 'is_active' => 1, 'expiry_hours' => 48,
        'tiers' => [['type' => 'fixed_amount', 'amount' => 7, 'min_subtotal' => 30]],
    ])->assertRedirect(route('admin.welcome-offer.index'));

    expect($offer->fresh()->is_active)->toBeTrue()->and($offer->fresh()->expiry_hours)->toBe(48)->and($offer->tiers()->count())->toBe(1);

    auth()->logout();
    $item = p2Product(40.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);
    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form());
    payLatestOrder($this);
    expect(WelcomeClaim::first()->used_tier_label)->toBe('$7 off orders $30+');
});

it('blocks non-admins from the welcome offer screen', function () {
    p2Offer();

    $this->get(route('admin.welcome-offer.index'))->assertRedirect();
});

it('does not accept cash on delivery: an order cannot be placed without paying', function () {
    $item = p2Product(40.00);

    $this->withSession(p2Cart($item))->post(route('checkout.store'), p2Form(['payment_method' => 'cod']))->assertSessionHasErrors('payment_method');

    expect(Order::count())->toBe(0);
});
