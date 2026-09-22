{{-- resources/views/checkout-paypal.blade.php --}}
@extends('layouts.main')

@section('title', 'Complete payment — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

<x-checkout.shell step="payment" title="Complete payment">

    <div class="co__grid">

        {{-- ── Left: PayPal button ──────────────────────────── --}}
        <div>
            <section class="co-panel" aria-labelledby="co-paypal-title">
                <h2 class="co-panel__title" id="co-paypal-title">Pay with PayPal</h2>
                <p class="co-panel__hint">You'll be asked to log in and confirm on PayPal — nothing is charged until you approve it there.</p>

                <div id="paypal-button-container" style="min-height:55px">
                    <div class="co-stripe__skeleton" data-paypal-skeleton>
                        <div class="co-stripe__bar"></div>
                        <div class="co-stripe__bar"></div>
                    </div>
                </div>

                <div class="co-alert is-hidden" id="payment-message" role="alert" style="margin:16px 0 0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span data-message-text></span>
                </div>

                <a class="co-back" href="{{ route('checkout') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back to details
                </a>

                <p class="co-secure">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                    Secured by PayPal
                </p>
            </section>
        </div>

        {{-- ── Right: what they're paying for ───────────────── --}}
        <div class="co-summary">
            <section class="co-panel" aria-labelledby="co-order-title">
                <h2 class="co-panel__title" id="co-order-title">Order {{ $order->tracking_number ?? '#' . $order->id }}</h2>
                <p class="co-panel__hint">Nothing is charged until you approve payment.</p>

                <ul class="co-sum__items">
                    @foreach ($order->items as $item)
                        <li class="co-sum__item">
                            <span class="co-sum__figure">
                                @php
                                    $img = $item->product?->image;
                                    $src = $img
                                        ? (Str::startsWith($img, 'assets/') ? asset($img) : Storage::url($img))
                                        : asset('assets/img/gallery/cart/cart-01.jpg');
                                @endphp
                                <img class="co-sum__thumb" src="{{ $src }}" alt="" width="54" height="54" loading="lazy">
                                <span class="co-sum__qty">{{ $item->qty }}</span>
                            </span>

                            <span style="min-width:0">
                                <span class="co-sum__name">{{ $item->name }}</span>
                                <span class="co-sum__variant">${{ number_format($item->price, 2) }} each</span>
                            </span>

                            <span class="co-sum__line">${{ number_format($item->total, 2) }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="co-sum__rule"></div>

                <div class="co-sum__row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="co-sum__row">
                    <span>Shipping</span>
                    <span>{{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}</span>
                </div>

                <div class="co-sum__rule"></div>

                <div class="co-sum__total">
                    <span>Total due</span>
                    <b>${{ number_format($order->total, 2) }}</b>
                </div>
            </section>

            <section class="co-panel" aria-labelledby="co-deliver-title">
                <h2 class="co-panel__title" id="co-deliver-title">Delivering to</h2>
                <p class="co-panel__hint">
                    <a href="{{ route('checkout') }}" style="color:var(--co-gold-ink); font-weight:600; text-decoration:none">Change</a>
                </p>

                <div class="co-review">
                    <div class="co-review__row">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                        <span>
                            <strong>{{ $order->name }}</strong>
                            {{ $order->email }}@if ($order->phone) · {{ $order->phone }}@endif
                        </span>
                    </div>

                    <div class="co-review__row">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        <span>
                            <strong>Shipping address</strong>
                            {{ $order->address }}@if ($order->address2), {{ $order->address2 }}@endif
                            @if ($order->city || $order->zip)
                                <br>{{ collect([$order->city, $order->zip])->filter()->implode(', ') }}
                            @endif
                        </span>
                    </div>

                    @if ($order->notes)
                        <div class="co-review__row">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v12H8l-4 4z"/></svg>
                            <span>
                                <strong>Order notes</strong>
                                {{ $order->notes }}
                            </span>
                        </div>
                    @endif
                </div>
            </section>
        </div>

    </div>

</x-checkout.shell>

@include('includes.footer')

@endsection

@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ urlencode($paypalClientId) }}&currency=USD&intent=capture"></script>
<script>
(function () {
    'use strict';

    var container  = document.getElementById('paypal-button-container');
    var msgBox      = document.getElementById('payment-message');
    var msgText     = msgBox ? msgBox.querySelector('[data-message-text]') : null;
    var captureUrl  = @json(route('checkout.paypal-capture', $order));
    var paypalOrderId = @json($paypalOrderId);

    function showError(message) {
        if (!msgBox) return;
        if (msgText) msgText.textContent = message;
        msgBox.classList.remove('is-hidden');
    }

    // If the SDK was blocked (ad blocker, network), don't leave an eternally
    // shimmering skeleton — say what happened, same as the Stripe page does.
    if (typeof paypal === 'undefined' || !paypal.Buttons) {
        var deadSkeleton = document.querySelector('[data-paypal-skeleton]');
        if (deadSkeleton) deadSkeleton.remove();
        showError('The payment form failed to load. Please refresh the page, or allow www.paypal.com if you use a content blocker. You have not been charged.');
        return;
    }

    paypal.Buttons({
        style: { shape: 'rect', color: 'gold', label: 'paypal', height: 48 },

        // The order was already created server-side (with our total, in USD) —
        // handing back that same id here means the buyer can never approve a
        // different amount than what we billed for.
        createOrder: function () {
            return paypalOrderId;
        },

        onApprove: function () {
            var skeleton = document.querySelector('[data-paypal-skeleton]');
            if (skeleton) skeleton.remove();
            if (msgBox) msgBox.classList.add('is-hidden');

            // Capture happens server-side, not via actions.order.capture() in the
            // browser — the same reason Stripe's confirmPayment result is only
            // trusted after its own redirect: money should be settled where our
            // secret key lives, not asserted by the client alone.
            return fetch(captureUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.redirect) {
                        window.location = res.redirect;
                    } else {
                        showError(res.error || 'Something went wrong while confirming your payment. Please try again.');
                    }
                })
                .catch(function () {
                    showError('Something went wrong while confirming your payment. No charge was made — please try again.');
                });
        },

        onError: function () {
            showError('Something went wrong while processing your payment. No charge was made — please try again.');
        },
    }).render('#paypal-button-container');
})();
</script>
@endpush
