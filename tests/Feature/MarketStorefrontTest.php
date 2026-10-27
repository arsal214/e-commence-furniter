<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function msStore(string $slug, array $over = []): Seller
{
    $u = User::factory()->create(['role' => 'seller']);

    return Seller::create($over + ['user_id' => $u->id, 'store_name' => ucwords(str_replace('-', ' ', $slug)), 'slug' => $slug, 'status' => 'approved',
        'subscription_status' => 'active', 'onboarding_step' => 'done', 'city' => 'Savannah', 'state' => 'GA']);
}

function msCat(string $name, ?Category $parent = null): Category
{
    return Category::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug(($parent?->name ?? '') . ' ' . $name), 'parent_id' => $parent?->id, 'is_active' => true]);
}

function msProd(Category $c, ?Seller $s, array $over = []): Product
{
    return Product::create($over + ['category_id' => $c->id, 'seller_id' => $s?->id, 'name' => 'Item ' . uniqid(), 'slug' => 'item-' . uniqid(),
        'price' => 20, 'stock' => 5, 'is_active' => true, 'moderation_status' => 'approved']);
}

// ── home ─────────────────────────────────────────────────────────────────────────────

it('renders the design home page with the h1, its promo cards and no broken sections when the store is empty', function () {
    $this->get('/')->assertOk()->assertSee('width=device-width', false)->assertSee('Shop direct from independent American sellers')
        ->assertSee('assets/cards/hero-', false)->assertSee('Buyer Protection')
        ->assertSee('Today&#039;s deals', false)->assertSee('Sell on Peyton Ghalib')->assertDontSee('Undefined')
        ->assertSee('Open your store')->assertDontSee('Seller pricing: $19.99/mo');
});

it('shows only visible products on the home page, never a hidden seller or an unapproved listing', function () {
    $fashion = msCat('Fashion');
    $live = msStore('live-store');
    $unpaid = msStore('unpaid-store', ['subscription_status' => 'past_due']);
    msProd($fashion, $live, ['name' => 'Visible Tote']);
    msProd($fashion, $unpaid, ['name' => 'Unpaid Seller Tote']);
    msProd($fashion, $live, ['name' => 'Pending Tote', 'moderation_status' => 'pending']);
    msProd($fashion, $live, ['name' => 'Removed Tote', 'moderation_status' => 'removed']);

    $this->get('/')->assertOk()->assertSee('Visible Tote')->assertDontSee('Unpaid Seller Tote')->assertDontSee('Pending Tote')->assertDontSee('Removed Tote')
        ->assertSee('Shop by category')->assertSee('Live Store');
});

it('lists a store on the home page only while it is live and has products', function () {
    $c = msCat('Home');
    msProd($c, msStore('has-products'));
    msStore('empty-store');
    msProd($c, msStore('late-store', ['subscription_status' => 'past_due']));

    $html = $this->get('/')->assertSee('Has Products')->assertDontSee('Empty Store')->assertDontSee('Late Store')->getContent();
    expect($html)->not->toMatch('/\d+ products?/'); // how many a store has published is never shown
});

// ── categories ───────────────────────────────────────────────────────────────────────

it('shows a parent category with the products of all its subcategories and lists them as filters', function () {
    $fashion = msCat('Fashion');
    $bags = msCat('Bags', $fashion);
    $shoes = msCat('Shoes', $fashion);
    msCat('Empty Sub', $fashion);
    $s = msStore('s1');
    msProd($bags, $s, ['name' => 'Leather Tote']);
    msProd($shoes, $s, ['name' => 'Canvas Sneaker']);
    msProd(msCat('Toys'), $s, ['name' => 'Wooden Train']);

    $html = $this->get('/category/fashion')->assertOk()->assertSee('Leather Tote')->assertSee('Canvas Sneaker')->assertDontSee('Wooden Train')->getContent();
    expect($html)->toContain('Bags')->toContain('Shoes')->not->toContain('Empty Sub');

    $this->get('/category/fashion-bags')->assertOk()->assertSee('Leather Tote')->assertDontSee('Canvas Sneaker')->assertSee('Fashion');
});

it('404s an inactive or unknown category and keeps empty categories out of the menu', function () {
    msCat('Ghost')->update(['is_active' => false]);
    msCat('Empty Cat');

    $this->get('/category/ghost')->assertNotFound();
    $this->get('/category/nope')->assertNotFound();
    $this->get('/')->assertDontSee('Empty Cat');
});

