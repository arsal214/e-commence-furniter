<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\PointEntry;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\User;
use App\Services\OrderSettlement;
use App\Services\SellerBilling;
use App\Services\ShipmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => Mail::fake());

function spSeller(string $name, array $over = []): User
{
    $u = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create($over + ['user_id' => $u->id, 'store_name' => $name, 'slug' => str($name)->slug()->toString(), 'status' => 'approved', 'subscription_status' => 'active',
        'onboarding_step' => 'done', 'stripe_account_id' => 'acct_9876', 'stripe_customer_id' => 'cus_1']);

    return $u;
}

function spOrder(User $u, float $total = 60): Shipment
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);
    $o = Order::create(['name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '1', 'address' => '1 Main St', 'city' => 'Jersey City', 'state' => 'NJ', 'zip' => '07302',
        'payment_method' => 'stripe', 'payment_status' => 'pending', 'shipping' => 'free', 'subtotal' => $total, 'total' => $total, 'status' => 'pending',
        'tracking_number' => 'FRN-' . strtoupper(uniqid()), 'stripe_payment_intent' => 'pi_1']);
    $p = Product::create(['category_id' => $cat->id, 'seller_id' => $u->seller->id, 'name' => 'Table Lamp', 'slug' => 'lamp-' . uniqid(), 'price' => $total, 'stock' => 5, 'is_active' => true]);
    OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'seller_id' => $u->seller->id, 'name' => 'Table Lamp', 'price' => $total, 'qty' => 1, 'total' => $total]);
    app(OrderSettlement::class)->markPaid($o->load('items'), 'stripe');

    return Shipment::where('order_id', $o->id)->sole();
}

// ── orders ───────────────────────────────────────────────────────────────────────────

it('shows what to ship, with the address the seller needs, and nothing from other stores', function () {
    $a = spSeller('Ace');
    $b = spSeller('Bee');
    spOrder($a);
    $other = spOrder($b);

    $this->actingAs($a)->get('/seller/orders')->assertOk()->assertSee('To ship (1)')->assertSee('Table Lamp × 1')->assertSee('Pat Buyer')->assertSee('1 Main St')
        ->assertSee('Mark as shipped')->assertDontSee('Buy USPS label')->assertDontSee('Message buyer');
    $this->actingAs($a)->get('/seller/orders?tab=shipped')->assertOk()->assertDontSee('Table Lamp');
    expect($other->order->tracking_number)->not->toBeNull();
    $this->actingAs($a)->get('/seller/orders')->assertDontSee($other->order->tracking_number);
});

it('marks a parcel shipped with tracking, emails the buyer and moves it to the shipped tab', function () {
    $a = spSeller('Ace');
    $s = spOrder($a);

    $this->actingAs($a)->post("/seller/orders/{$s->id}/ship", ['carrier' => 'USPS', 'tracking' => '9400 1118 9922'])->assertRedirect(route('seller.orders', ['tab' => 'shipped']));

    expect($s->fresh()->status)->toBe('shipped')->and($s->fresh()->tracking_number)->toBe('9400 1118 9922');
    Mail::assertSent(\App\Mail\OrderShippedMail::class, 1);
    $this->get('/seller/orders?tab=shipped')->assertSee('Shipped with USPS')->assertSee('9400 1118 9922')->assertSee('You earn');
});

it('refuses a second shipment, bad tracking, an unknown carrier, and another sellers parcel', function () {
    $a = spSeller('Ace');
    $b = spSeller('Bee');
    $mine = spOrder($a);
    $theirs = spOrder($b);

    $this->actingAs($a)->post("/seller/orders/{$mine->id}/ship", ['carrier' => 'Pigeon', 'tracking' => '123456'])->assertSessionHasErrors('carrier');
    $this->post("/seller/orders/{$mine->id}/ship", ['carrier' => 'UPS', 'tracking' => '<script>'])->assertSessionHasErrors('tracking');
    $this->post("/seller/orders/{$mine->id}/ship", ['carrier' => 'UPS', 'tracking' => '123'])->assertSessionHasErrors('tracking');
    $this->post("/seller/orders/{$theirs->id}/ship", ['carrier' => 'UPS', 'tracking' => '1Z999AA10123'])->assertNotFound();
    expect($theirs->fresh()->status)->toBe('pending');

    $this->post("/seller/orders/{$mine->id}/ship", ['carrier' => 'UPS', 'tracking' => '1Z999AA10123']);
    $this->post("/seller/orders/{$mine->id}/ship", ['carrier' => 'FedEx', 'tracking' => '7777777777'])->assertSessionHas('error');
    expect($mine->fresh()->carrier)->toBe('UPS');
});

it('cannot ship a parcel whose order was never paid', function () {
    $a = spSeller('Ace');
    $s = spOrder($a);
    $s->order->update(['payment_status' => 'pending']);

    $this->actingAs($a)->post("/seller/orders/{$s->id}/ship", ['carrier' => 'UPS', 'tracking' => '1Z999AA10123'])->assertNotFound();
});

it('keeps guests and shoppers out of the money pages', function () {
    foreach (['/seller/orders', '/seller/payouts', '/seller/levels', '/seller/billing', '/seller/payouts/export'] as $url) {
        $this->get($url)->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get($url)->assertRedirect(route('login'));
    }
    foreach (['/seller/points/redeem', '/seller/billing/portal', '/seller/payouts/bank'] as $url) {
        $this->post($url)->assertRedirect(route('login'));
    }
});

// ── payouts ──────────────────────────────────────────────────────────────────────────

