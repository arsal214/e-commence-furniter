{{-- resources/views/faq.blade.php --}}
@extends('layouts.main')

@section('title', 'FAQs | PeytonGhalib')
@section('meta_description', 'Find answers to frequently asked questions about orders, shipping, returns, payments, and more at PeytonGhalib.')
@section('canonical', url('/faq'))

@php
    // ── Single source of truth ────────────────────────────────────────────
    // This array drives BOTH the FAQPage schema and the visible accordion.
    // They used to be two separate copies and had drifted apart — the schema
    // promised free shipping on everything while the page promised it only
    // over $50, and the page advertised Cash on Delivery, which checkout does
    // not offer. Keep the answers here and both stay honest.
    $catList = !empty($categoryNames)
        ? \Illuminate\Support\Str::lower(implode(', ', array_slice($categoryNames, 0, 6)))
        : 'furniture, home decor, ceramics and lifestyle products';

    $faqGroups = [
        [
            'id'    => 'shipping',
            'title' => 'Shipping & Delivery',
            'icon'  => 'truck',
            'items' => [
                ['q' => 'How long does delivery take?',
                 'a' => 'Standard delivery takes 3–7 business days, depending on your location. You will receive a tracking link by email as soon as your order has been dispatched, and you can follow it from your account at any time.'],
                ['q' => 'Do you offer free shipping?',
                 'a' => 'Yes — shipping is free on every order, with no minimum spend. It is applied automatically at checkout, so there is nothing for you to do.'],
                ['q' => 'Can I track my order?',
                 'a' => 'Absolutely. Once your order is dispatched you will receive a confirmation email with your tracking number. You can also sign in and follow your order from Order History, or use the Track Order page with your tracking number.'],
                ['q' => 'Do you deliver on weekends?',
                 'a' => 'Weekend delivery depends on your location and the courier. Orders are processed on business days (Monday–Friday), and couriers may still deliver on a Saturday in some areas.'],
            ],
        ],
        [
            'id'    => 'returns',
            'title' => 'Returns & Refunds',
            'icon'  => 'refresh',
            'items' => [
                ['q' => 'What is your return policy?',
                 'a' => 'We offer a 30-day return policy on most items. Products must be unused, in their original condition, and returned in their original packaging. To start a return, contact our support team with your order number. Once we receive and inspect the item, your refund is processed within 5–7 business days.'],
                ['q' => 'I received a damaged or wrong item. What should I do?',
                 'a' => 'We are sorry about that. Contact our support team within 48 hours of delivery with your order number and a photo of the item. We will arrange a replacement or a full refund at no cost to you, and we aim to resolve these within 24–48 hours.'],
                ['q' => 'How long does a refund take?',
                 'a' => 'Refunds are processed within 5–7 business days of us receiving your returned item. How quickly it appears on your statement depends on your bank or card provider. You will get an email confirmation once the refund has been issued.'],
            ],
        ],
        [
            'id'    => 'orders',
            'title' => 'Orders & Payments',
            'icon'  => 'card',
            'items' => [
                ['q' => 'What payment methods do you accept?',
                 'a' => 'We accept Visa and Mastercard credit and debit cards. Payments are handled by Stripe over an encrypted connection — your card details are entered with Stripe and never touch our servers.'],
                ['q' => 'Is my payment information secure?',
                 'a' => 'Yes. All payments are processed through Stripe, an encrypted, PCI-compliant payment gateway. We do not store your card details, and the whole site is served over SSL.'],
                ['q' => 'Can I cancel or modify my order?',
                 'a' => 'You can cancel or change an order any time before it is dispatched — contact our support team with your order number as soon as you can. Once an order has shipped it cannot be cancelled, but you can return it after delivery.'],
                ['q' => 'Do I need an account to place an order?',
                 'a' => 'You need an account to check out, and it takes a moment to create. It also lets you track orders, keep a wishlist, see your purchase history, and check out faster next time.'],
            ],
        ],
        [
            'id'    => 'products',
            'title' => 'Products & Stock',
            'icon'  => 'box',
            'items' => [
                ['q' => 'What product categories do you sell?',
                 'a' => 'PeytonGhalib covers ' . $catList . ' — and we are always expanding the catalogue. Browse the shop to see everything currently in stock.'],
                ['q' => 'Are the products authentic and genuine?',
                 'a' => 'Yes. We source from verified suppliers and trusted brands, and every item is quality-checked before it reaches you. If a product ever fails to match its description, we will make it right.'],
                ['q' => 'What if an item I want is out of stock?',
                 'a' => 'Add it to your wishlist and it will be waiting for you when it returns. You can also contact support and we will help you find an alternative or give you an estimated restock date.'],
            ],
        ],
    ];

    $allItems  = collect($faqGroups)->flatMap(fn ($group) => $group['items']);
    $faqCount  = $allItems->count();
