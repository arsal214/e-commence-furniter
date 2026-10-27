<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Services\ShippingService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/** Data for the listing, product, store and cart pages, in the shapes the design uses. */
class PageViews
{
    private const LEVEL = ['new' => 'New Seller', 'rising' => 'Rising Seller', 'top' => 'Top Seller', 'elite' => 'Elite Seller'];

    // ── listing (category, shop, search, deals, store) ─────────────────────────────

    public static function list(Request $request, LengthAwarePaginator $page, array $meta): array
    {
        $sort = $meta['sort'];
        $query = fn (array $over) => array_filter(array_merge($request->except('page'), $over), fn ($v) => $v !== null && $v !== '' && $v !== false);
        $base = url($request->path());
        $chk = fn (bool $on) => ['bg' => $on ? '#C2410C' : '#FFFFFF', 'bd' => $on ? '#C2410C' : '#9CA3AF', 'mark' => $on ? '✓' : ''];

        $filters = collect([['instock', 'In stock only'], ['sale', 'On sale'], ['top', 'Rated 4 stars & up']])->map(function ($f) use ($request, $query, $base, $chk) {
            $on = $request->boolean($f[0]);
            $qs = http_build_query($query([$f[0] => $on ? null : 1]));

            return ['label' => $f[1], 'href' => $base . ($qs ? "?{$qs}" : '')] + $chk($on);
        })->all();

        $sorts = collect(['newest' => 'Featured', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'rating' => 'Top rated'])->map(function ($label, $key) use ($sort, $query, $base) {
            $qs = http_build_query($query(['sort' => $key === 'newest' ? null : $key]));

            return ['label' => $label, 'url' => $base . ($qs ? "?{$qs}" : ''), 'selected' => $sort === $key];
        })->values()->all();

        $subs = collect($meta['subs'] ?? [])->map(fn ($s) => ['name' => $s['name'], 'href' => $s['href'], 'bg' => $s['on'] ? '#111827' : '#FFFFFF', 'fg' => $s['on'] ? '#FFFFFF' : '#111827',
            'bd' => $s['on'] ? '#111827' : '#E5E7EB'])->all();

        return ['list' => [
            'title' => $meta['title'], 'kicker' => $meta['kicker'], 'crumb' => $meta['crumb'] ?? $meta['title'], 'count' => number_format($page->total()) . ' ' . str('result')->plural($page->total()),
            'items' => collect($meta['promoted'] ?? [])->map(fn (Product $p) => MarketViews::card($p) + ['sponsored' => true, 'href' => route('ad.click', $p->slug)])
                ->concat($page->getCollection()->reject(fn ($p) => collect($meta['promoted'] ?? [])->contains('id', $p->id))->map(fn (Product $p) => MarketViews::card($p)))->all(), 'some' => $page->count() > 0, 'none' => $page->count() === 0,
            'hasSubs' => count($subs) > 0, 'subs' => $subs, 'filters' => $filters, 'sorts' => $sorts, 'sort' => $sort, 'clear' => $base,
            'suggestions' => $page->count() === 0 ? Product::visible()->with('category.parent')->withAvg('reviews', 'rating')->withCount('reviews')->latest('products.id')->limit(5)->get()->map(fn ($p) => MarketViews::card($p))->all() : [],
            'pager' => self::pager($page), 'baseUrl' => $base,
        ]];
    }

    public static function pager(LengthAwarePaginator $p): string
    {
        if ($p->lastPage() <= 1) {
            return '';
        }
        $link = fn ($url, $text) => $url ? '<a href="' . e($url) . '" style="height:44px;padding:0 20px;border-radius:999px;border:1.5px solid #111827;color:#111827;font-weight:700;font-size:14px;display:flex;align-items:center">' . $text . '</a>'
            : '<span style="height:44px;padding:0 20px;border-radius:999px;border:1.5px solid #E5E7EB;color:#9CA3AF;font-size:14px;display:flex;align-items:center">' . $text . '</span>';

        return '<nav aria-label="Pages" style="display:flex;gap:12px;align-items:center;justify-content:center;margin-top:28px">' . $link($p->previousPageUrl(), '← Previous')
            . '<span style="font-size:14px;color:#4B5563">Page ' . $p->currentPage() . ' of ' . $p->lastPage() . '</span>' . $link($p->nextPageUrl(), 'Next →') . '</nav>';
    }

    // ── product ────────────────────────────────────────────────────────────────────

