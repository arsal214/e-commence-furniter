{{-- resources/views/cart.blade.php --}}
@extends('layouts.main')

@section('title', 'Your cart — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@php
    $itemCount = collect($cartItems)->sum('qty');
@endphp

@section('content')

@include('includes.navbar')

<x-checkout.shell step="cart" title="Your cart">

    @if (empty($cartItems))

        <section class="co-panel">
            <div class="co-empty">
                <div class="co-empty__icon" aria-hidden="true">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/><path d="M2 3h3l2.4 12h11L21 7H6"/></svg>
                </div>
                <p class="co-empty__title">Your cart is empty</p>
                <p class="co-empty__text">Once you add something you love, it'll show up here.</p>
                <a class="co-submit" href="{{ url('/shop') }}" style="text-decoration:none">Continue shopping</a>
            </div>
        </section>

    @else

        <div class="co__grid">

            {{-- ── Left: line items ─────────────────────────── --}}
            <div>
                <section class="co-panel" aria-labelledby="co-items-title">
                    <h2 class="co-panel__title" id="co-items-title">
                        {{ $itemCount }} item{{ $itemCount === 1 ? '' : 's' }} in your cart
                    </h2>
                    <p class="co-panel__hint">Prices and stock are confirmed at checkout.</p>

                    <ul class="co-lines">
                        @foreach ($cartItems as $item)
                            <li class="co-line">
                                @php
                                    $img = $item['image'] ?? null;
                                    $src = $img
                                        ? (Str::startsWith($img, 'assets/') ? asset($img) : Storage::url($img))
                                        : asset('assets/img/gallery/cart/cart-01.jpg');
                                @endphp

                                <a href="{{ route('product-details', $item['slug']) }}">
                                    <img class="co-line__thumb" src="{{ $src }}" alt="{{ $item['name'] }}"
                                         width="76" height="76" loading="lazy">
                                </a>

                                <div style="min-width:0">
                                    <p class="co-line__name">
                                        <a href="{{ route('product-details', $item['slug']) }}">{{ $item['name'] }}</a>
                                    </p>

                                    @if (!empty($item['color']) || !empty($item['size']))
                                        <p class="co-line__variant">
                                            {{ collect([$item['color'] ?? null, $item['size'] ?? null])->filter()->implode(' · ') }}
                                        </p>
                                    @endif

                                    <p class="co-line__unit">${{ number_format($item['price'], 2) }} each</p>
                                </div>

                                {{-- Quantity. The +/- buttons submit this form; the number
                                     input submits on change, so keyboard users don't need
                                     a separate "update" click. --}}
                                <form method="POST" action="{{ route('cart.update') }}" data-qty-form>
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $item['key'] }}">

                                    <div class="co-qty">
                                        <button class="co-qty__btn" type="button" data-qty-dec
                                                aria-label="Decrease quantity of {{ $item['name'] }}"
                                                @disabled($item['qty'] <= 1)>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14"/></svg>
                                        </button>

                                        <input class="co-qty__input" type="number" name="qty" min="1"
                                               value="{{ $item['qty'] }}" data-qty-input
                                               aria-label="Quantity of {{ $item['name'] }}">

                                        <button class="co-qty__btn" type="button" data-qty-inc
                                                aria-label="Increase quantity of {{ $item['name'] }}">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                        </button>
                                    </div>

                                    <noscript>
                                        <button class="co-ghost" type="submit" style="min-height:36px; margin-top:6px; font-size:13px">Update</button>
                                    </noscript>
                                </form>

                                <span class="co-line__total">${{ number_format($item['price'] * $item['qty'], 2) }}</span>

                                <form method="POST" action="{{ route('cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $item['key'] }}">
                                    <button class="co-line__remove" type="submit" aria-label="Remove {{ $item['name'] }} from cart">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <form method="POST" action="{{ route('cart.clear') }}" style="margin-top:14px">
                    @csrf
                    <button class="co-back" type="submit" style="width:auto; margin:0">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                        Clear cart
                    </button>
                </form>
            </div>

            {{-- ── Right: summary ───────────────────────────── --}}
            <div class="co-summary">
                <section class="co-panel" aria-labelledby="co-sum-title">
                    <h2 class="co-panel__title" id="co-sum-title">Summary</h2>
                    <p class="co-panel__hint">Shipping is calculated at the next step.</p>

                    <div class="co-sum__row">
                        <span>Subtotal ({{ $itemCount }} item{{ $itemCount === 1 ? '' : 's' }})</span>
                        <span>${{ number_format($cartTotal, 2) }}</span>
                    </div>
                    <div class="co-sum__row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>

                    <div class="co-sum__rule"></div>

                    <div class="co-sum__total">
                        <span>Total</span>
                        <b>${{ number_format($cartTotal, 2) }}</b>
                    </div>

                    @auth
                        <a class="co-submit" href="{{ url('/checkout') }}" style="text-decoration:none">
                            Proceed to checkout
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <button class="co-submit" type="button" data-signin-open>
                            Proceed to checkout
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    @endauth

                    <a class="co-back" href="{{ url('/shop') }}">Continue shopping</a>

                    <p class="co-secure">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        Secure, encrypted checkout
                    </p>

                    <div class="co-cards" aria-hidden="true">
                        <img src="{{ asset('assets/img/Payment/payment-01.png') }}" alt="" loading="lazy">
                        <img src="{{ asset('assets/img/Payment/payment-02.png') }}" alt="" loading="lazy">
                        <img src="{{ asset('assets/img/Payment/payment-03.png') }}" alt="" loading="lazy">
                    </div>
                </section>
            </div>

        </div>

    @endif

    {{-- Sign-in gate for guests --}}
    @guest
        <div class="co-modal" id="signin-modal" role="dialog" aria-modal="true" aria-labelledby="signin-title" hidden>
            <div class="co-modal__scrim" data-signin-close></div>

            <div class="co-modal__box">
                <button class="co-modal__close" type="button" data-signin-close aria-label="Close">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>

                <div class="co-modal__icon" aria-hidden="true">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                </div>

                <h2 class="co-modal__title" id="signin-title">Sign in to check out</h2>
                <p class="co-modal__text">Your cart is saved. Sign in or create an account to complete your order.</p>

                <div class="co-modal__actions">
                    <a class="co-submit" href="{{ url('/login') }}?redirect={{ urlencode(url('/checkout')) }}" style="text-decoration:none">Sign in</a>
                    <a class="co-ghost" href="{{ url('/register') }}">Register</a>
                </div>
            </div>
        </div>
    @endguest

