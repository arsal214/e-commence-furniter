<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\WelcomeOffer;
use App\Services\MetaEventsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function p4Capi(bool $on = true): void
{
    config([
        'services.meta.capi_enabled' => $on, 'services.meta.capi_token' => 'EAAB-test', 'services.meta.pixel_id' => '111222333',
        'services.meta.test_event_code' => 'TEST123',
    ]);
}

function p4Product(float $price = 40.00): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);

    return Product::create(['category_id' => $cat->id, 'name' => 'Lamp', 'slug' => 'lamp-' . uniqid(), 'price' => $price, 'stock' => 10, 'is_active' => true]);
}

function p4Cart(Product $p): array
{
    return ['cart' => ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => $p->name, 'slug' => $p->slug,
        'price' => (float) $p->price, 'sku' => null, 'image' => null, 'qty' => 1, 'color' => null, 'size' => null]]];
}

function p4Form(array $over = []): array
{
    return $over + [
        'name' => 'Pat Q Buyer', 'email' => ' Pat@Example.com ', 'phone' => '(555) 123-4567',
        'city' => 'Jersey City', 'country' => 'US', 'state' => 'NJ', 'zip' => '07302-1234',
        'address' => '1 Main St', 'payment_method' => 'paypal', 'shipping' => 'free', 'agree' => '1',
    ];
}

function p4Fake(int $graphStatus = 200): void
{
    // Graph API gets the given answer; everything else (TikTok etc.) is answered locally so no test touches the network.
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1], $graphStatus), '*' => Http::response([], 200)]);
}

function p4Sent(): array
{
    return Http::recorded(fn ($request) => str_contains($request->url(), 'graph.facebook.com'))
        ->map(fn ($pair) => $pair[0])->values()->all();
}

function p4Event(HttpRequest $r): array
{
    return $r->data()['data'][0];
}

it('hashes customer data the way Meta specifies', function () {
    $order = new Order(['email' => ' Pat@Example.com ', 'phone' => '(555) 123-4567', 'name' => 'Pat Q Buyer', 'city' => 'Jersey City',
        'state' => 'NJ', 'zip' => '07302-1234', 'country' => 'US', 'client_ip' => '203.0.113.9', 'client_user_agent' => 'UA/1', 'meta_fbp' => 'fb.1.1.2']);

    $u = app(MetaEventsService::class)->buildUser(null, null, null, $order);

    expect($u['em'])->toBe([hash('sha256', 'pat@example.com')])
        ->and($u['ph'])->toBe([hash('sha256', '15551234567')])
        ->and($u['fn'])->toBe([hash('sha256', 'pat')])->and($u['ln'])->toBe([hash('sha256', 'buyer')])
        ->and($u['ct'])->toBe(hash('sha256', 'jerseycity'))->and($u['st'])->toBe(hash('sha256', 'nj'))
        ->and($u['zp'])->toBe(hash('sha256', '07302'))->and($u['country'])->toBe(hash('sha256', 'us'))
        ->and($u['client_ip_address'])->toBe('203.0.113.9')->and($u['fbp'])->toBe('fb.1.1.2');
});

it('sends nothing when the Conversions API is off or has no token', function () {
    p4Fake();
    Mail::fake();
    $item = p4Product();

    p4Capi(false);
    $this->withSession(p4Cart($item))->post(route('checkout.store'), p4Form());

    config(['services.meta.capi_enabled' => true, 'services.meta.capi_token' => null]);
    $this->withSession(p4Cart($item))->post(route('checkout.store'), p4Form());

    expect(p4Sent())->toBe([]);
});

it('sends one Purchase when a PayPal order is paid, with the order id as event id and the charged total', function () {
    p4Fake();
    Mail::fake();
    p4Capi();
    $item = p4Product(40.00);

    $this->withSession(p4Cart($item))->post(route('checkout.store'), p4Form());
    expect(p4Sent())->toBe([]);   // placing an unpaid order is not a purchase
    payLatestOrder($this);

    $order = Order::first();
    $sent  = p4Sent();
    $e     = p4Event($sent[0]);
    expect($sent)->toHaveCount(1)
        ->and($sent[0]->url())->toContain('/111222333/events')
        ->and($sent[0]->data()['test_event_code'])->toBe('TEST123')
        ->and($e['event_name'])->toBe('Purchase')->and($e['event_id'])->toBe('Purchase.order-' . $order->id)
        ->and($e['custom_data']['value'])->toBe(40.0)->and($e['custom_data']['currency'])->toBe('USD')
        ->and($e['custom_data']['content_ids'])->toBe([$item->uuid]) // tracking uses the product uuid, never the row id
        ->and($e['custom_data']['num_items'])->toBe(1)
        ->and($e['user_data']['em'])->toBe([hash('sha256', 'pat@example.com')])
        ->and($order->fresh()->meta_event_sent_at)->not->toBeNull();
});

