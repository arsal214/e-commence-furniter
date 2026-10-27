@extends('seller.center.layout')
@section('title', 'Settings')

@section('content')
@php
  $card = 'background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px';
  $choice = fn (bool $on) => 'display:flex;gap:12px;align-items:center;padding:14px;border-radius:14px;border:1.5px solid ' . ($on ? '#111827' : '#E5E7EB') . ';background:#FFFFFF;font-size:14px;font-weight:600;cursor:pointer';
  $handlingNow = old('handling_days', $seller->handling_days);
  $windowNow = (int) old('return_window_days', $seller->return_window_days);
  $payerNow = old('return_shipping_payer', $seller->return_shipping_payer);
@endphp

<form method="POST" action="{{ route('seller.settings.save') }}" data-once style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  @csrf
  <div style="flex:1 1 400px;min-width:0;display:flex;flex-direction:column;gap:16px">
    <div style="{{ $card }}">
      <b style="font-size:16px">Processing time</b>
      <span style="font-size:13px;color:#4B5563;line-height:1.5">How long you need to pack and hand an order to the carrier. Shoppers see it on your store page.</span>
      @foreach($handling as $days => $label)
        <label style="{{ $choice((int) $handlingNow === $days) }}">
          <input type="radio" name="handling_days" value="{{ $days }}" @checked((int) $handlingNow === $days) style="width:18px;height:18px;accent-color:#C2410C">{{ $label }}
        </label>
      @endforeach
      @error('handling_days')<span class="pg-err">{{ $message }}</span>@enderror
    </div>
  </div>

  <div style="flex:1 1 340px;min-width:0;{{ $card }}">
    <b style="font-size:16px">Return policy</b>
    <span style="font-size:13px;font-weight:600">Return window</span>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      @foreach($windows as $w)
        <label style="height:42px;padding:0 18px;border-radius:999px;border:1.5px solid #111827;display:flex;align-items:center;gap:8px;font-weight:700;cursor:pointer;background:{{ $windowNow === $w ? '#111827' : '#FFFFFF' }};color:{{ $windowNow === $w ? '#FFFFFF' : '#111827' }}">
          <input type="radio" name="return_window_days" value="{{ $w }}" @checked($windowNow === $w) style="position:absolute;opacity:0;width:1px;height:1px">{{ $w }} days
        </label>
      @endforeach
    </div>
    @error('return_window_days')<span class="pg-err">{{ $message }}</span>@enderror
    <span style="font-size:13px;font-weight:600">Change-of-mind returns</span>
    @foreach(['buyer' => 'Buyer pays return shipping', 'seller' => 'I pay return shipping'] as $val => $label)
      <label style="{{ $choice($payerNow === $val) }}">
        <input type="radio" name="return_shipping_payer" value="{{ $val }}" @checked($payerNow === $val) style="width:18px;height:18px;accent-color:#C2410C">{{ $label }}
      </label>
    @endforeach
    @error('return_shipping_payer')<span class="pg-err">{{ $message }}</span>@enderror
    <span style="font-size:12px;color:#6B7280;line-height:1.5">Damaged, defective or wrong items must always be accepted, with return shipping paid by you. Your policy is shown on every product page.</span>
    <button type="submit" style="align-self:flex-start;height:46px;padding:0 24px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer">Save settings</button>
  </div>
</form>
@endsection
