{{-- resources/views/thank-you.blade.php --}}
@extends('layouts.pg')

@section('title', 'Order Confirmed | PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')
@php $shippingLabels = ['free' => 'Standard shipping', 'fast' => 'Fast shipping', 'local' => 'Local pickup']; @endphp

<div class="mk-wrap" style="max-width:760px;padding-top:8px;padding-bottom:32px;display:flex;flex-direction:column;gap:16px">
@if ($order)
    @php
        $firstName = \Str::of($order->name)->trim()->explode(' ')->first();
        $isPaid = $order->payment_status === 'paid';
        $trackUrl = url('/track-order?tracking=' . $order->tracking_number);
    @endphp

    @php
        $window = \App\Support\DeliveryEstimate::window();
        $arrive = $window['earliest']->format('D, M j') . ' – ' . $window['latest']->format('D, M j');
    @endphp
    <section style="display:flex;flex-direction:column;gap:20px;padding-top:24px">
        <span aria-hidden="true" style="width:64px;height:64px;border-radius:50%;background:#ECFDF3;color:#15803D;display:flex;align-items:center;justify-content:center;font-size:30px;font-weight:800">✓</span>
        <h1 style="margin:0;font-size:clamp(28px,4vw,40px);font-weight:800;letter-spacing:-.03em">Thank you, {{ $firstName }}! Your order is placed.</h1>
        <span style="font-size:16px;color:#374151;line-height:1.55">Order <b id="tracking-number">{{ $order->tracking_number }}</b> · Total ${{ number_format($order->total, 2) }}. A receipt is on its way to <b>{{ $order->email }}</b>.</span>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <button type="button" id="copy-tracking" data-tracking="{{ $order->tracking_number }}" style="height:40px;padding:0 16px;border-radius:999px;border:1.5px solid #D1D5DB;background:#fff;font-weight:700;font-size:13px;cursor:pointer"><span id="copy-label">Copy</span> order number</button>
            <span id="copy-status" class="sr-only" role="status" aria-live="polite"></span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px">
            @foreach ($order->items->groupBy(fn ($i) => $i->seller?->store_name ?? 'Peyton Ghalib') as $store => $lines)
                <div style="border:1px solid #E5E7EB;border-radius:16px;padding:16px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap">
                    <span style="display:flex;flex-direction:column;gap:2px"><b>{{ $store }}</b><span style="font-size:14px;color:#4B5563">{{ $lines->sum('qty') }} {{ \Illuminate\Support\Str::plural('item', $lines->sum('qty')) }} · Arrives {{ $arrive }}</span></span>
                    <a href="{{ $trackUrl }}" style="font-weight:700;font-size:14px">Track</a>
                </div>
            @endforeach
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ $trackUrl }}" style="height:50px;padding:0 26px;border-radius:999px;background:#C2410C;color:#FFFFFF;display:flex;align-items:center;font-weight:800">Track order</a>
            <a href="{{ url('/') }}" style="height:50px;padding:0 26px;border-radius:999px;border:1.5px solid #111827;color:#111827;display:flex;align-items:center;font-weight:700">Keep shopping</a>
        </div>
        @if (! $order->user_id && ! session('account_created'))
            <div style="border:1px dashed #D1D5DB;border-radius:16px;padding:16px;font-size:14px;color:#374151">Checked out as a guest? Set a password below to save this order and track every package in one place.</div>
        @endif
    </section>

    <section class="pg-card">
        <h2 style="margin:0;font-size:18px">Order summary</h2>
        @php $groups = $order->items->groupBy(fn ($i) => $i->seller?->store_name ?? 'Peyton Ghalib'); @endphp
        @foreach ($groups as $store => $lines)
            <div>
                <b style="font-size:13px;color:var(--pg-brand-dark)">Sold by {{ $store }}</b>
                @foreach ($lines as $item)
                    <div class="sc-row" style="margin-top:8px;align-items:center">
                        <span style="display:flex;gap:10px;align-items:center;min-width:0">
                            <span class="sc-thumb" style="width:48px;height:48px">@if($item->product?->image)<img src="{{ \App\Models\Product::imageUrl($item->product->image) }}" alt="" loading="lazy">@endif</span>
                            <span><b>{{ $item->name }}</b><small>{{ collect([$item->color, $item->size])->filter()->implode(' · ') }} × {{ $item->qty }}</small></span>
                        </span>
                        <b>${{ number_format($item->price * $item->qty, 2) }}</b>
                    </div>
                @endforeach
            </div>
        @endforeach

        <dl style="margin:0;display:flex;flex-direction:column;gap:6px;font-size:14px">
            <div class="sc-row" style="border:0"><dt>Subtotal</dt><dd style="margin:0">${{ number_format($order->subtotal, 2) }}</dd></div>
            @if ($order->discount > 0)<div class="sc-row" style="border:0;color:var(--pg-ok)"><dt>Welcome deal</dt><dd style="margin:0">&minus;${{ number_format($order->discount, 2) }}</dd></div>@endif
            <div class="sc-row" style="border:0"><dt>{{ $shippingLabels[$order->shipping] ?? ucfirst($order->shipping) }}</dt><dd style="margin:0">{{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}</dd></div>
            <div class="sc-row" style="border:0;font-size:18px"><dt><b>Total</b></dt><dd style="margin:0"><b>${{ number_format($order->total, 2) }}</b></dd></div>
        </dl>

        @if (session('account_created'))
            <div class="pg-alert ok">Your account is ready. Your orders will be saved to it.</div>
        @elseif (! $order->user_id)
            <form method="POST" action="{{ route('thank-you.account') }}" data-once style="display:flex;flex-direction:column;gap:12px;border-top:1px solid var(--pg-line);padding-top:16px">
                @csrf
                <b>Save your details for next time</b>
                <span class="pg-muted">Set a password to track orders and check out faster.</span>
                <x-pg.field label="Password" name="password" type="password" autocomplete="new-password" hint="At least 8 characters" required />
                <x-pg.field label="Confirm password" name="password_confirmation" type="password" autocomplete="new-password" required />
                <button class="pg-btn" type="submit">Create my account</button>
            </form>
        @endif
    </section>

    <section class="pg-card">
        <div class="pg-grid two">
            <div><b>Delivering to</b>
                <p class="pg-muted" style="margin:6px 0 0">{{ $order->name }}<br>{{ $order->address }}@if ($order->address2)<br>{{ $order->address2 }}@endif
                @if ($order->city || $order->zip)<br>{{ trim($order->city . ', ' . $order->state . ' ' . $order->zip, ' ,') }}@endif</p></div>
            <div><b>Payment</b>
                <p class="pg-muted" style="margin:6px 0 0">{{ $order->paymentLabel() }} · @if ($isPaid)<span style="color:var(--pg-ok);font-weight:700">Paid</span>@else<span style="color:var(--pg-warn);font-weight:700">Pending</span>@endif<br>
                Estimated delivery {{ \App\Support\DeliveryEstimate::minDays() }}–{{ \App\Support\DeliveryEstimate::maxDays() }} business days.</p></div>
        </div>
        <p class="pg-muted" style="margin:0">Each seller ships their own items, so packages from different stores can arrive on different days. Questions? <a href="{{ route('contact.show') }}">Contact us</a>. You are covered by Buyer Protection.</p>
    </section>
@else
    <section class="pg-card" style="text-align:center;align-items:center">
        <h1>Thank you for your order</h1>
        <p class="pg-muted" style="margin:0">We couldn't load the details just now, but your confirmation email has everything. You can look up an order any time.</p>
        <a class="pg-btn" href="{{ route('track-order') }}" style="width:auto">Track an order</a>
        <a class="mk-link" href="{{ route('shop') }}">Continue shopping</a>
    </section>
@endif
</div>
@endsection

@push('scripts')
@if ($order)
<script>
    (function () {
        var btn = document.getElementById('copy-tracking');
        if (!btn) return;

        var label  = document.getElementById('copy-label');
        var status = document.getElementById('copy-status');
        var reset;

        function confirmCopied() {
            label.textContent = 'Copied';
            status.textContent = 'Tracking number copied to clipboard.';
            clearTimeout(reset);
            reset = setTimeout(function () {
                label.textContent = 'Copy';
                status.textContent = '';
            }, 2000);
        }

        btn.addEventListener('click', function () {
            var value = btn.dataset.tracking;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(value).then(confirmCopied);
                return;
            }

            // http:// and older browsers have no clipboard API.
            var field = document.createElement('textarea');
            field.value = value;
            field.setAttribute('readonly', '');
            field.style.position = 'fixed';
            field.style.opacity = '0';
            document.body.appendChild(field);
            field.select();
            try { document.execCommand('copy'); confirmCopied(); } catch (e) { /* leave the number selectable */ }
            document.body.removeChild(field);
        });
    })();
