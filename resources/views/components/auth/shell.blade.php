{{--
    Auth shell — centred frame shared by login / register / reset.

    A single glass card on a soft gold-washed background, vertically and
    horizontally centred at every breakpoint.

    Slots : $slot   → the form
            $footer → the "create account" / secondary link row
--}}
@props([
    'heading'    => 'Welcome back',
    'subheading' => null,
    'eyebrow'    => 'Members',
    'wide'       => false,   // wider card, for two-column forms (register)
])

@once
@push('styles')
<style>
/* ── Auth (pgh-auth) ───────────────────────────────────────────
   Brand tokens. Gold #bb976d is a decorative/accent tone only —
   it fails 4.5:1 on white, so link text uses the darker --au-gold-ink. */
.pgh-auth {
    --au-gold:      #bb976d;
    --au-gold-soft: #d4a96a;
    --au-gold-ink:  #8a6a3f;  /* AA-compliant gold for text on light */
    --au-ink:       #0a0806;
    --au-navy:      #172430;
    --au-cream:     #faf9f7;
    --au-surface:   rgba(255, 255, 255, .82);
    --au-border:    rgba(187, 151, 109, .28);
    --au-text:      #1f1a15;
    --au-muted:     #6b6157;
    --au-danger:    #b42318;
    --au-success:   #15803d;
    --au-field:     #ffffff;

    min-height: 100dvh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', 'Poppins', ui-sans-serif, system-ui, sans-serif;
    background:
        radial-gradient(900px 500px at 50% -10%, rgba(187, 151, 109, .16), transparent 62%),
        radial-gradient(700px 500px at 50% 110%, rgba(187, 151, 109, .10), transparent 60%),
        var(--au-cream);
    color: var(--au-text);
}

.dark .pgh-auth {
    --au-surface: rgba(23, 36, 48, .55);
    --au-border:  rgba(212, 169, 106, .22);
    --au-text:    #f3ede4;
    --au-muted:   #a89f93;
    --au-danger:  #ff9c94;
    --au-success: #6ee7a8;
    --au-gold-ink:#d4a96a;   /* gold reads fine on near-black */
    --au-field:   rgba(10, 8, 6, .45);

    background:
        radial-gradient(900px 500px at 50% -10%, rgba(187, 151, 109, .18), transparent 62%),
        radial-gradient(700px 500px at 50% 110%, rgba(187, 151, 109, .12), transparent 60%),
        var(--au-ink);
}

/* ── Centred panel ─────────────────────────────────────────────
   Vertical padding flexes with viewport height so a short window
   doesn't get pushed into a scrollbar by fixed gutters. */
.pgh-auth__panel {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    padding: clamp(16px, 3vh, 32px) 20px;
}
@media (min-width: 640px) { .pgh-auth__panel { padding: clamp(16px, 3vh, 32px) 40px; } }

/* Trust cues, centred beneath the card */
.pgh-auth__trust {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px 22px;
    list-style: none;
    padding: 0;
    margin: 18px 0 0;
}
.pgh-auth__trust li {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: .7px;
    text-transform: uppercase;
    color: var(--au-muted);
}
.pgh-auth__trust svg { color: var(--au-gold); flex-shrink: 0; }

.pgh-auth__card {
    width: 100%;
    max-width: 27rem;
    background: var(--au-surface);
    -webkit-backdrop-filter: blur(16px);
    backdrop-filter: blur(16px);
    border: 1px solid var(--au-border);
    border-radius: 20px;
    padding: 26px 24px 24px;
    box-shadow: 0 1px 2px rgba(10, 8, 6, .04), 0 18px 50px -12px rgba(10, 8, 6, .16);
    animation: au-rise .55s cubic-bezier(.22, 1, .36, 1) both;
}
@media (min-width: 640px) { .pgh-auth__card { padding: 30px 36px 28px; } }
.dark .pgh-auth__card { box-shadow: 0 24px 60px -12px rgba(0, 0, 0, .6); }

/* Wide variant — room for the two-column field grid */
.pgh-auth__card--wide { max-width: 36rem; }

/* ── Two-column field grid ─────────────────────────────────────
   Stacks to one column below 640px, where side-by-side inputs would
   be too narrow to read or tap comfortably. */