it('reports a Stripe purchase once from the webhook and never twice on replay', function () {
    p4Fake();
    Mail::fake();
    p4Capi();
    config(['services.stripe.webhook_secret' => 'whsec_test', 'services.stripe.secret' => null]);
    $order = Order::create([
        'name' => 'Pat', 'email' => 'pat@example.com', 'phone' => '1', 'country' => 'US', 'address' => '1 St',
        'payment_method' => 'stripe', 'payment_status' => 'pending', 'shipping' => 'free', 'subtotal' => 40, 'shipping_cost' => 0,
        'total' => 40, 'status' => 'pending', 'tracking_number' => 'T-1', 'stripe_payment_intent' => 'pi_1',
        'meta_fbp' => 'fb.1.9.9', 'client_ip' => '203.0.113.9', 'client_user_agent' => 'UA/1',
    ]);
    OrderItem::create(['order_id' => $order->id, 'product_id' => p4Product()->id, 'name' => 'Lamp', 'price' => 40, 'qty' => 1, 'total' => 40]);

    $body = json_encode(['id' => 'evt_1', 'object' => 'event', 'type' => 'payment_intent.succeeded', 'livemode' => false,
        'data' => ['object' => ['id' => 'pi_1', 'object' => 'payment_intent', 'amount' => 4000]]]);
    foreach ([1, 2] as $_) {
        $t = time();
        $this->call('POST', '/stripe/webhook', [], [], [], ['CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => "t={$t},v1=" . hash_hmac('sha256', "{$t}.{$body}", 'whsec_test')], $body)->assertOk();
    }

    $sent = p4Sent();
    expect($sent)->toHaveCount(1)->and(p4Event($sent[0])['user_data']['fbp'])->toBe('fb.1.9.9')
        ->and(p4Event($sent[0])['user_data']['client_ip_address'])->toBe('203.0.113.9');
});

it('releases the once-only claim when Meta is unreachable so a later path can retry, and never breaks checkout', function () {
    Http::fake(['graph.facebook.com/*' => Http::sequence()->push('boom', 500)->push(['events_received' => 1], 200), '*' => Http::response([], 200)]);
    Mail::fake();
    p4Capi();

    $this->withSession(p4Cart(p4Product()))->post(route('checkout.store'), p4Form())->assertOk();
    payLatestOrder($this);   // Meta answers 500: the order still settles, the once-only claim is released

    expect(Order::first()->payment_status)->toBe('paid')->and(Order::first()->meta_event_sent_at)->toBeNull();

    p4Fake();
    expect(app(MetaEventsService::class)->purchase(Order::first()))->toBeTrue()->and(Order::first()->meta_event_sent_at)->not->toBeNull();
});

it('gives the product page browser ViewContent the same id the server sent to Meta', function () {
    p4Fake();
    p4Capi();
    $item = p4Product();

    $html = $this->get(route('product-details', $item->slug))->assertOk()->getContent();

    $sent = p4Sent();
    $id = p4Event($sent[0])['event_id'];
    expect($sent)->toHaveCount(1)->and(p4Event($sent[0])['event_name'])->toBe('ViewContent')->and($id)->toStartWith('ViewContent.')
        ->and($html)->toContain($id)->and($html)->not->toContain('ViewContent.product-')
        ->and($html)->toContain("gtag('event', 'view_item'");
});

it('shares the AddToCart id between the server event and the JSON the browser reads', function () {
    p4Fake();
    p4Capi();
    $item = p4Product(40.00);

    $res = $this->postJson(route('cart.add'), ['product_id' => $item->id, 'qty' => 2])->assertOk();

    $e = p4Event(p4Sent()[0]);
    expect($e['event_name'])->toBe('AddToCart')->and($e['custom_data']['value'])->toBe(80.0)
        ->and($res->json('product.meta_event_id'))->toBe($e['event_id']);
});

it('reuses a browser-minted AddToCart id when it has a valid shape, and ignores a junk one', function () {
    p4Fake();
    p4Capi();
    $item = p4Product();

    $this->post(route('cart.add'), ['product_id' => $item->id, 'meta_event_id' => 'AddToCart.abc123XYZ789']);
    $this->post(route('cart.add'), ['product_id' => $item->id, 'meta_event_id' => '<script>']);

    $ids = collect(p4Sent())->map(fn ($r) => p4Event($r)['event_id']);
    expect($ids[0])->toBe('AddToCart.abc123XYZ789')->and($ids[1])->toStartWith('AddToCart.')->and($ids[1])->not->toContain('<');
});

it('sends InitiateCheckout with the same id the checkout page uses in the browser, plus GA4 begin_checkout', function () {
    p4Fake();
    p4Capi();
    $item = p4Product(40.00);

    $html = $this->withSession(p4Cart($item))->get('/checkout')->assertOk()->getContent();

    $e = p4Event(p4Sent()[0]);
    expect($e['event_name'])->toBe('InitiateCheckout')->and($e['custom_data']['value'])->toBe(40.0)
        ->and($html)->toContain($e['event_id'])->and($html)->toContain("gtag('event', 'begin_checkout'");
});

it('sends Lead once when a welcome offer is claimed', function () {
    p4Fake();
    Mail::fake();
    p4Capi();
    $offer = WelcomeOffer::create(['name' => 'W', 'is_active' => true, 'expiry_hours' => 72]);
    $offer->tiers()->create(['type' => 'fixed_amount', 'amount' => 5, 'min_subtotal' => 35, 'sort_order' => 1]);

    $res = $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com'])->assertOk();
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com'])->assertOk();

    $sent = p4Sent();
    expect($sent)->toHaveCount(1)->and(p4Event($sent[0])['event_id'])->toBe($res->json('lead_event_id'))
        ->and(p4Event($sent[0])['user_data']['em'])->toBe([hash('sha256', 'pat@example.com')]);
});

it('keeps the campaign through to the order and builds fbc from the click id', function () {
    p4Fake();
    Mail::fake();
    $item = p4Product();

    $this->get('/?utm_source=facebook&utm_medium=paid_social&utm_campaign=halloween&utm_content=ad-7&fbclid=IwAR123');
    $this->get('/shop'); // a later page with no parameters must not wipe it
    $this->post(route('checkout.store'), p4Form());   // cart is empty here

    $this->withSession(p4Cart($item) + ['attribution' => session('attribution')])->post(route('checkout.store'), p4Form());

    $o = Order::first();
    expect($o->utm_source)->toBe('facebook')->and($o->utm_medium)->toBe('paid_social')->and($o->utm_campaign)->toBe('halloween')
        ->and($o->utm_content)->toBe('ad-7')->and($o->meta_fbc)->toStartWith('fb.1.')->and($o->meta_fbc)->toEndWith('.IwAR123');
});

it('reports GA4 purchase on the thank-you page with the order id, items and coupon', function () {
    p4Fake();
    Mail::fake();
    $offer = WelcomeOffer::create(['name' => 'W', 'is_active' => true, 'expiry_hours' => 72]);
    $offer->tiers()->create(['type' => 'fixed_amount', 'amount' => 5, 'min_subtotal' => 35, 'sort_order' => 1]);
    $item = p4Product(40.00);
    $this->postJson(route('welcome.claim'), ['email' => 'pat@example.com']);
    $code = \App\Models\WelcomeClaim::first()->code;

    $this->withSession(p4Cart($item))->post(route('checkout.store'), p4Form(['email' => 'pat@example.com']));
    $order = Order::first();

    $html = $this->withSession(['recent_order' => $order->tracking_number, 'order_total' => $order->total])->get('/thank-you')->assertOk()->getContent();

    expect($html)->toContain("gtag('event', 'purchase'")->and($html)->toContain($order->tracking_number)
        ->and($html)->toContain($code)->and($html)->toContain('Purchase.order-' . $order->id)
        ->and($html)->toContain('"item_id":"' . $item->uuid . '"');
});
