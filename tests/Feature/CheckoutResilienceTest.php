<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderSettlement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function crProduct(int $stock = 5, float $price = 40): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create([
        'category_id' => $cat->id, 'name' => 'Lamp ' . uniqid(), 'slug' => 'lamp-' . uniqid(),
        'price' => $price, 'stock' => $stock, 'is_active' => true,
    ]);
}

function crCart(Product $p, int $qty = 1): array
{
    return ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug,
        'price' => (float) $p->price, 'sku' => null, 'image' => null, 'qty' => $qty, 'color' => null, 'size' => null]];
}

function crForm(array $over = []): array
{
    return $over + [
        'name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '5551234567',
        'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ', 'zip' => '07302',
        'address' => '1 Main St', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1',
    ];
}

beforeEach(function () {
    Mail::fake();
    Cache::flush();
});

it('creates ONE order when the buyer double-clicks Pay or retries on a slow connection', function () {
    $p = crProduct();
    $session = ['cart' => crCart($p)];

    foreach (range(1, 3) as $_) {
        $this->withSession($session)->post('/checkout', crForm())->assertOk();
    }

    expect(Order::count())->toBe(1)
        ->and(Order::first()->items()->count())->toBe(1);
});

it('makes a separate order when the cart actually changed', function () {
    $p = crProduct();

    $this->withSession(['cart' => crCart($p, 1)])->post('/checkout', crForm())->assertOk();
    $this->withSession(['cart' => crCart($p, 2)])->post('/checkout', crForm())->assertOk();

    expect(Order::count())->toBe(2);
});

it('takes stock off exactly once, however many times the order is marked paid', function () {
    $p = crProduct(stock: 5);
    $this->withSession(['cart' => crCart($p, 2)])->post('/checkout', crForm())->assertOk();
    $order = Order::with('items')->first();

    $settle = app(OrderSettlement::class);
    $settle->markPaid($order, 'paypal');
    $settle->markPaid($order->fresh('items'), 'paypal'); // webhook + return page both arrive
    $settle->markPaid($order->fresh('items'), 'paypal');

    expect($p->fresh()->stock)->toBe(3)->and($order->fresh()->stock_issue)->toBeNull();
});

it('lets only one of two buyers take the last unit and flags the other order for staff', function () {
    $p = crProduct(stock: 1);

    // Both added it to the cart while it was in stock, both reached payment.
    $this->withSession(['cart' => crCart($p)])->post('/checkout', crForm(['email' => 'a@example.com']))->assertOk();
    $this->withSession(['cart' => crCart($p)])->post('/checkout', crForm(['email' => 'b@example.com']))->assertOk();
    [$first, $second] = Order::with('items')->orderBy('id')->get()->all();

    $settle = app(OrderSettlement::class);
    $settle->markPaid($first, 'paypal');
    $settle->markPaid($second, 'paypal');

    expect($p->fresh()->stock)->toBe(0)
        ->and($first->fresh()->stock_issue)->toBeNull()
        ->and($second->fresh()->stock_issue)->toContain('OVERSOLD');
});

it('never lets stock go negative', function () {
    $p = crProduct(stock: 1);
    $this->withSession(['cart' => crCart($p, 1)])->post('/checkout', crForm())->assertOk();
    $order = Order::with('items')->first();
    $order->items()->update(['qty' => 4]); // simulate the cart outgrowing stock

    app(OrderSettlement::class)->markPaid($order->fresh('items'), 'paypal');

    expect($p->fresh()->stock)->toBe(0);
});

it('stops checkout when the last unit sold out while the cart sat open', function () {
    $p = crProduct(stock: 1);
    $session = ['cart' => crCart($p, 1)];
    $p->update(['stock' => 0]); // someone else bought it

    $this->withSession($session)->post('/checkout', crForm())
        ->assertRedirect(route('cart'))
        ->assertSessionHas('error');

    expect(Order::count())->toBe(0);
});

it('rejects a tampered price and a negative or huge quantity from the cart', function () {
    $p = crProduct(stock: 5, price: 40);
    $cart = crCart($p, 1);
    $cart["{$p->id}||"]['price'] = 0.01; // forged session/cart price

    $this->withSession(['cart' => $cart])->post('/checkout', crForm())->assertOk();

    expect((float) Order::first()->subtotal)->toBe(40.0); // price is re-read from the database
});
