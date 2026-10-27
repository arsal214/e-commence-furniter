<?php

use App\Models\Category;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\StoreFollow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function axStore(string $slug = 'maple'): Seller
{
    $u = User::factory()->create(['role' => 'seller']);

    return Seller::create(['user_id' => $u->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done',
        'city' => 'Savannah', 'state' => 'GA', 'approved_at' => now()]);
}

function axAddr(array $over = []): array
{
    return $over + ['label' => 'Home', 'name' => 'Pat Buyer', 'phone' => '5551234567', 'address' => '1 Main St', 'address2' => 'Apt 4', 'city' => 'Jersey City', 'state' => 'NJ', 'zip' => '07302'];
}

it('shows every account tab, keeps strangers out, and 404s unknown tabs', function () {
    $this->get('/my-account/wallet')->assertRedirect(route('login'));
    $me = User::factory()->create();
    foreach (['returns', 'wallet', 'following', 'notifications', 'settings'] as $tab) {
        $this->actingAs($me)->get("/my-account/{$tab}")->assertOk();
    }
    $this->actingAs($me)->get('/my-account/nonsense')->assertNotFound();
});

it('saves, defaults and removes addresses, and fills checkout from the default one', function () {
    $me = User::factory()->create();
    $this->actingAs($me)->post('/my-account/addresses', axAddr())->assertSessionHasNoErrors()->assertRedirect('/my-account/wallet');
    $this->actingAs($me)->post('/my-account/addresses', axAddr(['label' => 'Work', 'address' => '9 Park Ave', 'city' => 'New York', 'state' => 'NY', 'zip' => '10017']));

    $home = $me->addresses()->where('label', 'Home')->first();
    $work = $me->addresses()->where('label', 'Work')->first();
    expect($home->is_default)->toBeTrue()->and($work->is_default)->toBeFalse(); // the first one is the default

    $this->actingAs($me)->get('/my-account/wallet')->assertSee('1 Main St')->assertSee('9 Park Ave');
    $this->actingAs($me)->post("/my-account/addresses/{$work->id}/default");
    expect($work->fresh()->is_default)->toBeTrue()->and($home->fresh()->is_default)->toBeFalse();

    $cat = Category::create(['name' => 'Home', 'slug' => 'home', 'is_active' => true]);
    $p = Product::create(['category_id' => $cat->id, 'name' => 'Mug', 'slug' => 'mug', 'price' => 20, 'stock' => 5, 'is_active' => true, 'moderation_status' => 'approved']);
    $cart = ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => 'Mug', 'slug' => 'mug', 'price' => 20.0, 'sku' => null, 'image' => null, 'qty' => 1, 'color' => null, 'size' => null]];
    $this->actingAs($me)->withSession(['cart' => $cart])->get('/checkout')->assertOk()->assertSee('9 Park Ave')->assertSee('10017');

    $this->actingAs($me)->post("/my-account/addresses/{$work->id}/delete");
    expect($me->addresses()->count())->toBe(1)->and($home->fresh()->is_default)->toBeTrue(); // another one takes over as default
});

it('rejects bad addresses, other people\'s addresses and more than ten', function () {
    $me = User::factory()->create();
    $other = User::factory()->create();
    foreach ([['state' => 'ZZ'], ['zip' => 'ABCDE'], ['address' => ''], ['city' => '']] as $bad) {
        $this->actingAs($me)->post('/my-account/addresses', axAddr($bad))->assertSessionHasErrors(array_key_first($bad));
    }
    $theirs = $other->addresses()->create(axAddr() + ['is_default' => true]);
    $this->actingAs($me)->post("/my-account/addresses/{$theirs->id}/delete")->assertNotFound();
    $this->actingAs($me)->post("/my-account/addresses/{$theirs->id}/default")->assertNotFound();
    expect($theirs->fresh())->not->toBeNull();

    foreach (range(1, 10) as $i) {
        $me->addresses()->create(axAddr(['label' => "A{$i}"]));
    }
    $this->actingAs($me)->post('/my-account/addresses', axAddr())->assertSessionHas('error');
    expect($me->addresses()->count())->toBe(10);
});

