<?php

use App\Mail\GiftCardMail;
use App\Models\Category;
use App\Models\GiftCard;
use App\Models\Order;
use App\Models\Product;
use App\Models\RewardEntry;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\User;
use App\Services\GiftCardService;
use App\Services\OrderSettlement;
use App\Services\RewardsService;
use App\Services\ShipmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => Mail::fake());

function rgStore(string $slug = 'maple'): Seller
{
    $u = User::factory()->create(['role' => 'seller']);

    return Seller::create(['user_id' => $u->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);
}

function rgProduct(Seller $s, float $price = 100): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create(['seller_id' => $s->id, 'category_id' => $cat->id, 'name' => 'Item ' . uniqid(), 'slug' => 'item-' . uniqid(), 'price' => $price, 'stock' => 50, 'is_active' => true, 'moderation_status' => 'approved']);
}

function rgCart(Product $p, int $qty = 1): array
{
    return ['cart' => ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug, 'price' => (float) $p->price, 'sku' => null, 'image' => null, 'qty' => $qty, 'color' => null, 'size' => null]]];
}

function rgForm(array $over = []): array
{
    return $over + ['name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '5551234567', 'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ', 'zip' => '07302',
        'address' => '1 Main St', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1'];
}

function rgGive(User $u, int $points): void
{
    RewardEntry::create(['user_id' => $u->id, 'delta' => $points, 'reason' => 'test', 'kind' => 'earn']);
}

function rgPaidOrder(User $buyer, Seller $s, float $gross = 80, array $over = []): array
{
    $o = Order::forceCreate($over + ['user_id' => $buyer->id, 'name' => 'x', 'email' => $buyer->email, 'phone' => '1', 'address' => 'a', 'city' => 'c', 'state' => 'NJ', 'zip' => '07302', 'country' => 'US',
        'subtotal' => $gross, 'total' => $gross, 'payment_method' => 'stripe', 'payment_status' => 'paid', 'status' => 'processing', 'tracking_number' => 'FRN-' . uniqid(), 'shipping' => 'free']);
    $p = rgProduct($s, $gross);
    $o->items()->create(['product_id' => $p->id, 'seller_id' => $s->id, 'name' => $p->name, 'price' => $gross, 'qty' => 1, 'total' => $gross]);
    app(ShipmentService::class)->createForOrder($o->fresh('items'));
    $ship = Shipment::where('order_id', $o->id)->first();
    $ship->update(['status' => 'shipped', 'shipped_at' => now()]);

    return [$o, $ship];
}

// ── rewards ─────────────────────────────────────────────────────────────

it('credits points once when a parcel is delivered, and skips guest orders', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    [$o, $ship] = rgPaidOrder($buyer, $s, 80.90);

    app(ShipmentService::class)->markDelivered($ship);
    app(ShipmentService::class)->markDelivered($ship->fresh()); // a second delivery notice changes nothing
    expect(app(RewardsService::class)->balance($buyer))->toBe(80)->and(RewardEntry::where('kind', 'earn')->count())->toBe(1);

    [$guestOrder, $guestShip] = rgPaidOrder($buyer, $s, 50);
    $guestOrder->update(['user_id' => null]);
    app(ShipmentService::class)->markDelivered($guestShip);
    expect(RewardEntry::count())->toBe(1);
});

it('earns on what was actually paid for the products, after platform discounts', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    [$o, $ship] = rgPaidOrder($buyer, $s, 100, ['discount' => 10]); // a $10 welcome deal
    app(ShipmentService::class)->markDelivered($ship);
    expect(app(RewardsService::class)->balance($buyer))->toBe(90);
});

it('spends points only when the order is paid, and will not promise the same points twice', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    $p = rgProduct($s, 100);
    rgGive($buyer, 1000);

    $go = fn () => $this->actingAs($buyer)->withSession(rgCart($p) + ['use_points' => true])->post('/checkout', rgForm(['email' => $buyer->email, 'name' => 'Pat ' . uniqid()]));
    $go();
    $first = Order::where('email', $buyer->email)->latest('id')->first();
    expect($first->points_used)->toBe(1000)->and((float) $first->points_discount)->toBe(10.0)->and((float) $first->total)->toBe(90.0);
    expect(app(RewardsService::class)->balance($buyer))->toBe(1000); // not spent yet

    $go(); // a second checkout while the first one waits for payment: those points are spoken for
    $second = Order::where('email', $buyer->email)->latest('id')->first();
    expect($second->id)->not->toBe($first->id)->and($second->points_used)->toBe(0)->and((float) $second->total)->toBe(100.0);

    app(OrderSettlement::class)->markPaid($first->load('items'), 'paypal', 9000);
    expect(app(RewardsService::class)->balance($buyer))->toBe(0);
    app(OrderSettlement::class)->markPaid($first->fresh()->load('items'), 'paypal', 9000); // a repeated notice does not spend twice
    expect(RewardEntry::where('kind', 'spend')->count())->toBe(1);
});

