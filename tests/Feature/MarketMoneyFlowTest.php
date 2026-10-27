<?php

use App\Models\Category;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\User;
use App\Services\DisputeService;
use App\Services\OrderSettlement;
use App\Services\PayoutService;
use App\Services\ShipmentService;
use App\Services\StripeTransfers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => Mail::fake());

function mfSeller(string $name, array $over = []): Seller
{
    $u = User::factory()->create(['role' => 'seller']);

    return Seller::create($over + ['user_id' => $u->id, 'store_name' => $name, 'slug' => str($name)->slug()->toString(), 'status' => 'approved',
        'subscription_status' => 'active', 'onboarding_step' => 'done', 'stripe_account_id' => 'acct_' . str($name)->slug()]);
}

/** A paid order: [seller|null => line total, ...]. Shipping is included in the card total. */
function mfOrder(array $lines, float $shipping = 0, string $method = 'stripe'): Order
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);
    $subtotal = array_sum($lines);
    $o = Order::create(['name' => 'Pat', 'email' => 'pat@example.com', 'phone' => '1', 'address' => 'x', 'payment_method' => $method, 'payment_status' => 'pending',
        'shipping' => 'free', 'subtotal' => $subtotal, 'shipping_cost' => $shipping, 'total' => $subtotal + $shipping, 'status' => 'pending', 'tracking_number' => 'T-' . uniqid(),
        'stripe_payment_intent' => 'pi_' . uniqid()]);
    foreach ($lines as $sellerId => $total) {
        $p = Product::create(['category_id' => $cat->id, 'seller_id' => $sellerId ?: null, 'name' => 'P' . uniqid(), 'slug' => 'p' . uniqid(), 'price' => $total, 'stock' => 9, 'is_active' => true]);
        OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'seller_id' => $sellerId ?: null, 'name' => $p->name, 'price' => $total, 'qty' => 1, 'total' => $total]);
    }

    return $o;
}

function mfPaid(array $lines, float $shipping = 0): Order
{
    $o = mfOrder($lines, $shipping);
    app(OrderSettlement::class)->markPaid($o->load('items'), 'stripe');

    return $o->fresh();
}

function mfDeliver(Shipment $s): Shipment
{
    $svc = app(ShipmentService::class);
    $svc->markShipped($s, 'USPS', '9400' . $s->id);
    $svc->markDelivered($s->fresh());

    return $s->fresh();
}

// ── shipments and what sellers earn ──────────────────────────────────────────────────

it('creates one shipment per seller when an order is paid, with the card fee shared by what each earned', function () {
    $a = mfSeller('Ace');
    $b = mfSeller('Bee');
    $order = mfPaid([$a->id => 60, $b->id => 40, 0 => 100]); // a house item too: fee share goes with it

    $sa = Shipment::where('seller_id', $a->id)->sole();
    $sb = Shipment::where('seller_id', $b->id)->sole();
    // Whole-order fee: 2.9% of $200 + $0.30 = $6.10, split 30% / 20% / 50%.
    expect((float) $sa->gross)->toBe(60.0)->and((float) $sa->fee)->toBe(1.83)->and((float) $sa->net)->toBe(58.17)
        ->and((float) $sb->gross)->toBe(40.0)->and((float) $sb->fee)->toBe(1.22)->and((float) $sb->net)->toBe(38.78)
        ->and(Shipment::count())->toBe(2)->and($sa->status)->toBe('pending');
});

it('does not create a second shipment when the payment is reported twice', function () {
    $a = mfSeller('Ace');
    $order = mfPaid([$a->id => 50]);

    app(OrderSettlement::class)->markPaid($order->load('items'), 'stripe');
    app(ShipmentService::class)->createForOrder($order);

    expect(Shipment::count())->toBe(1);
});

