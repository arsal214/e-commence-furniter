@extends('layouts.main')

@section('title', ($category->meta_title ?: 'Buy ' . $category->name . ' Online') . ' | PeytonGhalib')
@section('meta_description', $category->meta_description ?: 'Shop our full range of ' . $category->name . ' at PeytonGhalib. Quality pieces, fast delivery, easy returns — browse and buy online today.')

@push('schema')
@php
    $catDesc = $category->meta_description
        ?: 'Shop our full range of ' . $category->name . ' at PeytonGhalib. Quality pieces, fast delivery, easy returns.';
    $catListItems = [];
    foreach ($products as $i => $p) {
        $pImg = !empty($p->image)
            ? (str_starts_with($p->image, 'assets/') ? asset($p->image) : \Storage::url($p->image))
            : asset('assets/img/logo.svg');
        $catListItems[] = ['@type'=>'ListItem','position'=>$i+1,'name'=>$p->name,'url'=>route('product-details',$p->slug),'image'=>$pImg];
    }
    $schemaCollection = ['@context'=>'https://schema.org','@type'=>'CollectionPage','name'=>$category->name.' — PeytonGhalib','description'=>$catDesc,'url'=>url()->current()];
    $schemaItemList  = ['@context'=>'https://schema.org','@type'=>'ItemList','name'=>$category->name.' — PeytonGhalib','itemListElement'=>$catListItems];
@endphp
<script type="application/ld+json">{!! json_encode($schemaCollection, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($schemaItemList,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@push('styles')
<style>
/* ── Category Landing ── */
.cl-hero {
    display: grid;
    grid-template-columns: 1fr;
    min-height: 380px;
}
@media (min-width: 768px) {
    .cl-hero { grid-template-columns: 1fr 1fr; min-height: 440px; }
}
@media (min-width: 1200px) {
    .cl-hero { grid-template-columns: 3fr 2fr; min-height: 480px; }
}

.cl-hero-img {
    position: relative;
    overflow: hidden;
    min-height: 260px;
}
.cl-hero-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .6s ease;
}
.cl-hero-img:hover img { transform: scale(1.04); }

.cl-hero-body {
    background: #172430;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 40px 36px;
}
@media (min-width: 768px) { .cl-hero-body { padding: 52px 48px; } }

.cl-stat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(187,151,109,.15);
    border: 1px solid rgba(187,151,109,.3);
    border-radius: 50px;
    padding: 5px 14px;
    font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    color: #bb976d;
}

/* Sort bar */
.cl-sort-bar {
    position: sticky; top: 70px; z-index: 40;
    background: #fff;
    border-bottom: 1px solid #ede8e0;
    padding: 12px 0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
}
.dark .cl-sort-bar { background: #1e2d39; border-color: rgba(255,255,255,.08); }

/* Featured spotlight */
.cl-spotlight {
    display: grid;
    grid-template-columns: 1fr;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 4px 28px rgba(0,0,0,.08);
}
@media (min-width: 768px) {
    .cl-spotlight { grid-template-columns: 1fr 1fr; }
}

.cl-spotlight-img {
    position: relative;
    min-height: 300px;
    overflow: hidden;
}
.cl-spotlight-img img { width:100%; height:100%; object-fit:cover; }

/* Product cards */
.cl-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: box-shadow .25s, transform .25s;
}
.dark .cl-card { background: #1e2d39; }
.cl-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.13); transform: translateY(-3px); }

.cl-card-img {
    position: relative;
    overflow: hidden;
    aspect-ratio: 4/3;
}
.cl-card-img img { width:100%; height:100%; object-fit:cover; transition: transform .4s; }
.cl-card:hover .cl-card-img img { transform: scale(1.06); }

/* Category image cards */
.cl-cat-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    aspect-ratio: 3/2;
    display: block;
}
.cl-cat-card img { width:100%; height:100%; object-fit:cover; transition: transform .4s; }
.cl-cat-card:hover img { transform: scale(1.06); }
.cl-cat-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(23,36,48,.75) 0%, transparent 55%);
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 16px;
    transition: background .3s;
}
.cl-cat-card:hover .cl-cat-overlay { background: linear-gradient(to top, rgba(23,36,48,.88) 0%, transparent 60%); }

