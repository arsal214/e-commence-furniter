<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Database\Seeders\MarketplaceCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    Cache::flush();
    $this->seed(MarketplaceCategorySeeder::class);
});

function scSeller(string $slug = 'maple', array $over = []): User
{
    $user = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create($over + ['user_id' => $user->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved',
        'subscription_status' => 'active', 'onboarding_step' => 'done']);

    return $user;
}

function scSub(string $name = 'Bags'): Category
{
    return Category::where('name', $name)->whereNotNull('parent_id')->firstOrFail();
}

function scForm(array $over = []): array
{
    return $over + [
        'name' => 'Leather Tote Bag', 'category_id' => scSub()->id, 'description' => 'Hand made.', 'price' => '128.00',
        'stock' => 10, 'is_active' => '1', 'image' => UploadedFile::fake()->image('main.jpg', 1200, 1200),
    ];
}

function scProduct(User $u, array $over = []): Product
{
    return Product::create($over + ['seller_id' => $u->seller->id, 'category_id' => scSub()->id, 'name' => 'Mug ' . uniqid(),
        'slug' => 'mug-' . uniqid(), 'price' => 20, 'stock' => 5, 'is_active' => true, 'moderation_status' => 'approved']);
}

// ── access ───────────────────────────────────────────────────────────────────────────

it('keeps guests, shoppers and half-signed-up sellers out of Seller Center', function () {
    $this->get('/seller')->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create())->get('/seller')->assertRedirect(route('login'));

    $mid = scSeller('mid', ['onboarding_step' => 'owner']);
    $this->actingAs($mid)->get('/seller')->assertRedirect(route('seller.owner'));
    $this->actingAs($mid)->get('/seller/products/create')->assertRedirect(route('seller.owner'));
});

it('opens the dashboard for a seller who is waiting for approval and says so', function () {
    $u = scSeller('wait', ['status' => 'in_review']);

    $this->actingAs($u)->get('/seller')->assertOk()->assertSee('Your application is in review')->assertSee('Add your first product');
});

it('warns that the store is hidden when the plan payment failed', function () {
    $u = scSeller('late', ['subscription_status' => 'past_due']);

    $this->actingAs($u)->get('/seller')->assertOk()->assertSee('Your store is hidden');
});

it('shows only the seller own numbers on the dashboard', function () {
    $a = scSeller('a');
    $b = scSeller('b');
    $pa = scProduct($a, ['stock' => 2, 'name' => 'Lamp A']);
    scProduct($b, ['stock' => 1, 'name' => 'Secret B']);
    $order = Order::create(['name' => 'Pat', 'email' => 'p@e.com', 'phone' => '1', 'address' => 'x', 'city' => 'Jersey City', 'payment_method' => 'stripe',
        'payment_status' => 'paid', 'shipping' => 'free', 'subtotal' => 40, 'total' => 40, 'status' => 'processing', 'tracking_number' => 'T-1']);
    OrderItem::create(['order_id' => $order->id, 'product_id' => $pa->id, 'seller_id' => $a->seller->id, 'name' => 'Lamp A', 'price' => 40, 'qty' => 1, 'total' => 40]);
    app(\App\Services\ShipmentService::class)->createForOrder($order);

    $this->actingAs($a)->get('/seller')->assertOk()->assertSee('$40.00')->assertSee('Lamp A')->assertDontSee('Secret B');
    $this->actingAs($b)->get('/seller')->assertOk()->assertDontSee('Lamp A')->assertDontSee('$40.00');
});

// ── creating a product ───────────────────────────────────────────────────────────────

it('creates a product that waits for review, owned by the seller, and invisible to shoppers', function () {
    $u = scSeller();
    $this->actingAs($u)->post('/seller/products', scForm())->assertSessionHasNoErrors();

    $p = Product::first();
    expect($p->seller_id)->toBe($u->seller->id)->and($p->moderation_status)->toBe('pending')->and($p->slug)->toBe('leather-tote-bag');
    Storage::disk('public')->assertExists($p->image);
    expect(Product::visible()->count())->toBe(0);
});

it('ignores seller_id and moderation_status smuggled into the form', function () {
    $a = scSeller('a');
    $b = scSeller('b');

    $this->actingAs($a)->post('/seller/products', scForm(['seller_id' => $b->seller->id, 'moderation_status' => 'approved', 'supplier_cost' => 1]));

    $p = Product::first();
    expect($p->seller_id)->toBe($a->seller->id)->and($p->moderation_status)->toBe('pending');
});

it('makes ONE product when Save is tapped twice', function () {
    $u = scSeller();

    $this->actingAs($u)->post('/seller/products', scForm());
    $this->actingAs($u)->post('/seller/products', scForm(['image' => UploadedFile::fake()->image('main.jpg', 1200, 1200)]));

    expect(Product::count())->toBe(1);
});

