<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogV2Controller;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioV2Controller;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin;

// ──────────────────────────────────────────────
//  Auth routes
// ──────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::get('/forger-password', [HomeController::class, 'forgerPassword']);

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
});

// ──────────────────────────────────────────────
//  Admin routes
// ──────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('categories', Admin\CategoryController::class)->names('admin.categories');
    Route::resource('products',   Admin\ProductController::class)->names('admin.products');
    Route::resource('orders',     Admin\OrderController::class)->names('admin.orders')->only(['index', 'show', 'update']);
    Route::resource('sliders',    Admin\SliderController::class)->names('admin.sliders')->except(['show']);
});

// ──────────────────────────────────────────────
//  Public frontend routes
// ──────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index']);

Route::get('/about',                [HomeController::class, 'about']);
Route::get('/pricing',              [HomeController::class, 'pricing']);
Route::get('/team',                 [HomeController::class, 'team']);
Route::get('/our-clients',          [HomeController::class, 'ourClients']);
Route::get('/faq',                  [HomeController::class, 'faq']);
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions']);

Route::get('/portfolio-v1', [HomeController::class, 'portfolioV1']);
Route::get('/portfolio-v2', [HomeController::class, 'portfolioV2']);
Route::get('/portfolio-v3', [HomeController::class, 'portfolioV3']);

Route::get('/portfolio-details-v1',         [HomeController::class, 'portfolioDetailsV1']);
Route::get('/portfolio-details-v1/{title}', [PortfolioController::class, 'show'])->name('portfolio-details-v1');
Route::get('/portfolio-details-v2',         [HomeController::class, 'portfolioDetailsV2']);
Route::get('/portfolio-details-v2/{title}', [PortfolioV2Controller::class, 'show'])->name('portfolio-details-v2');

Route::get('/error', [HomeController::class, 'error']);

Route::get('/coming-soon', [HomeController::class, 'comingSoon']);
Route::get('/thank-you',   [HomeController::class, 'thankYou'])->name('thank-you');

Route::get('/shipping-method',       [HomeController::class, 'shippingMethod']);
Route::get('/payment-method',        [HomeController::class, 'paymentMethod']);
Route::get('/invoice',               [HomeController::class, 'invoice']);
Route::get('/payment-confirmation',  [HomeController::class, 'paymentConfirmation']);
Route::get('/payment-success',       [HomeController::class, 'paymentSuccess']);
Route::get('/payment-failure',       [HomeController::class, 'paymentFailure']);

Route::get('/shop-v1',          [HomeController::class, 'shopV1']);
Route::get('/product-category', [HomeController::class, 'productCategory']);

Route::get('/product-details',         [HomeController::class, 'productDetails']);
Route::get('/product-details/{slug}',  [ProductController::class, 'show'])->name('product-details');

Route::get('/cart',          [CartController::class, 'index'])->name('cart');
Route::post('/cart/add',     [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update',  [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove',  [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',   [CartController::class, 'clear'])->name('cart.clear');

Route::get('/blog-v1',  [HomeController::class, 'blogV1']);
Route::get('/blog-v2',  [HomeController::class, 'blogV2']);

Route::get('/blog-details-v1',         [HomeController::class, 'blogDetailsV1']);
Route::get('/blog-details-v1/{title}', [BlogController::class, 'show'])->name('blog-details-v1');
Route::get('/blog-details-v2',         [HomeController::class, 'blogDetailsV2']);
Route::get('/blog-details-v2/{title}', [BlogV2Controller::class, 'show'])->name('blog-details-v2');
Route::get('/blog-details-v3',         [HomeController::class, 'blogDetailsV3']);
Route::get('/blog-tag',                [HomeController::class, 'blogTag']);

Route::get('/short-code', [HomeController::class, 'shortCode']);

Route::get('/contact',  [HomeController::class, 'contact']);
Route::get('/contactus',  [ContactController::class, 'show'])->name('contact.show');
Route::post('/contactus', [ContactController::class, 'send'])->name('contact.send');