</x-checkout.shell>

@include('includes.footer')

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Quantity stepper ───────────────────────────────────────
       Submitting on change keeps the server as the source of truth for
       stock limits — CartController clamps the qty and flashes a notice. */
    document.querySelectorAll('[data-qty-form]').forEach(function (form) {
        var input = form.querySelector('[data-qty-input]');
        var dec   = form.querySelector('[data-qty-dec]');
        var inc   = form.querySelector('[data-qty-inc]');
        if (!input) return;

        function submitWith(value) {
            input.value = value;
            form.submit();
        }

        if (dec) dec.addEventListener('click', function () {
            var v = parseInt(input.value, 10) || 1;
            if (v > 1) submitWith(v - 1);
        });

        if (inc) inc.addEventListener('click', function () {
            var v = parseInt(input.value, 10) || 1;
            submitWith(v + 1);
        });

        input.addEventListener('change', function () {
            var v = parseInt(input.value, 10);
            submitWith(!v || v < 1 ? 1 : v);
        });
    });

    /* ── Sign-in modal ──────────────────────────────────────── */
    var modal = document.getElementById('signin-modal');
    if (!modal) return;

    var opener  = document.querySelector('[data-signin-open]');
    var lastFocused = null;

    function open() {
        lastFocused = document.activeElement;
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        var first = modal.querySelector('a, button');
        if (first) first.focus();
    }

    function close() {
        modal.hidden = true;
        document.body.style.overflow = '';
        if (lastFocused) lastFocused.focus();   // return focus where it came from
    }

    if (opener) opener.addEventListener('click', open);
    modal.querySelectorAll('[data-signin-close]').forEach(function (el) {
        el.addEventListener('click', close);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) close();   // every modal needs an escape route
    });
})();
</script>
@endpush
