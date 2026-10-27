<?php

use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function toyProduct(Category $cat, string $slug): Product
{
    return Product::create([
        'category_id' => $cat->id, 'name' => ucfirst($slug), 'slug' => $slug,
        'price' => 10, 'stock' => 5, 'is_active' => true,
    ]);
}

it('hides products of an inactive category everywhere on the storefront', function () {
    $live = Category::create(['name' => 'Puzzles', 'slug' => 'puzzles', 'is_active' => true]);
    $off  = Category::create(['name' => 'Plush',   'slug' => 'plush',   'is_active' => true]);
    $a = toyProduct($live, 'jigsaw');
    $b = toyProduct($off, 'teddy');

    expect(Product::visible()->pluck('slug')->all())->toBe(['jigsaw', 'teddy']);

    // A cart line added while the category was active.
    $cart = app(CartService::class);
    $cart->add($a, 1);
    $cart->add($b, 1);
    expect($cart->count())->toBe(2);

    $off->update(['is_active' => false]);

    expect(Product::visible()->pluck('slug')->all())->toBe(['jigsaw']);
    expect($cart->count())->toBe(1);                      // stale cart line pruned
    $this->get('/product-details/teddy')->assertNotFound();
    $this->get('/product-details/jigsaw')->assertOk();
    $this->get('/category/plush')->assertNotFound();
    expect(Product::find($b->id))->not->toBeNull();       // admin/order lookups still work
});
