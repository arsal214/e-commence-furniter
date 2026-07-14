{{-- resources/views/checkout-stripe.blade.php --}}
@extends('layouts.main')

@section('title', 'Complete payment — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

<x-checkout.shell step="payment" title="Complete payment">

    <div class="co__grid">

        {{-- ── Left: card entry ─────────────────────────────── --}}
        <div>
            <section class="co-panel" aria-labelledby="co-card-title">
                <h2 class="co-panel__title" id="co-card-title">Card details</h2>
                <p class="co-panel__hint">Entered directly with Stripe — we never see or store your card number.</p>

                {{-- Reserved height: the Payment Element mounts async, and a
                     collapsing skeleton would shift the button under the cursor. --}}
                <div class="co-stripe" id="payment-element">
                    <div class="co-stripe__skeleton" data-stripe-skeleton>
                        <div class="co-stripe__bar"></div>
                        <div class="co-stripe__bar"></div>
                        <div class="co-stripe__bar"></div>
                    </div>
                </div>

                <div class="co-alert is-hidden" id="payment-message" role="alert" style="margin:16px 0 0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span data-message-text></span>
                </div>

                <button class="co-submit" type="button" id="pay-btn">
                    <svg class="co-submit__spinner" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9"/></svg>
                    <span data-pay-label>Pay ${{ number_format($order->total, 2) }}</span>
                </button>

                <a class="co-back" href="{{ route('checkout') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back to details
                </a>

                <p class="co-secure">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                    Secured by Stripe · 256-bit encryption
                </p>

                <div class="co-cards" aria-hidden="true">
                    <img src="{{ asset('assets/img/Payment/payment-01.png') }}" alt="" loading="lazy">
                    <img src="{{ asset('assets/img/Payment/payment-02.png') }}" alt="" loading="lazy">
                    <img src="{{ asset('assets/img/Payment/payment-03.png') }}" alt="" loading="lazy">
                </div>
            </section>
        </div>

        {{-- ── Right: what they're paying for ───────────────── --}}
        <div class="co-summary">
            <section class="co-panel" aria-labelledby="co-order-title">
                <h2 class="co-panel__title" id="co-order-title">Order {{ $order->tracking_number ?? '#' . $order->id }}</h2>
                <p class="co-panel__hint">Nothing is charged until you press pay.</p>

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
<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    'use strict';

    var payBtn   = document.getElementById('pay-btn');
    var msgBox   = document.getElementById('payment-message');
    var msgText  = msgBox ? msgBox.querySelector('[data-message-text]') : null;
    var label    = payBtn ? payBtn.querySelector('[data-pay-label]') : null;
    var payText  = label ? label.textContent : 'Pay';

    var stripe   = Stripe(@json($stripeKey));
    var isDark   = document.documentElement.classList.contains('dark');

    /* Theme the Payment Element so it doesn't look like a foreign widget
       dropped into the page. Stripe re-renders these on mount. */
    var elements = stripe.elements({
        clientSecret: @json($clientSecret),
        appearance: {
            theme: isDark ? 'night' : 'stripe',
            variables: {
                colorPrimary:    '#bb976d',
                colorBackground: isDark ? '#12100e' : '#ffffff',
                colorText:       isDark ? '#f3ede4' : '#1f1a15',
                colorDanger:     isDark ? '#ff9c94' : '#b42318',
                fontFamily:      "'DM Sans', 'Poppins', ui-sans-serif, system-ui, sans-serif",
                borderRadius:    '12px',
                spacingUnit:     '4px',
            },
        },
    });

    var paymentElement = elements.create('payment');

    // Swap the skeleton out only once Stripe has actually painted.
    paymentElement.on('ready', function () {
        var skeleton = document.querySelector('[data-stripe-skeleton]');
        if (skeleton) skeleton.remove();
    });

    paymentElement.mount('#payment-element');

    function showError(message) {
        if (!msgBox) return;
        if (msgText) msgText.textContent = message;
        msgBox.classList.remove('is-hidden');
    }

    function clearError() {
        if (msgBox) msgBox.classList.add('is-hidden');
    }

    function setBusy(busy) {
        if (!payBtn) return;
        payBtn.disabled = busy;
        if (busy) {
            payBtn.setAttribute('aria-busy', 'true');
            if (label) label.textContent = 'Processing…';
        } else {
            payBtn.removeAttribute('aria-busy');
            if (label) label.textContent = payText;
        }
    }

    payBtn.addEventListener('click', async function () {
        clearError();
        setBusy(true);   // guards against a double-charge on double-click

        var result = await stripe.confirmPayment({
            elements: elements,
            confirmParams: { return_url: @json(route('checkout.stripe-success')) },
        });

        // On success Stripe redirects, so reaching here means it failed.
        // Only card/validation errors are safe to show the customer.
        if (result.error) {
            var type = result.error.type;
            showError(
                (type === 'card_error' || type === 'validation_error') && result.error.message
                    ? result.error.message
                    : 'Something went wrong while processing your payment. No charge was made — please try again.'
            );
            setBusy(false);
        }
    });

    // bfcache can restore the disabled button after a back-navigation.
    window.addEventListener('pageshow', function () { setBusy(false); });
})();
</script>
@endpush
