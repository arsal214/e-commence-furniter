@extends('seller.center.layout')
@section('title', 'Referrals')

@section('content')
<div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px">
  <b style="font-size:20px">Invite a seller, get a month free</b>
  <span style="font-size:14px;color:#4B5563;line-height:1.55">When a business you invite is approved and pays its first month, you get one month free ({{ $price }} credit on your next bill). Up to {{ $max }} free months a year — {{ $used }} used so far this year.</span>
  <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <span id="ref-link" style="flex:1 1 280px;min-width:0;padding:12px 14px;border-radius:12px;background:#F3F4F6;font-size:14px;font-weight:600;word-break:break-all">{{ $refLink }}</span>
    <button type="button" data-act="copyRef" data-copy="{{ $refLink }}" style="height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer">Copy link</button>
  </div>
  <a href="{{ url('/referral-terms') }}" style="font-size:13px;font-weight:700">Referral program terms</a>
</div>

<div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;overflow:hidden">
  @forelse($refs as $r)
    <div style="display:flex;justify-content:space-between;gap:12px;padding:14px 18px;border-bottom:1px solid #F3F4F6;font-size:14px;align-items:center">
      <span style="display:flex;flex-direction:column;gap:2px"><b>{{ $r['n'] }}</b><span style="font-size:12px;color:#6B7280">{{ $r['s'] }}</span></span><b>{{ $r['r'] }}</b>
    </div>
  @empty
    <p style="margin:0;padding:20px;color:#4B5563;font-size:14px">Nobody has used your link yet. Share it with a business that should sell here.</p>
  @endforelse
</div>
@endsection
