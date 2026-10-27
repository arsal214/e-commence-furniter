<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') | Seller Center</title>
  <meta name="robots" content="noindex">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/pg.css') }}?v={{ filemtime(public_path('css/pg.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/dc.css') }}?v={{ filemtime(public_path('css/dc.css')) }}">
</head>
<body>
  @include('seller.dc.top')

  @if($seller->status === 'info_requested')
    <div class="pg-alert" style="background:#FEF3C7;color:#78350F"><b>We need more information.</b> {{ $seller->admin_note }}
      <span style="display:flex;flex-wrap:wrap;gap:10px;margin-top:8px">
        <a href="{{ route('seller.business') }}"><b>Update business details</b></a>
        <a href="{{ route('seller.owner') }}"><b>Update owner details</b></a>
        <form method="POST" action="{{ route('seller.resubmit') }}" data-once>@csrf<button class="pg-btn sm" type="submit">Resubmit for review</button></form>
      </span></div>
  @elseif($seller->status === 'rejected')
    <div class="pg-alert err"><b>Your application was not approved.</b> {{ $seller->admin_note }} Contact info@peytonghalib.com if you think this is a mistake.</div>
  @endif
  @if(session('status'))<div class="pg-alert ok" role="status">{{ session('status') }}</div>@endif
  @if(session('error'))<div class="pg-alert err" role="alert">{{ session('error') }}</div>@endif
  @if($errors->any())<div class="pg-alert err" role="alert">{{ $errors->first() }}</div>@endif

  @yield('content')

  @include('seller.dc.bottom')

  <script>
    document.querySelectorAll('form[data-once]').forEach(function (f) {
      f.addEventListener('submit', function () { var b = f.querySelector('button[type=submit]'); if (b) b.disabled = true; });
    });
  </script>
  <script src="{{ asset('js/market.js') }}?v={{ filemtime(public_path('js/market.js')) }}" defer></script>
</body>
</html>