    public static function product(Product $p, array $delivery, $related, $bundle, $set): array
    {
        $p->loadMissing('category.parent', 'seller', 'variants', 'productImages', 'reviews.user');
        $seller = $p->seller;
        $root = $p->category?->parent ?? $p->category;
        $active = $p->variants->where('is_active', true);
        $colors = $active->where('type', 'color')->values();
        $sizes = $active->where('type', 'size')->values();
        $url = fn ($path) => Product::imageUrl($path);
        $gallery = collect([$p->image])->merge($p->productImages->where('type', 'image')->pluck('image'))->filter()->unique()->values();
        $reviews = $p->reviews->sortByDesc('id')->values();
        $avg = $reviews->count() ? round((float) $reviews->avg('rating'), 1) : null;
        $wished = in_array($p->id, \App\Models\Wishlist::productIdsForCurrentUser(), true);

        $firstColor = $colors->first(fn ($v) => $v->stock > 0)?->value ?? $colors->first()?->value;
        $firstSize = $sizes->first(fn ($v) => $v->stock > 0)?->value ?? $sizes->first()?->value;
        $stock = ($colors->isNotEmpty() || $sizes->isNotEmpty()) ? $p->effectiveStockFor($firstColor, $firstSize) : (int) $p->stock;
        $price = (float) $p->from_price;
        $was = $p->has_strike ? (float) $p->price : 0.0;
        $sellerRating = $seller ? \App\Models\Review::whereIn('product_id', $seller->products()->pluck('id'))->avg('rating') : null;
        $bdr = fn (bool $on) => $on ? '#111827' : '#D1D5DB';
        $opt = fn ($v) => ['price' => (float) $v->effective_price, 'list' => (float) $v->price, 'stock' => (int) $v->stock, 'image' => $v->image ? $url($v->image) : null];

        return ['pdp' => [
            'id' => $p->id, 'name' => $p->name, 'sub' => $p->category?->name ?? '', 'subHref' => $p->category ? route('category.landing', $p->category->slug) : url('/shop'),
            'catName' => $root?->name ?? 'Shop', 'catHref' => $root ? route('category.landing', $root->slug) : url('/shop'), 'tint' => MarketViews::tint($root?->slug, $root?->id),
            'img' => $gallery->isNotEmpty() ? $url($gallery[0]) : null,
            'thumbs' => $gallery->count() > 1 ? $gallery->map(fn ($g, $i) => ['label' => 'Photo ' . ($i + 1), 'img' => $url($g), 'bd' => $i === 0 ? '#111827' : '#E5E7EB'])->all() : [],
            'storeSlug' => $seller?->slug ?? '', 'store' => $seller?->store_name ?? 'Peyton Ghalib', 'storeHref' => $seller?->isLive() ? $seller->url() : url('/shop'),
            'level' => $seller ? self::LEVEL[$seller->level] : 'Peyton Ghalib', 'lvBg' => '#FFF1E8', 'lvFg' => '#9A3412',
            'storeInitial' => mb_strtoupper(mb_substr($seller?->store_name ?? 'P', 0, 1)), 'city' => $seller ? trim("{$seller->city}, {$seller->state}", ', ') : 'the US',
            'storeMeta' => ($sellerRating ? '★ ' . number_format((float) $sellerRating, 1) . ' · ' : '') . ($seller?->approved_at ? 'On Peyton Ghalib since ' . $seller->approved_at->format('Y') : 'Peyton Ghalib'),
            'rating' => $avg !== null ? number_format($avg, 1) : 'New', 'reviews' => number_format($reviews->count()),
            'stars' => str_repeat('★', (int) round($avg ?? 0)) . str_repeat('☆', 5 - (int) round($avg ?? 0)),
            'earn' => number_format((int) floor((float) $p->effective_price)), 'sold' => \App\Services\SoldCounts::label($p->id), 'priceNum' => number_format((float) $p->effective_price, 2, '.', ''),
            'priceStr' => MarketViews::money($price), 'hasWas' => $was > $price, 'wasStr' => MarketViews::money($was), 'off' => $was > $price ? '−' . round((1 - $price / $was) * 100) . '%' : '',
            'hasColors' => $colors->isNotEmpty(), 'colorName' => $firstColor ?? '',
            'colors' => $colors->map(fn ($v) => ['name' => $v->value, 'hex' => Product::colorHex($v->value), 'ring' => $v->value === $firstColor ? '#C2410C' : '#E5E7EB', 'out' => $v->stock <= 0])->all(),
            'hasSizes' => $sizes->isNotEmpty(), 'sizeName' => $firstSize ?? '',
            'sizes' => $sizes->map(fn ($v) => ['label' => $v->value, 'hasNote' => false, 'note' => '', 'out' => $v->stock <= 0, 'bd' => $bdr($v->value === $firstSize),
                'bg' => $v->value === $firstSize ? '#111827' : '#FFFFFF', 'fg' => $v->value === $firstSize ? '#FFFFFF' : '#111827'])->all(),
            'stockText' => $stock <= 0 ? 'Out of stock' : ($stock <= 5 ? "Only {$stock} left" : 'In stock'), 'stockColor' => $stock <= 0 ? '#B91C1C' : ($stock <= 5 ? '#92400E' : '#15803D'),
            'soldOut' => $stock <= 0, 'qty' => 1, 'wishLabel' => $wished ? '♥ Saved to wishlist' : '♡ Save to wishlist', 'wishColor' => $wished ? '#DC2626' : '#111827',
            'arrive' => $delivery['earliest']->format('D, M j') . ' – ' . $delivery['latest']->format('D, M j'),
            'returns' => $seller ? $seller->return_window_days . '-day returns' : '30-day returns',
            'returnsNote' => $seller ? ($seller->return_shipping_payer === 'seller' ? 'seller pays return shipping' : 'buyer pays return shipping') : 'See our return policy',
            'desc' => (string) $p->description,
            'specs' => collect($p->specifications ?? [])->map(fn ($s) => ['k' => $s['label'] ?? '', 'v' => $s['value'] ?? ''])->filter(fn ($s) => $s['k'] !== '')->values()->all(),
            'tags' => [],
            'bars' => collect([5, 4, 3, 2, 1])->map(function ($s) use ($reviews) {
                $pct = $reviews->count() ? round($reviews->where('rating', $s)->count() / $reviews->count() * 100) : 0;

                return ['s' => $s, 'pct' => $pct . '%', 'w' => $pct . '%'];
            })->all(),
            'reviewsList' => $reviews->take(10)->map(fn ($r) => ['name' => $r->author_name, 'stars' => str_repeat('★', (int) $r->rating) . str_repeat('☆', 5 - (int) $r->rating), 'title' => '', 'loc' => $r->country ?: 'United States',
                'date' => $r->created_at->format('M j, Y'), 'badge' => $r->is_verified ? 'Verified purchase' : 'Review', 'text' => (string) $r->comment, 'hasPhotos' => false, 'photos' => [],
                'reply' => (string) $r->seller_reply, 'replyBy' => $seller?->store_name ?? 'the seller'])->all(),
            'related' => $related->map(fn ($x) => MarketViews::card($x))->all(),
            'fbt' => $bundle->map(fn ($b) => ['id' => $b->id, 'name' => $b->name, 'href' => route('product-details', $b->slug), 'price' => MarketViews::money($b->effective_price)])->all(),
            'set' => $set ? ['id' => $set->id, 'saves' => MarketViews::money($set->savings()), 'name' => $set->name,
                'parts' => $set->items->map(fn ($i) => $i->product->name . ' (' . MarketViews::money($i->unitPrice()) . ')')->implode(' + '),
                'regular' => MarketViews::money($set->regularTotal()), 'price' => MarketViews::money($set->price)] : null,
            'variants' => ['colors' => $colors->mapWithKeys(fn ($v) => [$v->value => $opt($v)])->all(), 'sizes' => $sizes->mapWithKeys(fn ($v) => [$v->value => $opt($v)])->all(),
                'base' => ['price' => (float) $p->effective_price, 'list' => (float) $p->price, 'stock' => (int) $p->stock]],
        ]];
    }