@endphp

@push('schema')
@php
$schemaFaq = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $allItems->map(fn ($item) => [
        '@type'          => 'Question',
        'name'           => $item['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
    ])->all(),
];
@endphp
<script type="application/ld+json">{!! json_encode($schemaFaq, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@push('styles')
<style>
/* ── FAQ (fq-) ─────────────────────────────────────────────────
   Gold #bb976d is an accent tone — it fails 4.5:1 on white, so text
   and links use the darker --fq-gold-ink. */
.fq {
    --fq-gold:      #bb976d;
    --fq-gold-ink:  #8a6a3f;
    --fq-surface:   #ffffff;
    --fq-bg:        #faf9f7;
    --fq-border:    rgba(187, 151, 109, .24);
    --fq-text:      #1f1a15;
    --fq-muted:     #6b6157;
    --fq-field:     #ffffff;

    background:
        radial-gradient(900px 400px at 85% -5%, rgba(187, 151, 109, .13), transparent 60%),
        var(--fq-bg);
    padding: 40px 0 80px;
    font-family: 'DM Sans', 'Poppins', ui-sans-serif, system-ui, sans-serif;
    color: var(--fq-text);
}
.dark .fq {
    --fq-surface: rgba(23, 36, 48, .5);
    --fq-bg:      #0a0806;
    --fq-border:  rgba(212, 169, 106, .2);
    --fq-text:    #f3ede4;
    --fq-muted:   #a89f93;
    --fq-gold-ink:#d4a96a;
    --fq-field:   rgba(10, 8, 6, .4);
}

.fq__wrap { max-width: 900px; margin: 0 auto; padding: 0 20px; }

.fq__crumbs { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--fq-muted); margin-bottom: 14px; }
.fq__crumbs a { color: var(--fq-muted); text-decoration: none; }
.fq__crumbs a:hover { color: var(--fq-gold-ink); }
.fq__crumbs [aria-current] { color: var(--fq-gold-ink); font-weight: 600; }

.fq__head { text-align: center; margin-bottom: 26px; }
.fq__eyebrow {
    display: inline-block;
    font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    color: var(--fq-gold-ink);
    background: rgba(187, 151, 109, .12);
    border: 1px solid var(--fq-border);
    border-radius: 100px;
    padding: 5px 13px;
    margin-bottom: 12px;
}
.fq__title { font-size: clamp(26px, 3.6vw, 34px); font-weight: 700; letter-spacing: -.4px; margin: 0 0 10px; }
.fq__lede  { font-size: 15.5px; line-height: 1.7; color: var(--fq-muted); margin: 0 auto; max-width: 52ch; }

/* ── Search ────────────────────────────────────────────────── */
.fq-search { position: relative; display: flex; align-items: center; margin-bottom: 14px; }
.fq-search__icon { position: absolute; left: 16px; color: var(--fq-muted); pointer-events: none; }
.fq-search__input {
    width: 100%;
    height: 54px;                /* >= 44px touch target */
    font-size: 16px;             /* prevents iOS zoom-on-focus */
    color: var(--fq-text);
    background: var(--fq-field);
    border: 1px solid var(--fq-border);
    border-radius: 14px;
    padding: 0 46px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.fq-search__input::placeholder { color: var(--fq-muted); opacity: .75; }
.fq-search__input:hover { border-color: rgba(187, 151, 109, .5); }
.fq-search__input:focus-visible { border-color: var(--fq-gold); box-shadow: 0 0 0 4px rgba(187, 151, 109, .18); }

.fq-search__clear {
    position: absolute; right: 6px;
    display: grid; place-items: center;
    width: 44px; height: 44px;
    border: 0; border-radius: 10px;
    background: transparent; color: var(--fq-muted);
    cursor: pointer;
    transition: background .2s ease, color .2s ease;
}
.fq-search__clear[hidden] { display: none; }
.fq-search__clear:hover { background: rgba(187, 151, 109, .12); color: var(--fq-text); }
.fq-search__clear:focus-visible { outline: 2px solid var(--fq-gold); outline-offset: 2px; }

.fq-count { font-size: 13px; color: var(--fq-muted); text-align: center; margin: 0 0 26px; }

/* ── Group ─────────────────────────────────────────────────── */
.fq-group { margin-bottom: 26px; }
.fq-group[hidden] { display: none; }

.fq-group__head { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.fq-group__icon {
    display: grid; place-items: center;
    width: 34px; height: 34px; flex: none;
    border-radius: 10px;
    background: rgba(187, 151, 109, .14);
    color: var(--fq-gold-ink);
}
.fq-group__title { font-size: 17px; font-weight: 700; margin: 0; color: var(--fq-text); }

/* ── Accordion — native <details>, so it works without JS ───── */
.fq-item {
    background: var(--fq-surface);
    border: 1px solid var(--fq-border);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color .2s ease;
}
.fq-item + .fq-item { margin-top: 10px; }
.fq-item[hidden] { display: none; }
.fq-item:hover { border-color: rgba(187, 151, 109, .5); }
.fq-item[open] { border-color: var(--fq-gold); }

.fq-item__q {
    display: flex; align-items: center; gap: 14px;
    min-height: 56px;
    padding: 14px 18px;
    font-size: 15px; font-weight: 600;
    color: var(--fq-text);
    cursor: pointer;
    list-style: none;              /* kill the default disclosure triangle */
    transition: background .2s ease;
}
.fq-item__q::-webkit-details-marker { display: none; }
.fq-item__q:hover { background: rgba(187, 151, 109, .06); }
.fq-item__q:focus-visible { outline: 2px solid var(--fq-gold); outline-offset: -2px; }

/* Plus → minus. Rotating a bar is cheaper than swapping icons. */
.fq-item__sign { position: relative; width: 16px; height: 16px; margin-left: auto; flex: none; }
.fq-item__sign::before,
.fq-item__sign::after {
    content: '';
    position: absolute; inset: 50% 0 auto 0;
    height: 2px; border-radius: 2px;
    background: var(--fq-gold-ink);
    transform: translateY(-50%);
    transition: transform .25s cubic-bezier(.22, 1, .36, 1);
}
.fq-item__sign::after { transform: translateY(-50%) rotate(90deg); }
.fq-item[open] .fq-item__sign::after { transform: translateY(-50%) rotate(0deg); }

.fq-item__a {
    padding: 0 18px 18px;
    font-size: 14.5px; line-height: 1.75;
    color: var(--fq-muted);
    margin: 0;
    animation: fq-reveal .25s ease-out;
}
@keyframes fq-reveal { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }

/* Search hit highlight */
.fq-item mark {
    background: rgba(187, 151, 109, .3);
    color: inherit;
    border-radius: 3px;
    padding: 0 2px;
}

/* ── No results ────────────────────────────────────────────── */
.fq-empty {
    text-align: center;
    padding: 40px 20px;
    background: var(--fq-surface);
    border: 1px dashed var(--fq-border);
    border-radius: 16px;
}
.fq-empty[hidden] { display: none; }
.fq-empty__icon {
    display: grid; place-items: center;
    width: 54px; height: 54px; margin: 0 auto 14px;
    border-radius: 50%;
    background: rgba(187, 151, 109, .12);
    color: var(--fq-gold);
}
.fq-empty__title { font-size: 16px; font-weight: 700; margin: 0 0 6px; color: var(--fq-text); }
.fq-empty__text  { font-size: 14px; color: var(--fq-muted); margin: 0; }

/* ── Still stuck ───────────────────────────────────────────── */
.fq-cta {
    margin-top: 34px;
    text-align: center;
    border-radius: 20px;
    padding: 34px 24px;
    background:
        radial-gradient(500px 240px at 50% 0%, rgba(187, 151, 109, .28), transparent 65%),
        linear-gradient(160deg, #172430 0%, #0a0806 100%);
}
.fq-cta__title { font-size: 20px; font-weight: 700; color: #fff; margin: 0 0 8px; }
.fq-cta__text  { font-size: 14.5px; color: rgba(255, 255, 255, .78); margin: 0 auto 20px; max-width: 44ch; line-height: 1.65; }

.fq-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: 48px; padding: 0 22px;
    font-size: 14.5px; font-weight: 700;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a 0%, #bb976d 55%, #a9855c 100%);
    border: 0; border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 6px 20px -6px rgba(187, 151, 109, .55);
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s ease;
}
.fq-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px -8px rgba(187, 151, 109, .7); }
.fq-btn:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }
.fq-btn svg { transition: transform .2s ease; }
.fq-btn:hover svg { transform: translateX(3px); }

.fq-sr { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }

@media (prefers-reduced-motion: reduce) {
    .fq *, .fq *::before, .fq *::after { transition-duration: .01ms !important; animation: none !important; }
}
</style>
@endpush

@section('content')

@include('includes.navbar')

<div class="fq">
    <div class="fq__wrap">

        <nav class="fq__crumbs" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">FAQs</span>
        </nav>

        <header class="fq__head">
            <span class="fq__eyebrow">Help centre</span>
            <h1 class="fq__title">Frequently asked questions</h1>
            <p class="fq__lede">Everything about orders, shipping, returns and payments. Can't find it? We're one message away.</p>
        </header>

        {{-- Search filters the questions client-side --}}
        <div class="fq-search">
            <svg class="fq-search__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
            </svg>

            <label class="fq-sr" for="fq-search">Search the FAQs</label>
            <input class="fq-search__input" id="fq-search" type="search"
                   placeholder="Search — try “refund”, “tracking”, “payment”…"
                   autocomplete="off" data-fq-search>

            <button class="fq-search__clear" type="button" data-fq-clear aria-label="Clear search" hidden>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <p class="fq-count" data-fq-count aria-live="polite">{{ $faqCount }} questions</p>

        @foreach ($faqGroups as $group)
            <section class="fq-group" id="{{ $group['id'] }}" data-fq-group aria-labelledby="{{ $group['id'] }}-title">
                <div class="fq-group__head">
                    <span class="fq-group__icon" aria-hidden="true">
                        @switch($group['icon'])
                            @case('truck')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/></svg>
                                @break
                            @case('refresh')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                                @break
                            @case('card')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                                @break
                            @case('box')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>
                                @break
                        @endswitch
                    </span>
                    <h2 class="fq-group__title" id="{{ $group['id'] }}-title">{{ $group['title'] }}</h2>
                </div>

                @foreach ($group['items'] as $item)
                    {{-- <details> gives keyboard support and open/close for free, with no JS --}}
                    <details class="fq-item" data-fq-item>
                        <summary class="fq-item__q">
                            <span data-fq-q>{{ $item['q'] }}</span>
                            <span class="fq-item__sign" aria-hidden="true"></span>
                        </summary>
                        <p class="fq-item__a" data-fq-a>{{ $item['a'] }}</p>
                    </details>
                @endforeach
            </section>
        @endforeach

        <div class="fq-empty" data-fq-empty hidden>
            <div class="fq-empty__icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </div>
            <p class="fq-empty__title">No matching questions</p>
            <p class="fq-empty__text">Try a different word — or just ask us directly below.</p>
        </div>

        <section class="fq-cta">
            <h2 class="fq-cta__title">Still need a hand?</h2>
            <p class="fq-cta__text">Our support team answers every message within 24 hours.</p>
            <a class="fq-btn" href="{{ route('contact.show') }}">
                Contact support
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </section>

    </div>
</div>

@include('includes.footer')

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var search = document.querySelector('[data-fq-search]');
    if (!search) return;

    var clear  = document.querySelector('[data-fq-clear]');
    var count  = document.querySelector('[data-fq-count]');
    var empty  = document.querySelector('[data-fq-empty]');
    var groups = Array.prototype.slice.call(document.querySelectorAll('[data-fq-group]'));
    var items  = Array.prototype.slice.call(document.querySelectorAll('[data-fq-item]'));

    // Keep the original text so highlighting can be undone cleanly.
    items.forEach(function (item) {
        var q = item.querySelector('[data-fq-q]');
        var a = item.querySelector('[data-fq-a]');
        item._q = q ? q.textContent : '';
        item._a = a ? a.textContent : '';
    });

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function escapeRegExp(text) {
        return text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function highlight(element, text, term) {
        if (!element) return;

        if (!term) {
            element.textContent = text;
            return;
        }
        // Escape first, then wrap matches — never inject the raw term.
        element.innerHTML = escapeHtml(text).replace(
            new RegExp('(' + escapeRegExp(escapeHtml(term)) + ')', 'ig'),
            '<mark>$1</mark>'
        );
    }

    function apply() {
        var term = search.value.trim().toLowerCase();
        var hits = 0;

        if (clear) clear.hidden = term === '';

        items.forEach(function (item) {
            var match = !term
                || item._q.toLowerCase().indexOf(term) !== -1
                || item._a.toLowerCase().indexOf(term) !== -1;

            item.hidden = !match;
            if (match) hits++;

            // Open matches while searching so the answer is visible immediately.
            item.open = Boolean(term) && match;

            highlight(item.querySelector('[data-fq-q]'), item._q, term);
            highlight(item.querySelector('[data-fq-a]'), item._a, term);
        });

        // Hide a whole section once every question in it is filtered out.
        groups.forEach(function (group) {
            var visible = group.querySelectorAll('[data-fq-item]:not([hidden])').length;
            group.hidden = visible === 0;
        });

        if (empty) empty.hidden = hits !== 0;

        if (count) {
            count.textContent = term
                ? hits + (hits === 1 ? ' matching question' : ' matching questions')
                : items.length + ' questions';
        }
    }

    search.addEventListener('input', apply);

    search.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            search.value = '';
            apply();
        }
    });

    if (clear) {
        clear.addEventListener('click', function () {
            search.value = '';
            apply();
            search.focus();
        });
    }
})();
</script>
@endpush
