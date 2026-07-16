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

Route::get('/forgot-password',  [HomeController::class, 'forgerPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}',  [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',         [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/forger-password', fn() => redirect('/forgot-password', 301));

// ──────────────────────────────────────────────
//  Protected customer routes
// ──────────────────────────────────────────────
Route::middleware(['auth', 'must-reset'])->group(function () {
    // First-login password setup for guest-checkout accounts (flow B). Kept inside
    // the must-reset group; the middleware exempts these routes so the user can finish.
    Route::get('/account/set-password',  [AuthController::class, 'showSetPassword'])->name('account.set-password');
    Route::post('/account/set-password', [AuthController::class, 'setPassword']);

    Route::get('/my-profile',    fn() => redirect('/my-account'));
    Route::get('/my-account',    [HomeController::class, 'myAccount']);
    Route::get('/edit-account',  [HomeController::class, 'editAccount']);
    Route::post('/edit-account', [HomeController::class, 'updateAccount'])->name('account.update');
    Route::get('/order-history', [HomeController::class, 'orderHistory']);
    Route::get('/wishlist',          [HomeController::class, 'wishlist']);
    Route::post('/wishlist/toggle',  [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/remove',  [WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::post('/product/{slug}/review', [ReviewController::class, 'store'])->name('product.review.store');
});

// Checkout is open to guests. Placing an order auto-creates an account (flow B).
Route::get('/checkout',         [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout',        [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe-success');

// ──────────────────────────────────────────────
//  Admin routes
// ──────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('categories', Admin\CategoryController::class)->names('admin.categories')->except(['show']);
    Route::get('products/export', [Admin\ProductController::class, 'export'])->name('admin.products.export');
    Route::get('products/import',  [Admin\ProductImportController::class, 'index'])->name('admin.products.import');
    Route::post('products/import', [Admin\ProductImportController::class, 'store'])->name('admin.products.import.store');
    Route::resource('products',   Admin\ProductController::class)->names('admin.products')->except(['show']);
    Route::resource('orders',     Admin\OrderController::class)->names('admin.orders')->only(['index', 'show', 'update']);
    Route::resource('reviews',    Admin\ReviewController::class)->names('admin.reviews')->except(['show']);
    Route::resource('sliders',    Admin\SliderController::class)->names('admin.sliders')->except(['show']);
    Route::get('flash-deal',     [Admin\FlashDealController::class, 'index'])->name('admin.flash-deal.index');
    Route::put('flash-deal',     [Admin\FlashDealController::class, 'update'])->name('admin.flash-deal.update');
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

Route::redirect('/shipping-method', '/shipping-policy', 301); // SEO: duplicate of shipping-policy
Route::get('/payment-method',        [HomeController::class, 'paymentMethod']);
Route::get('/invoice',               [HomeController::class, 'invoice']);
Route::get('/payment-confirmation',  [HomeController::class, 'paymentConfirmation']);
Route::get('/payment-success',       [HomeController::class, 'paymentSuccess']);
Route::get('/payment-failure',       [HomeController::class, 'paymentFailure']);

Route::get('/shop',             [HomeController::class, 'shopV1'])->name('shop');
Route::get('/shop-v1',          fn() => redirect('/shop', 301));
Route::get('/quick-view/{slug}', [HomeController::class, 'quickView'])->name('quick-view');
Route::get('/categories',       [HomeController::class, 'categories']);
Route::redirect('/category/beauty-produts', '/category/beauty-products', 301); // SEO: typo fix (must precede dynamic route)
Route::get('/category/{slug}',  [HomeController::class, 'categoryLanding'])->name('category.landing');
Route::redirect('/product-category', '/shop', 301); // SEO: duplicate of /shop

Route::get('/return-policy',   [HomeController::class, 'returnPolicy'])->name('return-policy');
Route::get('/refund-policy',   [HomeController::class, 'refundPolicy'])->name('refund-policy');
Route::get('/shipping-policy', [HomeController::class, 'shippingPolicy'])->name('shipping-policy');
Route::get('/privacy-policy',  [HomeController::class, 'privacyPolicy'])->name('privacy-policy');

Route::get('/search/suggestions',      [ProductController::class, 'suggestions'])->name('search.suggestions');
Route::redirect('/product-details', '/', 301);
Route::get('/product-details/{slug}',  [ProductController::class, 'show'])->name('product-details');

Route::get('/cart',          [CartController::class, 'index'])->name('cart');
Route::post('/cart/add',     [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update',  [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove',  [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',   [CartController::class, 'clear'])->name('cart.clear');


Route::get('/short-code', [HomeController::class, 'shortCode']);

Route::get('/contact',  [HomeController::class, 'contact'])->name('contact.show');
Route::redirect('/contactus', '/contact', 301);
Route::post('/contactus', [ContactController::class, 'send'])->name('contact.send');
Route::post('/newsletter', [ContactController::class, 'newsletter'])->name('newsletter.subscribe');