it('never lets the fee exceed what the seller earned on a tiny order', function () {
    $a = mfSeller('Ace');
    mfPaid([$a->id => 0.20]);

    $s = Shipment::sole();
    expect((float) $s->net)->toBeGreaterThanOrEqual(0.0)->and((float) $s->fee)->toBeLessThanOrEqual(0.20);
});

it('ships once with tracking, and marks the order shipped only when every seller has shipped', function () {
    $a = mfSeller('Ace');
    $b = mfSeller('Bee');
    $order = mfPaid([$a->id => 30, $b->id => 30]);
    $svc = app(ShipmentService::class);
    $sa = Shipment::where('seller_id', $a->id)->first();
    $sb = Shipment::where('seller_id', $b->id)->first();

    expect($svc->markShipped($sa, 'USPS', '9400111'))->toBeTrue()->and($svc->markShipped($sa->fresh(), 'UPS', '1Z999'))->toBeFalse();
    expect($sa->fresh()->tracking_number)->toBe('9400111')->and($order->fresh()->status)->toBe('processing');
    Mail::assertSent(\App\Mail\OrderShippedMail::class, 1);

    $svc->markShipped($sb, 'UPS', '1Z1');
    expect($order->fresh()->status)->toBe('shipped');
});

it('leaves an order with house items to staff even when all seller parcels shipped', function () {
    $a = mfSeller('Ace');
    $order = mfPaid([$a->id => 30, 0 => 20]);

    app(ShipmentService::class)->markShipped(Shipment::sole(), 'USPS', '9400');

    expect($order->fresh()->status)->toBe('processing');
});

// ── delivery, levels, points, hold ───────────────────────────────────────────────────

it('adds sales and points once on delivery and sets the payout hold for the sellers level', function () {
    $a = mfSeller('Ace', ['delivered_sales' => 980]); // $20 short of Rising
    mfPaid([$a->id => 50]);
    $s = Shipment::sole();

    mfDeliver($s);
    app(ShipmentService::class)->markDelivered($s->fresh()); // a second call changes nothing

    $a->refresh();
    expect((float) $a->delivered_sales)->toBe(1030.0)->and($a->level)->toBe('rising')->and($a->points)->toBe(5);
    expect($s->fresh()->release_at->isSameDay(now()->addDays(2)))->toBeTrue(); // Rising: 2 days
});

it('holds a new seller 3 days and an elite seller none', function () {
    $new = mfSeller('New One');
    $elite = mfSeller('Elite One', ['level' => 'elite', 'delivered_sales' => 50000]);
    mfPaid([$new->id => 20]);
    mfPaid([$elite->id => 20]);

    $n = mfDeliver(Shipment::where('seller_id', $new->id)->first());
    $e = mfDeliver(Shipment::where('seller_id', $elite->id)->first());

    expect($n->release_at->isSameDay(now()->addDays(3)))->toBeTrue()->and($e->release_at->isSameDay(now()))->toBeTrue();
});

it('cannot deliver a parcel that never shipped', function () {
    $a = mfSeller('Ace');
    mfPaid([$a->id => 20]);

    expect(app(ShipmentService::class)->markDelivered(Shipment::sole()))->toBeFalse()->and($a->fresh()->points)->toBe(0);
});

it('counts a parcel as delivered after the carrier window, but not while the buyer has an open claim', function () {
    $a = mfSeller('Ace');
    $o1 = mfPaid([$a->id => 20]);
    $o2 = mfPaid([$a->id => 20]);
    $svc = app(ShipmentService::class);
    foreach (Shipment::all() as $s) { $svc->markShipped($s, 'USPS', '94' . $s->id); }

    expect($svc->autoDeliverDue())->toBe(0); // not yet

    $this->travel(8)->days();
    $s2 = Shipment::where('order_id', $o2->id)->first();
    Dispute::create(['order_id' => $o2->id, 'seller_id' => $a->id, 'reason' => 'not_received', 'details' => 'x', 'amount' => 20, 'status' => 'open']);

    expect($svc->autoDeliverDue())->toBe(1)->and(Shipment::where('order_id', $o1->id)->value('status'))->toBe('delivered')->and($s2->fresh()->status)->toBe('shipped');
});

