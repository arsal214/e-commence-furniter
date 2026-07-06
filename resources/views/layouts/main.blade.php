<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('title', 'PeytonGhalib - Quality Furniture & Home Decor')</title>
        <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/png" sizes="32x32">
        <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon.png') }}">
        {{-- Inline dark-mode detection: runs synchronously before any CSS is parsed,
             so the correct class is on <html> when styles are applied. --}}
        <script>
            (function () {
                var scheme = localStorage.getItem('colorScheme');
                document.documentElement.className = scheme === 'dark' ? 'dark' : 'light';
            })();
        </script>
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-X57MYCJ0B8"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-X57MYCJ0B8');
        </script>
        {{-- Warm up CDN connection so the icon font doesn't add a DNS round-trip --}}
        <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
        <!-- Primary Meta Tags -->
        <meta name="description" content="@yield('meta_description', 'PeytonGhalib — Your one-stop online destination for quality furniture, home decor, ceramics, and more at unbeatable prices with fast delivery.')">
<meta name="author" content="PeytonGhalib">
        <meta name="robots" content="@yield('robots', 'index, follow')">
        <link rel="canonical" href="@yield('canonical', url()->current())">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', 'PeytonGhalib — Home Decor & Everyday Essentials Online')">
        <meta property="og:description" content="@yield('meta_description', 'PeytonGhalib — Your one-stop online destination for quality furniture, home decor, ceramics, and more at unbeatable prices with fast delivery.')">
        <meta property="og:image" content="@yield('og_image', asset('assets/img/logo.svg'))">
        <meta property="og:site_name" content="PeytonGhalib">
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="@yield('title', 'PeytonGhalib — Home Decor & Everyday Essentials Online')">
        <meta name="twitter:description" content="@yield('meta_description', 'PeytonGhalib — Your one-stop online destination for quality furniture, home decor, ceramics, and more at unbeatable prices with fast delivery.')">
        <meta name="twitter:image" content="@yield('og_image', asset('assets/img/logo.svg'))">
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
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/=materialdesignicons.min.css" rel="stylesheet">
        {{-- AOS FOUC fix: style.css hides [data-aos] elements (opacity:0) as soon as it loads,
             but AOS.init() in scripts.js runs much later. Keep elements visible until AOS sets
             its data-aos-easing attribute on <body> (the exact moment AOS.init() fires). --}}
        <style>
            /* Quick-action icon buttons on product cards */
            .new-product-icon {
                background: rgba(23, 36, 48, 0.82) !important;
                color: #ffffff !important;
                box-shadow: 0 4px 14px rgba(0,0,0,0.18);
            }
            .new-product-icon svg { color: #ffffff !important; fill: #ffffff !important; }
            .new-product-icon:hover {
                background: #bb976d !important;
                box-shadow: 0 6px 20px rgba(187,151,109,0.4);
            }
        </style>
        <style>
            body:not([data-aos-easing]) [data-aos] {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        </style>
        <!-- Ahrefs Analytics -->
        <script src="https://analytics.ahrefs.com/analytics.js" data-key="QlGio/G4Sr1krD5aawauYg" async></script>
        <!-- TikTok Pixel Code Start -->
        <script>
        !function (w, d, t) {
          w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
        var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
        ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};


          ttq.load('D95OHCRC77UFCF7AKUBG');
          ttq.page();
        }(window, document, 'ttq');
        </script>
        <!-- TikTok Pixel Code End -->
        @stack('styles')
        {{-- Organization schema: present on every page --}}
        @php
        $schemaOrg = ['@context'=>'https://schema.org','@type'=>'Organization','name'=>'PeytonGhalib','url'=>url('/'),
            'logo'=>['@type'=>'ImageObject','url'=>asset('assets/img/favicon.png')],
            'description'=>'PeytonGhalib offers quality furniture, home decor, ceramics, and everyday essentials with fast delivery.',
            'telephone'=>'+19294699864',
            'address'=>['@type'=>'PostalAddress','streetAddress'=>'200 Orient Ave STE 2B','addressLocality'=>'Jersey City','addressRegion'=>'NJ','postalCode'=>'07305','addressCountry'=>'US'],
            'contactPoint'=>['@type'=>'ContactPoint','contactType'=>'customer service','telephone'=>'+19294699864','url'=>url('/contact')],
            'sameAs'=>['https://www.facebook.com/profile.php?id=61590448962752','https://www.instagram.com/peytonghalib/','https://twitter.com/peytonghalib']];
        @endphp
        <script type="application/ld+json">{!! json_encode($schemaOrg, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
        {{-- Page-specific schemas pushed from each template --}}
        @stack('schema')
        <!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1007586992128470');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=1007586992128470&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->
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
                                        onclick="fbq('track', 'Lead');"
                                        class="h-11 px-5 bg-primary text-white text-sm font-medium hover:bg-title duration-200 whitespace-nowrap">
                                    Claim Offer
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-6 pt-5 border-t border-[#E3E5E6] dark:border-bdr-clr-drk flex items-center justify-between gap-4 flex-wrap">
                        <a href="{{ url('/shop') }}"
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

            function showPopup() {
                var popup = document.getElementById('welcome-popup');
                if (!popup.classList.contains('hidden')) return;
                popup.classList.remove('hidden');
                document.removeEventListener('mouseleave', onExitIntent);
            }

            function onExitIntent(e) {
                if (e.clientY <= 0 && shouldShow()) {
                    showPopup();
                }
            }

            if (shouldShow()) {
                document.addEventListener('mouseleave', onExitIntent);
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
        <!-- Force light mode — dark mode toggle removed, scripts.js dark-mode code nulled out -->
        <script>
        (function(){
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            localStorage.removeItem('colorScheme');
        })();
        </script>
        <script src="{{ asset('assets/js/base.js') }}"></script>
        <script>
            ['flash-success','flash-error'].forEach(function(id){
                var el = document.getElementById(id);
                if(el) setTimeout(function(){ el.style.transition='opacity .5s'; el.style.opacity='0'; setTimeout(function(){ el.remove(); },500); }, 4000);
            });
        </script>
        @stack('scripts')

        <!-- ── Social Proof Notifications ── -->
        @php
            $spItems = \App\Models\Product::where('is_active', true)
                ->inRandomOrder()->take(10)->get()
                ->map(function ($p) {
                    return [
                        'name'  => $p->name,
                        'image' => $p->image
                            ? (str_starts_with($p->image, 'assets/') ? asset($p->image) : \Storage::url($p->image))
                            : asset('assets/img/gallery/wishList-01.jpg'),
                        'url'   => route('product-details', $p->slug),
                    ];
                })->values();
        @endphp
        @if($spItems->count())
        <style>
        #pg-sp {
            position: fixed; bottom: 28px; right: 24px; z-index: 9990;
            width: 310px; background: #fff;
            box-shadow: 0 8px 40px rgba(23,36,48,.14), 0 2px 8px rgba(23,36,48,.06);
            display: flex; align-items: center; gap: 13px;
            padding: 12px 14px 12px 12px;
            transform: translateY(20px); opacity: 0;
            transition: transform .4s cubic-bezier(.34,1.3,.64,1), opacity .35s ease;
            pointer-events: none;
            border-left: 3px solid #bb976d;
            font-family: 'Poppins', -apple-system, sans-serif;
        }
        .dark #pg-sp { background: #1c2d3e; box-shadow: 0 8px 40px rgba(0,0,0,.35); }
        #pg-sp.sp-show { transform: translateY(0); opacity: 1; pointer-events: auto; }
        #pg-sp-img { width: 56px; height: 56px; object-fit: cover; flex-shrink: 0; background: #f5f0e8; display: block; }
        #pg-sp-body { flex: 1; min-width: 0; }
        #pg-sp-line1 { font-size: 11.5px; color: #666; line-height: 1.45; margin: 0 0 3px; }
        .dark #pg-sp-line1 { color: #aaa; }
        #pg-sp-line1 strong { color: #172430; font-weight: 700; }
        .dark #pg-sp-line1 strong { color: #fff; }
        #pg-sp-product { font-size: 12px; font-weight: 600; color: #bb976d; text-decoration: none; line-height: 1.35; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        #pg-sp-product:hover { text-decoration: underline; }
        #pg-sp-meta { font-size: 10.5px; color: #bbb; margin: 3px 0 0; display: flex; align-items: center; gap: 5px; }
        #pg-sp-meta::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #4CAF50; flex-shrink: 0; }
        #pg-sp-close {
            position: absolute; top: 7px; right: 7px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #f0ebe3; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #999; font-size: 12px; line-height: 1;
            transition: background .18s, color .18s; flex-shrink: 0; padding: 0;
        }
        .dark #pg-sp-close { background: rgba(255,255,255,.1); color: #aaa; }
        #pg-sp-close:hover { background: #E13939; color: #fff; }
        </style>

        <div id="pg-sp" role="status" aria-live="polite" style="position:fixed;">
            <img id="pg-sp-img" src="" alt="">
            <div id="pg-sp-body">
                <p id="pg-sp-line1"></p>
                <a id="pg-sp-product" href=""></a>
                <p id="pg-sp-meta">Just now</p>
            </div>
            <button id="pg-sp-close" aria-label="Dismiss">
                <svg width="8" height="8" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                    <line x1="1" y1="1" x2="9" y2="9"/><line x1="9" y1="1" x2="1" y2="9"/>
                </svg>
            </button>
        </div>

        <script>
        (function () {
            var products = @json($spItems);
            var cities   = [
                'New York, NY','Los Angeles, CA','Chicago, IL','Houston, TX',
                'Miami, FL','Dallas, TX','Phoenix, AZ','Seattle, WA',
                'London, UK','Toronto, CA','Sydney, AU','Dubai, UAE',
                'Boston, MA','Atlanta, GA','Denver, CO','San Diego, CA',
                'Austin, TX','Portland, OR','Nashville, TN','Las Vegas, NV'
            ];
            var actions  = ['just purchased','started buying','just ordered','just bought'];
            var times    = ['Just now','1 min ago','2 mins ago','3 mins ago','5 mins ago'];

            var el      = document.getElementById('pg-sp');
            var imgEl   = document.getElementById('pg-sp-img');
            var line1   = document.getElementById('pg-sp-line1');
            var prodEl  = document.getElementById('pg-sp-product');
            var metaEl  = document.getElementById('pg-sp-meta');
            var closeEl = document.getElementById('pg-sp-close');
            if (!el) return;

            var dismissed = false;
            var pIdx = 0;
            var SHOW_MS  = 5000;
            var PAUSE_MS = 9000;

            function rnd(arr) { return arr[Math.floor(Math.random() * arr.length)]; }

            function showNext() {
                if (dismissed) return;
                var p    = products[pIdx % products.length];
                var city = rnd(cities);
                var act  = rnd(actions);
                var t    = rnd(times);
                pIdx++;

                imgEl.src          = p.image;
                imgEl.alt          = p.name;
                line1.innerHTML    = 'Someone from <strong>' + city + '</strong> ' + act;
                prodEl.textContent = p.name;
                prodEl.href        = p.url;
                metaEl.textContent = t;

                el.classList.add('sp-show');
                setTimeout(function () {
                    el.classList.remove('sp-show');
                    setTimeout(showNext, PAUSE_MS);
                }, SHOW_MS);
            }

            closeEl.addEventListener('click', function () {
                el.classList.remove('sp-show');
                dismissed = true;
                setTimeout(function () { dismissed = false; setTimeout(showNext, PAUSE_MS * 2); }, 0);
            });

            // Initial delay before first appearance
            setTimeout(showNext, 4000);
        }());
        </script>
        @endif

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

        <!-- Meta Pixel Add to Cart Tracking -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('click', function (event) {
                var el = event.target.closest('button, a');
                if (el && el.textContent.trim().toLowerCase() === 'add to cart') {
                    fbq('track', 'AddToCart');
                }
            });
        });
        </script>
    </body>
</html>
