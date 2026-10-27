<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use Database\Seeders\MarketplaceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function msProduct(bool $active): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create(['category_id' => $cat->id, 'name' => 'P ' . uniqid(), 'price' => 10, 'is_active' => $active]);
}

it('gives active products and all orders to MiniMart and inactive products to E19 Deli, deleting nothing', function () {
    $on = msProduct(true);
    $off = msProduct(false);
    $order = Order::create(['name' => 'A', 'email' => 'a@example.com', 'phone' => '1', 'address' => 'x', 'payment_method' => 'stripe',
        'payment_status' => 'paid', 'shipping' => 'free', 'subtotal' => 10, 'total' => 10, 'status' => 'processing', 'tracking_number' => 'T1']);
    $item = OrderItem::create(['order_id' => $order->id, 'product_id' => $on->id, 'name' => 'x', 'price' => 10, 'qty' => 1, 'total' => 10]);

    $this->seed(MarketplaceSeeder::class);

    $mini = Seller::where('slug', 'minimart')->first();
    $deli = Seller::where('slug', 'e19-deli')->first();

    expect($on->fresh()->seller_id)->toBe($mini->id)
        ->and($off->fresh()->seller_id)->toBe($deli->id)
        ->and($item->fresh()->seller_id)->toBe($mini->id)
        ->and($mini->isLive())->toBeTrue()->and($deli->isLive())->toBeTrue()
        ->and($mini->user->email_verified_at)->not->toBeNull()
        ->and(Product::count())->toBe(2)->and(Order::count())->toBe(1);

    // Storefront is unchanged: the active product stays visible, the inactive one stays hidden.
    expect(Product::visible()->pluck('id')->all())->toBe([$on->id]);
});

it('is safe to run twice and does not move a product that already has a seller', function () {
    $on = msProduct(true);
    $this->seed(MarketplaceSeeder::class);
    $this->seed(MarketplaceSeeder::class);

    expect(Seller::count())->toBe(2)->and(\App\Models\User::where('role', 'seller')->count())->toBe(2)
        ->and($on->fresh()->seller_id)->toBe(Seller::where('slug', 'minimart')->value('id'));
});
