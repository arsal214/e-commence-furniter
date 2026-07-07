@extends('layouts.main')
@section('title', ($item->meta_title ?? $item->name ?? 'Product Details') . ' | PeytonGhalib')
@section('meta_description', $item->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($item->description ?? 'Shop ' . ($item->name ?? 'this product') . ' at PeytonGhalib. Quality furniture and home decor at unbeatable prices with fast delivery.'), 155))
@section('og_type', 'product')
@php
    $ogImg = !empty($item->image)
        ? (str_starts_with($item->image, 'assets/') ? asset($item->image) : \Storage::url($item->image))
        : asset('assets/img/logo.svg');
@endphp
@section('og_image', $ogImg)
@push('schema')
@php
    $schemaImg = !empty($item->image)
        ? (str_starts_with($item->image, 'assets/') ? asset($item->image) : \Storage::url($item->image))
        : asset('assets/img/logo.svg');
    $schemaInStock     = ($item->stock ?? 0) > 0;
    $schemaReviewCount = $item->reviewCount();
    $schemaAvgRating   = $item->avgRating();

    $schemaProduct = [
        '@context'   => 'https://schema.org/',
        '@type'      => 'Product',
        'name'       => $item->name,
        'image'      => [$schemaImg],
        'description'=> strip_tags($item->description ?? ''),
        'brand'      => ['@type' => 'Brand', 'name' => 'PeytonGhalib'],
        'offers'     => [
            '@type'          => 'Offer',
            'url'            => route('product-details', $item->slug),
            'priceCurrency'  => 'USD',
            'price'          => number_format((float)($item->sale_price ?? $item->price), 2, '.', ''),
            'priceValidUntil'=> now()->addYear()->toDateString(),
            'itemCondition'  => 'https://schema.org/NewCondition',
            'availability'   => $schemaInStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller'         => ['@type' => 'Organization', 'name' => 'PeytonGhalib', 'url' => url('/')],
        ],
    ];
    if (!empty($item->sku))      { $schemaProduct['sku']      = $item->sku; }
    if ($item->category)         { $schemaProduct['category'] = $item->category->name; }
    if ($schemaReviewCount > 0) {
        $schemaProduct['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => round($schemaAvgRating, 1),
            'reviewCount' => (int) $schemaReviewCount,
            'bestRating'  => 5,
            'worstRating' => 1,
        ];
    }

    $breadcrumbItems = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Shop', 'item' => url('/shop')],
    ];
    if ($item->category) {
        $breadcrumbItems[] = ['@type'=>'ListItem','position'=>3,'name'=>$item->category->name,'item'=>url('/shop').'?category='.$item->category->slug];
        $breadcrumbItems[] = ['@type'=>'ListItem','position'=>4,'name'=>$item->name,'item'=>url()->current()];
    } else {
        $breadcrumbItems[] = ['@type'=>'ListItem','position'=>3,'name'=>$item->name,'item'=>url()->current()];
    }
    $schemaBreadcrumb = ['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>$breadcrumbItems];
@endphp
<script type="application/ld+json">{!! json_encode($schemaProduct, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($schemaBreadcrumb, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@push('styles')
<style>
/* Rich content (TinyMCE output) styles */
.rich-content h1,.rich-content h2,.rich-content h3,.rich-content h4,.rich-content h5,.rich-content h6{font-weight:600;line-height:1.3;margin-top:1.25em;margin-bottom:.5em}
.rich-content h1{font-size:1.6rem}.rich-content h2{font-size:1.35rem}.rich-content h3{font-size:1.2rem}.rich-content h4{font-size:1.05rem}
.rich-content p{margin-bottom:.9em;line-height:1.7}
.rich-content ul,.rich-content ol{padding-left:1.5em;margin-bottom:.9em}
.rich-content ul{list-style:disc}.rich-content ol{list-style:decimal}
.rich-content li{margin-bottom:.3em;line-height:1.6}
.rich-content strong,.rich-content b{font-weight:600}
.rich-content em,.rich-content i{font-style:italic}
.rich-content a{color:#bb976d;text-decoration:underline}
.rich-content table{width:100%;border-collapse:collapse;margin-bottom:1em;font-size:.9rem}
.rich-content table th,.rich-content table td{border:1px solid #e5e7eb;padding:.5rem .75rem;text-align:left}
.rich-content table th{background:#f9fafb;font-weight:600}
.dark .rich-content table th{background:#2d3748}.dark .rich-content table td,.dark .rich-content table th{border-color:#4a5568}
.rich-content blockquote{border-left:4px solid #bb976d;padding:.5rem 1rem;margin:1em 0;color:#6b7280;font-style:italic}
.rich-content img{max-width:100%;height:auto;border-radius:.25rem;margin:.5em 0}
.rich-content hr{border:none;border-top:1px solid #e5e7eb;margin:1.5em 0}
</style>
@endpush
@section('content')
@include('includes.navbar')
<!-- Search -->
<div class="search_popup fixed top-0 left-0 bg-red dark:bg-[#39434D] bg-opacity-90 dark:bg-opacity-80 backdrop-blur-[3px] dark:backdrop-blur-[7.5px] w-full h-screen z-[999] px-[15px] md:px-[30px] py-12 md:py-[70px] overflow-y-auto transform scale-90 opacity-0 invisible transition-all duration-300 flex items-center justify-center">
    <div class="container">
        <div class="relative max-w-4xl mx-auto hdr-search-wrapper">
            <button class="hdr_search_close w-[36px] h-[36px] absolute bottom-full md:top-0 right-0 flex items-center justify-center bg-title dark:bg-white text-white dark:text-title">
                <svg class="fill-current" width="15" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.742 12.0717C11.6006 12.2131 11.445 12.2838 11.2753 12.2838C11.1056 12.2838 10.9501 12.2131 10.8086 12.0717L6.16295 7.42598L1.55968 12.0292C1.41826 12.1707 1.2627 12.2414 1.09299 12.2414C0.923289 12.2414 0.767726 12.1707 0.626304 12.0292L0.32932 11.7323C0.187898 11.5908 0.117187 11.4353 0.117188 11.2656C0.117187 11.0959 0.187898 10.9403 0.329319 10.7989L4.93258 6.19561L0.414172 1.6772C0.272751 1.53578 0.20204 1.38021 0.20204 1.21051C0.20204 1.0408 0.272751 0.885239 0.414172 0.743817L0.73237 0.42562C0.873792 0.284198 1.02935 0.213487 1.19906 0.213487C1.36877 0.213488 1.52433 0.284198 1.66575 0.42562L6.18416 4.94403L10.8086 0.319553C10.9501 0.178132 11.1056 0.107421 11.2753 0.107422C11.445 0.107422 11.6006 0.178133 11.742 0.319554L12.039 0.616539C12.1804 0.75796 12.2511 0.913524 12.2511 1.08323C12.2511 1.25293 12.1804 1.4085 12.039 1.54992L7.41453 6.1744L12.0602 10.8201C12.2016 10.9615 12.2724 11.1171 12.2724 11.2868C12.2724 11.4565 12.2016 11.612 12.0602 11.7535L11.742 12.0717Z"/>
                </svg>
            </button>

            <div class="bg-white dark:bg-title py-8 sm:py-10 md:py-[60px] px-5 sm:px-8">
                <!-- Input -->
                <div class="relative">
                    <input class="outline-none border-b border-bdr-clr dark:border-bdr-clr-drk pb-4 md:pb-[22px] text-title w-full pr-7 md:pr-10 leading-none font-lg placeholder:text-title bg-transparent dark:bg-transparent dark:text-white dark:placeholder:text-white" type="text" placeholder="Type your keyword">
                    <button class="absolute right-0 top-0">
                        <svg class="fill-current text-title dark:text-white w-5 md:w-[30px]" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M29.5439 28.2361L22.1484 20.5625C24.0499 18.3074 25.0917 15.4701 25.0917 12.5162C25.0917 5.61489 19.4635 0 12.5459 0C5.62818 0 0 5.61489 0 12.5162C0 19.4176 5.62818 25.0325 12.5459 25.0325C15.1429 25.0325 17.6177 24.251 19.7335 22.7676L27.1852 30.4994C27.4967 30.8221 27.9156 31 28.3646 31C28.7895 31 29.1926 30.8384 29.4986 30.5445C30.1488 29.9203 30.1695 28.8853 29.5439 28.2361ZM12.5459 3.26511C17.6591 3.26511 21.8189 7.41506 21.8189 12.5162C21.8189 17.6174 17.6591 21.7674 12.5459 21.7674C7.43261 21.7674 3.27283 17.6174 3.27283 12.5162C3.27283 7.41506 7.43261 3.26511 12.5459 3.26511Z"/>
                        </svg>
                    </button>
                </div>
                <!-- Tags -->
                <div class="mt-10 md:mt-12">
                    <h4 class="font-medium leading-none">Popular Tags</h4>
                    <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                        @if(!empty($product->category))
                            <a class="btn btn-theme-outline btn-xs" href="{{ route('category.landing', $product->category->slug) }}" data-text="{{ $product->category->name }}"><span>{{ $product->category->name }}</span></a>
                        @endif
                        @if(!empty($product->tag))
                            <a class="btn btn-theme-outline btn-xs" href="{{ url('/shop') }}" data-text="{{ $product->tag }}"><span>{{ $product->tag }}</span></a>
                        @endif
                        <a class="btn btn-theme-outline btn-xs" href="{{ url('/shop') }}" data-text="Shop All"><span>Shop All</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Banner Start -->
<div class="bg-[#F8F5F0] dark:bg-dark-secondary py-5 md:py-[30px]">
    <div class="container-fluid">
        <ul class="flex items-center gap-[10px] text-base md:text-lg leading-none font-normal text-title dark:text-white max-w-[1720px] mx-auto flex-wrap">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li><a href="{{ url('/shop') }}">Shop</a></li>
            <li>/</li>
            <li class="text-primary">{{ $item->name ?? 'Classic Relaxable Chair' }}</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Product Detail Start -->
@php
    $defaultImg  = asset('assets/img/gallery/product-detls/product-01.jpg');
    $primarySrc  = !empty($item->image)
        ? (str_starts_with($item->image, 'assets/') ? asset($item->image) : Storage::url($item->image))
        : $defaultImg;
    $galleryImages = collect([$primarySrc]);
    foreach ($item->productImages as $pi) {
        $galleryImages->push(Storage::url($pi->image));
    }
    $savingsAmt = $item->sale_price ? number_format($item->price - $item->sale_price, 2) : null;
    $activePrice = number_format($item->sale_price ?? $item->price, 2);
@endphp

<style>
/* ── Product Detail Modern Layout ── */
.pd-section { background: #f7f4f0; }

.pd-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
@media (min-width: 768px) {
    .pd-layout {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        align-items: start;
    }
}
@media (min-width: 1200px) {
    .pd-layout { grid-template-columns: 1fr 500px; gap: 48px; }
}

/* Left: sticky image panel */
.pd-img-panel {
    position: sticky;
    top: 90px;
}
@media (max-width: 767px) { .pd-img-panel { position: static; } }

.pd-main-wrap {
    position: relative;
    background: #fff;
    overflow: hidden;
    aspect-ratio: 1/1;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
@media (min-width: 768px) { .pd-main-wrap { aspect-ratio: 5/4; } }
#pd-main-img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: opacity .25s, transform .4s;
}
.pd-main-wrap:hover #pd-main-img { transform: scale(1.03); }

/* Thumbnail strip */
.pd-thumbs {
    display: flex; gap: 10px;
    padding: 12px 0 0;
    overflow-x: auto; scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.pd-thumbs::-webkit-scrollbar { display: none; }
.pd-thumb {
    width: 76px; height: 76px; min-width: 76px;
    object-fit: cover; cursor: pointer;
    border-radius: 10px;
    border: 2.5px solid transparent;
    outline: 1px solid #e5e7eb;
    transition: border-color .2s, outline-color .2s, opacity .2s;
    opacity: .7;
}
.pd-thumb:hover { opacity: .9; border-color: #d1b896; outline-color: #d1b896; }
.pd-thumb.active { border-color: #bb976d; outline-color: #bb976d; opacity: 1; }

/* Right: info card */
.pd-info-col {
    background: #fff;
    border-radius: 16px;
    padding: 18px 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
@media (min-width: 768px) { .pd-info-col { padding: 24px 24px; } }

/* Price block */
.pd-price-block {
    background: linear-gradient(135deg, #f9f6f1 0%, #fdf8f2 100%);
    border-left: 3px solid #bb976d;
    border-radius: 0 10px 10px 0;
    padding: 10px 14px;
    margin-bottom: 10px;
}

/* Qty */
.pd-qty-wrap {
    display: flex; align-items: center;
    border: 1.5px solid #e5e7eb;
    border-radius: 50px;
    overflow: hidden; width: fit-content;
}
.pd-qty-btn {
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    background: transparent; cursor: pointer; border: none;
    color: #555; font-size: 18px;
    transition: background .15s;
}
.pd-qty-btn:hover { background: #f5f5f5; color: #bb976d; }

/* Buttons */
.pd-btn-cart {
    width: 100%; height: 60px;
    background: #172430;
    color: #fff; font-weight: 700;
    font-size: .85rem; letter-spacing: .08em; text-transform: uppercase;
    border: none; border-radius: 10px; cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 4px 14px rgba(23,36,48,.25);
}
.pd-btn-cart:hover { background: #0f1e2e; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(23,36,48,.35); }
.pd-btn-cart:active { transform: translateY(0); }

.pd-btn-wish {
    width: 100%; height: 46px;
    background: transparent;
    color: #555; font-size: .8rem; font-weight: 600;
    border: 1.5px solid #e5e7eb; border-radius: 10px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: border-color .2s, color .2s, background .2s;
}
.pd-btn-wish:hover { border-color: #bb976d; color: #bb976d; background: #fdf8f2; }

/* Trust row */
.pd-trust-row {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 6px; margin-top: 18px;
}
.pd-trust-item {
    background: #f9f7f4;
    border: 1px solid #ede8e0;
    border-radius: 10px;
    padding: 12px 6px; text-align: center;
    display: flex; flex-direction: column; align-items: center; gap: 6px;
}
.pd-trust-item span { font-size: 10.5px; color: #666; line-height: 1.35; }

/* Variant pills */
.pd-variant-pill {
    padding: 6px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 50px;
    font-size: .8rem; color: #333; cursor: pointer;
    transition: border-color .2s, color .2s, background .2s;
    display: inline-block;
}
.pd-variant-pill:hover,
.pd-variant-pill.active { border-color: #bb976d; color: #bb976d; background: #fdf8f2; }

/* Badge */
.pd-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .7rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
    padding: 4px 10px; border-radius: 50px; color: #fff;
}
</style>

<div class="pd-section py-5 md:py-8 lg:py-10">
    <div class="container-fluid px-4 sm:px-6">
        <div class="max-w-[1360px] mx-auto">
            <div class="pd-layout">

                {{-- ── Left: Image Carousel + Thumbs ── --}}
                <div class="pd-img-panel">
                    <div class="pd-main-wrap" id="pd-carousel">
                        {{-- Badge --}}
                        @if($item->tag)
                            @php
                                $badgeBg    = match($item->tag) { 'Sale'=>'#1CB28E','NEW'=>'#9739E1', default=>'#E13939' };
                                $badgeLabel = match($item->tag) { 'Sale'=>'Hot Sale','NEW'=>'NEW','OFF'=>'10% OFF','OFF1'=>'15% OFF', default=>$item->tag };
                            @endphp
                            <span class="pd-badge absolute top-4 left-4 z-10" style="background:{{ $badgeBg }}">{{ $badgeLabel }}</span>
                        @elseif($item->sale_price)
                            <span class="pd-badge absolute top-4 left-4 z-10" style="background:#E13939">Sale</span>
                        @endif

                        {{-- Slides --}}
                        <div id="pd-slides" style="display:flex;width:100%;height:100%;transition:transform .35s cubic-bezier(.4,0,.2,1);">
                            @foreach($galleryImages as $ti => $src)
                            <div style="min-width:100%;height:100%;flex-shrink:0;">
                                <img src="{{ $src }}"
                                     alt="{{ $item->name }} image {{ $ti + 1 }}"
                                     style="width:100%;height:100%;object-fit:cover;display:block;">
                            </div>
                            @endforeach
                        </div>

                        {{-- Prev arrow --}}
                        <button id="pd-prev" aria-label="Previous image"
                                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);z-index:10;
                                       width:38px;height:38px;border-radius:50%;border:none;cursor:pointer;
                                       background:rgba(255,255,255,.92);box-shadow:0 2px 10px rgba(0,0,0,.15);
                                       display:flex;align-items:center;justify-content:center;transition:background .2s;">
                            <svg width="9" height="15" viewBox="0 0 9 15" fill="none">
                                <path d="M7.5 13.5L1.5 7.5L7.5 1.5" stroke="#172430" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        {{-- Next arrow --}}
                        <button id="pd-next" aria-label="Next image"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);z-index:10;
                                       width:38px;height:38px;border-radius:50%;border:none;cursor:pointer;
                                       background:rgba(255,255,255,.92);box-shadow:0 2px 10px rgba(0,0,0,.15);
                                       display:flex;align-items:center;justify-content:center;transition:background .2s;">
                            <svg width="9" height="15" viewBox="0 0 9 15" fill="none">
                                <path d="M1.5 1.5L7.5 7.5L1.5 13.5" stroke="#172430" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        {{-- Dot indicators --}}
                        @if($galleryImages->count() > 1)
                        <div id="pd-dots" style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);display:flex;gap:6px;z-index:10;">
                            @foreach($galleryImages as $ti => $src)
                            <span class="pd-dot" data-index="{{ $ti }}"
                                  style="width:{{ $ti===0?'20px':'8px' }};height:8px;border-radius:50px;cursor:pointer;transition:all .3s;
                                         background:{{ $ti===0?'#bb976d':'rgba(255,255,255,.7)' }};"></span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Thumbnail strip --}}
                    <div class="pd-thumbs">
                        @foreach($galleryImages as $ti => $src)
                        <img src="{{ $src }}"
                             class="pd-thumb {{ $ti === 0 ? 'active' : '' }}"
                             data-index="{{ $ti }}"
                             alt="{{ $item->name }} image {{ $ti + 1 }}">
                        @endforeach
                    </div>
                </div>

                {{-- ── Right: Product Info Card ── --}}
                <div class="pd-info-col">

                    {{-- Category + badges row --}}
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        @if($item->category)
                        <a href="{{ route('category.landing', $item->category->slug) }}"
                           class="text-xs font-semibold text-[#bb976d] uppercase tracking-widest hover:underline">
                            {{ $item->category->name }}
                        </a>
                        @endif
                        @if(($item->stock ?? 1) > 0)
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block"></span> In Stock
                        </span>
                        @else
                        <span class="text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-full">Out of Stock</span>
                        @endif
                    </div>

                    {{-- Product Name H1 --}}
                    <h1 class="font-bold text-2xl sm:text-[1.75rem] text-[#172430] leading-snug mb-1" style="line-height:1.25">
                        {{ $item->name }}
                    </h1>

                    {{-- SKU --}}
                    @if($item->sku)
                    <p class="text-xs text-gray-400 mb-1">SKU: <span class="text-gray-600 font-medium">{{ $item->sku }}</span></p>
                    @endif

                    {{-- Short tagline --}}
                    @if($item->description)
                    @php $tagline = Str::limit(strip_tags($item->description), 110); @endphp
                    <p class="text-sm text-gray-500 leading-relaxed mb-2">{{ $tagline }}</p>
                    @endif

                    {{-- Stars --}}
                    @php
                        $avgR = $item->reviews_avg_rating ?? 0;
                        $revC = $item->reviews_count ?? 0;
                    @endphp
                    <div class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-0.5">
                            @for($s=1;$s<=5;$s++)
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="{{ $s <= round($avgR) ? '#F59E0B' : '#E5E7EB' }}">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-gray-700">{{ $revC > 0 ? number_format($avgR,1) : '0.0' }}</span>
                        <span class="text-sm text-gray-400">· {{ $revC }} {{ Str::plural('review',$revC) }}</span>
                        @if($revC > 0)
                        <a href="#tab-review" onclick="switchTab('tab-review', document.querySelectorAll('.pdtab-btn')[1])" class="text-xs text-[#bb976d] hover:underline ml-1">See all</a>
                        @endif
                    </div>

                    {{-- Price block --}}
                    <div class="pd-price-block mb-2">
                        @if($item->sale_price)
                        @php
                            $savePct = $item->price > 0 ? round((($item->price - $item->sale_price) / $item->price) * 100) : 0;
                            $saveAmt = number_format($item->price - $item->sale_price, 2);
                        @endphp
                        <div class="flex items-center gap-3 flex-wrap mb-1">
                            <span class="text-[2rem] font-extrabold text-[#172430] leading-none">${{ number_format($item->sale_price, 2) }}</span>
                            <span class="text-base text-gray-400 line-through font-medium">${{ number_format($item->price, 2) }}</span>
                            @if($savePct > 0)
                            <span class="text-sm font-bold text-white rounded-full px-3 py-1" style="background:#E13939;">{{ $savePct }}% OFF</span>
                            @endif
                        </div>
                        @if($savePct > 0)
                        <p class="text-xs font-semibold" style="color:#1CB28E;">
                            You save ${{ $saveAmt }} on this product
                        </p>
                        @endif
                        @else
                        <span class="text-[2rem] font-extrabold text-[#172430] leading-none">${{ number_format($item->price, 2) }}</span>
                        @endif
                    </div>

                    {{-- Key Features --}}
                    @php $keyFeatures = $item->key_features ? json_decode($item->key_features, true) : []; @endphp
                    @if(!empty($keyFeatures))
                    <ul class="space-y-2 mb-4 pb-4 border-b border-gray-100">
                        @foreach($keyFeatures as $feat)
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="flex-shrink-0 mt-0.5" width="17" height="17" viewBox="0 0 20 20" fill="none">
                                <circle cx="10" cy="10" r="10" fill="#22c55e" opacity=".15"/>
                                <path d="M5.5 10l3 3 5.5-6" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>{{ $feat }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    {{-- Sizes --}}
                    @if(!empty($item->sizes) && count($item->sizes))
                    <div class="mb-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Size</p>
                        <div class="flex flex-wrap gap-2" id="size-options">
                            @foreach($item->sizes as $si => $sz)
                            <label class="cursor-pointer">
                                <input class="appearance-none hidden size-radio" type="radio" name="size_display" value="{{ $sz }}" {{ $si===0?'checked':'' }}>
                                <span class="pd-variant-pill {{ $si===0?'active':'' }}">{{ $sz }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Colors --}}
                    @if(!empty($item->colors) && count($item->colors))
                    <div class="mb-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                            Color: <span id="selected-color-label" class="font-semibold text-gray-600 normal-case tracking-normal">{{ $item->colors[0] }}</span>
                        </p>
                        <div class="flex flex-wrap gap-2" id="color-options">
                            @foreach($item->colors as $ci => $clr)
                            <label class="cursor-pointer">
                                <input class="appearance-none hidden color-radio" type="radio" name="color_display" value="{{ $clr }}" {{ $ci===0?'checked':'' }}>
                                <span class="pd-variant-pill {{ $ci===0?'active':'' }}">{{ $clr }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Form: Qty + Add to Cart --}}
                    <form action="{{ route('cart.add') }}" method="POST" id="pd-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        @if(!empty($item->sizes))<input type="hidden" name="size"  id="selected-size"  value="{{ $item->sizes[0] ?? '' }}">@endif
                        @if(!empty($item->colors))<input type="hidden" name="color" id="selected-color" value="{{ $item->colors[0] ?? '' }}">@endif

                        {{-- Qty --}}
                        <div class="flex items-center gap-4 mb-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Qty</p>
                            <div class="pd-qty-wrap">
                                <button type="button" id="pd-dec" class="pd-qty-btn">
                                    <svg width="12" height="2" viewBox="0 0 12 2" fill="none"><path d="M1 1H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                                <input id="pd-qty" name="qty" type="number" value="1" min="1"
                                       style="width:40px;height:40px;text-align:center;font-weight:700;font-size:.9rem;background:transparent;border:none;outline:none;color:#172430;">
                                <button type="button" id="pd-inc" class="pd-qty-btn">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex-1">
                                <button type="submit" class="pd-btn-cart w-full">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:8px;margin-top:-2px">
                                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                    </svg>
                                    Add to Cart
                                </button>
                            </div>
                            <div class="flex-1">
                                <button type="button"
                                        class="wishlist-toggle-btn w-full flex items-center justify-center gap-2 border border-gray-200 rounded-xl text-gray-600 text-sm font-semibold hover:border-[#bb976d] hover:text-[#bb976d] hover:bg-[#fdf8f2] transition-all duration-200"
                                        style="background:transparent;cursor:pointer;height:60px;"
                                        data-product-id="{{ $item->id }}"
                                        data-text-add="Add to wishlist"
                                        data-text-remove="In Wishlist">
                                    <svg class="wishlist-btn-icon flex-shrink-0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                    <span class="wishlist-btn-text">Add to wishlist</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Social proof urgency --}}
                    @php $buyCount = rand(18, 63); @endphp
                    <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg px-3.5 py-2.5 mb-4 text-sm text-amber-700">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="#F59E0B" class="flex-shrink-0">
                            <path d="M13 10V6l-5 6h4v4l5-6h-4z"/><circle cx="12" cy="12" r="10" fill="#F59E0B" opacity=".1" stroke="#F59E0B" stroke-width="1.5"/>
                        </svg>
                        <span><strong>{{ $buyCount }} people</strong> bought this in the last 24 hours</span>
                    </div>

                    {{-- 3 trust tiles --}}
                    <div class="pd-trust-row">
                        <div class="pd-trust-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                            <span>Free shipping<br>over $25</span>
                        </div>
                        <div class="pd-trust-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
                            </svg>
                            <span>30-day returns</span>
                        </div>
                        <div class="pd-trust-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <span>Secure checkout</span>
                        </div>
                    </div>

                    {{-- Payment icons + SKU + Share --}}
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2 flex-wrap mb-3">
                            {{-- Visa --}}
                            <span class="inline-flex items-center justify-center border border-gray-200 bg-white rounded-md h-8 px-3" title="Visa"
                                  style="min-width:52px">
                                <span style="font-family:Arial,sans-serif;font-weight:900;font-style:italic;font-size:14px;letter-spacing:-0.5px">
                                    <span style="color:#1A1F71">VI</span><span style="color:#F7A600">SA</span>
                                </span>
                            </span>
                            {{-- Mastercard --}}
                            <span class="inline-flex items-center justify-center border border-gray-200 bg-white rounded-md h-8 px-3" title="Mastercard"
                                  style="min-width:52px">
                                <svg width="32" height="20" viewBox="0 0 32 20">
                                    <circle cx="12" cy="10" r="9" fill="#EB001B"/>
                                    <circle cx="20" cy="10" r="9" fill="#F79E1B"/>
                                    <path d="M16 3.8a9 9 0 0 1 0 12.4A9 9 0 0 1 16 3.8z" fill="#FF5F00"/>
                                </svg>
                            </span>
                            {{-- Amex --}}
                            <span class="inline-flex items-center justify-center border border-gray-200 rounded-md h-8 px-3" title="American Express"
                                  style="min-width:52px;background:#2E77BC">
                                <span style="font-family:Arial,sans-serif;font-weight:800;font-size:11px;color:#fff;letter-spacing:0.5px">AMEX</span>
                            </span>
                            {{-- PayPal --}}
                            <span class="inline-flex items-center justify-center border border-gray-200 bg-white rounded-md h-8 px-3" title="PayPal"
                                  style="min-width:52px">
                                <span style="font-family:Arial,sans-serif;font-weight:900;font-size:12px">
                                    <span style="color:#003087">Pay</span><span style="color:#009CDE">Pal</span>
                                </span>
                            </span>
                            {{-- Apple Pay --}}
                            <span class="inline-flex items-center justify-center border border-gray-200 rounded-md h-8 px-3" title="Apple Pay"
                                  style="min-width:52px;background:#000">
                                <span style="font-family:-apple-system,BlinkMacSystemFont,'SF Pro Text',Arial,sans-serif;font-weight:500;font-size:11px;color:#fff;letter-spacing:-0.2px"> Pay</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-gray-400">
                            <span class="font-medium text-gray-500">Share:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('product-details', $item->slug)) }}" target="_blank" rel="noopener noreferrer nofollow" aria-label="Share on Facebook"
                               class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:border-[#bb976d] hover:text-[#bb976d] transition-colors">
                                <svg width="8" height="15" viewBox="0 0 9 17" fill="currentColor"><path d="M6.60577 3.57091H8.06641V1.01793C7.35979 0.939731 6.64934 0.901696 5.93845 0.904012C5.44674 0.875673 4.9548 0.955623 4.49713 1.13826C4.03945 1.32089 3.6271 1.60179 3.28898 1.96127C2.95087 2.32075 2.69516 2.7501 2.5398 3.21924C2.38443 3.68838 2.33316 4.18596 2.38957 4.67708V6.92589H0.0664062V9.78076H2.38957V16.9578H5.2382V9.78076H7.46831L7.8224 6.92589H5.2382V4.95961C5.23934 4.13482 5.46065 3.57091 6.60577 3.57091Z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($item->name.' — PeytonGhalib') }}&url={{ urlencode(route('product-details', $item->slug)) }}" target="_blank" rel="noopener noreferrer nofollow" aria-label="Share on Twitter"
                               class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:border-[#bb976d] hover:text-[#bb976d] transition-colors">
                                <svg width="14" height="12" viewBox="0 0 21 17" fill="currentColor"><path d="M20.0664 2.79793C19.3139 3.12213 18.518 3.33748 17.7034 3.43737C18.5614 2.93408 19.203 2.1373 19.5067 1.19787C18.7031 1.66898 17.824 2.00078 16.9073 2.17893C16.3448 1.58655 15.6152 1.17498 14.813 0.997632C14.0109 0.820283 13.1734 0.885344 12.4092 1.18437C11.645 1.4834 10.9893 2.0026 10.5273 2.67457C10.0653 3.34654 9.81826 4.14027 9.81829 4.95275C9.8149 5.26331 9.84661 5.57327 9.91281 5.87687C8.2822 5.79842 6.68668 5.38079 5.23048 4.65126C3.77429 3.92172 2.49018 2.89669 1.46206 1.64315C0.934597 2.53471 0.771252 3.59165 1.00537 4.59822C1.23949 5.60479 1.85343 6.48508 2.72185 7.05939C2.07295 7.0421 1.43777 6.87085 0.869833 6.5601V6.6039C0.870909 7.53977 1.1981 8.4467 1.79632 9.17206C2.39455 9.89742 3.22731 10.3969 4.15443 10.5865C3.80358 10.6777 3.44202 10.7224 3.07926 10.7194C2.81857 10.7242 2.55811 10.7012 2.30241 10.6508C2.56687 11.4554 3.07741 12.1591 3.76359 12.6649C4.44978 13.1706 5.27781 13.4534 6.13346 13.4742C4.68099 14.5956 2.89006 15.2032 1.04706 15.1998C0.719312 15.202 0.391758 15.1835 0.0664062 15.1443C1.94176 16.3371 4.12647 16.9674 6.35647 16.959C7.89156 16.9693 9.41342 16.678 10.8337 16.102C12.2539 15.5261 13.5443 14.6769 14.6298 13.6039C15.7153 12.5309 16.5743 11.2554 17.1569 9.85148C17.7396 8.44756 18.0343 6.94319 18.0239 5.42576C18.0239 5.24619 18.0239 5.07392 18.0091 4.90165C18.8186 4.32993 19.5158 3.61702 20.0664 2.79793Z"/></svg>
                            </a>
                        </div>
                    </div>

                </div>{{-- end pd-info-col --}}

            </div>{{-- end pd-layout --}}
        </div>
    </div>
</div>
<!-- Product Detail End -->

<script>
(function(){
    // ── Carousel ──
    var slides   = document.getElementById('pd-slides');
    var thumbs   = document.querySelectorAll('.pd-thumb');
    var dots     = document.querySelectorAll('.pd-dot');
    var total    = thumbs.length;
    var current  = 0;

    function goTo(idx) {
        if(total === 0) return;
        current = (idx + total) % total;
        if(slides) slides.style.transform = 'translateX(-' + (current * 100) + '%)';

        // sync thumbs
        thumbs.forEach(function(t, i){
            t.classList.toggle('active', i === current);
        });

        // sync dots
        dots.forEach(function(d, i){
            d.style.width       = i === current ? '20px' : '8px';
            d.style.background  = i === current ? '#bb976d' : 'rgba(255,255,255,.7)';
        });
    }

    var prevBtn = document.getElementById('pd-prev');
    var nextBtn = document.getElementById('pd-next');
    if(prevBtn) prevBtn.addEventListener('click', function(){ goTo(current - 1); });
    if(nextBtn) nextBtn.addEventListener('click', function(){ goTo(current + 1); });

    // Thumbnail clicks
    thumbs.forEach(function(th){
        th.addEventListener('click', function(){ goTo(parseInt(th.dataset.index) || 0); });
    });

    // Dot clicks
    dots.forEach(function(d){
        d.addEventListener('click', function(){ goTo(parseInt(d.dataset.index) || 0); });
    });

    // Touch swipe
    var startX = 0;
    var carousel = document.getElementById('pd-carousel');
    if(carousel){
        carousel.addEventListener('touchstart', function(e){ startX = e.touches[0].clientX; pauseAuto(); }, {passive:true});
        carousel.addEventListener('touchend', function(e){
            var diff = startX - e.changedTouches[0].clientX;
            if(Math.abs(diff) > 40) goTo(diff > 0 ? current + 1 : current - 1);
            resumeAuto();
        }, {passive:true});
        carousel.addEventListener('mouseenter', pauseAuto);
        carousel.addEventListener('mouseleave', resumeAuto);
    }

    // ── Auto-swipe every 3 seconds ──
    var autoTimer;
    function startAuto(){ autoTimer = setInterval(function(){ goTo(current + 1); }, 3000); }
    function pauseAuto(){ clearInterval(autoTimer); }
    function resumeAuto(){ pauseAuto(); startAuto(); }
    if(total > 1) startAuto();

    // ── Qty +/- ──
    var qtyEl = document.getElementById('pd-qty');
    var dec = document.getElementById('pd-dec');
    var inc = document.getElementById('pd-inc');
    if(dec && inc && qtyEl){
        dec.addEventListener('click', function(){ var v=parseInt(qtyEl.value)||1; if(v>1) qtyEl.value=v-1; });
        inc.addEventListener('click', function(){ var v=parseInt(qtyEl.value)||1; qtyEl.value=v+1; });
    }

    // ── Color label + hidden input sync ──
    document.querySelectorAll('.color-radio').forEach(function(r){
        r.addEventListener('change', function(){
            var lbl = document.getElementById('selected-color-label');
            if(lbl) lbl.textContent = r.value;
            var hid = document.getElementById('selected-color');
            if(hid) hid.value = r.value;
        });
    });

    // ── Size hidden input sync ──
    document.querySelectorAll('.size-radio').forEach(function(r){
        r.addEventListener('change', function(){
            var hid = document.getElementById('selected-size');
            if(hid) hid.value = r.value;
        });
    });
}());
</script>

<!-- Tabs: Description / Reviews / Shipping Start -->
<div class="s-py-50">
    <div class="container-fluid">
        <div class="max-w-[985px] mx-auto">

            {{-- Tab buttons --}}
            <div class="flex gap-0 border-b border-bdr-clr dark:border-bdr-clr-drk mb-8 overflow-x-auto">
                <button onclick="switchTab('tab-desc', this)"
                        class="pdtab-btn px-5 py-3 text-sm sm:text-base font-medium leading-none whitespace-nowrap border-b-2 border-primary text-primary"
                        data-active="true">
                    Description
                </button>
                <button onclick="switchTab('tab-review', this)"
                        class="pdtab-btn px-5 py-3 text-sm sm:text-base font-medium leading-none whitespace-nowrap border-b-2 border-transparent text-paragraph dark:text-white/60 hover:text-primary duration-200">
                    Reviews ({{ $item->reviewCount() }})
                </button>
                @if($item->shipping_info)
                <button onclick="switchTab('tab-shipping', this)"
                        class="pdtab-btn px-5 py-3 text-sm sm:text-base font-medium leading-none whitespace-nowrap border-b-2 border-transparent text-paragraph dark:text-white/60 hover:text-primary duration-200">
                    Shipping
                </button>
                @endif
            </div>

            {{-- Description Panel --}}
            <div id="tab-desc" class="pdtab-panel">
                @if($item->description)
                    <div class="rich-content leading-relaxed">
                        {!! $item->description !!}
                    </div>
                @else
                    <p class="text-gray-400 italic">No description available for this product.</p>
                @endif
            </div>

            {{-- Reviews Panel --}}
            <div id="tab-review" class="pdtab-panel hidden">

                {{-- Average rating summary --}}
                @if ($item->reviewCount() > 0)
                <div class="flex items-center gap-4 mb-8 p-5 bg-[#F8F5F0] dark:bg-dark-secondary rounded-sm">
                    <div class="text-center">
                        <div class="text-5xl font-bold text-title dark:text-white leading-none">{{ number_format($item->avgRating(), 1) }}</div>
                        <div class="mt-1">@include('includes.Home._stars', ['rating' => $item->avgRating()])</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $item->reviewCount() }} {{ Str::plural('review', $item->reviewCount()) }}</div>
                    </div>
                </div>
                @endif

                {{-- Existing reviews --}}
                @forelse ($item->reviews as $review)
                <div class="border-b border-bdr-clr dark:border-bdr-clr-drk pb-6 mb-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                @include('includes.Home._stars', ['rating' => $review->rating])
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <h6 class="font-semibold text-sm text-title dark:text-white">{{ $review->user->name ?? 'Customer' }}</h6>
                        </div>
                    </div>
                    @if ($review->comment)
                    <p class="mt-2 text-sm text-paragraph dark:text-white/70 leading-relaxed">{{ $review->comment }}</p>
                    @endif
                </div>
                @empty
                <p class="text-gray-400 italic text-sm mb-8">No reviews yet. Be the first to review this product!</p>
                @endforelse

                {{-- Submit a review --}}
                <div class="mt-6 border-t border-bdr-clr dark:border-bdr-clr-drk pt-8">
                    <h5 class="text-lg font-semibold text-title dark:text-white mb-5">
                        @auth
                            @if ($item->reviews->where('user_id', auth()->id())->isNotEmpty())
                                Update Your Review
                            @else
                                Write a Review
                            @endif
                        @else
                            Write a Review
                        @endauth
                    </h5>

                    @auth
                    @php
                        $myReview = $item->reviews->firstWhere('user_id', auth()->id());
                    @endphp
                    <form action="{{ route('product.review.store', $item->slug) }}" method="POST" class="space-y-4">
                        @csrf
                        {{-- Star picker --}}
                        <div>
                            <label class="block text-sm font-medium text-title dark:text-white mb-2">Your Rating <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-1" id="star-picker">
                                @for ($s = 1; $s <= 5; $s++)
                                <button type="button"
                                        data-value="{{ $s }}"
                                        class="star-pick-btn w-8 h-8 text-gray-300 hover:text-[#EE9818] transition-colors duration-150"
                                        onclick="setRating({{ $s }})">
                                    <svg viewBox="0 0 15 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                                    </svg>
                                </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="{{ $myReview->rating ?? '' }}">
                            @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Comment --}}
                        <div>
                            <label class="block text-sm font-medium text-title dark:text-white mb-2">Your Review</label>
                            <textarea name="comment" rows="4"
                                      placeholder="Share your experience with this product..."
                                      class="w-full border border-[#E3E5E6] dark:border-bdr-clr-drk bg-transparent text-title dark:text-white px-4 py-3 text-sm outline-none focus:border-primary duration-200 placeholder:text-gray-400 resize-none">{{ $myReview->comment ?? '' }}</textarea>
                            @error('comment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="btn btn-solid" data-text="Submit Review">
                            <span>Submit Review</span>
                        </button>
                    </form>

                    <script>
                    (function () {
                        var current = {{ $myReview->rating ?? 0 }};
                        function paint(n) {
                            document.querySelectorAll('.star-pick-btn').forEach(function (btn) {
                                btn.style.color = parseInt(btn.dataset.value) <= n ? '#EE9818' : '#D1D5DB';
                            });
                        }
                        paint(current);
                        window.setRating = function (n) {
                            current = n;
                            document.getElementById('rating-input').value = n;
                            paint(n);
                        };
                        document.querySelectorAll('.star-pick-btn').forEach(function (btn) {
                            btn.addEventListener('mouseover', function () { paint(parseInt(btn.dataset.value)); });
                            btn.addEventListener('mouseout',  function () { paint(current); });
                        });
                    })();
                    </script>

                    @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Log in</a>
                        to leave a review.
                    </p>
                    @endauth
                </div>
            </div>

            {{-- Shipping Panel --}}
            @if($item->shipping_info)
            <div id="tab-shipping" class="pdtab-panel hidden">
                <div class="rich-content leading-relaxed">
                    {!! $item->shipping_info !!}
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
<script>
function switchTab(panelId, btn) {
    document.querySelectorAll('.pdtab-panel').forEach(function(p){ p.classList.add('hidden'); });
    document.querySelectorAll('.pdtab-btn').forEach(function(b){
        b.classList.remove('border-primary','text-primary');
        b.classList.add('border-transparent','text-paragraph','dark:text-white/60');
    });
    document.getElementById(panelId).classList.remove('hidden');
    btn.classList.add('border-primary','text-primary');
    btn.classList.remove('border-transparent','text-paragraph','dark:text-white/60');
}
</script>
<!-- Tabs End -->

<!-- Related Product Start -->
<div class="s-py-50-100">
    <div class="container-fluid">
        <div class="max-w-[547px] mx-auto text-center">
            <h6 class="text-xl sm:text-2xl leading-none font-bold dark:text-white">Related Products</h6>
            <p class="mt-3">Explore complementary options that enhance your experience. Discover related products curated just for you. </p>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-8 md:pt-[50px]">
            
            <!-- includes/Home/new-products.blade.php -->
            @include('includes.Home.new-products')

        </div>
    </div>
</div>
<!-- Related Product End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')
    
@include('includes.footer')

{{-- ── Size Chart Modal ── --}}
@if(!empty($item->size_chart))
<div id="sizeChartModal"
     class="fixed inset-0 z-[999] flex items-center justify-center px-4 py-8 opacity-0 invisible transition-all duration-300"
     aria-modal="true" role="dialog">
    {{-- Backdrop --}}
    <div id="sizeChartBackdrop"
         class="absolute inset-0 bg-title bg-opacity-80 backdrop-blur-sm"
         onclick="closeSizeChart()"></div>
    {{-- Panel --}}
    <div class="relative bg-white dark:bg-title w-full max-w-3xl max-h-[90vh] overflow-y-auto z-10 p-5 sm:p-8 shadow-2xl">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none dark:text-white">Size Guide</h3>
            <button onclick="closeSizeChart()"
                    class="w-9 h-9 flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white text-title dark:text-white transition-colors duration-200">
                <svg class="fill-current w-3 h-3" viewBox="0 0 12 12">
                    <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                </svg>
            </button>
        </div>
        {{-- Chart Image --}}
        <img src="{{ Storage::url($item->size_chart) }}"
             alt="Size chart for {{ $item->name }}"
             class="w-full h-auto object-contain">
    </div>
</div>
@endif

<script>
// ── Size Chart Modal ──
function openSizeChart() {
    var modal = document.getElementById('sizeChartModal');
    if (!modal) return;
    modal.classList.remove('opacity-0', 'invisible');
    document.body.style.overflow = 'hidden';
}
function closeSizeChart() {
    var modal = document.getElementById('sizeChartModal');
    if (!modal) return;
    modal.classList.add('opacity-0', 'invisible');
    document.body.style.overflow = '';
}
var sizeGuideBtn = document.getElementById('sizeGuideBtn');
if (sizeGuideBtn) sizeGuideBtn.addEventListener('click', openSizeChart);

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSizeChart();
});
</script>

<script>
(function () {
    document.querySelectorAll('.size-radio,.color-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            var group = this.name;
            document.querySelectorAll('input[name="'+group+'"]').forEach(function(r){
                var pill = r.nextElementSibling;
                if(pill) pill.classList.remove('active');
            });
            var myPill = this.nextElementSibling;
            if(myPill) myPill.classList.add('active');
        });
    });
})();
</script>

@endsection

