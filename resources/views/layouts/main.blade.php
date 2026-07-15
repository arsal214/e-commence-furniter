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
        {{-- Per-page LCP image preload (pushed only where a known hero image exists) --}}
        @stack('preload')
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


          ttq.load(@json(config('services.tiktok.pixel_id', 'D95OHCRC77UFCF7AKUBG')));
          ttq.page();
        }(window, document, 'ttq');
        </script>
        <!-- TikTok Pixel Code End -->
        @include('includes.tiktok-events')
        @stack('styles')
        {{-- Organization schema: present on every page --}}
        @php
        $schemaOrg = ['@context'=>'https://schema.org','@type'=>'Organization','name'=>'PeytonGhalib','url'=>url('/'),
            'logo'=>['@type'=>'ImageObject','url'=>asset('assets/img/favicon.png')],
            'description'=>'PeytonGhalib offers quality furniture, home decor, ceramics, and everyday essentials with fast delivery.',
            'telephone'=>'+19294699864',
            'address'=>['@type'=>'PostalAddress','streetAddress'=>'200 Orient Ave STE 2B','addressLocality'=>'Jersey City','addressRegion'=>'NJ','postalCode'=>'07305','addressCountry'=>'US'],
            'contactPoint'=>['@type'=>'ContactPoint','contactType'=>'customer service','telephone'=>'+19294699864','url'=>url('/contact')],
            'sameAs'=>['https://www.facebook.com/profile.php?id=61590448962752','https://www.instagram.com/peytonghalibexpress/','https://www.tiktok.com/@peytonghalib','https://www.pinterest.com/peytonexpress/']];
        @endphp
        <script type="application/ld+json">{!! json_encode($schemaOrg, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
        {{-- Page-specific schemas pushed from each template --}}
        @stack('schema')
        <!-- old  Meta Pixel Code -->
        {{-- <script>
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
        /></noscript> --}}
        <!-- End Meta Pixel Code -->


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
fbq('init', '1675737636873475');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1675737636873475&ev=PageView&noscript=1"
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
            position: fixed; bottom: 28px; left: 24px; z-index: 9990;
            width: 310px; max-width: calc(100vw - 32px); background: #fff;
            box-shadow: 0 8px 40px rgba(23,36,48,.14), 0 2px 8px rgba(23,36,48,.06);
            display: flex; align-items: center; gap: 13px;
            padding: 12px 14px 12px 12px;
            /* Slides in from the left edge it's anchored to */
            transform: translateX(-24px); opacity: 0;
            transition: transform .4s cubic-bezier(.34,1.3,.64,1), opacity .35s ease;
            pointer-events: none;
            border-left: 3px solid #bb976d;
            font-family: 'Poppins', -apple-system, sans-serif;
        }
        .dark #pg-sp { background: #1c2d3e; box-shadow: 0 8px 40px rgba(0,0,0,.35); }
        #pg-sp.sp-show { transform: translateX(0); opacity: 1; pointer-events: auto; }

        /* Toasts sit along the bottom on mobile — lift this clear of them */
        @media (max-width: 640px) {
            #pg-sp { bottom: 96px; left: 16px; }
        }
        @media (prefers-reduced-motion: reduce) {
            #pg-sp { transition: opacity .2s ease; transform: none; }
            #pg-sp.sp-show { transform: none; }
        }
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
                const name      = btn.dataset.productName;
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
                .then(r => {
                    if (!r.ok) throw new Error('bad status');
                    return r.json();
                })
                .then(data => {
                    wishlistIds = data.wishlist_ids || [];
                    updateButtons();
                    updateNavCount(data.count);

                    const label = name ? `“${name}”` : 'Item';
                    window.showToast?.(
                        data.added ? `${label} added to your wishlist.` : `${label} removed from your wishlist.`,
                        data.added ? 'success' : 'info'
                    );
                })
                // Was an empty catch: a failed toggle looked identical to a successful one.
                .catch(() => {
                    window.showToast?.("Couldn't update your wishlist. Please try again.", 'error');
                })
                .finally(() => { btn.disabled = false; });
            });

            // Init state on load
            document.addEventListener('DOMContentLoaded', updateButtons);
        })();
        </script>

        {{-- Toasts + Quick View.
             Deliberately plain CSS, not Tailwind utilities: assets/css/style.css loads
             after the Vite build, so base utilities there silently override Vite-only
             responsive variants. Scoped pg-* classes avoid that cascade entirely. --}}
        <style>
            .pg-toasts {
                position: fixed; top: 24px; right: 24px; z-index: 99999999;
                display: flex; flex-direction: column; gap: 10px;
                max-width: calc(100vw - 32px); pointer-events: none;
            }
            .pg-toast {
                display: flex; align-items: flex-start; gap: 10px;
                min-width: 280px; max-width: 380px; padding: 14px 14px 14px 16px;
                background: #fff; color: #172430;
                border: 1px solid #E8E1D7; border-left: 3px solid #BB976D;
                border-radius: 10px; box-shadow: 0 8px 28px rgba(23,36,48,.14);
                font-size: 14px; line-height: 1.45; pointer-events: auto;
                transform: translateX(16px); opacity: 0;
                transition: transform .22s ease-out, opacity .22s ease-out;
            }
            .pg-toast.pg-in     { transform: translateX(0); opacity: 1; }
            .pg-toast.pg-out    { transform: translateX(16px); opacity: 0; }
            .pg-toast--success  { border-left-color: #1F7A4C; }
            .pg-toast--error    { border-left-color: #C62828; }
            .pg-toast__icon     { flex: 0 0 auto; width: 18px; height: 18px; margin-top: 1px; }
            .pg-toast--success .pg-toast__icon { color: #1F7A4C; }
            .pg-toast--error   .pg-toast__icon { color: #C62828; }
            .pg-toast--info    .pg-toast__icon { color: #8A6A3F; }
            .pg-toast__msg      { flex: 1 1 auto; }
            .pg-toast__close {
                flex: 0 0 auto; width: 28px; height: 28px; margin: -4px -4px 0 0;
                display: inline-flex; align-items: center; justify-content: center;
                background: none; border: 0; border-radius: 6px;
                color: #6B6560; cursor: pointer;
            }
            .pg-toast__close:hover { color: #172430; background: #F6F6F6; }
            .pg-toast__close:focus-visible { outline: 2px solid #BB976D; outline-offset: 1px; }

            .dark .pg-toast { background: #172430; color: #fff; border-color: #2F3B45; }
            .dark .pg-toast__close { color: #DBDBDB; }
            .dark .pg-toast__close:hover { color: #fff; background: rgba(255,255,255,.08); }

            @media (max-width: 640px) {
                .pg-toasts { top: auto; bottom: 16px; right: 16px; left: 16px; }
                .pg-toast  { min-width: 0; max-width: none; }
            }
            @media (prefers-reduced-motion: reduce) {
                .pg-toast { transition: none; transform: none; }
            }

            /* ── Quick View ─────────────────────────────────────────── */
            .pg-qv[hidden] { display: none; }
            .pg-qv {
                position: fixed; inset: 0; z-index: 99999990;
                display: flex; align-items: center; justify-content: center; padding: 20px;
            }
            .pg-qv__backdrop { position: absolute; inset: 0; background: rgba(23,36,48,.6); }
            .pg-qv__panel {
                position: relative; width: 100%; max-width: 820px;
                max-height: calc(100vh - 40px); overflow-y: auto;
                background: #fff; border-radius: 16px;
                box-shadow: 0 24px 60px rgba(0,0,0,.28);
            }
            .dark .pg-qv__panel { background: #172430; color: #fff; }
            .pg-qv__close {
                position: absolute; top: 12px; right: 12px; z-index: 2;
                width: 44px; height: 44px; display: inline-flex;
                align-items: center; justify-content: center;
                background: #fff; border: 1px solid #E8E1D7; border-radius: 50%;
                color: #172430; cursor: pointer;
            }
            .pg-qv__close:hover { border-color: #BB976D; color: #BB976D; }
            .pg-qv__close:focus-visible { outline: 2px solid #BB976D; outline-offset: 2px; }
            .dark .pg-qv__close { background: #172430; border-color: #2F3B45; color: #fff; }
            .pg-qv__loading { padding: 60px; text-align: center; color: #6B6560; font-size: 14px; }
            body.pg-noscroll { overflow: hidden; }

            /* ── Product card actions ───────────────────────────────── */
            /* Touch devices have no hover, so a hover-revealed action stack is simply
               unreachable — wishlist, cart and quick view become dead on mobile.
               Visible by default; reveal-on-hover is a pointer-device enhancement.
               --slide keeps the original vertical nudge on the stacks that had one. */
            .pg-card-actions        { opacity: 1; }
            .pg-card-actions--slide { transform: translateY(-50%); }

            @media (hover: hover) and (pointer: fine) {
                .pg-card-actions {
                    opacity: 0;
                    transition: opacity .3s ease, transform .3s ease;
                }
                .group:hover .pg-card-actions,
                .group:focus-within .pg-card-actions {   /* keyboard users get it too */
                    opacity: 1;
                }
                .pg-card-actions--slide { transform: translateY(-40%); }
                .group:hover .pg-card-actions--slide,
                .group:focus-within .pg-card-actions--slide { transform: translateY(-50%); }
            }
            @media (prefers-reduced-motion: reduce) {
                .pg-card-actions { transition: none; }
            }

            /* ── Compact card action icons (wishlist / cart / quick view) ─ */
            .pg-ico-btn {
                width: 44px; height: 44px; border-radius: 50%;
                display: inline-flex; align-items: center; justify-content: center;
                background: rgba(255,255,255,.92); color: #172430; border: 0; cursor: pointer;
                box-shadow: 0 4px 14px rgba(0,0,0,.14);
                transition: background .2s ease, color .2s ease, transform .15s ease;
            }
            .pg-ico-btn:hover  { background: #BB976D; color: #fff; }
            .pg-ico-btn:active { transform: scale(.93); }
            .pg-ico-btn:focus-visible { outline: 2px solid #BB976D; outline-offset: 2px; }
            .pg-ico-btn svg { width: auto; height: 18px; }   /* height-only keeps each icon's aspect */
            .dark .pg-ico-btn { background: #172430; color: #fff; }
            .dark .pg-ico-btn:hover { background: #BB976D; color: #fff; }
            .pg-ico-btn.wishlist-active { color: #E13939; }
            /* Self-contained visually-hidden label (independent of style.css .sr-only) */
            .pg-ico-sr {
                position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
                overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
            }

            /* ── Added-to-cart confirmation ─────────────────────────── */
            .pg-atc[hidden] { display: none; }
            .pg-atc {
                position: fixed; inset: 0; z-index: 99999995;
                display: flex; align-items: center; justify-content: center; padding: 20px;
            }
            .pg-atc__backdrop {
                position: absolute; inset: 0; background: rgba(23,36,48,.6);
                opacity: 0; transition: opacity .25s ease;
            }
            .pg-atc.is-open .pg-atc__backdrop { opacity: 1; }
            .pg-atc__panel {
                position: relative; width: 100%; max-width: 420px;
                max-height: calc(100vh - 40px); overflow-y: auto;
                background: #fff; border-radius: 18px; padding: 24px 22px 20px;
                box-shadow: 0 24px 60px rgba(0,0,0,.28);
                transform: translateY(14px) scale(.96); opacity: 0;
                transition: transform .28s cubic-bezier(.22,.68,0,1.1), opacity .2s ease;
            }
            .pg-atc.is-open .pg-atc__panel { transform: none; opacity: 1; }
            .dark .pg-atc__panel { background: #172430; color: #fff; }
            .pg-atc__close {
                position: absolute; top: 12px; right: 12px; z-index: 2;
                width: 40px; height: 40px; display: inline-flex;
                align-items: center; justify-content: center;
                background: transparent; border: 0; border-radius: 50%;
                color: #6B6560; cursor: pointer;
            }
            .pg-atc__close:hover { color: #172430; background: rgba(0,0,0,.05); }
            .pg-atc__close:focus-visible { outline: 2px solid #BB976D; outline-offset: 2px; }
            .dark .pg-atc__close { color: #A9B2BB; }
            .dark .pg-atc__close:hover { color: #fff; background: rgba(255,255,255,.08); }
            .pg-atc__head { display: flex; align-items: center; gap: 10px; padding-right: 32px; }
            .pg-atc__check {
                flex: none; width: 34px; height: 34px; border-radius: 50%;
                display: inline-flex; align-items: center; justify-content: center;
                background: #E7F5EE; color: #1CB28E;
            }
            .dark .pg-atc__check { background: rgba(28,178,142,.15); }
            .pg-atc__title { font-size: 17px; font-weight: 700; line-height: 1.2; color: #172430; }
            .dark .pg-atc__title { color: #fff; }
            .pg-atc__row {
                display: flex; gap: 14px; align-items: center;
                margin-top: 18px; padding: 12px; border-radius: 12px; background: #FAF7F2;
            }
            .dark .pg-atc__row { background: rgba(255,255,255,.05); }
            .pg-atc__thumb {
                flex: none; width: 62px; height: 62px; border-radius: 10px;
                object-fit: cover; background: #ece7e0;
            }
            .pg-atc__name { font-weight: 600; font-size: 14px; line-height: 1.35; color: #172430; }
            .dark .pg-atc__name { color: #fff; }
            .pg-atc__meta { font-size: 13px; color: #6B6560; margin-top: 3px; }
            .dark .pg-atc__meta { color: #A9B2BB; }
            .pg-atc__summary {
                display: flex; align-items: center; justify-content: space-between;
                margin-top: 16px; padding-top: 14px; border-top: 1px solid #EEE8DF;
                font-size: 13px; color: #6B6560;
            }
            .dark .pg-atc__summary { border-color: #2F3B45; color: #A9B2BB; }
            .pg-atc__summary strong { color: #172430; font-weight: 700; }
            .dark .pg-atc__summary strong { color: #fff; }
            .pg-atc__actions { display: flex; gap: 10px; margin-top: 18px; }
            .pg-atc__btn {
                flex: 1; min-height: 48px; padding: 0 14px;
                display: inline-flex; align-items: center; justify-content: center; gap: 8px;
                border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer;
                text-decoration: none; text-align: center;
                transition: background .2s ease, border-color .2s ease, color .2s ease;
            }
            .pg-atc__btn--secondary { background: #fff; border: 1px solid #D9D2C7; color: #172430; }
            .pg-atc__btn--secondary:hover { border-color: #BB976D; color: #BB976D; }
            .dark .pg-atc__btn--secondary { background: transparent; border-color: #2F3B45; color: #fff; }
            .dark .pg-atc__btn--secondary:hover { border-color: #BB976D; color: #BB976D; }
            .pg-atc__btn--primary { background: #BB976D; border: 1px solid #BB976D; color: #fff; }
            .pg-atc__btn--primary:hover { background: #a8845a; border-color: #a8845a; }
            .pg-atc__btn:focus-visible { outline: 2px solid #BB976D; outline-offset: 2px; }
            .pg-atc__link {
                display: block; text-align: center; margin-top: 12px;
                font-size: 13px; color: #6B6560; text-decoration: underline;
            }
            .pg-atc__link:hover { color: #BB976D; }
            .dark .pg-atc__link { color: #A9B2BB; }
            .pg-atc-loading { opacity: .65; cursor: progress; pointer-events: none; }
            @media (prefers-reduced-motion: reduce) {
                .pg-atc__panel, .pg-atc__backdrop { transition: none; }
            }
        </style>

        <div id="pg-toasts" class="pg-toasts" role="status" aria-live="polite" aria-atomic="false"></div>

        <div id="pg-qv" class="pg-qv" hidden role="dialog" aria-modal="true" aria-labelledby="pg-qv-title">
            <div class="pg-qv__backdrop" data-pg-qv-close></div>
            <div class="pg-qv__panel">
                <button type="button" class="pg-qv__close" data-pg-qv-close aria-label="Close quick view">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
                <div id="pg-qv-body"><p class="pg-qv__loading">Loading…</p></div>
            </div>
        </div>

        {{-- Added-to-cart confirmation modal (populated by JS on AJAX add) --}}
        <div id="pg-atc" class="pg-atc" hidden role="dialog" aria-modal="true" aria-labelledby="pg-atc-title">
            <div class="pg-atc__backdrop" data-pg-atc-close></div>
            <div class="pg-atc__panel">
                <button type="button" class="pg-atc__close" data-pg-atc-close aria-label="Close">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
                <div class="pg-atc__head">
                    <span class="pg-atc__check" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    <h2 id="pg-atc-title" class="pg-atc__title">Added to your cart</h2>
                </div>
                <div class="pg-atc__row">
                    <img id="pg-atc-thumb" class="pg-atc__thumb" src="" alt="" loading="lazy">
                    <div>
                        <p id="pg-atc-name" class="pg-atc__name"></p>
                        <p id="pg-atc-meta" class="pg-atc__meta"></p>
                    </div>
                </div>
                <div class="pg-atc__summary">
                    <span id="pg-atc-count"></span>
                    <span>Subtotal:&nbsp;<strong id="pg-atc-total"></strong></span>
                </div>
                <div class="pg-atc__actions">
                    <button type="button" class="pg-atc__btn pg-atc__btn--secondary" data-pg-atc-close>Continue Shopping</button>
                    <a href="{{ route('checkout') }}" class="pg-atc__btn pg-atc__btn--primary">
                        Checkout
                        <svg width="15" height="11" viewBox="0 0 24 14" fill="none" aria-hidden="true"><path d="M23.82 6.62 18.38 1.18a.77.77 0 0 0-1.09 1.09l4.13 4.13H.9a.77.77 0 0 0 0 1.54h20.52l-4.13 4.13a.77.77 0 0 0 1.09 1.09l5.44-5.44a.77.77 0 0 0 0-1.1Z" fill="currentColor"/></svg>
                    </a>
                </div>
                <a href="{{ route('cart') }}" class="pg-atc__link">View cart</a>
            </div>
        </div>

        <script>
        (function () {
            /* ── Toasts ─────────────────────────────────────────────── */
            var host = document.getElementById('pg-toasts');

            var ICONS = {
                success: '<path d="M20 6 9 17l-5-5"/>',
                error:   '<circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/>',
                info:    '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>'
            };

            window.showToast = function (message, type) {
                if (!host) return;
                type = ICONS[type] ? type : 'info';

                var el = document.createElement('div');
                el.className = 'pg-toast pg-toast--' + type;
                el.innerHTML =
                    '<svg class="pg-toast__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
                    'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + ICONS[type] + '</svg>' +
                    '<span class="pg-toast__msg"></span>' +
                    '<button type="button" class="pg-toast__close" aria-label="Dismiss notification">' +
                    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
                    'stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>';

                // textContent, not innerHTML — product names are user data
                el.querySelector('.pg-toast__msg').textContent = message;

                var timer;
                function dismiss() {
                    clearTimeout(timer);
                    el.classList.add('pg-out');
                    setTimeout(function () { el.remove(); }, 220);
                }
                el.querySelector('.pg-toast__close').addEventListener('click', dismiss);

                host.appendChild(el);
                requestAnimationFrame(function () { el.classList.add('pg-in'); });
                timer = setTimeout(dismiss, 4000);
            };

            /* ── Quick View ─────────────────────────────────────────── */
            var modal   = document.getElementById('pg-qv');
            var body    = document.getElementById('pg-qv-body');
            var lastFocus = null;

            function closeQv() {
                if (!modal || modal.hidden) return;
                modal.hidden = true;
                document.body.classList.remove('pg-noscroll');
                body.innerHTML = '<p class="pg-qv__loading">Loading…</p>';
                if (lastFocus) lastFocus.focus();          // focus returns where it came from
            }

            function openQv(url, trigger) {
                if (!modal) return;
                lastFocus = trigger || null;
                modal.hidden = false;
                document.body.classList.add('pg-noscroll');
                modal.querySelector('.pg-qv__close').focus();

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (r) {
                        if (!r.ok) throw new Error('bad status');
                        return r.text();
                    })
                    .then(function (html) { body.innerHTML = html; })
                    .catch(function () {
                        closeQv();
                        window.showToast("Couldn't load that product. Please try again.", 'error');
                    });
            }

            document.addEventListener('click', function (e) {
                // .pg-qv-btn-trigger, not .pg-qv-btn — the latter styles the buttons
                // *inside* the panel, and matching it would hijack Add to Cart.
                var open = e.target.closest('.pg-qv-btn-trigger');
                if (open) {
                    e.preventDefault();
                    openQv(open.dataset.qvUrl, open);
                    return;
                }
                if (e.target.closest('[data-pg-qv-close]')) closeQv();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeQv();
            });
        })();
        </script>

        {{-- Added-to-cart: intercept cart.add forms, POST via fetch, show the modal.
             The <form> still works with JS off — this only enhances it. --}}
        <script>
        (function () {
            var modal = document.getElementById('pg-atc');
            if (!modal) return;

            var panel  = modal.querySelector('.pg-atc__panel');
            var elName = document.getElementById('pg-atc-name');
            var elMeta = document.getElementById('pg-atc-meta');
            var elThumb= document.getElementById('pg-atc-thumb');
            var elCount= document.getElementById('pg-atc-count');
            var elTotal= document.getElementById('pg-atc-total');
            var csrf   = document.querySelector('meta[name="csrf-token"]')?.content || '';
            var lastFocus = null;

            function updateBadges(count) {
                document.querySelectorAll('.js-cart-count').forEach(function (b) { b.textContent = count; });
            }

            function openModal(data) {
                var p = data.product || {}, c = data.cart || {};
                elThumb.src = p.image || '';
                elThumb.alt = p.name || '';
                elName.textContent = p.name || '';
                var qtyTxt = p.qty ? ('Qty ' + p.qty) : '';
                elMeta.textContent = [qtyTxt, p.price].filter(Boolean).join('  ·  ');
                var n = c.count || 0;
                elCount.textContent = n + (n === 1 ? ' item in cart' : ' items in cart');
                elTotal.textContent = c.total || '';

                modal.hidden = false;
                document.body.classList.add('pg-noscroll');
                requestAnimationFrame(function () { modal.classList.add('is-open'); });
                var primary = modal.querySelector('.pg-atc__btn--primary');
                if (primary) primary.focus();
            }

            function closeModal() {
                if (modal.hidden) return;
                modal.classList.remove('is-open');
                document.body.classList.remove('pg-noscroll');
                var done = function () { modal.hidden = true; panel.removeEventListener('transitionend', done); };
                panel.addEventListener('transitionend', done);
                setTimeout(done, 340); // fallback when motion is reduced / no transition fires
                if (lastFocus) { try { lastFocus.focus(); } catch (e) {} }
            }

            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                if (!/\/cart\/add\/?$/.test(form.getAttribute('action') || '')) return;
                // Opt-out: the cart page's cross-sell adds do a normal POST so the
                // cart list itself refreshes, rather than only popping the modal.
                if (form.hasAttribute('data-full-submit')) return;

                e.preventDefault();
                var btn = form.querySelector('[type="submit"]');
                lastFocus = btn || null;
                if (btn) { btn.disabled = true; btn.classList.add('pg-atc-loading'); }

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
                .then(function (res) {
                    var d = res.data || {};
                    if (d.cart && typeof d.cart.count !== 'undefined') updateBadges(d.cart.count);

                    if (d.status === 'error') {                 // nothing added (already at stock max)
                        window.showToast?.(d.message || "Couldn't add to cart.", 'error');
                        return;
                    }
                    if (d.status === 'partial') {               // added fewer than asked
                        window.showToast?.(d.message, 'info');
                    }
                    openModal(d);
                })
                .catch(function () {
                    window.showToast?.("Couldn't add to cart. Please try again.", 'error');
                })
                .finally(function () {
                    if (btn) { btn.disabled = false; btn.classList.remove('pg-atc-loading'); }
                });
            });

            modal.addEventListener('click', function (e) {
                if (e.target.closest('[data-pg-atc-close]')) { e.preventDefault(); closeModal(); }
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.hidden) closeModal();
            });
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