it('gives two sellers with the same product title different slugs', function () {
    $this->actingAs(scSeller('a'))->post('/seller/products', scForm());
    $this->actingAs(scSeller('b'))->post('/seller/products', scForm());

    expect(Product::orderBy('id')->pluck('slug')->all())->toBe(['leather-tote-bag', 'leather-tote-bag-2']);
});

it('enforces the photo rules: square, big enough, a real image', function () {
    $u = scSeller();
    $post = fn ($img) => $this->actingAs($u)->post('/seller/products', scForm(['image' => $img]));

    $post(UploadedFile::fake()->image('wide.jpg', 1600, 900))->assertSessionHasErrors(['image' => 'Product photo must be a 1:1 picture. Yours is 1600×900.']);
    $post(UploadedFile::fake()->image('small.jpg', 600, 600))->assertSessionHasErrors(['image' => 'Product photo must be at least 1000×1000 px. Yours is 600×600.']);
    $post(UploadedFile::fake()->image('huge.jpg', 7000, 7000))->assertSessionHasErrors('image');
    $post(UploadedFile::fake()->create('evil.jpg', 20, 'image/jpeg'))->assertSessionHasErrors(['image' => 'Product photo must be a JPG, PNG or WebP image.']);
    $post(UploadedFile::fake()->create('doc.pdf', 20, 'application/pdf'))->assertSessionHasErrors('image');
    expect(Product::count())->toBe(0);

    // A near-square photo inside the 2% tolerance is accepted.
    $post(UploadedFile::fake()->image('ok.png', 1000, 1015))->assertSessionHasNoErrors();
    expect(Product::count())->toBe(1);
});

it('requires a main photo on create', function () {
    $this->actingAs(scSeller())->post('/seller/products', array_diff_key(scForm(), ['image' => 1]))->assertSessionHasErrors('image');
});

it('validates price, sale price, stock, category and title', function () {
    $u = scSeller();
    $bad = fn (array $o, string $field) => $this->actingAs($u)->post('/seller/products', scForm($o))->assertSessionHasErrors($field);

    $bad(['price' => '0'], 'price');
    $bad(['price' => '-5'], 'price');
    $bad(['price' => 'abc'], 'price');
    $bad(['price' => '99999999'], 'price');
    $bad(['sale_price' => '200'], 'sale_price');
    $bad(['stock' => '-1'], 'stock');
    $bad(['stock' => '1.5'], 'stock');
    $bad(['name' => 'ab'], 'name');
    $bad(['category_id' => Category::whereNull('parent_id')->first()->id], 'category_id'); // a top-level category, not a subcategory
    $bad(['category_id' => 99999], 'category_id');
    Category::where('name', 'Bags')->update(['is_active' => false]);
    $bad(['category_id' => scSub()->id], 'category_id');
    expect(Product::count())->toBe(0);
});

it('stores colors and sizes with their own price and stock and lets the storefront price them', function () {
    $u = scSeller();
    $this->actingAs($u)->post('/seller/products', scForm([
        'colors' => [['value' => 'Cognac', 'price' => '138', 'stock' => 4], ['value' => 'Black', 'stock' => 6], ['value' => '']],
        'sizes' => [['value' => 'Large', 'price' => '148', 'stock' => 3]],
    ]))->assertSessionHasNoErrors();

    $p = Product::with('variants')->first();
    expect($p->colors)->toBe(['Cognac', 'Black'])->and($p->sizes)->toBe(['Large'])->and($p->variants)->toHaveCount(3);
    expect($p->effectivePriceFor('Cognac'))->toBe(138.0)
        ->and($p->effectivePriceFor('Black'))->toBe(128.0)   // blank price falls back to the base price
        ->and($p->effectivePriceFor('Black', 'Large'))->toBe(148.0)
        ->and($p->effectiveStockFor('Black', 'Large'))->toBe(3);
});

it('rejects the same color twice and a bad variant price', function () {
    $u = scSeller();

    $this->actingAs($u)->post('/seller/products', scForm(['colors' => [['value' => 'Red'], ['value' => ' red ']]]))->assertSessionHasErrors('colors');
    $this->actingAs($u)->post('/seller/products', scForm(['sizes' => [['value' => 'M', 'price' => '-3']]]))->assertSessionHasErrors('sizes.0.price');
    $this->actingAs($u)->post('/seller/products', scForm(['colors' => [['value' => 'Red', 'image' => UploadedFile::fake()->image('r.jpg', 800, 1200)]]]))->assertSessionHasErrors('colors.0.image');
    expect(Product::count())->toBe(0);
});

