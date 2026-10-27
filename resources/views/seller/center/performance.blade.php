@extends('seller.center.layout')
@section('title', 'Performance')

@section('content')
@php $card = 'background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px'; @endphp

<div class="sc-kpis">
  @foreach($perfKpis as $k)
    <div class="sc-kpi"><span>{{ $k['h'] }}</span><b>{{ $k['v'] }}</b><small>{{ $k['s'] }}</small></div>
  @endforeach
</div>

<div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  <div style="flex:1 1 380px;min-width:0;{{ $card }}">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px"><b style="font-size:16px">Account health</b>
      <span style="font-size:13px;font-weight:700;color:{{ $allGood ? '#15803D' : '#B91C1C' }}">{{ $allGood ? 'Good standing' : 'Needs attention' }}</span></div>
    @foreach($health as $h)
      <div style="display:flex;flex-direction:column;gap:6px">
        <div style="display:flex;justify-content:space-between;font-size:14px;gap:8px"><span>{{ $h['t'] }}</span><span><b>{{ $h['v'] }}</b> <span style="color:#6B7280;font-size:12px">target {{ $h['target'] }}</span></span></div>
        <div style="height:6px;border-radius:3px;background:#E5E7EB"><div style="height:6px;border-radius:3px;background:{{ $h['color'] }};width:{{ $h['pct'] }}"></div></div>
        <span style="font-size:12px;font-weight:700;color:{{ $h['color'] }}">{{ $h['status'] }}</span>
      </div>
    @endforeach
    <span style="font-size:12px;color:#6B7280;line-height:1.5">Based on paid orders from the last {{ \App\Support\SellerInsights::WINDOW_DAYS }} days. Shipping is on time when tracking is added within your processing time.</span>
  </div>

  <div style="flex:1 1 320px;min-width:0;{{ $card }}">
    <b style="font-size:16px">Rating breakdown</b>
    <div style="display:flex;align-items:baseline;gap:10px"><b style="font-size:36px;font-weight:800">{{ $avg ?? '—' }}</b><span style="color:#F59E0B">★★★★★</span><span style="font-size:13px;color:#4B5563">{{ $ratingCount }} {{ \Illuminate\Support\Str::plural('review', $ratingCount) }}</span></div>
    @foreach($rateBars as $b)
      <div style="display:flex;align-items:center;gap:10px;font-size:13px"><span style="width:30px">{{ $b['s'] }} ★</span><div style="flex:1;height:8px;border-radius:4px;background:#E5E7EB"><div style="height:8px;border-radius:4px;background:#F59E0B;width:{{ $b['pct'] }}"></div></div><span style="width:36px;text-align:right;color:#4B5563">{{ $b['pct'] }}</span></div>
    @endforeach
  </div>
</div>

<div style="{{ $card }}">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
    <b style="font-size:16px">Customer reviews</b>
    <div class="sc-tabs">@foreach($revTabs as $t)<a href="{{ route('seller.performance', $t['k'] ? ['filter' => $t['k']] : []) }}" class="{{ $filter === $t['k'] ? 'on' : '' }}">{{ $t['label'] }}</a>@endforeach</div>
  </div>
  @forelse($reviews as $r)
    <div style="border-top:1px solid #F3F4F6;padding-top:12px;display:flex;flex-direction:column;gap:6px">
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;font-size:13px;color:#4B5563"><span style="color:#F59E0B">{{ str_repeat('★', (int) $r->rating) }}{{ str_repeat('☆', 5 - (int) $r->rating) }}</span><b style="color:#111827">{{ $r->author_name }}</b><span>· {{ $r->created_at->format('M j, Y') }}</span></div>
      <span style="font-size:12px;color:#6B7280">{{ $r->product?->name }}{{ $r->is_verified ? ' · Verified purchase' : '' }}</span>
      <p style="margin:0;font-size:14px;line-height:1.55;color:#374151">{{ $r->comment }}</p>
      @if($r->seller_reply)
        <div style="padding:10px 12px;border-radius:12px;background:#F9FAFB;font-size:13px;color:#374151"><b>Your public reply:</b> {{ $r->seller_reply }}</div>
      @else
        <form method="POST" action="{{ route('seller.reviews.reply', $r->id) }}" data-once style="display:flex;flex-direction:column;gap:8px">
          @csrf
          <textarea name="reply" rows="2" required minlength="2" maxlength="1000" placeholder="Thank the buyer or explain how you've fixed the issue…" style="border-radius:12px;border:1px solid #D1D5DB;padding:10px 12px;font-size:15px;font-family:inherit"></textarea>
          <button type="submit" style="align-self:flex-start;height:40px;padding:0 18px;border-radius:999px;border:1.5px solid #111827;background:#fff;font-weight:700;font-size:13px;cursor:pointer">Post reply</button>
        </form>
      @endif
    </div>
  @empty
    <div style="padding:16px;border-radius:14px;background:#F9FAFB;font-size:14px;color:#4B5563">{{ $filter ? 'No reviews here — nice work.' : 'No reviews yet. They appear here once buyers review your products.' }}</div>
  @endforelse
</div>

<div style="{{ $card }}">
  <div style="display:flex;justify-content:space-between"><b style="font-size:16px">Refunds</b><span style="font-size:12px;color:#6B7280">Last 30 days</span></div>
  @forelse($refunds as $r)
    <div style="display:flex;justify-content:space-between;gap:12px;font-size:14px;border-top:1px solid #F3F4F6;padding-top:10px"><span><b>{{ $r['amt'] }} · {{ $r['reason'] }}</b><br><span style="font-size:12px;color:#6B7280">{{ $r['no'] }} · {{ $r['d'] }}</span></span><span style="color:#4B5563">Refunded</span></div>
  @empty
    <span style="font-size:14px;color:#4B5563">No refunds in the last 30 days.</span>
  @endforelse
</div>
@endsection
