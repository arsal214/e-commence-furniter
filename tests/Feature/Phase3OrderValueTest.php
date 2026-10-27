<?php

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\WelcomeClaim;
use App\Models\WelcomeOffer;
use App\Services\CartService;
use App\Services\ShippingService;
use App\Services\SoldCounts;
use Database\Seeders\BundleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function p3Product(string $name, float $price, array $over = []): Product
{
    $cat = Category::firstOrCreate(['slug' => $over['cat'] ?? 'home'], ['name' => ucfirst($over['cat'] ?? 'home'), 'is_active' => true]);
    unset($over['cat']);

    return Product::create($over + [
        'category_id' => $cat->id, 'name' => $name, 'slug' => \Illuminate\Support\Str::slug($name),
        'price' => $price, 'stock' => 10, 'is_active' => true,
    ]);
}

function p3Line(Product $p, int $qty = 1): array
{
    return ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug,
        'price' => (float) $p->price, 'sku' => null, 'image' => null, 'qty' => $qty, 'color' => null, 'size' => null]];
}

function p3Form(array $over = []): array
{
    return $over + [
        'name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '5551234567',
        'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ', 'zip' => '07302',
        'address' => '1 Main St', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1',
    ];
}

function p3ShippingOn(): void
{
    config(['checkout.shipping_fee_enabled' => true, 'checkout.free_shipping_threshold' => 35, 'checkout.flat_shipping_rate' => 4.99]);
}

function p3Bundle(array $products, float $price, bool $active = true): Bundle
{
    $b = Bundle::create(['name' => 'Cozy Set ' . uniqid(), 'price' => $price, 'is_active' => $active]);
    foreach ($products as $p) {
        $b->items()->create(['product_id' => $p->id, 'qty' => 1]);
    }

    return $b;
}

// ── 3.1 shipping threshold ───────────────────────────────────────────────────────────────

it('keeps shipping free on every order while the flag is off', function () {
    $svc = app(ShippingService::class);

    expect($svc->cost(5.00))->toBe(0.0)->and($svc->remaining(5.00))->toBeNull();
});

it('charges the flat rate under the threshold and nothing at or above it', function () {
    p3ShippingOn();
    $svc = app(ShippingService::class);

    expect($svc->cost(34.99))->toBe(4.99)->and($svc->cost(35.00))->toBe(0.0)->and($svc->cost(80.00))->toBe(0.0)
        ->and($svc->remaining(26.49))->toBe(8.51)->and($svc->remaining(35.00))->toBeNull();
});

it('puts the flat rate into the order total under the threshold and not above it', function () {
    Mail::fake();
    p3ShippingOn();
    $cheap = p3Product('Cheap Lamp', 20.00);
    $big   = p3Product('Big Lamp', 40.00);

    $this->withSession(['cart' => p3Line($cheap)])->post(route('checkout.store'), p3Form());
    $o = Order::latest('id')->first();
    expect((float) $o->shipping_cost)->toBe(4.99)->and((float) $o->total)->toBe(24.99);

    $this->withSession(['cart' => p3Line($big)])->post(route('checkout.store'), p3Form());
    $o = Order::latest('id')->first();
    expect((float) $o->shipping_cost)->toBe(0.0)->and((float) $o->total)->toBe(40.0);
});

it('judges the threshold before the welcome discount', function () {
    Mail::fake();
    p3ShippingOn();
    $offer = WelcomeOffer::create(['name' => 'W', 'is_active' => true, 'expiry_hours' => 72]);
    $offer->tiers()->create(['type' => 'fixed_amount', 'amount' => 5, 'min_subtotal' => 35, 'sort_order' => 1]);
    $item = p3Product('Exactly 35', 35.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);

    $this->withSession(['cart' => p3Line($item)])->post(route('checkout.store'), p3Form());

    $o = Order::latest('id')->first();
    expect((float) $o->shipping_cost)->toBe(0.0)->and((float) $o->discount)->toBe(5.0)->and((float) $o->total)->toBe(30.0);
});

it('shows the threshold message in the cart and the flat rate in the total', function () {
    p3ShippingOn();
    $item = p3Product('Lamp', 26.49);

    $this->withSession(['cart' => p3Line($item)])->get('/cart')->assertOk()
        ->assertSee('$8.51 away from free shipping')->assertSee('31.48');
});

// ── 3.2 add-on items ─────────────────────────────────────────────────────────────────────

