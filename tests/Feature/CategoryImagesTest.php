<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function ciCategory(string $name, ?string $image): Category
{
    $u = User::factory()->create(['role' => 'seller']);
    $s = Seller::firstOrCreate(['slug' => 'maple'], ['user_id' => $u->id, 'store_name' => 'Maple', 'status' => 'approved', 'subscription_status' => 'active', 'onboarding_step' => 'done']);
    $c = Category::create(['name' => $name, 'slug' => str($name)->slug(), 'image' => $image, 'is_active' => true]);
    Product::create(['seller_id' => $s->id, 'category_id' => $c->id, 'name' => $name . ' Item', 'slug' => str($name)->slug() . '-item', 'price' => 10, 'stock' => 3, 'is_active' => true, 'moderation_status' => 'approved']);

    return $c;
}

it('shows an uploaded category picture on the home page and the all-categories page, and keeps the initial for one without', function () {
    ciCategory('Lamps', 'categories/lamps.webp');
    ciCategory('Books', null);

    foreach (['/', '/sitemap'] as $url) {
        $html = $this->get($url)->assertOk()->getContent();
        expect($html)->toContain('/storage/categories/lamps.webp')->not->toContain('categories/books');
    }
    $this->get('/')->assertSee('Lamps')->assertSee('Books');
});