/* FAQ */
.cl-faq details[open] summary { color: #bb976d; }
.cl-faq details[open] .faq-icon { transform: rotate(45deg); color: #bb976d; }
</style>
@endpush

@section('content')
@include('includes.navbar')

{{-- ══════════════════════════════════════
     1. SPLIT-PANEL HERO
══════════════════════════════════════ --}}
@php
    $heroImg = $category->image
        ? (str_starts_with($category->image, 'assets/') ? asset($category->image) : Storage::url($category->image))
        : asset('assets/img/shortcode/breadcumb.jpg');
@endphp

<div class="cl-hero">
    {{-- Image side --}}
    <div class="cl-hero-img">
        <img src="{{ $heroImg }}" alt="{{ $category->name }}">
        {{-- Gradient overlay --}}
        <div class="absolute inset-0" style="background:linear-gradient(to right, transparent 60%, #172430 100%);"></div>
        {{-- Breadcrumb on image --}}
        <div class="absolute top-5 left-6">
            <ul class="flex items-center gap-2 text-sm text-white/80">
                <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                <li class="text-white/40">/</li>
                <li><a href="{{ url('/categories') }}" class="hover:text-white transition-colors">Categories</a></li>
                <li class="text-white/40">/</li>
                <li class="text-[#bb976d]">{{ $category->name }}</li>
            </ul>
        </div>
    </div>

    {{-- Text side --}}
    <div class="cl-hero-body">
        <div class="cl-stat-pill mb-4">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            {{ $category->name }}
        </div>

        <h1 class="text-white text-3xl md:text-4xl font-extrabold leading-tight mb-3">
            Buy {{ $category->name }}<br>
            <span style="color:#bb976d">Online</span>
        </h1>

        @if($category->description)
        <p class="text-white/70 text-sm leading-relaxed mb-6 max-w-sm">{{ Str::limit($category->description, 140) }}</p>
        @endif

        {{-- Stats row --}}
        <div class="flex items-center gap-6 mb-6 flex-wrap">
            <div>
                <p class="text-[#bb976d] text-2xl font-extrabold leading-none">{{ $totalCount }}</p>
                <p class="text-white/50 text-xs mt-0.5">Products</p>
            </div>
            @if($priceMin && $priceMax)
            <div class="w-px h-8 bg-white/15"></div>
            <div>
                <p class="text-white text-2xl font-extrabold leading-none">${{ number_format($priceMin, 0) }}–${{ number_format($priceMax, 0) }}</p>
                <p class="text-white/50 text-xs mt-0.5">Price range</p>
            </div>
            @endif
            <div class="w-px h-8 bg-white/15"></div>
            <div>
                <p class="text-white text-2xl font-extrabold leading-none">30</p>
                <p class="text-white/50 text-xs mt-0.5">Day returns</p>
            </div>
        </div>

        <a href="{{ url('/shop?category='.$category->slug) }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white rounded-full transition-all duration-200 hover:-translate-y-0.5"
           style="background:#bb976d;box-shadow:0 4px 18px rgba(187,151,109,.4);">
            Shop All {{ $category->name }}
            <svg width="14" height="10" viewBox="0 0 16 12" fill="none"><path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════
     2. SORT / FILTER BAR
══════════════════════════════════════ --}}
<div class="cl-sort-bar">
    <div class="container-fluid px-4 sm:px-6">
        <div class="max-w-[1720px] mx-auto flex items-center justify-between gap-4 flex-wrap">
            <p class="text-sm text-gray-500 dark:text-white/50">
                Showing <span class="font-semibold text-[#172430] dark:text-white">{{ $products->count() + ($featuredProduct ? 1 : 0) }}</span> of <span class="font-semibold text-[#172430] dark:text-white">{{ $totalCount }}</span> products
            </p>
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Sort --}}
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-400 font-medium whitespace-nowrap">Sort by:</label>
                    <select id="cl-sort" onchange="clSort(this.value)"
                            class="text-xs border border-gray-200 dark:border-white/10 rounded-lg px-3 py-1.5 bg-white dark:bg-[#172430] text-[#172430] dark:text-white outline-none cursor-pointer">
                        <option value="latest">Newest</option>
                        <option value="price_asc">Price: Low → High</option>
                        <option value="price_desc">Price: High → Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
                {{-- View all link --}}
                <a href="{{ url('/shop?category='.$category->slug) }}"
                   class="text-xs font-semibold px-4 py-1.5 rounded-lg border transition-colors duration-200"
                   style="border-color:#bb976d;color:#bb976d;"
                   onmouseover="this.style.background='#bb976d';this.style.color='#fff'"
                   onmouseout="this.style.background='';this.style.color='#bb976d'">
                    View All →
                </a>
            </div>
        </div>
    </div>
</div>

