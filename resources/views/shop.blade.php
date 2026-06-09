<!-- resources/views/shop.blade.php -->
@extends('layouts.main')

@section('title', 'Shop | PeytonGhalib')
@section('meta_description', 'Browse our full collection of furniture, home decor, ceramics and lifestyle products. Filter by category and find exactly what you need at PeytonGhalib.')

@push('schema')
@php
    $shopCategoryObj = $activeCategory
        ? $categories->firstWhere('slug', $activeCategory)
        : null;
    $shopPageName = $shopCategoryObj
        ? $shopCategoryObj->name . ' - PeytonGhalib'
        : 'Shop All Products - PeytonGhalib';
    $shopPageDesc = $shopCategoryObj
        ? 'Browse our ' . $shopCategoryObj->name . ' collection at PeytonGhalib.'
        : 'Browse all furniture, home decor, and lifestyle products at PeytonGhalib.';
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "{{ addslashes($shopPageName) }}",
    "description": "{{ addslashes($shopPageDesc) }}",
    "url": "{{ url()->current() }}",
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
            { "@type": "ListItem", "position": 2, "name": "Shop", "item": "{{ url('/shop') }}" }@if($shopCategoryObj),
            { "@type": "ListItem", "position": 3, "name": "{{ addslashes($shopCategoryObj->name) }}", "item": "{{ url()->current() }}" }@endif
        ]
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "{{ addslashes($shopPageName) }}",
    "itemListElement": [
        @foreach($products as $index => $p)
        @php
            $pImg = !empty($p->image)
                ? (str_starts_with($p->image, 'assets/') ? asset($p->image) : \Storage::url($p->image))
                : asset('assets/img/logo.svg');
        @endphp
        {
            "@type": "ListItem",
            "position": {{ $products->firstItem() + $index }},
            "name": "{{ addslashes($p->name) }}",
            "url": "{{ route('product-details', $p->slug) }}",
            "image": "{{ $pImg }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

@push('styles')
<style>
/* ── Category Filter Bar ─────────────────────────────────────── */
.pgf-bar {
    padding: 36px 0 0;
}
.pgf-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}
.pgf-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #bb976d;
}
.pgf-divider {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, #e8ddd1 0%, transparent 100%);
}
.pgf-title {
    font-size: 22px;
    font-weight: 500;
    color: #1a1a1a;
    margin: 0;
    line-height: 1;
}
.pgf-count {
    font-size: 12px;
    color: #9a9a9a;
    margin-left: 4px;
    font-weight: 400;
}

/* Scroll strip */
.pgf-scroll-wrap {
    position: relative;
}
.pgf-scroll-wrap::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 60px;
    background: linear-gradient(90deg, transparent, #fff);
    pointer-events: none;
    z-index: 2;
}
.pgf-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding-bottom: 4px;
}
@media(max-width: 767px) {
    .pgf-pills {
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-right: 60px;
    }
    .pgf-pills::-webkit-scrollbar { display: none; }
}

/* Pill base */
.pgf-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 100px;
    font-size: 13px;
    font-weight: 500;
    line-height: 1;
    white-space: nowrap;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1.5px solid #e5ddd4;
    background: #fff;
    color: #4a4a4a;
    cursor: pointer;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}
.pgf-pill::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #bb976d, #d4a96a);
    opacity: 0;
    transition: opacity 0.2s ease;
    border-radius: inherit;
}
.pgf-pill span { position: relative; z-index: 1; }
.pgf-pill svg { position: relative; z-index: 1; flex-shrink: 0; }

.pgf-pill:hover {
    border-color: #bb976d;
    color: #bb976d;
    box-shadow: 0 2px 12px rgba(187,151,109,.18);
    transform: translateY(-1px);
}
.pgf-pill.pgf-active {
    border-color: transparent;
    color: #fff;
    background: linear-gradient(135deg, #bb976d, #c9a87c);
    box-shadow: 0 4px 16px rgba(187,151,109,.35);
}
.pgf-pill.pgf-active::before { opacity: 0; }
.pgf-pill.pgf-active:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(187,151,109,.4); }

/* All pill special */
.pgf-pill-all {
    padding-left: 14px;
    padding-right: 18px;
    font-weight: 600;
}
.pgf-pill-all svg { margin-right: 1px; }