it('refuses checkout for an add-on-only cart under the threshold, and allows it once the threshold is met', function () {
    p3ShippingOn();
    $addon = p3Product('Nasal Strips', 6.00, ['is_addon' => true]);
    $main  = p3Product('Throw Blanket', 40.00);

    $this->withSession(['cart' => p3Line($addon)])->post(route('checkout.store'), p3Form())->assertRedirect(route('cart'));
    expect(Order::count())->toBe(0);
    $this->withSession(['cart' => p3Line($addon)])->get('/checkout')->assertRedirect(route('cart'));
    $this->withSession(['cart' => p3Line($addon)])->get('/cart')->assertSee('Add-on items ship free with orders of $35 or more');

    $this->withSession(['cart' => p3Line($addon, 1) + p3Line($main)])->post(route('checkout.store'), p3Form())->assertOk();
    expect(Order::count())->toBe(1);
});

it('lets add-on-only carts through when add-on shipping rules are off', function () {
    $addon = p3Product('Nasal Strips', 6.00, ['is_addon' => true]);

    $this->withSession(['cart' => p3Line($addon)])->post(route('checkout.store'), p3Form())->assertOk();
});

it('lets an admin mark a product as an add-on', function () {
    $admin = User::create(['name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'role' => 'admin']);
    $p = p3Product('Strips', 6.00);

    $this->actingAs($admin)->get(route('admin.products.edit', $p))->assertOk()->assertSee('name="is_addon"', false);
});

// ── 3.3 bundles ──────────────────────────────────────────────────────────────────────────

it('sells a bundle only while every component is active and in stock', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);

    expect($bundle->fresh('items.product.category')->isPurchasable())->toBeTrue()
        ->and($bundle->savings())->toBe(6.0);

    $b->update(['stock' => 0]);
    expect($bundle->fresh('items.product.category')->isPurchasable())->toBeFalse();

    $b->update(['stock' => 3, 'is_active' => false]);
    expect($bundle->fresh('items.product.category')->isPurchasable())->toBeFalse();
});

it('adds a bundle to the cart at the database price and ignores a tampered one', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);

    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id, 'price' => '0.01'])->assertRedirect(route('cart'));
    expect(app(CartService::class)->total())->toBe(53.99);

    $bundle->update(['price' => 50.00]);
    expect(app(CartService::class)->total())->toBe(50.0);
});

it('refuses an inactive or out-of-stock bundle', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $off = p3Bundle([$a, $b], 53.99, false);

    $this->post(route('cart.add-bundle'), ['bundle_id' => $off->id])->assertSessionHas('error');
    expect(app(CartService::class)->count())->toBe(0);

    $on = p3Bundle([$a, $b], 53.99);
    $b->update(['stock' => 0]);
    $this->post(route('cart.add-bundle'), ['bundle_id' => $on->id])->assertSessionHas('error');
});

it('drops a bundle from the cart when a component sells out', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);
    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id]);

    $b->update(['stock' => 0]);

    expect(app(CartService::class)->items())->toBe([]);
});

it('expands a bundle into its products on the order, adding up to exactly what was charged', function () {
    Mail::fake();
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $c = p3Product('Vase', 10.01);
    $bundle = p3Bundle([$a, $b, $c], 59.99);

    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id]);
    $this->post(route('checkout.store'), p3Form())->assertOk();

    $order = Order::latest('id')->first();
    $lines = OrderItem::where('order_id', $order->id)->get();
    expect($lines)->toHaveCount(3)
        ->and(round($lines->sum('total'), 2))->toBe(59.99)
        ->and((float) $order->total)->toBe(59.99)
        ->and($lines->pluck('product_id')->sort()->values()->all())->toBe(collect([$a->id, $b->id, $c->id])->sort()->values()->all())
        ->and($lines->every(fn ($l) => $l->bundle_id === $bundle->id))->toBeTrue();
});

it('offers the set on a component product page and in the cart, only when it really saves money', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);

    $this->get(route('product-details', $a->slug))->assertOk()->assertSee('Complete the set &mdash; save $6.00', false);
    $this->withSession(['cart' => p3Line($a)])->get('/cart')->assertOk()->assertSee('Complete the set');

    $bundle->update(['price' => 59.99]); // no saving at all: do not call it one
    $this->get(route('product-details', $a->slug))->assertDontSee('Complete the set &mdash; save', false);
});

it('seeds the launch bundles inactive, and skips a set whose parts cannot be found', function () {
    p3Product('10-in-1 Electric STEM Kit', 29.50);
    p3Product('GILOBABY Take-Apart Set', 28.99);

    (new BundleSeeder())->run();

    expect(Bundle::count())->toBe(1)->and(Bundle::first()->name)->toBe('Little Builder STEM Set')->and(Bundle::first()->is_active)->toBeFalse()
        ->and((float) Bundle::first()->price)->toBe(52.99);
});

