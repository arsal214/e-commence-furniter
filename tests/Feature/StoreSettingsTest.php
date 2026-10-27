<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\User;
use App\Support\DeliveryEstimate;
use App\Support\StoreSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

function ssAdmin(): User
{
    return User::create(['name' => 'A', 'email' => 'admin@example.com', 'password' => 'x', 'role' => 'admin']);
}

function ssCart(float $price): array
{
    $cat = Category::firstOrCreate(['slug' => 'home'], ['name' => 'Home', 'is_active' => true]);
    $p = Product::create(['category_id' => $cat->id, 'name' => 'Lamp', 'slug' => 'lamp', 'price' => $price, 'stock' => 5, 'is_active' => true]);

    return ['cart' => ["{$p->id}||" => ['key' => "{$p->id}||", 'id' => $p->id, 'name' => 'Lamp', 'slug' => 'lamp',
        'price' => $price, 'sku' => null, 'image' => null, 'qty' => 1, 'color' => null, 'size' => null]]];
}

function ssForm(array $over = []): array
{
    return ['settings' => $over];
}

it('keeps the .env defaults when nothing is saved', function () {
    $this->get('/')->assertOk();

    expect(config('checkout.shipping_fee_enabled'))->toBeFalse()
        ->and((float) config('checkout.free_shipping_threshold'))->toBe(35.0)
        ->and(StoreSettings::defaults()['free_shipping_threshold'])->toBe(config('checkout.free_shipping_threshold'));
});

it('applies a saved setting to the live site on the next request', function () {
    StoreSetting::create(['key' => 'shipping_fee_enabled', 'value' => 'on']);
    StoreSetting::create(['key' => 'free_shipping_threshold', 'value' => '50']);

    $this->withSession(ssCart(26.00))->get('/cart')->assertOk()->assertSee('$24.00 away from free shipping');
});

it('lets an admin save settings that go live at once', function () {
    $this->actingAs(ssAdmin())->put(route('admin.store-settings.update'), ssForm([
        'shipping_fee_enabled' => 'on', 'free_shipping_threshold' => '40', 'flat_shipping_rate' => '5.50',
        'sold_badge_enabled' => '', 'delivery_min_days' => '4', 'delivery_max_days' => '6',
    ]))->assertSessionHas('success');

    expect(StoreSetting::pluck('value', 'key')->all())->toBe([
        'shipping_fee_enabled' => 'on', 'free_shipping_threshold' => '40', 'flat_shipping_rate' => '5.50',
        'delivery_min_days' => '4', 'delivery_max_days' => '6',
    ]);

    auth()->logout();
    $this->withSession(ssCart(26.00))->get('/cart')->assertOk()->assertSee('$14.00 away from free shipping')->assertSee('31.50');
});

it('returns to the .env default when a field is cleared or set back to Default', function () {
    $admin = ssAdmin();
    $this->actingAs($admin)->put(route('admin.store-settings.update'), ssForm(['shipping_fee_enabled' => 'on', 'flat_shipping_rate' => '7']));
    expect(StoreSetting::count())->toBe(2);

    $this->actingAs($admin)->put(route('admin.store-settings.update'), ssForm(['shipping_fee_enabled' => '', 'flat_shipping_rate' => '']));

    expect(StoreSetting::count())->toBe(0);
    auth()->logout();
    $this->withSession(ssCart(26.00))->get('/cart')->assertDontSee('away from free shipping');
});

it('rejects values out of range, a backwards delivery window, and ignores unknown keys', function () {
    $admin = ssAdmin();

    $this->actingAs($admin)->put(route('admin.store-settings.update'), ssForm(['free_shipping_threshold' => '0']))->assertSessionHasErrors('settings.free_shipping_threshold');
    $this->actingAs($admin)->put(route('admin.store-settings.update'), ssForm(['flat_shipping_rate' => '5000']))->assertSessionHasErrors('settings.flat_shipping_rate');
    $this->actingAs($admin)->put(route('admin.store-settings.update'), ssForm(['shipping_fee_enabled' => 'maybe']))->assertSessionHasErrors('settings.shipping_fee_enabled');
    $this->actingAs($admin)->put(route('admin.store-settings.update'), ssForm(['delivery_min_days' => '9', 'delivery_max_days' => '5']))->assertSessionHasErrors('settings.delivery_max_days');
    $this->actingAs($admin)->put(route('admin.store-settings.update'), ssForm(['stripe_secret' => 'sk_live_x', 'nonsense' => '1']))->assertSessionHasNoErrors();

    expect(StoreSetting::count())->toBe(0);
});

it('moves the delivery window and never lets the end fall below the start', function () {
    StoreSetting::create(['key' => 'delivery_min_days', 'value' => '2']);
    StoreSetting::create(['key' => 'delivery_max_days', 'value' => '3']);
    StoreSettings::apply();

    expect(DeliveryEstimate::minDays())->toBe(2)->and(DeliveryEstimate::maxDays())->toBe(3);

    StoreSetting::where('key', 'delivery_max_days')->delete();
    StoreSetting::where('key', 'delivery_min_days')->update(['value' => '12']);
    StoreSettings::apply();

    expect(DeliveryEstimate::minDays())->toBe(12)->and(DeliveryEstimate::maxDays())->toBeGreaterThanOrEqual(12);
});

it('shows each setting with its .env default and whether it is overridden', function () {
    StoreSetting::create(['key' => 'flat_shipping_rate', 'value' => '6']);

    $this->actingAs(ssAdmin())->get(route('admin.store-settings.index'))->assertOk()
        ->assertSee('Default (Off)')->assertSee('Default ($35.00)')->assertSee('Set here.')->assertSee('Using the .env default.');
});

it('keeps payment keys and tokens out of the screen', function () {
    foreach (StoreSettings::definitions() as $key => $def) {
        expect($def[4])->not->toStartWith('services.stripe')->not->toStartWith('services.paypal')->not->toContain('token')->not->toContain('secret');
    }
});

it('keeps non-admins out', function () {
    $this->get(route('admin.store-settings.index'))->assertRedirect();
    $this->put(route('admin.store-settings.update'), ssForm(['shipping_fee_enabled' => 'on']))->assertRedirect();

    expect(StoreSetting::count())->toBe(0);
});

it('does not take the site down if the settings table is missing', function () {
    Schema::drop('store_settings');

    $this->get('/')->assertOk();
    expect(StoreSettings::saved())->toBe([]);
});
