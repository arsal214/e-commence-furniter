{{-- resources/views/contact.blade.php --}}
@extends('layouts.main')

@section('title', 'Contact Us | PeytonGhalib')
@section('meta_description', 'Get in touch with PeytonGhalib. We\'re here to help with any questions about our products, orders, or services. Contact us today.')
@section('canonical', url('/contact'))

@push('schema')
@php
$schemaContact = ['@context'=>'https://schema.org','@type'=>'ContactPage','name'=>'Contact PeytonGhalib','url'=>url('/contact'),
    'description'=>'Get in touch with PeytonGhalib customer support for questions about orders, products, and shipping.',
    'breadcrumb'=>['@type'=>'BreadcrumbList','itemListElement'=>[
        ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Contact','item'=>url('/contact')],
    ]]];
@endphp
<script type="application/ld+json">{!! json_encode($schemaContact, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@push('styles')
<style>
/* ── Contact (ct-) ─────────────────────────────────────────────
   Gold #bb976d is an accent tone — it fails 4.5:1 on white, so text
   and links use the darker --ct-gold-ink. */
.ct {
    --ct-gold:      #bb976d;
    --ct-gold-soft: #d4a96a;
    --ct-gold-ink:  #8a6a3f;
    --ct-surface:   #ffffff;
    --ct-bg:        #faf9f7;
    --ct-border:    rgba(187, 151, 109, .24);
    --ct-text:      #1f1a15;
    --ct-muted:     #6b6157;
    --ct-danger:    #b42318;
    --ct-success:   #15803d;
    --ct-field:     #ffffff;

    background:
        radial-gradient(900px 400px at 85% -5%, rgba(187, 151, 109, .13), transparent 60%),
        var(--ct-bg);
    padding: 40px 0 80px;
    font-family: 'DM Sans', 'Poppins', ui-sans-serif, system-ui, sans-serif;
    color: var(--ct-text);
}
.dark .ct {
    --ct-surface: rgba(23, 36, 48, .5);
    --ct-bg:      #0a0806;
    --ct-border:  rgba(212, 169, 106, .2);
    --ct-text:    #f3ede4;
    --ct-muted:   #a89f93;
    --ct-danger:  #ff9c94;
    --ct-success: #6ee7a8;
    --ct-gold-ink:#d4a96a;
    --ct-field:   rgba(10, 8, 6, .4);
}

.ct__wrap { max-width: 1180px; margin: 0 auto; padding: 0 20px; }

.ct__crumbs { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--ct-muted); margin-bottom: 14px; }
.ct__crumbs a { color: var(--ct-muted); text-decoration: none; }
.ct__crumbs a:hover { color: var(--ct-gold-ink); }
.ct__crumbs [aria-current] { color: var(--ct-gold-ink); font-weight: 600; }

.ct__eyebrow {
    display: inline-block;
    font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    color: var(--ct-gold-ink);
    background: rgba(187, 151, 109, .12);
    border: 1px solid var(--ct-border);
    border-radius: 100px;
    padding: 5px 13px;
    margin-bottom: 12px;
}
.ct__title { font-size: clamp(26px, 3.6vw, 34px); font-weight: 700; letter-spacing: -.4px; margin: 0 0 10px; }
.ct__lede  { font-size: 16px; line-height: 1.7; color: var(--ct-muted); margin: 0 0 32px; max-width: 58ch; }

/* ── Layout ────────────────────────────────────────────────── */
.ct__grid { display: grid; grid-template-columns: 1fr; gap: 22px; align-items: start; }
@media (min-width: 1025px) { .ct__grid { grid-template-columns: 1fr 360px; gap: 28px; } }

.ct-panel {
    background: var(--ct-surface);
    border: 1px solid var(--ct-border);
    border-radius: 18px;
    padding: 24px;
}
@media (min-width: 640px) { .ct-panel { padding: 30px 32px; } }
.ct-panel + .ct-panel { margin-top: 18px; }

.ct-panel__title { font-size: 17px; font-weight: 700; margin: 0 0 4px; color: var(--ct-text); }
.ct-panel__hint  { font-size: 13.5px; color: var(--ct-muted); margin: 0 0 22px; }

/* ── Contact channels ──────────────────────────────────────── */
@media (min-width: 1025px) { .ct-aside { position: sticky; top: 24px; } }

.ct-channel { display: flex; align-items: flex-start; gap: 13px; }
.ct-channel + .ct-channel { margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--ct-border); }

