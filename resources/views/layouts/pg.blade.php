<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php
    $siteTitle = html_entity_decode(trim($__env->yieldContent('title')), ENT_QUOTES) ?: \App\Support\SiteCopy::title('home');
    $siteDesc = html_entity_decode(trim($__env->yieldContent('meta_description')), ENT_QUOTES) ?: \App\Support\SiteCopy::description('home');
  @endphp
  <title>{{ $siteTitle }}</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('assets/logo/logo-mark.svg') }}">
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
  <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
  <meta name="description" content="{{ $siteDesc }}">
  <meta name="robots" content="@yield('robots', 'index, follow')">
  <link rel="canonical" href="@yield('canonical', url()->current())">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:site_name" content="Peyton Ghalib">
  <meta property="og:title" content="{{ $siteTitle }}">
  <meta property="og:description" content="{{ $siteDesc }}">
  <meta property="og:url" content="@yield('canonical', url()->current())">
  <meta property="og:image" content="@yield('og_image', asset('assets/img/logo-og.jpg'))">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $siteTitle }}">
  <meta name="twitter:description" content="{{ $siteDesc }}">
  <meta name="twitter:image" content="@yield('og_image', asset('assets/img/logo-og.jpg'))">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/pg.css') }}?v={{ filemtime(public_path('css/pg.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/market.css') }}?v={{ filemtime(public_path('css/market.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/dc.css') }}?v={{ filemtime(public_path('css/dc.css')) }}">
  @stack('styles')
  @if(request()->cookie('pg_optout') !== '1')@include('includes.tracking')@endif
  @stack('schema')
</head>
<body>
  @include('market.dc.top')

  @if(session('success'))<div class="dc-flash" role="status">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="dc-flash err" role="alert">{{ session('error') }}</div>@endif

  @yield('content')

  @include('market.dc.bottom')

  <script>
    // Browser twins of the server AddToCart events (same price, same product id).
    document.addEventListener('submit', function (e) {
      var f = e.target; if (!f.dataset.track) return;
      var d = JSON.parse(f.dataset.track);
      if (typeof fbq === 'function') fbq('track', 'AddToCart', { content_type: 'product', content_ids: [String(d.id)], content_name: d.name, value: d.price, currency: 'USD' });
      if (typeof gtag === 'function') gtag('event', 'add_to_cart', { currency: 'USD', value: d.price, items: [{ item_id: String(d.id), item_name: d.name, price: d.price, quantity: 1 }] });
    });
    // One tap, one request: a second tap on a slow connection must not resubmit a form.
    document.addEventListener('submit', function (e) {
      var f = e.target; if (f.dataset.sent) { e.preventDefault(); return; }
      if (/^post$/i.test(f.method)) { f.dataset.sent = '1'; setTimeout(function () { delete f.dataset.sent; }, 8000); }
    });
  </script>
  <script src="{{ asset('js/market.js') }}?v={{ filemtime(public_path('js/market.js')) }}" defer></script>
  @include('includes.welcome-offer')
  @stack('scripts')
</body>
</html>
