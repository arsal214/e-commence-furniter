<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MarketingController;
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
//  Seller sign-up: account -> email code -> plan -> business -> owner -> bank -> review
// ──────────────────────────────────────────────
Route::prefix('sell')->group(function () {
    $c = \App\Http\Controllers\Seller\OnboardingController::class;
    $l = \App\Http\Controllers\Seller\LandingController::class;

    Route::get('/', [$l, 'index'])->name('seller.landing');
    Route::get('pricing', [$l, 'pricing'])->name('seller.pricing');

    Route::middleware('guest')->group(function () use ($c) {
        Route::get('signup', [$c, 'signupForm'])->name('seller.signup');
        Route::post('signup', [$c, 'signup'])->middleware('throttle:10,1,sell-signup')->name('seller.signup.store');
    });

    Route::middleware('auth')->group(function () use ($c) {
        Route::get('verify', [$c, 'verifyForm'])->name('seller.verify');
        Route::post('verify', [$c, 'verify'])->middleware('throttle:10,1,sell-verify')->name('seller.verify.check');
        Route::post('verify/resend', [$c, 'resend'])->middleware('throttle:3,1,sell-resend')->name('seller.verify.resend');
        Route::get('plan', [$c, 'plan'])->name('seller.plan');
        Route::post('plan', [$c, 'subscribe'])->middleware('throttle:10,1,sell-plan')->name('seller.plan.subscribe');
        Route::get('plan/return', [$c, 'planReturn'])->name('seller.plan.return');
        Route::get('business', [$c, 'businessForm'])->name('seller.business');
        Route::post('business', [$c, 'business'])->name('seller.business.save');
        Route::get('owner', [$c, 'ownerForm'])->name('seller.owner');
        Route::post('owner', [$c, 'owner'])->name('seller.owner.save');
        Route::get('bank', [$c, 'bank'])->name('seller.bank');
        Route::post('bank', [$c, 'bankStart'])->middleware('throttle:10,1,sell-bank')->name('seller.bank.start');
        Route::get('bank/return', [$c, 'bankReturn'])->name('seller.bank.return');
        Route::get('review', [$c, 'review'])->name('seller.review');
    });
});

// ──────────────────────────────────────────────
//  Seller Center (approved-or-waiting sellers who finished sign-up)
// ──────────────────────────────────────────────
Route::prefix('seller')->name('seller.')->middleware(['auth', 'seller'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
    $m = \App\Http\Controllers\Seller\MoneyController::class;
    Route::get('orders',            [$m, 'orders'])->name('orders');
    Route::post('orders/{shipment}/ship', [$m, 'ship'])->middleware('throttle:60,1,ship')->name('orders.ship');
    Route::get('payouts',           [$m, 'payouts'])->name('payouts');
    Route::get('payouts/export',    [$m, 'payoutsCsv'])->name('payouts.export');
    Route::post('payouts/bank',     [$m, 'bank'])->middleware('throttle:10,1,bank-link')->name('payouts.bank');
    Route::get('levels',            [$m, 'levels'])->name('levels');
    Route::post('points/redeem',    [$m, 'redeem'])->middleware('throttle:10,1,redeem')->name('points.redeem');
    Route::get('billing',           [$m, 'billing'])->name('billing');
    Route::post('billing/portal',   [$m, 'portal'])->middleware('throttle:10,1,portal')->name('billing.portal');
    Route::post('resubmit', [\App\Http\Controllers\Seller\DashboardController::class, 'resubmit'])->name('resubmit');
    Route::get('messages', [\App\Http\Controllers\Seller\MessageController::class, 'index'])->name('messages');
    Route::post('messages/{conversation}', [\App\Http\Controllers\Seller\MessageController::class, 'reply'])->middleware('throttle:60,1,seller-message')->name('messages.reply');
    $in = \App\Http\Controllers\Seller\InsightsController::class;
    Route::get('performance', [$in, 'performance'])->name('performance');
    Route::post('reviews/{review}/reply', [$in, 'reply'])->middleware('throttle:30,1,review-reply')->name('reviews.reply');
    Route::get('referrals', [$in, 'referrals'])->name('referrals');
    Route::get('help', [$in, 'help'])->name('help');
    $mk = \App\Http\Controllers\Seller\MarketingController::class;
    Route::get('marketing', [$mk, 'index'])->name('marketing');
    Route::post('marketing/coupons', [$mk, 'createCoupon'])->middleware('throttle:30,1,seller-coupon')->name('marketing.coupons.store');
    Route::post('marketing/coupons/{coupon}/toggle', [$mk, 'toggleCoupon'])->name('marketing.coupons.toggle');
    Route::post('marketing/sale', [$mk, 'sale'])->middleware('throttle:10,1,seller-sale')->name('marketing.sale');
    Route::post('marketing/ads', [$mk, 'ads'])->middleware('throttle:20,1,seller-ads')->name('marketing.ads');
    $st = \App\Http\Controllers\Seller\SettingsController::class;
    Route::get('settings',   [$st, 'settings'])->name('settings');
    Route::post('settings',  [$st, 'saveSettings'])->middleware('throttle:30,1,seller-settings')->name('settings.save');
    Route::get('storefront', [$st, 'storefront'])->name('storefront');
    Route::post('storefront', [$st, 'saveStorefront'])->middleware('throttle:30,1,seller-storefront')->name('storefront.save');
    Route::resource('products', \App\Http\Controllers\Seller\ProductController::class)->except(['show']);
});

