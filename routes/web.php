<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogV2Controller;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioV2Controller;
use App\Http\Controllers\ContactController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/index-v2', [HomeController::class, 'indexV2']);
Route::get('/index-v3', [HomeController::class, 'indexV3']);
Route::get('/index-v4', [HomeController::class, 'indexV4']);
Route::get('/index-v5', [HomeController::class, 'indexV5']);
Route::get('/index-v6', [HomeController::class, 'indexV6']);

Route::get('/about', [HomeController::class, 'about']);
Route::get('/pricing', [HomeController::class, 'pricing']);
Route::get('/team', [HomeController::class, 'team']);
Route::get('/our-clients', [HomeController::class, 'ourClients']);
Route::get('/faq', [HomeController::class, 'faq']);
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions']);

Route::get('/portfolio-v1', [HomeController::class, 'portfolioV1']);
Route::get('/portfolio-v2', [HomeController::class, 'portfolioV2']);
Route::get('/portfolio-v3', [HomeController::class, 'portfolioV3']);

Route::get('/portfolio-details-v1', [HomeController::class, 'portfolioDetailsV1']);
Route::get('/portfolio-details-v1/{title}', [PortfolioController::class, 'show'])->name('portfolio-details-v1');

Route::get('/portfolio-details-v2', [HomeController::class, 'portfolioDetailsV2']);
Route::get('/portfolio-details-v2/{title}', [PortfolioV2Controller::class, 'show'])->name('portfolio-details-v2');

Route::get('/error', [HomeController::class, 'error']);

Route::get('/my-profile', [HomeController::class, 'myProfile']);
Route::get('/my-account', [HomeController::class, 'myAccount']);
Route::get('/edit-account', [HomeController::class, 'editAccount']);
Route::get('/order-history', [HomeController::class, 'orderHistory']);
Route::get('/wishlist', [HomeController::class, 'wishlist']);

Route::get('/login', [HomeController::class, 'login']);
Route::get('/register', [HomeController::class, 'register']);
Route::get('/forger-password', [HomeController::class, 'forgerPassword']);
Route::get('/coming-soon', [HomeController::class, 'comingSoon']);
Route::get('/thank-you', [HomeController::class, 'thankYou']);

Route::get('/shipping-method', [HomeController::class, 'shippingMethod']);
Route::get('/payment-method', [HomeController::class, 'paymentMethod']);
Route::get('/invoice', [HomeController::class, 'invoice']);
Route::get('/payment-confirmation', [HomeController::class, 'paymentConfirmation']);
Route::get('/payment-success', [HomeController::class, 'paymentSuccess']);
Route::get('/payment-failure', [HomeController::class, 'paymentFailure']);

Route::get('/shop-v1', [HomeController::class, 'shopV1']);
Route::get('/shop-v2', [HomeController::class, 'shopV2']);
Route::get('/shop-v3', [HomeController::class, 'shopV3']);
Route::get('/shop-v4', [HomeController::class, 'shopV4']);

Route::get('/product-category', [HomeController::class, 'productCategory']);

Route::get('/product-details', [HomeController::class, 'productDetails']);
Route::get('/product-details/{title}', [ProductController::class, 'show'])->name('product-details');

Route::get('/cart', [HomeController::class, 'cart']);
Route::get('/checkout', [HomeController::class, 'checkout']);

Route::get('/blog-v1', [HomeController::class, 'blogV1']);
Route::get('/blog-v2', [HomeController::class, 'blogV2']);

Route::get('/blog-details-v1', [HomeController::class, 'blogDetailsV1']);
Route::get('/blog-details-v1/{title}', [BlogController::class, 'show'])->name('blog-details-v1');

Route::get('/blog-details-v2', [HomeController::class, 'blogDetailsV2']);
Route::get('/blog-details-v2/{title}', [BlogV2Controller::class, 'show'])->name('blog-details-v2');

Route::get('/blog-details-v3', [HomeController::class, 'blogDetailsV3']);
Route::get('/blog-tag', [HomeController::class, 'blogTag']);

Route::get('/short-code', [HomeController::class, 'shortCode']);

Route::get('/contact', [HomeController::class, 'contact']);

Route::get('/contactus', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contactus', [ContactController::class, 'send'])->name('contact.send');