// ── payouts ──────────────────────────────────────────────────────────────────────────

it('pays a seller once the hold has passed, and never twice', function () {
    $a = mfSeller('Ace');
    mfPaid([$a->id => 100]);
    mfDeliver(Shipment::sole());
    $this->mock(StripeTransfers::class)->shouldReceive('transfer')->once()->andReturn('tr_1');

    expect(app(PayoutService::class)->releaseDue())->toBe(0); // hold not over

    $this->travel(4)->days();
    expect(app(PayoutService::class)->releaseDue())->toBe(1)->and(app(PayoutService::class)->releaseDue())->toBe(0);

    $p = Payout::sole();
    expect($p->status)->toBe('paid')->and($p->stripe_transfer_id)->toBe('tr_1')->and((float) $p->amount)->toBe(96.8)->and(Shipment::sole()->payout_id)->toBe($p->id); // 100 - (2.9 + 0.30)
});

it('adds several delivered parcels into one payout', function () {
    $a = mfSeller('Ace', ['level' => 'elite', 'delivered_sales' => 50000]);
    foreach ([30, 40] as $t) { mfPaid([$a->id => $t]); }
    foreach (Shipment::all() as $s) { mfDeliver($s); }
    $this->mock(StripeTransfers::class)->shouldReceive('transfer')->once()->andReturn('tr_1');

    app(PayoutService::class)->releaseDue();

    expect(Payout::count())->toBe(1)->and((float) Payout::sole()->amount)->toBe(round(70 - (30 * .029 + .30) - (40 * .029 + .30), 2));
});

it('records a failed transfer, frees the parcels, and pays them on the next run', function () {
    $a = mfSeller('Ace', ['level' => 'elite', 'delivered_sales' => 50000]);
    mfPaid([$a->id => 50]);
    mfDeliver(Shipment::sole());
    $mock = $this->mock(StripeTransfers::class);
    $mock->shouldReceive('transfer')->once()->andThrow(new RuntimeException('Account closed'));
    $mock->shouldReceive('transfer')->once()->andReturn('tr_ok');

    expect(app(PayoutService::class)->releaseDue())->toBe(0);
    expect(Payout::sole()->status)->toBe('failed')->and(Payout::sole()->failure_reason)->toContain('Account closed')->and(Shipment::sole()->payout_id)->toBeNull();

    expect(app(PayoutService::class)->releaseDue())->toBe(1);
    expect(Payout::where('status', 'paid')->count())->toBe(1);
});

it('skips a seller with no bank connected and keeps the money for later', function () {
    $a = mfSeller('Ace', ['level' => 'elite', 'delivered_sales' => 50000, 'stripe_account_id' => null]);
    mfPaid([$a->id => 50]);
    mfDeliver(Shipment::sole());
    $this->mock(StripeTransfers::class)->shouldNotReceive('transfer');

    expect(app(PayoutService::class)->releaseDue())->toBe(0)->and(Payout::count())->toBe(0)->and(Shipment::sole()->payout_id)->toBeNull();
});

it('still pays out when the plan lapsed: earned money is the sellers', function () {
    $a = mfSeller('Ace', ['level' => 'elite', 'delivered_sales' => 50000, 'subscription_status' => 'past_due']);
    mfPaid([$a->id => 50]);
    mfDeliver(Shipment::sole());
    $this->mock(StripeTransfers::class)->shouldReceive('transfer')->once()->andReturn('tr_1');

    expect(app(PayoutService::class)->releaseDue())->toBe(1);
});

// ── claims ───────────────────────────────────────────────────────────────────────────

