<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteText;
use App\Models\User;
use App\Support\SiteCopy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function p5Product(string $name, ?string $description = null, array $over = []): Product
{
    $cat = Category::firstOrCreate(['slug' => 'home-essentials'], ['name' => 'Home Essentials', 'is_active' => true]);

    return Product::create($over + ['category_id' => $cat->id, 'name' => $name, 'slug' => \Illuminate\Support\Str::slug($name) . '-' . uniqid(),
        'description' => $description, 'price' => 20, 'stock' => 5, 'is_active' => true]);
}

function p5Title(string $html): string
{
    preg_match('#<title>(.*?)</title>#s', $html, $m);

    return html_entity_decode(trim($m[1] ?? ''));
}

function p5Desc(string $html): string
{
    preg_match('#<meta name="description" content="([^"]*)"#', $html, $m);

    return html_entity_decode($m[1] ?? '');
}

it('uses the new titles and descriptions on the static pages, brand exactly once', function () {
    $pages = ['/' => 'home', '/shop' => 'shop', '/categories' => 'categories', '/about' => 'about', '/contact' => 'contact',
        '/faq' => 'faq', '/shipping-policy' => 'shipping-policy', '/return-policy' => 'return-policy', '/refund-policy' => 'refund-policy', '/track-order' => 'track-order'];

    foreach ($pages as $url => $key) {
        $html = $this->get($url)->assertOk()->getContent();
        expect(p5Title($html))->toBe(SiteCopy::title($key))
            ->and(preg_match('/PeytonGhalib.*PeytonGhalib/', p5Title($html)) === 1 && str_ends_with(p5Title($html), '| PeytonGhalib | PeytonGhalib'))->toBeFalse()
            ->and(p5Desc($html))->toBe(SiteCopy::description($key));
    }

    expect(SiteCopy::title('home'))->toBe('Shop Direct from Independent US Sellers | PeytonGhalib')
        ->and(SiteCopy::title('shop'))->toBe('Shop All Products | PeytonGhalib')
        ->and(SiteCopy::title('about'))->toBe('About PeytonGhalib | Gifts & Home Store in NJ');
});

it('keeps the shipping wording in step with the free-shipping threshold', function () {
    expect(SiteCopy::description('home'))->toContain('Free US shipping, Buyer Protection');

    config(['checkout.shipping_fee_enabled' => true, 'checkout.free_shipping_threshold' => 35]);

    expect(SiteCopy::description('home'))->toContain('Free US shipping on $35+, Buyer Protection')
        ->and(p5Desc($this->get('/')->getContent()))->toContain('Free US shipping on $35+');
});

it('no longer calls itself a toy store on the home page, footer, about page or FAQ', function () {
    foreach (['/', '/about', '/faq', '/shipping-policy', '/return-policy', '/refund-policy', '/contact'] as $url) {
        $html = strip_tags($this->get($url)->getContent());
        expect(stripos($html, 'toy store'))->toBeFalse("$url still says toy store");
        expect(stripos($html, 'Toys & Games for Kids'))->toBeFalse();
    }

    $home = $this->get('/')->getContent();
    expect($home)->toContain('Shop direct from independent American sellers')->and($home)->not->toContain('crafted for modern living spaces');

    $about = $this->get('/about')->getContent();
    expect($about)->toContain('independent American sellers')->and($about)->not->toContain('24/7 support');
});