.pgh-auth__grid { display: grid; grid-template-columns: 1fr; gap: 0; }

@media (min-width: 640px) {
    .pgh-auth__grid { grid-template-columns: 1fr 1fr; column-gap: 18px; }
    .pgh-auth__grid > .pgh-field--full { grid-column: 1 / -1; }
}

@keyframes au-rise {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: none; }
}

/* Card header is centred; the fields below stay left-aligned for scanability */
.pgh-auth__head { text-align: center; margin-bottom: 20px; }

.pgh-auth__logo { height: 30px; width: auto; margin-bottom: 14px; }
.dark .pgh-auth__logo { filter: brightness(0) invert(1); }

.pgh-auth__eyebrow {
    display: inline-block;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--au-gold-ink);
    background: rgba(187, 151, 109, .12);
    border: 1px solid var(--au-border);
    border-radius: 100px;
    padding: 5px 12px;
    margin-bottom: 10px;
}

.pgh-auth__title {
    font-size: clamp(24px, 3.4vw, 29px);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -.4px;
    color: var(--au-text);
    margin: 0 0 6px;
}

.pgh-auth__sub {
    font-size: 14.5px;
    line-height: 1.6;
    color: var(--au-muted);
    max-width: 34ch;
    margin: 0 auto;   /* centred under the title, kept to a readable measure */
}

/* ── Banners (server status / error summary) ───────────────── */
.pgh-auth__banner {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 13.5px;
    line-height: 1.5;
    margin-bottom: 20px;
    animation: au-rise .3s ease-out both;
}
.pgh-auth__banner svg { flex-shrink: 0; margin-top: 1px; }
.pgh-auth__banner--error {
    color: var(--au-danger);
    background: rgba(180, 35, 24, .07);
    border: 1px solid rgba(180, 35, 24, .25);
}
.dark .pgh-auth__banner--error { background: rgba(255, 156, 148, .08); border-color: rgba(255, 156, 148, .28); }
.pgh-auth__banner--success {
    color: var(--au-success);
    background: rgba(21, 128, 61, .07);
    border: 1px solid rgba(21, 128, 61, .25);
}
.dark .pgh-auth__banner--success { background: rgba(110, 231, 168, .08); border-color: rgba(110, 231, 168, .28); }

/* ── Fields ────────────────────────────────────────────────── */
.pgh-field { margin-bottom: 14px; }

.pgh-field__label {
    display: block;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--au-text);
    margin-bottom: 7px;
    transition: color .2s ease;
}
.pgh-field:focus-within .pgh-field__label { color: var(--au-gold-ink); }
.pgh-field__req { color: var(--au-danger); margin-left: 2px; }

.pgh-field__box { position: relative; display: flex; align-items: center; }

.pgh-field__icon {
    position: absolute;
    left: 14px;
    color: var(--au-muted);
    pointer-events: none;
    transition: color .2s ease;
}
.pgh-field:focus-within .pgh-field__icon { color: var(--au-gold-ink); }

.pgh-field__input {
    width: 100%;
    height: 52px;                 /* >= 44px touch target */
    font-size: 16px;              /* prevents iOS zoom-on-focus */
    color: var(--au-text);
    background: var(--au-field);
    border: 1px solid var(--au-border);
    border-radius: 12px;
    padding: 0 14px 0 44px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}
.pgh-field__input::placeholder { color: var(--au-muted); opacity: .75; }
.pgh-field__input:hover { border-color: rgba(187, 151, 109, .5); }
.pgh-field__input:focus-visible {
    border-color: var(--au-gold);
    box-shadow: 0 0 0 4px rgba(187, 151, 109, .18);
}
.pgh-field__input--toggle { padding-right: 50px; }

/* Invalid: colour + icon + text, never colour alone */
.pgh-field__input[aria-invalid="true"] {
    border-color: var(--au-danger);
    background: rgba(180, 35, 24, .04);
}
.pgh-field__input[aria-invalid="true"]:focus-visible {
    box-shadow: 0 0 0 4px rgba(180, 35, 24, .14);
}

