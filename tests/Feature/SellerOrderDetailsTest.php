<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Services\ShipmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function sodSeller(string $slug = 'maple'): User
{
    $u = User::factory()->create(['role' => 'seller', 'email_verified_at' => now(), 'name' => 'Maple Owner']);
    Seller::create(['user_id' => $u->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);

    return $u;
}

function sodOrder(Seller $seller, array $over = []): Order
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);
    $p = Product::firstOrCreate(['slug' => 'linen-cushion'], ['seller_id' => $seller->id, 'category_id' => $cat->id, 'name' => 'Linen Cushion', 'price' => 30, 'stock' => 9, 'is_active' => true, 'moderation_status' => 'approved']);
    $o = Order::forceCreate($over + ['name' => 'Pat Buyer', 'email' => 'secret.buyer@example.com', 'phone' => '555-123-4567', 'address' => '1 Main St', 'address2' => 'Apt 4', 'city' => 'Jersey City', 'state' => 'NJ',
        'zip' => '07302', 'country' => 'US', 'notes' => 'Leave with the doorman', 'payment_method' => 'stripe', 'payment_status' => 'paid', 'shipping' => 'free', 'subtotal' => 60, 'total' => 60,
        'status' => 'processing', 'tracking_number' => 'T-' . uniqid()]);
    OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'seller_id' => $seller->id, 'name' => 'Linen Cushion', 'color' => 'Sage', 'size' => '18 in', 'sku' => 'CUSH-18-S', 'price' => 30, 'qty' => 2, 'total' => 60]);
    app(ShipmentService::class)->createForOrder($o);

    return $o;
}

it('shows a seller what to pack and where to send it, without the buyer\'s email', function () {
    $u = sodSeller();
    sodOrder($u->seller);

    $this->actingAs($u)->get('/seller/orders')->assertOk()
        ->assertSee('Linen Cushion')->assertSee('Color: Sage')->assertSee('Size: 18 in')->assertSee('SKU CUSH-18-S')->assertSee('2 × $30.00', false)
        ->assertSee('Pat Buyer')->assertSee('1 Main St')->assertSee('Apt 4')->assertSee('Jersey City, NJ 07302')->assertSee('555-123-4567')
        ->assertSee('Customer note:')->assertSee('Leave with the doorman')->assertSee('Payment: Paid')
        ->assertDontSee('secret.buyer@example.com')->assertDontSee('secret.buyer');
});

it('omits the note block when there is no note and never shows an unpaid order', function () {
    $u = sodSeller();
    sodOrder($u->seller, ['notes' => null]);
    sodOrder($u->seller, ['payment_status' => 'pending', 'phone' => '999-000-1111']);

    $this->actingAs($u)->get('/seller/orders')->assertOk()->assertDontSee('Customer note:')->assertDontSee('999-000-1111');
});

it('sends a store owner to Seller Center after sign-in and in the header, shoppers to their account', function () {
    $seller = sodSeller();
    $buyer = User::factory()->create(['role' => 'customer']);

    $this->post('/login', ['email' => $seller->email, 'password' => 'password'])->assertRedirect('/seller');
    auth()->logout();
    $this->post('/login', ['email' => $buyer->email, 'password' => 'password'])->assertRedirect('/my-account');
    auth()->logout();

    $this->actingAs($seller)->get('/my-account')->assertRedirect('/seller');
    $this->actingAs($seller)->get('/my-account/settings')->assertOk(); // password and email changes stay reachable
    $this->actingAs($seller)->get('/')->assertOk()->assertSee('Seller Center')->assertDontSee('Sell on Peyton Ghalib');

    $this->actingAs($buyer)->get('/my-account')->assertOk();
    $this->actingAs($buyer)->get('/')->assertOk()->assertSee('Sell on Peyton Ghalib')->assertDontSee('Seller Center');
});