.ct-channel__icon {
    display: grid; place-items: center;
    width: 42px; height: 42px; flex: none;
    border-radius: 12px;
    background: rgba(187, 151, 109, .14);
    color: var(--ct-gold-ink);
}
.ct-channel__k { display: block; font-size: 10.5px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--ct-muted); margin-bottom: 4px; }
.ct-channel__v { display: block; font-size: 14.5px; font-weight: 700; color: var(--ct-text); word-break: break-word; }
.ct-channel__v a { color: inherit; text-decoration: none; }
.ct-channel__v a:hover { color: var(--ct-gold-ink); text-decoration: underline; text-underline-offset: 3px; }
.ct-channel__note { display: block; font-size: 12.5px; color: var(--ct-muted); margin-top: 3px; }

.ct-help { margin-top: 4px; }
.ct-help__link {
    display: flex; align-items: center; gap: 9px;
    min-height: 44px; padding: 8px 10px;
    border-radius: 11px;
    font-size: 14px; font-weight: 600;
    color: var(--ct-text);
    text-decoration: none;
    transition: background .2s ease, color .2s ease;
}
.ct-help__link:hover { background: rgba(187, 151, 109, .1); color: var(--ct-gold-ink); }
.ct-help__link:focus-visible { outline: 2px solid var(--ct-gold); outline-offset: 2px; }
.ct-help__link svg { color: var(--ct-gold); flex: none; }
.ct-help__link span:last-child { margin-left: auto; color: var(--ct-muted); }

/* ── Fields ────────────────────────────────────────────────── */
.ct-grid { display: grid; grid-template-columns: 1fr; gap: 0 18px; }
@media (min-width: 640px) { .ct-grid { grid-template-columns: 1fr 1fr; } }

.ct-field { margin-bottom: 16px; }
.ct-field--full { grid-column: 1 / -1; }
.ct-field__label { display: block; font-size: 13.5px; font-weight: 600; color: var(--ct-text); margin-bottom: 7px; transition: color .2s ease; }
.ct-field:focus-within .ct-field__label { color: var(--ct-gold-ink); }
.ct-field__req { color: var(--ct-danger); margin-left: 2px; }

