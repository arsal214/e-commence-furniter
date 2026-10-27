<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\OrderSettlement;
use App\Services\ShipmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => Mail::fake());

function acUser(array $over = []): User
{
    return User::factory()->create($over + ['role' => 'customer', 'password' => 'correct-horse-1']);
}

function acStore(string $name = 'Ace Home'): Seller
{
    $u = User::factory()->create(['role' => 'seller']);

    return Seller::create(['user_id' => $u->id, 'store_name' => $name, 'slug' => str($name)->slug()->toString(), 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);
}

function acOrder(User $buyer, Seller $seller, string $product = 'Table Lamp', float $total = 60, string $payment = 'paid'): Order
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);
    $o = Order::create(['user_id' => $buyer->id, 'name' => $buyer->name, 'email' => $buyer->email, 'phone' => '1', 'address' => '1 Main St', 'payment_method' => 'stripe', 'payment_status' => 'pending',
        'shipping' => 'free', 'subtotal' => $total, 'total' => $total, 'status' => 'pending', 'tracking_number' => 'FRN-' . strtoupper(uniqid()), 'stripe_payment_intent' => 'pi_1']);
    $p = Product::create(['category_id' => $cat->id, 'seller_id' => $seller->id, 'name' => $product, 'slug' => str($product)->slug() . '-' . uniqid(), 'price' => $total, 'stock' => 5, 'is_active' => true]);
    OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'seller_id' => $seller->id, 'name' => $product, 'price' => $total, 'qty' => 1, 'total' => $total]);
    if ($payment === 'paid') {
        app(OrderSettlement::class)->markPaid($o->load('items'), 'stripe');
    }

    return $o->fresh();
}

// ── sign in and create account ───────────────────────────────────────────────────────

it('shows the sign-in page from the design, with no prototype values or social buttons', function () {
    $html = $this->get('/login')->assertOk()->assertSee('Welcome to Peyton Ghalib')->assertSee('name="email"', false)->assertSee('name="password"', false)
        ->assertSee('Forgot your password?')->assertSee('Sell with 0% commission')->assertSee('width=device-width', false)->getContent();

    expect($html)->not->toContain('jessica.m@example.com')->not->toContain('>Apple<')->not->toContain('>Google<')->not->toContain('200 bonus points');
    $this->get('/register')->assertOk()->assertSee('name="name"', false)->assertSee('At least 8 characters')->assertDontSee('Forgot your password?');
});

it('signs a shopper in and sends them to their account, an admin to the admin', function () {
    $u = acUser(['email' => 'pat@example.com']);
    $admin = acUser(['email' => 'boss@example.com', 'role' => 'admin']);

    $this->post('/login', ['email' => 'pat@example.com', 'password' => 'correct-horse-1'])->assertRedirect('/my-account');
    $this->assertAuthenticatedAs($u);
    auth()->logout();
    $this->post('/login', ['email' => 'boss@example.com', 'password' => 'correct-horse-1'])->assertRedirect('/admin');
});

it('refuses a wrong password with one message that does not say which part was wrong', function () {
    acUser(['email' => 'pat@example.com']);

    $this->post('/login', ['email' => 'pat@example.com', 'password' => 'nope-nope-1'])->assertSessionHasErrors('email');
    $this->post('/login', ['email' => 'nobody@example.com', 'password' => 'nope-nope-1'])->assertSessionHasErrors('email');
    $this->assertGuest();
    $this->get('/login')->assertSee('input', false); // the page still renders after the failures
});

it('creates an account with one password field and signs the new shopper in', function () {
    $this->post('/register', ['name' => 'Pat Buyer', 'email' => 'new@example.com', 'password' => 'a-long-password'])->assertRedirect('/my-account');

    $u = User::where('email', 'new@example.com')->first();
    expect($u->role)->toBe('customer')->and(Hash::check('a-long-password', $u->password))->toBeTrue();
    $this->assertAuthenticatedAs($u);
});

it('rejects a short password, a taken email, a missing name and an invalid email when creating an account', function () {
    acUser(['email' => 'taken@example.com']);

    $this->post('/register', ['name' => 'P', 'email' => 'a@example.com', 'password' => 'short'])->assertSessionHasErrors('password');
    $this->post('/register', ['name' => 'P', 'email' => 'taken@example.com', 'password' => 'a-long-password'])->assertSessionHasErrors('email');
    $this->post('/register', ['name' => '', 'email' => 'a@example.com', 'password' => 'a-long-password'])->assertSessionHasErrors('name');
    $this->post('/register', ['name' => 'P', 'email' => 'not-an-email', 'password' => 'a-long-password'])->assertSessionHasErrors('email');
    expect(User::count())->toBe(1);
});