it('takes back earned points and returns spent ones when a parcel is refunded', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    [$o, $ship] = rgPaidOrder($buyer, $s, 100, ['points_used' => 500, 'points_discount' => 5]);
    RewardEntry::create(['user_id' => $buyer->id, 'delta' => -500, 'order_id' => $o->id, 'kind' => 'spend', 'reason' => 'used']);
    rgGive($buyer, 500);
    app(ShipmentService::class)->markDelivered($ship); // earns 95
    expect(app(RewardsService::class)->balance($buyer))->toBe(95);

    app(RewardsService::class)->onRefund($ship->fresh());
    app(RewardsService::class)->onRefund($ship->fresh()); // once only
    expect(app(RewardsService::class)->balance($buyer))->toBe(500); // 95 removed, 500 returned
});

it('expires the points of shoppers who have been inactive for a year', function () {
    $quiet = User::factory()->create();
    $active = User::factory()->create();
    rgGive($quiet, 300);
    RewardEntry::where('user_id', $quiet->id)->update(['created_at' => now()->subMonths(13)]);
    rgGive($active, 300);

    expect(app(RewardsService::class)->expireInactive())->toBe(1);
    expect(app(RewardsService::class)->balance($quiet))->toBe(0)->and(app(RewardsService::class)->balance($active))->toBe(300);
});

it('shows points on the account and the cart toggle only to shoppers who have enough', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    $p = rgProduct($s, 50);
    $this->actingAs($buyer)->withSession(rgCart($p))->get('/cart')->assertDontSee('Use my reward points');
    rgGive($buyer, 1250);
    $this->actingAs($buyer)->get('/my-account/rewards')->assertOk()->assertSee('1,250')->assertSee('$12.00');
    $this->actingAs($buyer)->withSession(rgCart($p))->get('/cart')->assertSee('Use my reward points')->assertSee('1,200');
    $this->actingAs($buyer)->post('/cart/points');
    $this->actingAs($buyer)->withSession(rgCart($p))->get('/cart')->assertSee('Reward points')->assertSee('$38.00');
    auth()->logout();
    $this->post('/cart/points')->assertRedirect(route('login'));
});

// ── gift cards ──────────────────────────────────────────────────────────

function gcStripe(object $session): GiftCardService
{
    $sessions = Mockery::mock();
    $sessions->shouldReceive('create')->andReturn((object) ['id' => 'cs_test_1', 'url' => 'https://checkout.stripe.test/pay']);
    $sessions->shouldReceive('retrieve')->andReturn($session);
    $client = (object) ['checkout' => (object) ['sessions' => $sessions]];
    $svc = Mockery::mock(GiftCardService::class)->makePartial()->shouldAllowMockingProtectedMethods();
    $svc->shouldReceive('stripe')->andReturn($client);
    app()->instance(GiftCardService::class, $svc);

    return $svc;
}

it('sends the shopper to Stripe to pay for a gift card, with amount limits', function () {
    $buyer = User::factory()->create();
    gcStripe((object) []);
    $this->actingAs($buyer)->post('/my-account/gift-cards/buy', ['amount' => 50, 'recipient_email' => 'friend@example.com', 'message' => 'Enjoy'])->assertRedirect('https://checkout.stripe.test/pay');
    $card = GiftCard::first();
    expect($card->status)->toBe('pending')->and((float) $card->balance)->toBe(0.0)->and((float) $card->amount)->toBe(50.0)->and($card->stripe_session_id)->toBe('cs_test_1');

    foreach ([5, 501, 'abc', null] as $bad) {
        $this->actingAs($buyer)->post('/my-account/gift-cards/buy', ['amount' => $bad])->assertSessionHasErrors('amount');
    }
    $this->actingAs($buyer)->post('/my-account/gift-cards/buy', ['amount' => 25, 'recipient_email' => 'not-an-email'])->assertSessionHasErrors('recipient_email');
    expect(GiftCard::count())->toBe(1);
});

it('only activates a gift card when Stripe says it was paid, the right amount, by the right shopper', function () {
    $buyer = User::factory()->create();
    $other = User::factory()->create();
    $card = GiftCard::create(['code' => 'AAAA-BBBB-CCCC-DDDD', 'amount' => 50, 'balance' => 0, 'status' => 'pending', 'buyer_id' => $buyer->id, 'stripe_session_id' => 'cs_1', 'recipient_email' => 'friend@example.com']);

    $paid = fn (array $o = []) => (object) ($o + ['payment_status' => 'paid', 'client_reference_id' => (string) $card->id, 'amount_total' => 5000]);

    gcStripe($paid(['payment_status' => 'unpaid']));
    $this->actingAs($buyer)->get('/gift-cards/return?session_id=cs_1')->assertSessionHas('error');
    gcStripe($paid(['amount_total' => 100]));
    $this->actingAs($buyer)->get('/gift-cards/return?session_id=cs_1')->assertSessionHas('error');
    gcStripe($paid());
    $this->actingAs($other)->get('/gift-cards/return?session_id=cs_1')->assertSessionHas('error');
    expect($card->fresh()->status)->toBe('pending');

    $this->actingAs($buyer)->get('/gift-cards/return?session_id=cs_1')->assertSessionHas('success');
    $this->actingAs($buyer)->get('/gift-cards/return?session_id=cs_1'); // refreshing the page never emails or credits twice
    expect($card->fresh()->status)->toBe('active')->and((float) $card->fresh()->balance)->toBe(50.0);
    Mail::assertSent(GiftCardMail::class, 1);
    Mail::assertSent(GiftCardMail::class, fn ($m) => $m->hasTo('friend@example.com'));
});