it('lets a shopper follow and unfollow a live store, and shows its new products', function () {
    $me = User::factory()->create();
    $s = axStore('maple');
    $cat = Category::create(['name' => 'Home', 'slug' => 'home', 'is_active' => true]);
    Product::create(['seller_id' => $s->id, 'category_id' => $cat->id, 'name' => 'Maple Spoon', 'slug' => 'maple-spoon', 'price' => 9, 'stock' => 5, 'is_active' => true, 'moderation_status' => 'approved']);

    $this->post('/store/maple/follow')->assertRedirect(route('login'));
    $this->get('/store/maple')->assertOk()->assertSee('0 followers')->assertSee('Follow');

    $this->actingAs($me)->post('/store/maple/follow')->assertSessionHas('success');
    expect(StoreFollow::count())->toBe(1);
    $this->actingAs($me)->post('/store/maple/follow'); // a second tap undoes it, never doubles it
    expect(StoreFollow::count())->toBe(0);
    $this->actingAs($me)->post('/store/maple/follow');
    $this->actingAs($me)->get('/store/maple')->assertSee('1 follower')->assertSee('Following');
    $this->actingAs($me)->get('/my-account/following')->assertOk()->assertSee('Maple')->assertSee('Maple Spoon');

    $s->update(['subscription_status' => 'past_due']); // a hidden store disappears from the list and cannot be followed
    $this->actingAs($me)->get('/my-account/following')->assertDontSee('Maple Spoon');
    $this->actingAs($me)->post('/store/maple/follow')->assertNotFound();
});

it('lists the shopper\'s own claims only', function () {
    $me = User::factory()->create();
    $other = User::factory()->create();
    $s = axStore();
    $mk = fn (User $u, string $no) => Order::forceCreate(['user_id' => $u->id, 'name' => 'x', 'email' => $u->email, 'phone' => '1', 'address' => 'a', 'city' => 'c', 'state' => 'NJ', 'zip' => '07302', 'country' => 'US',
        'subtotal' => 20, 'total' => 20, 'payment_method' => 'stripe', 'payment_status' => 'paid', 'status' => 'shipped', 'tracking_number' => $no, 'shipping' => 'free']);
    $mine = $mk($me, 'FRN-MINE');
    $theirs = $mk($other, 'FRN-THEIRS');
    Dispute::forceCreate(['order_id' => $mine->id, 'seller_id' => $s->id, 'user_id' => $me->id, 'reason' => 'damaged', 'details' => 'broken', 'amount' => 20, 'status' => 'open']);
    Dispute::forceCreate(['order_id' => $theirs->id, 'seller_id' => $s->id, 'user_id' => $other->id, 'reason' => 'not_received', 'details' => 'x', 'amount' => 20, 'status' => 'open']);

    $this->actingAs($me)->get('/my-account/returns')->assertOk()->assertSee('FRN-MINE')->assertSee('In review')->assertDontSee('FRN-THEIRS');
});

it('saves email choices from the notifications tab and stays on it', function () {
    $me = User::factory()->create();
    $this->actingAs($me)->from('/my-account/notifications')->post('/my-account/notifications', [])->assertRedirect('/my-account/notifications');
    expect($me->fresh()->marketing_opt_out_at)->not->toBeNull();
    $this->actingAs($me)->post('/my-account/notifications', ['marketing' => '1']);
    expect($me->fresh()->marketing_opt_out_at)->toBeNull();
});

it('gives sellers a help page with the guides and support details', function () {
    $this->get('/seller/help')->assertRedirect(route('login'));
    $u = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create(['user_id' => $u->id, 'store_name' => 'Maple', 'slug' => 'maple', 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);
    $this->actingAs($u)->get('/seller/help')->assertOk()->assertSee('Seller Agreement')->assertSee('Seller Support')->assertSee('+1 (929) 469-9864');
});
