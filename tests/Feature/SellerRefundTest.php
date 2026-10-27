<?php

use App\Mail\SellerNoticeMail;
use App\Models\Category;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentService;
use App\Services\StripeTransfers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => Mail::fake());

function srSeller(string $slug = 'maple'): User
{
    $u = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create(['user_id' => $u->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);

    return $u;
}

function srOrder(Seller $seller, ?User $buyer = null, array $over = []): Order
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);
    $p = Product::firstOrCreate(['slug' => 'cushion'], ['seller_id' => $seller->id, 'category_id' => $cat->id, 'name' => 'Cushion', 'price' => 40, 'stock' => 9, 'is_active' => true, 'moderation_status' => 'approved']);
    $o = Order::forceCreate($over + ['user_id' => $buyer?->id, 'name' => 'Pat Buyer', 'email' => $buyer?->email ?? 'pat@example.com', 'phone' => '1', 'address' => '1 Main St', 'city' => 'Jersey City',
        'state' => 'NJ', 'zip' => '07302', 'country' => 'US', 'payment_method' => 'stripe', 'stripe_payment_intent' => 'pi_1', 'payment_status' => 'paid', 'shipping' => 'free',
        'subtotal' => 40, 'total' => 40, 'status' => 'processing', 'tracking_number' => 'T-' . strtoupper(uniqid())]);
    OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'seller_id' => $seller->id, 'name' => 'Cushion', 'price' => 40, 'qty' => 1, 'total' => 40]);
    app(ShipmentService::class)->createForOrder($o);

    return $o;
}

it('lets a seller refund an order themselves, once, and the buyer is told', function () {
    $u = srSeller();
    $o = srOrder($u->seller);
    $s = Shipment::where('order_id', $o->id)->first();
    $this->mock(StripeTransfers::class)->shouldReceive('refund')->once()->andReturn('re_1');

    $this->actingAs($u)->get('/seller/orders')->assertOk()->assertSee('Refund buyer')->assertSee('$40.00')->assertDontSee('Stripe');
    $this->actingAs($u)->post(route('seller.orders.refund', $s))->assertSessionHas('status');

    expect($s->fresh()->status)->toBe('refunded')->and((float) $s->fresh()->net)->toBe(0.0)
        ->and(Dispute::where('order_id', $o->id)->first()->status)->toBe('refunded');
    Mail::assertSent(SellerNoticeMail::class, fn ($m) => $m->hasTo('pat@example.com') && str_contains($m->subjectLine, 'refunded'));

    $this->actingAs($u)->post(route('seller.orders.refund', $s))->assertSessionHas('error'); // never twice
});

it('only lets a seller refund their own orders, and not orders that were not paid by card', function () {
    $mine = srSeller('mine');
    $other = srSeller('other');
    $s = Shipment::where('order_id', srOrder($other->seller)->id)->first();
    $this->actingAs($mine)->post(route('seller.orders.refund', $s))->assertNotFound();
    expect($s->fresh()->status)->toBe('pending');

    $pp = Shipment::where('order_id', srOrder($mine->seller, null, ['payment_method' => 'paypal', 'stripe_payment_intent' => null])->id)->first();
    $this->actingAs($mine)->post(route('seller.orders.refund', $pp))->assertSessionHas('error');
    expect($pp->fresh()->status)->toBe('pending')->and(Dispute::count())->toBe(0); // no open claim left behind
});

