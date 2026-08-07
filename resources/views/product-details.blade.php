@extends('layouts.main')
@section('title', ($item->meta_title ?? $item->name ?? 'Product Details') . ' | PeytonGhalib')
@section('meta_description', $item->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($item->description ?? 'Shop ' . ($item->name ?? 'this product') . ' at PeytonGhalib. Quality furniture and home decor at unbeatable prices with fast delivery.'), 155))
@section('og_type', 'product')
@php
    $ogImg = !empty($item->image)
        ? (str_starts_with($item->image, 'assets/') ? asset($item->image) : url(\Storage::url($item->image)))
        : asset('assets/img/logo.svg');
@endphp
@section('og_image', $ogImg)
@push('schema')
@php
    $schemaImg = !empty($item->image)
        ? (str_starts_with($item->image, 'assets/') ? asset($item->image) : url(\Storage::url($item->image)))
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
        'offers'     => [
            '@type'          => 'Offer',
            'url'            => route('product-details', $item->slug),
            'priceCurrency'  => 'USD',
            'price'          => number_format($item->effective_price, 2, '.', ''),
            'priceValidUntil'=> now()->addYear()->toDateString(),
            'itemCondition'  => 'https://schema.org/NewCondition',
            'availability'   => $schemaInStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller'         => ['@type' => 'Organization', 'name' => 'PeytonGhalib', 'url' => url('/')],
            // Merchant listing fields Google recommends for product results.
            'hasMerchantReturnPolicy' => [
                '@type'                => 'MerchantReturnPolicy',
                'applicableCountry'    => 'US',
                'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
                'merchantReturnDays'   => 30,
                'returnMethod'         => 'https://schema.org/ReturnByMail',
                'returnFees'           => 'https://schema.org/FreeReturn',
            ],
            'shippingDetails' => [
                '@type'          => 'OfferShippingDetails',
                'shippingRate'   => ['@type' => 'MonetaryAmount', 'value' => '0', 'currency' => 'USD'],
                'shippingDestination' => ['@type' => 'DefinedRegion', 'addressCountry' => 'US'],
            ],
        ],
    ];
    // Only assert a brand when the product actually records one — hardcoding the
    // store name mislabelled third-party goods (e.g. a Ninja product as "PeytonGhalib").
    if (!empty($item->brand)) {
        $schemaProduct['brand'] = ['@type' => 'Brand', 'name' => $item->brand];
    }
    if (!empty($item->sku))      { $schemaProduct['sku']      = $item->sku; }
    if (!empty($item->gtin))     { $schemaProduct['gtin']     = $item->gtin; }
    if (!empty($item->mpn))      { $schemaProduct['mpn']      = $item->mpn; }
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
        $breadcrumbItems[] = ['@type'=>'ListItem','position'=>3,'name'=>$item->category->name,'item'=>route('category.landing', $item->category->slug)];
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

/* ── Product tabs ────────────────────────────────────────────────────────
   Segmented pill control rather than the old underlined text row: the site
   now pills every button, and a lone underline tab bar read as unfinished.
   The track is a real element, so the active pill sits inside a defined
   shape instead of floating on the page.                                */