it('lets an admin override site text, falls back to the default when blank, and ignores unknown keys', function () {
    $admin = User::create(['name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'role' => 'admin']);

    $this->actingAs($admin)->get(route('admin.site-text.index'))->assertOk()->assertSee('Site-wide')->assertSee('Shipping policy')->assertSee('Search results (title and description)');
    $this->actingAs($admin)->put(route('admin.site-text.update'), ['texts' => [
        'home.h1' => 'Presents for everyone', 'meta.faq.title' => 'Help Centre', 'nonsense.key' => 'x',
    ]])->assertSessionHas('success');

    expect(SiteText::count())->toBe(2)->and(SiteText::where('key', 'nonsense.key')->exists())->toBeFalse();
    expect($this->get('/')->getContent())->toContain('Presents for everyone');
    expect(p5Title($this->get('/faq')->getContent()))->toBe('Help Centre | PeytonGhalib');

    $this->actingAs($admin)->put(route('admin.site-text.update'), ['texts' => ['home.h1' => '']]);
    expect(SiteCopy::get('home.h1'))->toBe('Shop direct from independent American sellers');
});

it('keeps non-admins out of the site text screen', function () {
    $this->get(route('admin.site-text.index'))->assertRedirect();
});

it('uses a category own meta first, then the slug default, and never doubles the brand', function () {
    Category::create(['name' => 'Garden', 'slug' => 'garden', 'is_active' => true]);
    Category::create(['name' => 'Halloween', 'slug' => 'halloween', 'is_active' => true, 'meta_title' => 'Spooky Season Shop | PeytonGhalib', 'meta_description' => 'Custom Halloween text.']);

    $garden = $this->get('/category/garden')->assertOk()->getContent();
    expect(p5Title($garden))->toBe('Garden Décor & Outdoor Accessories | PeytonGhalib')
        ->and(p5Desc($garden))->toContain('Shop garden décor')->and(p5Desc($garden))->toContain('Free US shipping and 30-day returns');

    $hal = $this->get('/category/halloween')->assertOk()->getContent();
    expect(p5Title($hal))->toBe('Spooky Season Shop | PeytonGhalib')->and(p5Desc($hal))->toBe('Custom Halloween text.');
});

it('builds product titles of at most 60 characters, cut at a word boundary, brand once', function () {
    $long = p5Product("Cicy Bell Women's Plaid Trench Coat Long Single Breasted Lapel Collar Fall Winter Jacket Belted Outerwear");

    $title = SiteCopy::productTitle($long);
    $name  = substr($title, 0, -strlen(' | PeytonGhalib'));
    expect(mb_strlen($title))->toBeLessThanOrEqual(60)->and($title)->toEndWith(' | PeytonGhalib')->and(substr_count($title, 'PeytonGhalib'))->toBe(1)
        ->and(str_starts_with($long->name, $name))->toBeTrue()
        ->and($long->name[strlen($name)])->toBe(' ');   // the cut falls on a space: no word is split

    $short = p5Product('Throw Blanket', null, ['meta_title' => 'Cozy Throw Blanket | PeytonGhalib']);
    expect(SiteCopy::productTitle($short))->toBe('Cozy Throw Blanket | PeytonGhalib');

    expect(p5Title($this->get(route('product-details', $long->slug))->getContent()))->toBe($title);
});

it('builds product descriptions of 120-155 characters from the summary, padding thin ones with the shipping promise', function () {
    $rich = p5Product('Rich', '<p>' . str_repeat('A soft chunky knit throw for cold evenings on the sofa. ', 8) . '</p>');
    $thin = p5Product('Thin', 'A small ceramic vase.');

    expect(mb_strlen(SiteCopy::productDescription($rich)))->toBeBetween(120, 155)
        ->and(SiteCopy::productDescription($thin))->toBe('A small ceramic vase. Free US shipping and 30-day returns.');

    $mid = p5Product('Mid', 'A hand-finished ceramic vase with a matte glaze, ideal for dried flowers on a shelf or table.');
    expect(mb_strlen(SiteCopy::productDescription($mid)))->toBeLessThanOrEqual(155);

    $explicit = p5Product('Own', 'ignored', ['meta_description' => 'Our own hand-written snippet.']);
    expect(SiteCopy::productDescription($explicit))->toBe('Our own hand-written snippet.');
});

it('previews, then writes, the default category meta', function () {
    Category::create(['name' => 'Garden', 'slug' => 'garden', 'is_active' => true, 'meta_title' => 'Old toy wording', 'meta_description' => 'Old']);
    Category::create(['name' => 'Misc', 'slug' => 'misc-unknown', 'is_active' => true, 'meta_title' => 'Keep me']);

    $this->artisan('seo:apply-category-meta')->assertSuccessful();
    expect(Category::where('slug', 'garden')->first()->meta_title)->toBe('Old toy wording');

    $this->artisan('seo:apply-category-meta', ['--force' => true])->assertSuccessful();
    expect(Category::where('slug', 'garden')->first()->meta_title)->toBe('Garden Décor & Outdoor Accessories | PeytonGhalib')
        ->and(Category::where('slug', 'garden')->first()->meta_description)->toContain('{shipping}')
        ->and(Category::where('slug', 'misc-unknown')->first()->meta_title)->toBe('Keep me');
});

it('survives a missing site_texts table', function () {
    \Illuminate\Support\Facades\Schema::drop('site_texts');
    SiteCopy::forget();

    expect(SiteCopy::title('home'))->toBe('Shop Direct from Independent US Sellers | PeytonGhalib');
    $this->get('/')->assertOk();
});
