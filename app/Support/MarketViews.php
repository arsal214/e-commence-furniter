<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Seller;
use App\Services\CartService;
use App\Services\MarketCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Builds the arrays the design's templates (resources/views/market/dc) expect, from real data.
 * The shapes match the design's own mock data, so the transpiled markup needs no changes.
 */
class MarketViews
{
    /** Category tint colours from the design; any other category gets a stable one from the list. */
    public const TINTS = [
        'fashion' => '#F6EBDD', 'home-kitchen' => '#E7EEF5', 'beauty-health' => '#F9E7EC', 'toys-games' => '#FFF2D4', 'pet-supplies' => '#E6F3E3',
        'sports-outdoors' => '#E2F0F8', 'automotive' => '#ECEEF1', 'jewelry' => '#F1E9F7', 'baby' => '#FDEBDD', 'seasonal-holiday' => '#FFE3CC', 'handmade' => '#EFE7DC',
    ];
    private const FALLBACK_TINTS = ['#F6EBDD', '#E7EEF5', '#F9E7EC', '#FFF2D4', '#E6F3E3', '#E2F0F8', '#F1E9F7'];

    private const PATHS = [
        'search' => "<circle cx='11' cy='11' r='7'/><path d='m20 20-3.5-3.5'/>",
        'cart' => "<circle cx='9' cy='20' r='1.4'/><circle cx='18' cy='20' r='1.4'/><path d='M3 4h2l2.4 11.2a1 1 0 0 0 1 .8h9.2a1 1 0 0 0 1-.8L20 8H6.2'/>",
        'user' => "<circle cx='12' cy='8' r='4'/><path d='M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6'/>",
        'heart' => "<path d='M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z'/>",
        'menu' => "<path d='M4 7h16M4 12h16M4 17h16'/>",
        'home' => "<path d='M4 11 12 4l8 7v9h-5v-6H9v6H4z'/>",
        'grid' => "<rect x='4' y='4' width='7' height='7' rx='1.5'/><rect x='13' y='4' width='7' height='7' rx='1.5'/><rect x='4' y='13' width='7' height='7' rx='1.5'/><rect x='13' y='13' width='7' height='7' rx='1.5'/>",
        'tag' => "<path d='M3 12V4h8l10 10-8 8z'/><circle cx='7.5' cy='7.5' r='1.2'/>",
        'shield' => "<path d='M12 3 5 6v6c0 4.5 3 7.5 7 9 4-1.5 7-4.5 7-9V6z'/><path d='m9 12 2 2 4-4'/>",
        'ret' => "<path d='M9 14 4 9l5-5'/><path d='M4 9h11a5 5 0 0 1 0 10h-3'/>",
        'lock' => "<rect x='5' y='11' width='14' height='10' rx='2'/><path d='M8 11V8a4 4 0 0 1 8 0v3'/>",
        'chat' => "<path d='M4 5h16v11H9l-5 4z'/>",
        'close' => "<path d='M6 6l12 12M18 6 6 18'/>",
        'left' => "<path d='m15 18-6-6 6-6'/>", 'right' => "<path d='m9 18 6-6-6-6'/>",
        'badge' => "<circle cx='12' cy='9' r='6'/><path d='m9 14-1 7 4-2 4 2-1-7'/>",
        'pin' => "<path d='M12 21s-7-6-7-11a7 7 0 0 1 14 0c0 5-7 11-7 11z'/><circle cx='12' cy='10' r='2.5'/>",
        'flag' => "<path d='M5 21V4M5 4h12l-2 4 2 4H5'/>",
    ];

    public static function icon(string $d, string $color = '#111827', float $width = 2): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="' . $width . '" stroke-linecap="round" stroke-linejoin="round">' . $d . '</svg>';