.pgh-field__toggle {
    position: absolute;
    right: 5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;                  /* full 44px touch target */
    height: 44px;
    border: 0;
    border-radius: 10px;
    background: transparent;
    color: var(--au-muted);
    cursor: pointer;
    transition: color .2s ease, background .2s ease;
}
.pgh-field__toggle:hover { color: var(--au-gold-ink); background: rgba(187, 151, 109, .1); }
.pgh-field__toggle:focus-visible { outline: 2px solid var(--au-gold); outline-offset: 2px; }
.pgh-field__toggle .au-eye-off { display: none; }
.pgh-field__toggle[aria-pressed="true"] .au-eye-on  { display: none; }
.pgh-field__toggle[aria-pressed="true"] .au-eye-off { display: block; }

/* ── Password strength meter ───────────────────────────────── */
.pgh-strength { margin-top: 9px; }

.pgh-strength__bars {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 5px;
}
.pgh-strength__bar {
    height: 4px;
    border-radius: 100px;
    background: rgba(187, 151, 109, .2);
    transition: background .3s ease;
}
.pgh-strength[data-score="1"] .pgh-strength__bar:nth-child(-n+1),
.pgh-strength[data-score="2"] .pgh-strength__bar:nth-child(-n+2),
.pgh-strength[data-score="3"] .pgh-strength__bar:nth-child(-n+3),
.pgh-strength[data-score="4"] .pgh-strength__bar:nth-child(-n+4) { background: var(--au-strength, var(--au-gold)); }

