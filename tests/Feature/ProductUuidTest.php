<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

const PU_ID = 54321; // a row number that is easy to spot if a page ever prints it

function puProduct(): Product
{
    $u = User::factory()->create(['role' => 'seller']);
    $s = Seller::create(['user_id' => $u->id, 'store_name' => 'Maple', 'slug' => 'maple', 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);
    $c = Category::create(['name' => 'Home', 'slug' => 'home', 'is_active' => true]);

    return Product::forceCreate(['id' => PU_ID, 'seller_id' => $s->id, 'category_id' => $c->id, 'name' => 'Linen Cushion', 'slug' => 'linen-cushion', 'price' => 30, 'stock' => 9,
        'is_active' => true, 'moderation_status' => 'approved']);
}

it('gives every product a uuid, a fresh one for a copy', function () {
    $p = puProduct();
    expect($p->uuid)->toMatch('/^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/');

    $copy = $p->replicate();
    $copy->slug = 'copy';
    $copy->save();
    expect($copy->uuid)->not->toBe($p->uuid)->and(Product::fromRef(strtolower($p->uuid))->id)->toBe($p->id)->and(Product::fromRef('nope'))->toBeNull();
});

it('never prints a product\'s row number in what a shopper\'s browser posts', function () {
    $p = puProduct();
    $this->post(route('cart.add'), ['product_id' => $p->uuid, 'qty' => 2])->assertSessionHas('success');

    foreach (['/', '/shop', '/category/home', $p->seller->url(), route('product-details', $p->slug), '/cart', '/search?q=cushion'] as $url) {
        $html = $this->get($url)->assertOk()->getContent();
        expect($html)->not->toContain('data-id="' . PU_ID . '"')->not->toContain('name="product_id" value="' . PU_ID . '"')->not->toContain(PU_ID . '||')
            ->not->toContain('value="' . PU_ID . '"');
    }
    $this->get(route('product-details', $p->slug))->assertSee('name="product_id" value="' . $p->uuid . '"', false);
});

it('updates and removes a cart line by the opaque ref the cart page prints', function () {
    $p = puProduct();
    $this->post(route('cart.add'), ['product_id' => $p->uuid, 'qty' => 1]);

    preg_match('/name="cart_key" value="([0-9a-f]{24})"/', $this->get('/cart')->getContent(), $m);
    expect($m[1] ?? null)->not->toBeNull();

    $this->post(route('cart.update'), ['cart_key' => $m[1], 'qty' => 3]);
    expect(array_values(session('cart'))[0]['qty'])->toBe(3);
    $this->post(route('cart.remove'), ['cart_key' => $m[1]]);
    expect(session('cart'))->toBe([]);
});

it('adds to the wishlist by uuid and answers with uuids only', function () {
    $p = puProduct();
    $u = User::factory()->create();

    $this->actingAs($u)->postJson('/wishlist/toggle', ['product_id' => $p->uuid])->assertOk()->assertJson(['added' => true, 'wishlist_ids' => [$p->uuid]]);
    expect(Wishlist::where('user_id', $u->id)->value('product_id'))->toBe($p->id);
    $this->actingAs($u)->postJson('/wishlist/toggle', ['product_id' => $p->uuid])->assertJson(['added' => false]);
    $this->actingAs($u)->postJson('/wishlist/toggle', ['product_id' => 'junk'])->assertNotFound();
});