it('redeems a code into the shopper\'s balance, once, whatever the formatting', function () {
    $a = User::factory()->create();
    $b = User::factory()->create();
    GiftCard::create(['code' => 'ABCD-EFGH-JKLM-NPQR', 'amount' => 25, 'balance' => 25, 'status' => 'active']);
    GiftCard::create(['code' => 'ZZZZ-ZZZZ-ZZZZ-ZZZZ', 'amount' => 25, 'balance' => 25, 'status' => 'pending']);

    $this->actingAs($a)->post('/my-account/gift-cards/redeem', ['code' => 'wrong'])->assertSessionHas('error');
    $this->actingAs($a)->post('/my-account/gift-cards/redeem', ['code' => 'ZZZZ-ZZZZ-ZZZZ-ZZZZ'])->assertSessionHas('error'); // unpaid card
    $this->actingAs($a)->post('/my-account/gift-cards/redeem', ['code' => 'abcd efgh jklm npqr'])->assertSessionHas('success');
    expect(app(GiftCardService::class)->balance($a))->toBe(25.0);
    $this->actingAs($b)->post('/my-account/gift-cards/redeem', ['code' => 'ABCD-EFGH-JKLM-NPQR'])->assertSessionHas('error'); // already someone else's
    expect(app(GiftCardService::class)->balance($b))->toBe(0.0);
    $this->actingAs($a)->get('/my-account/giftcards')->assertOk()->assertSee('$25.00')->assertDontSee('ABCD-EFGH');
});

it('pays part of an order from the balance, and takes it off only once the order is paid', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    $p = rgProduct($s, 100);
    GiftCard::create(['code' => 'ABCD-EFGH-JKLM-NPQR', 'amount' => 30, 'balance' => 30, 'status' => 'active', 'owner_id' => $buyer->id]);

    $this->actingAs($buyer)->withSession(rgCart($p))->post('/checkout', rgForm(['email' => $buyer->email]));
    $order = Order::where('email', $buyer->email)->latest('id')->first();
    expect((float) $order->gift_card_discount)->toBe(30.0)->and((float) $order->total)->toBe(70.0);
    expect(app(GiftCardService::class)->balance($buyer))->toBe(30.0);

    app(OrderSettlement::class)->markPaid($order->load('items'), 'paypal', 7000);
    expect(app(GiftCardService::class)->balance($buyer))->toBe(0.0);
});

it('finishes an order without a payment step when gift cards and points cover everything', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    $p = rgProduct($s, 40);
    GiftCard::create(['code' => 'ABCD-EFGH-JKLM-NPQR', 'amount' => 100, 'balance' => 100, 'status' => 'active', 'owner_id' => $buyer->id]);

    $this->actingAs($buyer)->withSession(rgCart($p))->post('/checkout', rgForm(['email' => $buyer->email]))->assertRedirect(route('thank-you'));
    $order = Order::where('email', $buyer->email)->latest('id')->first();
    expect($order->payment_status)->toBe('paid')->and((float) $order->total)->toBe(0.0)->and((float) $order->gift_card_discount)->toBe(40.0);
    expect(app(GiftCardService::class)->balance($buyer))->toBe(60.0)->and(Shipment::where('order_id', $order->id)->exists())->toBeTrue();
});

it('does not let two checkouts spend the same gift card balance', function () {
    $s = rgStore();
    $buyer = User::factory()->create();
    $p = rgProduct($s, 100);
    GiftCard::create(['code' => 'ABCD-EFGH-JKLM-NPQR', 'amount' => 100, 'balance' => 100, 'status' => 'active', 'owner_id' => $buyer->id]);
    $go = fn (string $n) => $this->actingAs($buyer)->withSession(rgCart($p))->post('/checkout', rgForm(['email' => $buyer->email, 'name' => $n]));
    $go('First Buyer');
    $go('Second Buyer');
    $orders = Order::where('email', $buyer->email)->orderBy('id')->get();
    expect($orders->count())->toBeGreaterThanOrEqual(1);
    expect((float) $orders->sum('gift_card_discount'))->toBeLessThanOrEqual(100.0);
});