.pgh-strength[data-score="1"] { --au-strength: #b42318; }
.pgh-strength[data-score="2"] { --au-strength: #c77a17; }
.pgh-strength[data-score="3"] { --au-strength: #bb976d; }
.pgh-strength[data-score="4"] { --au-strength: #15803d; }
.dark .pgh-strength[data-score="1"] { --au-strength: #ff9c94; }
.dark .pgh-strength[data-score="4"] { --au-strength: #6ee7a8; }

.pgh-strength__label {
    display: block;
    font-size: 12px;
    color: var(--au-muted);
    margin-top: 6px;
}

.pgh-field__error {
    display: none;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    font-weight: 500;
    color: var(--au-danger);
    margin-top: 6px;
}
.pgh-field__error.is-visible { display: flex; animation: au-shake .3s ease; }
.pgh-field__error svg { flex-shrink: 0; }

@keyframes au-shake {
    0%, 100% { transform: translateX(0); }
    25%      { transform: translateX(-3px); }
    75%      { transform: translateX(3px); }
}

/* ── Row: remember + forgot ────────────────────────────────── */
.pgh-auth__row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin: 2px 0 18px;
}

.pgh-check { display: inline-flex; align-items: center; gap: 9px; cursor: pointer; user-select: none; }
.pgh-check__input { position: absolute; opacity: 0; width: 0; height: 0; }
.pgh-check__box {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 19px;
    height: 19px;
    border: 1.5px solid var(--au-border);
    border-radius: 6px;
    background: var(--au-field);
    transition: background .2s ease, border-color .2s ease, transform .15s ease;
}
.pgh-check__box svg { opacity: 0; transform: scale(.6); transition: opacity .18s ease, transform .18s cubic-bezier(.34, 1.56, .64, 1); }
.pgh-check__input:checked + .pgh-check__box {
    background: var(--au-gold);
    border-color: var(--au-gold);
}
.pgh-check__input:checked + .pgh-check__box svg { opacity: 1; transform: scale(1); color: #0a0806; }
.pgh-check__input:focus-visible + .pgh-check__box { outline: 2px solid var(--au-gold); outline-offset: 2px; }
.pgh-check:active .pgh-check__box { transform: scale(.92); }
.pgh-check__text { font-size: 13.5px; color: var(--au-muted); }

.pgh-auth__link {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--au-gold-ink);
    text-decoration: none;
    border-radius: 4px;
    transition: color .2s ease;
}
.pgh-auth__link:hover { text-decoration: underline; text-underline-offset: 3px; }
.pgh-auth__link:focus-visible { outline: 2px solid var(--au-gold); outline-offset: 3px; }

/* Terms note above the register button */
.pgh-auth__terms {
    font-size: 12.5px;
    line-height: 1.6;
    color: var(--au-muted);
    margin: 20px 0 20px;
}

/* Helper note (e.g. "the link expires in 60 minutes") */
.pgh-auth__note {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    font-size: 12.5px;
    line-height: 1.55;
    color: var(--au-muted);
    background: rgba(187, 151, 109, .08);
    border: 1px solid var(--au-border);
    border-radius: 12px;
    padding: 11px 13px;
    margin: 6px 0 22px;
}
.pgh-auth__note svg { flex-shrink: 0; margin-top: 1px; color: var(--au-gold-ink); }

/* Breathing room where a form has no row between fields and button */
.pgh-auth__spacer { height: 8px; }

/* ── Submit ────────────────────────────────────────────────── */
.pgh-submit {
    position: relative;
    width: 100%;
    height: 52px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    overflow: hidden;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: .3px;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a 0%, #bb976d 55%, #a9855c 100%);
    border: 0;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 6px 20px -6px rgba(187, 151, 109, .55);
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s ease, filter .2s ease;
}
.pgh-submit:hover  { transform: translateY(-2px); box-shadow: 0 12px 28px -8px rgba(187, 151, 109, .7); filter: brightness(1.04); }
.pgh-submit:active { transform: translateY(0) scale(.99); }
.pgh-submit:focus-visible { outline: 2px solid var(--au-gold-ink); outline-offset: 3px; }

/* Shine sweep on hover — transform only, no layout cost */
.pgh-submit::after {
    content: '';
    position: absolute;
    top: 0;
    left: -60%;
    width: 40%;
    height: 100%;
    background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .45), transparent);
    transform: skewX(-18deg);
    transition: left .55s ease;
}
.pgh-submit:hover::after { left: 120%; }

.pgh-submit[disabled], .pgh-submit[aria-busy="true"] {
    cursor: not-allowed;
    opacity: .72;
    transform: none;
    box-shadow: none;
}
.pgh-submit[disabled]::after { display: none; }

.pgh-submit__spinner { display: none; animation: au-spin .7s linear infinite; }
.pgh-submit[aria-busy="true"] .pgh-submit__spinner { display: block; }
.pgh-submit[aria-busy="true"] .pgh-submit__label { opacity: .85; }

@keyframes au-spin { to { transform: rotate(360deg); } }

/* ── Footer link row ───────────────────────────────────────── */
.pgh-auth__foot {
    text-align: center;
    font-size: 14px;
    color: var(--au-muted);
    margin: 18px 0 0;
}
.pgh-auth__foot a { font-weight: 700; color: var(--au-gold-ink); text-decoration: none; }
.pgh-auth__foot a:hover { text-decoration: underline; text-underline-offset: 3px; }

/* Screen-reader-only text. Defined here rather than relying on Tailwind's
   .sr-only, which is absent from the prebuilt theme CSS and would only appear
   after a Vite rebuild. */
.pgh-auth .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* ── Short viewports ───────────────────────────────────────────
   On a laptop-height window, drop the optional chrome rather than
   push the form into a scrollbar. The form itself is never hidden —
   if it still doesn't fit (very short window, zoomed-in text), the
   page scrolls normally, which is the correct fallback. */
@media (max-height: 820px) {
    .pgh-auth__trust { display: none; }
}
@media (max-height: 720px) {
    .pgh-auth__logo { margin-bottom: 10px; }
    .pgh-auth__head { margin-bottom: 16px; }
    .pgh-auth__sub  { display: none; }
}

/* ── Motion preferences ────────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
    .pgh-auth *,
    .pgh-auth *::before,
    .pgh-auth *::after {
        animation: none !important;
        transition-duration: .01ms !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    'use strict';

    var form = document.querySelector('[data-auth-form]');
    if (!form) return;

    /* ── Password show/hide ─────────────────────────────────── */
    form.querySelectorAll('[data-pw-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('aria-controls'));
            if (!input) return;
            var reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            btn.setAttribute('aria-pressed', String(reveal));
            btn.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
            input.focus({ preventScroll: true });
        });
    });

    /* ── Validation ─────────────────────────────────────────────
       Rules live on the input (required / type / minlength), so a
       field validates itself and the form needs no per-field config. */
    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    function messageFor(input) {
        var value = input.value.trim();
        var label = input.dataset.label || 'This field';

        if (input.required && !value) return label + ' is required.';
        if (!value) return '';
        if (input.type === 'email' && !EMAIL_RE.test(value)) {
            return 'Enter a valid email address, e.g. name@example.com';
        }
        if (input.minLength > 0 && value.length < input.minLength) {
            return label + ' must be at least ' + input.minLength + ' characters.';
        }
        // data-match="password" → must equal that field (confirm-password)
        if (input.dataset.match) {
            var other = document.getElementById(input.dataset.match);
            if (other && other.value !== input.value) return 'Passwords do not match.';
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
        // Validate on blur, not on keystroke — don't scold mid-typing.
        input.addEventListener('blur', function () { setError(input, messageFor(input)); });

        // Once a field is marked invalid, clear it as soon as it becomes valid.
        input.addEventListener('input', function () {
            if (input.getAttribute('aria-invalid') === 'true' && !messageFor(input)) {
                setError(input, '');
            }

            // Editing the password can silently invalidate an already-filled
            // confirm field, so re-check anything that mirrors this one.
            form.querySelectorAll('[data-match="' + input.id + '"]').forEach(function (mirror) {
                if (mirror.value) setError(mirror, messageFor(mirror));
            });
        });
    });

    /* ── Password strength meter ────────────────────────────────
       Rough, honest signal only — length first, then variety. The server
       still enforces the real rule (min 8). */
    var LEVELS = ['Too short', 'Weak', 'Fair', 'Good', 'Strong'];

    form.querySelectorAll('[data-strength-for]').forEach(function (meter) {
        var input = document.getElementById(meter.dataset.strengthFor);
        if (!input) return;
        var label = meter.querySelector('[data-strength-label]');

        input.addEventListener('input', function () {
            var value = input.value;
            var score = 0;

            if (value.length >= 8)  score++;
            if (value.length >= 12) score++;
            if (/[A-Z]/.test(value) && /[a-z]/.test(value)) score++;
            if (/\d/.test(value) && /[^\w\s]/.test(value)) score++;
            if (value.length < 8) score = value.length ? 1 : 0;

            meter.setAttribute('data-score', String(score));
            if (label) label.textContent = value ? LEVELS[score] : 'Use at least 8 characters.';
        });
    });

    /* ── Submit: block invalid, then show a loading state ───── */
    var button = form.querySelector('[data-submit]');

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
            return;
        }

        if (button) {
            button.setAttribute('aria-busy', 'true');
            button.disabled = true;        // guards against double-submit
        }
    });

    // Back/forward cache can restore a disabled button — re-enable it.
    window.addEventListener('pageshow', function () {
        if (button) {
            button.removeAttribute('aria-busy');
            button.disabled = false;
        }
    });
})();
</script>
@endpush
@endonce

