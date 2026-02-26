<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $sliders          = Slider::active()->get();
        $featuredProducts = Product::where('is_active', true)->where('is_featured', true)->latest()->take(6)->get();
        $newProducts      = Product::where('is_active', true)->latest()->take(4)->get();
        $categories       = Category::where('is_active', true)->withCount('products')->get();
        return view('index', compact('sliders', 'featuredProducts', 'newProducts', 'categories'));
    }

    public function indexV2()
    {
        return view('index-v2');  
    }

    public function indexV3()
    {
        return view('index-v3');  
    }

    public function indexV4()
    {
        return view('index-v4');  
    }

    public function indexV5()
    {
        return view('index-v5');  
    }

    public function indexV6()
    {
        return view('index-v6');  
    }

    public function about()
    {
        return view('about');  
    }

    public function pricing()
    {
        return view('pricing');  
    }

    public function team()
    {
        return view('team');  
    }

    public function ourClients()
    {
        return view('our-clients');  
    }

    public function faq()
    {
        return view('faq');  
    }

    public function termsAndConditions()
    {
        return view('terms-and-conditions');  
    }

    public function portfolioV1()
    {
        return view('portfolio-v1');  
    }

    public function portfolioV2()
    {
        return view('portfolio-v2');  
    }

    public function portfolioV3()
    {
        return view('portfolio-v3');  
    }

    public function portfolioDetailsV1()
    {
        return view('portfolio-details-v1');  
    }

    public function portfolioDetailsV2()
    {
        return view('portfolio-details-v2');  
    }

    public function error()
    {
        return view('error');  
    }

    public function myProfile()
    {
        return view('my-profile');  
    }

    public function myAccount()
    {
        $user   = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('items')->latest()->get();
        return view('my-account', compact('user', 'orders'));
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
        $user   = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('items')->latest()->get();
        return view('order-history', compact('user', 'orders'));
    }

    public function wishlist()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
                                 ->with('product')
                                 ->latest()
                                 ->get();
        return view('wishlist', compact('wishlistItems'));
    }
    
    public function login()
    {
        return view('login');  
    }

    public function register()
    {
        return view('register');  
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

    public function shopV1()
    {
        $activeCategory = request('category');
        $products = Product::with('category')
            ->where('is_active', true)
            ->when($activeCategory, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('slug', $activeCategory)))
            ->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('shop-v1', compact('products', 'categories', 'activeCategory'));
    }

    public function shopV2()
    {
        return view('shop-v2');  
    }

    public function shopV3()
    {
        return view('shop-v3');  
    }

    public function shopV4()
    {
        return view('shop-v4');  
    }

    public function productCategory()
    {
        $products   = Product::where('is_active', true)->latest()->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('product-category', compact('products', 'categories'));
    }
    
    public function productDetails()
    {
        return view('product-details');  
    }

    public function cart()
    {
        return view('cart');  
    }

    public function checkout()
    {
        return view('checkout');  
    }

    public function blogV1()
    {
        return view('blog-v1');  
    }

    public function blogV2()
    {
        return view('blog-v2');  
    }

    public function blogDetailsV1()
    {
        return view('blog-details-v1');  
    }

    public function blogDetailsV2()
    {
        return view('blog-details-v2');  
    }

    public function blogDetailsV3()
    {
        return view('blog-details-v3');  
    }

    public function blogTag()
    {
        return view('blog-tag');  
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