<section class="py-10 md:py-14">
    <div class="container-fluid px-4 sm:px-6">
        <div class="max-w-[1720px] mx-auto">

{{-- ══════════════════════════════════════
     3. FEATURED / EDITOR'S PICK SPOTLIGHT
══════════════════════════════════════ --}}
@if($featuredProduct)
@php
    $fpImg = !empty($featuredProduct->image)
        ? (str_starts_with($featuredProduct->image, 'assets/') ? asset($featuredProduct->image) : Storage::url($featuredProduct->image))
        : asset('assets/img/gallery/product-detls/product-01.jpg');
    $fpPrice   = $featuredProduct->effective_price;
    $fpSavePct = $featuredProduct->sale_price && $featuredProduct->price > 0
        ? round((($featuredProduct->price - $featuredProduct->sale_price) / $featuredProduct->price) * 100)
        : 0;
@endphp
<div class="cl-spotlight mb-10 md:mb-14" data-aos="fade-up">
    <div class="cl-spotlight-img">
        <img src="{{ $fpImg }}" alt="{{ $featuredProduct->name }}">
        <div class="absolute inset-0" style="background:linear-gradient(to right, transparent 50%, rgba(23,36,48,.04));"></div>
        <span class="absolute top-5 left-5 text-xs font-bold text-white px-3 py-1.5 rounded-full"
              style="background:#bb976d;letter-spacing:.05em;">★ Editor's Pick</span>
        @if($fpSavePct > 0)
        <span class="absolute top-5 right-5 text-xs font-bold text-white px-3 py-1.5 rounded-full"
              style="background:#E13939;">{{ $fpSavePct }}% OFF</span>
        @endif
    </div>
    <div class="flex flex-col justify-center p-8 md:p-12" style="background:#f9f6f1;">
        <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:#bb976d;">{{ $category->name }} — Top Pick</p>
        <h2 class="text-2xl md:text-3xl font-extrabold text-[#172430] leading-snug mb-3">{{ $featuredProduct->name }}</h2>
        @if($featuredProduct->description)
        <p class="text-sm text-gray-500 leading-relaxed mb-5">{{ Str::limit(strip_tags($featuredProduct->description), 130) }}</p>
        @endif

        {{-- Stars + count --}}
        <div class="mb-4">
            @include('includes.Home._stars', [
                'rating' => $featuredProduct->reviews_avg_rating ?? 0,
                'count'  => $featuredProduct->reviews_count ?? 0,
            ])
        </div>

        {{-- Price --}}
        <div class="flex items-baseline gap-3 mb-6">
            <span class="text-3xl font-extrabold text-[#172430]">${{ number_format($fpPrice, 2) }}</span>
            @if($featuredProduct->sale_price)
            <span class="text-base text-gray-400 line-through">${{ number_format($featuredProduct->price, 2) }}</span>
            @endif
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('product-details', $featuredProduct->slug) }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white rounded-full transition-all hover:-translate-y-0.5"
               style="background:#172430;box-shadow:0 4px 16px rgba(23,36,48,.3);">
                View Product
                <svg width="14" height="10" viewBox="0 0 16 12" fill="none"><path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <form action="{{ route('cart.add') }}" method="POST" class="contents">
                @csrf
                <input type="hidden" name="product_id" value="{{ $featuredProduct->id }}">
                <input type="hidden" name="qty" value="1">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold rounded-full border-2 transition-all hover:-translate-y-0.5"
                        style="border-color:#bb976d;color:#bb976d;background:transparent;"
                        onmouseover="this.style.background='#bb976d';this.style.color='#fff'"
                        onmouseout="this.style.background='transparent';this.style.color='#bb976d'">
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════
     4. PRODUCT GRID
