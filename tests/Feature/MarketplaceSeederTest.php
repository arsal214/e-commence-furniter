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

it('gives old paid orders a shipment, settles delivered history so it is never paid out again, and opens Seller Center', function () {
    $on = msProduct(true);
    $mk = function (string $no, string $status, string $pay) use ($on) {
        $o = Order::create(['name' => 'A', 'email' => 'a@example.com', 'phone' => '1', 'address' => 'x', 'payment_method' => 'stripe',
            'payment_status' => $pay, 'shipping' => 'free', 'subtotal' => 50, 'total' => 50, 'status' => $status, 'tracking_number' => $no]);
        OrderItem::create(['order_id' => $o->id, 'product_id' => $on->id, 'name' => 'x', 'price' => 50, 'qty' => 1, 'total' => 50]);

        return $o;
    };
    $delivered = $mk('T-DONE', 'delivered', 'paid');
    $open = $mk('T-OPEN', 'processing', 'paid');
    $refunded = $mk('T-REFUND', 'cancelled', 'paid');
    $unpaid = $mk('T-UNPAID', 'cancelled', 'pending');

    $this->seed(MarketplaceSeeder::class);
    $this->seed(MarketplaceSeeder::class); // still one shipment per order

    $mini = Seller::where('slug', 'minimart')->first();
    $status = fn (Order $o) => \App\Models\Shipment::where('order_id', $o->id)->value('status');
    expect($status($delivered))->toBe('delivered')->and($status($open))->toBe('pending')->and($status($refunded))->toBe('refunded')
        ->and(\App\Models\Shipment::where('order_id', $unpaid->id)->exists())->toBeFalse()
        ->and(\App\Models\Shipment::count())->toBe(3);

    $ship = \App\Models\Shipment::where('order_id', $delivered->id)->first();
    expect($ship->payout_id)->not->toBeNull()->and($ship->release_at)->not->toBeNull()
        ->and(\App\Models\Payout::count())->toBe(1)->and(\App\Models\Payout::first()->status)->toBe('paid')
        ->and((float) $mini->fresh()->delivered_sales)->toBe(50.0)->and($mini->fresh()->points)->toBe(0)
        ->and($mini->onboarding_step)->toBe('done');

    $this->actingAs($mini->user)->get('/seller/payouts')->assertOk();
    $this->actingAs($mini->user)->get('/seller/orders')->assertOk();
});

it('is safe to run twice and does not move a product that already has a seller', function () {
    $on = msProduct(true);
    $this->seed(MarketplaceSeeder::class);
    $this->seed(MarketplaceSeeder::class);

    expect(Seller::count())->toBe(2)->and(\App\Models\User::where('role', 'seller')->count())->toBe(2)
        ->and($on->fresh()->seller_id)->toBe(Seller::where('slug', 'minimart')->value('id'));
});
