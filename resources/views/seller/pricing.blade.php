@extends('seller.layout')
@section('title', 'Seller Pricing | One Plan, $19.99/Month, 0% Commission | Peyton Ghalib')
@section('description', 'One simple seller plan on Peyton Ghalib: $19.99 a month, 0% commission, unlimited products and daily payouts. No setup or listing fees.')

@section('content')
<section class="pg-sec"><div class="pg-wide" style="max-width:560px">
  <span class="pg-eyebrow">ONE PLAN. NO COMMISSION.</span>
  <h1 style="margin:8px 0 20px">Simple pricing for every seller</h1>

  <div class="pg-plan">
    <div style="display:flex;justify-content:space-between;align-items:center"><b>Seller plan</b><span class="pg-muted">0% commission</span></div>
    <div><b class="price">${{ number_format($price, 2) }}</b> <span class="pg-muted">/ month</span></div>
    <span class="pg-muted">Billed monthly in USD. Cancel any time. No setup fees, no listing fees.</span>
    @foreach($includes as $item)<span class="pg-check">✓ {{ $item }}</span>@endforeach
    <a class="pg-btn" href="{{ route('seller.signup') }}" style="margin-top:8px">Start selling</a>
    <span class="pg-muted">Card processing (2.9% + $0.30 per order) is deducted from each order before payout. If a monthly payment fails, your store is hidden from shoppers until it goes through. Everything is restored automatically.</span>
  </div>
</div></section>
@endsection
