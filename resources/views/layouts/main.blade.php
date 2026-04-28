<!-- resources/views/layouts/main.blade.php -->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title', 'PeytonGhalib') | PeytonGhalib</title>
        <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/gif" sizes="18x18">
        {{-- Inline dark-mode detection: runs synchronously before any CSS is parsed,
             so the correct class is on <html> when styles are applied. --}}
        <script>
            (function () {
                var scheme = localStorage.getItem('colorScheme');
                document.documentElement.className = scheme === 'dark' ? 'dark' : 'light';
            })();
        </script>
        {{-- Warm up CDN connection so the icon font doesn't add a DNS round-trip --}}
        <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

        <!-- Meta tags for SEO -->
        <meta content="	ceramics,furniture,PeytonGhalib, furniture store, interior design" name="keywords">
        <meta name="author" content="PeytonGhalib">
        <meta name="website" content="https://peytonghalib.com">
        <meta name="email" content="support@peytonghalib.com">
        <meta name="version" content="1.0.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @auth
        <meta name="wishlist-ids" content="{{ json_encode(\App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')) }}">
        @else
        <meta name="wishlist-ids" content="[]">
        @endauth
        <meta name="is-logged-in" content="{{ auth()->check() ? 'true' : 'false' }}">
        <meta name="wishlist-toggle-url" content="{{ auth()->check() ? route('wishlist.toggle') : url('/login') }}">

        <!-- Main Stylesheet -->
        @vite('resources/css/app.css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
        {{-- AOS FOUC fix: style.css hides [data-aos] elements (opacity:0) as soon as it loads,
             but AOS.init() in scripts.js runs much later. Keep elements visible until AOS sets
             its data-aos-easing attribute on <body> (the exact moment AOS.init() fires). --}}
        <style>
            body:not([data-aos-easing]) [data-aos] {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        </style>
        @stack('styles')
    </head>

    <body class="dark:bg-title">


        <!-- Flash Messages -->
        @if(session('success'))
        <div id="flash-success" class="fixed top-5 right-5 z-[99999] bg-green-600 text-white px-5 py-3 rounded shadow-lg text-sm max-w-sm">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div id="flash-error" class="fixed top-5 right-5 z-[99999] bg-red-600 text-white px-5 py-3 rounded shadow-lg text-sm max-w-sm">
            {{ session('error') }}
        </div>
        @endif

        <!-- Main Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Back to top -->
        <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fixed hidden text-lg rectangle-full z-10 bottom-5 end-5 h-9 w-9 text-center bg-[#bb976d] text-white leading-9"><i class="mdi mdi-arrow-up"></i></a>
        <!-- Back to top -->

        <!-- Welcome Sales Popup -->
        <div id="welcome-popup" class="fixed inset-0 z-[99999] flex items-center justify-center px-4 hidden">
            <!-- Backdrop -->
            <div id="welcome-popup-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <!-- Popup Card -->
            <div class="relative z-10 bg-white dark:bg-title w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col sm:flex-row">

                <!-- Left image panel -->
                <div class="sm:w-5/12 hidden sm:block relative min-h-[320px]">
                    <img src="{{ asset('assets/img/home-v1/pdct-cgry-01.jpg') }}"
                         alt="Special Offer"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
                        <div>
                            <span class="text-xs uppercase tracking-widest text-primary font-semibold">Limited Time</span>
                            <h3 class="text-white text-2xl font-bold leading-tight mt-1">New Arrivals<br>Are Here!</h3>
                        </div>
                    </div>
                </div>

                <!-- Right content panel -->
                <div class="sm:w-7/12 p-8 sm:p-10 flex flex-col justify-between">
                    <!-- Close button -->
                    <button id="welcome-popup-close"
                            class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white text-title dark:text-white duration-200">
                        <svg class="fill-current w-3 h-3" viewBox="0 0 12 12"><path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/></svg>
                    </button>

                    <div>
                        <span class="text-xs uppercase tracking-widest text-primary font-semibold">Welcome to PeytonGhalib</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-title dark:text-white leading-tight mt-2">
                            Get <span class="text-primary">10% Off</span><br>Your First Order
                        </h2>
                        <p class="text-paragraph dark:text-white-light text-sm mt-3 leading-relaxed">
                            Subscribe to our newsletter and unlock an exclusive 10% discount on your first purchase. Stay updated with the latest deals, new arrivals, and more.
                        </p>

                        <!-- Newsletter form inside popup -->
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-5">
                            @csrf
                            <div class="flex gap-2">
                                <input type="email" name="email"
                                       placeholder="Enter your email"
                                       required
                                       class="flex-1 h-11 border border-[#E3E5E6] dark:border-bdr-clr-drk bg-transparent text-title dark:text-white px-4 text-sm outline-none focus:border-primary duration-200 placeholder:text-gray-400">
                                <button type="submit"
                                        class="h-11 px-5 bg-primary text-white text-sm font-medium hover:bg-title duration-200 whitespace-nowrap">
                                    Claim Offer
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-6 pt-5 border-t border-[#E3E5E6] dark:border-bdr-clr-drk flex items-center justify-between gap-4 flex-wrap">
                        <a href="{{ url('/shop-v1') }}"
                           class="text-sm font-semibold text-title dark:text-white hover:text-primary duration-200 flex items-center gap-1">
                            Browse All Products
                            <svg width="12" height="8" viewBox="0 0 24 14" fill="none" class="fill-current"><path d="M23.82 6.62L18.38 1.18C18.18 0.95 17.84 0.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46H0.57C0.27 6.46 0.02 6.71 0.02 7.01C0.02 7.31 0.27 7.55 0.57 7.55H22.12L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z"/></svg>
                        </a>
                        <button id="welcome-popup-skip"
                                class="text-xs text-gray-400 hover:text-primary duration-200 underline">
                            No thanks, skip
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        (function () {
            var POPUP_KEY = 'pg_welcome_shown';
            var EXPIRY_HOURS = 24; // show once every 24 hours

            function shouldShow() {
                var stored = localStorage.getItem(POPUP_KEY);
                if (!stored) return true;
                var ts = parseInt(stored, 10);
                var now = Date.now();
                return (now - ts) > EXPIRY_HOURS * 60 * 60 * 1000;
            }

            function closePopup() {
                var popup = document.getElementById('welcome-popup');
                popup.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(function () { popup.classList.add('hidden'); }, 300);
                localStorage.setItem(POPUP_KEY, Date.now().toString());
            }

            if (shouldShow()) {
                // Show after 1.5s delay
                setTimeout(function () {
                    var popup = document.getElementById('welcome-popup');
                    popup.classList.remove('hidden');
                }, 1500);
            }

            document.getElementById('welcome-popup-close').addEventListener('click', closePopup);
            document.getElementById('welcome-popup-skip').addEventListener('click', closePopup);
            document.getElementById('welcome-popup-backdrop').addEventListener('click', closePopup);

            // Also close after form submit (newsletter)
            var form = document.querySelector('#welcome-popup form');
            if (form) {
                form.addEventListener('submit', function () {
                    localStorage.setItem(POPUP_KEY, Date.now().toString());
                });
            }
        })();
        </script>
        <!-- Welcome Sales Popup End -->

        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <script src="{{ asset('assets/js/base.js') }}"></script>
        <script>
            ['flash-success','flash-error'].forEach(function(id){
                var el = document.getElementById(id);
                if(el) setTimeout(function(){ el.style.transition='opacity .5s'; el.style.opacity='0'; setTimeout(function(){ el.remove(); },500); }, 4000);
            });
        </script>
        @stack('scripts')

        <!-- Wishlist Toggle JS -->
        <script>
        (function () {
            const isLoggedIn   = document.querySelector('meta[name="is-logged-in"]')?.content === 'true';
            const toggleUrl    = document.querySelector('meta[name="wishlist-toggle-url"]')?.content || '/login';
            const csrfToken    = document.querySelector('meta[name="csrf-token"]')?.content || '';
            let wishlistIds    = JSON.parse(document.querySelector('meta[name="wishlist-ids"]')?.content || '[]');

            function updateButtons() {
                document.querySelectorAll('.wishlist-toggle-btn').forEach(btn => {
                    const pid = parseInt(btn.dataset.productId);
                    const inWishlist = wishlistIds.includes(pid);
                    const textEl = btn.querySelector('.wishlist-btn-text');
                    const icon   = btn.querySelector('.wishlist-icon-outline');

                    if (inWishlist) {
                        btn.classList.add('wishlist-active');
                        if (icon)   { icon.style.fill = '#E13939'; icon.style.color = '#E13939'; }
                        if (textEl) textEl.textContent = 'In Wishlist ♥';
                    } else {
                        btn.classList.remove('wishlist-active');
                        if (icon)   { icon.style.fill = ''; icon.style.color = ''; }
                        if (textEl) textEl.textContent = 'Add to wishlist';
                    }
                });
            }

            function updateNavCount(count) {
                const el = document.getElementById('nav-wishlist-count');
                if (el) el.textContent = count;
            }

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.wishlist-toggle-btn');
                if (!btn) return;
                e.preventDefault();

                if (!isLoggedIn) {
                    window.location.href = '/login?redirect=/wishlist';
                    return;
                }

                const productId = btn.dataset.productId;
                btn.disabled = true;

                fetch(toggleUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_id: productId }),
                })
                .then(r => r.json())
                .then(data => {
                    wishlistIds = data.wishlist_ids || [];
                    updateButtons();
                    updateNavCount(data.count);
                })
                .catch(() => {})
                .finally(() => { btn.disabled = false; });
            });

            // Init state on load
            document.addEventListener('DOMContentLoaded', updateButtons);
        })();
        </script>

    </body>
</html>