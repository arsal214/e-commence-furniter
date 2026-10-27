@extends('seller.onboarding.layout', ['step' => 2])
@section('title', 'Your seller plan')

@section('content')
<span class="pg-ok" style="color:var(--pg-ok);font-weight:700;font-size:13px">✓ Email verified</span>
<h1>Your seller plan</h1>

<div class="pg-plan">
  <b>Seller plan</b>
  <span><b class="price">$19.99</b> <span class="pg-muted">/ month</span></span>
  <b style="color:var(--pg-brand-dark)">0% commission on everything you sell</b>
  <span class="pg-muted">Unlimited listings, variants, daily payouts, coupons, analytics and support. Card processing fees apply per order (2.9% + $0.30) and are taken from the order before payout.</span>
</div>

<p class="pg-muted">You'll pay securely on Stripe. Your store goes live once our team approves your documents. If a payment fails, your store is hidden until it's paid.</p>

@if($seller->subscription_status === 'active')
  <div class="pg-alert ok">Your plan is already active.</div>
@endif

<form method="POST" action="{{ route('seller.plan.subscribe') }}" data-once>
  @csrf
  <button class="pg-btn" type="submit">{{ $seller->subscription_status === 'active' ? 'Continue' : 'Subscribe · $19.99/month' }}</button>
</form>
<p class="pg-muted" style="text-align:center">Secure payment by Stripe</p>
@endsection
