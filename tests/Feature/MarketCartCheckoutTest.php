<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Services\OrderSettlement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => Mail::fake());

function cmStore(string $name, array $over = []): Seller
{
    $u = User::factory()->create(['role' => 'seller']);

    return Seller::create($over + ['user_id' => $u->id, 'store_name' => $name, 'slug' => \Illuminate\Support\Str::slug($name), 'status' => 'approved',
        'subscription_status' => 'active', 'onboarding_step' => 'done']);
}

function cmProd(?Seller $s, string $name, float $price = 30, array $over = []): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create($over + ['category_id' => $cat->id, 'seller_id' => $s?->id, 'name' => $name, 'slug' => \Illuminate\Support\Str::slug($name) . '-' . uniqid(),
        'price' => $price, 'stock' => 5, 'is_active' => true, 'moderation_status' => 'approved']);
}

function cmLine(Product $p, int $qty = 1): array
{
    return ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug, 'price' => (float) $p->price,
        'sku' => null, 'image' => null, 'qty' => $qty, 'color' => null, 'size' => null]];
}

function cmForm(array $over = []): array
{
    return $over + ['name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '5551234567', 'city' => 'Jersey City', 'country' => 'US',
        'state' => 'NJ', 'zip' => '07302', 'address' => '1 Main St', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1'];
}

// ── cart ─────────────────────────────────────────────────────────────────────────────

it('groups cart lines by the store that sells them', function () {
    $zed = cmProd(cmStore('Zed Goods'), 'Zed Lamp');
    $ace = cmProd(cmStore('Ace Home'), 'Ace Mug');
    $house = cmProd(null, 'House Candle');

    $html = $this->withSession(['cart' => cmLine($zed) + cmLine($ace) + cmLine($house)])->get('/cart')->assertOk()
        ->assertSee('width=device-width', false)->assertSee('Ace Home')->assertSee('Zed Goods')->assertSee('Peyton Ghalib')->getContent();

    // Stores are listed together, in name order, with each store's items under its own heading.
    expect($html)->toMatch('/Ace Home.*Ace Mug.*Peyton Ghalib.*House Candle.*Zed Goods.*Zed Lamp/s');
});

it('adds from the product page and shows the line under its store', function () {
    $p = cmProd(cmStore('Ace Home'), 'Ace Mug', 12);

    $this->post(route('cart.add'), ['product_id' => $p->id, 'qty' => 2])->assertSessionHas('success');
    $this->get('/cart')->assertSee('Ace Home')->assertSee('Ace Mug')->assertSee('$24.00');
});

it('drops a line when its store stops being live, and prices the rest correctly', function () {
    $keep = cmProd(cmStore('Keep Co'), 'Keeper', 10);
    $gone = cmProd($lapsing = cmStore('Lapse Co'), 'Lapser', 50);
    $session = ['cart' => cmLine($keep) + cmLine($gone)];

    $this->withSession($session)->get('/cart')->assertSee('Lapser');
    $lapsing->update(['subscription_status' => 'past_due']);

    $this->withSession($session)->get('/cart')->assertOk()->assertDontSee('Lapser')->assertDontSee('Lapse Co')->assertSee('Keeper')->assertSee('$10.00');
});

it('re-reads the price from the database, so a tampered cart price is ignored', function () {
    $p = cmProd(cmStore('Ace Home'), 'Ace Mug', 40);
    $line = cmLine($p);
    $line["{$p->id}||"]['price'] = 0.01;

    $this->withSession(['cart' => $line])->get('/cart')->assertSee('$40.00')->assertDontSee('$0.01');
});

it('refuses to add a hidden, unapproved or sold-out product', function () {
    $s = cmStore('Ace Home');
    $pending = cmProd($s, 'Pending', 10, ['moderation_status' => 'pending']);
    $soldOut = cmProd($s, 'Sold Out', 10, ['stock' => 0]);
    $unpaid = cmProd(cmStore('Late', ['subscription_status' => 'past_due']), 'Unpaid Seller', 10);

    $this->post(route('cart.add'), ['product_id' => $pending->id])->assertNotFound();
    $this->post(route('cart.add'), ['product_id' => $unpaid->id])->assertNotFound();
    $this->post(route('cart.add'), ['product_id' => $soldOut->id])->assertSessionHas('error');
    $this->get('/cart')->assertSee('Your cart is empty');
});

it('escapes a store name that contains HTML in the cart and at checkout', function () {
    $p = cmProd(cmStore('<b>Evil</b> Shop'), '<i>Bad</i> Name');
    $s = ['cart' => cmLine($p)];

    foreach (['/cart', '/checkout'] as $url) {
        $this->withSession($s)->get($url)->assertOk()->assertDontSee('<b>Evil</b>', false)->assertDontSee('<i>Bad</i>', false);
    }
});

// ── checkout ─────────────────────────────────────────────────────────────────────────

it('sends an empty cart away from checkout and renders the form on a phone otherwise', function () {
    $this->get('/checkout')->assertRedirect(route('cart'));

    $p = cmProd(cmStore('Ace Home'), 'Ace Mug');
    $this->withSession(['cart' => cmLine($p)])->get('/checkout')->assertOk()->assertSee('width=device-width', false)
        ->assertSee('Sold by Ace Home')->assertSee('Order summary')->assertSee('name="email"', false);
});

it('creates one order, with each line tied to its store, for a two-store cart', function () {
    $a = cmStore('Ace Home');
    $b = cmStore('Bee Goods');
    $session = ['cart' => cmLine(cmProd($a, 'Ace Mug', 20)) + cmLine(cmProd($b, 'Bee Lamp', 35))];

    $this->withSession($session)->post('/checkout', cmForm())->assertOk();
    $this->withSession($session)->post('/checkout', cmForm())->assertOk(); // double click

    $order = Order::with('items')->sole();
    expect($order->items->pluck('seller_id', 'name')->all())->toBe(['Ace Mug' => $a->id, 'Bee Lamp' => $b->id])
        ->and((float) $order->subtotal)->toBe(55.0);
});

it('stops checkout when a store lapsed after the shopper filled the cart', function () {
    $p = cmProd($s = cmStore('Ace Home'), 'Ace Mug');
    $session = ['cart' => cmLine($p)];
    $s->update(['subscription_status' => 'past_due']);

    $this->withSession($session)->post('/checkout', cmForm())->assertRedirect(route('cart'));
    expect(Order::count())->toBe(0);
});

it('validates the checkout form like a stranger would try to break it', function () {
    $session = ['cart' => cmLine(cmProd(cmStore('Ace Home'), 'Ace Mug'))];
    $bad = fn (array $o, string $f) => $this->withSession($session)->post('/checkout', cmForm($o))->assertSessionHasErrors($f);

    $bad(['email' => 'not-an-email'], 'email');
    $bad(['name' => ''], 'name');
    $bad(['zip' => 'ABCDE'], 'zip');
    $bad(['state' => 'ZZ'], 'state');
    $bad(['country' => 'CN'], 'country');
    $bad(['payment_method' => 'bitcoin'], 'payment_method');
    $bad(['agree' => null], 'agree');
    $bad(['shipping' => 'teleport'], 'shipping');
    expect(Order::count())->toBe(0);
});

// ── after payment ────────────────────────────────────────────────────────────────────

it('shows the thank-you page with items under their stores, a copy button and an account offer for guests', function () {
    $s = cmStore('Ace Home');
    $this->withSession(['cart' => cmLine(cmProd($s, 'Ace Mug', 20))])->post('/checkout', cmForm());
    $order = Order::first();
    app(OrderSettlement::class)->markPaid($order->load('items'), 'paypal');

    $this->withSession(['recent_order' => $order->tracking_number])->get('/thank-you')->assertOk()
        ->assertSee('Thank you, Pat')->assertSee($order->tracking_number)->assertSee('id="copy-tracking"', false)->assertSee('Sold by Ace Home')
        ->assertSee('Create my account')->assertSee('Paid');
});

it('shows a friendly thank-you page when the session has no order', function () {
    $this->get('/thank-you')->assertOk()->assertSee('Thank you for your order')->assertSee('Track an order');
});

it('does not show another shoppers order on the thank-you page', function () {
    $this->withSession(['cart' => cmLine(cmProd(cmStore('Ace Home'), 'Ace Mug'))])->post('/checkout', cmForm());
    $order = Order::first();

    $this->get('/thank-you')->assertDontSee($order->tracking_number)->assertDontSee('pat@example.com');
    $this->withSession(['recent_order' => 'FRN-0000-NOPE'])->get('/thank-you')->assertOk()->assertDontSee($order->tracking_number);
});

it('tracks an order by number, ignores case and spaces, and says so when it is not found', function () {
    $this->withSession(['cart' => cmLine(cmProd(cmStore('Ace Home'), 'Ace Mug'))])->post('/checkout', cmForm());
    $order = Order::first();

    $this->get('/track-order?tracking=' . urlencode('  ' . strtolower($order->tracking_number) . ' '))->assertOk()
        ->assertSee($order->tracking_number)->assertSee('Sold by Ace Home')->assertSee('Order placed');
    $this->get('/track-order?tracking=FRN-9999-ZZZZZZZZ')->assertOk()->assertSee('find an order with that tracking number');
    $this->get('/track-order')->assertOk()->assertDontSee('could not find');
    $this->get('/track-order?tracking[]=x')->assertStatus(200);
});
