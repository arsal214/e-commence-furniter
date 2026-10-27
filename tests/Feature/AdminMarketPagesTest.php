<?php

use App\Models\Category;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\Slider;
use App\Models\User;
use App\Services\OrderSettlement;
use App\Services\StripeTransfers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => Mail::fake());

function apAdmin(): User { return User::factory()->create(['role' => 'admin']); }

function apSeller(string $name, array $over = []): Seller
{
    $u = User::factory()->create(['role' => 'seller']);

    return Seller::create($over + ['user_id' => $u->id, 'store_name' => $name, 'slug' => str($name)->slug()->toString(), 'status' => 'approved', 'subscription_status' => 'active',
        'onboarding_step' => 'done', 'stripe_account_id' => 'acct_1234']);
}

function apOrder(Seller $s, float $total = 50, string $method = 'stripe'): Order
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);
    $o = Order::create(['name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '1', 'address' => 'x', 'payment_method' => $method, 'payment_status' => 'pending', 'shipping' => 'free',
        'subtotal' => $total, 'total' => $total, 'status' => 'pending', 'tracking_number' => 'FRN-' . strtoupper(uniqid()), 'stripe_payment_intent' => 'pi_1']);
    $p = Product::create(['category_id' => $cat->id, 'seller_id' => $s->id, 'name' => 'Lamp ' . uniqid(), 'slug' => 'lamp-' . uniqid(), 'price' => $total, 'stock' => 5, 'is_active' => true]);
    OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'seller_id' => $s->id, 'name' => $p->name, 'price' => $total, 'qty' => 1, 'total' => $total]);
    app(OrderSettlement::class)->markPaid($o->load('items'), $method);

    return $o->fresh();
}

it('keeps every marketplace admin page behind the admin login', function () {
    $d = Dispute::create(['order_id' => apOrder(apSeller('Ace'))->id, 'seller_id' => Seller::first()->id, 'reason' => 'damaged', 'details' => 'x', 'amount' => 5, 'status' => 'open']);
    $slider = Slider::create(['title' => 'S', 'is_active' => true]);
    $pages = ['/admin', '/admin/disputes', '/admin/payouts', '/admin/subscriptions', '/admin/marketing', '/admin/reports'];

    foreach ([null, User::factory()->create(), Seller::first()->user] as $who) {
        foreach ($pages as $u) { ($who ? $this->actingAs($who) : $this)->get($u)->assertRedirect(route('login')); }
        ($who ? $this->actingAs($who) : $this)->post("/admin/disputes/{$d->id}", ['action' => 'refund'])->assertRedirect(route('login'));
        ($who ? $this->actingAs($who) : $this)->post("/admin/marketing/banners/{$slider->id}")->assertRedirect(route('login'));
    }
    expect($d->fresh()->status)->toBe('open')->and($slider->fresh()->is_active)->toBeTrue();
});

it('shows the overview with live counts and what needs attention', function () {
    $s = apSeller('Ace');
    apSeller('Waiting Co', ['status' => 'in_review', 'submitted_at' => now()]);
    Dispute::create(['order_id' => apOrder($s)->id, 'seller_id' => $s->id, 'reason' => 'not_received', 'details' => 'x', 'amount' => 5, 'status' => 'open']);
    apSeller('Late Co', ['subscription_status' => 'past_due']);

    $this->actingAs(apAdmin())->get('/admin')->assertOk()->assertSee('Seller applications waiting')->assertSee('Open disputes')->assertSee('Waiting Co')
        ->assertSee('Item not received')->assertSee('1 seller with failed payments')->assertSee('Seller levels');
});

it('lists claims and refunds the buyer in full, once', function () {
    $s = apSeller('Ace');
    $order = apOrder($s, 50);
    $d = Dispute::create(['order_id' => $order->id, 'seller_id' => $s->id, 'user_id' => null, 'email' => 'pat@example.com', 'reason' => 'damaged', 'details' => 'Arrived broken', 'amount' => 50, 'status' => 'open']);
    $this->mock(StripeTransfers::class)->shouldReceive('refund')->once()->andReturn('re_9');
    $admin = apAdmin();

    $this->actingAs($admin)->get('/admin/disputes')->assertOk()->assertSee('Damaged on arrival')->assertSee('$50.00')->assertSee('Arrived broken')->assertSee('Refund buyer in full');

    $this->post("/admin/disputes/{$d->id}", ['action' => 'refund'])->assertSessionHas('success');
    $this->post("/admin/disputes/{$d->id}", ['action' => 'refund'])->assertSessionHas('error');
    expect($d->fresh()->status)->toBe('refunded')->and(Shipment::sole()->status)->toBe('refunded');
    $this->get('/admin/disputes?tab=resolved')->assertSee('Refunded');
});

it('offers the manual PayPal refund only for PayPal orders and rejects unknown actions', function () {
    $s = apSeller('Ace');
    $order = apOrder($s, 40, 'paypal');
    $d = Dispute::create(['order_id' => $order->id, 'seller_id' => $s->id, 'reason' => 'damaged', 'details' => 'x', 'amount' => 40, 'status' => 'open']);

    $this->actingAs(apAdmin())->get('/admin/disputes')->assertSee('I refunded in PayPal');
    $this->post("/admin/disputes/{$d->id}", ['action' => 'delete-everything'])->assertSessionHasErrors('action');
    $this->post("/admin/disputes/{$d->id}", ['action' => 'refund_manual'])->assertSessionHas('success');
    expect($d->fresh()->status)->toBe('refunded');
});

