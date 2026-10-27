<?php

use App\Mail\NewMessageMail;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Seller;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    Cache::flush();
    RateLimiter::clear('message-send:1');
});

function msgStore(string $slug = 'maple', array $over = []): User
{
    $u = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create($over + ['user_id' => $u->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);

    return $u;
}

it('lets a shopper write to a live store and the store answer from Seller Center', function () {
    $seller = msgStore('maple');
    $buyer = User::factory()->create(['name' => 'Pat Buyer']);

    $this->post('/my-account/messages', ['to' => 'maple', 'body' => 'Hi'])->assertRedirect(route('login'));
    $this->actingAs($buyer)->post('/my-account/messages', ['to' => 'maple', 'body' => 'Is the mug dishwasher safe?'])->assertRedirect();
    $c = Conversation::first();
    expect($c->buyer_id)->toBe($buyer->id)->and($c->seller_id)->toBe($seller->seller->id)->and(Message::count())->toBe(1);
    Mail::assertSent(NewMessageMail::class, fn ($m) => $m->hasTo($seller->email));

    $this->actingAs($seller)->get('/seller/messages')->assertOk()->assertSee('Pat Buyer')->assertSee('dishwasher safe');
    expect(Message::first()->read_at)->not->toBeNull(); // opening it marks it read
    $this->actingAs($seller)->post("/seller/messages/{$c->id}", ['body' => 'Yes, top rack.'])->assertSessionHasNoErrors();
    expect(Message::count())->toBe(2);

    $this->actingAs($buyer)->get('/my-account/messages')->assertOk()->assertSee('Maple')->assertSee('top rack');
    $this->actingAs($buyer)->post('/my-account/messages', ['to' => 'maple', 'body' => 'Thanks!']); // same conversation, not a second one
    expect(Conversation::count())->toBe(1)->and(Message::count())->toBe(3);
});

it('keeps conversations private to the two people in them', function () {
    $a = msgStore('a');
    $b = msgStore('b');
    $pat = User::factory()->create(['name' => 'Pat One']);
    $sam = User::factory()->create(['name' => 'Sam Two']);
    $this->actingAs($pat)->post('/my-account/messages', ['to' => 'a', 'body' => 'private words for A']);
    $c = Conversation::first();

    $this->actingAs($sam)->post("/my-account/messages/{$c->id}", ['body' => 'intruder'])->assertNotFound();
    $this->actingAs($b)->post("/seller/messages/{$c->id}", ['body' => 'intruder'])->assertNotFound();
    $this->actingAs($b)->get("/seller/messages?c={$c->id}")->assertOk()->assertDontSee('private words');
    $this->actingAs($sam)->get("/my-account/messages?c={$c->id}")->assertOk()->assertDontSee('private words');
    expect(Message::count())->toBe(1);
});

it('counts unread messages per side and clears them when read', function () {
    $seller = msgStore('maple');
    $buyer = User::factory()->create();
    $svc = app(MessageService::class);
    $c = $svc->open($buyer, $seller->seller);
    $svc->send($c, 'buyer', $buyer, 'one');
    $svc->send($c, 'buyer', $buyer, 'two');
    expect($svc->unreadForSeller($seller->seller))->toBe(2)->and($svc->unreadForBuyer($buyer))->toBe(0);

    $this->actingAs($seller)->get('/seller/products')->assertSee('Messages'); // the menu shows it
    $this->actingAs($seller)->get('/seller/messages');
    expect($svc->unreadForSeller($seller->seller))->toBe(0);
});

it('emails about new messages at most once every ten minutes per conversation', function () {
    $seller = msgStore('maple');
    $buyer = User::factory()->create();
    $svc = app(MessageService::class);
    $c = $svc->open($buyer, $seller->seller);
    foreach (['a', 'b', 'c'] as $t) {
        $svc->send($c, 'buyer', $buyer, $t);
    }
    Mail::assertSent(NewMessageMail::class, 1);
});

it('refuses empty, oversized and flooded messages and strips markup', function () {
    $seller = msgStore('maple');
    $buyer = User::factory()->create();
    $this->actingAs($buyer)->post('/my-account/messages', ['to' => 'maple', 'body' => ''])->assertSessionHasErrors('body');
    $this->actingAs($buyer)->post('/my-account/messages', ['to' => 'maple', 'body' => str_repeat('a', 2001)])->assertSessionHasErrors('body');
    expect(Message::count())->toBe(0);

    $this->actingAs($buyer)->post('/my-account/messages', ['to' => 'maple', 'body' => '<script>alert(1)</script>Hello <b>there</b>']);
    expect(Message::first()->body)->toBe('alert(1)Hello there');
    $this->actingAs($buyer)->get('/my-account/messages')->assertDontSee('<script>alert(1)', false);

    $svc = app(MessageService::class);
    $c = Conversation::first();
    foreach (range(1, MessageService::PER_HOUR + 5) as $i) {
        $svc->send($c, 'buyer', $buyer, "m{$i}");
    }
    expect(Message::count())->toBeLessThanOrEqual(MessageService::PER_HOUR);
});

it('does not start conversations with stores that are not live', function () {
    msgStore('hidden', ['subscription_status' => 'past_due']);
    $buyer = User::factory()->create();
    $this->actingAs($buyer)->post('/my-account/messages', ['to' => 'hidden', 'body' => 'hello'])->assertNotFound();
    $this->actingAs($buyer)->post('/my-account/messages', ['to' => 'nobody', 'body' => 'hello'])->assertNotFound();
    expect(Conversation::count())->toBe(0);
});

it('offers the Message buttons on store and product pages', function () {
    msgStore('maple');
    $this->get('/store/maple')->assertOk()->assertSee('/my-account/messages?to=maple', false);
    $buyer = User::factory()->create();
    $this->actingAs($buyer)->get('/my-account/messages?to=maple')->assertOk()->assertSee('New message to Maple');
});
