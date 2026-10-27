<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Services\GeoIp;
use App\Services\MarketCatalog;
use App\Support\PageViews;
use App\Support\SiteCopy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/** The shopper-facing marketplace: home, category and store pages, search. */
class MarketController extends Controller
{
    protected const EFFECTIVE = 'COALESCE(NULLIF(products.sale_price, 0), products.price)';

    public function __construct(protected MarketCatalog $catalog) {}

    public function home()
    {
        return view('market.home', ['h1' => SiteCopy::get('home.h1')] + \App\Support\MarketViews::home());
    }

    /** Remembers the shopper's delivery ZIP in a cookie; shown in the header and used for delivery dates. */
    public function location(Request $request)
    {
        $zip = $request->validate(['zip' => ['required', 'regex:/^\d{5}$/']])['zip'];

        // A ZIP the shopper typed replaces any estimate.
        return response()->json(['zip' => $zip])->cookie('pg_zip', $zip, 60 * 24 * 365, null, null, null, false)->withoutCookie('pg_zip_src');
    }

    /**
     * First visit with no ZIP: estimate it from the IP address, once. pg_zip_try stops repeat lookups for
     * a day whether or not it worked, and a ZIP the shopper already set is never touched.
     */
    public function detectLocation(Request $request, GeoIp $geo)
    {
        if ($request->cookie('pg_zip') || $request->cookie('pg_zip_try')) {
            return response()->json(['zip' => null]);
        }

        $zip = $geo->zip($request->ip());
        $res = response()->json(['zip' => $zip])->cookie('pg_zip_try', '1', 60 * 24, null, null, null, false);

        return $zip
            ? $res->cookie('pg_zip', $zip, 60 * 24 * 365, null, null, null, false)->cookie('pg_zip_src', 'ip', 60 * 24 * 365, null, null, null, false)
            : $res;
    }

    public function shop(Request $request)
    {
        return $this->page($request, Product::visible(), [
            'title' => SiteCopy::title('shop'), 'description' => SiteCopy::description('shop'),
            'heading' => 'All products', 'kicker' => 'SHOP',
        ]);
    }

    public function deals(Request $request)
    {
        return $this->page($request, Product::visible()->whereRaw('products.sale_price > 0 AND products.sale_price < products.price'), [
            'title' => "Today's Deals | PeytonGhalib", 'description' => 'Discounted products from independent US sellers on PeytonGhalib. Buyer Protection on every order.',
            'heading' => "Today's deals", 'kicker' => 'DEALS',
        ]);
    }

    public function category(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $parent = $category->parent_id ? $category->parent : $category;
        $ids = $this->catalog->idsFor($category);
        $subs = $this->catalog->tree()->firstWhere('id', $parent->id)?->children ?? collect();

        return $this->page($request, Product::visible()->whereIn('products.category_id', $ids), [
            'title' => SiteCopy::categoryTitle($category), 'description' => SiteCopy::categoryDescription($category),
            'heading' => $category->name, 'kicker' => strtoupper($parent->name),
            'subs' => collect([['name' => 'All ' . $parent->name, 'href' => route('category.landing', $parent->slug), 'on' => $category->id === $parent->id]])
                ->merge($subs->map(fn ($c) => ['name' => $c->name, 'href' => route('category.landing', $c->slug), 'on' => $category->id === $c->id]))->all(),
            'crumb' => $category->name,
        ]);
    }

    public function search(Request $request)
    {
        $raw = $request->query('q', '');
        $q = is_string($raw) ? trim(mb_substr($raw, 0, 100)) : ''; // q[]=x is not a search
        if ($q === '') {
            return redirect()->route('shop');
        }

        // Escape LIKE wildcards so "100%" searches for the text, not for everything.
        $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';
        $base = Product::visible()->where(fn ($w) => $w->where('products.name', 'like', $like)->orWhere('products.brand', 'like', $like)->orWhere('products.description', 'like', $like));
        $stores = Seller::live()->where('store_name', 'like', $like)->orderBy('store_name')->limit(6)->get();

        return $this->page($request, $base, [
            'title' => "{$q} | Search | Peyton Ghalib", 'description' => "Search results for {$q} on Peyton Ghalib.", 'robots' => 'noindex, follow',
            'heading' => "Results for \"{$q}\"", 'kicker' => 'SEARCH RESULTS', 'crumb' => "Search: {$q}",
            'subs' => $stores->map(fn ($s) => ['name' => 'Store: ' . $s->store_name, 'href' => $s->url(), 'on' => false])->all(),
        ]);
    }

