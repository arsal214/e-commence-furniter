@php $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:10px'; @endphp
<div style="{{ $card }}">
  <b style="font-size:16px">Start a claim</b>
  <span style="font-size:14px;color:#4B5563;line-height:1.5">If an order didn't arrive, arrived damaged or isn't as described, open its tracking page and choose "Report a problem". Each seller's return window is shown on their product pages.</span>
  @forelse($claimOrders as $o)
    <div style="display:flex;justify-content:space-between;gap:10px;align-items:center;font-size:14px;border-top:1px solid #F3F4F6;padding-top:8px"><span><b>{{ $o['store'] }}</b> · {{ $o['no'] }} <span style="color:#6B7280">· {{ $o['status'] }}</span></span><a href="{{ $o['url'] }}" style="font-weight:700">Open</a></div>
  @empty
    <span style="font-size:14px;color:#4B5563">You have no orders yet.</span>
  @endforelse
</div>
@forelse($claims as $c)
  <div style="{{ $card }}">
    <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap"><b>{{ $c['reason'] }} · {{ $c['store'] }}</b><span style="height:26px;padding:0 10px;border-radius:999px;font-size:12px;font-weight:800;display:flex;align-items:center;background:{{ $c['done'] ? '#ECFDF3' : '#FEF3C7' }};color:{{ $c['done'] ? '#15803D' : '#92400E' }}">{{ $c['status'] }}</span></div>
    <span style="font-size:13px;color:#4B5563">Order {{ $c['no'] }} · {{ $c['amount'] }} · opened {{ $c['when'] }}</span>
    @if($c['note'])<span style="font-size:13px;color:#374151;line-height:1.5">{{ $c['note'] }}</span>@endif
  </div>
@empty
  <span style="font-size:14px;color:#4B5563">You haven't opened any claims.</span>
@endforelse