it('never lets a self-registration choose the admin or seller role', function () {
    $this->post('/register', ['name' => 'Evil', 'email' => 'evil@example.com', 'password' => 'a-long-password', 'role' => 'admin']);

    expect(User::where('email', 'evil@example.com')->value('role'))->toBe('customer');
});

it('renders the password screens in the same card and validates them', function () {
    $this->get('/forgot-password')->assertOk()->assertSee('Reset your password')->assertSee('name="email"', false);
    $this->get('/reset-password/tok123?email=pat@example.com')->assertOk()->assertSee('Choose a new password')->assertSee('value="tok123"', false)->assertSee('value="pat@example.com"', false);
    $this->post('/forgot-password', ['email' => 'not-an-email'])->assertSessionHasErrors('email');
    $this->actingAs(acUser(['must_reset_password' => true]))->get('/account/set-password')->assertOk()->assertSee('Set your password');
});

// ── account ──────────────────────────────────────────────────────────────────────────

it('keeps guests out of every account page', function () {
    foreach (['/my-account', '/my-account/orders', '/my-account/wishlist', '/my-account/reviews', '/my-account/settings'] as $url) {
        $this->get($url)->assertRedirect(route('login'));
    }
    $this->post('/my-account/password')->assertRedirect(route('login'));
    $this->get('/my-account/payments')->assertNotFound();
});

it('shows the overview with the shoppers own name and numbers and no prototype data', function () {
    $u = acUser(['name' => 'Dana Lee']);
    acOrder($u, acStore(), 'Table Lamp');

    $html = $this->actingAs($u)->get('/my-account')->assertOk()->assertSee('Dana Lee')->assertSee('Member since')->assertSee('Table Lamp')->assertSee('Orders')->assertSee('Wishlist')->getContent();

    expect($html)->not->toContain('Jessica')->not->toContain('1,240')->not->toContain('Visa ••')->not->toContain('(212) 555');
});

it('says so on a new account with nothing yet', function () {
    $this->actingAs(acUser())->get('/my-account')->assertOk()->assertSee('No orders on the way right now')->assertSee('NOTHING ON THE WAY');
    $this->get('/my-account/orders')->assertOk();
    $this->get('/my-account/wishlist')->assertOk()->assertSee('Nothing saved yet');
});

it('lists only the shoppers own paid orders, grouped by store, with a tracking link', function () {
    $me = acUser();
    $other = acUser();
    $a = acStore('Ace Home');
    $b = acStore('Bee Goods');
    $mine = acOrder($me, $a, 'Table Lamp');
    acOrder($me, $b, 'Wool Rug');
    acOrder($other, $a, 'Secret Item');
    acOrder($me, $a, 'Unpaid Thing', payment: 'pending');

    app(ShipmentService::class)->markShipped(Shipment::where('order_id', $mine->id)->first(), 'USPS', '9400111122');

    $html = $this->actingAs($me)->get('/my-account/orders')->assertOk()->assertSee('Table Lamp')->assertSee('Wool Rug')->assertSee('Sold by')->assertSee('Ace Home')->assertSee('Bee Goods')
        ->assertSee('Shipped')->assertSee('Preparing')->assertSee('USPS · 9400111122')->assertSee('track-order?tracking=' . $mine->tracking_number, false)
        ->assertDontSee('Secret Item')->assertDontSee('Unpaid Thing')->assertDontSee('Message seller')->getContent();
    expect(substr_count($html, 'Track package'))->toBe(2);
});

it('escapes store and product names in the account', function () {
    $u = acUser();
    acOrder($u, acStore('<b>Evil</b> Shop'), '<img src=x onerror=alert(1)>');

    foreach (['/my-account', '/my-account/orders'] as $url) {
        $this->actingAs($u)->get($url)->assertOk()->assertDontSee('<b>Evil</b>', false)->assertDontSee('<img src=x onerror', false);
    }
});

it('shows the wishlist, hides what shoppers can no longer see, and removes an item', function () {
    $u = acUser();
    $s = acStore();
    $o = acOrder($u, $s, 'Kept Lamp');
    $hidden = acOrder($u, $s, 'Hidden Lamp');
    Product::where('name', 'Hidden Lamp')->update(['is_active' => false]);
    $kept = Product::where('name', 'Kept Lamp')->first();
    Wishlist::create(['user_id' => $u->id, 'product_id' => $kept->id]);
    Wishlist::create(['user_id' => $u->id, 'product_id' => Product::where('name', 'Hidden Lamp')->value('id')]);

    $this->actingAs($u)->get('/my-account/wishlist')->assertOk()->assertSee('Kept Lamp')->assertDontSee('Hidden Lamp')->assertSee('View product');
    $this->postJson('/wishlist/toggle', ['product_id' => $kept->id])->assertJson(['added' => false]);
    $this->get('/my-account/wishlist')->assertDontSee('Kept Lamp')->assertSee('Nothing saved yet');
});