/* Bottom border */
.pgf-bottom {
    margin-top: 28px;
    height: 1px;
    background: linear-gradient(90deg, #e8ddd1, transparent 80%);
}
</style>
@endpush

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Shop</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Shop</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Shop Start -->
<div class="s-py-100">
    <div class="container-fluid">
        <!-- Category Filter Bar -->
        <div class="pgf-bar max-w-[1720px] mx-auto">

            <div class="pgf-header">
                <span class="pgf-label">Collections</span>
                <div class="pgf-divider"></div>
                <h4 class="pgf-title">
                    Choose Category
                    <span class="pgf-count">{{ $categories->count() + 1 }} options</span>
                </h4>
            </div>

            <div class="pgf-scroll-wrap">
                <div class="pgf-pills">

                    {{-- All --}}
                    <a class="pgf-pill pgf-pill-all {{ !$activeCategory ? 'pgf-active' : '' }}"
                       href="{{ url('/shop') }}">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" style="opacity:.85">
                            <path d="M3 3h7v7H3zm11 0h7v7h-7zM3 14h7v7H3zm11 3h2v-2h2v2h2v2h-2v2h-2v-2h-2v-2z"/>
                        </svg>
                        <span>All</span>
                    </a>

                    @foreach ($categories as $cat)
                    <a class="pgf-pill {{ $activeCategory === $cat->slug ? 'pgf-active' : '' }}"
                       href="{{ url('/shop') }}?category={{ $cat->slug }}">
                        <span>{{ $cat->name }}</span>
                    </a>
                    @endforeach

                </div>
            </div>

            <div class="pgf-bottom"></div>
        </div>

        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-10 md:pt-14" data-aos="fade-up" data-aos-delay="200">

            <!-- includes/Shop/shops-v1.blade.php -->
            @include('includes.Shop.shops-v1')

        </div>

        @if ($products->hasPages())
        <div class="flex items-center justify-center gap-2 mt-8 md:mt-12 flex-wrap">
            {{-- Previous --}}
            @if ($products->onFirstPage())
                <span class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk text-gray-300 cursor-not-allowed">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.18 7.39L5.62 12.83C5.82 13.06 6.16 13.09 6.39 12.89C6.62 12.70 6.65 12.35 6.45 12.12L1.88 7.55H23.43C23.73 7.55 23.98 7.30 23.98 7.00C23.98 6.70 23.73 6.46 23.43 6.46H1.88L6.39 1.94C6.62 1.75 6.65 1.40 6.45 1.18C6.26 0.95 5.91 0.92 5.68 1.12L0.18 6.62C-0.03 6.83 -0.03 7.17 0.18 7.39Z" fill="currentColor"/></svg>
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk hover:border-primary hover:text-primary duration-200">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.18 7.39L5.62 12.83C5.82 13.06 6.16 13.09 6.39 12.89C6.62 12.70 6.45 12.12L1.88 7.55H23.43C23.73 7.55 23.98 7.30 23.98 7.00C23.98 6.70 23.73 6.46 23.43 6.46H1.88L6.39 1.94C6.62 1.75 6.45 1.18C6.26 0.95 5.91 0.92 5.68 1.12L0.18 6.62C-0.03 6.83 -0.03 7.17 0.18 7.39Z" fill="currentColor"/></svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                @if ($page == $products->currentPage())
                    <span class="w-10 h-10 flex items-center justify-center bg-primary text-white font-semibold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk hover:border-primary hover:text-primary duration-200">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk hover:border-primary hover:text-primary duration-200">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.82 6.62L18.38 1.18C18.18 0.95 17.84 0.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46H0.57C0.27 6.46 0.02 6.71 0.02 7.01C0.02 7.31 0.27 7.55 0.57 7.55H22.12L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z" fill="currentColor"/></svg>
                </a>
            @else
                <span class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk text-gray-300 cursor-not-allowed">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.82 6.62L18.38 1.18C18.18 0.95 17.84 0.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46H0.57C0.27 6.46 0.02 6.71 0.02 7.01C0.02 7.31 0.27 7.55 0.57 7.55H22.12L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z" fill="currentColor"/></svg>
                </span>
            @endif
        </div>
        <p class="text-center text-sm text-gray-400 dark:text-white-light mt-3">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
        </p>
        @endif

    </div>
</div>
<!-- Shop End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer')

@endsection