        return 'url("data:image/svg+xml,' . rawurlencode($svg) . '")';
    }

    public static function icons(): array
    {
        $p = self::PATHS;
        $i = fn (string $k, string $c = '#111827', float $w = 2) => self::icon($p[$k], $c, $w);

        return [
            'search' => $i('search'), 'searchW' => $i('search', '#FFFFFF'), 'cart' => $i('cart'), 'cartW' => $i('cart', '#FFFFFF'), 'user' => $i('user'),
            'heart' => $i('heart'), 'menu' => $i('menu'), 'closeW' => $i('close', '#FFFFFF'), 'menuW' => $i('menu', '#FFFFFF'), 'menuO' => $i('menu', '#9A3412', 2.4),
            'chev' => $i('right', '#6B7280', 2.2), 'chevL' => $i('left', '#111827', 2.4),
            'userCircle' => self::icon("<circle cx='12' cy='12' r='10'/><circle cx='12' cy='10' r='3.2'/><path d='M6.5 18.5c1.3-2.3 3.2-3.5 5.5-3.5s4.2 1.2 5.5 3.5'/>", '#FFFFFF', 1.8),
            'userW' => $i('user', '#FFFFFF'), 'heartW' => $i('heart', '#FFFFFF'), 'pinW' => $i('pin', '#D1D5DB'), 'close' => $i('close'), 'left' => $i('left'), 'right' => $i('right'),
            'lock' => $i('lock', '#15803D'), 'lockW' => $i('lock', '#CBD5E1'), 'chatW' => $i('chat', '#FFFFFF'), 'chat' => $i('chat'), 'shield' => $i('shield', '#15803D'),
            'ret' => $i('ret', '#C2410C'), 'badge' => $i('badge', '#C2410C'), 'pin' => $i('pin', '#4B5563'), 'flag' => $i('flag', '#6B7280'),
        ];
    }

    /** Design order for the categories it knows, then the rest by name. */
    public static function ordered(Collection $tree): Collection
    {
        $order = array_flip(array_keys(self::TINTS));

        return $tree->sortBy(fn ($c) => [$order[$c->slug] ?? 99, $c->name])->values();
    }

    public static function tint(?string $slug, ?int $id = null): string
    {
        return self::TINTS[$slug] ?? self::FALLBACK_TINTS[($id ?? crc32((string) $slug)) % count(self::FALLBACK_TINTS)];
    }

    public static function money(float|string|null $n): string
    {
        return '$' . number_format((float) $n, 2);
    }

    /** One product card, in the shape of the design's card(). */
    public static function card(Product $p): array
    {
        $price = (float) $p->from_price;
        $was = $p->has_strike ? (float) $p->price : 0.0;
        $root = $p->category?->parent ?? $p->category;
        $wished = in_array($p->id, \App\Models\Wishlist::productIdsForCurrentUser(), true);

        return [
            'id' => $p->id, 'href' => route('product-details', $p->slug), 'name' => $p->name, 'price' => self::money($price),
            'was' => $was ? self::money($was) : '', 'off' => $was ? '−' . round((1 - $price / $was) * 100) . '%' : '', 'hasOff' => (bool) $was,
            'rating' => ($p->reviews_count ?? 0) > 0 ? number_format((float) $p->reviews_avg_rating, 1) : '', 'reviews' => number_format((int) ($p->reviews_count ?? 0)),
            'hasRating' => ($p->reviews_count ?? 0) > 0, 'sold' => \App\Services\SoldCounts::label($p->id),
            'store' => $p->seller?->store_name ?? 'Peyton Ghalib', 'tint' => self::tint($root?->slug, $root?->id), 'sub' => $p->category?->name ?? '',
            'img' => $p->image ? Product::imageUrl($p->image) : null, 'usa' => true, 'wishIcon' => $wished ? '♥' : '♡', 'wishColor' => $wished ? '#DC2626' : '#111827',
        ];
    }

    /** Variables the page frame (header, menu, footer, tab bar) reads, on every page. */
    public static function shell(Request $request): array
    {
        static $memo = [];
        $key = spl_object_id($request);
        if (isset($memo[$key])) {
            return $memo[$key];
        }

        $tree = self::ordered(app(MarketCatalog::class)->tree());
        $cart = app(CartService::class);
        $current = $request->route('slug');
        $user = $request->user();
        $zip = $request->cookie('pg_zip');

        $navCats = $tree->map(function ($c) use ($current, $request) {
            $on = $request->routeIs('category.landing') && $current === $c->slug;

            return ['name' => $c->name, 'href' => route('category.landing', $c->slug), 'bg' => $on ? '#FFF1E8' : 'transparent', 'fg' => $on ? '#C2410C' : '#374151', 'ul' => $on ? 'inset 0 -3px 0 #EA580C' : 'none'];
        })->all();
        $menuCats = $tree->map(fn ($c) => ['name' => $c->name, 'href' => route('category.landing', $c->slug),
            'subs' => $c->children->map(fn ($s) => ['name' => $s->name, 'href' => route('category.landing', $s->slug)])->all()])->all();

        $count = $cart->count();
        $route = $request->route()?->getName();
        $onProduct = $route === 'product-details';
        $tabs = collect([
            ['Home', url('/'), 'home', $request->is('/')], ['Categories', url('/sitemap'), 'grid', $request->is('sitemap') || $route === 'category.landing'],
            ['Deals', url('/category/deals'), 'tag', $request->is('category/deals')], ['Account', $user ? url('/my-account') : route('login'), 'user', $request->is('my-account*')],
            ["Cart ({$count})", route('cart'), 'cart', $route === 'cart'],
        ])->map(fn ($t) => ['label' => $t[0], 'href' => $t[1], 'color' => $t[3] ? '#C2410C' : '#4B5563', 'icon' => self::icon(self::PATHS[$t[2]], $t[3] ? '#C2410C' : '#4B5563')])->all();
        $showTabs = ! $onProduct && $route !== 'checkout';
        $barH = ($showTabs || $onProduct) ? 76 : 0;

        return $memo[$key] = [
            'ic' => self::icons(), 'isDesk' => true, 'isMobile' => false, 'navCats' => $navCats, 'menuCats' => $menuCats,
            'cartCount' => $count, 'searchInput' => (string) $request->query('q', ''),
            'locLabel' => $zip ? e($zip) : 'Set your ZIP', 'locZip' => $zip ?: '', 'locNote' => $zip ? 'Set by you' : 'Add a US delivery ZIP to see delivery dates', 'zipInput' => $zip ?: '',
            'hasLocErr' => false, 'locErr' => '', 'gpsLabel' => 'Use my current location',
            'menuHello' => 'Hello, ' . ($user ? explode(' ', trim($user->name))[0] : 'Sign in'), 'menuUserHref' => $user ? url('/my-account') : route('login'),
            'footCols' => [
                ['h' => 'Company', 'links' => [['t' => 'About us', 'href' => url('/about')], ['t' => 'Contact us', 'href' => route('contact.show')], ['t' => 'FAQ', 'href' => url('/faq')], ['t' => 'Accessibility', 'href' => url('/accessibility')]]],
                ['h' => 'Customer service', 'links' => [['t' => 'Track your order', 'href' => route('track-order')], ['t' => 'Shipping policy', 'href' => route('shipping-policy')],
                    ['t' => 'Return policy', 'href' => route('return-policy')], ['t' => 'Buyer Protection', 'href' => url('/buyer-protection')]]],
                ['h' => 'Shop with us', 'links' => [['t' => "Today's deals", 'href' => url('/category/deals')], ['t' => 'All products', 'href' => route('shop')], ['t' => 'Sitemap', 'href' => url('/sitemap')]]],
                ['h' => 'Sell with us', 'links' => [['t' => 'Sell on Peyton Ghalib', 'href' => route('seller.landing')], ['t' => 'Seller pricing: $19.99/mo', 'href' => route('seller.pricing')], ['t' => 'Start selling', 'href' => route('seller.signup')], ['t' => 'Seller Agreement', 'href' => url('/seller-agreement')]]],
            ],
            'legalLinks' => [['t' => 'Terms of Service', 'href' => url('/terms-and-conditions')], ['t' => 'Privacy Policy', 'href' => route('privacy-policy')], ['t' => 'Cookie Policy', 'href' => url('/cookies')], ['t' => 'Do Not Sell or Share', 'href' => url('/do-not-sell')]],
            'socials' => [['t' => 'Instagram', 'href' => 'https://www.instagram.com/peytonghalibexpress/'], ['t' => 'Facebook', 'href' => 'https://www.facebook.com/profile.php?id=61590448962752'], ['t' => 'TikTok', 'href' => 'https://www.tiktok.com/@peytonghalib'], ['t' => 'Pinterest', 'href' => 'https://www.pinterest.com/peytonexpress/']],
            'payments' => ['VISA', 'Mastercard', 'AMEX', 'Discover', 'PayPal', 'Apple Pay', 'Google Pay'],
            'subscribed' => false, 'notSubscribed' => true, 'hasToast' => false, 'toast' => '', 'toastBottom' => '84px',
            'chatOpen' => false, 'chatLabel' => 'Chat with us', 'chatBottom' => '16px', 'chatPanelBottom' => '80px',
            'supportMsgs' => [['t' => 'Hi! How can we help? Pick a topic or email info@peytonghalib.com.', 'align' => 'flex-start', 'bg' => '#FFFFFF', 'fg' => '#111827']],
            'quickQs' => [['t' => 'Where is my order?'], ['t' => 'Start a return'], ['t' => 'Talk to a person']], 'supportInput' => '',
            'tabs' => $tabs, 'showTabs' => $showTabs, 'showBuyBar' => $onProduct,
        ];
    }

    /** Variables for the home page. */
    public static function home(): array
    {
        $tree = self::ordered(app(MarketCatalog::class)->tree());
        $with = fn () => Product::visible()->with('seller:id,store_name,slug,level', 'variants', 'category.parent')->withAvg('reviews', 'rating')->withCount('reviews');
        $ic = self::icons();

        $deals = $with()->whereRaw('products.sale_price > 0 AND products.sale_price < products.price')->orderByRaw('(products.price - products.sale_price) / products.price DESC')->limit(10)->get();
        $recent = collect(session('recent_ids', []))->take(12)->all();
        $recentItems = $recent ? $with()->whereIn('products.id', $recent)->get()->sortBy(fn ($p) => array_search($p->id, $recent))->values() : collect();

        $left = max(0, now()->diffInSeconds(now()->endOfDay(), false));
        $pad = fn (int $n) => str_pad((string) $n, 2, '0', STR_PAD_LEFT);

        $stores = Seller::live()->withCount(['products as shop_count' => fn ($q) => $q->visible()])->orderByDesc('delivered_sales')->orderBy('id')->get()
            ->filter(fn ($s) => $s->shop_count > 0)->take(6);

        $tiles = collect($deals->count() >= 4 ? $deals : $with()->latest('products.id')->limit(4)->get())->take(4)->map(fn ($p) => ['href' => route('product-details', $p->slug),
            'tint' => self::tint(($p->category?->parent ?? $p->category)?->slug), 'sub' => $p->category?->name ?? '', 'price' => self::money($p->from_price), 'img' => $p->image ? Product::imageUrl($p->image) : null])->all();
        $promos = array_map(fn ($c) => $c + ['hasKicker' => $c['kicker'] !== '', 'hasCta' => $c['cta'] !== '', 'isGrid' => ! empty($c['grid']), 'isImage' => empty($c['grid']), 'tiles' => ! empty($c['grid']) ? $tiles : []], self::promos());

        return [
            'promos' => $promos, 'catTiles' => $tree->map(fn ($c) => ['name' => $c->name, 'href' => route('category.landing', $c->slug), 'tint' => self::tint($c->slug, $c->id), 'initial' => mb_substr($c->name, 0, 1)])->all(),
            'cd' => [$pad(intdiv($left, 3600)), $pad(intdiv($left % 3600, 60)), $pad($left % 60)], 'deals' => $deals->map(fn ($p) => self::card($p))->all(),
            'trust' => [
                ['icon' => $ic['shield'], 'h' => 'Buyer Protection', 'p' => "Full refund if an order doesn't arrive or isn't as described."],
                ['icon' => $ic['badge'], 'h' => 'Verified US sellers', 'p' => 'Every store passes business and identity checks.'],
                ['icon' => $ic['ret'], 'h' => 'Easy returns', 'p' => 'Start a return from your orders in a few taps.'],
                ['icon' => $ic['lock'], 'h' => 'Secure checkout', 'p' => 'Payments encrypted and processed by Stripe.'],
            ],
            'recommended' => $with()->latest('products.id')->limit(25)->get()->map(fn ($p) => self::card($p))->all(), 'canMore' => true,
            'storeCards' => $stores->map(fn ($s) => ['name' => $s->store_name, 'href' => route('store.show', $s->slug), 'city' => trim("{$s->city}, {$s->state}", ', '),
                'rating' => '', 'followers' => $s->shop_count . ' ' . str('product')->plural($s->shop_count), 'level' => ucfirst($s->level) . ' Seller',
                'lvBg' => ['new' => '#F3F4F6', 'rising' => '#DBEAFE', 'top' => '#EDE9FE', 'elite' => '#FEF3C7'][$s->level] ?? '#F3F4F6',
                'lvFg' => ['new' => '#374151', 'rising' => '#1E40AF', 'top' => '#5B21B6', 'elite' => '#92400E'][$s->level] ?? '#374151',
                'initial' => mb_substr($s->store_name, 0, 1), 'tint' => self::tint(null, $s->id), 'sale' => '', 'hasSale' => false, 'fLabel' => 'Visit', 'fBg' => '#FFFFFF', 'fFg' => '#111827'])->all(),
            'recentItems' => $recentItems->map(fn ($p) => self::card($p))->all(),
        ];
    }

    private static function promos(): array
    {
        return [
            ['kicker' => 'Get trick-or-treat ready', 'title' => 'Free shipping on Halloween finds', 'cta' => 'Shop Halloween', 'href' => url('/category/seasonal-holiday'), 'bg' => '#0057FF', 'fg' => '#FFFFFF', 'cb' => '#FFD23F', 'cf' => '#111827'],
            ['kicker' => '', 'title' => 'Costumes & décor under $50', 'cta' => '', 'href' => url('/category/seasonal-holiday'), 'bg' => '#7EE0A1', 'fg' => '#111827'],
            ['kicker' => '', 'title' => 'Shop holiday gift ideas', 'cta' => '', 'href' => url('/category/seasonal-holiday'), 'bg' => '#E11D2E', 'fg' => '#FFFFFF'],
            ['kicker' => 'Low prices every day', 'title' => 'Fall finds under $40', 'cta' => '', 'href' => url('/category/deals'), 'bg' => '#8B3FD9', 'fg' => '#FFFFFF', 'grid' => true],
            ['kicker' => 'For US businesses', 'title' => 'Sell with 0% commission', 'cta' => 'Start selling', 'href' => route('seller.landing'), 'bg' => '#EA580C', 'fg' => '#111827', 'cb' => '#111827', 'cf' => '#FFFFFF'],
            ['kicker' => 'Earn on every order', 'title' => '1 point per $1. 100 points = $1 off', 'cta' => 'See rewards', 'href' => url('/my-account'), 'bg' => '#0F2A5F', 'fg' => '#FFFFFF', 'cb' => '#FFFFFF', 'cf' => '#0F2A5F'],
        ];
    }
}
