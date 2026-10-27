<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  <meta name="description" content="@yield('description')">
  <link rel="canonical" href="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Peyton Ghalib">
  <meta property="og:title" content="@yield('title')">
  <meta property="og:description" content="@yield('description')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta name="twitter:card" content="summary">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/pg.css') }}">
</head>
<body>
  <header class="pg-top"><div class="pg-top__in pg-wide">
    <a href="{{ url('/') }}" class="pg-logo" aria-label="Peyton Ghalib home">PG</a>
    <a href="{{ route('seller.landing') }}" class="pg-brand"><b>Peyton Ghalib</b><small>SELLER CENTER</small></a>
    <nav class="pg-nav" aria-label="Seller">
      <a href="{{ route('seller.pricing') }}">Pricing</a>
      <a href="{{ route('login') }}">Sign in</a>
      <a href="{{ route('seller.signup') }}" class="pg-btn sm">Start selling</a>
    </nav>
  </div></header>

  @yield('content')

  <footer class="pg-foot"><div class="pg-wide">
    <b>Peyton Ghalib LLC</b>
    <span>200 Orient Ave STE 2B, Jersey City, NJ 07305</span>
    <span><a href="mailto:info@peytonghalib.com">info@peytonghalib.com</a> · <a href="tel:+19294699864">+1 (929) 469-9864</a></span>
    <span>US-registered businesses only.</span>
  </div></footer>
</body>
</html>