══════════════════════════════════════ --}}
@if($products->isNotEmpty())
<div id="cl-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6 mb-10 md:mb-14" data-aos="fade-up">
    @foreach($products as $product)
    @php
        $pImg = !empty($product->image)
            ? (str_starts_with($product->image, 'assets/') ? asset($product->image) : Storage::url($product->image))
            : null;
    @endphp
    <div class="cl-card group"
         data-price="{{ $product->effective_price }}"
         data-rating="{{ $product->reviews_avg_rating ?? 0 }}"
         data-date="{{ $product->created_at->timestamp }}">

        {{-- Image --}}
        <div class="cl-card-img">
            <a href="{{ route('product-details', $product->slug) }}">
                @if($pImg)
                <img src="{{ $pImg }}" alt="{{ $product->name }}">
                @else
                <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center" style="aspect-ratio:4/3;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                @endif
            </a>

            {{-- Tag badge --}}
            @if($product->tag)
            @php $tagBg = match($product->tag) { 'Sale'=>'#1CB28E','NEW'=>'#9739E1',default=>'#E13939' }; @endphp
            <span class="absolute top-3 left-3 text-[10px] font-bold text-white px-2.5 py-1 rounded-full z-10"
                  style="background:{{ $tagBg }}">{{ $product->tag }}</span>
            @elseif($product->sale_price)
            @php $sp = $product->price > 0 ? round((($product->price-$product->sale_price)/$product->price)*100) : 0; @endphp
            @if($sp > 0)
            <span class="absolute top-3 left-3 text-[10px] font-bold text-white px-2.5 py-1 rounded-full z-10"
                  style="background:#E13939">{{ $sp }}% OFF</span>
            @endif
            @endif

            {{-- Quick actions overlay --}}
            <div class="absolute inset-0 flex flex-col items-end justify-center gap-2 pr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                {{-- Wishlist --}}
                <button type="button"
                        class="wishlist-toggle-btn w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                        style="background:rgba(23,36,48,.85);color:#fff;border:none;cursor:pointer;"
                        onmouseover="this.style.background='#bb976d'" onmouseout="this.style.background='rgba(23,36,48,.85)'"
                        data-product-id="{{ $product->id }}" title="Add to wishlist">
                    <svg class="wishlist-icon-outline" width="16" height="16" viewBox="0 0 24 20" fill="currentColor">
                        <path d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969Z"/>
                    </svg>
                </button>
                {{-- Quick view --}}
                <a href="{{ route('product-details', $product->slug) }}"
                   class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                   style="background:rgba(23,36,48,.85);color:#fff;"
                   onmouseover="this.style.background='#bb976d'" onmouseout="this.style.background='rgba(23,36,48,.85)'"
                   title="View product">
                    <svg width="16" height="13" viewBox="0 0 24 16" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M22.3478 8.44208C20.2569 12.1678 16.2916 14.4822 12.0014 14.4822C7.70844 14.4822 3.74319 12.1678 1.65223 8.44208C1.49119 8.15278 1.49119 7.84697 1.65223 7.55792C3.74319 3.83229 7.70844 1.51813 12.0014 1.51813C16.2916 1.51813 20.2568 3.83229 22.3478 7.55792C22.5116 7.84697 22.5116 8.15278 22.3478 8.44208ZM12.0014 11.1141C13.7314 11.1141 15.1392 9.71721 15.1392 7.99987C15.1392 6.28253 13.7314 4.88562 12.0014 4.88562C10.2686 4.88562 8.86081 6.28253 8.86081 7.99987C8.86081 9.71721 10.2687 11.1141 12.0014 11.1141Z"/></svg>
                </a>
                {{-- Add to cart --}}
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit"
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                            style="background:rgba(23,36,48,.85);color:#fff;border:none;cursor:pointer;"
                            onmouseover="this.style.background='#bb976d'" onmouseout="this.style.background='rgba(23,36,48,.85)'"
                            title="Add to cart">
                        <svg width="16" height="17" viewBox="0 0 20 22" fill="currentColor"><path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826Z"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Card body --}}
        <div class="p-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#bb976d] mb-1">{{ $product->category->name ?? $category->name }}</p>
            <h3 class="text-sm font-semibold text-[#172430] dark:text-white leading-snug mb-2 line-clamp-2">
                <a href="{{ route('product-details', $product->slug) }}" class="hover:text-[#bb976d] transition-colors duration-200">{{ $product->name }}</a>
            </h3>
            @include('includes.Home._stars', ['rating' => $product->reviews_avg_rating ?? 0, 'count' => $product->reviews_count ?? 0])
            <div class="flex items-center gap-2 mt-2">
                <span class="text-base font-bold text-[#172430] dark:text-white">
                    {{ $product->display_price }}
                </span>
                @if($product->sale_price)
                <span class="text-xs text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- View all CTA --}}
<div class="text-center mb-14" data-aos="fade-up">
    <a href="{{ url('/shop?category='.$category->slug) }}"
       class="group inline-flex items-center gap-3 px-8 py-4 text-white font-bold text-sm rounded-full transition-all duration-300 hover:-translate-y-0.5"
       style="background:#172430;box-shadow:0 4px 20px rgba(23,36,48,.3);">
        View All {{ $totalCount }} {{ $category->name }} Products
        <svg class="transition-transform duration-300 group-hover:translate-x-1" width="16" height="12" viewBox="0 0 16 12" fill="none">
            <path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>