it('closes in the sellers favour with a note for the buyer', function () {
    $s = apSeller('Ace');
    $d = Dispute::create(['order_id' => apOrder($s)->id, 'seller_id' => $s->id, 'email' => 'pat@example.com', 'reason' => 'not_received', 'details' => 'x', 'amount' => 50, 'status' => 'open']);

    $this->actingAs(apAdmin())->post("/admin/disputes/{$d->id}", ['action' => 'seller', 'note' => 'Carrier confirms delivery'])->assertSessionHas('success');

    expect($d->fresh()->status)->toBe('closed_seller')->and($d->fresh()->resolution_note)->toBe('Carrier confirms delivery');
});

it('shows payouts with real totals and failed transfers', function () {
    $s = apSeller('Ace', ['level' => 'top']);
    Payout::create(['seller_id' => $s->id, 'amount' => 123.45, 'status' => 'paid', 'paid_at' => now(), 'release_at' => now()]);
    Payout::create(['seller_id' => $s->id, 'amount' => 9.99, 'status' => 'failed', 'failure_reason' => 'Account closed', 'release_at' => now()]);

    $this->actingAs(apAdmin())->get('/admin/payouts')->assertOk()->assertSee('Paid out today')->assertSee('$123.45')->assertSee('Account closed')->assertSee('Top Seller')->assertSee('hold 1 day');
});

it('shows subscriptions with monthly revenue and who is hidden', function () {
    apSeller('Ace');
    apSeller('Bee');
    apSeller('Late Co', ['subscription_status' => 'past_due', 'subscription_event_at' => now()]);

    $this->actingAs(apAdmin())->get('/admin/subscriptions')->assertOk()->assertSee('MRR $39.98')->assertSee('Payment failed · hidden')->assertSee('Late Co');
});

it('turns a homepage banner on and off', function () {
    $b = Slider::create(['title' => 'Fall sale', 'button_text' => 'Shop now', 'is_active' => true]);
    $admin = apAdmin();

    $this->actingAs($admin)->get('/admin/marketing')->assertOk()->assertSee('Fall sale')->assertSee('Live · turn off');
    $this->post("/admin/marketing/banners/{$b->id}")->assertSessionHas('success');
    expect($b->fresh()->is_active)->toBeFalse();
    $this->get('/admin/marketing')->assertSee('Hidden · go live');
    $this->get('/')->assertDontSee('Fall sale');
});

it('puts the marketplace pages in the admin menu with the design colours', function () {
    $html = $this->actingAs(apAdmin())->get('/admin/payouts')->getContent();

    foreach (['Overview', 'Seller approvals', 'Product moderation', 'Disputes &amp; refunds', 'Payouts', 'Subscriptions', 'Banners &amp; coupons', 'Store dashboard'] as $label) {
        expect($html)->toContain($label);
    }
    expect($html)->toContain('#0F172A')->not->toContain('#bb976d');
});

// ── buyer side of a claim ────────────────────────────────────────────────────────────

it('lets a buyer open a claim from the tracking page with the right email, and only once', function () {
    $s = apSeller('Ace');
    $order = apOrder($s, 50);
    $url = route('claim.store', $order->tracking_number);

    $this->get(route('track-order', ['tracking' => $order->tracking_number]))->assertOk()->assertSee('Open a Buyer Protection claim');
    $this->post($url, ['seller_id' => $s->id, 'email' => 'wrong@example.com', 'reason' => 'damaged', 'details' => 'It came broken'])->assertSessionHas('error');
    $this->post($url, ['seller_id' => $s->id, 'email' => 'pat@example.com', 'reason' => 'damaged', 'details' => 'It came broken'])->assertRedirect()->assertSessionHas('success');
    $this->post($url, ['seller_id' => $s->id, 'email' => 'pat@example.com', 'reason' => 'damaged', 'details' => 'Trying again'])->assertSessionHas('error');

    expect(Dispute::count())->toBe(1);
    $this->get(route('track-order', ['tracking' => $order->tracking_number]))->assertSee('we are reviewing it')->assertDontSee('Open a Buyer Protection claim');
});

it('validates the claim form and gives the same answer for an unknown order', function () {
    $s = apSeller('Ace');
    $order = apOrder($s);

    $this->post(route('claim.store', $order->tracking_number), ['seller_id' => $s->id, 'email' => 'pat@example.com', 'reason' => 'meh', 'details' => 'short'])->assertSessionHasErrors(['reason', 'details']);
    $this->post(route('claim.store', 'FRN-0000-NOPE'), ['seller_id' => $s->id, 'email' => 'pat@example.com', 'reason' => 'damaged', 'details' => 'Long enough text'])
        ->assertSessionHas('error', 'That email does not match this order.');
    expect(Dispute::count())->toBe(0);
});
