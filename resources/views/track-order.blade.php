@extends('layouts.pg')

@section('title', \App\Support\SiteCopy::title('track-order'))
@section('meta_description', \App\Support\SiteCopy::description('track-order'))

@section('content')
@php
  $steps = ['pending' => 'Order placed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'out_for_delivery' => 'Out for delivery', 'delivered' => 'Delivered'];
  $index = $order ? array_search($order->status, array_keys($steps), true) : false;
@endphp

<div class="mk-wrap" style="max-width:760px;padding-top:24px;padding-bottom:32px;display:flex;flex-direction:column;gap:16px">
  <section class="pg-card">
    <h1>Track your order</h1>
    <p class="pg-muted" style="margin:0">Enter the tracking number from your confirmation email.</p>
    <form method="GET" action="{{ route('track-order') }}" style="display:flex;gap:8px;flex-wrap:wrap">
      <input name="tracking" value="{{ $tracking }}" placeholder="FRN-2026-XXXXXXXX" maxlength="40" required aria-label="Tracking number"
             style="flex:1 1 220px;height:52px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px">
      <button class="pg-btn" type="submit" style="width:auto">Track</button>
    </form>
  </section>

  @if ($tracking !== '' && ! $order)
    <div class="pg-alert err" role="alert">We could not find an order with that tracking number. Check it and try again, or <a href="{{ route('contact.show') }}">contact us</a>.</div>
  @endif

  @if ($order)
    <section class="pg-card">
      <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center">
        <h2 style="margin:0;font-size:18px">{{ $order->tracking_number }}</h2>
        <span class="sc-pill {{ $order->status === 'cancelled' ? 'bad' : ($order->status === 'delivered' ? 'ok' : 'warn') }}">{{ $order->statusLabel() }}</span>
      </div>

      @if ($order->status !== 'cancelled' && $index !== false)
        <ol style="list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(5,1fr);gap:6px;text-align:center;font-size:12px">
          @foreach ($steps as $key => $label)
            @php $done = array_search($key, array_keys($steps), true) <= $index; @endphp
            <li><div class="pg-steps" style="margin:0 0 6px"><i class="{{ $done ? 'on' : '' }}"></i></div><span style="font-weight:{{ $done ? 700 : 400 }}">{{ $label }}</span></li>
          @endforeach
        </ol>
      @endif

      <div class="pg-grid two" style="font-size:14px">
        @if ($order->shipped_at)<div><b>Shipped</b><p class="pg-muted" style="margin:4px 0 0">{{ \Carbon\Carbon::parse($order->shipped_at)->format('M j, Y') }}</p></div>@endif
        @if ($order->carrier)<div><b>Carrier</b><p class="pg-muted" style="margin:4px 0 0">{{ $order->carrier }}</p></div>@endif
        @if ($order->supplier_tracking)<div><b>Carrier tracking</b><p class="pg-muted" style="margin:4px 0 0">{{ $order->supplier_tracking }}</p></div>@endif
        <div><b>Estimated delivery</b><p class="pg-muted" style="margin:4px 0 0">{{ \App\Support\DeliveryEstimate::minDays() }}–{{ \App\Support\DeliveryEstimate::maxDays() }} business days</p></div>
      </div>

      <div>
        @foreach ($order->items->groupBy(fn ($i) => $i->seller?->store_name ?? 'Peyton Ghalib') as $store => $lines)
          <b style="font-size:13px;color:var(--pg-brand-dark)">Sold by {{ $store }}</b>
          @foreach ($lines as $item)
            <p class="sc-row" style="margin:6px 0"><span>{{ $item->name }} × {{ $item->qty }}</span><b>${{ number_format($item->price * $item->qty, 2) }}</b></p>
          @endforeach
          @php $sid = $lines->first()->seller_id; $ship = $sid ? $shipments->get($sid) : null; $claim = $sid ? $claims->get($sid) : null; @endphp
          @if ($ship)
            <p class="pg-muted" style="margin:4px 0 8px;font-size:13px">
              {{ ['pending' => 'Waiting for the seller to ship', 'shipped' => 'Shipped' . ($ship->carrier ? ' with ' . $ship->carrier : '') . ($ship->tracking_number ? ' · ' . $ship->tracking_number : ''), 'delivered' => 'Delivered', 'refunded' => 'Refunded'][$ship->status] ?? '' }}
            </p>
            @if ($claim)
              <div class="pg-alert {{ $claim->status === 'refunded' ? 'ok' : 'err' }}" style="background:#FFF1E8;color:#7C2D12">Claim: {{ $claim->status === 'open' ? 'we are reviewing it' : ($claim->status === 'refunded' ? 'approved and refunded' : 'reviewed, no refund') }}.</div>
            @elseif ($order->payment_status === 'paid' && $ship->status !== 'refunded')
              <details style="margin:6px 0 14px"><summary style="cursor:pointer;font-weight:700;font-size:13px;color:#C2410C">Problem with this order? Open a Buyer Protection claim</summary>
                <form method="POST" action="{{ route('claim.store', $order->tracking_number) }}" data-once style="display:flex;flex-direction:column;gap:10px;margin-top:10px">
                  @csrf
                  <input type="hidden" name="seller_id" value="{{ $sid }}">
                  <x-pg.field label="Email used for the order" name="email" type="email" required />
                  <label class="pg-field">What went wrong?
                    <select name="reason" required>@foreach (\App\Services\DisputeService::REASONS as $k => $label)<option value="{{ $k }}" @selected(old('reason') === $k)>{{ $label }}</option>@endforeach</select>
                  </label>
                  <label class="pg-field">Details<textarea name="details" rows="3" maxlength="1500" required style="border-radius:12px;border:1px solid #D1D5DB;padding:10px 12px;font-size:16px;font-family:inherit">{{ old('details') }}</textarea>
                    @error('details')<span class="err">{{ $message }}</span>@enderror</label>
                  <button class="pg-btn" type="submit">Send claim</button>
                </form>
              </details>
            @endif
          @endif
        @endforeach
      </div>
      <p class="pg-muted" style="margin:0">Items from different stores ship separately and can arrive on different days.</p>
    </section>
  @endif
</div>
@endsection
