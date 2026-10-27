@php $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:4px'; @endphp
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;border-radius:20px;background:#FFF7ED;padding:20px">
  <span style="display:flex;flex-direction:column;gap:2px"><span style="font-size:11px;font-weight:800;letter-spacing:.12em;color:#9A3412">YOUR POINTS</span><b style="font-size:36px;font-weight:800">{{ number_format($points) }}</b><span style="font-size:14px;color:#7C2D12">Worth {{ $pointsWorth }} at checkout</span></span>
  <a href="{{ url('/cart') }}" style="height:46px;padding:0 22px;border-radius:999px;background:#C2410C;color:#fff;display:flex;align-items:center;font-weight:800">Use at checkout</a>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px">
  <div style="{{ $card }}"><b>1 point per $1</b><span style="font-size:13px;color:#4B5563">On product subtotal after discounts, posted when your order is delivered</span></div>
  <div style="{{ $card }}"><b>100 points = $1 off</b><span style="font-size:13px;color:#4B5563">Redeem at checkout in 100-point steps</span></div>
  <div style="{{ $card }}"><b>12 months</b><span style="font-size:13px;color:#4B5563">Points expire after 12 months without any points activity</span></div>
</div>
<div style="border:1px solid #E5E7EB;border-radius:20px;overflow:hidden">
  @forelse($pointsHistory as $r)
    <div style="display:flex;justify-content:space-between;gap:12px;padding:14px 18px;border-bottom:1px solid #F3F4F6;font-size:14px"><span style="display:flex;flex-direction:column"><b>{{ $r['t'] }}</b><span style="font-size:12px;color:#6B7280">{{ $r['d'] }}</span></span><b style="color:{{ str_starts_with($r['v'], '+') ? '#15803D' : '#111827' }}">{{ $r['v'] }}</b></div>
  @empty
    <p style="margin:0;padding:18px;font-size:14px;color:#4B5563">No points yet. You earn them when an order is delivered.</p>
  @endforelse
</div>
<a href="{{ url('/rewards-terms') }}" style="font-size:13px;font-weight:700">Rewards terms</a>