.ct-field__input, .ct-field__area, .ct-field__select {
    width: 100%;
    font-size: 16px;                /* prevents iOS zoom-on-focus */
    color: var(--ct-text);
    background: var(--ct-field);
    border: 1px solid var(--ct-border);
    border-radius: 12px;
    padding: 0 14px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.ct-field__input, .ct-field__select { height: 50px; }   /* >= 44px touch target */
.ct-field__area { height: 150px; padding: 13px 14px; resize: vertical; line-height: 1.6; }

.ct-field__select {
    appearance: none; -webkit-appearance: none;
    padding-right: 40px;
    cursor: pointer;
    /* caret drawn as a background so the control stays native-behaving */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b6157' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}
.dark .ct-field__select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23a89f93' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
}

.ct-field__input::placeholder, .ct-field__area::placeholder { color: var(--ct-muted); opacity: .75; }
.ct-field__input:hover, .ct-field__area:hover, .ct-field__select:hover { border-color: rgba(187, 151, 109, .5); }
.ct-field__input:focus-visible,
.ct-field__area:focus-visible,
.ct-field__select:focus-visible { border-color: var(--ct-gold); box-shadow: 0 0 0 4px rgba(187, 151, 109, .18); }

.ct-field__input[aria-invalid="true"],
.ct-field__area[aria-invalid="true"],
.ct-field__select[aria-invalid="true"] { border-color: var(--ct-danger); background-color: rgba(180, 35, 24, .04); }

.ct-field__error {
    display: none; align-items: center; gap: 5px;
    font-size: 12.5px; font-weight: 500; color: var(--ct-danger); margin-top: 6px;
}
.ct-field__error.is-visible { display: flex; }
.ct-field__error svg { flex: none; }

.ct-count { font-size: 12px; color: var(--ct-muted); text-align: right; margin: 6px 0 0; font-variant-numeric: tabular-nums; }

/* ── Submit ────────────────────────────────────────────────── */
.ct-actions { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 6px; }
.ct-terms { font-size: 12.5px; color: var(--ct-muted); margin: 0; max-width: 34ch; line-height: 1.55; }
.ct-terms a { color: var(--ct-gold-ink); font-weight: 600; text-decoration: none; }
.ct-terms a:hover { text-decoration: underline; text-underline-offset: 3px; }

.ct-submit {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: 52px; padding: 0 26px;
    font-size: 15px; font-weight: 700;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a 0%, #bb976d 55%, #a9855c 100%);
    border: 0; border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 6px 20px -6px rgba(187, 151, 109, .55);
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s ease, opacity .2s ease;
}
.ct-submit:hover  { transform: translateY(-2px); box-shadow: 0 12px 28px -8px rgba(187, 151, 109, .7); }
.ct-submit:active { transform: translateY(0) scale(.99); }
.ct-submit:focus-visible { outline: 2px solid var(--ct-gold-ink); outline-offset: 3px; }
.ct-submit[disabled], .ct-submit[aria-busy="true"] { opacity: .7; cursor: not-allowed; transform: none; box-shadow: none; }
.ct-submit__spinner { display: none; animation: ct-spin .7s linear infinite; }
.ct-submit[aria-busy="true"] .ct-submit__spinner { display: block; }
@keyframes ct-spin { to { transform: rotate(360deg); } }

/* ── Banners ───────────────────────────────────────────────── */
.ct-banner {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 13px 15px; margin-bottom: 20px;
    border-radius: 12px;
    font-size: 14px; line-height: 1.55;
}
.ct-banner[hidden] { display: none; }
.ct-banner svg { flex: none; margin-top: 1px; }
.ct-banner--error   { color: var(--ct-danger);  background: rgba(180, 35, 24, .07); border: 1px solid rgba(180, 35, 24, .25); }
.ct-banner--success { color: var(--ct-success); background: rgba(21, 128, 61, .08); border: 1px solid rgba(21, 128, 61, .28); }
.dark .ct-banner--error   { background: rgba(255, 156, 148, .08); border-color: rgba(255, 156, 148, .28); }
.dark .ct-banner--success { background: rgba(110, 231, 168, .08); border-color: rgba(110, 231, 168, .3); }

/* Sent — replaces the form entirely, so nobody re-submits by reflex */
.ct-sent { text-align: center; padding: 34px 16px; }
.ct-sent[hidden] { display: none; }
.ct-sent__icon {
    display: grid; place-items: center;
    width: 60px; height: 60px; margin: 0 auto 16px;
    border-radius: 50%;
    background: rgba(21, 128, 61, .1);
    color: var(--ct-success);
    animation: ct-pop .4s cubic-bezier(.34, 1.56, .64, 1) both;
}
@keyframes ct-pop { from { opacity: 0; transform: scale(.6); } to { opacity: 1; transform: none; } }
.ct-sent__title { font-size: 19px; font-weight: 700; margin: 0 0 8px; color: var(--ct-text); }
.ct-sent__text  { font-size: 14.5px; line-height: 1.65; color: var(--ct-muted); margin: 0 0 20px; }

.ct-ghost {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    min-height: 44px; padding: 0 18px;
    font-size: 14px; font-weight: 600;
    color: var(--ct-text);
    background: transparent;
    border: 1px solid var(--ct-border);
    border-radius: 11px;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s ease, border-color .2s ease;
}
.ct-ghost:hover { background: rgba(187, 151, 109, .08); border-color: var(--ct-gold); }
.ct-ghost:focus-visible { outline: 2px solid var(--ct-gold); outline-offset: 2px; }

.ct-sr { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }

@media (prefers-reduced-motion: reduce) {
    .ct *, .ct *::before, .ct *::after { transition-duration: .01ms !important; animation: none !important; }
}
</style>
@endpush

@section('content')

@include('includes.navbar')

<div class="ct">
    <div class="ct__wrap">

        <nav class="ct__crumbs" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Contact</span>
        </nav>

        <span class="ct__eyebrow">Get in touch</span>
        <h1 class="ct__title">We'd love to hear from you</h1>
        <p class="ct__lede">
            A question about an order, a product, a return — or just to say hello.
            Send us a message and we'll reply within 24 hours.
        </p>

        <div class="ct__grid">

            {{-- ── Form ─────────────────────────────────────── --}}
            <div>
                <section class="ct-panel" aria-labelledby="ct-form-title">

                    {{-- Shown after a successful send; replaces the form --}}
                    <div class="ct-sent" id="ct-sent" role="status" hidden>
                        <div class="ct-sent__icon" aria-hidden="true">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        <p class="ct-sent__title">Message sent</p>
                        <p class="ct-sent__text" data-sent-text>Thanks — we'll get back to you within 24 hours.</p>
                        <button class="ct-ghost" type="button" data-send-another>Send another message</button>
                    </div>

                    <div id="ct-form-wrap">
                        <h2 class="ct-panel__title" id="ct-form-title">Send us a message</h2>
                        <p class="ct-panel__hint">All fields are required.</p>

                        <div class="ct-banner ct-banner--error" id="ct-error" role="alert" hidden>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                            <span data-error-summary></span>
                        </div>

                        <form method="POST" action="{{ route('contact.send') }}" id="ct-form" data-ct-form novalidate>
                            @csrf

                            <div class="ct-grid">
                                <div class="ct-field">
                                    <label class="ct-field__label" for="name">
                                        Full name <span class="ct-field__req" aria-hidden="true">*</span><span class="ct-sr">(required)</span>
                                    </label>
                                    <input class="ct-field__input" id="name" name="name" type="text"
                                           value="{{ old('name', auth()->user()?->name) }}"
                                           placeholder="Jane Doe" autocomplete="name"
                                           data-validate data-label="Full name" required
                                           aria-describedby="name-error">
                                    <p class="ct-field__error" id="name-error" role="alert">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                        <span data-error-text></span>
                                    </p>
                                </div>

                                <div class="ct-field">
                                    <label class="ct-field__label" for="email">
                                        Email address <span class="ct-field__req" aria-hidden="true">*</span><span class="ct-sr">(required)</span>
                                    </label>
                                    <input class="ct-field__input" id="email" name="email" type="email" inputmode="email"
                                           value="{{ old('email', auth()->user()?->email) }}"
                                           placeholder="name@example.com" autocomplete="email"
                                           data-validate data-label="Email address" required
                                           aria-describedby="email-error">
                                    <p class="ct-field__error" id="email-error" role="alert">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                        <span data-error-text></span>
                                    </p>
                                </div>

                                <div class="ct-field">
                                    <label class="ct-field__label" for="number">
                                        Phone number <span class="ct-field__req" aria-hidden="true">*</span><span class="ct-sr">(required)</span>
                                    </label>
                                    {{-- type=tel, not number: a phone has spaces and a leading + --}}
                                    <input class="ct-field__input" id="number" name="number" type="tel" inputmode="tel"
                                           value="{{ old('number') }}"
                                           placeholder="+1 555 000 0000" autocomplete="tel"
                                           data-validate data-label="Phone number" required
                                           aria-describedby="number-error">
                                    <p class="ct-field__error" id="number-error" role="alert">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                        <span data-error-text></span>
                                    </p>
                                </div>

                                <div class="ct-field">
                                    <label class="ct-field__label" for="subject">
                                        Subject <span class="ct-field__req" aria-hidden="true">*</span><span class="ct-sr">(required)</span>
                                    </label>
                                    <select class="ct-field__select" id="subject" name="subject"
                                            data-validate data-label="Subject" required
                                            aria-describedby="subject-error">
                                        @foreach (['Order Inquiry', 'Payment Problem', 'Shipping & Delivery', 'Return & Refund', 'Product Question', 'Other'] as $option)
                                            <option value="{{ $option }}" @selected(old('subject') === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    <p class="ct-field__error" id="subject-error" role="alert">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                        <span data-error-text></span>
                                    </p>
                                </div>

                                <div class="ct-field ct-field--full">
                                    <label class="ct-field__label" for="Message">
                                        Your message <span class="ct-field__req" aria-hidden="true">*</span><span class="ct-sr">(required)</span>
                                    </label>
                                    <textarea class="ct-field__area" id="Message" name="Message"
                                              placeholder="Tell us what's on your mind…"
                                              maxlength="5000"
                                              data-validate data-label="Your message" required
                                              aria-describedby="Message-error Message-count">{{ old('Message') }}</textarea>
                                    <p class="ct-count" id="Message-count" aria-live="polite"><span data-count>0</span> / 5000</p>
                                    <p class="ct-field__error" id="Message-error" role="alert">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                                        <span data-error-text></span>
                                    </p>
                                </div>
                            </div>

                            <div class="ct-actions">
                                <p class="ct-terms">
                                    By submitting, you agree to our
                                    <a href="{{ url('/terms-and-conditions') }}">Terms &amp; Conditions</a>.
                                </p>

                                <button class="ct-submit" type="submit" data-ct-submit>
                                    <svg class="ct-submit__spinner" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9"/></svg>
                                    <span data-submit-label>Send message</span>
                                </button>
                            </div>
                        </form>
                    </div>

                </section>
            </div>

            {{-- ── Channels ─────────────────────────────────── --}}
            <div class="ct-aside">
                <section class="ct-panel" aria-labelledby="ct-reach-title">
                    <h2 class="ct-panel__title" id="ct-reach-title">Reach us directly</h2>
                    <p class="ct-panel__hint">Prefer email? We're one click away.</p>

                    <div class="ct-channel">
                        <span class="ct-channel__icon" aria-hidden="true">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></svg>
                        </span>
                        <span style="min-width:0">
                            <span class="ct-channel__k">Email us</span>
                            <span class="ct-channel__v">
                                <a href="mailto:peytonexpress44@gmail.com">peytonexpress44@gmail.com</a>
                            </span>
                            <span class="ct-channel__note">We reply within 24 hours</span>
                        </span>
                    </div>

                    <div class="ct-channel">
                        <span class="ct-channel__icon" aria-hidden="true">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        </span>
                        <span>
                            <span class="ct-channel__k">Support hours</span>
                            <span class="ct-channel__v">Mon – Fri, 9am – 6pm</span>
                            <span class="ct-channel__note">Limited support at weekends</span>
                        </span>
                    </div>

                    <div class="ct-channel">
                        <span class="ct-channel__icon" aria-hidden="true">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h8l-1 8 10-12h-8z"/></svg>
                        </span>
                        <span>
                            <span class="ct-channel__k">Response time</span>
                            <span class="ct-channel__v">Under 24 hours</span>
                            <span class="ct-channel__note">For every enquiry</span>
                        </span>
                    </div>
                </section>

                <section class="ct-panel" aria-labelledby="ct-quick-title">
                    <h2 class="ct-panel__title" id="ct-quick-title">Answers, faster</h2>
                    <p class="ct-panel__hint">Most questions are covered here.</p>

                    <nav class="ct-help" aria-label="Help links">
                        <a class="ct-help__link" href="{{ route('track-order') }}">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/></svg>
                            <span>Track my order</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <a class="ct-help__link" href="{{ url('/faq') }}">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.2 9a2.9 2.9 0 0 1 5.6 1c0 2-2.8 2.5-2.8 2.5M12 17h.01"/></svg>
                            <span>Read the FAQ</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <a class="ct-help__link" href="{{ route('shipping-policy') }}">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                            <span>Shipping policy</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <a class="ct-help__link" href="{{ route('return-policy') }}">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                            <span>Returns &amp; refunds</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </nav>
                </section>
            </div>

        </div>
    </div>
</div>

@include('includes.footer')

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var form = document.querySelector('[data-ct-form]');
    if (!form) return;

    var button   = form.querySelector('[data-ct-submit]');
    var label    = form.querySelector('[data-submit-label]');
    var banner   = document.getElementById('ct-error');
    var summary  = banner ? banner.querySelector('[data-error-summary]') : null;
    var formWrap = document.getElementById('ct-form-wrap');
    var sent     = document.getElementById('ct-sent');
    var sentText = sent ? sent.querySelector('[data-sent-text]') : null;

    /* ── Character counter ──────────────────────────────────── */
    var message = document.getElementById('Message');
    var counter = document.querySelector('[data-count]');

    function refreshCount() {
        if (counter && message) counter.textContent = String(message.value.length);
    }
    if (message) message.addEventListener('input', refreshCount);
    refreshCount();

    /* ── Validation ─────────────────────────────────────────── */
    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    function messageFor(field) {
        var value = (field.value || '').trim();
        var name  = field.dataset.label || 'This field';

        if (field.required && !value) return name + ' is required.';
        if (!value) return '';
        if (field.type === 'email' && !EMAIL_RE.test(value)) {
            return 'Enter a valid email address, e.g. name@example.com';
        }
        if (field.type === 'tel' && value.replace(/\D/g, '').length < 6) {
            return 'Enter a phone number we can reach you on.';
        }
        return '';
    }

    function setError(field, text) {
        var box = document.getElementById(field.id + '-error');
        if (!box) return;
        var slot = box.querySelector('[data-error-text]');

        if (text) {
            field.setAttribute('aria-invalid', 'true');
            if (slot) slot.textContent = text;
            box.classList.add('is-visible');
        } else {
            field.removeAttribute('aria-invalid');
            box.classList.remove('is-visible');
        }
    }

    var fields = form.querySelectorAll('[data-validate]');

    fields.forEach(function (field) {
        field.addEventListener('blur', function () { setError(field, messageFor(field)); });
        field.addEventListener('input', function () {
            if (field.getAttribute('aria-invalid') === 'true' && !messageFor(field)) setError(field, '');
        });
    });

    function setBusy(busy) {
        if (!button) return;
        button.disabled = busy;
        if (busy) {
            button.setAttribute('aria-busy', 'true');
            if (label) label.textContent = 'Sending…';
        } else {
            button.removeAttribute('aria-busy');
            if (label) label.textContent = 'Send message';
        }
    }

    function showBanner(text) {
        if (!banner) return;
        if (summary) summary.textContent = text;
        banner.hidden = false;
        banner.scrollIntoView({ block: 'center', behavior: 'smooth' });
    }

    /* ── Submit ─────────────────────────────────────────────────
       The endpoint answers JSON, so post with fetch and render the
       result in place — a plain submit would dump JSON in the browser. */
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (banner) banner.hidden = true;

        var firstInvalid = null;
        fields.forEach(function (field) {
            var text = messageFor(field);
            setError(field, text);
            if (text && !firstInvalid) firstInvalid = field;
        });

        if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ block: 'center', behavior: 'smooth' });
            return;
        }

        setBusy(true);

        try {
            var response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            var payload = await response.json().catch(function () { return {}; });

            // 422: Laravel's field-level validation errors
            if (response.status === 422 && payload.errors) {
                var first = null;

                Object.keys(payload.errors).forEach(function (key) {
                    var field = form.querySelector('[name="' + key + '"]');
                    if (field) {
                        setError(field, payload.errors[key][0]);
                        if (!first) first = field;
                    }
                });

                showBanner(payload.message || 'Please check the highlighted fields.');
                if (first) first.focus();
                setBusy(false);
                return;
            }

            if (!response.ok || payload.success === false) {
                showBanner(payload.message || 'Something went wrong. Please try again.');
                setBusy(false);
                return;
            }

            // Success: swap the form out so nobody re-sends by reflex
            if (sentText && payload.message) sentText.textContent = payload.message;
            if (formWrap) formWrap.hidden = true;
            if (sent) {
                sent.hidden = false;
                sent.scrollIntoView({ block: 'center', behavior: 'smooth' });
            }
        } catch (error) {
            showBanner('We could not reach the server. Check your connection and try again.');
            setBusy(false);
        }
    });

    /* ── Send another ───────────────────────────────────────── */
    var again = document.querySelector('[data-send-another]');
    if (again) {
        again.addEventListener('click', function () {
            form.reset();
            fields.forEach(function (field) { setError(field, ''); });
            refreshCount();
            setBusy(false);
            if (sent) sent.hidden = true;
            if (formWrap) formWrap.hidden = false;
            var name = document.getElementById('name');
            if (name) name.focus();
        });
    }
})();
</script>
@endpush