    // ── store ──────────────────────────────────────────────────────────────────────

    public static function store(Seller $s, LengthAwarePaginator $page): array
    {
        $rating = \App\Models\Review::whereIn('product_id', $s->products()->pluck('id'))->avg('rating');

        return ['store' => [
            'name' => $s->store_name, 'initial' => mb_substr($s->store_name, 0, 1),
            'slug' => $s->slug, 'followers' => ($n = \App\Models\StoreFollow::where('seller_id', $s->id)->count()) . ' ' . str('follower')->plural($n),
            'fLabel' => auth()->check() && \App\Models\StoreFollow::where('seller_id', $s->id)->where('user_id', auth()->id())->exists() ? 'Following ✓' : 'Follow',
            'fBg' => auth()->check() && \App\Models\StoreFollow::where('seller_id', $s->id)->where('user_id', auth()->id())->exists() ? '#111827' : '#FFFFFF', 'fFg' => auth()->check() && \App\Models\StoreFollow::where('seller_id', $s->id)->where('user_id', auth()->id())->exists() ? '#FFFFFF' : '#111827', 'level' => self::LEVEL[$s->level], 'city' => trim("{$s->city}, {$s->state}", ', '), 'since' => $s->approved_at?->format('Y') ?? now()->format('Y'),
            'rating' => $rating ? number_format((float) $rating, 1) : 'New', 'products' => number_format($page->total()), 'ships' => $s->handling_days . ' ' . str('day')->plural($s->handling_days),
            'returns' => $s->return_window_days . ' days', 'hasSale' => false, 'sale' => '',
            'policy' => "Returns accepted within {$s->return_window_days} days. " . ($s->return_shipping_payer === 'seller' ? 'The seller pays return shipping.' : 'The buyer pays return shipping for change-of-mind returns; damaged or wrong items are always free to return.') . " Orders ship within {$s->handling_days} business days with tracking.",
            'about' => $s->about ?: 'An independent American business verified by Peyton Ghalib. Business registration, EIN and owner identity were reviewed before the store opened.',
            'items' => $page->getCollection()->map(fn (Product $p) => MarketViews::card($p))->all(), 'pager' => self::pager($page),
        ]];
    }

