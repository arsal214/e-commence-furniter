@extends('seller.center.layout')
@section('title', 'Marketing')

@section('content')
@php
  $card = 'background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px';
  $input = 'height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:15px;min-width:0;width:100%';
  $primary = 'align-self:flex-start;height:46px;padding:0 24px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer';
  $budget = old('budget', number_format((float) $seller->ads_daily_budget, 0, '.', ''));
@endphp

<div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  {{-- Coupons --}}
  <div style="flex:1 1 440px;min-width:0;{{ $card }}">
    <b style="font-size:16px">Create a coupon</b>
    <form method="POST" action="{{ route('seller.marketing.coupons.store') }}" data-once style="display:flex;flex-direction:column;gap:12px">
      @csrf
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px">
        <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Code<input name="code" value="{{ old('code') }}" required minlength="3" maxlength="30" placeholder="WELCOME10" style="{{ $input }};text-transform:uppercase"></label>
        <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Discount
          <select name="type" style="{{ $input }};background:#fff"><option value="percent" @selected(old('type') !== 'amount')>% off</option><option value="amount" @selected(old('type') === 'amount')>$ off</option></select></label>
        <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Amount<input name="value" value="{{ old('value') }}" required type="number" step="0.01" min="0.5" inputmode="decimal" placeholder="10" style="{{ $input }}"></label>
        <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Min. order ($)<input name="min_order" value="{{ old('min_order') }}" type="number" step="0.01" min="0" inputmode="decimal" placeholder="0" style="{{ $input }}"></label>
        <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Ends (optional)<input name="ends_at" value="{{ old('ends_at') }}" type="date" style="{{ $input }}"></label>
        <label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Max uses (optional)<input name="max_uses" value="{{ old('max_uses') }}" type="number" min="1" inputmode="numeric" style="{{ $input }}"></label>
      </div>
      <button type="submit" style="{{ $primary }}">Create coupon</button>
    </form>
    <span style="font-size:12px;color:#6B7280;line-height:1.5">A coupon only discounts items from your store, and you fund it: the discount comes out of your payout for that order.</span>
    @foreach($coupons as $c)
      <div style="display:flex;justify-content:space-between;gap:10px;align-items:center;border-top:1px solid #F3F4F6;padding-top:10px;font-size:14px">
        <span style="display:flex;flex-direction:column;gap:2px"><b>{{ $c->code }}</b>
          <span style="font-size:12px;color:#6B7280">{{ $c->label() }}{{ (float) $c->min_order > 0 ? ' · min $' . number_format((float) $c->min_order, 2) : '' }} · {{ $c->ends_at ? 'ends ' . $c->ends_at->format('M j, Y') : 'no end date' }}</span></span>
        <span style="display:flex;gap:10px;align-items:center"><span style="font-size:13px;color:#4B5563">{{ $c->uses }}{{ $c->max_uses ? '/' . $c->max_uses : '' }} used</span>
          <form method="POST" action="{{ route('seller.marketing.coupons.toggle', $c->id) }}">@csrf<button type="submit" style="height:34px;padding:0 14px;border-radius:999px;border:1.5px solid {{ $c->isUsable() ? '#15803D' : '#9CA3AF' }};background:#fff;font-weight:700;font-size:12px;cursor:pointer;color:{{ $c->isUsable() ? '#15803D' : '#4B5563' }}">{{ $c->is_active ? ($c->isUsable() ? 'On' : 'Ended') : 'Off' }}</button></form></span>
      </div>
    @endforeach
  </div>

  <div style="flex:1 1 340px;min-width:0;display:flex;flex-direction:column;gap:16px">
    {{-- Promoted listings --}}
    <form method="POST" action="{{ route('seller.marketing.ads') }}" data-once style="{{ $card }}">
      @csrf
      <div style="display:flex;justify-content:space-between;align-items:center"><b style="font-size:16px">Promoted listings</b>
        <label style="display:flex;gap:8px;align-items:center;font-size:13px;font-weight:700"><input type="checkbox" name="enabled" value="1" @checked(old('enabled', $seller->ads_active)) style="width:18px;height:18px;accent-color:#C2410C">On</label></div>
      <span style="font-size:13px;color:#4B5563;line-height:1.5">Show your products at the top of search and category pages, marked Sponsored. You only pay when a shopper clicks: ${{ number_format($cpc, 2) }} per click, added to your plan invoice.</span>
      <label style="display:flex;flex-direction:column;gap:6px;font-size:14px;font-weight:600">Daily budget: $<span data-budget>{{ $budget }}</span>
        <input type="range" name="budget" min="{{ $min }}" max="{{ $max }}" step="1" value="{{ $budget }}" oninput="this.parentElement.querySelector('[data-budget]').textContent=this.value" style="accent-color:#C2410C"></label>
      <span style="font-size:13px;color:#4B5563">Up to {{ (int) floor($budget / max(0.01, $cpc)) }} clicks a day. Spent today: ${{ number_format($spentToday, 2) }}. This month: {{ $monthClicks }} {{ \Illuminate\Support\Str::plural('click', $monthClicks) }}, ${{ number_format($monthCost, 2) }}.</span>
      @error('budget')<span class="pg-err">{{ $message }}</span>@enderror
      <button type="submit" style="{{ $primary }}">Save</button>
    </form>

    {{-- Store-wide sale --}}
    <div style="{{ $card }}">
      <div style="display:flex;justify-content:space-between;align-items:center"><b style="font-size:16px">Store-wide sale</b>
        <span style="font-size:12px;font-weight:800;padding:4px 10px;border-radius:999px;background:{{ $seller->sale_active ? '#ECFDF3' : '#F3F4F6' }};color:{{ $seller->sale_active ? '#15803D' : '#4B5563' }}">{{ $seller->sale_active ? 'On' : 'Off' }}</span></div>
      <form method="POST" action="{{ route('seller.marketing.sale') }}" data-once style="display:flex;flex-direction:column;gap:12px">
        @csrf
        <label style="display:flex;align-items:center;gap:8px;font-size:14px;font-weight:600">Discount
          <input name="percent" type="number" min="{{ \App\Services\StoreSale::MIN_PERCENT }}" max="{{ \App\Services\StoreSale::MAX_PERCENT }}" value="{{ old('percent', $seller->sale_percent ?: 10) }}" style="{{ $input }};width:90px"> % off everything</label>
        @error('percent')<span class="pg-err">{{ $message }}</span>@enderror
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <button type="submit" style="{{ $primary }}">{{ $seller->sale_active ? 'Change sale' : 'Start sale' }}</button>
          @if($seller->sale_active)<button type="submit" name="action" value="stop" style="height:46px;padding:0 24px;border-radius:999px;border:1.5px solid #111827;background:#fff;font-weight:700;cursor:pointer">End sale</button>@endif
        </div>
      </form>
      <span style="font-size:12px;color:#6B7280;line-height:1.5">{{ $seller->sale_active ? "Everything is {$seller->sale_percent}% off now. " : '' }}A product that already has a better sale price keeps it, and your own sale prices come back when the sale ends.</span>
    </div>
  </div>
</div>
@endsection
