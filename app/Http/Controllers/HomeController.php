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
        return view('thank-you');
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

    public function shopV1(Request $request)
    {
        $activeCategory = $request->get('category');
        $searchQuery    = trim($request->get('search', ''));
        $minPrice       = (float) $request->get('min_price', 0);
        $maxPrice       = (float) $request->get('max_price', 9999);

        $products = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('is_active', true)
            ->when($activeCategory, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('slug', $activeCategory)))
            ->when($searchQuery, fn($q) => $q->where(function ($q2) use ($searchQuery) {
                $q2->where('name', 'like', '%' . $searchQuery . '%')
                   ->orWhere('description', 'like', '%' . $searchQuery . '%');
            }))
            ->when($minPrice > 0, fn($q) => $q->where(function ($q2) use ($minPrice) {
                $q2->where('sale_price', '>=', $minPrice)
                   ->orWhere(function ($q3) use ($minPrice) {
                       $q3->whereNull('sale_price')->where('price', '>=', $minPrice);
                   });
            }))
            ->when($maxPrice < 9999, fn($q) => $q->where(function ($q2) use ($maxPrice) {
                $q2->where('sale_price', '<=', $maxPrice)
                   ->orWhere(function ($q3) use ($maxPrice) {
                       $q3->whereNull('sale_price')->where('price', '<=', $maxPrice);
                   });
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('shop', compact('products', 'categories', 'activeCategory', 'searchQuery', 'minPrice', 'maxPrice'));
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