it('lets a signed-in buyer ask for a refund, tells the seller, and the seller can approve it', function () {
    $u = srSeller();
    $buyer = User::factory()->create();
    $o = srOrder($u->seller, $buyer);
    $this->mock(StripeTransfers::class)->shouldReceive('refund')->once()->andReturn('re_2');

    $this->actingAs($buyer)->get('/my-account/returns')->assertOk()->assertSee('Request a refund for this order')->assertSee($u->seller->uuid)->assertDontSee('name="seller" value="' . $u->seller->id . '"', false);
    $this->actingAs($buyer)->post(route('account.refund'), ['order' => $o->tracking_number, 'seller' => $u->seller->uuid, 'reason' => 'changed_mind', 'details' => 'It does not fit my room.'])
        ->assertRedirect('/my-account/returns')->assertSessionHas('success');
    $claim = Dispute::first();
    expect($claim->status)->toBe('open')->and($claim->reason)->toBe('changed_mind')->and($claim->user_id)->toBe($buyer->id);
    Mail::assertSent(SellerNoticeMail::class, fn ($m) => $m->hasTo($u->email) && str_contains($m->subjectLine, 'asked for a refund'));

    $s = Shipment::where('order_id', $o->id)->first();
    $this->actingAs($u)->get('/seller/orders')->assertSee('Refund request')->assertSee('It does not fit my room.')->assertSee('Decline request');
    $this->actingAs($u)->post(route('seller.orders.refund', $s))->assertSessionHas('status');
    expect($claim->fresh()->status)->toBe('refunded')->and(Dispute::count())->toBe(1); // the request itself was resolved
});

it('lets a seller decline with a reason, which staff and the buyer then see', function () {
    $u = srSeller();
    $buyer = User::factory()->create();
    $o = srOrder($u->seller, $buyer);
    $this->actingAs($buyer)->post(route('account.refund'), ['order' => $o->tracking_number, 'seller' => $u->seller->uuid, 'reason' => 'damaged', 'details' => 'Arrived with a torn seam.']);
    $s = Shipment::where('order_id', $o->id)->first();

    $this->actingAs($u)->post(route('seller.orders.refund.decline', $s), ['note' => 'x'])->assertSessionHasErrors('note');
    $this->actingAs($u)->post(route('seller.orders.refund.decline', $s), ['note' => 'The photos show normal wear.'])->assertSessionHas('status');
    $claim = Dispute::first();
    expect($claim->status)->toBe('open')->and($claim->seller_note)->toBe('The photos show normal wear.')->and($claim->seller_declined_at)->not->toBeNull();

    $this->actingAs($buyer)->get('/my-account/returns')->assertSee('The seller replied:')->assertSee('The photos show normal wear.');
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->get(route('admin.disputes.index'))->assertOk()->assertSee('Seller declined: The photos show normal wear.');
    $this->actingAs($u)->post(route('seller.orders.refund.decline', $s), ['note' => 'Second try please'])->assertSessionHas('error'); // already answered
});

it('keeps buyers from refund requests on other people\'s orders or stores that are not on them', function () {
    $u = srSeller();
    $buyer = User::factory()->create();
    $stranger = User::factory()->create();
    $o = srOrder($u->seller, $buyer);
    $other = srSeller('other');
    $form = ['order' => $o->tracking_number, 'seller' => $u->seller->uuid, 'reason' => 'damaged', 'details' => 'Arrived with a torn seam.'];

    $this->actingAs($stranger)->post(route('account.refund'), $form)->assertNotFound();
    $this->actingAs($buyer)->post(route('account.refund'), ['seller' => $other->seller->uuid] + $form)->assertNotFound();
    $this->actingAs($buyer)->post(route('account.refund'), ['reason' => 'nope'] + $form)->assertSessionHasErrors('reason');
    auth()->logout(); // actingAs() stays signed in for the rest of the test
    $this->post(route('account.refund'), $form)->assertRedirect(route('login'));
    expect(Dispute::count())->toBe(0);
});

it('still takes a claim from the tracking page, now with the seller sent as its uuid', function () {
    $u = srSeller();
    $o = srOrder($u->seller);
    $this->post(route('claim.store', $o->tracking_number), ['seller_id' => $u->seller->uuid, 'email' => 'pat@example.com', 'reason' => 'not_received', 'details' => 'Never arrived, two weeks now.'])
        ->assertSessionHas('success');
    expect(Dispute::first()->seller_id)->toBe($u->seller->id);

    $html = $this->get('/track-order?tracking=' . $o->tracking_number)->getContent();
    expect($html)->not->toContain('name="seller_id" value="' . $u->seller->id . '"');
});