</script>
@endif

{{-- ── Meta Pixel Purchase ──────────────────────────────────────────────
     Gated on the flashed order_total, so refreshing /thank-you cannot register
     the same purchase twice. The payload is built from the order row while it
     is still in session; the flashed total is the fallback so a sale is never
     reported without a value. eventID follows the TikTok convention
     (deterministic on the order id) so a Conversions API twin added later
     deduplicates against this browser event. --}}
@if (session()->has('order_total'))
    @php
        $pixelValue = (float) ($order?->total ?? session('order_total'));

        // This store carries no catalog SKUs (see TikTokEventsService::contents),
        // so content ids are the product id the feed is keyed on. Always the
        // product id, never the SKU: an order line that happens to carry a SKU
        // used to emit one here, which matched nothing in the catalog and broke
        // attribution for exactly the orders that converted. ViewContent,
        // AddToCart and Purchase must all send the same id for the same product.
        $pixelContents = $order
            ? $order->items->map(fn ($item) => [
                'id'         => (string) $item->product_id,
                'quantity'   => (int) $item->qty,
                'item_price' => (float) $item->price,
            ])->values()->all()
            : [];

        // Meta matches the catalog on content_ids; contents[].id alone is not
        // enough for Advantage+ / dynamic ads to resolve the product. Two lines
        // of the same product (different colour/size) collapse to one id —
        // a repeated id in content_ids is rejected.
        $pixelContentIds = array_values(array_unique(array_column($pixelContents, 'id')));

        $pixelNames = $order
            ? $order->items->pluck('product.name')->filter()->unique()->values()->all()
            : [];

        // Meta de-duplicates Purchases on transaction_id across browser and
        // Conversions API, and across a repeat delivery of the same event.
        $pixelTransactionId = $order?->tracking_number ?: ($order?->id ? 'order-' . $order->id : null);

        // GA4 items use the same product ids as the pixel's content_ids.
        $gaItems = $order ? $order->items->map(fn ($item) => [
            'item_id' => (string) $item->product_id, 'item_name' => $item->name,
            'price' => (float) $item->price, 'quantity' => (int) $item->qty,
        ])->values()->all() : [];
        $gaCoupon = $order && (float) $order->discount > 0 && $order->welcome_claim_id
            ? \App\Models\WelcomeClaim::whereKey($order->welcome_claim_id)->value('code') : null;
    @endphp
{{-- A zero or negative value is rejected by Meta exactly like a missing one, and
     a rejected Purchase counts against the pixel's error rate. Skipping the call
     keeps the campaign clean; the server log tells us an order slipped through
     without a usable total so the root cause can be chased. --}}