// ──────────────────────────────────────────────
//  Protected customer routes
// ──────────────────────────────────────────────
Route::middleware(['auth', 'must-reset'])->group(function () {
    // First-login password setup for guest-checkout accounts (flow B). Kept inside
    // the must-reset group; the middleware exempts these routes so the user can finish.
    Route::get('/account/set-password',  [AuthController::class, 'showSetPassword'])->name('account.set-password');
    Route::post('/account/set-password', [AuthController::class, 'setPassword'])->name('account.set-password.store');

    Route::get('/my-profile',    fn() => redirect('/my-account'));
    Route::get('/my-account/{tab?}', [\App\Http\Controllers\AccountController::class, 'show'])->where('tab', 'orders|returns|wishlist|reviews|rewards|giftcards|wallet|messages|following|notifications|settings')->name('account');
    Route::post('/my-account/settings',      [\App\Http\Controllers\AccountController::class, 'profile']);
    Route::post('/my-account/password',      [\App\Http\Controllers\AccountController::class, 'password'])->middleware('throttle:10,1,account-password');
    Route::post('/my-account/notifications', [\App\Http\Controllers\AccountController::class, 'notifications']);
    Route::post('/my-account/messages', [\App\Http\Controllers\MessageController::class, 'start'])->middleware('throttle:20,1,message-start');
    Route::post('/my-account/messages/{conversation}', [\App\Http\Controllers\MessageController::class, 'reply'])->middleware('throttle:60,1,message-reply');
    Route::post('/my-account/gift-cards/buy', [\App\Http\Controllers\GiftCardController::class, 'buy'])->middleware('throttle:10,1,giftcard-buy')->name('giftcards.buy');
    Route::get('/gift-cards/return', [\App\Http\Controllers\GiftCardController::class, 'returned'])->name('giftcards.return');
    Route::post('/my-account/gift-cards/redeem', [\App\Http\Controllers\GiftCardController::class, 'redeem'])->middleware('throttle:10,1,giftcard-redeem')->name('giftcards.redeem');
    Route::post('/my-account/addresses', [\App\Http\Controllers\AccountController::class, 'addAddress'])->middleware('throttle:20,1,address-add');
    Route::post('/my-account/addresses/{address}/default', [\App\Http\Controllers\AccountController::class, 'defaultAddress']);
    Route::post('/my-account/addresses/{address}/delete', [\App\Http\Controllers\AccountController::class, 'deleteAddress']);
    Route::post('/store/{slug}/follow', [\App\Http\Controllers\AccountController::class, 'follow'])->middleware('throttle:60,1,store-follow')->name('store.follow');
    // Addresses of the old pages.
    Route::get('/edit-account',  fn() => redirect('/my-account/settings'));
    Route::get('/order-history', fn() => redirect('/my-account/orders'));
    Route::get('/wishlist',      fn() => redirect('/my-account/wishlist'));
    Route::post('/wishlist/toggle',  [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/remove',  [WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::post('/product/{slug}/review', [ReviewController::class, 'store'])->name('product.review.store');
});

// Checkout is open to guests. Placing an order auto-creates an account (flow B).
Route::get('/checkout',         [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout',        [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe-success');
Route::post('/checkout/paypal/{order}/capture', [CheckoutController::class, 'paypalCapture'])->name('checkout.paypal-capture');

// Pay-by-link for an unpaid order, emailed from the admin. The token is the credential.
Route::get('/pay/{token}', [CheckoutController::class, 'payLink'])->name('order.pay');

// ──────────────────────────────────────────────
//  Admin routes
// ──────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\MarketAdminController::class, 'overview'])->name('admin.overview');
    Route::get('/store', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('disputes',                [Admin\MarketAdminController::class, 'disputes'])->name('admin.disputes.index');
    Route::post('disputes/{dispute}',     [Admin\MarketAdminController::class, 'decideDispute'])->name('admin.disputes.decide');
    Route::get('payouts',                 [Admin\MarketAdminController::class, 'payouts'])->name('admin.payouts.index');
    Route::get('subscriptions',           [Admin\MarketAdminController::class, 'subscriptions'])->name('admin.subscriptions.index');
    Route::get('marketing',               [Admin\MarketAdminController::class, 'marketing'])->name('admin.marketing.index');
    Route::post('marketing/banners/{slider}', [Admin\MarketAdminController::class, 'toggleBanner'])->name('admin.marketing.banner');
    Route::get('reports',                 [Admin\MarketAdminController::class, 'reports'])->name('admin.reports.index');
    Route::resource('categories', Admin\CategoryController::class)->names('admin.categories')->except(['show']);
    Route::get('products/export', [Admin\ProductController::class, 'export'])->name('admin.products.export');
    Route::get('products/import',  [Admin\ProductImportController::class, 'index'])->name('admin.products.import');
    Route::post('products/import', [Admin\ProductImportController::class, 'store'])->name('admin.products.import.store');
    Route::resource('products',   Admin\ProductController::class)->names('admin.products')->except(['show']);
    Route::resource('orders',     Admin\OrderController::class)->names('admin.orders')->only(['index', 'show', 'update']);
    Route::post('orders/{order}/request-payment', [Admin\OrderController::class, 'requestPayment'])->name('admin.orders.request-payment');
    Route::post('orders/{order}/delivery-proofs', [Admin\OrderController::class, 'storeDeliveryProof'])->name('admin.orders.delivery-proofs.store');
    Route::get('orders/{order}/delivery-proofs/{proof}', [Admin\OrderController::class, 'showDeliveryProof'])->name('admin.orders.delivery-proofs.show');
    Route::delete('orders/{order}/delivery-proofs/{proof}', [Admin\OrderController::class, 'destroyDeliveryProof'])->name('admin.orders.delivery-proofs.destroy');
    Route::resource('reviews',    Admin\ReviewController::class)->names('admin.reviews')->except(['show']);
    Route::resource('sliders',    Admin\SliderController::class)->names('admin.sliders')->except(['show']);
    Route::get('email-logs',     [Admin\EmailLogController::class, 'index'])->name('admin.email-logs.index');

    // Marketplace approvals: seller applications and listing moderation.
    Route::get('sellers',                  [Admin\SellerController::class, 'index'])->name('admin.sellers.index');
    Route::get('sellers/{seller}',         [Admin\SellerController::class, 'show'])->name('admin.sellers.show');
    Route::post('sellers/{seller}/decision', [Admin\SellerController::class, 'decide'])->name('admin.sellers.decide');
    Route::get('sellers/{seller}/documents/{type}', [Admin\SellerController::class, 'document'])->name('admin.sellers.document');
    Route::get('moderation',               [Admin\ModerationController::class, 'index'])->name('admin.moderation.index');
    Route::get('moderation/{product}',     [Admin\ModerationController::class, 'show'])->name('admin.moderation.show');
    Route::post('moderation/{product}',    [Admin\ModerationController::class, 'decide'])->name('admin.moderation.decide');

    // Marketing: one-to-one deals & offers email.
    Route::get('customers',        [Admin\CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('campaigns/compose',  [Admin\CampaignController::class, 'compose'])->name('admin.campaigns.compose');
    Route::post('campaigns/preview', [Admin\CampaignController::class, 'preview'])->name('admin.campaigns.preview');
    Route::post('campaigns/send',    [Admin\CampaignController::class, 'send'])->name('admin.campaigns.send');
    Route::resource('email-templates', Admin\EmailTemplateController::class)->names('admin.email-templates')->except(['show']);
    // Shared by both editors: images must be hosted before they can go in a mail body.
    Route::post('email-images', [Admin\EmailImageController::class, 'store'])->name('admin.email-images.store');
    Route::get('flash-deal',     [Admin\FlashDealController::class, 'index'])->name('admin.flash-deal.index');
    Route::put('flash-deal',     [Admin\FlashDealController::class, 'update'])->name('admin.flash-deal.update');
    Route::get('store-settings', [Admin\StoreSettingsController::class, 'index'])->name('admin.store-settings.index');
    Route::put('store-settings', [Admin\StoreSettingsController::class, 'update'])->name('admin.store-settings.update');
    Route::get('site-text',      [Admin\SiteTextController::class, 'index'])->name('admin.site-text.index');
    Route::put('site-text',      [Admin\SiteTextController::class, 'update'])->name('admin.site-text.update');
    Route::get('bundles',        [Admin\BundleController::class, 'index'])->name('admin.bundles.index');
    Route::put('bundles/{bundle}', [Admin\BundleController::class, 'update'])->name('admin.bundles.update');
    Route::get('welcome-offer',  [Admin\WelcomeOfferController::class, 'index'])->name('admin.welcome-offer.index');
    Route::put('welcome-offer',  [Admin\WelcomeOfferController::class, 'update'])->name('admin.welcome-offer.update');
});

// ──────────────────────────────────────────────
//  Public frontend routes
// ──────────────────────────────────────────────
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', [\App\Http\Controllers\MarketController::class, 'home'])->name('home');
Route::get('/ad/{slug}', [\App\Http\Controllers\MarketController::class, 'adClick'])->middleware('throttle:120,1,ad-click')->name('ad.click');

// Company, help and policy pages (copy in resources/docs/pages.json).
foreach (\App\Support\Docs::PATHS as $slug => $path) {
    $route = Route::get($path, [\App\Http\Controllers\PageController::class, 'doc'])->defaults('slug', $slug);
    if ($name = ['returns' => 'return-policy', 'shipping' => 'shipping-policy', 'privacy' => 'privacy-policy', 'contact' => 'contact.show'][$slug] ?? null) {
        $route->name($name);
    }
}
Route::get('/refund-policy', [\App\Http\Controllers\PageController::class, 'doc'])->defaults('slug', 'returns')->name('refund-policy');
Route::get('/faq', [\App\Http\Controllers\PageController::class, 'help'])->name('faq');
Route::get('/sitemap', [\App\Http\Controllers\PageController::class, 'sitemap'])->name('sitemap');



Route::get('/thank-you',   [HomeController::class, 'thankYou'])->name('thank-you');
Route::post('/thank-you/account', [HomeController::class, 'createAccountFromOrder'])->middleware('throttle:5,1')->name('thank-you.account');
Route::post('/track-order/{tracking}/claim', [\App\Http\Controllers\ClaimController::class, 'store'])->middleware('throttle:6,60,claim')->name('claim.store');
Route::get('/track-order', [HomeController::class, 'trackOrder'])->middleware('throttle:30,1,track-order')->name('track-order');

Route::redirect('/shipping-method', '/shipping-policy', 301); // SEO: duplicate of shipping-policy

Route::get('/shop',             [\App\Http\Controllers\MarketController::class, 'shop'])->name('shop');
Route::post('/location',         [\App\Http\Controllers\MarketController::class, 'location'])->middleware('throttle:30,1,location')->name('location');
Route::post('/location/detect',  [\App\Http\Controllers\MarketController::class, 'detectLocation'])->middleware('throttle:10,1,locdetect')->name('location.detect');
Route::get('/search',           [\App\Http\Controllers\MarketController::class, 'search'])->name('search');
Route::get('/stores/{slug}/page/{uuid}', [\App\Http\Controllers\MarketController::class, 'store'])->where('uuid', '[0-9A-Fa-f-]{36}')->name('store.show');
Route::get('/store/{slug}', [\App\Http\Controllers\MarketController::class, 'storeLegacy']); // old address: sent on to the new one
Route::get('/shop-v1',          fn() => redirect('/shop', 301));
Route::redirect('/categories', '/sitemap', 301); // the old categories page
Route::redirect('/category/beauty-produts', '/category/beauty-products', 301); // SEO: typo fix (must precede dynamic route)
Route::get('/category/deals',   [\App\Http\Controllers\MarketController::class, 'deals'])->name('deals');
Route::get('/category/{slug}',  [\App\Http\Controllers\MarketController::class, 'category'])->name('category.landing');
Route::redirect('/product-category', '/shop', 301); // SEO: duplicate of /shop


Route::get('/search/suggestions',      [ProductController::class, 'suggestions'])->name('search.suggestions');
Route::redirect('/product-details', '/', 301);
Route::get('/product-details/{slug}',  [ProductController::class, 'show'])->name('product-details');

Route::get('/cart',          [CartController::class, 'index'])->name('cart');
Route::post('/cart/add',     [CartController::class, 'add'])->name('cart.add');
// Bundle add ("Frequently bought together" on the product page).
Route::post('/cart/add-many', [CartController::class, 'addMany'])->name('cart.add-many');
Route::post('/cart/add-bundle', [CartController::class, 'addBundle'])->name('cart.add-bundle');
Route::post('/cart/update',  [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove',  [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',   [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon',  [CartController::class, 'coupon'])->middleware('throttle:20,1,cart-coupon')->name('cart.coupon');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::post('/cart/points', [CartController::class, 'points'])->name('cart.points');

// Stripe -> us. Signature-verified inside the controller; no session/CSRF.
Route::post('/stripe/webhook', \App\Http\Controllers\StripeWebhookController::class)->name('stripe.webhook');



Route::redirect('/contactus', '/contact', 301);
Route::post('/contactus', [ContactController::class, 'send'])->name('contact.send');
Route::post('/newsletter', [ContactController::class, 'newsletter'])->name('newsletter.subscribe');

// Opt-out target for the unsubscribe link in promotional email. Signed, so the
// URL itself is the credential; POST is what one-click unsubscribe clients use.
Route::match(['get', 'post'], '/unsubscribe', [MarketingController::class, 'unsubscribe'])
    ->middleware('signed')
    ->name('marketing.unsubscribe');

// New-customer welcome offer. Server-decided; the JSON only drives what the page shows.
Route::get('/welcome/status', [\App\Http\Controllers\WelcomeOfferController::class, 'status'])->name('welcome.status');
Route::post('/welcome/claim', [\App\Http\Controllers\WelcomeOfferController::class, 'claim'])->middleware('throttle:5,60')->name('welcome.claim');
