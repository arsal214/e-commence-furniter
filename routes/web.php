<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\SitemapController;

// ──────────────────────────────────────────────
//  Auth routes
// ──────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [HomeController::class, 'forgerPassword'])->name('password.request');
Route::get('/forger-password', fn() => redirect('/forgot-password', 301));

// ──────────────────────────────────────────────
//  Protected customer routes
// ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/my-profile',    [HomeController::class, 'myProfile']);
    Route::get('/my-account',    [HomeController::class, 'myAccount']);
    Route::get('/edit-account',  [HomeController::class, 'editAccount']);
    Route::post('/edit-account', [HomeController::class, 'updateAccount'])->name('account.update');
    Route::get('/order-history', [HomeController::class, 'orderHistory']);
    Route::get('/wishlist',          [HomeController::class, 'wishlist']);
    Route::post('/wishlist/toggle',  [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/remove',  [WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::get('/checkout',         [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout',        [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe-success');

    Route::post('/product/{slug}/review', [ReviewController::class, 'store'])->name('product.review.store');
});

// ──────────────────────────────────────────────
//  Admin routes
// ──────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('categories', Admin\CategoryController::class)->names('admin.categories');
    Route::get('products/export', [Admin\ProductController::class, 'export'])->name('admin.products.export');
    Route::get('products/import',  [Admin\ProductImportController::class, 'index'])->name('admin.products.import');
    Route::post('products/import', [Admin\ProductImportController::class, 'store'])->name('admin.products.import.store');
    Route::resource('products',   Admin\ProductController::class)->names('admin.products');
    Route::resource('orders',     Admin\OrderController::class)->names('admin.orders')->only(['index', 'show', 'update']);
    Route::resource('sliders',    Admin\SliderController::class)->names('admin.sliders')->except(['show']);
});

// ──────────────────────────────────────────────
//  Public frontend routes
// ──────────────────────────────────────────────
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', [HomeController::class, 'index']);

Route::get('/about',                [HomeController::class, 'about']);
Route::get('/faq',                  [HomeController::class, 'faq']);
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions']);

Route::get('/error', [HomeController::class, 'error']);

Route::get('/coming-soon', [HomeController::class, 'comingSoon']);
Route::get('/thank-you',   [HomeController::class, 'thankYou'])->name('thank-you');
Route::get('/track-order', [HomeController::class, 'trackOrder'])->name('track-order');

Route::get('/shipping-method',       [HomeController::class, 'shippingMethod']);
Route::get('/payment-method',        [HomeController::class, 'paymentMethod']);
Route::get('/invoice',               [HomeController::class, 'invoice']);
Route::get('/payment-confirmation',  [HomeController::class, 'paymentConfirmation']);
Route::get('/payment-success',       [HomeController::class, 'paymentSuccess']);
Route::get('/payment-failure',       [HomeController::class, 'paymentFailure']);

Route::get('/shop',             [HomeController::class, 'shopV1'])->name('shop');
Route::get('/shop-v1',          fn() => redirect('/shop', 301));
Route::get('/categories',       [HomeController::class, 'categories']);
Route::get('/category/{slug}',  [HomeController::class, 'categoryLanding'])->name('category.landing');
Route::get('/product-category', [HomeController::class, 'productCategory']);

Route::get('/return-policy',   [HomeController::class, 'returnPolicy'])->name('return-policy');
Route::get('/refund-policy',   [HomeController::class, 'refundPolicy'])->name('refund-policy');
Route::get('/shipping-policy', [HomeController::class, 'shippingPolicy'])->name('shipping-policy');
Route::get('/privacy-policy',  [HomeController::class, 'privacyPolicy'])->name('privacy-policy');

Route::get('/product-details',         [HomeController::class, 'productDetails']);
Route::get('/product-details/{slug}',  [ProductController::class, 'show'])->name('product-details');

Route::get('/cart',          [CartController::class, 'index'])->name('cart');
Route::post('/cart/add',     [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update',  [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove',  [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',   [CartController::class, 'clear'])->name('cart.clear');


Route::get('/short-code', [HomeController::class, 'shortCode']);

Route::get('/contact',  [HomeController::class, 'contact']);
Route::get('/contactus',  [ContactController::class, 'show'])->name('contact.show');
Route::post('/contactus', [ContactController::class, 'send'])->name('contact.send');
Route::post('/newsletter', [ContactController::class, 'newsletter'])->name('newsletter.subscribe');
