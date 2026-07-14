{{--
    Checkout shell — frame shared by the details step (checkout) and the
    payment step (checkout-stripe). Holds the breadcrumb, title, progress
    steps, and all checkout CSS (pushed @once).

    Props: step   details | payment
           title  page heading
--}}
@props([
    'step'  => 'details',
    'title' => 'Checkout',
])

@once
@push('styles')
<style>
/* ── Checkout (co-) ────────────────────────────────────────────
   Gold #bb976d is an accent tone only — it fails 4.5:1 on white,
   so link/label text uses the darker --co-gold-ink. */
.co {
    --co-gold:      #bb976d;
    --co-gold-ink:  #8a6a3f;
    --co-surface:   #ffffff;
    --co-bg:        #faf9f7;
    --co-border:    rgba(187, 151, 109, .24);
    --co-text:      #1f1a15;
    --co-muted:     #6b6157;
    --co-danger:    #b42318;
    --co-success:   #15803d;
    --co-field:     #ffffff;

    background:
        radial-gradient(900px 400px at 85% -5%, rgba(187, 151, 109, .13), transparent 60%),
        var(--co-bg);
    padding: 36px 0 80px;
    font-family: 'DM Sans', 'Poppins', ui-sans-serif, system-ui, sans-serif;
    color: var(--co-text);
}
.dark .co {
    --co-surface: rgba(23, 36, 48, .5);
    --co-bg:      #0a0806;
    --co-border:  rgba(212, 169, 106, .2);
    --co-text:    #f3ede4;
    --co-muted:   #a89f93;
    --co-danger:  #ff9c94;
    --co-success: #6ee7a8;
    --co-gold-ink:#d4a96a;
    --co-field:   rgba(10, 8, 6, .4);
}

.co__wrap { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
.co__wrap--narrow { max-width: 900px; }

/* ── Header + steps ────────────────────────────────────────── */
.co__crumbs { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--co-muted); margin-bottom: 14px; }
.co__crumbs a { color: var(--co-muted); text-decoration: none; }
.co__crumbs a:hover { color: var(--co-gold-ink); }
.co__crumbs [aria-current] { color: var(--co-gold-ink); font-weight: 600; }

.co__title { font-size: clamp(24px, 3.4vw, 30px); font-weight: 700; letter-spacing: -.3px; margin: 0 0 20px; }