it('filters by price and stock and sorts, ignoring junk input', function () {
    $c = msCat('Home');
    $s = msStore('s1');
    msProd($c, $s, ['name' => 'Cheap Mug', 'price' => 5]);
    msProd($c, $s, ['name' => 'Mid Lamp', 'price' => 50]);
    msProd($c, $s, ['name' => 'Pricey Sofa', 'price' => 500, 'stock' => 0]);
    msProd($c, $s, ['name' => 'Sale Vase', 'price' => 100, 'sale_price' => 30]);

    $this->get('/shop?min=10&max=60')->assertSee('Mid Lamp')->assertSee('Sale Vase')->assertDontSee('Cheap Mug')->assertDontSee('Pricey Sofa'); // sale price counts
    $this->get('/shop?instock=1')->assertDontSee('Pricey Sofa')->assertSee('Cheap Mug');
    expect($this->get('/shop?sort=price_desc')->getContent())->toMatch('/Pricey Sofa.*Mid Lamp.*Sale Vase.*Cheap Mug/s');
    expect($this->get('/shop?sort=price_asc')->getContent())->toMatch('/Cheap Mug.*Sale Vase.*Mid Lamp.*Pricey Sofa/s');
    $this->get('/shop?min=abc&max=;DROP TABLE&sort=evil&page=-5')->assertOk()->assertSee('Cheap Mug');
});

it('paginates 24 per page', function () {
    $c = msCat('Home');
    $s = msStore('s1');
    foreach (range(1, 30) as $i) { msProd($c, $s, ['name' => sprintf('Thing %02d', $i)]); }

    $this->get('/shop')->assertSee('30 results')->assertSee('Thing 30')->assertDontSee('Thing 01');
    $this->get('/shop?page=2')->assertSee('Thing 01')->assertDontSee('Thing 30');
});

// ── search ───────────────────────────────────────────────────────────────────────────

it('searches names, brands and descriptions and finds stores by name', function () {
    $c = msCat('Home');
    $s = msStore('maple-and-pine');
    msProd($c, $s, ['name' => 'Ceramic Mug']);
    msProd($c, $s, ['name' => 'Plain Bowl', 'description' => 'Fits a ceramic glaze']);
    msProd($c, $s, ['name' => 'Iron Skillet', 'brand' => 'Ceramica']);
    msProd($c, $s, ['name' => 'Wool Rug']);

    $this->get('/search?q=ceramic')->assertOk()->assertSee('Ceramic Mug')->assertSee('Plain Bowl')->assertSee('Iron Skillet')->assertDontSee('Wool Rug');
    $this->get('/search?q=maple')->assertSee('Maple And Pine');
    $this->get('/search?q=zzzz')->assertOk()->assertSee('No matches yet');
});

it('treats % and _ in a search as plain text and never as wildcards', function () {
    $c = msCat('Home');
    msProd($c, msStore('s1'), ['name' => 'Everything Mug']);

    $this->get('/search?q=' . urlencode('%'))->assertOk()->assertSee('No matches yet')->assertSee('0 results');
    $this->get('/search?q=' . urlencode('_'))->assertOk()->assertSee('0 results');
});

it('redirects an empty search, caps a huge one, noindexes results and escapes the query', function () {
    $this->get('/search')->assertRedirect(route('shop'));
    $this->get('/search?q=' . urlencode('   '))->assertRedirect(route('shop'));
    $this->get('/search?q=' . str_repeat('a', 5000))->assertOk();

    $html = $this->get('/search?q=' . urlencode('<script>alert(1)</script>'))->assertOk()->getContent();
    expect($html)->not->toContain('<script>alert(1)</script>')->toContain('noindex');
    $this->get('/search?q[]=x')->assertRedirect(route('shop')); // an array instead of text must not crash
});

// ── store page ───────────────────────────────────────────────────────────────────────

it('shows a live store with its own products only and 404s a hidden or unknown one', function () {
    $c = msCat('Home');
    $a = msStore('store-a', ['about' => 'Hand made in Georgia']);
    $b = msStore('store-b');
    msProd($c, $a, ['name' => 'A Product']);
    msProd($c, $b, ['name' => 'B Product']);

    $this->get($a->url())->assertOk()->assertSee('A Product')->assertSee('Hand made in Georgia')->assertDontSee('B Product');
    expect($a->url())->toMatch('#/stores/store-a/page/[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$#'); // the id is a uuid, not a row number

    expect($this->get($a->url())->getContent())->not->toMatch('#>\s*[\d,]+\s*</b><span[^>]*>Products<#'); // no published-products count

    // The old address and a stale store name both lead to the current address.
    $this->get('/store/store-a')->assertStatus(301)->assertRedirect($a->url());
    $this->get('/stores/old-name/page/' . $a->uuid)->assertStatus(301)->assertRedirect($a->url());
    $this->get('/stores/store-a/page/' . $b->uuid)->assertRedirect($b->url()); // a mismatched pair is corrected, not trusted
    $this->get('/stores/store-a/page/00000000-0000-0000-0000-000000000000')->assertNotFound();

    $b->update(['subscription_status' => 'past_due']);
    $this->get($b->url())->assertNotFound();
    $pending = msStore('pending-store', ['status' => 'in_review']);
    $this->get($pending->url())->assertNotFound();
    $this->get('/store/nope')->assertNotFound();
});