// ── editing and deleting ─────────────────────────────────────────────────────────────

it('lets price and stock change instantly but sends new wording back to review', function () {
    $u = scSeller();
    $p = scProduct($u, ['name' => 'Old Mug', 'description' => 'Same']);

    $this->actingAs($u)->put("/seller/products/{$p->id}", scForm(['name' => 'Old Mug', 'description' => 'Same', 'price' => '25', 'stock' => 9, 'image' => null]))->assertSessionHasNoErrors();
    expect($p->fresh()->moderation_status)->toBe('approved')->and((float) $p->fresh()->price)->toBe(25.0)->and($p->fresh()->stock)->toBe(9);

    $this->actingAs($u)->put("/seller/products/{$p->id}", scForm(['name' => 'New Mug Name', 'description' => 'Same', 'image' => null]));
    expect($p->fresh()->moderation_status)->toBe('pending');
});

it('keeps the old main photo when no new one is chosen and replaces it when one is', function () {
    $u = scSeller();
    $p = scProduct($u, ['image' => 'products/old.jpg']);
    Storage::disk('public')->put('products/old.jpg', 'x');

    $this->actingAs($u)->put("/seller/products/{$p->id}", scForm(['name' => $p->name, 'image' => null]));
    expect($p->fresh()->image)->toBe('products/old.jpg');

    $this->actingAs($u)->put("/seller/products/{$p->id}", scForm(['name' => $p->name]));
    expect($p->fresh()->image)->not->toBe('products/old.jpg');
    Storage::disk('public')->assertMissing('products/old.jpg');
});

it('NEVER lets one seller view, change or delete another sellers product', function () {
    $a = scSeller('a');
    $b = scSeller('b');
    $pa = scProduct($a, ['name' => 'A Only']);

    $this->actingAs($b)->get("/seller/products/{$pa->id}/edit")->assertNotFound();
    $this->actingAs($b)->put("/seller/products/{$pa->id}", scForm(['name' => 'Hacked']))->assertNotFound();
    $this->actingAs($b)->delete("/seller/products/{$pa->id}")->assertNotFound();
    $this->actingAs($b)->get('/seller/products')->assertOk()->assertDontSee('A Only');

    expect($pa->fresh()->name)->toBe('A Only')->and(Product::count())->toBe(1);
});

it('deletes a product with no orders but only hides one that has orders', function () {
    $u = scSeller();
    $free = scProduct($u);
    $sold = scProduct($u);
    $order = Order::create(['name' => 'P', 'email' => 'p@e.com', 'phone' => '1', 'address' => 'x', 'payment_method' => 'stripe', 'payment_status' => 'paid',
        'shipping' => 'free', 'subtotal' => 20, 'total' => 20, 'status' => 'processing', 'tracking_number' => 'T-9']);
    OrderItem::create(['order_id' => $order->id, 'product_id' => $sold->id, 'seller_id' => $u->seller->id, 'name' => 'x', 'price' => 20, 'qty' => 1, 'total' => 20]);

    $this->actingAs($u)->delete("/seller/products/{$free->id}");
    $this->actingAs($u)->delete("/seller/products/{$sold->id}");

    expect(Product::find($free->id))->toBeNull()->and($sold->fresh()->is_active)->toBeFalse();
});

it('filters the product list by status and only shows own products', function () {
    $u = scSeller();
    scProduct($u, ['name' => 'Live One']);
    scProduct($u, ['name' => 'Waiting One', 'moderation_status' => 'pending']);
    scProduct($u, ['name' => 'Hidden One', 'is_active' => false]);

    $this->actingAs($u)->get('/seller/products?status=live')->assertSee('Live One')->assertDontSee('Waiting One')->assertDontSee('Hidden One');
    $this->actingAs($u)->get('/seller/products?status=review')->assertSee('Waiting One')->assertDontSee('Live One');
    $this->actingAs($u)->get('/seller/products?status=draft')->assertSee('Hidden One')->assertDontSee('Live One');
});

it('renders the add-product form on a phone with spare option rows and the photo rule', function () {
    $this->actingAs(scSeller())->get('/seller/products/create')->assertOk()
        ->assertSee('width=device-width', false)->assertSee('1:1 picture, at least 1000×1000 px')
        ->assertSee('Choose a subcategory')->assertSee('colors[2][value]', false)->assertSee('sizes[2][value]', false);
});

it('lists only subcategories that exist and never leaks sub-categories into the shop navigation', function () {
    expect(Category::whereNull('parent_id')->where('name', 'Fashion')->exists())->toBeTrue()
        ->and(Category::navigation()->pluck('name'))->not->toContain('Bags');
});