it('will not let an admin switch on a set priced at or above its parts', function () {
    $admin = User::create(['name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'role' => 'admin']);
    $bundle = p3Bundle([p3Product('Throw', 45.00), p3Product('Sand Art', 14.99)], 53.99, false);

    $this->actingAs($admin)->get(route('admin.bundles.index'))->assertOk()->assertSee($bundle->name);
    $this->actingAs($admin)->put(route('admin.bundles.update', $bundle), ['price' => 60, 'is_active' => 1])->assertSessionHasErrors('price');
    $this->actingAs($admin)->put(route('admin.bundles.update', $bundle), ['price' => 50, 'is_active' => 1]);

    expect($bundle->fresh()->is_active)->toBeTrue();
});

// ── 3.4 relevant cross-sells ─────────────────────────────────────────────────────────────

it('shows only same-category products as related, and hides the rail when there are none', function () {
    $main = p3Product('Throw', 45.00, ['cat' => 'home']);
    $same = p3Product('Cushion', 20.00, ['cat' => 'home']);
    $other = p3Product('Action Camera', 99.00, ['cat' => 'electronics']);

    $html = $this->get(route('product-details', $main->slug))->assertOk()->getContent();
    expect($html)->toContain('Related Products')->and($html)->toContain('Cushion')->and($html)->not->toContain('Action Camera');

    $same->update(['is_active' => false]);
    $this->get(route('product-details', $main->slug))->assertDontSee('Related Products');
});

it('does not pad the cart tray with products from other categories', function () {
    $main = p3Product('Throw', 45.00, ['cat' => 'home']);
    p3Product('Action Camera', 99.00, ['cat' => 'electronics']);

    $this->withSession(['cart' => p3Line($main)])->get('/cart')->assertOk()->assertDontSee('Action Camera');
});

// ── 3.5 sold badge ───────────────────────────────────────────────────────────────────────

function p3SoldOrder(Product $p, int $qty, string $payment = 'paid'): void
{
    $o = Order::create([
        'name' => 'x', 'email' => uniqid() . '@example.com', 'phone' => '1', 'country' => 'US', 'address' => '1 St',
        'payment_method' => 'stripe', 'payment_status' => $payment, 'shipping' => 'free', 'subtotal' => 1, 'shipping_cost' => 0,
        'total' => 1, 'status' => 'processing', 'tracking_number' => 'T-' . uniqid(),
    ]);
    OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'name' => $p->name, 'price' => 1, 'qty' => $qty, 'total' => $qty]);
}

it('shows "N sold" only when the flag is on, the orders are paid, and the count reaches the minimum', function () {
    $p = p3Product('Popular Lamp', 20.00);
    p3SoldOrder($p, 12);
    p3SoldOrder($p, 30, 'pending'); // unpaid orders never count
    SoldCounts::flush();

    expect(SoldCounts::label($p->id))->toBeNull(); // flag off

    config(['checkout.sold_badge_enabled' => true]);
    SoldCounts::flush();
    expect(SoldCounts::label($p->id))->toBe('12 sold in the last 30 days');

    $few = p3Product('Quiet Lamp', 20.00);
    p3SoldOrder($few, 9);
    SoldCounts::flush();
    expect(SoldCounts::label($few->id))->toBeNull();

    $this->get('/shop')->assertOk()->assertSee('12 sold in the last 30 days');
});

it('applies the welcome free-shipping tier to the flat rate once shipping has a fee', function () {
    Mail::fake();
    p3ShippingOn();
    $offer = WelcomeOffer::create(['name' => 'W', 'is_active' => true, 'expiry_hours' => 72]);
    $offer->tiers()->create(['type' => 'free_shipping', 'amount' => 0, 'min_subtotal' => 0, 'sort_order' => 1]);
    $item = p3Product('Small Lamp', 20.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);

    $this->withSession(['cart' => p3Line($item)])->post(route('checkout.store'), p3Form());

    $o = Order::latest('id')->first();
    expect((float) $o->shipping_cost)->toBe(0.0)->and((float) $o->total)->toBe(20.0)->and($o->welcome_claim_id)->not->toBeNull();
});

it('renders every storefront page for a shopper who has a bundle in the cart', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);
    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id]);

    // The cart drawer in the global menu lists the set; it has no product page to link to.
    foreach (['/', '/shop', '/cart', '/checkout', '/faq', route('product-details', $a->slug)] as $url) {
        $this->get($url)->assertOk();
    }
});

