{{-- resources/views/shop.blade.php --}}
@extends('layouts.main')

@section('title', 'Shop | PeytonGhalib')
@section('meta_description', 'Browse our full collection of furniture, home decor, ceramics and lifestyle products. Filter by category, price, colour and size at PeytonGhalib.')

@php
    // Canonical: a single selected category maps to its landing page; anything else
    // (multi-select, price/colour/size facets) is a filtered view of /shop.
    $soleCategory = count($filters['categories']) === 1 ? $filters['categories'][0] : null;

    if ($soleCategory) {
        $shopCanonical = url('/category/' . $soleCategory);
    } elseif (request()->get('page', 1) > 1) {
        $shopCanonical = url('/shop') . '?page=' . request()->get('page');
    } else {
        $shopCanonical = url('/shop');
    }
@endphp
@section('canonical', $shopCanonical)

@push('schema')
@php
    $shopCategoryObj = $soleCategory ? $categories->firstWhere('slug', $soleCategory) : null;
    $shopPageName    = $shopCategoryObj ? $shopCategoryObj->name . ' - PeytonGhalib' : 'Shop All Products - PeytonGhalib';
    $shopPageDesc    = $shopCategoryObj
        ? 'Browse our ' . $shopCategoryObj->name . ' collection at PeytonGhalib.'
        : 'Browse all furniture, home decor, and lifestyle products at PeytonGhalib.';

    $breadcrumbItems = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Shop', 'item' => url('/shop')],
    ];
    if ($shopCategoryObj) {
        $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $shopCategoryObj->name, 'item' => url()->current()];
    }

    $listItems = [];
    foreach ($products as $index => $p) {
        $pImg = !empty($p->image)
            ? (str_starts_with($p->image, 'assets/') ? asset($p->image) : \Storage::url($p->image))
            : asset('assets/img/logo.svg');
        $listItems[] = [
            '@type'    => 'ListItem',
            'position' => $products->firstItem() + $index,
            'name'     => $p->name,
            'url'      => route('product-details', $p->slug),
            'image'    => $pImg,
        ];
    }

    $schemaCollection = [
        '@context'   => 'https://schema.org',
        '@type'      => 'CollectionPage',
        'name'       => $shopPageName,
        'description'=> $shopPageDesc,
        'url'        => url()->current(),
        'breadcrumb' => ['@type' => 'BreadcrumbList', 'itemListElement' => $breadcrumbItems],
    ];

    $schemaItemList = [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => $shopPageName,
        'itemListElement' => $listItems,
    ];
