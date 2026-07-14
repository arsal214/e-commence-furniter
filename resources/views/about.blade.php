{{-- resources/views/about.blade.php --}}
@extends('layouts.main')

@section('title', 'About Us | PeytonGhalib')
@section('meta_description', 'Learn about PeytonGhalib — our story, mission, and commitment to delivering quality furniture and home decor at unbeatable prices with exceptional service.')

@push('schema')
@php
$schemaAbout = ['@context'=>'https://schema.org','@type'=>'AboutPage','name'=>'About PeytonGhalib','url'=>url('/about'),
    'description'=>'Learn about PeytonGhalib — our mission, our products, and our commitment to quality furniture and home decor.',
    'about'=>['@type'=>'Organization','name'=>'PeytonGhalib','url'=>url('/')]];
@endphp
<script type="application/ld+json">{!! json_encode($schemaAbout, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@push('styles')
<style>
/* ── About (ab-) ───────────────────────────────────────────────
   Gold #bb976d is an accent tone — it fails 4.5:1 on white, so text
   and links use the darker --ab-gold-ink. */
.ab {
    --ab-gold:      #bb976d;
    --ab-gold-soft: #d4a96a;
    --ab-gold-ink:  #8a6a3f;
    --ab-ink:       #0a0806;
    --ab-surface:   #ffffff;
    --ab-bg:        #faf9f7;
    --ab-border:    rgba(187, 151, 109, .24);
    --ab-text:      #1f1a15;
    --ab-muted:     #6b6157;

    font-family: 'DM Sans', 'Poppins', ui-sans-serif, system-ui, sans-serif;
    color: var(--ab-text);
    background: var(--ab-bg);
}
.dark .ab {
    --ab-surface: rgba(23, 36, 48, .5);
    --ab-bg:      #0a0806;
    --ab-border:  rgba(212, 169, 106, .2);
    --ab-text:    #f3ede4;
    --ab-muted:   #a89f93;
    --ab-gold-ink:#d4a96a;
}

.ab__wrap { max-width: 1180px; margin: 0 auto; padding: 0 20px; }

.ab__eyebrow {
    display: inline-block;
    font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    color: var(--ab-gold-ink);
    background: rgba(187, 151, 109, .12);
    border: 1px solid var(--ab-border);
    border-radius: 100px;
    padding: 5px 13px;
    margin-bottom: 14px;
}

.ab__h2 {
    font-size: clamp(24px, 3.2vw, 34px);
    font-weight: 700; letter-spacing: -.4px; line-height: 1.2;
    margin: 0 0 12px;
    color: var(--ab-text);
}
.ab__lede { font-size: 16px; line-height: 1.7; color: var(--ab-muted); margin: 0; max-width: 60ch; }

/* ── Hero ──────────────────────────────────────────────────── */
.ab-hero {
    position: relative;
    display: grid;
    place-items: center;
    min-height: 420px;
    padding: 90px 20px;
    text-align: center;
    overflow: hidden;
    isolation: isolate;
}
.ab-hero::before {
    content: '';
    position: absolute; inset: 0; z-index: -2;
    background-image: var(--ab-hero-img);
    background-size: cover;
    background-position: center;
}
/* Scrim keeps the white type well above 4.5:1 on any photo */
.ab-hero::after {
    content: '';
    position: absolute; inset: 0; z-index: -1;
    background: linear-gradient(160deg, rgba(10, 8, 6, .8) 0%, rgba(23, 36, 48, .74) 55%, rgba(10, 8, 6, .88) 100%);
}

.ab-hero__crumbs { display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; color: rgba(255, 255, 255, .7); margin-bottom: 16px; }
.ab-hero__crumbs a { color: rgba(255, 255, 255, .7); text-decoration: none; }
.ab-hero__crumbs a:hover { color: var(--ab-gold-soft); }
.ab-hero__crumbs [aria-current] { color: var(--ab-gold-soft); font-weight: 600; }

.ab-hero__title {
    font-size: clamp(32px, 5.5vw, 52px);
    font-weight: 700; line-height: 1.1; letter-spacing: -1px;
    color: #fff;
    margin: 0 0 14px;
}
.ab-hero__title em { font-style: normal; color: var(--ab-gold-soft); }
.ab-hero__text { max-width: 56ch; margin: 0 auto 26px; font-size: 16px; line-height: 1.7; color: rgba(255, 255, 255, .82); }

.ab-hero__actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

/* ── Buttons ───────────────────────────────────────────────── */
.ab-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: 48px; padding: 0 24px;
    font-size: 14.5px; font-weight: 700;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a 0%, #bb976d 55%, #a9855c 100%);
    border: 0; border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 6px 20px -6px rgba(187, 151, 109, .55);
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s ease;
}
.ab-btn:hover  { transform: translateY(-2px); box-shadow: 0 12px 28px -8px rgba(187, 151, 109, .7); }
.ab-btn:active { transform: translateY(0) scale(.99); }
.ab-btn:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }
.ab-btn svg { transition: transform .2s ease; }
.ab-btn:hover svg { transform: translateX(3px); }