it('opens the welcome offer by itself on product pages only', function () {
    $item = p3Product('Lamp', 40.00);
    \App\Models\WelcomeOffer::create(['name' => 'W', 'is_active' => true, 'expiry_hours' => 72])
        ->tiers()->create(['type' => 'fixed_amount', 'amount' => 5, 'min_subtotal' => 35, 'sort_order' => 1]);

    expect($this->get(route('product-details', $item->slug))->getContent())->toContain('data-autopen="1500"')
        ->and($this->get('/')->getContent())->toContain('data-autopen="0"')
        ->and($this->get('/shop')->getContent())->toContain('data-autopen="0"');
});

it('offers the set in the add-to-cart answer, only while it is live, in stock and really cheaper', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);

    $res = $this->postJson(route('cart.add'), ['product_id' => $a->id])->assertOk();
    expect($res->json('bundle.id'))->toBe($bundle->id)->and($res->json('bundle.saves'))->toBe('$6.00')
        ->and($res->json('bundle.price'))->toBe('$53.99')->and($res->json('bundle.includes'))->toBe(['Throw', 'Sand Art']);

    // Once the set is in the cart there is nothing left to offer.
    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id]);
    expect($this->postJson(route('cart.add'), ['product_id' => $a->id])->json('bundle'))->toBeNull();

    $this->flushSession();
    $bundle->update(['price' => 59.99]);   // no saving: no offer
    expect($this->postJson(route('cart.add'), ['product_id' => $a->id])->json('bundle'))->toBeNull();

    $bundle->update(['price' => 53.99, 'is_active' => false]);
    expect($this->postJson(route('cart.add'), ['product_id' => $b->id])->json('bundle'))->toBeNull();
});

it('moves the shopper loose item into the set so they never pay for it twice', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);

    $this->post(route('cart.add'), ['product_id' => $a->id]);
    expect(app(CartService::class)->total())->toBe(45.0);

    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id])->assertSessionHas('success');

    $items = app(CartService::class)->items();
    expect(array_keys($items))->toBe(['bundle|' . $bundle->id])->and(app(CartService::class)->total())->toBe(53.99);
    expect(session('success'))->toContain('Throw')->toContain('only pay once');
});

it('only swaps the quantity the set covers, and leaves other options of the product alone', function () {
    $a = p3Product('Throw', 45.00);
    $b = p3Product('Sand Art', 14.99);
    $bundle = p3Bundle([$a, $b], 53.99);

    $this->post(route('cart.add'), ['product_id' => $a->id, 'qty' => 2]);
    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id]);

    $items = app(CartService::class)->items();
    expect($items["{$a->id}||"]['qty'])->toBe(1)->and($items['bundle|' . $bundle->id]['qty'])->toBe(1)
        ->and(round(app(CartService::class)->total(), 2))->toBe(98.99);

    // A line of the same product in a chosen colour is a different item: not absorbed.
    $this->flushSession();
    $this->post(route('cart.add'), ['product_id' => $a->id, 'color' => 'Red']);
    $this->post(route('cart.add-bundle'), ['bundle_id' => $bundle->id]);
    expect(array_keys(app(CartService::class)->items()))->toContain("{$a->id}|Red|");
});

it('shows the set offer markup in the add-to-cart confirmation', function () {
    $item = p3Product('Lamp', 40.00);

    $this->get(route('product-details', $item->slug))->assertOk()->assertSee('id="pg-atc-bundle"', false)->assertSee('Add from the set', false);
})->skip('The add-to-cart popup is not part of the marketplace design; the JSON offer is covered by the test above.');

it('frequently bought together never shows $0.00 or a From price, and skips unpriced items', function () {
    $main  = p3Product('Ghost Decor', 35.67);
    $free  = p3Product('Unpriced Thing', 0);
    $good  = p3Product('Pumpkin Light', 20);
    $main->variants()->create(['type' => 'color', 'value' => 'White', 'price' => 25.99, 'stock' => 5, 'is_active' => true]);
    $good->variants()->create(['type' => 'size', 'value' => 'S', 'price' => 0, 'stock' => 5, 'is_active' => true]);

    $html = $this->get(route('product-details', $main->slug))->assertOk()->getContent();
    $fbt  = substr($html, $at = strpos($html, 'id="fbt-form"'), strpos($html, '</form>', $at) - $at);

    expect($fbt)->toContain('$20.00')->not->toContain('$0.00')->not->toContain('Unpriced Thing');
    expect($good->fresh()->load('variants')->display_price)->toBe('$20.00');
    expect($good->effectivePriceFor(null, 'S'))->toBe(20.0);
});