@endphp
<script type="application/ld+json">{!! json_encode($schemaCollection, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($schemaItemList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@push('styles')
{{-- Scoped plain CSS. assets/css/style.css loads after the Vite build and its base
     utilities silently override Vite-only responsive variants, so the sidebar layout
     is kept out of Tailwind's way entirely. --}}
<style>
    .pg-shop { display: flex; flex-direction: column; gap: 28px; }
    @media (min-width: 1025px) {
        .pg-shop { flex-direction: row; align-items: flex-start; gap: 36px; }
        .pg-shop__aside { width: 280px; flex: 0 0 280px; position: sticky; top: 24px; }
        .pg-shop__main  { flex: 1 1 auto; min-width: 0; }
    }

    .pg-fbox { border: 1px solid #E8E1D7; border-radius: 16px; background: #fff; }
    .dark .pg-fbox { background: #172430; border-color: #2F3B45; }
    .pg-fbox__head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 20px; border-bottom: 1px solid #E8E1D7;
    }
    .dark .pg-fbox__head { border-color: #2F3B45; }
    .pg-fbox__title { font-size: 12px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #172430; margin: 0; }
    .dark .pg-fbox__title { color: #fff; }
    .pg-fbox__sub { font-size: 12px; color: #6B6560; margin: 3px 0 0; }
    .dark .pg-fbox__sub { color: #DBDBDB; }
    .pg-fbox__clear { font-size: 13px; font-weight: 600; color: #8A6A3F; text-decoration: none; }
    .dark .pg-fbox__clear { color: #BB976D; }
    .pg-fbox__clear:hover { text-decoration: underline; }

    /* Native <details> accordion: keyboard + screen-reader support with no JS */
    .pg-facet { border-bottom: 1px solid #E8E1D7; }
    .dark .pg-facet { border-color: #2F3B45; }
    .pg-facet:last-of-type { border-bottom: 0; }
    .pg-facet > summary {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; cursor: pointer; list-style: none;
        font-size: 15px; font-weight: 600; color: #172430;
    }
    .dark .pg-facet > summary { color: #fff; }
    .pg-facet > summary::-webkit-details-marker { display: none; }
    .pg-facet > summary:focus-visible { outline: 2px solid #BB976D; outline-offset: -2px; }
    .pg-facet__chev { transition: transform .2s ease; flex: 0 0 auto; color: #6B6560; }
    .pg-facet[open] .pg-facet__chev { transform: rotate(180deg); }
    .pg-facet__body { padding: 0 20px 18px; }

    .pg-opt { display: flex; align-items: center; gap: 10px; padding: 7px 0; cursor: pointer; }
    .pg-opt input { width: 17px; height: 17px; flex: 0 0 auto; accent-color: #BB976D; cursor: pointer; }
    .pg-opt input:focus-visible { outline: 2px solid #BB976D; outline-offset: 2px; }
    .pg-opt__label { flex: 1 1 auto; font-size: 14px; color: #3C474E; }
    .dark .pg-opt__label { color: #DBDBDB; }
    .pg-opt__count { flex: 0 0 auto; font-size: 12px; color: #6B6560; font-variant-numeric: tabular-nums; }
    .pg-opt--off .pg-opt__label, .pg-opt--off .pg-opt__count { opacity: .45; }
    .pg-opt__dot {
        width: 16px; height: 16px; border-radius: 50%; flex: 0 0 auto;
        border: 1px solid rgba(0,0,0,.18); box-shadow: inset 0 0 0 1px rgba(255,255,255,.35);
    }

    .pg-apply {
        width: 100%; min-height: 44px; margin-top: 4px; border: 0; border-radius: 8px;
        background: #BB976D; color: #172430; font-size: 14px; font-weight: 600;
        cursor: pointer; touch-action: manipulation;
    }
    .pg-apply:hover { opacity: .9; }
    .pg-apply:focus-visible { outline: 2px solid #172430; outline-offset: 2px; }

    /* Native select (opted out of niceSelect). appearance:none + our own chevron so it
       looks consistent across browsers without the plugin rewriting the markup. */
    .pg-native-select {
        appearance: none; -webkit-appearance: none; -moz-appearance: none;
        height: 44px; padding: 0 38px 0 14px;
        border: 1px solid #E8E1D7; border-radius: 8px;
        background-color: #fff; color: #172430;
        font-size: 14px; line-height: 44px;
        cursor: pointer; touch-action: manipulation;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B6560' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }
    .pg-native-select:hover { border-color: #BB976D; }
    .pg-native-select:focus-visible { outline: 2px solid #BB976D; outline-offset: 2px; }
    .dark .pg-native-select {
        background-color: #172430; color: #fff; border-color: #2F3B45;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23DBDBDB' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    }

    /* Mobile: the sidebar collapses behind a Filters disclosure */
    .pg-mobile-toggle { display: block; }
    @media (min-width: 1025px) {
        .pg-mobile-toggle { display: none; }
        .pg-shop__aside .pg-fbox { display: block !important; }
    }
</style>
@endpush

@section('content')

@include('includes.navbar')

@php
    /** Build a /shop URL preserving the other filters. */
    $shopUrl = function (array $overrides = []) {
        $params = array_merge(request()->except(['page']), $overrides);
        $params = array_filter($params, fn($v) => $v !== null && $v !== '' && $v !== []);
        return url('/shop') . ($params ? '?' . http_build_query($params) : '');
    };

    $hasFilters = $filters['categories'] || $filters['price'] || $filters['search'] !== '';

    // One removable chip per active filter value.
    $chips = [];
    if ($filters['search'] !== '') {
        $chips[] = ['label' => '“' . $filters['search'] . '”', 'url' => $shopUrl(['search' => null])];
    }
    foreach ($filters['categories'] as $slug) {
        $name = optional($categories->firstWhere('slug', $slug))->name ?? $slug;
        $chips[] = ['label' => $name, 'url' => $shopUrl(['category' => array_values(array_diff($filters['categories'], [$slug]))])];
    }
    foreach ($filters['price'] as $key) {
        $chips[] = ['label' => $priceBuckets[$key]['label'], 'url' => $shopUrl(['price' => array_values(array_diff($filters['price'], [$key]))])];
    }
@endphp

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h1 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Shop</h1>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}" class="hover:text-primary duration-200">Home</a></li>
            <li aria-hidden="true">/</li>
            <li class="text-primary">Shop</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<div class="s-py-100">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">

            {{-- One GET form wraps the sidebar and the sort control, so every facet composes --}}
            <form method="GET" action="{{ url('/shop') }}" id="pg-shop-form" class="pg-shop">

                {{-- ── Sidebar ────────────────────────────────────────── --}}
                <aside class="pg-shop__aside" aria-label="Product filters">

                    <button type="button" id="pg-filters-toggle" class="pg-apply pg-mobile-toggle"
                            aria-expanded="false" aria-controls="pg-filter-box">
                        Filters @if ($hasFilters) ({{ count($chips) }}) @endif
                    </button>

                    <div class="pg-fbox" id="pg-filter-box">
                        <div class="pg-fbox__head">
                            <div>
                                <p class="pg-fbox__title">Filters</p>
                                <p class="pg-fbox__sub">{{ $products->total() }} {{ \Str::plural('product', $products->total()) }}</p>
                            </div>
                            @if ($hasFilters)
                                <a href="{{ url('/shop') }}" class="pg-fbox__clear">Clear All</a>
                            @endif
                        </div>

                        {{-- Search --}}
                        <details class="pg-facet" open>
                            <summary>
                                Search
                                <svg class="pg-facet__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </summary>
                            <div class="pg-facet__body">
                                <label for="pg-search" class="sr-only">Search products</label>
                                <input type="search" id="pg-search" name="search" value="{{ $filters['search'] }}"
                                       placeholder="Search products…"
                                       class="w-full h-11 px-3.5 rounded-lg border border-gray-200 dark:border-bdr-clr-drk bg-white dark:bg-title text-sm text-title dark:text-white focus:outline-none focus:border-primary">
                            </div>
                        </details>

                        {{-- Category (multi-select) --}}
                        <details class="pg-facet" open>
                            <summary>
                                Category
                                <svg class="pg-facet__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </summary>
                            <div class="pg-facet__body">
                                @foreach ($categories as $cat)
                                    @php $n = $categoryCounts[$cat->slug] ?? 0; @endphp
                                    <label class="pg-opt {{ $n === 0 ? 'pg-opt--off' : '' }}">
                                        <input type="checkbox" name="category[]" value="{{ $cat->slug }}"
                                               @checked(in_array($cat->slug, $filters['categories'], true))>
                                        <span class="pg-opt__label">{{ $cat->name }}</span>
                                        <span class="pg-opt__count">({{ $n }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        {{-- Price buckets --}}
                        <details class="pg-facet" open>
                            <summary>
                                Price
                                <svg class="pg-facet__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </summary>
                            <div class="pg-facet__body">
                                @foreach ($priceBuckets as $key => $bucket)
                                    @php $n = $priceCounts[$key] ?? 0; @endphp
                                    <label class="pg-opt {{ $n === 0 ? 'pg-opt--off' : '' }}">
                                        <input type="checkbox" name="price[]" value="{{ $key }}"
                                               @checked(in_array($key, $filters['price'], true))>
                                        <span class="pg-opt__label">{{ $bucket['label'] }}</span>
                                        <span class="pg-opt__count">({{ $n }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        <div class="pg-facet__body" style="padding-top:16px;">
                            {{-- Kept for no-JS: with JS, changing any input auto-applies --}}
                            <button type="submit" class="pg-apply">Apply Filters</button>
                        </div>
                    </div>
                </aside>

                {{-- ── Main ───────────────────────────────────────────── --}}
                <div class="pg-shop__main">

                    {{-- Toolbar --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <p class="text-sm text-paragraph dark:text-white-light" role="status" aria-live="polite">
                            @if ($products->total() > 0)
                                Showing <span class="font-semibold text-title dark:text-white">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span>
                                of <span class="font-semibold text-title dark:text-white">{{ $products->total() }}</span>
                                {{ \Str::plural('product', $products->total()) }}
                            @else
                                No products found
                            @endif
                        </p>

                        <div class="flex items-center gap-2">
                            <label for="pg-sort" class="text-sm text-paragraph dark:text-white-light">Sort by</label>
                            {{-- pg-native-select keeps niceSelect's hands off it: the plugin
                                 mangles utility-styled selects and its jQuery-only change
                                 event never reaches the auto-submit listener. --}}
                            <select id="pg-sort" name="sort" class="pg-native-select">
                                @foreach ($sortOptions as $value => $label)
                                    <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Active filter chips --}}
                    @if ($chips)
                        <div class="flex flex-wrap items-center gap-2 pt-4">
                            @foreach ($chips as $chip)
                                <a href="{{ $chip['url'] }}"
                                   class="inline-flex items-center gap-1.5 min-h-[34px] pl-3 pr-2.5 rounded-full bg-snow dark:bg-white/10 text-sm text-title dark:text-white hover:text-primary transition-colors duration-200 cursor-pointer">
                                    {{ $chip['label'] }}
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                    <span class="sr-only">Remove this filter</span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Grid / empty state --}}
                    @if ($products->isEmpty())
                        <div class="text-center py-[80px] sm:py-[112px]">
                            <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-snow dark:bg-white/10">
                                <svg class="w-7 h-7 text-title dark:text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                </svg>
                            </span>
                            <h2 class="mt-6 text-xl font-bold text-title dark:text-white">No products match your filters</h2>
                            <p class="mt-2 text-sm text-paragraph dark:text-white-light max-w-md mx-auto leading-relaxed">
                                @if ($hasFilters)
                                    Try removing a filter or widening your price range.
                                @else
                                    There's nothing in the catalogue yet. Please check back soon.
                                @endif
                            </p>
                            @if ($hasFilters)
                                <a href="{{ url('/shop') }}" class="mt-7 inline-flex items-center justify-center min-h-[48px] px-8 rounded-lg bg-primary text-title text-sm font-semibold hover:opacity-90 cursor-pointer">
                                    Clear All Filters
                                </a>
                            @endif
                        </div>
                    @else
                        {{-- Arbitrary padding values: a base pt-* from style.css (loads last)
                             would override the md: variant and the bump would never apply. --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-[24px] md:pt-[32px]">
                            @include('includes.Shop.shops-v1')
                        </div>
                    @endif

                    {{-- Pagination --}}
                    @if ($products->hasPages())
                        <nav aria-label="Product pagination" class="flex items-center justify-center gap-2 mt-10 md:mt-14 flex-wrap">
                            @php
                                $pageBase = 'w-11 h-11 inline-flex items-center justify-center rounded-lg text-sm transition-colors duration-200 touch-manipulation';
                                $pageIdle = $pageBase . ' border border-gray-200 dark:border-bdr-clr-drk text-title dark:text-white hover:border-primary hover:text-primary cursor-pointer';
                                $pageOff  = $pageBase . ' border border-gray-100 dark:border-bdr-clr-drk text-paragraph dark:text-white-light opacity-40 cursor-not-allowed';
                            @endphp

                            @if ($products->onFirstPage())
                                <span class="{{ $pageOff }}" aria-disabled="true">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                                    <span class="sr-only">Previous page</span>
                                </span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" rel="prev" class="{{ $pageIdle }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                                    <span class="sr-only">Previous page</span>
                                </a>
                            @endif

                            @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span class="{{ $pageBase }} bg-primary text-title font-bold" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="{{ $pageIdle }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" rel="next" class="{{ $pageIdle }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                    <span class="sr-only">Next page</span>
                                </a>
                            @else
                                <span class="{{ $pageOff }}" aria-disabled="true">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                    <span class="sr-only">Next page</span>
                                </span>
                            @endif
                        </nav>
                    @endif

                </div>
            </form>

        </div>
    </div>
</div>

@include('includes.Home.popup')
@include('includes.footer')

@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('pg-shop-form');
    if (!form) return;

    // Auto-apply on change. The Apply button stays in the markup as the no-JS path.
    form.addEventListener('change', function (e) {
        if (e.target.matches('input[type="checkbox"], select')) form.submit();
    });

    // Mobile: collapse the filter panel behind a disclosure button.
    var toggle = document.getElementById('pg-filters-toggle');
    var box    = document.getElementById('pg-filter-box');
    if (!toggle || !box) return;

    var mq = window.matchMedia('(min-width: 1025px)');

    function sync() {
        if (mq.matches) {
            box.style.display = '';                 // desktop: always shown
            toggle.setAttribute('aria-expanded', 'false');
        } else {
            box.style.display = 'none';             // mobile: closed by default
            toggle.setAttribute('aria-expanded', 'false');
        }
    }
    sync();
    mq.addEventListener('change', sync);

    toggle.addEventListener('click', function () {
        var open = box.style.display !== 'none';
        box.style.display = open ? 'none' : 'block';
        toggle.setAttribute('aria-expanded', String(!open));
    });
})();
</script>
@endpush