@else
<div class="text-center py-20 text-gray-400 mb-14">
    <svg class="mx-auto mb-4 text-gray-200" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <p class="mb-3 text-base">No products in this category yet.</p>
    <a href="{{ url('/shop') }}" class="text-[#bb976d] font-semibold hover:underline">Browse all products →</a>
</div>
@endif

{{-- ══════════════════════════════════════
     5. FAQ
══════════════════════════════════════ --}}
<div class="max-w-3xl mx-auto mb-14 cl-faq" data-aos="fade-up">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-1 h-8 rounded-full" style="background:#bb976d;"></div>
        <h2 class="text-2xl md:text-3xl font-bold text-[#172430] dark:text-white">
            Questions about {{ $category->name }}
        </h2>
    </div>
    @php
    $faqs = [
        ['q'=>'What materials are used in your '.$category->name.'?',
         'a'=>'All PeytonGhalib '.$category->name.' products are made from premium, sustainably sourced materials. Each product page lists exact material details, finishes, and care instructions.'],
        ['q'=>'How long does delivery take for '.$category->name.'?',
         'a'=>'Standard delivery takes 3–7 business days. Free delivery on orders over $99. For large furniture items, white-glove delivery with in-room placement is available on request.'],
        ['q'=>'Can I return a '.$category->name.' item if it doesn\'t fit?',
         'a'=>'Yes — 30-day hassle-free returns on all items in original, unused condition. Contact our support team and we\'ll arrange collection or a pre-paid return label.'],
        ['q'=>'Are '.$category->name.' dimensions accurate on the product page?',
         'a'=>'All dimensions (H × W × D in cm/inches) are measured to industry standards. We recommend measuring your space before ordering. Lifestyle photos show realistic scale.'],
    ];
    @endphp
    <div class="space-y-3">
        @foreach($faqs as $faq)
        <details class="group border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden">
            <summary class="flex items-center justify-between gap-4 px-6 py-4 cursor-pointer list-none
                            bg-white dark:bg-[#1e2d39] hover:bg-[#fdf6ee] dark:hover:bg-white/5 transition-colors duration-200">
                <span class="font-semibold text-sm md:text-base text-[#172430] dark:text-white">{{ $faq['q'] }}</span>
                <svg class="faq-icon flex-none w-5 h-5 text-gray-400 transition-transform duration-300"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                </svg>
            </summary>
            <div class="px-6 py-4 bg-[#fafaf8] dark:bg-white/5 border-t border-gray-100 dark:border-white/10">
                <p class="text-sm text-gray-500 dark:text-white/70 leading-relaxed">{{ $faq['a'] }}</p>
            </div>
        </details>
        @endforeach
    </div>
</div>

{{-- ══════════════════════════════════════
     6. RELATED CATEGORIES — IMAGE CARDS
══════════════════════════════════════ --}}
@if($relatedCategories->isNotEmpty())
<div data-aos="fade-up">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-1 h-7 rounded-full" style="background:#bb976d;"></div>
        <h2 class="text-xl md:text-2xl font-bold text-[#172430] dark:text-white">Also Browse</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach($relatedCategories as $related)
        @php
            $relImg = $related->image
                ? (str_starts_with($related->image, 'assets/') ? asset($related->image) : Storage::url($related->image))
                : asset('assets/img/shortcode/breadcumb.jpg');
        @endphp
        <a href="{{ route('category.landing', $related->slug) }}" class="cl-cat-card group">
            <img src="{{ $relImg }}" alt="{{ $related->name }}">
            <div class="cl-cat-overlay">
                <p class="text-white font-bold text-sm leading-tight">{{ $related->name }}</p>
                <p class="text-white/60 text-xs mt-0.5">{{ $related->products_count }} {{ Str::plural('item', $related->products_count) }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

        </div>
    </div>
</section>

@include('includes.footer')

<script>
// Sort cards client-side
function clSort(val) {
    var grid = document.getElementById('cl-grid');
    if (!grid) return;
    var cards = Array.from(grid.querySelectorAll('.cl-card'));
    cards.sort(function(a, b) {
        if (val === 'price_asc')  return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
        if (val === 'price_desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
        if (val === 'rating')     return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
        return parseInt(b.dataset.date) - parseInt(a.dataset.date); // latest
    });
    cards.forEach(function(c){ grid.appendChild(c); });
}
</script>
@endsection
