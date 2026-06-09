@extends('layouts.main')
@section('title', 'PeytonGhalib - Quality Furniture & Home Decor')
@section('meta_description', 'Shop quality furniture, home decor, ceramics and more at PeytonGhalib. Thousands of products at unbeatable prices with fast, reliable delivery nationwide.')

@push('schema')
@php
$schemaWebsite = [
    '@context'        => 'https://schema.org',
    '@type'           => 'WebSite',
    'name'            => 'PeytonGhalib',
    'url'             => url('/'),
    'description'     => 'Shop quality furniture, home decor, ceramics and more at PeytonGhalib. Thousands of products at unbeatable prices with fast, reliable delivery nationwide.',
    'potentialAction' => [
        '@type'       => 'SearchAction',
        'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => url('/shop') . '?search={search_term_string}'],
        'query-input' => 'required name=search_term_string',
    ],
];
@endphp
<script type="application/ld+json">{!! json_encode($schemaWebsite, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@include('includes.navbar')

<!-- Hero Section Start -->
@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   PeytonGhalib — Premium Hero  (pgh- prefix)
═══════════════════════════════════════════════════════════════ */

/* ── Keyframes ─────────────────────────────────────────────── */
@keyframes pgh-up   { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
@keyframes pgh-left { from{opacity:0;transform:translateX(32px)} to{opacity:1;transform:translateX(0)} }
@keyframes pgh-bob  { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
@keyframes pgh-bob2 { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }
@keyframes pgh-pulse{ 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(1.7)} }
@keyframes pgh-shimmer {
    0%   { background-position: -400% center }
    100% { background-position:  400% center }
}
@keyframes pgh-spin-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

/* ── Section shell ─────────────────────────────────────────── */
.pgh-hero {
    position: relative;
    background: #FAF9F7;
    overflow: hidden;
}
.dark .pgh-hero { background: #16120e; }

/* ── Hero two-column row ────────────────────────────────────── */
.pgh-row {
    display: flex;
    flex-direction: column;
    gap: 40px;
    align-items: center;
    margin-bottom: 80px;
}
@media (min-width: 768px) {
    .pgh-row {
        flex-direction: row;
        gap: 48px;
        margin-bottom: 112px;
        align-items: center;
    }
    .pgh-row-left  { flex: 0 0 54%; max-width: 54%; }
    .pgh-row-right { flex: 0 0 46%; max-width: 46%; }
}
.pgh-row-left  { width: 100%; }
.pgh-row-right { width: 100%; }

/* Mesh gradient orbs */
.pgh-orb {
    position: absolute; border-radius: 50%;
    filter: blur(80px); pointer-events: none; z-index: 0;
}
.pgh-orb-1 {
    width: 600px; height: 600px;
    top: -200px; right: -100px;
    background: radial-gradient(circle, rgba(187,151,109,.12) 0%, transparent 70%);
}
.pgh-orb-2 {
    width: 500px; height: 500px;
    bottom: -200px; left: -100px;
    background: radial-gradient(circle, rgba(187,151,109,.08) 0%, transparent 70%);
}
.pgh-orb-3 {
    width: 300px; height: 300px;
    top: 40%; left: 38%;
    background: radial-gradient(circle, rgba(212,169,106,.06) 0%, transparent 70%);
}

/* ── Live trust badge ──────────────────────────────────────── */
.pgh-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 18px; border-radius: 100px;
    background: rgba(187,151,109,.1);
    border: 1px solid rgba(187,151,109,.25);
    font-size: 12px; font-weight: 700; letter-spacing: .35px; color: #8a6a30;
    animation: pgh-up .6s ease both;
}
.dark .pgh-badge { color: #d4a96a; background: rgba(187,151,109,.12); border-color: rgba(187,151,109,.22); }
.pgh-badge-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #22c55e;
    animation: pgh-pulse 1.8s ease-in-out infinite;
}

/* ── Headline ──────────────────────────────────────────────── */
.pgh-h1 {
    font-size: clamp(2.4rem, 4.5vw, 4rem);
    font-weight: 800; line-height: 1.08;
    letter-spacing: -.02em; color: #1c1410;
    animation: pgh-up .65s .12s ease both;
}
.dark .pgh-h1 { color: #f5f0ea; }
.pgh-h1-grad {
    background: linear-gradient(135deg, #bb976d 0%, #e8c48a 40%, #8b6510 100%);
    background-size: 200% auto;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: pgh-shimmer 6s linear infinite;
}

/* ── Body copy ─────────────────────────────────────────────── */
.pgh-body {
    font-size: 17px; line-height: 1.72; color: #6b5d52;
    animation: pgh-up .65s .22s ease both;
}
.dark .pgh-body { color: #a09080; }

/* ── CTA buttons ───────────────────────────────────────────── */
.pgh-cta-wrap { animation: pgh-up .65s .32s ease both; }
.pgh-btn-a {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 15px 30px; border-radius: 14px;
    background: linear-gradient(135deg, #c4a070 0%, #bb976d 50%, #a07840 100%);
    background-size: 200% auto;
    color: #fff; font-size: 14px; font-weight: 700; letter-spacing: .4px;
    box-shadow: 0 6px 28px rgba(187,151,109,.42), 0 1px 4px rgba(187,151,109,.2);
    transition: all .35s cubic-bezier(.22,.68,0,1.15);
    text-decoration: none;
}
.pgh-btn-a:hover {
    background-position: right center; color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(187,151,109,.52);
}
.pgh-btn-a svg { transition: transform .3s ease; }
.pgh-btn-a:hover svg { transform: translateX(4px); }

.pgh-btn-b {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 26px; border-radius: 14px;
    background: #fff; color: #3d2e20;
    font-size: 14px; font-weight: 600;
    border: 1.5px solid #e8ddd4;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: all .3s ease; text-decoration: none;
}
.dark .pgh-btn-b { background: rgba(255,255,255,.07); color: #f0e8de; border-color: rgba(255,255,255,.13); }
.pgh-btn-b:hover { border-color: #bb976d; color: #bb976d; transform: translateY(-2px); box-shadow: 0 6px 22px rgba(187,151,109,.16); }

/* ── Social proof row ──────────────────────────────────────── */
.pgh-proof { animation: pgh-up .65s .42s ease both; }
.pgh-avatar-stack { display: flex; }
.pgh-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    border: 2.5px solid #fff; margin-left: -10px;
    background: linear-gradient(135deg, #d4a96a, #bb976d);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 700; color: #fff;
    flex-shrink: 0;
    overflow: hidden;
}
.pgh-avatar:first-child { margin-left: 0; }
.pgh-stars { color: #f59e0b; font-size: 13px; letter-spacing: .5px; }

/* ── Stats row ─────────────────────────────────────────────── */
.pgh-stats { animation: pgh-up .65s .52s ease both; }
.pgh-stat-n {
    font-size: 28px; font-weight: 800; line-height: 1;
    background: linear-gradient(135deg, #bb976d, #8b6510);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.pgh-stat-sep { width: 1px; height: 40px; background: linear-gradient(to bottom, transparent, rgba(187,151,109,.3), transparent); }

/* ── Hero image collage ────────────────────────────────────── */
.pgh-collage {
    position: relative; z-index: 1;
    animation: pgh-left .75s .1s ease both;
    padding: 24px 0 24px 0;
}
/* Decorative glow shape behind image */
.pgh-collage-glow {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 60% 50%, rgba(187,151,109,.18) 0%, transparent 68%);
    pointer-events: none; z-index: 0;
}
.pgh-img-wrap {
    position: relative; z-index: 1;
    border-radius: 28px; overflow: hidden;
    height: 520px;
    box-shadow: 0 32px 90px rgba(0,0,0,.22), 0 8px 24px rgba(0,0,0,.1);
    transform: perspective(1200px) rotateY(-2deg) rotateX(1.5deg);
    transition: transform .6s cubic-bezier(.22,.68,0,1.1), box-shadow .6s ease;
}
.pgh-img-wrap:hover {
    transform: perspective(1200px) rotateY(0deg) rotateX(0deg) scale(1.01);
    box-shadow: 0 40px 110px rgba(0,0,0,.26);
}
.pgh-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: center;
    display: block;
    transition: transform .7s ease;
}
.pgh-img-wrap:hover img { transform: scale(1.04); }
@media (max-width: 1279px) { .pgh-img-wrap { height: 440px; } }
@media (max-width: 1023px) { .pgh-img-wrap { height: 380px; transform: none; border-radius: 22px; } }
@media (max-width: 767px)  { .pgh-img-wrap { height: 280px; } }

/* Top-right decorative corner dot grid */
.pgh-dot-grid {
    position: absolute; top: 0; right: -16px; z-index: 0;
    width: 120px; height: 120px;
    background-image: radial-gradient(rgba(187,151,109,.35) 1.5px, transparent 1.5px);
    background-size: 16px 16px;
    opacity: .7;
    pointer-events: none;
}
/* Bottom-left accent line */
.pgh-img-accent {
    position: absolute; bottom: 12px; left: 0; z-index: 0;
    width: 80px; height: 4px; border-radius: 2px;
    background: linear-gradient(90deg, #bb976d, transparent);
}
/* "New Season" floating label on image */
.pgh-new-badge {
    position: absolute; top: 44px; left: 20px; z-index: 10;
    background: linear-gradient(135deg, #bb976d, #d4a96a);
    color: #fff; font-size: 10px; font-weight: 800;
    letter-spacing: .8px; text-transform: uppercase;
    padding: 5px 12px; border-radius: 8px;
    box-shadow: 0 4px 14px rgba(187,151,109,.5);
    pointer-events: none;
}

/* ── Floating cards ────────────────────────────────────────── */
.pgh-float {
    position: absolute; z-index: 20;
    background: rgba(255,255,255,.9);
    backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,.8);
    border-radius: 18px;
    box-shadow: 0 10px 40px rgba(0,0,0,.14), 0 2px 8px rgba(0,0,0,.06);
    padding: 12px 16px; white-space: nowrap;
}
.dark .pgh-float { background:rgba(25,18,10,.88); border-color:rgba(255,255,255,.1); }
.pgh-fi { width:36px;height:36px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.pgh-fv { font-size:13px;font-weight:700;color:#1c1208;line-height:1.15; }
.dark .pgh-fv { color:#f5ede0; }
.pgh-fs { font-size:10px;color:#a09080;margin-top:2px; }
.pgh-float-1 { top:18px; right:18px; animation:pgh-bob 4.5s ease-in-out infinite; }
.pgh-float-2 { bottom:26px; left:18px; animation:pgh-bob2 4s ease-in-out infinite; animation-delay:-2s; }
.pgh-float-3 { top:42%; right:18px; animation:pgh-bob 5s ease-in-out infinite; animation-delay:-3.5s; }

/* ── Category image cards ──────────────────────────────────── */
.pgh-cats-scroll {
    display: flex; gap: 14px;
    overflow-x: auto; padding-bottom: 8px;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.pgh-cats-scroll::-webkit-scrollbar { display: none; }
@media(min-width:1024px) {
    .pgh-cats-scroll { display: grid; grid-template-columns: repeat(6,1fr); overflow-x: visible; }
}
.pgh-catimg-card {
    position: relative; overflow: hidden;
    border-radius: 22px; flex-shrink: 0;
    width: 160px; height: 200px;
    background: #e4dbd0;
    text-decoration: none;
    transition: transform .4s cubic-bezier(.22,.68,0,1.1), box-shadow .4s ease;
}
@media(min-width:1024px){ .pgh-catimg-card { width: auto; height: 210px; } }
.pgh-catimg-card img { width:100%;height:100%;object-fit:cover;display:block;transition:transform .6s ease; }
.pgh-catimg-card:hover img { transform: scale(1.1); }
.pgh-catimg-card:hover { transform: translateY(-5px); box-shadow: 0 18px 48px rgba(0,0,0,.2); }
.pgh-cat-scrim {
    position:absolute; inset:0;
    background: linear-gradient(to top, rgba(0,0,0,.72) 0%, rgba(0,0,0,.18) 55%, transparent 100%);
    transition: opacity .3s;
}
.pgh-catimg-card:hover .pgh-cat-scrim { opacity: .85; }
.pgh-cat-body { position:absolute; bottom:0; left:0; right:0; padding:14px 14px 16px; }
.pgh-cat-count {
    display: inline-block;
    font-size: 10px; font-weight: 600; letter-spacing: .5px; text-transform: uppercase;
    color: rgba(255,255,255,.7); margin-bottom: 4px;
}
.pgh-cat-name { font-size:14px; font-weight:700; color:#fff; line-height:1.25; }
.pgh-cat-arrow {
    position: absolute; top: 14px; right: 14px;
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transform: translateY(6px);
    transition: opacity .3s, transform .3s;
}
.pgh-catimg-card:hover .pgh-cat-arrow { opacity: 1; transform: translateY(0); }
/* Category fallback */
.pgh-cat-fb { width:100%;height:100%;display:flex;align-items:center;justify-content:center; }


/* ── Responsive misc ────────────────────────────────────────── */
@media(max-width:1023px){
    .pgh-collage  { padding: 8px 0; }
    .pgh-dot-grid { width: 72px; height: 72px; right: -4px; }
    .pgh-float    { padding:9px 13px; }
    .pgh-fv       { font-size:12px; }
    .pgh-fs       { font-size:9px; }
    .pgh-fi       { width:28px;height:28px; }
    .pgh-float-1  { top:12px !important; right:12px !important; }
    .pgh-float-2  { bottom:16px !important; left:12px !important; }
    .pgh-float-3  { display:none !important; }
}
@media(max-width:639px){
    .pgh-h1     { font-size: 2.15rem; }
    .pgh-body   { font-size: 15px; }
    .pgh-stat-n { font-size: 22px; }
    .pgh-ti     { padding: 16px 14px; }
    .pgh-float-2{ display:none !important; }
}
</style>
@endpush

{{-- ── PHP data prep ──────────────────────────────────────────── --}}
@php
    $catIconMap = [
        'sofa'     => '<path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/><path d="M2 11a2 2 0 1 1 4 0v2h12v-2a2 2 0 1 1 4 0v4a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/><path d="M6 17v2"/><path d="M18 17v2"/>',
        'chair'    => '<path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/><path d="M2 11a2 2 0 1 1 4 0v2h12v-2a2 2 0 1 1 4 0v4a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>',
        'lamp'     => '<path d="M9 18h6"/><path d="M10 22h4"/><path d="m5 6 7-4 7 4"/><path d="M12 2v4"/><rect x="5" y="6" width="14" height="12" rx="2"/>',
        'vase'     => '<path d="M8 22h8"/><path d="M7 10h10"/><path d="M12 10v12"/><path d="M9 3h6l1 7H8z"/>',
        'table'    => '<path d="M3 6h18"/><path d="M3 18h18"/><path d="M8 6v12"/><path d="M16 6v12"/>',
        'wood'     => '<path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" y1="2" x2="6" y2="4"/><line x1="10" y1="2" x2="10" y2="4"/><line x1="14" y1="2" x2="14" y2="4"/>',
        'interior' => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'bedroom'  => '<path d="M2 4v16"/><path d="M2 8h18a2 2 0 012 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/>',
        'outdoor'  => '<circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>',
    ];
    if (!function_exists('pgbIcon')) {
        function pgbIcon(string $slug, array $map): string {
            $slug = strtolower($slug);
            foreach ($map as $key => $path) {
                if (str_contains($slug, $key)) return $path;
            }
            return '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>';
        }
    }
@endphp

<section class="pgh-hero pt-24 pb-16 md:pt-32 md:pb-24 xl:pt-36 xl:pb-28">

    {{-- Background orbs --}}
    <div class="pgh-orb pgh-orb-1"></div>
    <div class="pgh-orb pgh-orb-2"></div>
    <div class="pgh-orb pgh-orb-3"></div>

    <div class="container-fluid" style="position:relative;z-index:1;">
        <div class="max-w-[1720px] mx-auto">

            {{-- ════════════════════════════════════════════════════════════
                 ROW 1 — Hero copy + Bento collage
            ════════════════════════════════════════════════════════════ --}}
            <div class="pgh-row">

                {{-- LEFT: Copy -------------------------------------------- --}}
                <div class="pgh-row-left">

                    {{-- Live trust badge --}}
                    <div class="pgh-badge">
                        <span class="pgh-badge-dot"></span>
                        Trusted by 10,000+ Customers
                    </div>

                    {{-- H1 --}}
                    <h1 class="pgh-h1 mt-5">
                        Premium Furniture<br>
                        &amp; Home Decor,<br>
                        <span class="pgh-h1-grad">Delivered to Your Door</span>
                    </h1>

                    {{-- Body --}}
                    <p class="pgh-body mt-5 max-w-[500px]">
                        From handcrafted sofas and artisan ceramics to complete interior collections — curated for modern living, delivered with care.
                    </p>

                    {{-- CTAs --}}
                    <div class="pgh-cta-wrap mt-8 flex flex-wrap gap-3 md:gap-4">
                        <a href="{{ url('/shop') }}" class="pgh-btn-a">
                            Shop Now
                            <svg width="15" height="11" viewBox="0 0 16 12" fill="none"><path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <a href="{{ url('/categories') }}" class="pgh-btn-b">
                            Explore Collections
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        </a>
                    </div>

                    {{-- Social proof --}}
                    <div class="pgh-proof mt-8 flex items-center gap-4">
                        <div class="pgh-avatar-stack">
                            {{-- Avatar 1 --}}
                            <div class="pgh-avatar" style="background:linear-gradient(135deg,#d4a96a,#bb976d);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="rgba(255,255,255,.9)"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            </div>
                            {{-- Avatar 2 --}}
                            <div class="pgh-avatar" style="background:linear-gradient(135deg,#c4a070,#8b6510);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="rgba(255,255,255,.9)"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            </div>
                            {{-- Avatar 3 --}}
                            <div class="pgh-avatar" style="background:linear-gradient(135deg,#e8c48a,#c4903c);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="rgba(255,255,255,.9)"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            </div>
                            {{-- +count --}}
                            <div class="pgh-avatar" style="background:linear-gradient(135deg,#a07840,#6b4f20);font-size:9px;font-weight:700;">+9K</div>
                        </div>
                        <div>
                            <div class="pgh-stars">★★★★★</div>
                            <div style="font-size:12px;color:#a09080;margin-top:2px;">4.9 from 2k+ reviews</div>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="pgh-stats mt-9 flex items-center gap-5 md:gap-7">
                        <div>
                            <div class="pgh-stat-n">10K+</div>
                            <div class="text-xs text-paragraph dark:text-white-light mt-1.5 font-medium">Happy Customers</div>
                        </div>
                        <div class="pgh-stat-sep"></div>
                        <div>
                            <div class="pgh-stat-n">500+</div>
                            <div class="text-xs text-paragraph dark:text-white-light mt-1.5 font-medium">Products</div>
                        </div>
                        <div class="pgh-stat-sep"></div>
                        <div>
                            <div class="pgh-stat-n">4.9★</div>
                            <div class="text-xs text-paragraph dark:text-white-light mt-1.5 font-medium">Avg. Rating</div>
                        </div>
                        <div class="pgh-stat-sep hidden sm:block"></div>
                        <div class="hidden sm:block">
                            <div class="pgh-stat-n">30D</div>
                            <div class="text-xs text-paragraph dark:text-white-light mt-1.5 font-medium">Free Returns</div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Hero image with floating badges ------------------- --}}
                <div class="pgh-collage pgh-row-right">

                    {{-- Soft glow behind image --}}
                    <div class="pgh-collage-glow"></div>

                    {{-- Dot grid accent (top-right) --}}
                    <div class="pgh-dot-grid"></div>

                    {{-- Bottom accent line --}}
                    <div class="pgh-img-accent"></div>

                    {{-- Main hero image --}}
                    <div class="pgh-img-wrap">
                        <img src="{{ asset('assets/img/home-v1/banner-02.png') }}"
                             alt="Premium Furniture — Delivered to Your Door"
                             loading="eager">
                    </div>

                    {{-- "New Season" pill over image (outside wrap so not clipped) --}}
                    <span class="pgh-new-badge">New Season</span>

                    {{-- Float 1: Rating (top-right over image) --}}
                    <div class="pgh-float pgh-float-1">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="pgh-fi" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            <div><div class="pgh-fv">4.9 / 5.0</div><div class="pgh-fs">2k+ Reviews</div></div>
                        </div>
                    </div>

                    {{-- Float 2: Free delivery (bottom-left over image) --}}
                    <div class="pgh-float pgh-float-2">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="pgh-fi" style="background:linear-gradient(135deg,#bb976d,#8b6510);">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            </div>
                            <div><div class="pgh-fv">Free Delivery</div><div class="pgh-fs">On all orders</div></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════════
                 ROW 2 — Category image cards
            ════════════════════════════════════════════════════════════ --}}
            <div class="mb-14 md:mb-20">
                <div class="flex items-end justify-between mb-7" data-aos="fade-up">
                    <div>
                        <span class="text-xs uppercase tracking-widest text-primary font-semibold">Browse by Collection</span>
                        <h2 class="mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">Shop Your Style</h2>
                    </div>
                    <a href="{{ url('/categories') }}"
                       class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-primary
                              border border-primary/30 px-5 py-2.5 rounded-full
                              hover:bg-primary hover:text-white hover:border-primary transition-all duration-200">
                        All Collections →
                    </a>
                </div>

                <div class="pgh-cats-scroll" data-aos="fade-up" data-aos-delay="80">
                    @foreach($categories->take(6) as $cat)
                    @php
                        $catSrc = null;
                        if ($cat->image) {
                            $catSrc = str_starts_with($cat->image,'assets/') ? asset($cat->image) : Storage::url($cat->image);
                        }
                        $catFb = ['#c8b9a8','#b8a48e','#c4b09a','#d4c0aa','#b09078','#c0a88c'];
                    @endphp
                    <a href="{{ route('category.landing', $cat->slug) }}" class="pgh-catimg-card"
                       style="animation-delay:{{ $loop->index * .07 }}s;">
                        @if($catSrc)
                            <img src="{{ $catSrc }}" alt="{{ $cat->name }}">
                        @else
                            <div class="pgh-cat-fb" style="background:{{ $catFb[$loop->index % 6] }};">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                    {!! pgbIcon($cat->slug ?? $cat->name, $catIconMap) !!}
                                </svg>
                            </div>
                        @endif
                        <div class="pgh-cat-scrim"></div>
                        <div class="pgh-cat-body">
                            <div class="pgh-cat-count">{{ $cat->products_count }} {{ Str::plural('item', $cat->products_count) }}</div>
                            <div class="pgh-cat-name">{{ $cat->name }}</div>
                        </div>
                        <div class="pgh-cat-arrow">
                            <svg width="12" height="10" viewBox="0 0 16 12" fill="none"><path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Trust Strip Start -->
<div class="ts-wrap">
    <div class="container-fluid">
        <div class="ts-strip" data-aos="fade-up">

                {{-- Free Shipping --}}
                <div class="ts-item">
                    <div class="ts-icon" style="background:#FEF3E8;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C9893A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13" rx="1"/>
                            <path d="M16 8h4l3 4v3h-7V8z"/>
                            <circle cx="5.5" cy="18.5" r="2.5"/>
                            <circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="ts-label">Free Shipping</div>
                        <div class="ts-sub">On all orders</div>
                    </div>
                </div>

                <div class="ts-sep"></div>

                {{-- Secure Payments --}}
                <div class="ts-item">
                    <div class="ts-icon" style="background:#EBF4FF;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="ts-label">Secure Payments</div>
                        <div class="ts-sub">SSL encrypted</div>
                    </div>
                </div>

                <div class="ts-sep"></div>

                {{-- Easy Returns --}}
                <div class="ts-item">
                    <div class="ts-icon" style="background:#E8FBF6;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 .49-4.5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="ts-label">Easy Returns</div>
                        <div class="ts-sub">30-day policy</div>
                    </div>
                </div>

                <div class="ts-sep"></div>

                {{-- 24/7 Support --}}
                <div class="ts-item">
                    <div class="ts-icon" style="background:#F0EAFF;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.64A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="ts-label">24/7 Support</div>
                        <div class="ts-sub">Always here for you</div>
                    </div>
                </div>

                <div class="ts-sep"></div>

                {{-- 10K+ Customers --}}
                <div class="ts-item">
                    <div class="ts-icon" style="background:#FFF3E8;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                            <path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <div>
                        <div class="ts-label">10K+ Customers</div>
                        <div class="ts-sub">And counting</div>
                    </div>
                </div>

        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Trust Strip ───────────────────────────────────────────── */
.ts-wrap {
    padding: 10px 0 32px;
    background: #FAF9F7;
}
.dark .ts-wrap { background: #16120e; }

.ts-strip {
    display: flex;
    align-items: stretch;
    width: 100%;
    background: #ffffff;
    border: 1px solid #EDE7DF;
    border-radius: 18px;
    box-shadow: 0 2px 20px rgba(0,0,0,.055);
    overflow: hidden;
}
.dark .ts-strip {
    background: rgba(255,255,255,.04);
    border-color: rgba(255,255,255,.08);
}

.ts-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 24px 16px;
    flex: 1 1 0%;
    min-width: 0;
    transition: background .25s ease;
}
.ts-item:hover { background: #FDFAF7; }
.dark .ts-item:hover { background: rgba(255,255,255,.03); }

.ts-icon {
    width: 46px; height: 46px;
    border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: transform .3s cubic-bezier(.22,.68,0,1.2);
}
.ts-item:hover .ts-icon { transform: scale(1.12) rotate(-6deg); }

.ts-label {
    font-size: 15px;
    font-weight: 700;
    color: #1c1410;
    line-height: 1.2;
    white-space: nowrap;
}
.dark .ts-label { color: #f0e8de; }

.ts-sub {
    font-size: 12px;
    color: #a09080;
    margin-top: 3px;
    white-space: nowrap;
}

.ts-sep {
    width: 1px;
    align-self: stretch;
    background: linear-gradient(to bottom, transparent 10%, #EDE7DF 40%, #EDE7DF 60%, transparent 90%);
    flex-shrink: 0;
}
.dark .ts-sep { background: linear-gradient(to bottom, transparent 10%, rgba(255,255,255,.1) 40%, rgba(255,255,255,.1) 60%, transparent 90%); }

/* Responsive */
@media (max-width: 1199px) {
    .ts-item  { padding: 22px 12px; gap: 12px; }
    .ts-icon  { width: 42px; height: 42px; border-radius: 12px; }
    .ts-label { font-size: 14px; }
}
@media (max-width: 1023px) {
    .ts-item  { padding: 20px 10px; gap: 10px; }
    .ts-icon  { width: 38px; height: 38px; border-radius: 11px; }
    .ts-label { font-size: 13px; }
    .ts-sub   { font-size: 11px; }
}
@media (max-width: 767px) {
    .ts-strip { flex-wrap: wrap; border-radius: 14px; }
    .ts-item  { flex: 0 0 50%; justify-content: flex-start; padding: 18px 18px; }
    .ts-sep   { display: none; }
}
@media (max-width: 479px) {
    .ts-item  { flex: 0 0 100%; border-bottom: 1px solid #EDE7DF; }
    .ts-item:last-child { border-bottom: none; }
}
</style>
@endpush
<!-- Trust Strip End -->

<!-- Product Category Area Start -->
<div class="s-py-100-50">
    <div class="container-fluid">

        <!-- Section Title -->
        <div class="flex items-end justify-between gap-4 mb-8 md:mb-10 max-w-[1720px] mx-auto" data-aos="fade-up">
            <div>
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Top Sellers</span>
                <h2 class="leading-tight mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">Featured Products</h2>
                <p class="mt-1.5 text-sm text-gray-400 dark:text-gray-500 hidden sm:block">Handpicked favourites loved by our customers</p>
            </div>
            <a href="{{ url('/shop') }}"
               class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-title dark:text-white
                      border border-current px-5 py-2.5 rounded-full hover:text-primary hover:border-primary
                      duration-300 whitespace-nowrap">
                View All
                <svg width="14" height="10" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/>
                </svg>
            </a>
        </div>

        @push('styles')
        <style>
        .hv1-pdct-ctgry-slider .owl-item { padding-right: 16px; }
        .hv1-pdct-ctgry-slider .owl-item:last-child { padding-right: 0; }
        .pgcat-card { position:relative; overflow:hidden; border-radius:18px; cursor:pointer; display:block; text-decoration:none; height:300px; background:#f0ece8; transition:transform .35s cubic-bezier(.22,.68,0,1.15), box-shadow .35s ease; }
        .pgcat-card:hover { transform:translateY(-6px); box-shadow:0 24px 60px rgba(0,0,0,.22); }
        .pgcat-img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:transform .5s ease; }
        .pgcat-card:hover .pgcat-img { transform:scale(1.06); }
        .pgcat-scrim { position:absolute; inset:0; background:linear-gradient(to top,rgba(0,0,0,.62) 0%,rgba(0,0,0,.18) 50%,transparent 100%); pointer-events:none; }
        .pgcat-body { position:absolute; bottom:0; left:0; right:0; padding:20px 20px 18px; text-align:center; }
        .pgcat-name { font-size:18px; font-weight:700; color:#fff; line-height:1.2; letter-spacing:.2px; margin:0 0 6px; text-shadow:0 2px 8px rgba(0,0,0,.4); }
        .pgcat-count { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; letter-spacing:.5px; text-transform:uppercase; color:rgba(255,255,255,.85); padding:4px 12px; border-radius:20px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); transition:background .3s; }
        .pgcat-card:hover .pgcat-count { background:rgba(255,255,255,.25); }
        .pgcat-cta { position:absolute; bottom:0; left:0; right:0; padding:14px 20px; display:flex; align-items:center; justify-content:center; gap:8px; background:rgba(255,255,255,.12); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border-top:1px solid rgba(255,255,255,.15); transform:translateY(100%); transition:transform .3s cubic-bezier(.22,.68,0,1.1); }
        .pgcat-card:hover .pgcat-cta { transform:translateY(0); }
        .pgcat-cta span { font-size:13px; font-weight:700; color:#fff; letter-spacing:.4px; text-transform:uppercase; }
        .pgcat-cta svg { transition:transform .3s ease; }
        .pgcat-card:hover .pgcat-cta svg { transform:translateX(4px); }
        .pgcat-no-img { position:absolute; inset:0; background:linear-gradient(145deg,#C8956A 0%,#7B4A2D 100%); }
        .pgcat-card:active { transform:translateY(-3px) scale(.98); }
        </style>
        @endpush

        <!-- Slider Wrapper -->
        <div class="max-w-[1720px] mx-auto relative" data-aos="fade-up" data-aos-delay="100">
            <div class="owl-carousel hv1-pdct-ctgry-slider"
                 data-carousel-items="4"
                 data-carousel-xl="4"
                 data-carousel-lg="4"
                 data-carousel-md="4"
                 data-carousel-sm="2"
                 data-carousel-xs="1"
                 data-carousel-margin="16"
                 data-carousel-loop="false"
                 data-carousel-autoplay="false">
                @include('includes.Home.best-sellers')
            </div>

            <!-- Prev Button -->
            <button class="hv1pdct_prev
                           absolute top-1/2 -translate-y-1/2 -left-3 sm:-left-5 z-[99]
                           w-10 h-10 md:w-12 md:h-12 rounded-full
                           bg-white dark:bg-title shadow-lg border border-gray-100 dark:border-gray-700
                           flex items-center justify-center
                           text-title dark:text-white hover:bg-primary hover:text-white hover:border-primary
                           duration-300"
                    aria-label="Previous">
                <svg width="14" height="12" viewBox="0 0 24 14" fill="none">
                    <path d="M0.18 7.39L5.62 12.83C5.82 13.06 6.16 13.09 6.39 12.89C6.62 12.70 6.65 12.35 6.45 12.12L1.88 7.55L23.43 7.55C23.73 7.55 23.98 7.30 23.98 7.00C23.98 6.70 23.73 6.46 23.43 6.46L1.88 6.46L6.39 1.94C6.62 1.75 6.65 1.40 6.45 1.18C6.26.95 5.91.92 5.68 1.12L0.18 6.62C-0.03 6.83-0.03 7.17 0.18 7.39Z" fill="currentColor"/>
                </svg>
            </button>

            <!-- Next Button -->
            <button class="hv1pdct_next
                           absolute top-1/2 -translate-y-1/2 -right-3 sm:-right-5 z-[99]
                           w-10 h-10 md:w-12 md:h-12 rounded-full
                           bg-white dark:bg-title shadow-lg border border-gray-100 dark:border-gray-700
                           flex items-center justify-center
                           text-title dark:text-white hover:bg-primary hover:text-white hover:border-primary
                           duration-300"
                    aria-label="Next">
                <svg width="14" height="12" viewBox="0 0 24 14" fill="none">
                    <path d="M23.82 6.62L18.38 1.18C18.18.95 17.84.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46L.57 6.46C.27 6.46.02 6.71.02 7.01C.02 7.31.27 7.55.57 7.55L22.12 7.55L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z" fill="currentColor"/>
                </svg>
            </button>
        </div>

    </div>
</div>
<!-- Featured Products Area End -->

<!-- New Arrivals Area Start -->
<section class="s-py-50-100">
    <div class="container-fluid">
        <div class="flex items-end justify-between gap-4 mb-8 md:mb-12 max-w-[1720px] mx-auto" data-aos="fade-up">
            <div>
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Just landed</span>
                <h2 class="leading-tight mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">New Arrivals</h2>
                <p class="mt-1.5 text-sm text-gray-400 dark:text-gray-500 hidden sm:block">Fresh pieces added to our collection</p>
            </div>
            <a href="{{ url('/shop') }}"
               class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-title dark:text-white
                      border border-current px-5 py-2.5 rounded-full hover:text-primary hover:border-primary
                      duration-300 whitespace-nowrap">
                View All
                <svg width="14" height="10" viewBox="0 0 24 14" fill="none"><path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/></svg>
            </a>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-8" data-aos="fade-up" data-aos-delay="100">
            @include('includes.Home.new-products')
        </div>
        <div class="text-center mt-8 md:mt-12">
            <a href="{{ url('/shop') }}" class="btn btn-outline" data-text="Shop All New Arrivals">
                <span>Shop All New Arrivals</span>
            </a>
        </div>
    </div>
</section>
<!-- New Arrivals Area End -->

<!-- Why Shop with PeytonGhalib Start -->
<section class="s-py-100 bg-overlay dark:before:bg-title dark:before:bg-opacity-80" style="background-image: url('{{ asset('assets/img/home-v1/bg.png') }}');">
    <img class="absolute top-0 right-0 w-[20%] z-[-1]" src="{{ asset('assets/img/home-v1/shape-01.png') }}" alt="shape">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <div class="max-w-[1186px] ml-auto">
                <div class="mb-8 md:mb-12" data-aos="fade-up">
                    <span class="text-xs uppercase tracking-widest text-primary font-semibold">Our promise</span>
                    <h2 class="leading-tight mt-2 text-2xl md:text-3xl font-bold text-title dark:text-white">Why Shop with PeytonGhalib</h2>
                    <p class="mt-3 max-w-xl text-paragraph dark:text-white-light">We're committed to delivering quality furniture and home decor with service you can count on — from browsing to your doorstep.</p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-[30px]">
                    @include('includes.Home.services')
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why Shop with PeytonGhalib End -->

<!-- Best Selling Products Start -->
<section class="s-py-100-50 bg-[#FAFAF8] dark:bg-dark-secondary">
    <div class="container-fluid">
        <div class="flex items-end justify-between gap-4 mb-8 md:mb-12 max-w-[1720px] mx-auto" data-aos="fade-up">
            <div>
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Customer favourites</span>
                <h2 class="leading-tight mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">Best Selling Products</h2>
                <p class="mt-1.5 text-sm text-gray-400 dark:text-gray-500 hidden sm:block">Most loved pieces by our customers</p>
            </div>
            <a href="{{ url('/shop') }}"
               class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-title dark:text-white
                      border border-current px-5 py-2.5 rounded-full hover:text-primary hover:border-primary
                      duration-300 whitespace-nowrap">
                Shop All
                <svg width="14" height="10" viewBox="0 0 24 14" fill="none"><path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/></svg>
            </a>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-8" data-aos="fade-up" data-aos-delay="100">
            @include('includes.Home.best-sellers')
        </div>
        <div class="text-center mt-8 md:mt-12">
            <a href="{{ url('/shop') }}" class="btn btn-outline" data-text="Shop All Best Sellers">
                <span>Shop All Best Sellers</span>
            </a>
        </div>
    </div>
</section>
<!-- Best Selling Products End -->

<!-- Blog Start -->
{{-- <div class="s-py-50">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <!-- Section Title -->
            <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
                <div> 
                    <svg class="mx-auto w-14 sm:w-24" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M54.1712 13.3447C51.1919 10.3694 47.603 8.07541 43.6517 6.62074C39.7004 5.16606 35.4808 4.58531 31.2834 4.91849C27.583 5.20919 23.9747 6.21786 20.66 7.88816C20.3943 8.02261 20.1928 8.25689 20.0996 8.53966C20.0064 8.82242 20.0291 9.13061 20.1627 9.39668C20.2963 9.66275 20.5299 9.865 20.8124 9.9591C21.0949 10.0532 21.4032 10.0315 21.6696 9.89869C24.7242 8.35948 28.0493 7.42992 31.4592 7.16195C35.6479 6.82393 39.8575 7.47425 43.749 9.06055C47.6404 10.6469 51.105 13.1248 53.8637 16.2949C56.6224 19.4649 58.5981 23.2385 59.6318 27.3117C60.6655 31.3849 60.7282 35.644 59.815 39.7459C58.9018 43.8478 57.0381 47.6779 54.3739 50.9279C51.7098 54.1778 48.3198 56.7568 44.4768 58.457C40.6337 60.1573 36.4452 60.9313 32.2483 60.7169C28.0514 60.5024 23.9636 59.3054 20.3139 57.2223C19.7228 56.8884 19.0678 56.6832 18.3918 56.6203C17.7159 56.5573 17.0343 56.638 16.3917 56.857L7.80458 59.7162L10.6627 51.1324C10.8835 50.4918 10.9652 49.8115 10.9025 49.1368C10.8398 48.4621 10.6341 47.8086 10.2991 47.2196C7.75663 42.7571 6.54397 37.6604 6.80425 32.5311C6.99787 28.7145 8.02183 24.9862 9.8047 21.6061C9.87344 21.4753 9.91576 21.3322 9.92922 21.1851C9.94269 21.0379 9.92704 20.8896 9.88317 20.7485C9.8393 20.6074 9.76807 20.4763 9.67355 20.3628C9.57902 20.2492 9.46306 20.1554 9.33227 20.0866C9.20148 20.0179 9.05843 19.9756 8.91129 19.9621C8.76415 19.9486 8.6158 19.9643 8.47471 20.0081C8.33361 20.052 8.20254 20.1232 8.08898 20.2178C7.97541 20.3123 7.88157 20.4283 7.81283 20.559C5.87824 24.2277 4.76714 28.2741 4.55699 32.4163C4.2749 37.9775 5.59054 43.5033 8.34846 48.3408C8.52452 48.6541 8.63172 49.0014 8.66288 49.3595C8.69405 49.7176 8.64847 50.0782 8.5292 50.4172L4.95962 61.1385C4.89365 61.3366 4.88416 61.5493 4.93218 61.7525C4.98021 61.9558 5.08386 62.1416 5.23154 62.2893C5.37922 62.437 5.56509 62.5407 5.76835 62.5887C5.9716 62.6367 6.18421 62.6272 6.38237 62.5612L17.1069 58.9906C17.4487 58.8722 17.8116 58.8275 18.1718 58.8594C18.5321 58.8913 18.8815 58.9991 19.1971 59.1757C23.5981 61.6894 28.5801 63.0079 33.6483 63.0002C34.1334 63.0002 34.6193 62.9881 35.1062 62.9639C40.7246 62.6746 46.1386 60.7621 50.692 57.4581C55.2455 54.154 58.743 49.6004 60.7608 44.3488C62.7786 39.0972 63.23 33.3732 62.0604 27.8702C60.8909 22.3673 58.1504 17.3216 54.1712 13.3447V13.3447Z" fill="#BB976D"/>
                        <path d="M22.8322 29.2756L29.7565 31.1351C29.9473 31.1863 30.1483 31.1863 30.3391 31.1352C30.5299 31.0841 30.7039 30.9837 30.8436 30.844C30.9833 30.7043 31.0838 30.5303 31.1349 30.3394C31.186 30.1486 31.186 29.9477 31.1348 29.7568L29.2753 22.8315C29.1152 22.2369 28.8019 21.6948 28.3667 21.2593L11.3368 4.22982L11.3359 4.22845L11.3345 4.22755L8.13439 1.02749C7.47535 0.369542 6.58216 0 5.6509 0C4.71965 0 3.82645 0.369542 3.16741 1.02749L1.02717 3.16714H1.02662C0.369089 3.82655 -0.000103318 4.71981 2.16884e-08 5.65103C0.000103362 6.58226 0.369494 7.47544 1.02717 8.13471L21.2595 28.3665C21.6951 28.802 22.2375 29.1155 22.8322 29.2756ZM26.4488 22.5231C26.4147 22.5431 26.3818 22.565 26.3501 22.5886L22.5889 26.3499C22.5652 26.3815 22.5433 26.4145 22.5233 26.4486L6.61474 10.5404L10.5402 6.61488L26.4488 22.5231ZM28.4557 28.456L24.6789 27.4416L27.4415 24.679L28.4557 28.456ZM2.61858 4.75799L4.75822 2.61835C4.99515 2.38196 5.31618 2.2492 5.65087 2.2492C5.98557 2.2492 6.30659 2.38196 6.54353 2.61835L8.94927 5.02409L5.02381 8.94964L2.61804 6.54385C2.38159 6.3068 2.24885 5.98562 2.24895 5.65081C2.24905 5.31599 2.38199 4.9949 2.61858 4.75799Z" fill="#BB976D"/>
                        <path d="M52.0664 36.4375H21.3457C21.0474 36.4375 20.7612 36.556 20.5502 36.767C20.3392 36.978 20.2207 37.2642 20.2207 37.5625C20.2207 37.8609 20.3392 38.1471 20.5502 38.358C20.7612 38.569 21.0474 38.6876 21.3457 38.6876H52.0664C52.3648 38.6876 52.6509 38.569 52.8619 38.358C53.0729 38.1471 53.1914 37.8609 53.1914 37.5625C53.1914 37.2642 53.0729 36.978 52.8619 36.767C52.6509 36.556 52.3648 36.4375 52.0664 36.4375Z" fill="#BB976D"/>
                        <path d="M52.0665 43.9521H30.0489C29.7505 43.9521 29.4643 44.0707 29.2533 44.2817C29.0424 44.4926 28.9238 44.7788 28.9238 45.0772C28.9238 45.3755 29.0424 45.6617 29.2533 45.8727C29.4643 46.0837 29.7505 46.2022 30.0489 46.2022H52.0665C52.3649 46.2022 52.651 46.0837 52.862 45.8727C53.073 45.6617 53.1915 45.3755 53.1915 45.0772C53.1915 44.7788 53.073 44.4926 52.862 44.2817C52.651 44.0707 52.3649 43.9521 52.0665 43.9521Z" fill="#BB976D"/>
                        <path d="M22.1914 45.0766C22.1914 45.3128 22.2614 45.5436 22.3926 45.7399C22.5238 45.9363 22.7102 46.0893 22.9284 46.1797C23.1465 46.27 23.3866 46.2937 23.6182 46.2476C23.8498 46.2015 24.0625 46.0878 24.2295 45.9209C24.3964 45.7539 24.5101 45.5412 24.5562 45.3096C24.6023 45.078 24.5786 44.8379 24.4883 44.6198C24.3979 44.4016 24.2449 44.2152 24.0485 44.084C23.8522 43.9528 23.6214 43.8828 23.3852 43.8828C23.0686 43.8828 22.765 44.0086 22.5411 44.2325C22.3172 44.4564 22.1914 44.76 22.1914 45.0766Z" fill="#BB976D"/>
                    </svg>                                                  
                </div>
                <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Latest Blog</h3>
                <p class="mt-3">Stay informed and inspired with our latest blog posts. Explore insightful content that keeps you ahead of trends and informed on topics you love. </p>
            </div>
            <!-- Blog Wrapper -->
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-5 md:gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                
                <!-- includes/Home/blog.blade.php -->
                @include('includes.Home.blog')

            </div>
        </div>
    </div>
</div> --}}
<!-- Blog End -->


<!-- Flash Deal Start -->
@if($flashDeal->is_active)
<section id="fd-section" style="background:{{ $flashDeal->bg_color }};" class="relative overflow-hidden">

    {{-- Gold gradient top border --}}
    <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,#bb976d 30%,#e4c28a 50%,#bb976d 70%,transparent);"></div>

    {{-- Ambient glows --}}
    <div style="position:absolute;top:-120px;left:-80px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(187,151,109,.08) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-100px;right:-60px;width:560px;height:560px;border-radius:50%;background:radial-gradient(circle,rgba(187,151,109,.06) 0%,transparent 70%);pointer-events:none;"></div>

    {{-- Fine grid texture --}}
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:48px 48px;pointer-events:none;"></div>

    <div style="max-width:1440px;margin:0 auto;padding:0 40px;position:relative;z-index:10;">
        <div style="padding:56px 0;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:40px;">

            {{-- LEFT: text --}}
            <div>
                {{-- Badge --}}
                <div style="display:inline-flex;align-items:center;gap:7px;
                            border:1px solid rgba(187,151,109,.45);
                            padding:5px 14px 5px 10px;margin-bottom:20px;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#bb976d;display:inline-block;
                                 box-shadow:0 0 0 3px rgba(187,151,109,.25);animation:fd-pulse 2s infinite;"></span>
                    <span style="font-family:'Poppins',sans-serif;font-size:9.5px;font-weight:700;letter-spacing:.18em;
                                 text-transform:uppercase;color:#bb976d;">{{ $flashDeal->badge_text }}</span>
                </div>

                {{-- Discount --}}
                <div style="font-family:'Poppins',sans-serif;font-size:clamp(2.6rem,4.5vw,4.2rem);font-weight:800;
                            line-height:1;letter-spacing:-.02em;color:#fff;margin-bottom:10px;">
                    {{ $flashDeal->discount_label }}
                </div>

                {{-- Title --}}
                <div style="font-family:'Poppins',sans-serif;font-size:clamp(1rem,1.6vw,1.25rem);font-weight:500;
                            color:rgba(255,255,255,.75);letter-spacing:.01em;margin-bottom:8px;">
                    {{ $flashDeal->title }}
                </div>

                @if($flashDeal->subtitle)
                <p style="font-family:'Poppins',sans-serif;font-size:13px;color:rgba(255,255,255,.4);
                          line-height:1.6;max-width:380px;margin:0;">{{ $flashDeal->subtitle }}</p>
                @endif

                {{-- Gold rule --}}
                <div style="width:52px;height:1.5px;background:linear-gradient(90deg,#bb976d,transparent);margin-top:20px;"></div>
            </div>

            {{-- CENTER: countdown --}}
            @if($flashDeal->ends_at && $flashDeal->ends_at->isFuture())
            @php $labels = ['D'=>'Days','H'=>'Hrs','M'=>'Min','S'=>'Sec']; @endphp
            <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                <span style="font-family:'Poppins',sans-serif;font-size:9px;font-weight:700;letter-spacing:.2em;
                             text-transform:uppercase;color:rgba(255,255,255,.35);">Offer Ends In</span>

                <div id="fd-countdown" data-ends="{{ $flashDeal->ends_at->timestamp }}"
                     style="display:flex;align-items:flex-start;gap:6px;">
                    @foreach(['D','H','M','S'] as $unit)
                    <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                        <div class="fd-unit" data-unit="{{ strtolower($unit) }}"
                             style="min-width:70px;height:72px;
                                    display:flex;align-items:center;justify-content:center;
                                    background:rgba(255,255,255,.06);
                                    border:1px solid rgba(187,151,109,.2);
                                    font-family:'Poppins',sans-serif;
                                    font-size:2rem;font-weight:800;color:#fff;
                                    letter-spacing:-.02em;position:relative;overflow:hidden;">
                            <span style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,.04) 0%,transparent 60%);pointer-events:none;"></span>
                            <span class="fd-val" style="position:relative;z-index:1;">00</span>
                        </div>
                        <span style="font-family:'Poppins',sans-serif;font-size:8.5px;font-weight:600;
                                     letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.35);">
                            {{ $labels[$unit] }}
                        </span>
                    </div>
                    @if($unit !== 'S')
                    <span style="font-family:'Poppins',sans-serif;font-size:1.8rem;font-weight:300;
                                 color:rgba(187,151,109,.5);margin-top:16px;line-height:1;">:</span>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- RIGHT: CTA --}}
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:16px;">

                <a href="{{ $flashDeal->cta_url }}"
                   id="fd-cta"
                   style="display:inline-flex;align-items:center;gap:12px;
                          background:linear-gradient(135deg,#bb976d 0%,#d4aa80 50%,#bb976d 100%);
                          background-size:200% auto;
                          color:#fff;padding:16px 36px;
                          font-family:'Poppins',sans-serif;
                          font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
                          text-decoration:none;
                          box-shadow:0 8px 32px rgba(187,151,109,.3);
                          transition:background-position .4s ease,box-shadow .3s,transform .2s;">
                    {{ $flashDeal->cta_text }}
                    <svg width="14" height="10" viewBox="0 0 16 10" fill="none">
                        <path d="M1 5H15M15 5L11 1M15 5L11 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div style="display:flex;align-items:center;gap:8px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.6" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span style="font-family:'Poppins',sans-serif;font-size:10.5px;color:rgba(255,255,255,.3);letter-spacing:.04em;">Secure checkout · Free returns</span>
                </div>

            </div>

        </div>
    </div>

    {{-- Gold bottom border --}}
    <div style="position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(187,151,109,.3) 50%,transparent);"></div>
</section>

<style>
@keyframes fd-pulse {
    0%,100% { box-shadow: 0 0 0 3px rgba(187,151,109,.25); }
    50%      { box-shadow: 0 0 0 6px rgba(187,151,109,.08); }
}
#fd-cta:hover {
    background-position: right center !important;
    box-shadow: 0 12px 40px rgba(187,151,109,.45) !important;
    transform: translateY(-2px);
}
@media (max-width: 900px) {
    #fd-section > div > div {
        grid-template-columns: 1fr !important;
        text-align: center;
        padding: 40px 0 !important;
    }
    #fd-section > div > div > div:first-child { align-items: center; display: flex; flex-direction: column; }
    #fd-section > div > div > div:last-child { align-items: center !important; }
}
</style>

<script>
(function () {
    var el = document.getElementById('fd-countdown');
    if (!el) return;
    var ends = parseInt(el.getAttribute('data-ends'), 10) * 1000;
    function pad(n) { return n < 10 ? '0' + n : '' + n; }
    function tick() {
        var diff = Math.max(0, ends - Date.now());
        el.querySelector('[data-unit="d"] .fd-val').textContent = pad(Math.floor(diff / 86400000));
        el.querySelector('[data-unit="h"] .fd-val').textContent = pad(Math.floor((diff % 86400000) / 3600000));
        el.querySelector('[data-unit="m"] .fd-val').textContent = pad(Math.floor((diff % 3600000) / 60000));
        el.querySelector('[data-unit="s"] .fd-val').textContent = pad(Math.floor((diff % 60000) / 1000));
        if (diff > 0) setTimeout(tick, 1000);
    }
    tick();
}());
</script>
@endif
<!-- Flash Deal End -->

<!-- Customer Reviews Start -->
<div class="s-py-50-100 bg-[#F8F6F3] dark:bg-title">
    <div class="container-fluid">
        <!-- Section Title -->
        <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
            <span class="text-xs uppercase tracking-widest text-primary font-semibold">What our customers say</span>
            <h2 class="leading-tight mt-2 text-2xl md:text-3xl font-bold text-title dark:text-white">Customer Reviews</h2>
            <p class="mt-3 text-paragraph dark:text-white-light">Real stories from real shoppers. See why thousands of customers love shopping with PeytonGhalib.</p>
        </div>

        <!-- Reviews Slider -->
        <div class="max-w-[1720px] mx-auto relative group" data-aos="fade-up" data-aos-delay="100">
            <div class="owl-carousel reviews-slider"
                 data-carousel-items="3"
                 data-carousel-xl="3"
                 data-carousel-lg="2"
                 data-carousel-md="2"
                 data-carousel-sm="1"
                 data-carousel-xs="1"
                 data-carousel-margin="24"
                 data-carousel-loop="true"
                 data-carousel-autoplay="true"
                 data-carousel-dots="true">

                @php
                $reviews = [
                    ['name' => 'Sarah Mitchell',   'rating' => 5, 'product' => 'Nordic Oak Dining Table', 'date' => 'May 2026',   'review' => 'Our new dining table is absolutely stunning. The solid oak finish looks even better in person — rich, warm, and exactly as pictured. Assembly was straightforward and delivery arrived ahead of schedule.'],
                    ['name' => 'James Reynolds',   'rating' => 5, 'product' => 'Velvet Sofa Set',          'date' => 'April 2026', 'review' => 'The velvet sofa arrived beautifully packaged and looks incredible in our living room. Upholstery quality is premium and the frame feels very sturdy. The whole process was seamless from checkout to delivery.'],
                    ['name' => 'Aisha Karimi',     'rating' => 4, 'product' => 'Ceramic Vase Collection',  'date' => 'May 2026',   'review' => 'Ordered the ceramic vase collection as a housewarming gift and the recipient was thrilled. The craftsmanship is beautiful and the packaging was gift-worthy. Delivery took a day longer than expected, but well worth it.'],
                    ['name' => 'Daniel Thompson',  'rating' => 5, 'product' => 'Scandinavian Wardrobe',    'date' => 'March 2026', 'review' => 'I\'ve been shopping here for months and every order has been perfect. The Scandinavian wardrobe is spacious, beautifully finished, and worth every penny. Fast shipping, incredible quality — 10/10!'],
                    ['name' => 'Priya Sharma',     'rating' => 5, 'product' => 'Abstract Wall Art Set',    'date' => 'April 2026', 'review' => 'The wall art set transformed our hallway completely. Colours are vibrant, frames are solid, and they arrived perfectly protected. Exactly what the photos showed — no surprises, just great quality.'],
                    ['name' => 'Michael Barnes',   'rating' => 4, 'product' => 'Bookshelf with Drawers',   'date' => 'May 2026',   'review' => 'Great bookshelf at a very fair price. The drawers glide smoothly and the shelves are deeper than expected — loads of storage. Took about 45 minutes to assemble with clear instructions. Very satisfied overall.'],
                    ['name' => 'Fatima Al-Rashid', 'rating' => 5, 'product' => 'Rattan Accent Chair',      'date' => 'May 2026',   'review' => 'This rattan chair is a showstopper — every guest asks where I got it! The natural woven finish is perfect, the cushion is comfortable, and it has held up beautifully. Exceptional experience from start to finish.'],
                    ['name' => 'Chris Lawrence',   'rating' => 5, 'product' => 'Marble Coffee Table',      'date' => 'April 2026', 'review' => 'The marble coffee table is even more impressive in person. The surface is smooth and cool to the touch, and the brushed gold legs are a perfect contrast. Fast delivery, exactly as described. Will definitely shop here again.'],
                ];
                @endphp

                @foreach($reviews as $review)
                <div class="bg-white dark:bg-[#1a1a2e] rounded-sm p-6 md:p-8 shadow-sm flex flex-col h-full border border-transparent hover:border-primary duration-300">

                    <!-- Stars -->
                    <div class="flex items-center gap-1 mb-4">
                        @for($s = 1; $s <= 5; $s++)
                            @if($s <= $review['rating'])
                                <svg class="w-4 h-4 text-[#F5A623] fill-current" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                            @else
                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                            @endif
                        @endfor
                        <span class="ml-1 text-xs text-gray-400 dark:text-white-light font-medium">{{ $review['rating'] }}.0</span>
                    </div>

                    <!-- Review Text -->
                    <p class="text-paragraph dark:text-white-light text-sm leading-relaxed flex-1">&ldquo;{{ $review['review'] }}&rdquo;</p>

                    <!-- Product Reference -->
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1.5 text-xs bg-[#F5F0EB] dark:bg-primary/10 text-primary px-2.5 py-1 rounded-full font-medium">
                            <svg class="w-3 h-3 fill-current flex-none" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3z"/></svg>
                            {{ $review['product'] }}
                        </span>
                    </div>

                    <!-- Divider -->
                    <div class="my-5 border-t border-[#E3E5E6] dark:border-bdr-clr-drk"></div>

                    <!-- Customer Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-none">
                            <span class="text-primary font-bold text-base leading-none">{{ strtoupper(substr($review['name'], 0, 1)) }}</span>
                        </div>
                        <div>
                            <h5 class="font-semibold text-sm text-title dark:text-white leading-none">{{ $review['name'] }}</h5>
                            <span class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 fill-current flex-none" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Verified Purchase &middot; {{ $review['date'] }}
                            </span>
                        </div>
                        <div class="ml-auto">
                            <svg class="w-7 h-7 text-primary/20 fill-current" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>
                    </div>

                </div>
                @endforeach

            </div>

            <!-- Slider Navigation -->
            <button class="icon reviews_prev w-9 h-9 md:w-12 md:h-12 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary hover:text-white transform p-2 absolute top-1/2 -translate-y-1/2 -left-4 md:-left-6 z-[999] shadow-md" aria-label="Previous Review">
                <svg class="fill-current" width="18" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z"/>
                </svg>
            </button>
            <button class="icon reviews_next w-9 h-9 md:w-12 md:h-12 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary hover:text-white transform p-2 absolute top-1/2 -translate-y-1/2 -right-4 md:-right-6 z-[999] shadow-md" aria-label="Next Review">
                <svg class="fill-current" width="18" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z"/>
                </svg>
            </button>
        </div>

    </div>
</div>
<!-- Customer Reviews End -->

<!-- Internal Linking — Explore Our Range Start -->
<section class="bg-white dark:bg-title border-t border-[#E3E5E6] dark:border-white/10">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto py-10 md:py-14">

            <div class="flex items-end justify-between gap-4 mb-8" data-aos="fade-up">
                <div>
                    <span class="text-xs uppercase tracking-widest text-primary font-semibold">Everything in one place</span>
                    <h2 class="leading-tight mt-1 text-xl md:text-2xl font-bold text-title dark:text-white">Explore Our Full Range</h2>
                </div>
                <a href="{{ url('/shop') }}"
                   class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline whitespace-nowrap">
                    Shop All Products
                    <svg width="12" height="8" viewBox="0 0 24 14" fill="none" class="fill-current"><path d="M23.82 6.62L18.38 1.18C18.18 0.95 17.84 0.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46H0.57C0.27 6.46 0.02 6.71 0.02 7.01C0.02 7.31 0.27 7.55 0.57 7.55H22.12L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z"/></svg>
                </a>
            </div>

            {{-- All DB categories as keyword-rich text links --}}
            @if($categories->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-10" data-aos="fade-up" data-aos-delay="50">
                @php $verbs = ['Shop','Browse','Explore','Discover','Shop','Browse','Explore','Discover','Shop','Browse','Explore','Discover']; @endphp
                @foreach($categories as $idx => $category)
                <a href="{{ route('category.landing', $category->slug) }}"
                   class="flex items-center justify-between py-3 border-b border-[#F0EDE8] dark:border-white/5 group">
                    <span class="text-sm font-medium text-paragraph dark:text-white-light group-hover:text-primary transition-colors duration-200">
                        {{ $verbs[$idx % 12] }} {{ $category->name }} Online
                    </span>
                    <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-primary flex-none transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Curated room & style keyword pill links --}}
            <div class="mt-8 pt-6 border-t border-[#F0EDE8] dark:border-white/5" data-aos="fade-up" data-aos-delay="100">
                <p class="text-xs uppercase tracking-widest text-gray-400 font-semibold mb-3">Popular searches</p>
                <div class="flex flex-wrap gap-2">
                    @php
                    $popularLinks = [
                        'Shop Living Room Furniture',
                        'Browse Bedroom Furniture Sets',
                        'Explore Dining Tables &amp; Chairs',
                        'View Sofas &amp; Armchairs',
                        'Shop Ceramic Home Decor',
                        'Browse Wall Art &amp; Mirrors',
                        'View New Arrivals',
                        'Shop Best Selling Products',
                        'Browse Home Accessories',
                        'Explore Storage &amp; Shelving',
                    ];
                    @endphp
                    @foreach($popularLinks as $label)
                    <a href="{{ url('/shop') }}"
                       class="text-xs px-3 py-1.5 border border-[#E3E5E6] dark:border-white/15
                              text-paragraph dark:text-white-light
                              hover:border-primary hover:text-primary
                              transition-all duration-200 rounded-full whitespace-nowrap">
                        {!! $label !!}
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Internal Linking — Explore Our Range End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer')

@push('scripts')
<script>
    $(document).ready(function () {
        var reviewsSlider = $('.reviews-slider');
        $('.reviews_next').on('click', function () {
            reviewsSlider.trigger('next.owl.carousel');
        });
        $('.reviews_prev').on('click', function () {
            reviewsSlider.trigger('prev.owl.carousel', [300]);
        });
    });
</script>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    var $cat = $('.hv1-pdct-ctgry-slider');
    if (!$cat.length) return;

    // Destroy the generic init so we can reinitialize with rewind
    $cat.trigger('destroy.owl.carousel').removeClass('owl-loaded owl-drag');
    $cat.find('.owl-stage-outer').children().unwrap();

    $cat.owlCarousel({
        items       : 4,
        margin      : 0,
        loop        : false,
        rewind      : true,
        autoplay    : false,
        smartSpeed  : 500,
        mouseDrag   : true,
        touchDrag   : true,
        responsive  : {
            0   : { items: 1 },
            576 : { items: 2 },
            768 : { items: 4 },
            1024: { items: 4 },
        }
    });

    // Wire nav buttons
    $('.hv1pdct_next').off('click').on('click', function () {
        $cat.trigger('next.owl.carousel');
    });
    $('.hv1pdct_prev').off('click').on('click', function () {
        $cat.trigger('prev.owl.carousel');
    });
});
</script>
@endpush

@endsection

@push('scripts')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "PeytonGhalib",
  "url": "https://peytonghalib.com",
  "logo": "https://peytonghalib.com/assets/img/logo.svg",
  "email": "support@@peytonghalib.com",
  "description": "PeytonGhalib — Your one-stop online destination for quality furniture, home decor, ceramics, and more at unbeatable prices with fast delivery.",
  "sameAs": [
    "https://www.facebook.com/peytonghalib",
    "https://twitter.com/peytonghalib",
    "https://www.instagram.com/peytonghalib",
    "https://www.linkedin.com/company/peytonghalib"
  ]
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebSite",
  "name": "PeytonGhalib",
  "url": "https://peytonghalib.com",
  "potentialAction": {
    "@@type": "SearchAction",
    "target": "https://peytonghalib.com/shop?search={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "LocalBusiness",
  "name": "PeytonGhalib",
  "url": "https://peytonghalib.com",
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "4.9",
    "reviewCount": "2134",
    "bestRating": "5",
    "worstRating": "1"
  },
  "review": [
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Sarah Mitchell"},
      "datePublished": "2026-05-10",
      "name": "Stunning Nordic Oak Dining Table",
      "reviewBody": "Our new dining table is absolutely stunning. The solid oak finish looks even better in person — rich, warm, and exactly as pictured. Assembly was straightforward and delivery arrived ahead of schedule.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "James Reynolds"},
      "datePublished": "2026-04-22",
      "name": "Premium quality Velvet Sofa Set",
      "reviewBody": "The velvet sofa arrived beautifully packaged and looks incredible in our living room. Upholstery quality is premium and the frame feels very sturdy. The whole process was seamless from checkout to delivery.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Aisha Karimi"},
      "datePublished": "2026-05-03",
      "name": "Beautiful Ceramic Vase Collection — perfect gift",
      "reviewBody": "Ordered the ceramic vase collection as a housewarming gift and the recipient was thrilled. The craftsmanship is beautiful and the packaging was gift-worthy. Delivery took a day longer than expected, but well worth it.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "4", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Daniel Thompson"},
      "datePublished": "2026-03-15",
      "name": "Spacious and beautifully finished Scandinavian Wardrobe",
      "reviewBody": "I've been shopping here for months and every order has been perfect. The Scandinavian wardrobe is spacious, beautifully finished, and worth every penny. Fast shipping, incredible quality.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Priya Sharma"},
      "datePublished": "2026-04-18",
      "name": "Abstract Wall Art Set transformed our hallway",
      "reviewBody": "The wall art set transformed our hallway completely. Colours are vibrant, frames are solid, and they arrived perfectly protected. Exactly what the photos showed — no surprises, just great quality.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Michael Barnes"},
      "datePublished": "2026-05-07",
      "name": "Great Bookshelf with Drawers at a fair price",
      "reviewBody": "Great bookshelf at a very fair price. The drawers glide smoothly and the shelves are deeper than expected — loads of storage. Took about 45 minutes to assemble with clear instructions. Very satisfied overall.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "4", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Fatima Al-Rashid"},
      "datePublished": "2026-05-14",
      "name": "Rattan Accent Chair — a showstopper",
      "reviewBody": "This rattan chair is a showstopper — every guest asks where I got it! The natural woven finish is perfect, the cushion is comfortable, and it has held up beautifully. Exceptional experience from start to finish.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Chris Lawrence"},
      "datePublished": "2026-04-29",
      "name": "Impressive Marble Coffee Table — worth every penny",
      "reviewBody": "The marble coffee table is even more impressive in person. The surface is smooth and cool to the touch, and the brushed gold legs are a perfect contrast. Fast delivery, exactly as described.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    }
  ]
}
</script>
@endpush