@if ($pixelValue > 0)
<script>
(function () {
    @if ($pixelTransactionId)
    {{-- Second line of defence behind the flashed-session gate above. That gate
         stops a refresh, but a restore from bfcache or a browser that replays
         the document can run this script again against the same order. Revenue
         double-counted here corrupts ROAS for the whole campaign, so the lock is
         persisted per order id rather than per page load. --}}
    var lock = 'pg_fb_purchase_' + @json($pixelTransactionId);
    try {
        if (sessionStorage.getItem(lock)) return;
        sessionStorage.setItem(lock, '1');
    } catch (e) { /* private mode: the session gate above still covers refresh */ }
    @endif

    if (typeof gtag === 'function') {
        gtag('event', 'purchase', {
            transaction_id: @json((string) $pixelTransactionId),
            value: {{ number_format($pixelValue, 2, '.', '') }},
            currency: @json(config('services.meta.currency', 'USD')),
            shipping: {{ number_format((float) ($order?->shipping_cost ?? 0), 2, '.', '') }},
            @if ($gaCoupon) coupon: @json($gaCoupon), @endif
            items: {!! json_encode($gaItems, JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}
        });
    }

    if (typeof fbq !== 'function') return;
    fbq('track', 'Purchase', {
        value: {{ number_format($pixelValue, 2, '.', '') }},
        currency: @json(config('services.meta.currency', 'USD')),
        content_type: 'product',
        content_ids: {!! json_encode($pixelContentIds, JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!},
        contents: {!! json_encode($pixelContents, JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!},
        num_items: {{ (int) ($order ? $order->items->sum('qty') : 0) }}@if (!empty($pixelNames)),
        content_name: @json(implode(', ', $pixelNames))@endif
        @if ($pixelTransactionId), transaction_id: @json((string) $pixelTransactionId)@endif
    }@if ($order), { eventID: @json('Purchase.order-' . $order->id) }@endif);
})();
</script>
@else
    @php \Log::warning('Meta Purchase pixel skipped: non-positive order value', [
        'order_id' => $order?->id,
        'flashed'  => session('order_total'),
    ]) @endphp
@endif
@endif
@endpush
