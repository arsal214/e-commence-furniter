<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Sell on Peyton Ghalib') | Peyton Ghalib</title>
  <meta name="robots" content="noindex">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/pg.css') }}">
</head>
<body>
  <header class="pg-top"><div class="pg-top__in">
    <a href="{{ url('/') }}" class="pg-logo" aria-label="Peyton Ghalib">PG</a>
    <span class="pg-brand"><b>Peyton Ghalib</b><small>SELLER CENTER</small></span>
  </div></header>

  <main class="pg-wrap">
    @isset($step)
      <div class="pg-steps" aria-label="Step {{ $step }} of 6">
        @foreach(range(1, 6) as $i)<i class="{{ $i <= $step ? 'on' : '' }}"></i>@endforeach
      </div>
    @endisset
    <div class="pg-card">
      @if(session('error'))<div class="pg-alert err" role="alert">{{ session('error') }}</div>@endif
      @if(session('status'))<div class="pg-alert ok" role="status">{{ session('status') }}</div>@endif
      @yield('content')
    </div>
  </main>

  <script>
    // One tap, one request: a second tap on a slow connection must not resubmit.
    document.querySelectorAll('form[data-once]').forEach(function (f) {
      f.addEventListener('submit', function () {
        var b = f.querySelector('button[type=submit]');
        if (b) { b.disabled = true; }
      });
    });
  </script>
</body>
</html>
