<?php

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(fn () => Storage::fake('public'));

function ssSeller(string $slug = 'maple'): User
{
    $user = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create(['user_id' => $user->id, 'store_name' => ucfirst($slug), 'slug' => $slug, 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);

    return $user;
}

it('keeps the settings and storefront pages behind the seller login', function () {
    $this->get('/seller/settings')->assertRedirect(route('login'));
    $this->get('/seller/storefront')->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create())->post('/seller/settings', [])->assertRedirect(route('login'));
});

it('saves processing time and return policy, and shows them to shoppers', function () {
    $u = ssSeller();
    $this->actingAs($u)->get('/seller/settings')->assertOk()->assertSee('Return window')->assertSee('Processing time');
    $this->actingAs($u)->post('/seller/settings', ['handling_days' => 3, 'return_window_days' => 60, 'return_shipping_payer' => 'seller'])->assertSessionHasNoErrors()->assertRedirect(route('seller.settings'));

    $s = $u->seller->fresh();
    expect([$s->handling_days, $s->return_window_days, $s->return_shipping_payer])->toBe([3, 60, 'seller']);
    $this->get(route('store.show', 'maple'))->assertSee('3 days')->assertSee('60 days');
});

it('refuses settings values the design does not offer', function () {
    $u = ssSeller();
    $ok = ['handling_days' => 2, 'return_window_days' => 30, 'return_shipping_payer' => 'buyer'];
    foreach (['handling_days' => 99, 'return_window_days' => 45, 'return_shipping_payer' => 'nobody'] as $k => $v) {
        $this->actingAs($u)->post('/seller/settings', [$k => $v] + $ok)->assertSessionHasErrors($k);
    }
    $this->actingAs($u)->post('/seller/settings', [])->assertSessionHasErrors(['handling_days', 'return_window_days', 'return_shipping_payer']);
});

it('saves the store story and a logo that matches the logo rules', function () {
    $u = ssSeller();
    $this->actingAs($u)->post('/seller/storefront', ['about' => 'Hand-made in Savannah.', 'logo' => UploadedFile::fake()->image('logo.png', 500, 500)])->assertSessionHasNoErrors();
    $s = $u->seller->fresh();
    expect($s->about)->toBe('Hand-made in Savannah.')->and($s->logo)->not->toBeNull();
    Storage::disk('public')->assertExists($s->logo);

    $this->actingAs($u)->post('/seller/storefront', ['about' => 'x', 'logo' => UploadedFile::fake()->image('wide.png', 900, 400)])->assertSessionHasErrors('logo');
    $this->actingAs($u)->post('/seller/storefront', ['about' => str_repeat('a', 1001)])->assertSessionHasErrors('about');

    $old = $s->logo;
    $this->actingAs($u)->post('/seller/storefront', ['about' => '', 'remove_logo' => '1'])->assertSessionHasNoErrors();
    expect($u->seller->fresh()->logo)->toBeNull()->and($u->seller->fresh()->about)->toBeNull();
    Storage::disk('public')->assertMissing($old);
});

it('lets one seller change only their own store', function () {
    $a = ssSeller('a');
    $b = ssSeller('b');
    $this->actingAs($a)->post('/seller/settings', ['handling_days' => 5, 'return_window_days' => 14, 'return_shipping_payer' => 'seller', 'seller_id' => $b->seller->id]);
    expect($b->seller->fresh()->handling_days)->toBe(2)->and($a->seller->fresh()->handling_days)->toBe(5);
});

it('lists the new pages in the Seller Center menu', function () {
    $this->actingAs(ssSeller())->get('/seller/products')->assertOk()->assertSee('/seller/storefront', false)->assertSee('/seller/settings', false)->assertSee('+ Add product');
});