// ── product page ─────────────────────────────────────────────────────────────────────

it('renders the product page with seller, options, schema and the welcome-offer hook', function () {
    $c = msCat('Bags', msCat('Fashion'));
    $s = msStore('harbor', ['level' => 'rising', 'return_window_days' => 60, 'return_shipping_payer' => 'seller']);
    $p = msProd($c, $s, ['name' => 'Leather Tote', 'price' => 128, 'colors' => ['Cognac', 'Black'], 'sizes' => ['Large']]);
    $p->variants()->create(['type' => 'color', 'value' => 'Cognac', 'price' => 138, 'stock' => 4, 'is_active' => true]);
    $p->variants()->create(['type' => 'color', 'value' => 'Black', 'price' => 128, 'stock' => 0, 'is_active' => true]);
    $p->variants()->create(['type' => 'size', 'value' => 'Large', 'price' => 148, 'stock' => 3, 'is_active' => true]);

    $html = $this->get(route('product-details', $p->slug))->assertOk()
        ->assertSee('Harbor')->assertSee('Rising Seller')->assertSee('60-day returns')->assertSee('seller pays return shipping')
        ->assertSee('data-welcome-product', false)->assertSee('application/ld+json', false)->assertSee('Buyer Protection')
        ->assertSee('name="product_id" value="' . $p->id . '"', false)->getContent();
    expect($html)->toContain('value="Cognac"')->toContain('value="Large"');
    // The sold-out colour cannot be picked.
    expect($html)->toMatch('/data-value="Black"[^>]*disabled/');
});

it('404s the product page for anything shoppers must not see', function () {
    $c = msCat('Home');
    $s = msStore('s1');
    $hidden = msProd($c, $s, ['moderation_status' => 'pending']);
    $off = msProd($c, $s, ['is_active' => false]);
    $s2 = msProd($c, msStore('s2', ['subscription_status' => 'past_due']));

    foreach ([$hidden, $off, $s2] as $p) { $this->get(route('product-details', $p->slug))->assertNotFound(); }
    $this->get('/product-details/nope')->assertNotFound();
});

it('escapes seller-written text everywhere it is shown', function () {
    $c = msCat('Home');
    $s = msStore('s1', ['store_name' => '<b>Evil</b> Store', 'about' => '<script>x()</script>']);
    $p = msProd($c, $s, ['name' => '<img src=x onerror=alert(1)>', 'description' => '<script>steal()</script>']);

    foreach (['/', '/shop', $s->url(), route('product-details', $p->slug), '/search?q=img'] as $url) {
        $html = $this->get($url)->assertOk()->getContent();
        expect($html)->not->toContain('<script>steal()')->not->toContain('<script>x()')->not->toContain('<img src=x onerror')->not->toContain('<b>Evil</b>');
    }
});

it('puts the right seller on every order line at checkout, including set components', function () {
    Mail::fake();
    $c = msCat('Home');
    $a = msStore('a');
    $b = msStore('b');
    $pa = msProd($c, $a, ['name' => 'From A', 'price' => 30]);
    $pb = msProd($c, $b, ['name' => 'From B', 'price' => 25]);
    $house = msProd($c, null, ['name' => 'House Item', 'price' => 10]);
    $line = fn (Product $p) => ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug, 'price' => (float) $p->price, 'sku' => null, 'image' => null, 'qty' => 1, 'color' => null, 'size' => null]];

    $this->withSession(['cart' => $line($pa) + $line($pb) + $line($house)])->post('/checkout', [
        'name' => 'Pat', 'email' => 'pat@example.com', 'phone' => '5551234567', 'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ',
        'zip' => '07302', 'address' => '1 Main', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1',
    ])->assertOk();

    $by = OrderItem::where('order_id', Order::first()->id)->pluck('seller_id', 'name');
    expect($by['From A'])->toBe($a->id)->and($by['From B'])->toBe($b->id)->and($by['House Item'])->toBeNull();
});