it('validates who can open a claim, and when', function () {
    $a = mfSeller('Ace');
    $order = mfPaid([$a->id => 50]);
    $svc = app(DisputeService::class);

    expect($svc->open($order, $a->id, 'someone@else.com', 'not_received', 'x'))->toBe('That email does not match this order.');
    expect($svc->open($order, 99999, 'pat@example.com', 'not_received', 'x'))->toBe('There is nothing to claim for this seller.');

    $first = $svc->open($order, $a->id, 'PAT@example.com', 'not_received', 'Never came');
    expect($first)->toBeInstanceOf(Dispute::class)->and((float) $first->amount)->toBe(50.0);
    expect($svc->open($order, $a->id, 'pat@example.com', 'damaged', 'again'))->toBe('A claim is already open for this order.');

    $old = mfPaid([$a->id => 20]);
    $old->forceFill(['created_at' => now()->subDays(100)])->save();
    expect($svc->open($old, $a->id, 'pat@example.com', 'damaged', 'x'))->toBe('This order is past the claim window.');

    $unpaid = mfOrder([$a->id => 20]);
    expect($svc->open($unpaid, $a->id, 'pat@example.com', 'damaged', 'x'))->toBe('Only paid orders can be claimed.');
});

it('refunds the buyer, voids the sellers payout for those items, and decides only once', function () {
    $a = mfSeller('Ace', ['level' => 'elite', 'delivered_sales' => 50000]);
    $order = mfPaid([$a->id => 50]);
    mfDeliver(Shipment::sole());
    $d = app(DisputeService::class)->open($order, $a->id, 'pat@example.com', 'not_as_described', 'Wrong size');
    $this->mock(StripeTransfers::class, function ($m) use ($order) {
        $m->shouldReceive('refund')->once()->withArgs(fn ($o, $amount, $key) => $o->id === $order->id && $amount === 50.0)->andReturn('re_1');
        $m->shouldNotReceive('transfer');
    });
    $svc = app(DisputeService::class);

    expect($svc->refund($d))->toBeTrue()->and($svc->refund($d->fresh()))->toBe('This claim was already decided.');
    expect($d->fresh()->status)->toBe('refunded')->and($d->fresh()->stripe_refund_id)->toBe('re_1')->and(Shipment::sole()->status)->toBe('refunded')->and((float) Shipment::sole()->net)->toBe(0.0);
    expect(app(PayoutService::class)->releaseDue())->toBe(0);
});

it('keeps the money held while a claim is open and releases it when staff side with the seller', function () {
    $a = mfSeller('Ace', ['level' => 'elite', 'delivered_sales' => 50000]);
    $order = mfPaid([$a->id => 50]);
    mfDeliver(Shipment::sole());
    $d = app(DisputeService::class)->open($order, $a->id, 'pat@example.com', 'not_received', 'Never came');
    $this->mock(StripeTransfers::class)->shouldReceive('transfer')->once()->andReturn('tr_1');

    expect(app(PayoutService::class)->releaseDue())->toBe(0);

    expect(app(DisputeService::class)->sideWithSeller($d, 'Tracking shows delivered'))->toBeTrue();
    expect(app(PayoutService::class)->releaseDue())->toBe(1);
});

it('will not refund a PayPal order through Stripe, but accepts a refund staff made in PayPal', function () {
    $a = mfSeller('Ace');
    $order = mfOrder([$a->id => 50], method: 'paypal');
    app(OrderSettlement::class)->markPaid($order->load('items'), 'paypal');
    $d = app(DisputeService::class)->open($order->fresh(), $a->id, 'pat@example.com', 'damaged', 'Broken');
    $this->mock(StripeTransfers::class)->shouldReceive('refund')->andThrow(new RuntimeException('Only Stripe payments can be refunded here. Refund PayPal orders in PayPal.'));

    expect(app(DisputeService::class)->refund($d))->toContain('Refund PayPal orders in PayPal');
    expect($d->fresh()->status)->toBe('open');

    expect(app(DisputeService::class)->refund($d->fresh(), manual: true))->toBeTrue()->and($d->fresh()->status)->toBe('refunded');
});