it('shows what is coming and what was paid, with no made-up bank details', function () {
    $a = spSeller('Ace', ['level' => 'elite', 'delivered_sales' => 50000]);
    $s = spOrder($a, 100);
    app(ShipmentService::class)->markShipped($s, 'USPS', '9400111122');
    app(ShipmentService::class)->markDelivered($s->fresh());
    Payout::create(['seller_id' => $a->seller->id, 'amount' => 55.5, 'status' => 'paid', 'paid_at' => now(), 'release_at' => now()]);
    Payout::create(['seller_id' => $a->seller->id, 'amount' => 9.0, 'status' => 'failed', 'failure_reason' => 'Account closed', 'release_at' => now()]);

    $html = $this->actingAs($a)->get('/seller/payouts')->assertOk()->assertSee('NEXT PAYOUT')->assertSee('$96.80')->assertSee('Account ••9876')->assertDontSee('Stripe')->assertSee('$55.50')->assertSee('Failed: Account closed')
        ->assertSee('Elite Seller hold: same day')->assertSee('/seller/payouts/export', false)->getContent();
    expect($html)->not->toContain('Chase')->not->toContain('061000104')->not->toContain('Harbor');
});

it('exports payouts as a CSV that only holds the sellers own rows', function () {
    $a = spSeller('Ace');
    $b = spSeller('Bee');
    Payout::create(['seller_id' => $a->seller->id, 'amount' => 11.11, 'status' => 'paid', 'release_at' => now()]);
    Payout::create(['seller_id' => $b->seller->id, 'amount' => 99.99, 'status' => 'paid', 'release_at' => now()]);

    $csv = $this->actingAs($a)->get('/seller/payouts/export')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8')->streamedContent();

    expect($csv)->toContain('11.11')->not->toContain('99.99');
});

it('sends the seller to Stripe to change the bank, and says so when there is none', function () {
    $a = spSeller('Ace');
    $none = spSeller('None Co', ['stripe_account_id' => null]);
    $this->mock(SellerBilling::class)->shouldReceive('bankLoginUrl')->once()->andReturn('https://connect.stripe.test/express');

    $this->actingAs($a)->post('/seller/payouts/bank')->assertRedirect('https://connect.stripe.test/express');
    $this->actingAs($none)->post('/seller/payouts/bank')->assertRedirect(route('seller.bank'));
});

// ── levels and points ────────────────────────────────────────────────────────────────

it('shows the level, progress and the points history', function () {
    $a = spSeller('Ace', ['delivered_sales' => 980, 'level' => 'new', 'points' => 98]);
    PointEntry::create(['seller_id' => $a->seller->id, 'delta' => 33, 'reason' => 'Order FRN-1 delivered ($330.00)']);
    PointEntry::create(['seller_id' => $a->seller->id, 'delta' => -100, 'reason' => 'Redeemed for $1 off your plan']);

    $this->actingAs($a)->get('/seller/levels')->assertOk()->assertSee('New Seller · $980.00 lifetime sales')->assertSee('$20 more to Rising Seller')->assertSee('YOU ARE HERE')
        ->assertSee('Order FRN-1 delivered')->assertSee('+33')->assertSee('−100')->assertSee('$0.98')->assertDontSee('Rising Seller · $8,420');
});

it('redeems points as a credit on the plan, once, and keeps the remainder', function () {
    $a = spSeller('Ace', ['points' => 842]);
    $this->mock(SellerBilling::class)->shouldReceive('credit')->once()->withArgs(fn ($seller, $dollars) => $dollars === 8);

    $this->actingAs($a)->get('/seller')->assertOk()->assertSee('Redeem $8');
    $this->post('/seller/points/redeem')->assertSessionHas('status');
    $this->post('/seller/points/redeem')->assertSessionHas('error'); // 42 left: not enough

    expect($a->seller->fresh()->points)->toBe(42)->and(PointEntry::where('delta', -800)->count())->toBe(1);
});

it('leaves the points alone when Stripe cannot apply the credit', function () {
    $a = spSeller('Ace', ['points' => 500]);
    $this->mock(SellerBilling::class)->shouldReceive('credit')->andThrow(new RuntimeException('stripe down'));

    $this->actingAs($a)->post('/seller/points/redeem')->assertSessionHas('error');

    expect($a->seller->fresh()->points)->toBe(500)->and(PointEntry::count())->toBe(0);
});

// ── billing and the dashboard ────────────────────────────────────────────────────────

it('shows billing with real invoices and sends plan changes to Stripe', function () {
    $a = spSeller('Ace');
    $this->mock(SellerBilling::class, function ($m) {
        $m->shouldReceive('invoices')->andReturn([['d' => 'Oct 26, 2026', 'v' => '$19.99', 's' => 'Paid']]);
        $m->shouldReceive('portalUrl')->once()->andReturn('https://billing.stripe.test/p/1');
    });

    $html = $this->actingAs($a)->get('/seller/billing')->assertOk()->assertSee('Oct 26, 2026')->assertSee('Active')->assertSee('Update card or cancel')->getContent();
    expect($html)->not->toContain('Visa ••')->not->toContain('1099-K</b>')->not->toContain('Prototype');
    $this->post('/seller/billing/portal')->assertRedirect('https://billing.stripe.test/p/1');
});

it('has no prototype text or fake buttons on the dashboard and frame', function () {
    $a = spSeller('Ace Home');
    $html = $this->actingAs($a)->get('/seller')->assertOk()->assertSee('Ace Home')->assertSee('Sign out')->getContent();

    foreach (['Harbor', 'Prototype', 'Visa ••', 'Chase', '$2,960.40', '14,982'] as $fake) {
        expect($html)->not->toContain($fake);
    }
});