.co-steps { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 26px; list-style: none; padding: 0; }
.co-step { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--co-muted); }
.co-step__dot {
    display: grid; place-items: center;
    width: 24px; height: 24px; border-radius: 50%;
    font-size: 11.5px; font-weight: 700;
    border: 1.5px solid var(--co-border);
    background: var(--co-surface);
    color: var(--co-muted);
}
.co-step--done .co-step__dot    { background: var(--co-gold); border-color: var(--co-gold); color: #0a0806; }
.co-step--done                  { color: var(--co-gold-ink); }
.co-step--current .co-step__dot { border-color: var(--co-gold); color: var(--co-gold-ink); box-shadow: 0 0 0 4px rgba(187, 151, 109, .16); }
.co-step--current               { color: var(--co-text); }
.co-steps__sep { width: 22px; height: 1px; background: var(--co-border); }

/* ── Layout ────────────────────────────────────────────────── */
.co__grid { display: grid; grid-template-columns: 1fr; gap: 22px; align-items: start; }
@media (min-width: 1025px) { .co__grid { grid-template-columns: 1fr 400px; gap: 28px; } }

.co-panel {
    background: var(--co-surface);
    border: 1px solid var(--co-border);
    border-radius: 18px;
    padding: 22px;
}
@media (min-width: 640px) { .co-panel { padding: 26px 28px; } }
.co-panel + .co-panel { margin-top: 18px; }

.co-panel__title { font-size: 16px; font-weight: 700; margin: 0 0 4px; color: var(--co-text); }
.co-panel__hint  { font-size: 13px; color: var(--co-muted); margin: 0 0 18px; }

/* ── Fields ────────────────────────────────────────────────── */
.co-grid { display: grid; grid-template-columns: 1fr; gap: 0 18px; }
@media (min-width: 640px) { .co-grid { grid-template-columns: 1fr 1fr; } }

.co-field { margin-bottom: 15px; }
.co-field--full { grid-column: 1 / -1; }
.co-field__label { display: block; font-size: 13.5px; font-weight: 600; color: var(--co-text); margin-bottom: 7px; transition: color .2s ease; }
.co-field:focus-within .co-field__label { color: var(--co-gold-ink); }
.co-field__req { color: var(--co-danger); margin-left: 2px; }

.co-field__input, .co-field__area {
    width: 100%;
    font-size: 16px;                /* prevents iOS zoom-on-focus */
    color: var(--co-text);
    background: var(--co-field);
    border: 1px solid var(--co-border);
    border-radius: 12px;
    padding: 0 14px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.co-field__input { height: 50px; }                       /* >= 44px touch target */
.co-field__area  { height: 104px; padding: 13px 14px; resize: vertical; line-height: 1.5; }
.co-field__input::placeholder, .co-field__area::placeholder { color: var(--co-muted); opacity: .75; }
.co-field__input:hover, .co-field__area:hover { border-color: rgba(187, 151, 109, .5); }
.co-field__input:focus-visible, .co-field__area:focus-visible { border-color: var(--co-gold); box-shadow: 0 0 0 4px rgba(187, 151, 109, .18); }
.co-field__input[aria-invalid="true"], .co-field__area[aria-invalid="true"] { border-color: var(--co-danger); background: rgba(180, 35, 24, .04); }

.co-field__error {
    display: none; align-items: center; gap: 5px;
    font-size: 12.5px; font-weight: 500; color: var(--co-danger); margin-top: 6px;
}
.co-field__error.is-visible { display: flex; }
.co-field__error svg { flex: none; }

/* ── Summary (sticky) ──────────────────────────────────────── */
@media (min-width: 1025px) { .co-summary { position: sticky; top: 24px; } }

.co-sum__items { list-style: none; margin: 0 0 4px; padding: 0; display: grid; gap: 12px; }
.co-sum__item  { display: flex; align-items: center; gap: 12px; }

.co-sum__figure { position: relative; flex: none; }
.co-sum__thumb {
    width: 54px; height: 54px;
    border-radius: 11px; object-fit: cover;
    border: 1px solid var(--co-border);
    background: rgba(187, 151, 109, .12);
}
.co-sum__qty {
    position: absolute; top: -7px; right: -7px;
    min-width: 21px; height: 21px; padding: 0 5px;
    display: grid; place-items: center;
    border-radius: 100px;
    background: var(--co-gold); color: #0a0806;
    font-size: 11px; font-weight: 700;
    font-variant-numeric: tabular-nums;
    border: 2px solid var(--co-surface);
}
.co-sum__name { display: block; font-size: 14px; font-weight: 600; color: var(--co-text); margin: 0 0 3px; }
.co-sum__variant { display: block; font-size: 12px; color: var(--co-muted); margin: 0; }
.co-sum__line { margin-left: auto; font-size: 14.5px; font-weight: 700; white-space: nowrap; font-variant-numeric: tabular-nums; }

.co-sum__rule { height: 1px; background: var(--co-border); margin: 18px 0; }

.co-sum__row {
    display: flex; align-items: center; justify-content: space-between;
    font-size: 14px; color: var(--co-muted); margin-bottom: 9px;
}
.co-sum__row span:last-child { color: var(--co-text); font-weight: 600; font-variant-numeric: tabular-nums; }

.co-sum__total {
    display: flex; align-items: baseline; justify-content: space-between;
    font-size: 15px; font-weight: 700; color: var(--co-text);
}
.co-sum__total b { font-size: 24px; font-variant-numeric: tabular-nums; }

/* ── Radio / choice cards ──────────────────────────────────── */
.co-choice {
    display: flex; align-items: center; gap: 11px;
    padding: 13px 14px;
    border: 1px solid var(--co-border);
    border-radius: 12px;
    background: var(--co-field);
    cursor: pointer;
    transition: border-color .2s ease, background .2s ease;
}
.co-choice + .co-choice { margin-top: 9px; }
.co-choice:hover { border-color: rgba(187, 151, 109, .55); }
.co-choice__input { position: absolute; opacity: 0; width: 0; height: 0; }
.co-choice__mark {
    display: grid; place-items: center;
    width: 19px; height: 19px; flex: none;
    border: 1.5px solid var(--co-border);
    border-radius: 50%;
    transition: border-color .2s ease;
}
.co-choice__mark::after {
    content: ''; width: 9px; height: 9px; border-radius: 50%;
    background: var(--co-gold);
    transform: scale(0);
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1);
}
.co-choice__input:checked + .co-choice__mark { border-color: var(--co-gold); }
.co-choice__input:checked + .co-choice__mark::after { transform: scale(1); }
.co-choice__input:focus-visible + .co-choice__mark { outline: 2px solid var(--co-gold); outline-offset: 2px; }
/* Selected card: border + tint + filled dot — not colour alone */
.co-choice:has(.co-choice__input:checked) { border-color: var(--co-gold); background: rgba(187, 151, 109, .09); }

.co-choice__label { font-size: 14px; font-weight: 600; color: var(--co-text); }
.co-choice__note  { display: block; font-size: 12.5px; font-weight: 400; color: var(--co-muted); margin-top: 3px; }
.co-choice__price { margin-left: auto; font-size: 14px; font-weight: 700; color: var(--co-text); font-variant-numeric: tabular-nums; }

/* ── Terms checkbox ────────────────────────────────────────── */
.co-check { display: flex; align-items: flex-start; gap: 10px; cursor: pointer; }
.co-check__input { position: absolute; opacity: 0; width: 0; height: 0; }
.co-check__box {
    display: grid; place-items: center;
    width: 20px; height: 20px; flex: none; margin-top: 1px;
    border: 1.5px solid var(--co-border);
    border-radius: 6px;
    background: var(--co-field);
    transition: background .2s ease, border-color .2s ease;
}
.co-check__box svg { opacity: 0; transform: scale(.6); transition: opacity .18s ease, transform .18s cubic-bezier(.34, 1.56, .64, 1); color: #0a0806; }
.co-check__input:checked + .co-check__box { background: var(--co-gold); border-color: var(--co-gold); }
.co-check__input:checked + .co-check__box svg { opacity: 1; transform: scale(1); }
.co-check__input:focus-visible + .co-check__box { outline: 2px solid var(--co-gold); outline-offset: 2px; }
.co-check__input[aria-invalid="true"] + .co-check__box { border-color: var(--co-danger); }
.co-check__text { font-size: 13.5px; color: var(--co-muted); line-height: 1.5; }
.co-check__text a { color: var(--co-gold-ink); font-weight: 600; text-decoration: none; }
.co-check__text a:hover { text-decoration: underline; text-underline-offset: 3px; }

/* ── Actions ───────────────────────────────────────────────── */
.co-submit {
    position: relative;
    width: 100%;
    min-height: 52px;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    margin-top: 16px;
    font-size: 15px; font-weight: 700;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a 0%, #bb976d 55%, #a9855c 100%);
    border: 0; border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 6px 20px -6px rgba(187, 151, 109, .55);
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s ease, opacity .2s ease;
}
.co-submit:hover  { transform: translateY(-2px); box-shadow: 0 12px 28px -8px rgba(187, 151, 109, .7); }
.co-submit:active { transform: translateY(0) scale(.99); }
.co-submit:focus-visible { outline: 2px solid var(--co-gold-ink); outline-offset: 3px; }
.co-submit[disabled], .co-submit[aria-busy="true"] { opacity: .7; cursor: not-allowed; transform: none; box-shadow: none; }
.co-submit__spinner { display: none; animation: co-spin .7s linear infinite; }
.co-submit[aria-busy="true"] .co-submit__spinner { display: block; }
@keyframes co-spin { to { transform: rotate(360deg); } }

.co-back {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; min-height: 44px; margin-top: 10px;
    font-size: 13.5px; font-weight: 600;
    color: var(--co-muted); text-decoration: none;
    border-radius: 10px;
    transition: color .2s ease;
}
.co-back:hover { color: var(--co-gold-ink); }
.co-back:focus-visible { outline: 2px solid var(--co-gold); outline-offset: 2px; }

.co-secure {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    font-size: 12.5px; color: var(--co-muted);
    margin-top: 14px;
}
.co-secure svg { color: var(--co-success); flex: none; }

.co-cards { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
.co-cards img { height: 22px; width: auto; opacity: .75; }

/* ── Stripe payment element ────────────────────────────────── */
.co-stripe {
    padding: 16px;
    border: 1px solid var(--co-border);
    border-radius: 14px;
    background: var(--co-field);
    min-height: 220px;              /* reserve space so mounting doesn't shift layout */
}
.co-stripe__skeleton { display: grid; gap: 12px; }
.co-stripe__bar {
    height: 46px; border-radius: 10px;
    background: linear-gradient(90deg, rgba(187,151,109,.10) 25%, rgba(187,151,109,.20) 37%, rgba(187,151,109,.10) 63%);
    background-size: 400% 100%;
    animation: co-shimmer 1.4s ease infinite;
}
.co-stripe__bar:nth-child(2) { width: 70%; }
.co-stripe__bar:nth-child(3) { width: 45%; }
@keyframes co-shimmer { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }

.co-review { display: grid; gap: 10px; }
.co-review__row { display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: var(--co-muted); line-height: 1.5; }
.co-review__row svg { color: var(--co-gold); flex: none; margin-top: 2px; }
.co-review__row strong { display: block; color: var(--co-text); font-weight: 600; margin-bottom: 2px; }

/* ── Cart line items ───────────────────────────────────────── */
.co-lines { list-style: none; margin: 0; padding: 0; }
.co-line {
    display: grid;
    grid-template-columns: 76px 1fr;
    gap: 14px;
    padding: 16px 0;
    align-items: center;
}
.co-line + .co-line { border-top: 1px solid var(--co-border); }
@media (min-width: 720px) {
    .co-line { grid-template-columns: 76px 1fr auto auto auto; gap: 16px; }
}

.co-line__thumb {
    width: 76px; height: 76px;
    border-radius: 12px; object-fit: cover;
    border: 1px solid var(--co-border);
    background: rgba(187, 151, 109, .12);
}
.co-line__name {
    font-size: 15px; font-weight: 600; margin: 0 0 4px;
    color: var(--co-text);
}
.co-line__name a { color: inherit; text-decoration: none; }
.co-line__name a:hover { color: var(--co-gold-ink); }
.co-line__variant { font-size: 12.5px; color: var(--co-muted); margin: 0 0 4px; }
.co-line__unit { font-size: 13px; color: var(--co-muted); margin: 0; font-variant-numeric: tabular-nums; }

.co-line__total {
    font-size: 15.5px; font-weight: 700; color: var(--co-text);
    font-variant-numeric: tabular-nums; white-space: nowrap;
    min-width: 84px; text-align: right;
}

/* Quantity stepper — 44px targets, no layout shift on press */
.co-qty { display: inline-flex; align-items: center; gap: 2px; }
.co-qty__btn {
    display: grid; place-items: center;
    width: 40px; height: 44px;
    border: 1px solid var(--co-border);
    background: var(--co-field);
    color: var(--co-text);
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, color .2s ease;
}
.co-qty__btn:first-child { border-radius: 10px 0 0 10px; }
.co-qty__btn:last-child  { border-radius: 0 10px 10px 0; }
.co-qty__btn:hover:not([disabled]) { background: rgba(187, 151, 109, .12); border-color: var(--co-gold); color: var(--co-gold-ink); }
.co-qty__btn:focus-visible { outline: 2px solid var(--co-gold); outline-offset: -2px; z-index: 1; }
.co-qty__btn[disabled] { opacity: .45; cursor: not-allowed; }

.co-qty__input {
    width: 48px; height: 44px;
    border: 1px solid var(--co-border);
    border-left: 0; border-right: 0;
    background: var(--co-field);
    color: var(--co-text);
    text-align: center;
    font-size: 15px; font-weight: 600;
    font-variant-numeric: tabular-nums;
    outline: none;
    -moz-appearance: textfield;
}
.co-qty__input::-webkit-outer-spin-button,
.co-qty__input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.co-qty__input:focus-visible { border-color: var(--co-gold); box-shadow: 0 0 0 3px rgba(187, 151, 109, .16); z-index: 1; position: relative; }

.co-line__remove {
    display: grid; place-items: center;
    width: 44px; height: 44px;
    border: 0; border-radius: 10px;
    background: transparent;
    color: var(--co-muted);
    cursor: pointer;
    transition: background .2s ease, color .2s ease;
}
.co-line__remove:hover { background: rgba(180, 35, 24, .1); color: var(--co-danger); }
.co-line__remove:focus-visible { outline: 2px solid var(--co-danger); outline-offset: 2px; }

.co-note {
    display: flex; align-items: center; gap: 8px;
    font-size: 12.5px; color: var(--co-muted);
    margin-top: 4px;
}
.co-note svg { color: var(--co-gold); flex: none; }

/* ── Empty state ───────────────────────────────────────────── */
.co-empty { text-align: center; padding: 44px 20px; }
.co-empty__icon {
    display: grid; place-items: center;
    width: 62px; height: 62px; margin: 0 auto 16px;
    border-radius: 50%;
    background: rgba(187, 151, 109, .12);
    color: var(--co-gold);
}
.co-empty__title { font-size: 18px; font-weight: 700; margin: 0 0 6px; color: var(--co-text); }
.co-empty__text  { font-size: 14px; color: var(--co-muted); margin: 0 0 20px; }
.co-empty .co-submit { max-width: 240px; margin-left: auto; margin-right: auto; }

/* ── Sign-in modal ─────────────────────────────────────────── */
.co-modal { position: fixed; inset: 0; z-index: 9999; display: grid; place-items: center; padding: 20px; }
.co-modal[hidden] { display: none; }
.co-modal__scrim {
    position: absolute; inset: 0;
    background: rgba(10, 8, 6, .62);          /* strong enough to isolate the dialog */
    -webkit-backdrop-filter: blur(3px);
    backdrop-filter: blur(3px);
}
.co-modal__box {
    position: relative;
    width: 100%; max-width: 26rem;
    background: var(--co-surface);
    border: 1px solid var(--co-border);
    border-radius: 20px;
    padding: 30px 26px 26px;
    text-align: center;
    box-shadow: 0 30px 70px -20px rgba(10, 8, 6, .5);
    animation: co-pop .25s cubic-bezier(.22, 1, .36, 1) both;
}
@keyframes co-pop { from { opacity: 0; transform: scale(.95) translateY(8px); } to { opacity: 1; transform: none; } }

.co-modal__close {
    position: absolute; top: 10px; right: 10px;
    display: grid; place-items: center;
    width: 44px; height: 44px;
    border: 0; border-radius: 10px;
    background: transparent; color: var(--co-muted);
    cursor: pointer;
    transition: background .2s ease, color .2s ease;
}
.co-modal__close:hover { background: rgba(187, 151, 109, .12); color: var(--co-text); }
.co-modal__close:focus-visible { outline: 2px solid var(--co-gold); outline-offset: 2px; }

.co-modal__icon {
    display: grid; place-items: center;
    width: 56px; height: 56px; margin: 0 auto 14px;
    border-radius: 50%;
    background: rgba(187, 151, 109, .14);
    color: var(--co-gold-ink);
}
.co-modal__title { font-size: 19px; font-weight: 700; margin: 0 0 6px; color: var(--co-text); }
.co-modal__text  { font-size: 14px; color: var(--co-muted); margin: 0 0 20px; }
.co-modal__actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.co-modal__actions .co-submit { margin-top: 0; }

.co-ghost {
    display: inline-flex; align-items: center; justify-content: center;
    min-height: 52px; padding: 0 18px;
    font-size: 15px; font-weight: 700;
    color: var(--co-text);
    background: transparent;
    border: 1px solid var(--co-border);
    border-radius: 12px;
    text-decoration: none;
    transition: background .2s ease, border-color .2s ease;
}
.co-ghost:hover { background: rgba(187, 151, 109, .08); border-color: var(--co-gold); }
.co-ghost:focus-visible { outline: 2px solid var(--co-gold); outline-offset: 2px; }

/* ── Banners ───────────────────────────────────────────────── */
.co-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 15px; margin-bottom: 20px;
    border-radius: 12px; font-size: 14px;
    color: var(--co-danger);
    background: rgba(180, 35, 24, .07);
    border: 1px solid rgba(180, 35, 24, .25);
}
.dark .co-alert { background: rgba(255, 156, 148, .08); border-color: rgba(255, 156, 148, .28); }
.co-alert svg { flex: none; margin-top: 1px; }
.co-alert.is-hidden { display: none; }

.co-sr { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }

@media (prefers-reduced-motion: reduce) {
    .co *, .co *::before, .co *::after { transition-duration: .01ms !important; animation: none !important; }
}
</style>
@endpush
@endonce

<div class="co">
    <div class="co__wrap {{ $step === 'payment' ? 'co__wrap--narrow' : '' }}">

        <nav class="co__crumbs" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('cart') }}">Cart</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $step === 'payment' ? 'Payment' : 'Checkout' }}</span>
        </nav>

        <h1 class="co__title">{{ $title }}</h1>

        @php
            // How far along the flow this page is: cart 1, details 2, payment 3.
            $stepIndex = ['cart' => 1, 'details' => 2, 'payment' => 3][$step] ?? 1;
        @endphp

        <ol class="co-steps">
            @foreach (['Cart', 'Details', 'Payment'] as $i => $label)
                @php
                    $n     = $i + 1;
                    $state = $n < $stepIndex ? 'done' : ($n === $stepIndex ? 'current' : 'todo');
                @endphp

                @if ($i > 0)
                    <li class="co-steps__sep" aria-hidden="true"></li>
                @endif

                <li class="co-step {{ $state !== 'todo' ? 'co-step--' . $state : '' }}"
                    @if ($state === 'current') aria-current="step" @endif>
                    <span class="co-step__dot" aria-hidden="true">
                        @if ($state === 'done')
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        @else
                            {{ $n }}
                        @endif
                    </span>
                    {{ $label }}
                </li>
            @endforeach
        </ol>

        {{ $slot }}

    </div>
</div>