it('asks for a review only after delivery, saves it, and then stops asking', function () {
    $u = acUser();
    $o = acOrder($u, acStore(), 'Table Lamp');
    $ship = Shipment::where('order_id', $o->id)->first();

    $this->actingAs($u)->get('/my-account/reviews')->assertOk()->assertSee('You are all caught up')->assertDontSee('Submit review');

    app(ShipmentService::class)->markShipped($ship, 'USPS', '9400111122');
    app(ShipmentService::class)->markDelivered($ship->fresh());
    $html = $this->get('/my-account/reviews')->assertOk()->assertSee('Waiting for your review')->assertSee('Table Lamp')->assertSee('Submit review')->getContent();

    $slug = Product::where('name', 'Table Lamp')->value('slug');
    $this->post(route('product.review.store', $slug), ['rating' => 5, 'comment' => 'Lovely lamp'])->assertSessionHas('success');
    $this->post(route('product.review.store', $slug), ['rating' => 9])->assertSessionHasErrors('rating');

    expect(Review::count())->toBe(1)->and(Review::first()->is_verified)->toBeTrue();
    $this->get('/my-account/reviews')->assertSee('Your reviews')->assertSee('Lovely lamp')->assertDontSee('Submit review');
});

it('changes the name and email, and unverifies a new email address', function () {
    $u = acUser(['email' => 'old@example.com', 'email_verified_at' => now()]);
    acUser(['email' => 'taken@example.com']);

    $this->actingAs($u)->post('/my-account/settings', ['name' => 'New Name', 'email' => 'taken@example.com'])->assertSessionHasErrors('email');
    $this->post('/my-account/settings', ['name' => 'New Name', 'email' => 'fresh@example.com'])->assertRedirect('/my-account/settings');

    expect($u->fresh()->name)->toBe('New Name')->and($u->fresh()->email)->toBe('fresh@example.com')->and($u->fresh()->email_verified_at)->toBeNull();
});

it('changes the password only with the right current password, and the new one works', function () {
    $u = acUser(['email' => 'pat@example.com']);

    $this->actingAs($u)->post('/my-account/password', ['current_password' => 'wrong', 'password' => 'brand-new-pass-1', 'password_confirmation' => 'brand-new-pass-1'])->assertSessionHasErrors('current_password');
    $this->post('/my-account/password', ['current_password' => 'correct-horse-1', 'password' => 'short', 'password_confirmation' => 'short'])->assertSessionHasErrors('password');
    $this->post('/my-account/password', ['current_password' => 'correct-horse-1', 'password' => 'brand-new-pass-1', 'password_confirmation' => 'different'])->assertSessionHasErrors('password');
    expect(Hash::check('correct-horse-1', $u->fresh()->password))->toBeTrue();

    $this->post('/my-account/password', ['current_password' => 'correct-horse-1', 'password' => 'brand-new-pass-1', 'password_confirmation' => 'brand-new-pass-1'])->assertRedirect('/my-account/settings');
    expect(Hash::check('brand-new-pass-1', $u->fresh()->password))->toBeTrue();
});

it('saves the marketing email choice either way', function () {
    $u = acUser();

    $this->actingAs($u)->post('/my-account/notifications', [])->assertRedirect('/my-account/settings');
    expect($u->fresh()->acceptsMarketing())->toBeFalse();
    $this->post('/my-account/notifications', ['marketing' => '1']);
    expect($u->fresh()->acceptsMarketing())->toBeTrue();
    $this->get('/my-account/settings')->assertOk()->assertSee('Change password')->assertSee('Send me deals and offers');
});

it('keeps the old account addresses working', function () {
    $this->actingAs(acUser());

    $this->get('/order-history')->assertRedirect('/my-account/orders');
    $this->get('/wishlist')->assertRedirect('/my-account/wishlist');
    $this->get('/edit-account')->assertRedirect('/my-account/settings');
});

it('signs the shopper out from the account page', function () {
    $u = acUser();
    $html = $this->actingAs($u)->get('/my-account')->assertSee('data-post="/logout"', false)->getContent();

    $this->post('/logout')->assertRedirect('/login');
    $this->assertGuest();
});