<main class="pgh-auth">

    <section class="pgh-auth__panel">

        <div class="pgh-auth__card @if ($wide) pgh-auth__card--wide @endif">

            <div class="pgh-auth__head">
                <a href="{{ url('/') }}" aria-label="PeytonGhalib home">
                    <img class="pgh-auth__logo" src="{{ asset('assets/img/logo.svg') }}" alt="PeytonGhalib" width="120" height="30">
                </a>

                @if ($eyebrow)
                    <span class="pgh-auth__eyebrow">{{ $eyebrow }}</span>
                @endif

                <h1 class="pgh-auth__title">{{ $heading }}</h1>

                @if ($subheading)
                    <p class="pgh-auth__sub">{{ $subheading }}</p>
                @endif
            </div>

            {{-- Success feedback (e.g. "password reset link sent") --}}
            @if (session('status'))
                <div class="pgh-auth__banner pgh-auth__banner--success" role="status">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Server-side error summary — announced immediately --}}
            @if ($errors->any())
                <div class="pgh-auth__banner pgh-auth__banner--error" role="alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{ $slot }}

            @isset($footer)
                <p class="pgh-auth__foot">{{ $footer }}</p>
            @endisset

        </div>

        <ul class="pgh-auth__trust">
            <li>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Secure checkout
            </li>
            <li>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 18a2 2 0 1 0 4 0 2 2 0 0 0-4 0zM15 18a2 2 0 1 0 4 0 2 2 0 0 0-4 0z"/><path d="M3 6h11v10M14 9h4l3 3v6"/></svg>
                Free shipping
            </li>
            <li>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                Easy returns
            </li>
        </ul>

    </section>

</main>
