<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeSeller(array $over = []): Seller
{
    $user = User::factory()->create(['role' => 'seller']);
    return Seller::create(array_merge([
        'user_id' => $user->id, 'store_name' => 'Maple Home', 'slug' => 'maple-home',
        'business_name' => 'Maple Home LLC', 'business_type' => 'LLC', 'ein' => '12-3456789',
        'address' => '1 Main St', 'city' => 'Jersey City', 'zip' => '07305',
        'owner_name' => 'Pat Doe', 'owner_phone' => '+19294699864', 'onboarding_step' => 'done',
    ], $over));
}

function makeProduct(?Seller $seller, array $over = []): Product
{
    $cat = Category::first() ?? Category::create(['name' => 'Home', 'slug' => 'home', 'is_active' => true]);
    return Product::create(array_merge([
        'category_id' => $cat->id, 'name' => 'Mug ' . uniqid(), 'price' => 10, 'is_active' => true,
        'seller_id' => $seller?->id,
    ], $over));
}

it('maps delivered sales to levels and payout holds', function () {
    expect(Seller::levelFor(0))->toBe('new')
        ->and(Seller::levelFor(1000))->toBe('rising')
        ->and(Seller::levelFor(10000))->toBe('top')
        ->and(Seller::levelFor(50000))->toBe('elite');

    expect(makeSeller(['level' => 'elite'])->holdDays())->toBe(0);
});

it('shows house products and live-seller products only', function () {
    $house = makeProduct(null);
    $live = makeProduct(makeSeller(['status' => 'approved', 'subscription_status' => 'active', 'slug' => 'a']));
    $unpaid = makeProduct(makeSeller(['status' => 'approved', 'subscription_status' => 'past_due', 'slug' => 'b']));
    $pending = makeProduct(makeSeller(['status' => 'in_review', 'subscription_status' => 'active', 'slug' => 'c']));
    $moderated = makeProduct(null, ['moderation_status' => 'pending']);

    $visible = Product::visible()->pluck('id')->all();

    expect($visible)->toContain($house->id, $live->id)
        ->not->toContain($unpaid->id, $pending->id, $moderated->id);
});

it('lets only sellers through the seller middleware', function () {
    Route::get('/_seller-probe', fn () => 'ok')->middleware(['web', 'auth', 'seller']);

    $this->actingAs(User::factory()->create())->get('/_seller-probe')->assertRedirect('/login');
    $this->actingAs(makeSeller()->user)->get('/_seller-probe')->assertOk();
});
