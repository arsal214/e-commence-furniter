<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Support\DeliveryEstimate;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function p1Product(array $over = []): Product
{
    $cat = Category::firstOrCreate(['slug' => 'gifts'], ['name' => 'Gifts', 'is_active' => true]);

    return Product::create($over + [
        'category_id' => $cat->id, 'name' => 'Ghost Decor', 'slug' => 'ghost-decor',
        'price' => 25.99, 'stock' => 5, 'is_active' => true,
    ]);
}

it('skips weekends and US federal holidays in the delivery estimate', function () {
    expect(DeliveryEstimate::isFederalHoliday(CarbonImmutable::parse('2026-11-26')))->toBeTrue()  // Thanksgiving
        ->and(DeliveryEstimate::isFederalHoliday(CarbonImmutable::parse('2026-07-03')))->toBeTrue() // Jul 4 is a Saturday -> observed Fri
        ->and(DeliveryEstimate::isFederalHoliday(CarbonImmutable::parse('2026-10-13')))->toBeFalse();

    // Thu Jul 2 + 1 business day: Fri Jul 3 (observed holiday) and the weekend are skipped.
    expect(DeliveryEstimate::addBusinessDays(CarbonImmutable::parse('2026-07-02'), 1)->toDateString())->toBe('2026-07-06');
});

it('adds processing days before transit days', function () {
    config(['checkout.delivery' => ['processing_days' => 1, 'min_days' => 5, 'max_days' => 7]]);

    // Mon Oct 19 2026 -> ships Tue 20th -> +5/+7 business days = Tue 27th / Thu 29th (Columbus Day was Oct 12).
    $w = DeliveryEstimate::window(CarbonImmutable::parse('2026-10-19'));

    expect($w['earliest']->toDateString())->toBe('2026-10-27')
        ->and($w['latest']->toDateString())->toBe('2026-10-29');
});

it('shows one price on every card: From only when variant prices differ', function () {
    $plain = p1Product();
    expect($plain->display_price)->toBe('$25.99')->and($plain->was_price)->toBeNull();

    $multi = p1Product(['name' => 'Jumpsuit', 'slug' => 'jumpsuit', 'price' => 74.99]);
    ProductVariant::create(['product_id' => $multi->id, 'type' => 'size', 'value' => 'S', 'price' => 54.99, 'stock' => 3, 'is_active' => true]);
    ProductVariant::create(['product_id' => $multi->id, 'type' => 'size', 'value' => 'L', 'price' => 74.99, 'stock' => 3, 'is_active' => true]);
    $multi->refresh();

    expect($multi->display_price)->toBe('From $54.99')->and($multi->was_price)->toBeNull();
});

it('renders no $0.00 compare-at price and no duplicated brand in the title', function () {
    $item = p1Product(['meta_title' => 'Halloween Ghost Decor | PeytonGhalib']);

    $res = $this->get(route('product-details', $item->slug))->assertOk();
    $res->assertHeader('Cache-Control');

    expect($res->headers->get('Cache-Control'))->toContain('no-cache');
    $html = $res->getContent();
    expect($html)->not->toContain('$0.00')
        ->and(substr_count($html, '<title>Halloween Ghost Decor | PeytonGhalib</title>'))->toBe(1);
});

function p1Checkout(array $over = []): array
{
    return $over + [
        'name' => 'Pat Guest', 'email' => 'guest@example.com', 'phone' => '5551234567',
        'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ', 'zip' => '07302',
        'address' => '1 Main St', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1',
    ];
}

function p1Cart(Product $p): array
{
    return ['cart' => ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug,
        'price' => 25.99, 'sku' => null, 'image' => null, 'qty' => 1, 'color' => null, 'size' => null]]];
}

it('lets a new email check out as a guest without creating an account', function () {
    $item = p1Product();

    $this->withSession(p1Cart($item))->post(route('checkout.store'), p1Checkout())->assertOk();

    expect(User::where('email', 'guest@example.com')->exists())->toBeFalse()
        ->and(Order::first()->user_id)->toBeNull()
        ->and(Order::first()->email)->toBe('guest@example.com');
});

it('still auto-creates the legacy account when the flag is on', function () {
    config(['checkout.auto_create_account' => true]);
    $item = p1Product();

    $this->withSession(p1Cart($item))->post(route('checkout.store'), p1Checkout());

    expect(User::where('email', 'guest@example.com')->exists())->toBeTrue();
});

it('lets a guest create an optional account from the thank-you page and attaches the order', function () {
    $item = p1Product();
    $this->withSession(p1Cart($item))->post(route('checkout.store'), p1Checkout());
    $order = Order::first();

    $this->withSession(['recent_order' => $order->tracking_number])
        ->post(route('thank-you.account'), ['password' => 'secret-pass-1', 'password_confirmation' => 'secret-pass-1'])
        ->assertRedirect(route('thank-you'));

    $user = User::where('email', 'guest@example.com')->first();
    expect($user)->not->toBeNull()->and($order->fresh()->user_id)->toBe($user->id);
});

it('refuses the optional account when the email already has one', function () {
    $item = p1Product();
    $this->withSession(p1Cart($item))->post(route('checkout.store'), p1Checkout());
    $order = Order::first();
    $order->update(['user_id' => null]);
    User::create(['name' => 'x', 'email' => 'guest@example.com', 'password' => 'x', 'role' => 'customer']);

    $this->withSession(['recent_order' => $order->tracking_number])
        ->post(route('thank-you.account'), ['password' => 'secret-pass-1', 'password_confirmation' => 'secret-pass-1']);

    expect($order->fresh()->user_id)->toBeNull();
});

it('unpublishes a product by slug without deleting it', function () {
    $item = p1Product(['slug' => 'eu-rotating-power-strip', 'name' => 'EU strip']);

    $this->artisan('products:unpublish', ['slug' => 'eu-rotating-power-strip'])->assertSuccessful();

    expect($item->fresh()->is_active)->toBeFalse();
    $this->get(route('product-details', 'eu-rotating-power-strip'))->assertNotFound();
});

it('renders home, shop and category cards with the same price for a variant product', function () {
    $multi = p1Product(['name' => 'Jumpsuit', 'slug' => 'jumpsuit', 'price' => 74.99, 'is_best_seller' => true]);
    ProductVariant::create(['product_id' => $multi->id, 'type' => 'size', 'value' => 'S', 'price' => 54.99, 'stock' => 3, 'is_active' => true]);
    ProductVariant::create(['product_id' => $multi->id, 'type' => 'size', 'value' => 'L', 'price' => 74.99, 'stock' => 3, 'is_active' => true]);

    foreach (['/', '/shop', '/category/gifts'] as $url) {
        $html = $this->get($url)->assertOk()->getContent();
        expect($html)->toContain('>$54.99</b>')->not->toContain('>$74.99</b>');
    }
});