.pd-tabs-wrap{margin-bottom:2rem;overflow-x:auto;scrollbar-width:none;-ms-overflow-style:none}
.pd-tabs-wrap::-webkit-scrollbar{display:none}
.pd-tabs{display:inline-flex;align-items:center;gap:.25rem;padding:.3rem;background:#F4F1EC;border-radius:999px;min-width:max-content}
.pd-tab{display:inline-flex;align-items:center;gap:.5rem;white-space:nowrap;cursor:pointer;
  padding:.65rem 1.35rem;border-radius:999px;border:0;background:transparent;
  font-size:.875rem;font-weight:500;line-height:1;color:#6b7280;
  transition:background-color .25s ease,color .25s ease,box-shadow .25s ease}
.pd-tab i{font-size:.85rem;opacity:.85}
.pd-tab:hover{color:#bb976d;background:rgba(255,255,255,.7)}
.pd-tab.is-active{background:#bb976d;color:#fff;box-shadow:0 2px 8px rgba(187,151,109,.35)}
.pd-tab.is-active:hover{background:#ad8a61;color:#fff}
.pd-tab:focus-visible{outline:2px solid #bb976d;outline-offset:2px}
.pd-tab__badge{display:inline-flex;align-items:center;justify-content:center;min-width:1.3rem;height:1.3rem;
  padding:0 .35rem;border-radius:999px;background:rgba(0,0,0,.07);color:inherit;font-size:.7rem;font-weight:600;line-height:1}
.pd-tab.is-active .pd-tab__badge{background:rgba(255,255,255,.25)}

.dark .pd-tabs{background:rgba(255,255,255,.06)}
.dark .pd-tab{color:rgba(255,255,255,.6)}
.dark .pd-tab:hover{background:rgba(255,255,255,.1);color:#fff}
.dark .pd-tab.is-active{color:#fff}
.dark .pd-tab__badge{background:rgba(255,255,255,.15)}

@media (max-width:480px){
  .pd-tab{padding:.6rem 1rem;font-size:.8rem}
  .pd-tab i{font-size:.78rem}
}
@media (prefers-reduced-motion:reduce){.pd-tab{transition:none}}

/* Heading used where a tab bar would hold a single tab. */
.pd-section-title{display:flex;align-items:center;gap:.6rem;font-size:1.15rem;font-weight:700;color:#1f2937;margin-bottom:1.5rem}
.pd-section-title i{color:#bb976d;font-size:1rem}
.dark .pd-section-title{color:#fff}

/* Reviews are their own section now; pull it up under the tabs so the two do
   not read as unrelated blocks separated by a gap. */
.pd-reviews{padding-bottom:3.125rem;scroll-margin-top:5rem}

/* ── Review grid ─────────────────────────────────────────────────────────
   Masonry on desktop via CSS columns: the cards are wildly uneven in height
   (some carry a photo, some are two lines) and a row-based grid would leave
   a ragged band of whitespace under every short card. Columns pack them.
   On mobile the same markup becomes a horizontal snap slider, since three
   narrow columns stacked would bury the rest of the page.               */
.pd-rev-head{display:flex;align-items:center;flex-wrap:wrap;gap:.5rem .75rem;padding-bottom:1rem;border-bottom:1px solid #e5e7eb}
.pd-rev-head__title{font-size:1.05rem;font-weight:600;color:#1f2937}
.pd-rev-head__sep{color:#d1d5db}
.pd-rev-head__score{font-size:1.05rem;font-weight:700;color:#1f2937}
.pd-rev-head__stars{display:inline-flex;align-items:center;gap:1px;font-size:.8rem}
.pd-rev-head__count{font-size:.8rem;color:#6b7280}
.pd-rev-head__verified{display:inline-flex;align-items:center;gap:.3rem;font-size:.8rem;color:#15803d}

.pd-rev-bar{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin:1rem 0 1.25rem}
.pd-rev-tab{display:inline-block;font-size:.85rem;font-weight:600;color:#1f2937;padding-bottom:.5rem;border-bottom:2px solid #1f2937}
.pd-rev-filter{position:relative;flex:0 1 260px;min-width:180px}
.pd-rev-filter__input{width:100%;font-size:.85rem;padding:.5rem 2.25rem .5rem .75rem;border:1px solid #d1d5db;border-radius:999px;outline:none;background:#fff;color:#1f2937}
.pd-rev-filter__input:focus{border-color:#bb976d}
.pd-rev-filter__icon{position:absolute;top:50%;right:.85rem;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none}

.pd-rev-grid{columns:3;column-gap:1rem}
.pd-rev-card{break-inside:avoid;-webkit-column-break-inside:avoid;page-break-inside:avoid;
  display:inline-block;width:100%;background:#f7f7f7;border-radius:.5rem;padding:1rem;margin-bottom:1rem}
/* is-hidden = held back by the "view more" cap (desktop only).
   is-filtered = ruled out by the keyword search (every breakpoint). */
.pd-rev-card.is-hidden,.pd-rev-card.is-filtered{display:none}
.pd-rev-card__top{display:flex;align-items:center;justify-content:space-between;gap:.5rem;margin-bottom:.5rem}
.pd-rev-card__stars{display:inline-flex;align-items:center;gap:1px;font-size:.75rem}
.pd-rev-card__date{font-size:.72rem;color:#9ca3af}
.pd-rev-card__text{font-size:.82rem;line-height:1.55;color:#374151;margin:0}
.pd-rev-card__img{margin-top:.75rem;width:100%;max-width:150px;height:auto;aspect-ratio:1/1;object-fit:cover;border-radius:.35rem;background:#ececec}
.pd-rev-card__foot{display:flex;align-items:center;gap:.4rem;margin-top:.85rem}
.pd-rev-card__avatar{display:inline-flex;align-items:center;justify-content:center;width:1.55rem;height:1.55rem;border-radius:50%;
  color:#fff;font-size:.72rem;font-weight:600;flex-shrink:0;line-height:1}
.pd-rev-card__name{font-size:.78rem;color:#4b5563;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
/* The flag is a trust signal — where the reviewer is — so it is sized to be
   read rather than squinted at, and outlined so it holds its own against the
   grey card. Drawn as SVG, not emoji: Windows has no flag glyphs and would
   render the bare letters "US" instead. */
.pd-rev-card__flag{display:inline-flex;align-items:center;line-height:0;flex-shrink:0}
.pd-flag{width:22px;height:15.4px;display:block;border-radius:2px;
  box-shadow:0 0 0 1px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.15)}
.dark .pd-flag{box-shadow:0 0 0 1px rgba(255,255,255,.25),0 1px 2px rgba(0,0,0,.4)}
.pd-rev-card__tick{font-size:.72rem;color:#15803d}

/* ── Write a review ──────────────────────────────────────────────────────
   Framed as a card rather than left as loose fields under a rule: asking for
   a review is a request, and a request reads better inside something that
   looks deliberately built for it.                                       */
.pd-write{margin-top:2.5rem;background:linear-gradient(180deg,#FAF7F2 0%,#F4F1EC 100%);
  border:1px solid #EAE3D9;border-radius:1rem;padding:1.75rem}
.pd-write__head{display:flex;align-items:flex-start;gap:.9rem;margin-bottom:1.5rem}
.pd-write__icon{display:inline-flex;align-items:center;justify-content:center;width:2.6rem;height:2.6rem;
  flex-shrink:0;border-radius:50%;background:#bb976d;color:#fff;font-size:1rem}
.pd-write__title{font-size:1.15rem;font-weight:700;color:#1f2937;line-height:1.2;margin:0}
.pd-write__sub{font-size:.82rem;color:#6b7280;margin:.3rem 0 0}
.pd-write__form{display:flex;flex-direction:column;gap:1.25rem}
.pd-write__field{display:flex;flex-direction:column}
.pd-write__label{display:flex;align-items:center;gap:.4rem;font-size:.85rem;font-weight:600;color:#374151;margin-bottom:.6rem}
.pd-write__req{color:#dc2626}
.pd-write__opt{font-size:.7rem;font-weight:500;color:#9ca3af;background:rgba(0,0,0,.05);padding:.1rem .45rem;border-radius:999px}

/* Stars sized to be tapped, not aimed at — the old 32px targets were below
   the 44px touch guidance and sat flat against the page. */
.pd-stars{display:flex;align-items:center;gap:.15rem;flex-wrap:wrap}
.pd-star{width:2.5rem;height:2.5rem;padding:.35rem;border:0;background:transparent;cursor:pointer;
  color:#D8D5D0;transition:transform .15s ease,color .15s ease}
.pd-star svg{width:100%;height:100%;display:block}
.pd-star:hover{transform:scale(1.15)}
.pd-star:focus-visible{outline:2px solid #bb976d;outline-offset:2px;border-radius:.35rem}
.pd-stars__verdict{margin-left:.6rem;font-size:.82rem;font-weight:600;color:#bb976d;min-height:1.2em}

.pd-write__textarea{width:100%;font-size:.875rem;line-height:1.6;color:#1f2937;background:#fff;
  border:1px solid #E2DACE;border-radius:.6rem;padding:.85rem 1rem;outline:none;resize:vertical;min-height:7rem;
  transition:border-color .2s ease,box-shadow .2s ease}
.pd-write__textarea::placeholder{color:#b6b2ab}
.pd-write__textarea:focus{border-color:#bb976d;box-shadow:0 0 0 3px rgba(187,151,109,.15)}
.pd-write__count{align-self:flex-end;font-size:.7rem;color:#9ca3af;margin-top:.35rem}
.pd-write__error{font-size:.75rem;color:#dc2626;margin-top:.4rem}

.pd-write__submit{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;align-self:flex-start;
  padding:.8rem 1.9rem;border:0;border-radius:999px;cursor:pointer;
  background:#bb976d;color:#fff;font-size:.875rem;font-weight:600;text-decoration:none;
  box-shadow:0 2px 10px rgba(187,151,109,.35);transition:background-color .2s ease,transform .15s ease,box-shadow .2s ease}
.pd-write__submit:hover{background:#a8845a;color:#fff;transform:translateY(-1px);box-shadow:0 4px 14px rgba(187,151,109,.45)}
.pd-write__submit:active{transform:translateY(0)}
.pd-write__submit:focus-visible{outline:2px solid #1f2937;outline-offset:2px}
.pd-write__gate{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.pd-write__gate-text{font-size:.875rem;color:#4b5563;margin:0}

.dark .pd-write{background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.1)}
.dark .pd-write__title{color:#fff}
.dark .pd-write__label{color:rgba(255,255,255,.85)}
.dark .pd-write__textarea{background:rgba(0,0,0,.2);border-color:rgba(255,255,255,.15);color:#fff}
.dark .pd-write__gate-text{color:rgba(255,255,255,.7)}
.dark .pd-star{color:rgba(255,255,255,.2)}

@media (max-width:640px){
  .pd-write{padding:1.25rem;border-radius:.75rem}
  .pd-write__submit{width:100%;align-self:stretch}
  .pd-stars__verdict{width:100%;margin-left:0;margin-top:.25rem}
}
@media (prefers-reduced-motion:reduce){
  .pd-star,.pd-write__submit{transition:none}
  .pd-star:hover,.pd-write__submit:hover{transform:none}
}

.pd-rev-empty{font-size:.85rem;color:#9ca3af;font-style:italic;padding:1.5rem 0;text-align:center}
.pd-rev-more-wrap{display:flex;justify-content:center;margin-top:1.25rem}
.pd-rev-more{font-size:.8rem;color:#3b82f6;background:#fff;border:1px solid #93c5fd;border-radius:999px;padding:.5rem 1.25rem;cursor:pointer;transition:background .2s,color .2s}
.pd-rev-more:hover{background:#eff6ff}
.pd-rev-more:focus-visible{outline:2px solid #bb976d;outline-offset:2px}

.dark .pd-rev-head{border-bottom-color:rgba(255,255,255,.12)}
.dark .pd-rev-head__title,.dark .pd-rev-head__score,.dark .pd-rev-tab{color:#fff}
.dark .pd-rev-tab{border-bottom-color:#fff}
.dark .pd-rev-card{background:rgba(255,255,255,.05)}
.dark .pd-rev-card__text{color:rgba(255,255,255,.75)}
.dark .pd-rev-card__name{color:rgba(255,255,255,.6)}
.dark .pd-rev-filter__input{background:transparent;border-color:rgba(255,255,255,.2);color:#fff}
.dark .pd-rev-more{background:transparent}

@media (max-width:1023.98px){.pd-rev-grid{columns:2}}

/* Mobile: one full-width card per row.
   This was a sideways snap slider. Stacking is the better call: a slider hides
   how many reviews exist, can't be skimmed, and competes with the page's own
   vertical scroll. Full width also means no card is ever clipped mid-sentence,
   which is what the narrow cards were doing. The "view more" cap comes back
   here precisely because the list is now vertical and would otherwise run long. */
@media (max-width:767.98px){
  .pd-rev-grid{columns:auto;display:flex;flex-direction:column;gap:.75rem;overflow:visible;margin-inline:0;padding-inline:0}
  .pd-rev-card{width:100%;max-width:100%;margin-bottom:0;padding:16px}
  .pd-rev-card__text{font-size:.9375rem;line-height:1.6}
  .pd-rev-card__img{max-width:120px}
  .pd-rev-bar{gap:.75rem}
  .pd-rev-filter{flex:1 1 100%}
  .pd-rev-head{gap:.4rem .6rem}
  .pd-rev-head__title,.pd-rev-head__score{font-size:1rem}
}
@media (prefers-reduced-motion:reduce){.pd-rev-grid{scroll-behavior:auto}}
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
                    {{-- Plain text, not a heading: this search-popup label otherwise
                         becomes an <h4> ahead of the product's real <h1>. --}}
                    <p class="font-medium leading-none">Popular Tags</p>
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
            @if($item->category)
            <li><a href="{{ route('category.landing', $item->category->slug) }}">{{ $item->category->name }}</a></li>
            <li>/</li>
            @endif
            <li class="text-primary">{{ $item->name }}</li>
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
    $galleryImages = collect([['src' => $primarySrc, 'type' => 'image']]);
    // Map a colour name (lowercased) to the gallery slide index that shows it,
    // so selecting a swatch can jump the slider to that colour's photo. Slide 0
    // is the primary image; gallery images follow. First image per colour wins.
    // Video slides are never matched to a colour.
    $colorImageMap = [];
    if (!empty($item->image_color)) {
        $colorImageMap[strtolower(trim($item->image_color))] = 0;
    }
    foreach ($item->productImages as $pi) {
        $galleryImages->push(['src' => Storage::url($pi->image), 'type' => $pi->isVideo() ? 'video' : 'image']);
        if (!$pi->isVideo() && !empty($pi->color)) {
            $key = strtolower(trim($pi->color));
            if (!isset($colorImageMap[$key])) {
                $colorImageMap[$key] = $galleryImages->count() - 1;
            }
        }
    }
    $savingsAmt = $item->sale_price ? number_format($item->price - $item->sale_price, 2) : null;
    $activePrice = number_format($item->effective_price, 2);

    // Per-option pricing/stock the storefront JS uses to update price & availability
    // when a colour/size is picked. Keyed by lowercased value within each dimension.
    // 'base' is the product's own price/stock, used when a selection has no variant.
    $pdVariants = ['color' => [], 'size' => [], 'base' => [
        'now'   => (float) $item->effective_price,
        'was'   => $item->has_strike ? (float) $item->price : null,
        'stock' => (int) $item->stock,
    ]];
    foreach ($item->variants as $v) {
        if (!$v->is_active || !in_array($v->type, ['color', 'size'], true)) {
            continue;
        }
        $pdVariants[$v->type][strtolower(trim($v->value))] = [
            'now'   => (float) $v->effective_price,
            'was'   => $v->has_strike ? (float) $v->price : null,
            'stock' => (int) $v->stock,
        ];
    }
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
@media (max-width: 767.98px) { .pd-img-panel { position: static; } }
/* Grid items are min-width:auto by default, so this panel would not shrink below
   the min-content width of the thumbnail strip inside it. On a product with five
   thumbnails that forced the single grid track to 592px inside a 341px screen and
   pushed the whole page sideways — invisible on products with three thumbs, which
   is why it only showed up on the live catalogue.

   min-width:0 lets the track shrink; .pd-thumbs already scrolls on its own axis,
   so the strip stays fully reachable. Scoped below 1200px because the desktop
   grid (1fr 500px) has room for the strip at its natural width.

   The .98 on every max-width bound in this file is deliberate: a fractional-DPI
   display reports a non-integer viewport (1199.32 at 150% scaling), where a
   plain 'max-width: 1199px' and the matching 'min-width: 1200px' are both false
   and no rule at all applies. */
@media (max-width: 1199.98px) { .pd-img-panel { min-width: 0; } }

.pd-main-wrap {
    position: relative;
    background: #fff;
    overflow: hidden;
    aspect-ratio: 1/1;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
@media (min-width: 768px) { .pd-main-wrap { aspect-ratio: 1/1; } }
/* Sliding image track. Pinned with absolute inset (not percentage heights) so
   iOS Safari sizes it reliably — the height:100% chain collapses the image on iOS. */
.pd-slides {
    position: absolute;
    inset: 0;
    display: flex;
    transition: transform .35s cubic-bezier(.4, 0, .2, 1);
}
.pd-slide {
    position: relative;
    min-width: 100%;
    flex-shrink: 0;
}
.pd-slide-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

/* ── Zoom ──
   The scale goes on the <img>, never on .pd-slides: that track carries the
   translateX the carousel animates, and a second transform on the same element
   would overwrite it. .pd-main-wrap already clips (overflow:hidden), so the
   magnified image stays inside the frame.
   Only <img> is targeted — a <video> shares .pd-slide-img but must not zoom,
   since scaling it would put its own controls out of reach. */
img.pd-slide-img {
    transition: transform .25s ease;
    cursor: zoom-in;
}
img.pd-slide-img.is-zoomed {
    cursor: zoom-out;
    will-change: transform;
}
/* Inset offset: .pd-main-wrap clips with overflow:hidden, so an outward ring
   on an inset:0 image would be cut off and invisible. */
img.pd-slide-img:focus-visible {
    outline: 3px solid #bb976d;
    outline-offset: -3px;
}
@media (prefers-reduced-motion: reduce) {
    img.pd-slide-img { transition: none; }
}

/* Thumbnail strip */
.pd-thumbs {
    display: flex; gap: 10px;
    padding: 12px 0 0;
    overflow-x: auto; scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.pd-thumbs::-webkit-scrollbar { display: none; }
/* Thumbs are <button> now rather than bare <img>/<div>, so the strip is
   reachable by keyboard. The box is reset to look exactly as it did before —
   the visual design is unchanged, only the semantics are. */
.pd-thumb {
    width: 76px; height: 76px; min-width: 76px;
    cursor: pointer;
    border-radius: 10px;
    border: 2.5px solid transparent;
    outline: 1px solid #e5e7eb;
    transition: border-color .2s, outline-color .2s, opacity .2s;
    opacity: .7;
    padding: 0;
    background: none;
    overflow: hidden;
}
.pd-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.pd-thumb:hover { opacity: .9; border-color: #d1b896; outline-color: #d1b896; }
.pd-thumb.active { border-color: #bb976d; outline-color: #bb976d; opacity: 1; }
/* Without this the strip is tabbable but gives no sign of where focus is. */
.pd-thumb:focus-visible { outline: 2px solid #bb976d; outline-offset: 2px; opacity: 1; }
.pd-thumb-video {
    display: flex; align-items: center; justify-content: center;
    background: #172430; color: #fff; font-size: 22px;
}
.pd-dot { border: none; padding: 0; }
.pd-dot:focus-visible { outline: 2px solid #bb976d; outline-offset: 3px; }

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
    /* 50px already rendered as a pill at this height; stated as 999px so every
       button and control on the site carries one value. overflow:hidden below
       is what keeps the inner buttons' hover fill inside the curve. */
    border-radius: 999px;
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
    border: none; border-radius: 999px; cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 4px 14px rgba(23,36,48,.25);
}
.pd-btn-cart:hover { background: #0f1e2e; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(23,36,48,.35); }
.pd-btn-cart:active { transform: translateY(0); }

.pd-btn-wish {
    width: 100%; height: 46px;
    background: transparent;
    color: #555; font-size: .8rem; font-weight: 600;
    border: 1.5px solid #e5e7eb; border-radius: 999px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: border-color .2s, color .2s, background .2s;
}
.pd-btn-wish:hover { border-color: #bb976d; color: #bb976d; background: #fdf8f2; }
/* Height moved out of an inline style so the mobile block can override it —
   an inline height would have needed !important to beat. */
.wishlist-toggle-btn { height: 60px; }

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

/* Delivery estimate — same cream tile language as .pd-trust-item, laid out as a
   row because the date needs room to breathe and must not wrap mid-range. */
.pd-delivery {
    display: flex; align-items: flex-start; gap: 10px;
    background: #f9f7f4;
    border: 1px solid #ede8e0;
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 16px;
}
.pd-delivery svg { flex: none; margin-top: 1px; }
.pd-delivery__head { font-size: 13px; color: #2b2b2b; line-height: 1.45; margin: 0; }
.pd-delivery__head strong { color: #8a6a3f; font-weight: 600; white-space: nowrap; }
.pd-delivery__sub  { font-size: 11.5px; color: #777; line-height: 1.4; margin: 3px 0 0; }

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

/* ── Mobile: above-the-fold priority ──
   The square media used to fill the first screen, pushing the title, price and
   Add to Cart below the fold. The fixed bottom bar keeps price + Add to Cart
   reachable at all times. Desktop is unaffected — its two-column layout already
   shows everything up top. */
.pd-sticky-bar { display: none; }
@media (max-width: 767.98px) {
    /* Was 4/3 to buy vertical space, but 4/3 is landscape and product photos are
       portrait, so object-fit:contain letterboxed them — a 310px-wide box showed
       a 211px photo between two fat white bars. Squarer costs ~75px of scroll and
       the sticky bar already guarantees the CTA is reachable, so the trade was
       paying for above-fold space with a hero image that looked broken.
       Cropping instead is not an option: this is the product, and cutting its
       edges off to fill a box is worse than a little letterboxing. */
    .pd-main-wrap { aspect-ratio: 1/1; max-height: 62vh; }
    body { padding-bottom: 74px; }              /* room so the bar never covers content */

    /* ── Typography ──
       Desktop sizes were rendering unchanged on a 371px screen. */
    h1.font-bold { font-size: 1.375rem; line-height: 1.3; }   /* 32px → 22px */
    .pd-info h2, .pd-sec-title, h2.text-xl { font-size: 1.125rem; }  /* 24px → 18px */
    .pd-info p, .pd-desc { font-size: .9375rem; }             /* body 16 → 15px */

    /* ── Price ── */
    #pd-price-now { font-size: 1.625rem; }                    /* 32px → 26px */
    .pd-price-block { padding: 8px 12px; border-radius: 0 8px 8px 0; margin-bottom: 8px; }

    /* ── Rating: stars and count stay on one line ── */
    .pd-rating-row { flex-wrap: nowrap; white-space: nowrap; gap: .4rem; }
    .pd-rating-row > * { flex-shrink: 0; }

    /* ── Buy controls ──
       Add to Cart takes the full width, wishlist sits under it (markup stacks
       the row at this breakpoint). Both land in the 48–52px band; the qty
       buttons stay 44px because they are the only controls needing a precise
       tap, and shrinking them to match would drop under a comfortable target. */
    .pd-btn-cart {
        height: 50px; font-size: 1rem; font-weight: 500; letter-spacing: .04em;
        box-shadow: 0 3px 10px rgba(23,36,48,.20);
    }
    .wishlist-toggle-btn, .pd-btn-wish { height: 48px; font-size: 1rem; font-weight: 500; }
    .pd-qty-wrap { border-width: 1px; }
    .pd-qty-btn { width: 44px; height: 44px; font-size: 16px; }

    /* ── Gallery: keep the arrows off the product ── */
    .pd-nav-btn, .pd-arrow { width: 34px; height: 34px; }
    .pd-nav-prev, .pd-arrow-prev { left: 6px; }
    .pd-nav-next, .pd-arrow-next { right: 6px; }

    /* ── Cards ── */
    .pd-card, .pd-info-panel, .pd-write { padding: 16px; }

    /* ── Frequently bought together ──
       Rows stack (the wrapper switches to a column at this breakpoint), the
       thumbnails come down from 64px so the name gets the width, and the submit
       spans the card instead of sitting in a short pill beside the total. */
    .pd-fbt-foot #fbt-submit,
    #fbt-submit { width: 100%; padding-inline: 16px; height: 50px; font-size: 1rem; font-weight: 500; }
    #pd-fbt img, .pd-fbt img { width: 52px; height: 52px; }

    /* ── Write a review ── */
    .pd-write__textarea { font-size: 1rem; }   /* 16px stops iOS zooming on focus */
    .pd-write__head { gap: .7rem; margin-bottom: 1.1rem; }
    .pd-write__icon { width: 2.2rem; height: 2.2rem; }
    .pd-star { width: 2.35rem; height: 2.35rem; }

    .pd-sticky-bar {
        display: flex; align-items: center; gap: 12px;
        position: fixed; left: 0; right: 0; bottom: 0; z-index: 9990;
        padding: 10px 14px calc(10px + env(safe-area-inset-bottom, 0px));
        background: #fff; border-top: 1px solid #ece7df;
        box-shadow: 0 -6px 20px rgba(0,0,0,.10);
    }
    .dark .pd-sticky-bar { background: #172430; border-color: #2f3b45; }
    .pd-sticky-bar__price { display: flex; flex-direction: column; line-height: 1.12; flex: none; }
    .pd-sticky-bar__now { font-size: 18px; font-weight: 800; color: #172430; }
    .dark .pd-sticky-bar__now { color: #fff; }
    .pd-sticky-bar__was { font-size: 12px; color: #9aa0a6; text-decoration: line-through; }
    .pd-sticky-bar__btn {
        flex: 1; min-height: 50px; border: 0; border-radius: 999px; cursor: pointer;
        background: #172430; color: #fff; font-weight: 700; font-size: 14px;
        letter-spacing: .04em; text-transform: uppercase;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    }
    .pd-sticky-bar__btn:active { transform: scale(.99); }
    .pd-sticky-bar__btn[disabled] { opacity: .5; cursor: not-allowed; }
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
                                // 'OFF' => '10% OFF' and 'OFF1' => '15% OFF' were fixed
                                // strings unrelated to the product's actual prices, so a
                                // product at full price could still advertise 15% off.
                                // Real figure now, and no badge when there is no discount.
                                $badgeBg    = match($item->tag) { 'Sale'=>'#1CB28E','NEW'=>'#9739E1', default=>'#E13939' };
                                $badgeLabel = match($item->tag) {
                                    'Sale'  => 'Hot Sale',
                                    'NEW'   => 'NEW',
                                    default => $item->discount_percent ? $item->discount_percent.'% OFF' : null,
                                };
                            @endphp
                            @if($badgeLabel)
                            <span class="pd-badge absolute top-4 left-4 z-10" style="background:{{ $badgeBg }}">{{ $badgeLabel }}</span>
                            @endif
                        @elseif($item->sale_price)
                            <span class="pd-badge absolute top-4 left-4 z-10" style="background:#E13939">Sale</span>
                        @endif

                        {{-- Slides --}}
                        <div id="pd-slides" class="pd-slides">
                            @foreach($galleryImages as $ti => $slide)
                            <div class="pd-slide" role="group" aria-roledescription="slide"
                                 aria-label="Image {{ $ti + 1 }} of {{ $galleryImages->count() }}">
                                @if($slide['type'] === 'video')
                                <video src="{{ $slide['src'] }}" class="pd-slide-img" controls playsinline muted loop preload="metadata"></video>
                                @else
                                {{-- Slide 0 is this page's LCP element: never lazy, and flagged
                                     high so it is fetched ahead of everything else. Every other
                                     slide sits off-screen behind a transform, so eager-loading
                                     them only stole bandwidth from the one image the customer
                                     can actually see. --}}
                                {{-- role/tabindex rather than a <button> wrapper: the image is
                                     position:absolute inset:0 inside the slide, so wrapping it
                                     would break that layout. The label keeps the product name
                                     so nothing the alt conveyed is lost, and adds the action.
                                     Only the visible slide is in the tab order — goTo() moves
                                     the 0 as slides change (roving tabindex), otherwise Tab
                                     would walk through images nobody can see. --}}
                                <img src="{{ $slide['src'] }}"
                                     alt="{{ $item->name }} image {{ $ti + 1 }}"
                                     class="pd-slide-img"
                                     role="button"
                                     tabindex="{{ $ti === 0 ? '0' : '-1' }}"
                                     aria-pressed="false"
                                     aria-label="Zoom {{ $item->name }} image {{ $ti + 1 }}"
                                     @if($ti === 0) fetchpriority="high" @else loading="lazy" @endif
                                     decoding="async">
                                @endif
                            </div>
                            @endforeach
                        </div>

                        {{-- Screen readers get no notice of a transform-based slide change,
                             so the current position is announced here instead. --}}
                        <p id="pd-slide-status" class="sr-only" role="status" aria-live="polite">
                            Image 1 of {{ $galleryImages->count() }}
                        </p>

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
                            <button type="button" class="pd-dot" data-index="{{ $ti }}"
                                    aria-label="Show image {{ $ti + 1 }}"
                                    @if($ti === 0) aria-current="true" @endif
                                    style="width:{{ $ti===0?'20px':'8px' }};height:8px;border-radius:50px;cursor:pointer;transition:all .3s;
                                           background:{{ $ti===0?'#bb976d':'rgba(255,255,255,.7)' }};"></button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Thumbnail strip.
                         Real <button>s: these were an <img> and a <div> with click
                         handlers, so a keyboard user could never reach them — the
                         prev/next arrows were the only way through the gallery.
                         The alt is empty because the button already carries the
                         label; leaving both makes screen readers say it twice. --}}
                    <div class="pd-thumbs" aria-label="Product image thumbnails">
                        @foreach($galleryImages as $ti => $slide)
                        <button type="button"
                                class="pd-thumb {{ $slide['type'] === 'video' ? 'pd-thumb-video' : '' }} {{ $ti === 0 ? 'active' : '' }}"
                                data-index="{{ $ti }}"
                                @if($ti === 0) aria-current="true" @endif
                                aria-label="{{ $slide['type'] === 'video' ? 'Show product video' : 'Show image ' . ($ti + 1) }}">
                            @if($slide['type'] === 'video')
                            <i class="mdi mdi-play" aria-hidden="true"></i>
                            @else
                            <img src="{{ $slide['src'] }}" alt="" loading="lazy" decoding="async">
                            @endif
                        </button>
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
                        @php $pdInStock = ($item->stock ?? 1) > 0; @endphp
                        <span id="pd-stock-badge"
                              data-in="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full"
                              data-out="text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-full"
                              class="{{ $pdInStock ? 'inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full' : 'text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-full' }}">
                            @if($pdInStock)<span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block"></span> In Stock @else Out of Stock @endif
                        </span>
                    </div>

                    {{-- Product Name H1 --}}
                    <h1 class="font-bold text-2xl sm:text-[1.75rem] text-[#172430] leading-snug mb-1" style="line-height:1.25">
                        {{ $item->name }}
                    </h1>

                    {{-- SKU --}}
                    @if($item->sku)
                    <p class="text-xs text-gray-400 mb-1">SKU: <span class="text-gray-600 font-medium">{{ $item->sku }}</span></p>
                    @endif

                    {{-- Stars --}}
                    @php
                        $avgR = $item->reviews_avg_rating ?? 0;
                        $revC = $item->reviews_count ?? 0;
                    @endphp
                    {{-- Rating row hidden until the product actually has reviews — no
                         "0.0 · 0 reviews" placeholder. --}}
                    @if($revC > 0)
                    <div class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-0.5">
                            @for($s=1;$s<=5;$s++)
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="{{ $s <= round($avgR) ? '#F59E0B' : '#E5E7EB' }}">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-gray-700">{{ number_format($avgR,1) }}</span>
                        <span class="text-sm text-gray-400">· {{ $revC }} {{ Str::plural('review',$revC) }}</span>
                        {{-- Reviews are their own section now, so this is a plain
                             anchor jump — no tab to activate first. --}}
                        <a href="#reviews" class="text-xs text-[#bb976d] hover:underline ml-1">See all</a>
                    </div>
                    @endif

                    {{-- Price block — stable DOM updated by the variant JS when a
                         colour/size with its own price is selected. Initialised with
                         the product's base price. --}}
                    @php
                        $baseNow = (float) $item->effective_price;
                        $baseWas = $item->has_strike ? (float) $item->price : null;
                    @endphp
                    <div class="pd-price-block mb-2">
                        <div class="flex items-center gap-3 flex-wrap mb-1">
                            <span id="pd-price-now" class="text-[2rem] font-extrabold text-[#172430] leading-none">${{ number_format($baseNow, 2) }}</span>
                            <span id="pd-price-was" class="text-base text-gray-400 line-through font-medium" style="{{ $baseWas ? '' : 'display:none' }}">${{ number_format($baseWas ?? 0, 2) }}</span>
                            <span id="pd-price-badge" class="text-sm font-bold text-white rounded-full px-3 py-1" style="background:#E13939;{{ $baseWas ? '' : 'display:none' }}"></span>
                        </div>
                        <p id="pd-price-save" class="text-xs font-semibold" style="color:#1CB28E;{{ $baseWas ? '' : 'display:none' }}"></p>
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
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Size</p>
                            @if(!empty($item->size_chart))
                            <button type="button" id="sizeGuideBtn"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#8A6A3F] underline underline-offset-2 hover:text-[#6e532f] cursor-pointer">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 8h20v8H2z"/><path d="M6 8v3M10 8v2M14 8v3M18 8v2"/></svg>
                                Size guide
                            </button>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-2" id="size-options">
                            @foreach($item->sizes as $si => $sz)
                            <label class="cursor-pointer">
                                <input class="appearance-none hidden size-radio" type="radio" name="size_display" value="{{ $sz }}" {{ $si===0?'checked':'' }}>
                                <span class="pd-variant-pill {{ $si===0?'active':'' }}">{{ $sz }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @elseif(!empty($item->size_chart))
                    {{-- Product has a size chart but no size variants — still expose the guide --}}
                    <div class="mb-4">
                        <button type="button" id="sizeGuideBtn"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#8A6A3F] underline underline-offset-2 hover:text-[#6e532f] cursor-pointer">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 8h20v8H2z"/><path d="M6 8v3M10 8v2M14 8v3M18 8v2"/></svg>
                            Size guide
                        </button>
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

                        {{-- Stacked on phones so Add to Cart gets the full width and
                             the secondary action sits under it, side by side from sm up. --}}
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
                            <div class="flex-1 w-full">
                                <button type="submit" id="pd-add-btn" class="pd-btn-cart w-full">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:8px;margin-top:-2px">
                                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                    </svg>
                                    <span id="pd-add-btn-label">Add to Cart</span>
                                </button>
                            </div>
                            <div class="flex-1 w-full">
                                <button type="button"
                                        class="wishlist-toggle-btn w-full flex items-center justify-center gap-2 border border-gray-200 rounded-xl text-gray-600 text-sm font-semibold hover:border-[#bb976d] hover:text-[#bb976d] hover:bg-[#fdf8f2] transition-all duration-200"
                                        style="background:transparent;cursor:pointer;"
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

                    {{-- Delivery estimate.
                         Dates are computed from config('checkout.delivery') — the
                         same numbers the shipping policy page renders — so the
                         window quoted here can never contradict the policy.
                         Worded as an estimate, not a guarantee: business days skip
                         weekends but not public holidays (see DeliveryEstimate). --}}
                    <div class="pd-delivery">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                        <div>
                            <p class="pd-delivery__head">
                                Free delivery, estimated
                                <strong>{{ $delivery['earliest']->format('D j M') }}</strong>
                                @unless($delivery['earliest']->isSameDay($delivery['latest']))
                                    – <strong>{{ $delivery['latest']->format('D j M') }}</strong>
                                @endunless
                            </p>
                            <p class="pd-delivery__sub">
                                {{ $delivery['min_days'] }}–{{ $delivery['max_days'] }} business days · Free on every order, no minimum
                            </p>
                        </div>
                    </div>

                    {{-- 3 trust tiles --}}
                    <div class="pd-trust-row">
                        <div class="pd-trust-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                            <span>Free shipping<br>on all orders</span>
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

{{-- Mobile sticky Add to Cart: submits the main #pd-cart-form (so selected qty /
     size / colour carry over) and triggers the same confirmation modal. --}}
<div class="pd-sticky-bar" aria-hidden="false">
    <div class="pd-sticky-bar__price">
        <span id="pd-sticky-now" class="pd-sticky-bar__now">${{ $activePrice }}</span>
        <span id="pd-sticky-was" class="pd-sticky-bar__was" style="{{ $baseWas ? '' : 'display:none' }}">${{ number_format($baseWas ?? 0, 2) }}</span>
    </div>
    <button type="submit" form="pd-cart-form" id="pd-sticky-btn" class="pd-sticky-bar__btn"
            {{ $pdInStock ? '' : 'disabled aria-disabled=true' }}>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <span id="pd-sticky-btn-label">{{ $pdInStock ? 'Add to Cart' : 'Out of Stock' }}</span>
    </button>
</div>

<script>
(function(){
    // ── Carousel ──
    var slides   = document.getElementById('pd-slides');
    var thumbs   = document.querySelectorAll('.pd-thumb');
    var dots     = document.querySelectorAll('.pd-dot');
    var status   = document.getElementById('pd-slide-status');
    // Counted from the slides themselves rather than the thumbs: the slides are
    // what goTo actually moves, so anything that changes one strip without the
    // other can no longer put the index out of range.
    var total    = slides ? slides.children.length : 0;
    var current  = 0;

    function goTo(idx) {
        if(total === 0) return;

        // Drop any zoom before moving. Without this a magnified image stays
        // magnified as the track slides, so the next photo arrives already
        // scaled and off-centre — and on touch it would stay that way with no
        // hover-out to release it.
        clearAllZoom();

        current = (idx + total) % total;
        if(slides) slides.style.transform = 'translateX(-' + (current * 100) + '%)';

        // Pause any gallery video left behind so it doesn't keep playing off-screen,
        // then autoplay the one on the slide we just landed on (if any). Muted, since
        // autoplay without a user gesture is blocked by the browser otherwise.
        if(slides) {
            Array.prototype.forEach.call(slides.children, function(slideEl, i){
                // Roving tabindex: only the visible slide's image is reachable by
                // Tab. Leaving every image at 0 would make a keyboard user tab
                // through each off-screen photo to get past the gallery.
                var im = slideEl.querySelector('img.pd-slide-img');
                if (im) im.tabIndex = (i === current) ? 0 : -1;

                var v = slideEl.querySelector('video');
                if (!v) return;
                if (i === current) {
                    var playPromise = v.play();
                    if (playPromise && playPromise.catch) playPromise.catch(function(){});
                } else {
                    v.pause();
                }
            });
        }

        // sync thumbs
        thumbs.forEach(function(t, i){
            t.classList.toggle('active', i === current);
            if (i === current) { t.setAttribute('aria-current', 'true'); }
            else               { t.removeAttribute('aria-current'); }
        });

        // sync dots
        dots.forEach(function(d, i){
            d.style.width       = i === current ? '20px' : '8px';
            d.style.background  = i === current ? '#bb976d' : 'rgba(255,255,255,.7)';
            if (i === current) { d.setAttribute('aria-current', 'true'); }
            else               { d.removeAttribute('aria-current'); }
        });

        if (status) status.textContent = 'Image ' + (current + 1) + ' of ' + total;
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
        carousel.addEventListener('touchstart', function(e){ startX = e.touches[0].clientX; }, {passive:true});
        carousel.addEventListener('touchend', function(e){
            var diff = startX - e.changedTouches[0].clientX;
            if(Math.abs(diff) > 40) goTo(diff > 0 ? current + 1 : current - 1);
        }, {passive:true});
    }

    // Arrow keys once focus is anywhere in the gallery. Bound to the thumb strip
    // as well as the carousel: the strip is a sibling of the carousel, not a
    // child, so a keydown on a focused thumbnail never reaches the carousel.
    function onGalleryKey(e){
        var img    = currentZoomable();
        var zoomed = !!(img && img.classList.contains('is-zoomed'));

        // Enter/Space toggles zoom, but only while the image itself has focus —
        // otherwise Space on a focused thumbnail would zoom instead of
        // activating that thumbnail.
        if ((e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') && e.target === img) {
            e.preventDefault();
            if (zoomed) {
                clearZoom(img);
                announce('Zoom off.');
            } else {
                // Centre, because there is no pointer to take a position from.
                setZoom(img, 50, 50);
                announce('Zoomed in. Arrow keys pan, Escape exits.');
            }
            return;
        }

        if (e.key === 'Escape' && zoomed) {
            e.preventDefault();
            clearZoom(img);
            announce('Zoom off.');
            return;
        }

        // While zoomed the arrows pan the magnified image rather than changing
        // slide — moving to another photo mid-inspection is never what someone
        // examining detail is asking for. Escape or toggling off returns the
        // arrows to slide navigation.
        if (zoomed) {
            var STEP = 10;
            switch (e.key) {
                case 'ArrowLeft':  e.preventDefault(); panZoom(img, -STEP, 0); return;
                case 'ArrowRight': e.preventDefault(); panZoom(img,  STEP, 0); return;
                case 'ArrowUp':    e.preventDefault(); panZoom(img, 0, -STEP); return;
                case 'ArrowDown':  e.preventDefault(); panZoom(img, 0,  STEP); return;
            }
        }

        if (e.key === 'ArrowLeft')  { e.preventDefault(); goTo(current - 1); }
        if (e.key === 'ArrowRight') { e.preventDefault(); goTo(current + 1); }
    }
    var thumbStrip = document.querySelector('.pd-thumbs');
    if (carousel)   carousel.addEventListener('keydown', onGalleryKey);
    if (thumbStrip) thumbStrip.addEventListener('keydown', onGalleryKey);

    // ── Zoom ──
    // Desktop pans the magnified image under the cursor; touch has no hover, so
    // there it is a tap-to-toggle instead. Both drive the same scale/origin pair,
    // so there is one behaviour to reason about rather than two.
    var ZOOM_SCALE  = 2.5;
    var finePointer = window.matchMedia
        ? window.matchMedia('(hover: hover) and (pointer: fine)').matches
        : false;

    // <video> is excluded by the selector, not by a branch — a zoomed video
    // would push its own controls outside the frame.
    function currentZoomable(){
        var slide = slides && slides.children[current];
        return slide ? slide.querySelector('img.pd-slide-img') : null;
    }

    // Spoken feedback for actions that change nothing visible to a screen reader.
    // Only the keyboard path calls this: hover-zoom fires on every mousemove and
    // would turn the live region into a stream of noise.
    function announce(msg){
        if (status) status.textContent = msg;
    }

    function clampPct(n){ return Math.max(0, Math.min(100, n)); }

    // The one place zoom is switched on. Pointer, touch and keyboard all end up
    // here, so there is a single definition of "zoomed" rather than three that
    // can drift. The origin is stashed on the element because keyboard panning
    // needs somewhere to read the current position back from — a pointer always
    // supplies a fresh coordinate, a key press does not.
    function setZoom(img, xPct, yPct){
        if (!img) return;
        var x = clampPct(xPct);
        var y = clampPct(yPct);

        img.style.transformOrigin = x + '% ' + y + '%';
        img.style.transform       = 'scale(' + ZOOM_SCALE + ')';
        img.classList.add('is-zoomed');
        img.setAttribute('aria-pressed', 'true');
        img.dataset.zx = x;
        img.dataset.zy = y;
    }

    function applyZoom(img, clientX, clientY){
        if (!img || !carousel) return;
        var rect = carousel.getBoundingClientRect();
        if (!rect.width || !rect.height) return;

        // Clamped inside setZoom: a pointer leaving the box mid-drag would
        // otherwise produce an origin outside 0–100% and throw the image off.
        setZoom(
            img,
            ((clientX - rect.left) / rect.width)  * 100,
            ((clientY - rect.top)  / rect.height) * 100
        );
    }

    // Keyboard has no pointer to follow, so it nudges the stored origin instead.
    function panZoom(img, dx, dy){
        if (!img) return;
        setZoom(img, Number(img.dataset.zx || 50) + dx, Number(img.dataset.zy || 50) + dy);
    }

    function clearZoom(img){
        if (!img) return;
        img.style.transform       = '';
        img.style.transformOrigin = '';
        img.classList.remove('is-zoomed');
        img.setAttribute('aria-pressed', 'false');
        delete img.dataset.zx;
        delete img.dataset.zy;
    }

    function clearAllZoom(){
        if (!slides) return;
        Array.prototype.forEach.call(
            slides.querySelectorAll('img.pd-slide-img'), clearZoom
        );
    }

    if (carousel && finePointer) {
        carousel.addEventListener('mousemove', function(e){
            applyZoom(currentZoomable(), e.clientX, e.clientY);
        });
        carousel.addEventListener('mouseleave', clearAllZoom);
    }

    if (carousel && !finePointer) {
        carousel.addEventListener('click', function(e){
            // The arrows and dots live inside the carousel; a tap on one of them
            // is navigation, not a zoom request.
            if (e.target.closest('button')) return;

            var img = currentZoomable();
            if (!img) return;
            if (img.classList.contains('is-zoomed')) clearZoom(img);
            else applyZoom(img, e.clientX, e.clientY);
        });
    }

    // Losing focus releases the zoom. The pointer path has mouseleave to undo
    // itself; the keyboard path has nothing, so tabbing onward would strand the
    // image magnified behind the user.
    if (slides) {
        Array.prototype.forEach.call(slides.querySelectorAll('img.pd-slide-img'), function(im){
            im.addEventListener('blur', function(){ clearZoom(im); });
        });
    }

    // ── No auto-swipe ──
    // The gallery used to advance itself every 3 seconds. Two things were wrong
    // with that. It defeated the colour swatches: picking a colour jumps to that
    // colour's photo, but the timer resumed on mouseleave and rotated away from
    // it within seconds, so the feature only worked if the cursor never left the
    // image. And it moved a product's photos out from under the customer while
    // they were looking at them — on a page whose entire job is letting someone
    // examine the thing before buying it. Hover-pause was the only stop control,
    // which is no help to keyboard or touch users (WCAG 2.2.2).
    // The gallery now moves only when the customer moves it.

    // ── Qty +/- ──
    var qtyEl = document.getElementById('pd-qty');
    var dec = document.getElementById('pd-dec');
    var inc = document.getElementById('pd-inc');
    if(dec && inc && qtyEl){
        dec.addEventListener('click', function(){ var v=parseInt(qtyEl.value)||1; if(v>1) qtyEl.value=v-1; });
        inc.addEventListener('click', function(){ var v=parseInt(qtyEl.value)||1; qtyEl.value=v+1; });
    }

    // ── Color label + hidden input sync + image switch ──
    var pdColorImageMap = @json($colorImageMap);
    document.querySelectorAll('.color-radio').forEach(function(r){
        r.addEventListener('change', function(){
            var lbl = document.getElementById('selected-color-label');
            if(lbl) lbl.textContent = r.value;
            var hid = document.getElementById('selected-color');
            if(hid) hid.value = r.value;
            // Jump the slider to this colour's photo, if one is assigned. No
            // pause call needed now that nothing moves the gallery on its own —
            // the chosen colour's photo stays on screen.
            var idx = pdColorImageMap[r.value.toLowerCase().trim()];
            if(typeof idx === 'number'){
                goTo(idx);
            }
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

<!-- Tabs: About this item / Shipping Information Start -->
<div class="s-py-50">
    <div class="container-fluid">
        <div class="max-w-[985px] mx-auto">

            {{-- A segmented pill control, matching the pill shape used by every
                 button on the site. With Reviews promoted to its own section,
                 a product without shipping info has only one panel left — and a
                 lone tab is not a choice, so it renders as a heading instead. --}}
            @if($item->shipping_info)
            <div class="pd-tabs-wrap">
                <div class="pd-tabs" role="tablist" aria-label="Product information">
                    <button type="button" onclick="switchTab('tab-desc', this)"
                            class="pdtab-btn pd-tab is-active" role="tab"
                            aria-selected="true" aria-controls="tab-desc">
                        <i class="mdi mdi-text-box-outline" aria-hidden="true"></i>
                        <span>About this item</span>
                    </button>

                    <button type="button" onclick="switchTab('tab-shipping', this)"
                            class="pdtab-btn pd-tab" role="tab"
                            aria-selected="false" aria-controls="tab-shipping">
                        <i class="mdi mdi-truck-fast-outline" aria-hidden="true"></i>
                        <span>Shipping Information</span>
                    </button>
                </div>
            </div>
            @else
            <h2 class="pd-section-title">
                <i class="mdi mdi-text-box-outline" aria-hidden="true"></i>
                About this item
            </h2>
            @endif

            {{-- Description Panel --}}
            <div id="tab-desc" class="pdtab-panel">
                @if($item->description)
                    <div class="rich-content leading-relaxed">
                        {!! $item->description !!}
                    </div>
                @else
                    <p class="text-gray-400 italic">No description available for this product.</p>
                @endif

                {{-- Specifications table (rendered only when the product has specs) --}}
                @if(!empty($item->specifications))
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-title dark:text-white mb-4">Specifications</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-bdr-clr dark:border-bdr-clr-drk rounded-lg overflow-hidden">
                            <tbody>
                                @foreach($item->specifications as $i => $spec)
                                <tr class="{{ $i % 2 === 0 ? 'bg-[#F8F5F0] dark:bg-white/5' : '' }}">
                                    <th scope="row" class="text-left font-semibold text-title dark:text-white px-4 py-3 w-1/3 align-top">{{ $spec['label'] ?? '' }}</th>
                                    <td class="text-paragraph dark:text-white/70 px-4 py-3">{{ $spec['value'] ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
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
<!-- Tabs End -->

<!-- Reviews Section Start -->
{{-- Reviews live in their own section rather than behind a tab: a tab hides
     them behind a click, and social proof only works where it is seen. --}}
<section id="reviews" class="pd-reviews">
    <div class="container-fluid">
        <div class="max-w-[985px] mx-auto">

            @php
                    $pdReviews      = $item->reviews;
                    $pdReviewCount  = $pdReviews->count();
                    // Only claimable when it is true of every review shown.
                    $pdAllVerified  = $pdReviewCount > 0 && $pdReviews->every(fn ($r) => $r->is_verified);
                    // Cards revealed before "View more" is pressed. Two full rows
                    // of three, which is where the grid stops looking balanced.
                    $pdInitialShown = 6;
                @endphp

                @if ($pdReviewCount > 0)
                {{-- Summary bar --}}
                <div class="pd-rev-head">
                    <span class="pd-rev-head__title">Reviews</span>
                    <span class="pd-rev-head__sep" aria-hidden="true">|</span>
                    <span class="pd-rev-head__score">{{ number_format($item->avgRating(), 2) }}</span>
                    <span class="pd-rev-head__stars">@include('includes.Home._stars', ['rating' => $item->avgRating()])</span>
                    <span class="pd-rev-head__count">{{ $pdReviewCount }} {{ Str::plural('rating', $pdReviewCount) }}</span>
                    @if ($pdAllVerified)
                        <span class="pd-rev-head__verified">
                            <i class="mdi mdi-check-circle" aria-hidden="true"></i> All from verified purchases
                        </span>
                    @endif
                </div>

                {{-- Section tab + keyword filter --}}
                <div class="pd-rev-bar">
                    <span class="pd-rev-tab" aria-current="true">Item Reviews ({{ $pdReviewCount }})</span>

                    <div class="pd-rev-filter">
                        <label for="pd-rev-search" class="sr-only">Filter reviews by keyword</label>
                        <input type="search" id="pd-rev-search" class="pd-rev-filter__input"
                               placeholder="Filter by keyword" autocomplete="off">
                        <span class="pd-rev-filter__icon" aria-hidden="true"><i class="mdi mdi-magnify"></i></span>
                    </div>
                </div>

                {{-- Cards: masonry columns on desktop, snap slider on mobile --}}
                <div class="pd-rev-grid" id="pd-rev-grid">
                    @foreach ($pdReviews as $review)
                    <article class="pd-rev-card {{ $loop->index >= $pdInitialShown ? 'is-hidden' : '' }}"
                             data-rev-text="{{ Str::lower($review->comment . ' ' . $review->author_name) }}">
                        <div class="pd-rev-card__top">
                            <span class="pd-rev-card__stars">@include('includes.Home._stars', ['rating' => $review->rating])</span>
                            <time class="pd-rev-card__date" datetime="{{ $review->created_at->toDateString() }}">
                                {{ $review->created_at->format('m/d/Y') }}
                            </time>
                        </div>

                        @if ($review->comment)
                            <p class="pd-rev-card__text">{{ $review->comment }}</p>
                        @endif

                        @if ($review->image_url)
                            <img src="{{ $review->image_url }}" alt="Photo from {{ $review->author_name }}'s review"
                                 class="pd-rev-card__img" loading="lazy" decoding="async" width="160" height="160">
                        @endif

                        <footer class="pd-rev-card__foot">
                            <span class="pd-rev-card__avatar" style="background:{{ $review->avatar_color }}" aria-hidden="true">{{ $review->initial }}</span>
                            <span class="pd-rev-card__name">{{ $review->author_name }}</span>
                            @if ($review->country)
                                <span class="pd-rev-card__flag">@include('includes._flag', ['code' => $review->country])</span>
                            @endif
                            @if ($review->is_verified)
                                <i class="mdi mdi-check-circle pd-rev-card__tick" title="Verified purchase" aria-label="Verified purchase"></i>
                            @endif
                        </footer>
                    </article>
                    @endforeach
                </div>

                <p class="pd-rev-empty" id="pd-rev-noresult" hidden>No reviews match that keyword.</p>

                @if ($pdReviewCount > $pdInitialShown)
                <div class="pd-rev-more-wrap">
                    <button type="button" class="pd-rev-more" id="pd-rev-more"
                            data-more="View more reviews for this item" data-less="Show fewer reviews">
                        View more reviews for this item
                    </button>
                </div>
                @endif

                @else
                <p class="text-gray-400 italic text-sm mb-8">No reviews yet. Be the first to review this product!</p>
                @endif

                {{-- Write a review --}}
                @php
                    $myReview = auth()->check() ? $item->reviews->firstWhere('user_id', auth()->id()) : null;
                    $isUpdate = (bool) $myReview;
                @endphp

                <div class="pd-write">
                    <div class="pd-write__head">
                        <span class="pd-write__icon" aria-hidden="true">
                            <i class="mdi mdi-square-edit-outline"></i>
                        </span>
                        <div>
                            <h3 class="pd-write__title">{{ $isUpdate ? 'Update Your Review' : 'Write a Review' }}</h3>
                            <p class="pd-write__sub">
                                {{ $isUpdate
                                    ? 'Changed your mind? Update your rating below.'
                                    : 'Tell other shoppers what you think — it only takes a minute.' }}
                            </p>
                        </div>
                    </div>

                    @auth
                    <form action="{{ route('product.review.store', $item->slug) }}" method="POST" class="pd-write__form">
                        @csrf

                        {{-- Star picker --}}
                        <div class="pd-write__field">
                            <label class="pd-write__label" id="pd-rating-label">
                                Your Rating <span class="pd-write__req" aria-hidden="true">*</span>
                            </label>
                            <div class="pd-stars" id="star-picker" role="radiogroup" aria-labelledby="pd-rating-label">
                                @for ($s = 1; $s <= 5; $s++)
                                <button type="button"
                                        data-value="{{ $s }}"
                                        role="radio"
                                        aria-checked="{{ (int) ($myReview->rating ?? 0) === $s ? 'true' : 'false' }}"
                                        aria-label="{{ $s }} {{ Str::plural('star', $s) }}"
                                        class="star-pick-btn pd-star"
                                        onclick="setRating({{ $s }})">
                                    <svg viewBox="0 0 15 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z"/>
                                    </svg>
                                </button>
                                @endfor
                                <span class="pd-stars__verdict" id="pd-rating-verdict" aria-live="polite"></span>
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="{{ $myReview->rating ?? '' }}">
                            @error('rating') <p class="pd-write__error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Comment --}}
                        <div class="pd-write__field">
                            <label class="pd-write__label" for="pd-review-comment">
                                Your Review <span class="pd-write__opt">Optional</span>
                            </label>
                            <textarea name="comment" id="pd-review-comment" rows="4" maxlength="1000"
                                      placeholder="What did you like about it? How was the delivery?"
                                      class="pd-write__textarea">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                            <div class="pd-write__count"><span id="pd-review-count">0</span> / 1000</div>
                            @error('comment') <p class="pd-write__error">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="pd-write__submit">
                            <i class="mdi mdi-send" aria-hidden="true"></i>
                            {{ $isUpdate ? 'Update Review' : 'Submit Review' }}
                        </button>
                    </form>

                    @else
                    <div class="pd-write__gate">
                        <p class="pd-write__gate-text">Sign in to share your experience with this product.</p>
                        <a href="{{ route('login') }}" class="pd-write__submit pd-write__submit--link">
                            <i class="mdi mdi-login" aria-hidden="true"></i>
                            Log in to review
                        </a>
                    </div>
                    @endauth
                </div>

        </div>
    </div>
</section>
<!-- Reviews Section End -->

<script>
function switchTab(panelId, btn) {
    document.querySelectorAll('.pdtab-panel').forEach(function(p){ p.classList.add('hidden'); });
    document.querySelectorAll('.pdtab-btn').forEach(function(b){
        b.classList.remove('is-active');
        b.setAttribute('aria-selected', 'false');
    });

    var panel = document.getElementById(panelId);
    if (panel) panel.classList.remove('hidden');

    btn.classList.add('is-active');
    btn.setAttribute('aria-selected', 'true');

    // The bar scrolls sideways on narrow screens, so a tab activated from
    // elsewhere on the page (the "See all" rating link) may sit off-screen.
    if (btn.scrollIntoView) {
        btn.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' });
    }
}

/* Arrow-key navigation between tabs, which role="tablist" leads a screen
   reader to expect. */
document.addEventListener('keydown', function (e) {
    var current = document.activeElement;
    if (!current || !current.classList.contains('pdtab-btn')) return;
    if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;

    var tabs = Array.prototype.slice.call(document.querySelectorAll('.pdtab-btn'));
    var next = tabs[(tabs.indexOf(current) + (e.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length];

    e.preventDefault();
    next.focus();
    next.click();
});
</script>

{{-- ── Frequently bought together ──────────────────────────────────────────
     Companions come from real co-purchase history where the store has enough
     orders for it, and from nearest-priced items in the same category where it
     does not (ProductController::bundleFor). Every tile is in-stock and active,
     because the add posts straight to the cart and out-of-stock lines are now
     rejected there.

     The current product is shown as a fixed, non-removable member of the bundle
     so the total reads as "this plus these", which is the whole point — a
     checkbox list that lets you deselect the item you are looking at is just a
     second recommendations rail. --}}
@if($boughtTogether->isNotEmpty())
<div class="s-py-50 pt-0">
    <div class="container-fluid">
        <div class="max-w-[985px] mx-auto">
            <h2 class="text-xl sm:text-2xl leading-none font-bold dark:text-white mb-6">Frequently bought together</h2>

            <form method="POST" action="{{ route('cart.add-many') }}" id="fbt-form"
                  class="border border-gray-200 dark:border-white/10 rounded-xl p-5 sm:p-6">
                @csrf

                {{-- flex-wrap is deliberately sm+ only. On a wrapping *column*
                     flex container the cross axis (width) is sized to content,
                     so a long product name stretched this row to 636px inside a
                     299px parent and pushed the whole page sideways. Stacked and
                     unwrapped on phones, wrapping rows from sm up. --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-3 sm:flex-wrap">
                    {{-- Anchor product --}}
                    <div class="flex items-center gap-3 flex-1 min-w-[200px]">
                        <img src="{{ $ogImg }}" alt="" class="w-16 h-16 object-cover rounded-lg flex-none border border-gray-100 dark:border-white/10" loading="lazy" decoding="async">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-title dark:text-white truncate">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-white-light">This item · {{ $item->display_price }}</p>
                        </div>
                    </div>

                    @foreach($boughtTogether as $bundleItem)
                        <span class="hidden sm:block text-2xl text-primary/40 font-light select-none" aria-hidden="true">+</span>
                        <label class="flex items-center gap-3 flex-1 min-w-[200px] cursor-pointer">
                            <input type="checkbox" name="product_ids[]" value="{{ $bundleItem->id }}" checked
                                   class="fbt-check w-4 h-4 accent-[#bb976d] flex-none"
                                   data-price="{{ number_format($bundleItem->effective_price, 2, '.', '') }}"
                                   aria-label="Include {{ $bundleItem->name }} in this bundle">
                            <img src="{{ $bundleItem->image ? (str_starts_with($bundleItem->image, 'assets/') ? asset($bundleItem->image) : \Storage::url($bundleItem->image)) : asset('assets/img/logo.svg') }}"
                                 alt="" class="w-16 h-16 object-cover rounded-lg flex-none border border-gray-100 dark:border-white/10" loading="lazy" decoding="async">
                            <div class="min-w-0">
                                <a href="{{ route('product-details', $bundleItem->slug) }}"
                                   class="text-sm font-medium text-title dark:text-white hover:text-primary duration-200 line-clamp-2">{{ $bundleItem->name }}</a>
                                <p class="text-xs text-gray-500 dark:text-white-light">{{ $bundleItem->display_price }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="pd-fbt-foot flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:flex-wrap mt-5 pt-5 border-t border-gray-100 dark:border-white/10">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-white-light">Total for <span id="fbt-count">{{ $boughtTogether->count() + 1 }}</span> items</p>
                        <p class="text-xl font-bold text-title dark:text-white" id="fbt-total">$0.00</p>
                    </div>
                    {{-- The anchor product is not a checkbox, so it is submitted here.
                         Without it the bundle would add only the companions. --}}
                    <input type="hidden" name="product_ids[]" value="{{ $item->id }}">
                    <button type="submit" class="pd-btn-cart" id="fbt-submit" style="width:auto;padding-inline:28px">
                        Add selected to cart
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var form = document.getElementById('fbt-form');
    if (!form) return;

    // The anchor product is always in the bundle, so its price is the floor the
    // total counts up from.
    var BASE   = {{ number_format((float) $item->effective_price, 2, '.', '') }};
    var checks = Array.prototype.slice.call(form.querySelectorAll('.fbt-check'));
    var totalEl = document.getElementById('fbt-total');
    var countEl = document.getElementById('fbt-count');
    var submit  = document.getElementById('fbt-submit');

    function refresh() {
        var total = BASE, count = 1;

        checks.forEach(function (c) {
            if (!c.checked) return;
            var p = parseFloat(c.dataset.price);
            if (isFinite(p)) { total += p; count++; }
        });

        if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
        if (countEl) countEl.textContent = count;
        // The anchor alone is still a valid add, so the button never disables —
        // unchecking everything just makes this an ordinary add-to-cart.
        if (submit) submit.textContent = count === 1 ? 'Add this item to cart' : 'Add ' + count + ' items to cart';
    }

    checks.forEach(function (c) { c.addEventListener('change', refresh); });
    refresh();
})();
</script>
@endpush
@endif

<!-- Related Product Start -->
<div class="s-py-50-100">
    <div class="container-fluid">
        <div class="max-w-[547px] mx-auto text-center">
            <h2 class="text-xl sm:text-2xl leading-none font-bold dark:text-white">Related Products</h2>
            <p class="mt-3">Explore complementary options that enhance your experience. Discover related products curated just for you. </p>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-8 md:pt-[50px]">
            
            <!-- includes/Home/new-products.blade.php -->
            @include('includes.Home.new-products')

        </div>
    </div>
</div>
<!-- Related Product End -->

{{-- Static demo quick-view removed: it rendered dummy 'Classic Relaxable Chair'
     content in the HTML of every page. Real quick-view is the pg-qv modal. --}}
    
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

{{-- Variant pricing & availability: when a colour/size with its own price/stock
     is selected, update the displayed price, the sticky bar, the stock badge and
     the Add-to-Cart buttons. Size price takes precedence over colour (mirrors
     Product::effectivePriceFor on the server). --}}
<script>
(function () {
    var V = @json($pdVariants);
    if (!V) return;

    function money(n) { return '$' + Number(n).toFixed(2); }
    function checkedVal(sel) { var r = document.querySelector(sel + ':checked'); return r ? r.value : null; }
    function lookup(type, val) { return val ? (V[type][val.toLowerCase().trim()] || null) : null; }

    function set(id, fn) { var el = document.getElementById(id); if (el) fn(el); }
    function show(el, on) { el.style.display = on ? '' : 'none'; }

    function resolve() {
        var sizeV  = lookup('size',  checkedVal('.size-radio'));
        var colorV = lookup('color', checkedVal('.color-radio'));

        // PRICE: size wins over colour when both have a variant, else base.
        // Mirrors Product::effectivePriceFor on the server.
        var priced = sizeV || colorV || V.base;

        // STOCK: the MINIMUM across every dimension that has a variant — not the
        // first match. Mirrors Product::effectiveStockFor. Using price's
        // first-match rule here meant an out-of-stock colour on a product that
        // also had sizes reported the size's stock, so the badge read "In Stock"
        // and the button stayed enabled for something with none left.
        var stocks = [];
        if (sizeV)  stocks.push(Number(sizeV.stock));
        if (colorV) stocks.push(Number(colorV.stock));
        var stock = stocks.length ? Math.min.apply(null, stocks) : Number(V.base.stock);

        return { now: priced.now, was: priced.was, stock: stock };
    }

    function apply() {
        var r = resolve();
        var was = r.was && r.was > r.now ? r.was : null;
        var pct = was ? Math.round((was - r.now) / was * 100) : 0;

        // Main price block
        set('pd-price-now', function (el) { el.textContent = money(r.now); });
        set('pd-price-was', function (el) { show(el, !!was); if (was) el.textContent = money(was); });
        set('pd-price-badge', function (el) { show(el, pct > 0); if (pct > 0) el.textContent = pct + '% OFF'; });
        set('pd-price-save', function (el) {
            show(el, !!was); if (was) el.textContent = 'You save ' + money(was - r.now) + ' on this product';
        });

        // Sticky bar
        set('pd-sticky-now', function (el) { el.textContent = money(r.now); });
        set('pd-sticky-was', function (el) { show(el, !!was); if (was) el.textContent = money(was); });

        // Stock badge
        var inStock = r.stock > 0;
        set('pd-stock-badge', function (el) {
            el.className = inStock ? el.dataset.in : el.dataset.out;
            el.innerHTML = inStock
                ? '<span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block"></span> In Stock'
                : 'Out of Stock';
        });

        // Add-to-Cart buttons (main + sticky)
        [['pd-add-btn', 'pd-add-btn-label'], ['pd-sticky-btn', 'pd-sticky-btn-label']].forEach(function (pair) {
            var btn = document.getElementById(pair[0]);
            var lbl = document.getElementById(pair[1]);
            if (!btn) return;
            btn.disabled = !inStock;
            btn.style.opacity = inStock ? '' : '.5';
            btn.style.cursor  = inStock ? '' : 'not-allowed';
            if (lbl) lbl.textContent = inStock ? 'Add to Cart' : 'Out of Stock';
        });
    }

    document.querySelectorAll('.color-radio, .size-radio').forEach(function (r) {
        r.addEventListener('change', apply);
    });
    apply(); // reflect the initially-selected options
})();
</script>

@endsection

{{-- ── Meta Pixel ViewContent ───────────────────────────────────────────────
     content_ids carries the product id, not the SKU: this store has no catalog
     SKUs (see TikTokEventsService::contents) and the product feed is keyed on
     the id, so a SKU here would fail to resolve against the catalog and break
     dynamic-ads attribution. AddToCart and Purchase use the same id for the
     same reason — the three events have to agree or Meta cannot follow one
     product through the funnel.

     The value is the price the page rendered with. The variant picker rewrites
     the displayed price after load, but ViewContent describes the catalog entry
     the visitor landed on, not whichever option they click next.

     The guard is a per-page-load flag rather than a sessionStorage lock: a
     visitor genuinely returning to this product later in the same tab is a real
     second view, and persisting the lock would silently undercount it. This
     only stops the same page load from reporting twice. --}}
@push('scripts')
<script>
(function () {
    if (typeof fbq !== 'function') return;
    if (window.__pgMetaViewContentFired) return;
    window.__pgMetaViewContentFired = true;

    var payload = {
        content_type: 'product',
        content_ids:  [@json((string) $item->id)],
        content_name: @json($item->name),
        value:        {{ number_format((float) $item->effective_price, 2, '.', '') }},
        currency:     @json(config('services.meta.currency', 'USD'))
    };
    @if ($item->category)
    payload.content_category = @json($item->category->name);
    @endif

    {{-- Deterministic on the product id so a Conversions API twin added later
         collapses into this event instead of counting a second view. --}}
    fbq('track', 'ViewContent', payload, { eventID: @json('ViewContent.product-' . $item->id) });
})();
</script>
@endpush

@push('scripts')
<script>
/* Write-a-review form: star picker, verdict label, character counter. */
(function () {
    var picker = document.getElementById('star-picker');
    if (!picker) return;   // logged out — the form is not on the page

    var input   = document.getElementById('rating-input');
    var verdict = document.getElementById('pd-rating-verdict');
    var stars   = Array.prototype.slice.call(picker.querySelectorAll('.star-pick-btn'));
    var WORDS   = ['', 'Poor', 'Fair', 'Good', 'Very good', 'Excellent'];
    var current = parseInt(input.value, 10) || 0;

    function paint(n, committed) {
        stars.forEach(function (btn) {
            var v = parseInt(btn.dataset.value, 10);
            btn.style.color = v <= n ? '#EE9818' : '';
            if (committed) btn.setAttribute('aria-checked', v === n ? 'true' : 'false');
        });
        verdict.textContent = WORDS[n] || '';
    }

    window.setRating = function (n) {
        current = n;
        input.value = n;
        paint(n, true);
    };

    stars.forEach(function (btn) {
        btn.addEventListener('mouseover', function () { paint(parseInt(btn.dataset.value, 10), false); });
        btn.addEventListener('focus',     function () { paint(parseInt(btn.dataset.value, 10), false); });
        btn.addEventListener('mouseout',  function () { paint(current, false); });
        btn.addEventListener('blur',      function () { paint(current, false); });
    });

    paint(current, true);

    var box = document.getElementById('pd-review-comment');
    var count = document.getElementById('pd-review-count');
    if (box && count) {
        var sync = function () { count.textContent = box.value.length; };
        box.addEventListener('input', sync);
        sync();
    }
})();

/* Review grid: keyword filter + "view more".
   Both are progressive enhancement — with JS off every review is still in the
   DOM and the mobile slider still swipes; only the cap and the filter go. */
(function () {
    var grid = document.getElementById('pd-rev-grid');
    if (!grid) return;

    var cards    = Array.prototype.slice.call(grid.querySelectorAll('.pd-rev-card'));
    var search   = document.getElementById('pd-rev-search');
    var moreBtn  = document.getElementById('pd-rev-more');
    var noResult = document.getElementById('pd-rev-noresult');
    var expanded = false;

    function capped() {
        // The cap is a desktop affordance; the mobile slider shows everything.
        cards.forEach(function (card) {
            if (card.dataset.capped === '1' && !expanded) {
                card.classList.add('is-hidden');
            } else {
                card.classList.remove('is-hidden');
            }
        });
    }

    // Remember which cards started capped, so the state survives filtering.
    cards.forEach(function (card) {
        card.dataset.capped = card.classList.contains('is-hidden') ? '1' : '0';
    });

    if (moreBtn) {
        moreBtn.addEventListener('click', function () {
            expanded = !expanded;
            capped();
            moreBtn.textContent = expanded ? moreBtn.dataset.less : moreBtn.dataset.more;
            if (!expanded) grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (search) {
        var debounce;
        search.addEventListener('input', function () {
            clearTimeout(debounce);
            debounce = setTimeout(function () {
                var q = search.value.trim().toLowerCase();
                var matches = 0;

                cards.forEach(function (card) {
                    var hit = !q || (card.dataset.revText || '').indexOf(q) !== -1;
                    card.classList.toggle('is-filtered', !hit);
                    if (hit) matches++;
                });

                // A search has to look past the cap, or matches sitting in the
                // hidden tail would read as "no results".
                if (q) {
                    cards.forEach(function (c) { c.classList.remove('is-hidden'); });
                } else {
                    capped();
                }

                if (noResult) noResult.hidden = matches !== 0;
                if (moreBtn) moreBtn.parentElement.style.display = q ? 'none' : '';
            }, 150);
        });
    }
})();
</script>
@endpush