.ab-btn--ghost {
    color: #fff;
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .3);
    box-shadow: none;
    -webkit-backdrop-filter: blur(6px);
    backdrop-filter: blur(6px);
}
.ab-btn--ghost:hover { background: rgba(255, 255, 255, .16); box-shadow: none; }

.ab-btn--dark { color: #0a0806; }

/* ── Stat strip ────────────────────────────────────────────── */
.ab-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 30px;
}
@media (min-width: 700px) { .ab-stats { grid-template-columns: repeat(4, 1fr); gap: 16px; } }

.ab-stat {
    background: var(--ab-surface);
    border: 1px solid var(--ab-border);
    border-radius: 14px;
    padding: 16px;
    text-align: center;
}
.ab-stat__v {
    display: block;
    font-size: 24px; font-weight: 700; line-height: 1.1;
    color: var(--ab-gold-ink);
    font-variant-numeric: tabular-nums;
    margin-bottom: 5px;
}
.ab-stat__k { display: block; font-size: 11.5px; font-weight: 600; letter-spacing: .7px; text-transform: uppercase; color: var(--ab-muted); }

/* ── Story ─────────────────────────────────────────────────── */
.ab-section { padding: 72px 0; }
.ab-section--alt { background: var(--ab-surface); }
.dark .ab-section--alt { background: rgba(23, 36, 48, .3); }

.ab-story { display: grid; grid-template-columns: 1fr; gap: 36px; align-items: center; }
@media (min-width: 1025px) { .ab-story { grid-template-columns: 1fr 1fr; gap: 56px; } }

/* Image collage — a static grid, so it needs no carousel JS */
.ab-collage { display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 160px 160px; gap: 12px; }
@media (min-width: 640px) { .ab-collage { grid-template-rows: 210px 210px; gap: 14px; } }

.ab-collage__img { width: 100%; height: 100%; object-fit: cover; border-radius: 16px; border: 1px solid var(--ab-border); }
.ab-collage__img--tall { grid-row: span 2; }

.ab-story__p { font-size: 15.5px; line-height: 1.75; color: var(--ab-muted); margin: 0 0 14px; }

/* ── Value cards ───────────────────────────────────────────── */
.ab-values {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-top: 40px;
}

.ab-value {
    background: var(--ab-surface);
    border: 1px solid var(--ab-border);
    border-radius: 18px;
    padding: 24px 22px;
    transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
}
.ab-value:hover { transform: translateY(-3px); border-color: rgba(187, 151, 109, .55); box-shadow: 0 18px 38px -20px rgba(10, 8, 6, .4); }

.ab-value__icon {
    display: grid; place-items: center;
    width: 50px; height: 50px;
    border-radius: 14px;
    background: rgba(187, 151, 109, .14);
    color: var(--ab-gold-ink);
    margin-bottom: 16px;
}
.ab-value__badge {
    display: inline-block;
    font-size: 10px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase;
    color: var(--ab-gold-ink);
    margin-bottom: 7px;
}
.ab-value__title { font-size: 16px; font-weight: 700; margin: 0 0 8px; color: var(--ab-text); }
.ab-value__text  { font-size: 14px; line-height: 1.65; color: var(--ab-muted); margin: 0; }

/* ── Reviews ───────────────────────────────────────────────── */
.ab-reviews {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 40px;
}

.ab-review {
    position: relative;
    background: var(--ab-surface);
    border: 1px solid var(--ab-border);
    border-radius: 18px;
    padding: 24px 22px;
    display: flex; flex-direction: column;
    transition: transform .2s ease, border-color .2s ease;
}
.ab-review:hover { transform: translateY(-2px); border-color: rgba(187, 151, 109, .5); }

