{{-- resources/views/checkout.blade.php --}}
@extends('layouts.main')

@section('title', 'Checkout — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@php
    $itemCount = collect($cartItems)->sum('qty');
@endphp

@section('content')

@include('includes.navbar')

<x-checkout.shell step="details" title="Checkout">

    <form method="POST" action="{{ route('checkout.store') }}" data-co-form novalidate>
        @csrf

        @if ($errors->any())
            <div class="co-alert" role="alert">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="co__grid">

            {{-- ── Left: details ────────────────────────────── --}}
            <div>
                <section class="co-panel">
                    <h2 class="co-panel__title">Contact details</h2>
                    <p class="co-panel__hint">We'll send your order confirmation and tracking here.</p>

                    <div class="co-grid">
                        <div class="co-field co-field--full">
                            <label class="co-field__label" for="name">
                                Full name <span class="co-field__req" aria-hidden="true">*</span><span class="co-sr">(required)</span>
                            </label>
                            <input class="co-field__input" id="name" name="name" type="text"
                                   value="{{ old('name', auth()->user()?->name) }}"
                                   placeholder="Jane Doe" autocomplete="name"
                                   data-validate data-label="Full name" required
                                   @error('name') aria-invalid="true" @enderror
                                   aria-describedby="name-error">
                            <p class="co-field__error @error('name') is-visible @enderror" id="name-error" role="alert">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                <span data-error-text>@error('name'){{ $message }}@enderror</span>
                            </p>
                        </div>

                        <div class="co-field">
                            <label class="co-field__label" for="email">
                                Email <span class="co-field__req" aria-hidden="true">*</span><span class="co-sr">(required)</span>
                            </label>
                            <input class="co-field__input" id="email" name="email" type="email" inputmode="email"
                                   value="{{ old('email', auth()->user()?->email) }}"
                                   placeholder="name@example.com" autocomplete="email"
                                   data-validate data-label="Email" required
                                   @error('email') aria-invalid="true" @enderror
                                   aria-describedby="email-error">
                            <p class="co-field__error @error('email') is-visible @enderror" id="email-error" role="alert">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                <span data-error-text>@error('email'){{ $message }}@enderror</span>
                            </p>
                        </div>

                        <div class="co-field">
                            <label class="co-field__label" for="phone">
                                Phone <span class="co-field__req" aria-hidden="true">*</span><span class="co-sr">(required)</span>
                            </label>
                            <input class="co-field__input" id="phone" name="phone" type="tel" inputmode="tel"
                                   value="{{ old('phone') }}"
                                   placeholder="+1 555 000 0000" autocomplete="tel"
                                   data-validate data-label="Phone" required
                                   @error('phone') aria-invalid="true" @enderror
                                   aria-describedby="phone-error">
                            <p class="co-field__error @error('phone') is-visible @enderror" id="phone-error" role="alert">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                <span data-error-text>@error('phone'){{ $message }}@enderror</span>
                            </p>
                        </div>
                    </div>
                </section>

                <section class="co-panel">
                    <h2 class="co-panel__title">Shipping address</h2>
                    <p class="co-panel__hint">Where should we deliver your order?</p>

                    <div class="co-grid">
                        <div class="co-field co-field--full">
                            <label class="co-field__label" for="address">
                                Address line 1 <span class="co-field__req" aria-hidden="true">*</span><span class="co-sr">(required)</span>
                            </label>
                            <input class="co-field__input" id="address" name="address" type="text"
                                   value="{{ old('address') }}"
                                   placeholder="123 Main Street" autocomplete="address-line1"
                                   data-validate data-label="Address" required
                                   @error('address') aria-invalid="true" @enderror
                                   aria-describedby="address-error">
                            <p class="co-field__error @error('address') is-visible @enderror" id="address-error" role="alert">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                <span data-error-text>@error('address'){{ $message }}@enderror</span>
                            </p>
                        </div>

                        <div class="co-field co-field--full">
                            <label class="co-field__label" for="address2">Address line 2</label>
                            <input class="co-field__input" id="address2" name="address2" type="text"
                                   value="{{ old('address2') }}"
                                   placeholder="Apartment, suite, etc. (optional)" autocomplete="address-line2">
                        </div>

                        <div class="co-field">
                            <label class="co-field__label" for="city">Town / City</label>
                            <input class="co-field__input" id="city" name="city" type="text"
                                   value="{{ old('city') }}" placeholder="Your city" autocomplete="address-level2">
                        </div>

                        <div class="co-field">
                            <label class="co-field__label" for="zip">ZIP / Postcode</label>
                            <input class="co-field__input" id="zip" name="zip" type="text"
                                   value="{{ old('zip') }}" placeholder="1217" autocomplete="postal-code">
                        </div>
                    </div>
                </section>

                <section class="co-panel">
                    <h2 class="co-panel__title">Order notes</h2>
                    <p class="co-panel__hint">Anything we should know — delivery instructions, a gift message.</p>

                    <div class="co-field" style="margin-bottom:0">
                        <label class="co-sr" for="notes">Order notes</label>
                        <textarea class="co-field__area" id="notes" name="notes"
                                  placeholder="Leave at the side door, please.">{{ old('notes') }}</textarea>
                    </div>
                </section>
            </div>

            {{-- ── Right: summary + payment ─────────────────── --}}
            <div class="co-summary">
                <section class="co-panel" aria-labelledby="co-sum-title">
                    <h2 class="co-panel__title" id="co-sum-title">
                        Order summary
                        <span style="font-weight:500; color:var(--co-muted)">({{ $itemCount }} item{{ $itemCount === 1 ? '' : 's' }})</span>
                    </h2>
                    <p class="co-panel__hint">
                        <a href="{{ route('cart') }}" style="color:var(--co-gold-ink); font-weight:600; text-decoration:none">Edit cart</a>
                    </p>

                    <ul class="co-sum__items">
                        @foreach ($cartItems as $item)
                            <li class="co-sum__item">
                                <span class="co-sum__figure">
                                    @php
                                        $img = $item['image'] ?? null;
                                        $src = $img
                                            ? (Str::startsWith($img, 'assets/') ? asset($img) : Storage::url($img))
                                            : asset('assets/img/gallery/cart/cart-01.jpg');
                                    @endphp
                                    <img class="co-sum__thumb" src="{{ $src }}" alt="" width="54" height="54" loading="lazy">
                                    <span class="co-sum__qty">{{ $item['qty'] }}</span>
                                </span>

                                <span style="min-width:0">
                                    <span class="co-sum__name">{{ $item['name'] }}</span>
                                    @if (!empty($item['color']) || !empty($item['size']))
                                        <span class="co-sum__variant">
                                            {{ collect([$item['color'] ?? null, $item['size'] ?? null])->filter()->implode(' · ') }}
                                        </span>
                                    @endif
                                </span>

                                <span class="co-sum__line">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="co-sum__rule"></div>

                    {{-- data-cost is read by the script, so the displayed total always
                         matches whatever $shippingOptions the controller offers. --}}
                    <fieldset style="border:0; padding:0; margin:0 0 16px">
                        <legend class="co-field__label" style="padding:0">Shipping</legend>

                        @foreach ($shippingOptions as $key => $option)
                            <label class="co-choice">
                                <input class="co-choice__input" type="radio" name="shipping" value="{{ $key }}"
                                       data-cost="{{ $option['cost'] }}"
                                       @checked(old('shipping', array_key_first($shippingOptions)) === $key)>
                                <span class="co-choice__mark" aria-hidden="true"></span>
                                <span class="co-choice__label">{{ $option['label'] }}</span>
                                <span class="co-choice__price">
                                    {{ $option['cost'] > 0 ? '$' . number_format($option['cost'], 2) : 'Free' }}
                                </span>
                            </label>
                        @endforeach
                    </fieldset>

                    <div class="co-sum__row">
                        <span>Subtotal</span>
                        <span>${{ number_format($cartTotal, 2) }}</span>
                    </div>
                    <div class="co-sum__row">
                        <span>Shipping</span>
                        <span id="co-shipping">Free</span>
                    </div>

                    <div class="co-sum__rule"></div>

                    <div class="co-sum__total">
                        <span>Total</span>
                        <b id="co-total">${{ number_format($cartTotal, 2) }}</b>
                    </div>
                </section>

                <section class="co-panel" aria-labelledby="co-pay-title">
                    <h2 class="co-panel__title" id="co-pay-title">Payment</h2>
                    <p class="co-panel__hint">Your card details are entered on the next step.</p>

                    {{-- Stripe is the only method enabled server-side, so it is
                         pre-selected: making someone tick the only option is friction. --}}
                    <label class="co-choice">
                        <input class="co-choice__input" type="radio" name="payment_method" value="stripe"
                               @checked(old('payment_method', 'stripe') === 'stripe')>
                        <span class="co-choice__mark" aria-hidden="true"></span>
                        <span>
                            <span class="co-choice__label">Debit / Credit card</span>
                            <span class="co-choice__note">Processed securely by Stripe</span>
                        </span>
                    </label>

                    <div style="margin-top:18px">
                        {{-- Unchecked by default: pre-ticked consent is a dark pattern and
                             non-compliant in the EU/UK. On redisplay after a failed submit we
                             honour what the customer actually sent. --}}
                        <label class="co-check">
                            <input class="co-check__input" type="checkbox" name="agree" value="1"
                                   id="agree" data-validate data-label="The terms" required
                                   @checked(old('agree'))
                                   @error('agree') aria-invalid="true" @enderror
                                   aria-describedby="agree-error">
                            <span class="co-check__box" aria-hidden="true">
                                <svg width="12" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </span>
                            <span class="co-check__text">
                                I agree to the <a href="{{ url('/terms-and-conditions') }}">Terms &amp; Conditions</a>
                                and <a href="{{ route('refund-policy') }}">Refund Policy</a>.
                            </span>
                        </label>
                        <p class="co-field__error @error('agree') is-visible @enderror" id="agree-error" role="alert">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                            <span data-error-text>@error('agree'){{ $message }}@enderror</span>
                        </p>
                    </div>

                    <button class="co-submit" type="submit" data-co-submit>
                        <svg class="co-submit__spinner" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9"/></svg>
                        <span>Continue to payment</span>
                    </button>

                    <a class="co-back" href="{{ route('cart') }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        Back to cart
                    </a>

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
    </form>

</x-checkout.shell>

@include('includes.footer')

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var form = document.querySelector('[data-co-form]');
    if (!form) return;

    var SUBTOTAL = {{ (float) $cartTotal }};

    /* ── Totals ─────────────────────────────────────────────────
       Cost comes from the radio's data-cost, rendered from the
       controller's $shippingOptions — no duplicated price table. */
    var shippingEl = document.getElementById('co-shipping');
    var totalEl    = document.getElementById('co-total');

    function money(n) { return '$' + n.toFixed(2); }

    function refreshTotals() {
        var checked = form.querySelector('input[name="shipping"]:checked');
        var cost    = checked ? parseFloat(checked.dataset.cost || '0') : 0;

        if (shippingEl) shippingEl.textContent = cost > 0 ? money(cost) : 'Free';
        if (totalEl)    totalEl.textContent    = money(SUBTOTAL + cost);
    }

    form.querySelectorAll('input[name="shipping"]').forEach(function (radio) {
        radio.addEventListener('change', refreshTotals);
    });
    refreshTotals();

    /* ── Validation ─────────────────────────────────────────── */
    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    function messageFor(input) {
        var label = input.dataset.label || 'This field';

        if (input.type === 'checkbox') {
            return input.required && !input.checked
                ? 'Please accept the terms before continuing.'
                : '';
        }

        var value = input.value.trim();
        if (input.required && !value) return label + ' is required.';
        if (!value) return '';
        if (input.type === 'email' && !EMAIL_RE.test(value)) {
            return 'Enter a valid email address, e.g. name@example.com';
        }
        if (input.type === 'tel' && value.replace(/\D/g, '').length < 6) {
            return 'Enter a phone number we can reach you on.';
        }
        return '';
    }

    function setError(input, message) {
        var box = document.getElementById(input.id + '-error');
        if (!box) return;
        var text = box.querySelector('[data-error-text]');

        if (message) {
            input.setAttribute('aria-invalid', 'true');
            if (text) text.textContent = message;
            box.classList.add('is-visible');
        } else {
            input.removeAttribute('aria-invalid');
            box.classList.remove('is-visible');
        }
    }

    var fields = form.querySelectorAll('[data-validate]');

    fields.forEach(function (input) {
        var event = input.type === 'checkbox' ? 'change' : 'blur';
        input.addEventListener(event, function () { setError(input, messageFor(input)); });

        input.addEventListener('input', function () {
            if (input.getAttribute('aria-invalid') === 'true' && !messageFor(input)) {
                setError(input, '');
            }
        });
    });

    /* ── Submit ─────────────────────────────────────────────── */
    var button = form.querySelector('[data-co-submit]');

    form.addEventListener('submit', function (event) {
        var firstInvalid = null;

        fields.forEach(function (input) {
            var message = messageFor(input);
            setError(input, message);
            if (message && !firstInvalid) firstInvalid = input;
        });

        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.focus();          // WCAG: focus the first invalid field
            firstInvalid.scrollIntoView({ block: 'center', behavior: 'smooth' });
            return;
        }

        if (button) {
            button.setAttribute('aria-busy', 'true');
            button.disabled = true;        // payment forms must not double-submit
        }
    });

    // bfcache can restore the disabled button — re-enable it.
    window.addEventListener('pageshow', function () {
        if (button) {
            button.removeAttribute('aria-busy');
            button.disabled = false;
        }
    });
})();
</script>
@endpush
