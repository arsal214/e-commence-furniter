<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $sliders     = Slider::active()->get();
        $categories  = Category::where('is_active', true)->withCount('products')->get();
        $newProducts = Product::where('is_active', true)
                              ->with('category')
                              ->withAvg('reviews', 'rating')
                              ->withCount('reviews')
                              ->latest()
                              ->take(20)
                              ->get()
                              ->shuffle()
                              ->take(8);
        $bestSellers = Product::where('is_active', true)
                              ->where('is_best_seller', true)
                              ->with('category')
                              ->withAvg('reviews', 'rating')
                              ->withCount('reviews')
                              ->latest()
                              ->take(8)
                              ->get();
        $featuredProducts = Product::where('is_active', true)
                                   ->where('is_featured', true)
                                   ->with('category')
                                   ->latest()
                                   ->take(6)
                                   ->get();
        $flashDeal = FlashDeal::current();
        return view('index', compact('sliders', 'featuredProducts', 'newProducts', 'bestSellers', 'categories', 'flashDeal'));
    }

    public function about()
    {
        // Real reviews with something to say — no fabricated testimonials.
        $reviews = Review::with(['user:id,name', 'product:id,name,slug'])
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->where('rating', '>=', 4)
            ->latest()
            ->take(6)
            ->get();

        // A review is only "verified" if that customer actually bought that product.
        // Reviewing is open to any signed-in user, so we can't assume it.
        $verified = [];

        if ($reviews->isNotEmpty()) {
            $verified = OrderItem::query()
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('orders.user_id', $reviews->pluck('user_id'))
                ->whereIn('order_items.product_id', $reviews->pluck('product_id'))
                ->get(['orders.user_id', 'order_items.product_id'])
                ->map(fn ($row) => $row->user_id . '-' . $row->product_id)
                ->flip()
                ->toArray();
        }

        return view('about', [
            // Counts are stated as fact on the page, so read them, don't hardcode them.
            'productCount'  => Product::where('is_active', true)->count(),
            'categoryCount' => Category::where('is_active', true)->count(),
            'reviews'       => $reviews,
            'verified'      => $verified,
            'ratingAvg'     => round((float) Review::avg('rating'), 1),
            'ratingCount'   => Review::count(),
        ]);
    }

    public function faq()
    {
        // Real category names, so the "what do you sell" answer can't go stale.
        return view('faq', [
            'categoryNames' => Category::where('is_active', true)
                                       ->orderBy('name')
                                       ->pluck('name')
                                       ->all(),
        ]);
    }

    public function termsAndConditions()
    {
        return view('terms-and-conditions');  
    }

    public function error()
    {
        return view('error');  
    }

    public function myAccount()
    {
        $user = Auth::user();

        // items.product supplies the order thumbnails on the dashboard
        $orders = Order::where('user_id', $user->id)->with('items.product')->latest()->get();

        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        return view('my-account', compact('user', 'orders', 'wishlistCount'));
    }

    public function editAccount()
    {
        $user = Auth::user();
        return view('edit-account', compact('user'));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'current_password'      => 'nullable|string',
            'password'              => 'nullable|string|min:6|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Account updated successfully.');
    }

    public function orderHistory()
    {
        $user = Auth::user();

        // items.product supplies the thumbnails and product links
        $orders = Order::where('user_id', $user->id)->with('items.product')->latest()->get();

        return view('order-history', compact('user', 'orders'));
    }

    public function wishlist()
    {
        $user = Auth::user();

        $wishlistItems = Wishlist::where('user_id', $user->id)
                                 ->with('product')
                                 ->latest()
                                 ->get();

        return view('wishlist', compact('user', 'wishlistItems'));
    }
    
    public function forgerPassword()
    {
        return view('forger-password');  
    }

    public function comingSoon()
    {
        return view('coming-soon');  
    }

    public function thankYou()
    {
        $order = null;

        if ($tracking = session('recent_order')) {
            $order = Order::with('items.product')
                ->where('tracking_number', $tracking)
                ->first();
        }

        // No order in session (direct visit, expired session) still renders —
        // the view falls back to a generic thank-you with a way to find the order.
        return view('thank-you', compact('order'));
    }

    public function trackOrder(Request $request)
    {
        $order = null;

        if ($request->filled('tracking')) {
            $order = Order::with('items')
                ->where('tracking_number', strtoupper(trim($request->tracking)))
                ->first();
        }

        return view('track-order', compact('order'));
    }

    public function shippingMethod()
    {
        return view('shipping-method');  
    }

    public function paymentMethod()
    {
        return view('payment-method');  
    }

    public function invoice()
    {
        return view('invoice');  
    }

    public function paymentConfirmation()
    {
        return view('payment-confirmation');  
    }

    public function paymentSuccess()
    {
        return view('payment-success');  
    }
    
    public function paymentFailure()
    {
        return view('payment-failure');  
    }

    /** Sort options offered on the shop page. Keys are whitelisted against ?sort=. */
    public const SHOP_SORTS = [
        'latest'     => 'Newest',
        'price_low'  => 'Price: Low to High',
        'price_high' => 'Price: High to Low',
        'rating'     => 'Top Rated',
        'name'       => 'Name: A–Z',
    ];

    /** Price facets. Bounds are inclusive; null means open-ended. */
    public const SHOP_PRICE_BUCKETS = [
        'under-50'  => ['label' => 'Under $50',      'min' => null, 'max' => 49.99],
        '50-100'    => ['label' => '$50 – $100',     'min' => 50,   'max' => 100],
        '100-200'   => ['label' => '$100 – $200',    'min' => 100.01, 'max' => 200],
        '200-500'   => ['label' => '$200 – $500',    'min' => 200.01, 'max' => 500],
        'over-500'  => ['label' => '$500 & above',   'min' => 500.01, 'max' => null],
    ];

    /** Mirrors Product::getEffectivePriceAttribute() in SQL, so filtering and sorting
     *  use the same price the customer is actually charged. */
    private const EFFECTIVE_PRICE = 'COALESCE(NULLIF(sale_price, 0), price)';

    /**
     * Base product query with every active filter applied.
     *
     * $except skips one facet, which is what makes the sidebar counts behave: the
     * count next to "Chairs" must reflect the other filters but not the category
     * filter itself, or checking one category would zero out all the others.
     */
    private function shopQuery(array $f, ?string $except = null)
    {
        $eff = self::EFFECTIVE_PRICE;

        return Product::query()
            ->where('is_active', true)
            ->when($except !== 'search' && $f['search'] !== '', fn($q) => $q->where(function ($w) use ($f) {
                $w->where('name', 'like', '%' . $f['search'] . '%')
                  ->orWhere('description', 'like', '%' . $f['search'] . '%');
            }))
            ->when($except !== 'categories' && $f['categories'], fn($q) => $q->whereHas(
                'category', fn($c) => $c->whereIn('slug', $f['categories'])
            ))
            ->when($except !== 'price' && $f['price'], fn($q) => $q->where(function ($w) use ($f, $eff) {
                foreach ($f['price'] as $key) {
                    $b = self::SHOP_PRICE_BUCKETS[$key] ?? null;
                    if (! $b) continue;
                    $w->orWhere(function ($x) use ($b, $eff) {          // buckets OR together
                        if ($b['min'] !== null) $x->whereRaw("{$eff} >= ?", [$b['min']]);
                        if ($b['max'] !== null) $x->whereRaw("{$eff} <= ?", [$b['max']]);
                    });
                }
            }));
    }

    public function shopV1(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $validSlugs = $categories->pluck('slug')->all();

        // Every facet is multi-select, so each arrives as an array. Values are
        // intersected against what actually exists — never trusted straight from the URL.
        $f = [
            'search'     => trim((string) $request->get('search', '')),
            'categories' => array_values(array_intersect((array) $request->get('category', []), $validSlugs)),
            'price'      => array_values(array_intersect((array) $request->get('price', []), array_keys(self::SHOP_PRICE_BUCKETS))),
        ];

        $sort = array_key_exists($request->get('sort'), self::SHOP_SORTS) ? $request->get('sort') : 'latest';
        $eff  = self::EFFECTIVE_PRICE;

        $products = $this->shopQuery($f)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($sort === 'price_low',  fn($q) => $q->orderByRaw("{$eff} ASC"))
            ->when($sort === 'price_high', fn($q) => $q->orderByRaw("{$eff} DESC"))
            ->when($sort === 'rating',     fn($q) => $q->orderByDesc('reviews_avg_rating'))
            ->when($sort === 'name',       fn($q) => $q->orderBy('name'))
            ->when($sort === 'latest',     fn($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        // Counts per facet option. Small catalogue, so a count query per option is fine;
        // this would need rethinking (a single GROUP BY) at thousands of products.
        $categoryCounts = [];
        foreach ($categories as $cat) {
            $categoryCounts[$cat->slug] = (clone $this->shopQuery($f, 'categories'))
                ->whereHas('category', fn($c) => $c->where('slug', $cat->slug))->count();
        }

        $priceCounts = [];
        foreach (self::SHOP_PRICE_BUCKETS as $key => $b) {
            $priceCounts[$key] = (clone $this->shopQuery($f, 'price'))
                ->where(function ($x) use ($b, $eff) {
                    if ($b['min'] !== null) $x->whereRaw("{$eff} >= ?", [$b['min']]);
                    if ($b['max'] !== null) $x->whereRaw("{$eff} <= ?", [$b['max']]);
                })->count();
        }

        return view('shop', [
            'products'       => $products,
            'categories'     => $categories,
            'filters'        => $f,
            'sort'           => $sort,
            'sortOptions'    => self::SHOP_SORTS,
            'priceBuckets'   => self::SHOP_PRICE_BUCKETS,
            'categoryCounts' => $categoryCounts,
            'priceCounts'    => $priceCounts,
        ]);
    }

    /**
     * Quick-view panel for a single product. Returns a bare partial (no layout) that
     * the shop grid injects into the modal — the template's own .quick-view popup is
     * a static demo with hardcoded images, so it can't be reused here.
     */
    public function quickView(string $slug)
    {
        $product = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('includes.Shop.quick-view', compact('product'));
    }

    public function categories()
    {
        $categories = Category::where('is_active', true)->withCount('products')->orderBy('name')->get();
        return view('categories', compact('categories'));
    }

    public function categoryLanding($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $baseQuery = Product::where('category_id', $category->id)->where('is_active', true);

        $totalCount = (clone $baseQuery)->count();
        $priceMin   = (clone $baseQuery)->min(\DB::raw('COALESCE(sale_price, price)'));
        $priceMax   = (clone $baseQuery)->max(\DB::raw('COALESCE(sale_price, price)'));

        $featuredProduct = (clone $baseQuery)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->first();

        $products = (clone $baseQuery)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($featuredProduct, fn($q) => $q->where('id', '!=', $featuredProduct->id))
            ->latest()
            ->take(8)
            ->get();

        $relatedCategories = Category::where('is_active', true)
                                     ->where('id', '!=', $category->id)
                                     ->withCount('products')
                                     ->inRandomOrder()
                                     ->take(4)
                                     ->get();

        return view('category-landing', compact(
            'category', 'products', 'relatedCategories',
            'totalCount', 'priceMin', 'priceMax', 'featuredProduct'
        ));
    }

    public function returnPolicy()   { return view('return-policy'); }
    public function refundPolicy()   { return view('refund-policy'); }
    public function shippingPolicy() { return view('shipping-policy'); }
    public function privacyPolicy()  { return view('privacy-policy'); }

    public function productCategory()
    {
        $products   = Product::where('is_active', true)->latest()->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('product-category', compact('products', 'categories'));
    }
    
    public function shortCode()
    {
        return view('short-code');  
    }

    public function contact()
    {
        return view('contact');  
    }
}