.ab-review__stars { display: flex; align-items: center; gap: 3px; margin-bottom: 14px; }
.ab-review__stars svg { color: #d4a96a; }
.ab-review__stars svg[data-empty] { color: var(--ab-border); }
.ab-review__score { margin-left: 6px; font-size: 12px; font-weight: 600; color: var(--ab-muted); font-variant-numeric: tabular-nums; }

.ab-review__text { font-size: 14.5px; line-height: 1.7; color: var(--ab-text); margin: 0 0 20px; flex: 1; }

.ab-review__who { display: flex; align-items: center; gap: 11px; padding-top: 16px; border-top: 1px solid var(--ab-border); }
.ab-review__avatar {
    display: grid; place-items: center;
    width: 38px; height: 38px; flex: none;
    border-radius: 50%;
    font-size: 14px; font-weight: 700;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a, #bb976d);
}
.ab-review__name { display: block; font-size: 14px; font-weight: 700; color: var(--ab-text); }
.ab-review__role { display: block; font-size: 11.5px; color: var(--ab-muted); margin-top: 3px; }

/* Verified: icon + text, so the claim doesn't rest on colour alone */
.ab-review__badge {
    display: inline-flex; align-items: center; gap: 3px;
    font-weight: 700;
    color: #15803d;
}
.dark .ab-review__badge { color: #6ee7a8; }

.ab-review__link { color: var(--ab-gold-ink); font-weight: 600; text-decoration: none; }
.ab-review__link:hover { text-decoration: underline; text-underline-offset: 2px; }

/* No reviews yet */
.ab-noreviews {
    text-align: center;
    max-width: 32rem;
    margin: 40px auto 0;
    padding: 40px 24px;
    background: var(--ab-surface);
    border: 1px dashed var(--ab-border);
    border-radius: 18px;
}
.ab-noreviews__icon {
    display: grid; place-items: center;
    width: 56px; height: 56px; margin: 0 auto 14px;
    border-radius: 50%;
    background: rgba(187, 151, 109, .12);
    color: var(--ab-gold);
}
.ab-noreviews__title { font-size: 17px; font-weight: 700; margin: 0 0 6px; color: var(--ab-text); }
.ab-noreviews__text  { font-size: 14px; line-height: 1.65; color: var(--ab-muted); margin: 0 0 20px; }

/* ── Closing CTA ───────────────────────────────────────────── */
.ab-cta {
    position: relative;
    border-radius: 24px;
    padding: 52px 28px;
    text-align: center;
    overflow: hidden;
    background:
        radial-gradient(600px 300px at 50% 0%, rgba(187, 151, 109, .28), transparent 65%),
        linear-gradient(160deg, #172430 0%, #0a0806 100%);
}
.ab-cta__title { font-size: clamp(24px, 3.4vw, 32px); font-weight: 700; color: #fff; margin: 0 0 10px; letter-spacing: -.4px; }
.ab-cta__text  { font-size: 15px; color: rgba(255, 255, 255, .78); margin: 0 auto 24px; max-width: 48ch; line-height: 1.7; }
.ab-cta__actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

@media (prefers-reduced-motion: reduce) {
    .ab *, .ab *::before, .ab *::after { transition-duration: .01ms !important; animation: none !important; }
}
</style>
@endpush

@section('content')

@include('includes.navbar')

<div class="ab">

    {{-- ── Hero ─────────────────────────────────────────────── --}}
    <header class="ab-hero" style="--ab-hero-img: url('{{ asset('assets/img/shortcode/bg-about.png') }}')">
        <div class="ab__wrap">
            <nav class="ab-hero__crumbs" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">About</span>
            </nav>

            <h1 class="ab-hero__title">Furniture that makes a house feel like <em>home</em>.</h1>
            <p class="ab-hero__text">
                We're PeytonGhalib — a one-stop destination for quality furniture, home decor,
                and everyday essentials, at prices that leave room for the rest of your life.
            </p>

            <div class="ab-hero__actions">
                <a class="ab-btn" href="{{ url('/shop') }}">
                    Shop the collection
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a class="ab-btn ab-btn--ghost" href="{{ route('contact.show') }}">Talk to us</a>
            </div>
        </div>
    </header>

    {{-- ── Story ────────────────────────────────────────────── --}}
    <section class="ab-section" aria-labelledby="ab-story-title">
        <div class="ab__wrap">
            <div class="ab-story">

                <div class="ab-collage">
                    <img class="ab-collage__img ab-collage__img--tall" src="{{ asset('assets/img/about/about-banner-01.jpg') }}"
                         alt="A styled living-room setting" loading="lazy" width="400" height="440">
                    <img class="ab-collage__img" src="{{ asset('assets/img/about/about-banner-02.jpg') }}"
                         alt="Home decor detail" loading="lazy" width="400" height="210">
                    <img class="ab-collage__img" src="{{ asset('assets/img/about/about-banner-03.jpg') }}"
                         alt="Furniture in a finished room" loading="lazy" width="400" height="210">
                </div>

                <div>
                    <span class="ab__eyebrow">Our story</span>
                    <h2 class="ab__h2" id="ab-story-title">Built on one simple belief</h2>

                    <p class="ab-story__p">
                        At PeytonGhalib, our story began with a simple belief — that everyone deserves access to
                        quality products across every aspect of their life. From home essentials and electronics to
                        fashion, sports, and lifestyle goods, we set out to build a one-stop destination where
                        customers can discover thousands of products from trusted brands and independent sellers alike.
                    </p>
                    <p class="ab-story__p">
                        Over the years, our commitment to affordability, variety, and exceptional customer service has
                        driven our growth. We've built a platform that connects buyers with the products they love,
                        backed by fast shipping, easy returns, and a shopping experience designed around you. Today,
                        PeytonGhalib serves customers nationwide — and we're just getting started.
                    </p>

                    {{-- Counts come from the catalogue, so they can't go stale --}}
                    <div class="ab-stats">
                        <div class="ab-stat">
                            <span class="ab-stat__v">{{ number_format($productCount) }}</span>
                            <span class="ab-stat__k">Products</span>
                        </div>
                        <div class="ab-stat">
                            <span class="ab-stat__v">{{ number_format($categoryCount) }}</span>
                            <span class="ab-stat__k">Categories</span>
                        </div>
                        <div class="ab-stat">
                            <span class="ab-stat__v">Free</span>
                            <span class="ab-stat__k">Shipping</span>
                        </div>
                        <div class="ab-stat">
                            <span class="ab-stat__v">24/7</span>
                            <span class="ab-stat__k">Support</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Why choose us ────────────────────────────────────── --}}
    <section class="ab-section ab-section--alt" aria-labelledby="ab-values-title">
        <div class="ab__wrap">
            <div style="text-align:center; max-width:44rem; margin:0 auto">
                <span class="ab__eyebrow">Our promise</span>
                <h2 class="ab__h2" id="ab-values-title">Why choose PeytonGhalib</h2>
                <p class="ab__lede" style="margin:0 auto">
                    Thousands of products across multiple categories — all in one place — with unbeatable prices,
                    fast delivery, and a customer experience that keeps you coming back.
                </p>
            </div>

            @php
                // Inline SVG rather than <img>, so icons inherit colour and theme correctly.
                $values = [
                    ['badge' => 'Always free',  'title' => 'Free shipping',   'icon' => 'truck',
                     'text'  => 'Enjoy hassle-free shopping with complimentary shipping on all orders — no minimum purchase required.'],
                    ['badge' => '30-day policy','title' => 'Easy returns',    'icon' => 'box',
                     'text'  => 'Changed your mind? No problem. Our hassle-free return process ensures your complete satisfaction.'],
                    ['badge' => '100% secure',  'title' => 'Secure payment',  'icon' => 'lock',
                     'text'  => 'Shop with confidence. Our encrypted checkout keeps your payment information safe at every step.'],
                    ['badge' => 'Always here',  'title' => '24/7 support',    'icon' => 'support',
                     'text'  => 'Our dedicated support team is always ready to help you — any time, any day, any question.'],
                    ['badge' => 'QC certified', 'title' => 'Quality assured', 'icon' => 'award',
                     'text'  => 'Every product passes our rigorous QC standards. Trust in quality that goes beyond your expectations.'],
                ];
            @endphp

            <div class="ab-values">
                @foreach ($values as $value)
                    <article class="ab-value">
                        <div class="ab-value__icon" aria-hidden="true">
                            @switch($value['icon'])
                                @case('truck')
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
                                    @break
                                @case('box')
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                                    @break
                                @case('lock')
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                                    @break
                                @case('support')
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14v-2a8 8 0 0 1 16 0v2"/><rect x="2" y="14" width="4" height="6" rx="1.5"/><rect x="18" y="14" width="4" height="6" rx="1.5"/><path d="M20 20a4 4 0 0 1-4 3h-2"/></svg>
                                    @break
                                @case('award')
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="m8.5 14-1.5 8 5-3 5 3-1.5-8"/></svg>
                                    @break
                            @endswitch
                        </div>

                        <span class="ab-value__badge">{{ $value['badge'] }}</span>
                        <h3 class="ab-value__title">{{ $value['title'] }}</h3>
                        <p class="ab-value__text">{{ $value['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Reviews ──────────────────────────────────────────── --}}
    <section class="ab-section" aria-labelledby="ab-reviews-title">
        <div class="ab__wrap">
            <div style="text-align:center; max-width:44rem; margin:0 auto">
                <span class="ab__eyebrow">What our customers say</span>
                <h2 class="ab__h2" id="ab-reviews-title">Customer reviews</h2>

                @if ($ratingCount > 0)
                    <p class="ab__lede" style="margin:0 auto">
                        <strong style="color:var(--ab-text)">{{ number_format($ratingAvg, 1) }} out of 5</strong>
                        from {{ number_format($ratingCount) }} {{ Str::plural('review', $ratingCount) }} left by shoppers on the products they bought.
                    </p>
                @else
                    <p class="ab__lede" style="margin:0 auto">
                        Reviews come straight from customers on the products they've bought.
                    </p>
                @endif
            </div>

            @forelse ($reviews as $review)
                @if ($loop->first)
                    <div class="ab-reviews">
                @endif

                @php
                    // "Sarah Mitchell" → "Sarah M." — full surnames don't belong on a public page.
                    $parts    = array_values(array_filter(explode(' ', trim($review->user?->name ?? 'Customer'))));
                    $display  = $parts[0] ?? 'Customer';
                    if (count($parts) > 1) {
                        $display .= ' ' . strtoupper(substr(end($parts), 0, 1)) . '.';
                    }
                    // Only claim "verified buyer" when an order actually contains this product.
                    $isVerified = isset($verified[$review->user_id . '-' . $review->product_id]);
                @endphp

                <figure class="ab-review" style="margin:0">
                    <div class="ab-review__stars" role="img" aria-label="{{ $review->rating }} out of 5 stars">
                        @for ($s = 1; $s <= 5; $s++)
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor" @if ($s > $review->rating) data-empty @endif aria-hidden="true">
                                <path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/>
                            </svg>
                        @endfor
                        <span class="ab-review__score">{{ number_format($review->rating, 1) }}</span>
                    </div>

                    <blockquote class="ab-review__text" style="margin:0 0 20px">
                        &ldquo;{{ $review->comment }}&rdquo;
                    </blockquote>

                    <figcaption class="ab-review__who">
                        <span class="ab-review__avatar" aria-hidden="true">{{ strtoupper(substr($display, 0, 1)) }}</span>
                        <span style="min-width:0">
                            <span class="ab-review__name">{{ $display }}</span>
                            <span class="ab-review__role">
                                @if ($isVerified)
                                    <span class="ab-review__badge">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                        Verified buyer
                                    </span>
                                    &middot;
                                @endif

                                @if ($review->product)
                                    <a href="{{ route('product-details', $review->product->slug) }}" class="ab-review__link">{{ $review->product->name }}</a>
                                @endif
                            </span>
                        </span>
                    </figcaption>
                </figure>

                @if ($loop->last)
                    </div>
                @endif
            @empty
                <div class="ab-noreviews">
                    <div class="ab-noreviews__icon" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <p class="ab-noreviews__title">No reviews yet</p>
                    <p class="ab-noreviews__text">Buy something you love and tell us what you think — your review will show up right here.</p>
                    <a class="ab-btn" href="{{ url('/shop') }}">
                        Browse the collection
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ── Closing CTA ──────────────────────────────────────── --}}
    <section class="ab-section" style="padding-top:0">
        <div class="ab__wrap">
            <div class="ab-cta">
                <h2 class="ab-cta__title">Ready to make your space yours?</h2>
                <p class="ab-cta__text">
                    Browse the collection, or send us a note — we're happy to help you find the right piece.
                </p>
                <div class="ab-cta__actions">
                    <a class="ab-btn ab-btn--dark" href="{{ url('/shop') }}">
                        Start shopping
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <a class="ab-btn ab-btn--ghost" href="{{ route('contact.show') }}">Contact us</a>
                </div>
            </div>
        </div>
    </section>

</div>

@include('includes.footer')

@endsection
