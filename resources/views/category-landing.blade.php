@extends('layouts.main')

@section('title', ($category->meta_title ?: 'Buy ' . $category->name . ' Online') . ' | PeytonGhalib')
{{-- Explicit self-canonical to the clean slug so tracking/junk query params
     (?utm_source=…) never spawn duplicate canonical URLs. --}}
@section('canonical', url('/category/' . $category->slug))
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
    $schemaCollection = ['@context'=>'https://schema.org','@type'=>'CollectionPage','name'=>$category->name.' — PeytonGhalib','description'=>$catDesc,'url'=>url('/category/' . $category->slug)];
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

.pg-native-select {
    appearance: none; -webkit-appearance: none; -moz-appearance: none;
    height: 36px; padding: 0 34px 0 12px; line-height: 34px; font-size: 13px;
    border: 1px solid #E8E1D7; border-radius: 8px; background-color: #fff; color: #172430; cursor: pointer;
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B6560' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
}
.pg-native-select:hover { border-color: #BB976D; }
.dark .pg-native-select { background-color: #172430; color: #fff; border-color: #2F3B45; }
/* Sort bar */
.cl-sort-bar {
    position: sticky; top: 70px; z-index: 40;
    background: #fff;
    border-bottom: 1px solid #ede8e0;
    padding: 6px 0;
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
    aspect-ratio: 1/1; border-radius: 12px;
}
.cl-card-img img { width:100%; height:100%; object-fit:cover; transition: transform .4s; }
.cl-card:hover .cl-card-img img { transform: scale(1.06); }


/* Category hero */
.clx-hero { text-align: center; padding: 36px 16px 32px; border-top: 1px solid #c9c2b4; background: radial-gradient(700px 220px at 50% 0, rgba(187,151,109,.18), transparent 70%), #f4f1ea; }
.clx-crumbs { display: flex; justify-content: center; flex-wrap: wrap; gap: 8px; margin: 0 0 14px; padding: 0; list-style: none; font-size: 12px; color: #6b6560; }
.clx-crumbs a { color: #6b6560; text-decoration: none; } .clx-crumbs a:hover { color: #8a6a44; }
.clx-crumbs__cur { color: #172430; font-weight: 600; }
.clx-eyebrow { display: inline-block; margin-bottom: 10px; font-size: 11px; font-weight: 700; letter-spacing: .24em; text-transform: uppercase; color: #8a6a44; }
.clx-title { margin: 0; font: 400 clamp(2.4rem, 6vw, 4rem)/1.05 var(--pg-serif, serif); color: #172430; }
.clx-rule { display: block; width: 56px; height: 3px; margin: 18px auto 0; border-radius: 3px; background: #bb976d; }
.clx-desc { max-width: 640px; margin: 16px auto 0; font-size: 16px; line-height: 1.6; color: #4a4640; }
@media (max-width: 640px) { .clx-hero { padding: 26px 16px 24px; } .clx-desc { font-size: 14px; } }
/* Also browse: premium image cards */
.cl-cat-card {
    position: relative; display: block; overflow: hidden; border-radius: 18px;
    aspect-ratio: 4/5; background: #e9e4db;
    box-shadow: 0 1px 2px rgba(23,36,48,.06), 0 8px 24px rgba(23,36,48,.08);
    transition: transform .35s ease, box-shadow .35s ease;
}
.cl-cat-card:hover { transform: translateY(-6px); box-shadow: 0 2px 4px rgba(23,36,48,.08), 0 18px 40px rgba(23,36,48,.18); }
.cl-cat-card img { width:100%; height:100%; object-fit:cover; transition: transform .7s ease; }
.cl-cat-card:hover img { transform: scale(1.07); }
.cl-cat-overlay {
    position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: flex-end;
    padding: 20px; color: #fff;
    background: linear-gradient(to top, rgba(23,36,48,.82) 0%, rgba(23,36,48,.25) 45%, transparent 70%);
}
.cl-cat-name { font-size: 1.15rem; font-weight: 600; line-height: 1.2; }
.cl-cat-meta { display: flex; align-items: center; justify-content: space-between; margin-top: 6px; font-size: .75rem; color: rgba(255,255,255,.75); }
.cl-cat-go {
    width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center;
    background: rgba(255,255,255,.18); backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,.35);
    transition: background .3s, transform .3s;
}
.cl-cat-card:hover .cl-cat-go { background: #bb976d; border-color: #bb976d; transform: translateX(3px); }

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
<div class="clx-hero">
    <ul class="clx-crumbs">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li aria-hidden="true">/</li>
        <li><a href="{{ url('/categories') }}">Categories</a></li>
        <li aria-hidden="true">/</li>
        <li class="clx-crumbs__cur">{{ $category->name }}</li>
    </ul>
    <span class="clx-eyebrow">Collection</span>
    <h1 class="clx-title">{{ $category->name }}</h1>
    <span class="clx-rule" aria-hidden="true"></span>
    @if($category->description)
    <p class="clx-desc">{{ Str::limit($category->description, 180) }}</p>
    @endif
</div>

{{-- ══════════════════════════════════════
     2. SORT / FILTER BAR
══════════════════════════════════════ --}}
<div class="cl-sort-bar">
    <div class="container-fluid px-4 sm:px-6">
        <div class="max-w-[1720px] mx-auto flex items-center justify-between gap-4 flex-wrap">
            <span class="text-sm text-gray-500">{{ number_format($totalCount) }} {{ Str::plural('item', $totalCount) }}</span>
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Sort --}}
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-400 font-medium whitespace-nowrap">Sort by:</label>
                    <select id="cl-sort" onchange="location.search='?sort='+this.value"
                            class="pg-native-select">
                        <option value="latest" @selected(request('sort','latest')==='latest')>Newest</option>
                        <option value="price_asc" @selected(request('sort','latest')==='price_asc')>Price: Low → High</option>
                        <option value="price_desc" @selected(request('sort','latest')==='price_desc')>Price: High → Low</option>
                        <option value="rating" @selected(request('sort','latest')==='rating')>Top Rated</option>
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

<section class="py-5 md:py-6">
    <div class="container-fluid px-4 sm:px-6">
        <div class="max-w-[1720px] mx-auto">

{{-- ══════════════════════════════════════
     4. PRODUCT GRID
══════════════════════════════════════ --}}
@if($products->isNotEmpty())
{{-- Two-up on phones, matching the shop grid. --}}
<div id="cl-grid" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6 mb-10 md:mb-14" data-aos="fade-up">
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
                @if ($product->was_price)
                <span class="text-xs text-gray-400 line-through">{{ $product->was_price }}</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mb-14">{{ $products->links() }}</div>

@else
<div class="text-center py-20 text-gray-400 mb-14">
    <svg class="mx-auto mb-4 text-gray-200" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <p class="mb-3 text-base">No products in this category yet.</p>
    <a href="{{ url('/shop') }}" class="text-[#bb976d] font-semibold hover:underline">Browse all products →</a>
</div>
@endif

{{-- ══════════════════════════════════════
     6. RELATED CATEGORIES — IMAGE CARDS
══════════════════════════════════════ --}}
@if($relatedCategories->isNotEmpty())
<div data-aos="fade-up">
    <div class="text-center mb-8">
        <p class="text-xs font-bold uppercase tracking-[.2em] mb-2" style="color:#bb976d;">Discover more</p>
        <h2 class="text-2xl md:text-3xl font-semibold text-[#172430] dark:text-white">More Categories</h2>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
        @foreach($relatedCategories as $related)
        @php
            $relImg = $related->image
                ? (str_starts_with($related->image, 'assets/') ? asset($related->image) : Storage::url($related->image))
                : asset('assets/img/shortcode/breadcumb.jpg');
        @endphp
        <a href="{{ route('category.landing', $related->slug) }}" class="cl-cat-card group">
            <img src="{{ $relImg }}" alt="{{ $related->name }}" loading="lazy">
            <div class="cl-cat-overlay">
                <p class="cl-cat-name">{{ $related->name }}</p>
                <div class="cl-cat-meta">
                    <span>{{ $related->products_count }} {{ Str::plural('item', $related->products_count) }}</span>
                    <span class="cl-cat-go"><svg width="14" height="10" viewBox="0 0 16 12" fill="none"><path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                </div>
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


@endsection
