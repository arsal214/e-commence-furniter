@extends('layouts.pg')

@section('title', 'Unsubscribed | Peyton Ghalib')
@section('robots', 'noindex, nofollow')

@section('content')
<div style="max-width:640px;margin:0 auto;padding:56px clamp(16px,4vw,40px) 72px;display:flex;flex-direction:column;gap:18px;align-items:flex-start">
  <span aria-hidden="true" style="width:64px;height:64px;border-radius:50%;background:#ECFDF3;color:#15803D;display:flex;align-items:center;justify-content:center;font-size:30px;font-weight:800">✓</span>
  <h1 style="margin:0;font-size:clamp(26px,4vw,36px);font-weight:800;letter-spacing:-.03em">You're off our offers list</h1>
  <p style="margin:0;font-size:16px;line-height:1.6;color:#374151">
    @if($email)<b>{{ $email }}</b> will no longer receive deals and promotional emails from us.@else You will no longer receive deals and promotional emails from us.@endif
    You'll still get order confirmations, shipping updates and anything else about a purchase you've made — those aren't marketing.
  </p>
  <p style="margin:0;font-size:14px;color:#4B5563">Changed your mind? Turn deals back on under Account › Notifications, or subscribe again from the box at the bottom of any page.</p>
  <a href="{{ url('/') }}" style="height:50px;padding:0 26px;border-radius:999px;background:#C2410C;color:#FFFFFF;display:flex;align-items:center;font-weight:800">Back to Peyton Ghalib</a>
</div>
@endsection
