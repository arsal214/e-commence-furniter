@extends('seller.layout')
@section('title', 'Sell on Peyton Ghalib | 0% Commission, $19.99/Month')
@section('description', 'Open your US online store on Peyton Ghalib. Pay one flat $19.99 a month, keep 100% of every sale, and get paid daily to your US bank.')

@section('content')
<section class="pg-hero"><div class="pg-wide pg-hero__in">
  <div>
    <span class="pg-eyebrow">FOR US BUSINESSES</span>
    <h1>Sell to American shoppers. Keep 100% of every sale.</h1>
    <p>0% commission. One flat plan of ${{ number_format($price, 2) }} a month. Daily payouts to your US bank for delivered orders.</p>
    <div class="pg-actions">
      <a class="pg-btn" href="{{ route('seller.signup') }}">Open your store</a>
      <a class="pg-btn ghost" href="{{ route('seller.pricing') }}">See pricing</a>
    </div>
    <small>Billed monthly · cancel any time · US-registered businesses only</small>
  </div>

  <div class="pg-calc" data-calc data-fee="{{ $price }}">
    <b>See what you keep</b>
    <label>Your monthly sales: <b data-sales>$5,000</b>
      <input type="range" min="500" max="50000" step="500" value="5000" aria-label="Monthly sales">
    </label>
    <div class="pg-calc__rows">
      <div><span>Peyton Ghalib plan</span><b>${{ number_format($price, 2) }}</b></div>
      <div><span>Commission on sales</span><b>$0.00</b></div>
      <div class="total"><b>You keep</b><b data-keep>$4,980.01</b></div>
    </div>
    <p>With a 15% commission you'd pay <b data-comm>$750.00</b> on the same sales, <b data-saved>$730.01</b> more in your pocket every month. Before card processing (2.9% + $0.30 per order).</p>
  </div>
</div></section>

<section class="pg-sec"><div class="pg-wide">
  <h2>Open your store in 6 steps</h2>
  <div class="pg-cards">
    @foreach($steps as $i => [$h, $p])
      <div class="pg-tile"><span class="pg-num">{{ $i + 1 }}</span><b>{{ $h }}</b><span>{{ $p }}</span></div>
    @endforeach
  </div>
</div></section>

<section class="pg-sec alt"><div class="pg-wide">
  <h2>Everything included in one plan</h2>
  <div class="pg-cards">
    @foreach($features as [$h, $p])
      <div class="pg-tile"><b>{{ $h }}</b><span>{{ $p }}</span></div>
    @endforeach
  </div>
</div></section>

<section class="pg-sec"><div class="pg-wide">
  <h2>Grow your level, unlock more</h2>
  <p class="pg-muted">Levels are based on your total delivered sales. Every seller also earns reward points: 1 point per $10 sold, 100 points = $1 off your plan.</p>
  <div class="pg-cards four">
    @foreach($levels as [$name, $min, $perks])
      <div class="pg-tile"><span class="pg-muted">{{ $name }}</span><b class="big">{{ $min }}</b><span class="pg-muted">total sales</span>
        @foreach($perks as $perk)<span class="pg-check">✓ {{ $perk }}</span>@endforeach
      </div>
    @endforeach
  </div>
</div></section>

<section class="pg-sec alt"><div class="pg-wide pg-two">
  <div>
    <h2>What you'll need</h2>
    @foreach($needs as [$h, $p])<p class="pg-check"><span>✓</span><span><b>{{ $h }}</b> · {{ $p }}</span></p>@endforeach
  </div>
  <div>
    <h2>Seller FAQ</h2>
    @foreach($faq as [$q, $a])
      <details class="pg-faq"><summary>{{ $q }}</summary><p>{{ $a }}</p></details>
    @endforeach
  </div>
</div></section>

<section class="pg-sec"><div class="pg-wide pg-cta">
  <div><b>Ready to open your store?</b><span>Most applications are reviewed within 1–2 business days.</span></div>
  <a class="pg-btn" href="{{ route('seller.signup') }}">Start selling · ${{ number_format($price, 2) }}/mo</a>
</div></section>

<script>
  // Calculator is progressive enhancement: the static numbers above are already correct for $5,000.
  (function () {
    var box = document.querySelector('[data-calc]'); if (!box) return;
    var fee = parseFloat(box.dataset.fee), range = box.querySelector('input');
    var money = function (n) { return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };
    range.addEventListener('input', function () {
      var s = +range.value;
      box.querySelector('[data-sales]').textContent = '$' + s.toLocaleString('en-US');
      box.querySelector('[data-keep]').textContent = money(s - fee);
      box.querySelector('[data-comm]').textContent = money(s * 0.15);
      box.querySelector('[data-saved]').textContent = money(s * 0.15 - fee);
    });
  })();
</script>
@endsection