    // ── cart ───────────────────────────────────────────────────────────────────────

    public static function cart(array $items, float $subtotal, ShippingService $shipping, float $welcomeDiscount, array $coupon = ['code' => null, 'discount' => 0.0], array $points = ['points' => 0, 'discount' => 0.0, 'available' => 0, 'on' => false], float $gift = 0.0): array
    {
        $products = Product::with('seller:id,uuid,store_name,slug,level,city,state', 'category.parent')->whereIn('id', collect($items)->pluck('id')->filter())->get()->keyBy('id');
        $groups = [];

        foreach ($items as $key => $line) {
            $p = $products->get($line['id'] ?? 0);
            $seller = $p?->seller;
            $gk = $seller?->id ?? 'house';
            $groups[$gk] ??= ['store' => $seller?->store_name ?? 'Peyton Ghalib', 'href' => $seller?->isLive() ? $seller->url() : url('/shop'),
                'level' => $seller ? self::LEVEL[$seller->level] : '', 'lvBg' => '#FFF1E8', 'lvFg' => '#9A3412', 'city' => $seller ? trim("{$seller->city}, {$seller->state}", ', ') : 'the US', 'items' => [], 'sum' => 0.0];
            $lineTotal = (float) $line['price'] * (int) $line['qty'];
            $groups[$gk]['sum'] += $lineTotal;
            $groups[$gk]['items'][] = [
                'key' => $line['key'] ?? $key, 'qty' => (int) $line['qty'], 'name' => $line['name'], 'href' => $p ? route('product-details', $p->slug) : url('/shop'),
                'sub' => $p?->category?->name ?? (! empty($line['bundle_id']) ? 'Set' : ''), 'tint' => MarketViews::tint(($p?->category?->parent ?? $p?->category)?->slug), 'img' => $p?->image ? Product::imageUrl($p->image) : null,
                'variant' => collect([$line['color'] ?? null, $line['size'] ?? null])->filter()->implode(' · ') ?: 'Standard', 'unitStr' => MarketViews::money($line['price']), 'line' => MarketViews::money($lineTotal),
                'less' => max(0, (int) $line['qty'] - 1), 'more' => (int) $line['qty'] + 1,
            ];
        }
        $delivery = \App\Support\DeliveryEstimate::window();
        $count = collect($items)->sum('qty');
        $shipCost = $shipping->cost($subtotal);
        $total = max(0, $subtotal - $welcomeDiscount - $coupon['discount'] - $points['discount'] + $shipCost - $gift);

        return ['cv' => [
            'count' => $count, 'sellers' => count($groups), 'hasItems' => $count > 0, 'empty' => $count === 0, 'subtotal' => MarketViews::money($subtotal),
            'hasCoupon' => $welcomeDiscount > 0, 'coupon' => '−' . MarketViews::money($welcomeDiscount), 'giftOn' => $gift > 0, 'giftOff' => '−' . MarketViews::money($gift), 'ptsAvail' => $points['available'] > 0, 'ptsOn' => $points['discount'] > 0, 'ptsUse' => number_format($points['available'] ?: 0), 'ptsOff' => '−' . MarketViews::money($points['discount']), 'ptsWorth' => MarketViews::money($points['available'] / 100),
            'couponOn' => $coupon['discount'] > 0, 'couponCode' => $coupon['code'], 'couponOff' => '−' . MarketViews::money($coupon['discount']), 'couponMsg' => $coupon['reason'] ?? null, 'hasBundle' => false, 'hasPts' => false,
            'shipping' => $shipCost > 0 ? MarketViews::money($shipCost) : 'Free', 'total' => MarketViews::money($total), 'earn' => 0,
            'list' => collect($groups)->map(fn ($g) => $g + ['notFree' => false, 'free' => false, 'hasBundle' => false, 'arrive' => $delivery['earliest']->format('D, M j') . ' – ' . $delivery['latest']->format('D, M j'),
                'shipStr' => $shipping->enabled() ? 'see order summary' : 'Free'])->sortBy('store')->values()->all(),
        ]];
    }
}