    public function store(Request $request, string $slug, string $uuid)
    {
        $store = Seller::live()->where('uuid', $uuid)->firstOrFail();

        // A renamed store keeps working under its old name: send it to the current address.
        if ($store->slug !== $slug) {
            return redirect($store->url(), 301);
        }

        $products = Product::visible()->where('products.seller_id', $store->id)->with('seller:id,uuid,store_name,slug,level', 'variants')
            ->withAvg('reviews', 'rating')->withCount('reviews')->orderByDesc('products.id')->paginate(24)->withQueryString();

        return view('market.store', PageViews::store($store, $products) + [
            'title' => "{$store->store_name} | Peyton Ghalib",
            'description' => $store->about ? str($store->about)->limit(150)->toString() : "Shop {$store->store_name}, a verified US seller on Peyton Ghalib.",
        ]);
    }

    /** Links made before stores had their own id: /store/{slug} goes to the new address. */
    public function storeLegacy(string $slug)
    {
        return redirect(Seller::live()->where('slug', $slug)->firstOrFail()->url(), 301);
    }

    /** A click on a promoted listing: counted and billed once, then on to the product. */
    public function adClick(Request $request, string $slug, \App\Services\AdService $ads)
    {
        $product = Product::visible()->with('seller')->where('slug', $slug)->firstOrFail();
        $ads->click($product, $request);

        return redirect()->route('product-details', $product->slug);
    }

    protected function page(Request $request, Builder $base, array $meta)
    {
        // Promoted products lead the first page of a search or category, and are labelled as such.
        $promoted = $request->query('page', 1) == 1 && ! $request->boolean('instock') ? app(\App\Services\AdService::class)->pick($base) : collect();
        $page = $this->listing($request, $base);
        $sort = in_array($request->query('sort'), ['newest', 'price_asc', 'price_desc', 'rating'], true) ? $request->query('sort') : 'newest';

        return view('market.list', PageViews::list($request, $page, ['title' => $meta['heading'], 'kicker' => $meta['kicker'], 'crumb' => $meta['crumb'] ?? $meta['heading'],
            'sort' => $sort, 'subs' => $meta['subs'] ?? [], 'promoted' => $promoted]) + [
            'title' => $meta['title'], 'description' => $meta['description'], 'robots' => $meta['robots'] ?? null,
        ]);
    }

    /** Sort, price, stock, sale and rating filters plus pagination, shared by every listing page. */
    protected function listing(Request $request, Builder $base)
    {
        $min = $request->filled('min') && is_numeric($request->query('min')) ? max(0, (float) $request->query('min')) : null;
        $max = $request->filled('max') && is_numeric($request->query('max')) ? max(0, (float) $request->query('max')) : null;
        $sort = in_array($request->query('sort'), ['newest', 'price_asc', 'price_desc', 'rating'], true) ? $request->query('sort') : 'newest';

        return $base->with('seller:id,uuid,store_name,slug,level', 'variants', 'category.parent')->withAvg('reviews', 'rating')->withCount('reviews')
            ->when($min !== null, fn ($q) => $q->whereRaw(self::EFFECTIVE . ' >= CAST(? AS DECIMAL(10,2))', [$min]))
            ->when($max !== null, fn ($q) => $q->whereRaw(self::EFFECTIVE . ' <= CAST(? AS DECIMAL(10,2))', [$max]))
            ->when($request->boolean('instock'), fn ($q) => $q->where('products.stock', '>', 0))
            ->when($request->boolean('sale'), fn ($q) => $q->whereRaw('products.sale_price > 0 AND products.sale_price < products.price'))
            ->when($request->boolean('top'), fn ($q) => $q->whereIn('products.id', \App\Models\Review::query()->select('product_id')->groupBy('product_id')->havingRaw('AVG(rating) >= 4')))
            ->when($sort === 'price_asc', fn ($q) => $q->orderByRaw(self::EFFECTIVE . ' asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderByRaw(self::EFFECTIVE . ' desc'))
            ->when($sort === 'rating', fn ($q) => $q->orderByDesc('reviews_avg_rating'))
            ->orderByDesc('products.id')
            ->paginate(24)->withQueryString();
    }
}
