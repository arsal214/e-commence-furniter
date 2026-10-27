<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/** The card numbers in the order the home page shows them. */
function heroOrder($test): array
{
    preg_match_all('#assets/cards/hero-(\d+)\.png#', $test->get('/')->assertOk()->getContent(), $m);

    return array_map('intval', $m[1]);
}

it('shows all ten ready-made hero cards, every picture file present', function () {
    $order = heroOrder($this);
    expect($order)->toHaveCount(10)->and(collect($order)->sort()->values()->all())->toBe(range(1, 10));

    foreach (range(1, 10) as $n) {
        expect(public_path("assets/cards/hero-{$n}.png"))->toBeFile();
    }
});

it('shuffles the cards on every page load', function () {
    $orders = collect(range(1, 8))->map(fn () => implode(',', heroOrder($this)))->unique();
    expect($orders->count())->toBeGreaterThan(1);
});

it('links a card to its category when the shop has one, else to a search, keeping the price cap', function () {
    $html = $this->get('/')->getContent();
    expect($html)->toContain('/search?q=halloween')->toContain('/search?q=gift&amp;max=25');

    Category::create(['name' => 'Halloween', 'slug' => 'halloween', 'is_active' => true]);
    Category::create(['name' => 'Gifts', 'slug' => 'gifts', 'is_active' => true]);
    $html = $this->get('/')->getContent();
    expect($html)->toContain('/category/halloween"')->toContain('/category/gifts?max=25')->not->toContain('/search?q=halloween');
});
