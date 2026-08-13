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

/* ── Product description prose ───────────────────────────────────────────
   Inherits the site type system (assets/css/typography.css): Inter for the
   copy, Manrope for the headings inside it. Only the things specific to this
   block are set here — the measure, the lead paragraph, and the bullets.
   Measure is capped near 68ch because the panel is 985px wide and a line
   that long loses the reader on the carriage return.                     */
.pd-prose{
  font-family:var(--pg-font-body);
  font-size:var(--pg-body-size);
  line-height:var(--pg-lh-body);
  color:var(--pg-c-body);
  max-width:68ch;
  text-wrap:pretty;
}
.pd-prose p{margin-bottom:1.15em;line-height:1.8}
.pd-prose p:last-child{margin-bottom:0}
/* ~80 descriptions were pasted from Google and wrap their copy in <div>
   rather than <p>. Without this they run together as one unbroken block. */
.pd-prose > div{margin-bottom:1.15em;line-height:1.8}
.pd-prose > div:last-child{margin-bottom:0}

/* Opening paragraph carries the pitch, so it gets the weight of a lead:
   a touch larger, darker, tighter. Everything after it settles back down.
   Description only — shipping info opens with logistics, not a pitch.    */
#tab-desc .pd-prose > p:first-child{
  font-size:clamp(1.0625rem,1.02rem + .22vw,1.1875rem);
  line-height:1.65;
  letter-spacing:-.01em;
  color:var(--pg-c-heading);
  margin-bottom:1.3em;
}

.pd-prose h1,.pd-prose h2,.pd-prose h3,.pd-prose h4,.pd-prose h5,.pd-prose h6{
  font-family:var(--pg-font-heading);
  font-weight:700;
  letter-spacing:var(--pg-track-heading);
  color:var(--pg-c-heading);
  margin-top:1.8em;
  margin-bottom:.6em;
}
.pd-prose > :first-child{margin-top:0}

/* Brand-coloured markers: the default disc reads as unstyled next to the
   rest of the page, and bullets are where feature copy usually lands.     */
.pd-prose ul{list-style:none;padding-left:0;margin-bottom:1.15em}
.pd-prose ul > li{position:relative;padding-left:1.5rem;margin-bottom:.55em;line-height:1.75}
.pd-prose ul > li::before{
  content:'';position:absolute;left:.15rem;top:.72em;
  width:.4rem;height:.4rem;border-radius:999px;background:#bb976d;
}
.pd-prose ol{padding-left:1.35rem;margin-bottom:1.15em}
.pd-prose ol > li{margin-bottom:.55em;line-height:1.75;padding-left:.25rem}
.pd-prose ol > li::marker{color:#bb976d;font-weight:600}
.pd-prose strong,.pd-prose b{font-weight:600;color:var(--pg-c-heading)}
.pd-prose blockquote{
  border-left:3px solid #bb976d;background:#F8F5F0;border-radius:0 .5rem .5rem 0;
  padding:.9rem 1.25rem;margin:1.5em 0;font-style:normal;color:var(--pg-c-body);
}
.dark .pd-prose blockquote{background:rgba(255,255,255,.05)}
/* Sizes are fluid via the shared clamps, so the phone-width font-size
   override this block used to carry is no longer needed. */

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

{{-- ═══════════════════════════════════════════════════════════════════════
     PREMIUM PRODUCT INFORMATION LAYER (pdx-)

     A second push so every rule here lands after the block above and wins on
     equal specificity — the older .pd- rules stay intact for the gallery,
     reviews and sticky bar, which are not part of this redesign.

     Inter is self-hosted (php artisan fonts:self-host) and declared in
     assets/css/fonts.css alongside Poppins/Josefin. No CDN link: the layout
     deliberately removed cross-origin font fetches to protect LCP, and a
     googleapis <link> here would put a DNS+TLS round-trip in front of the
     text paint.
     ═══════════════════════════════════════════════════════════════════════ --}}
@push('styles')
{{-- 700 is the product title (largest text in the column); 400 is the body.
     Both are needed before first paint, so they are preloaded rather than
     discovered when fonts.css parses. --}}
<link rel="preload" as="font" type="font/woff2" href="{{ asset('assets/fonts/inter-700-normal-latin.woff2') }}" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="{{ asset('assets/fonts/inter-400-normal-latin.woff2') }}" crossorigin>
<style>
/* ── Tokens ─────────────────────────────────────────────────────────────
   Scoped to .pdx so nothing here leaks into the rest of the site. Gold
   #bb976d is the brand accent but fails 4.5:1 on white, so every gold that
   carries text uses --pdx-gold-ink instead. */
.pdx {
    --pdx-ink:       #101820;
    --pdx-body:      #4A5259;
    --pdx-muted:     #6B7280;
    --pdx-gold:      #bb976d;
    --pdx-gold-ink:  #8A6A3F;
    --pdx-gold-soft: #F7F1E8;
    --pdx-line:      #E8E5E0;
    --pdx-line-soft: #F1EEE9;
    --pdx-surface:   #FBFAF8;
    --pdx-ok:        #15803D;
    --pdx-ok-soft:   #ECFDF3;

    --pdx-ease:      cubic-bezier(.4, 0, .2, 1);
    --pdx-lift:      cubic-bezier(.34, 1.56, .64, 1);

    /* The site's unified type tokens (assets/css/typography.css): Inter for
       body, Manrope for headings. Both are now self-hosted — before that run
       they fell through to the system UI stack. */
    font-family: var(--pg-font-body);
    font-feature-settings: 'cv11', 'ss01';
    -webkit-font-smoothing: antialiased;
}
.pdx-title, .pdx-price, .pdx-h3 { font-family: var(--pg-font-heading); }

/* ── Typography scale ───────────────────────────────────────────────────
   Sizes come straight from the brief: 36/30/26 title, 34 price, 18/600
   section titles, 18/400 body at 1.75, 13px uppercase labels. clamp() is
   used instead of three breakpoints so large screens scale too. */
.pdx-title {
    font-size: clamp(1.625rem, 1.15rem + 1.6vw, 2.25rem);   /* 26 → 36 */
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.022em;
    color: var(--pdx-ink);
    margin: 0 0 .5rem;
    text-wrap: balance;
}
.pdx-price {
    font-size: clamp(1.75rem, 1.4rem + 1.1vw, 2.125rem);    /* 28 → 34 */
    font-weight: 700;
    line-height: 1;
    letter-spacing: -.02em;
    color: var(--pdx-ink);
    font-variant-numeric: tabular-nums;
}
.pdx-h3 {
    font-size: 1.125rem; font-weight: 600; line-height: 1.4;
    letter-spacing: -.01em; color: var(--pdx-ink); margin: 0;
}
.pdx-body { font-size: 1.125rem; font-weight: 400; line-height: 1.75; color: var(--pdx-body); }
.pdx-label {
    font-size: .8125rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .09em;
    color: var(--pdx-muted); margin: 0;
}

/* ── Info column ────────────────────────────────────────────────────────
   Generous internal rhythm: the brief's "breathing room" is mostly this
   one value. */
.pdx.pd-info-col {
    background: #fff;
    border-radius: 20px;
    padding: 22px 20px 26px;
    box-shadow: 0 1px 2px rgba(16, 24, 32, .04), 0 12px 40px -12px rgba(16, 24, 32, .10);
    border: 1px solid var(--pdx-line-soft);
}
@media (min-width: 768px) { .pdx.pd-info-col { padding: 32px 30px 34px; } }

/* Consistent vertical rhythm between blocks, with a hairline where a real
   visual break helps scanning. */
.pdx-block { margin-top: 26px; }
.pdx-block--sep { padding-top: 26px; border-top: 1px solid var(--pdx-line-soft); }

/* ── Eyebrow row (category + stock) ─────────────────────────────────── */
.pdx-eyebrow {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    margin-bottom: 12px;
}
.pdx-eyebrow__cat {
    font-size: .8125rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .09em;
    color: var(--pdx-gold-ink); text-decoration: none;
}
.pdx-eyebrow__cat:hover { text-decoration: underline; text-underline-offset: 3px; }

/* ── Social proof ───────────────────────────────────────────────────────
   Rendered only when the product genuinely has reviews. There is no
   fabricated rating, view counter or "N bought today" here: those numbers
   have no source in the data and inventing them is both a trust and a
   legal liability (FTC 16 CFR 465). */
.pdx-social {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    margin-bottom: 14px;
}
.pdx-stars { display: inline-flex; align-items: center; gap: 2px; }
.pdx-social__score { font-size: .9375rem; font-weight: 700; color: var(--pdx-ink); font-variant-numeric: tabular-nums; }
.pdx-social__count { font-size: .9375rem; color: var(--pdx-muted); }
.pdx-social__link {
    font-size: .875rem; font-weight: 600; color: var(--pdx-gold-ink);
    text-decoration: underline; text-underline-offset: 3px;
}
.pdx-social__link:hover { color: #6E532F; }

/* ── Price ──────────────────────────────────────────────────────────── */
.pdx.pd-price-block {
    background: none; border-left: 0; border-radius: 0;
    padding: 0; margin: 0 0 4px;
}
.pdx-price-row { display: flex; align-items: baseline; gap: 12px; flex-wrap: wrap; }
.pdx-was { font-size: 1.0625rem; color: #9AA0A6; text-decoration: line-through; font-weight: 500; }
.pdx-off {
    font-size: .8125rem; font-weight: 700; color: #fff;
    background: #C2321F; border-radius: 999px; padding: 4px 11px;
    letter-spacing: .01em;
}
.pdx-save { font-size: .875rem; font-weight: 600; color: var(--pdx-ok); margin: 8px 0 0; }
.pdx-tax { font-size: .875rem; color: var(--pdx-muted); margin: 6px 0 0; }

/* ── Variant controls ───────────────────────────────────────────────────
   The radio itself was `display:none`, which took it out of the tab order
   and made variants unreachable by keyboard. It is now visually hidden but
   still focusable, and `:checked`/`:focus-visible` drive the pill styling —
   so selection also renders correctly before the JS runs. */
.pdx-vinput {
    position: absolute; width: 1px; height: 1px;
    padding: 0; margin: -1px; overflow: hidden;
    clip: rect(0 0 0 0); clip-path: inset(50%); white-space: nowrap;
}

.pdx-options { display: flex; flex-wrap: wrap; gap: 10px; }

/* Size — premium rounded button with a check on the selected one */
.pdx.pd-variant-pill,
.pdx .pd-variant-pill {
    position: relative;
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    min-height: 48px; min-width: 56px;
    padding: 0 18px;
    font-size: .9375rem; font-weight: 600; color: var(--pdx-ink);
    background: #fff;
    border: 2px solid var(--pdx-line);
    border-radius: 14px;
    cursor: pointer;
    transition: border-color .2s var(--pdx-ease), background .2s var(--pdx-ease),
                transform .2s var(--pdx-lift), box-shadow .2s var(--pdx-ease);
}
.pdx .pd-variant-pill:hover {
    border-color: var(--pdx-gold);
    background: var(--pdx-gold-soft);
    transform: translateY(-2px);
    box-shadow: 0 8px 18px -8px rgba(187, 151, 109, .55);
}
.pdx .pd-variant-pill.active,
.pdx .pdx-vinput:checked + .pd-variant-pill {
    border-color: var(--pdx-gold-ink);
    background: var(--pdx-gold-soft);
    color: var(--pdx-gold-ink);
    box-shadow: inset 0 0 0 1px rgba(138, 106, 63, .28);
}
.pdx .pdx-vinput:focus-visible + .pd-variant-pill {
    outline: 2px solid var(--pdx-ink);
    outline-offset: 3px;
}
/* Check mark appears only on the selected button */
.pdx .pd-variant-pill .pdx-check { display: none; }
.pdx .pd-variant-pill.active .pdx-check,
.pdx .pdx-vinput:checked + .pd-variant-pill .pdx-check { display: inline-flex; }

/* Colour — a real swatch of the colour, not a text button */
.pdx .pd-variant-pill--swatch {
    min-width: 0; padding: 0;
    width: 48px; height: 48px;
    border-radius: 50%;
    border-width: 2px;
    background: #fff;
}
.pdx .pd-variant-pill--swatch:hover { background: #fff; }
.pdx .pdx-vinput:checked + .pd-variant-pill--swatch { background: #fff; }
.pdx-swatch__dot {
    display: block; width: 100%; height: 100%;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: inset 0 0 0 1px rgba(16, 24, 32, .14);
    transition: transform .2s var(--pdx-lift);
}
.pdx .pd-variant-pill--swatch:hover .pdx-swatch__dot { transform: scale(.9); }
.pdx .pd-variant-pill--swatch .pdx-check {
    position: absolute; inset: 0;
    align-items: center; justify-content: center;
    /* Mix-blend keeps the tick legible on both a white and a black swatch */
    color: #fff; mix-blend-mode: difference;
}

/* ── Quantity ───────────────────────────────────────────────────────── */
.pdx.pd-qty-wrap,
.pdx .pd-qty-wrap {
    border: 2px solid var(--pdx-line);
    border-radius: 14px;
    overflow: hidden;
    height: 52px;
}
.pdx .pd-qty-btn {
    width: 52px; height: 100%;
    color: var(--pdx-ink);
    transition: background .18s var(--pdx-ease), color .18s var(--pdx-ease);
}
.pdx .pd-qty-btn:hover { background: var(--pdx-gold-soft); color: var(--pdx-gold-ink); }
.pdx .pd-qty-btn:focus-visible { outline: 2px solid var(--pdx-ink); outline-offset: -3px; }
.pdx .pd-qty-btn:disabled { opacity: .35; cursor: not-allowed; }
.pdx #pd-qty {
    width: 52px !important; height: 100% !important;
    font-size: 1rem !important; font-weight: 700 !important;
    font-variant-numeric: tabular-nums;
}
/* Chrome/Safari spinners would sit on top of the custom +/- buttons */
.pdx #pd-qty::-webkit-outer-spin-button,
.pdx #pd-qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.pdx #pd-qty { -moz-appearance: textfield; appearance: textfield; }

/* ── Add to Cart — the dominant element on the page ─────────────────── */
.pdx .pd-btn-cart {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; height: 60px;
    font-size: 1rem; font-weight: 700;
    letter-spacing: .02em; text-transform: none;
    border-radius: 16px;
    background: linear-gradient(180deg, #1D2B39 0%, #101820 100%);
    box-shadow: 0 1px 2px rgba(16, 24, 32, .2), 0 12px 28px -10px rgba(16, 24, 32, .55);
    transition: transform .2s var(--pdx-lift), box-shadow .25s var(--pdx-ease), filter .2s var(--pdx-ease);
    position: relative; overflow: hidden;
}
.pdx .pd-btn-cart:hover {
    background: linear-gradient(180deg, #24354652 0%, #0A1119 100%), #101820;
    transform: translateY(-2px) scale(1.008);
    box-shadow: 0 2px 4px rgba(16, 24, 32, .22), 0 18px 38px -12px rgba(16, 24, 32, .6);
}
.pdx .pd-btn-cart:active { transform: translateY(0) scale(.995); }
.pdx .pd-btn-cart:focus-visible { outline: 3px solid var(--pdx-gold); outline-offset: 3px; }
.pdx .pd-btn-cart:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }

/* Ripple — a pseudo-element scaled from the click point by the JS below */
.pdx-ripple {
    position: absolute; border-radius: 50%;
    background: rgba(255, 255, 255, .35);
    transform: scale(0); pointer-events: none;
    animation: pdx-ripple .55s var(--pdx-ease) forwards;
}
@keyframes pdx-ripple { to { transform: scale(2.6); opacity: 0; } }

/* Loading state: label swaps for a spinner without changing button width */
.pdx-spin { display: none; width: 19px; height: 19px; flex: none; }
.pdx [data-loading="true"] .pdx-spin { display: block; animation: pdx-spin .7s linear infinite; }
.pdx [data-loading="true"] .pdx-cart-ico { display: none; }
@keyframes pdx-spin { to { transform: rotate(360deg); } }

/* ── Buy Now — outlined secondary, same height ──────────────────────── */
.pdx-btn-buy {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; height: 60px;
    font-size: 1rem; font-weight: 700; letter-spacing: .02em;
    color: var(--pdx-ink);
    background: #fff;
    border: 2px solid var(--pdx-ink);
    border-radius: 16px;
    cursor: pointer;
    transition: background .2s var(--pdx-ease), color .2s var(--pdx-ease),
                transform .2s var(--pdx-lift), box-shadow .25s var(--pdx-ease);
    position: relative; overflow: hidden;
}
.pdx-btn-buy:hover {
    background: var(--pdx-ink); color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 14px 30px -12px rgba(16, 24, 32, .5);
}
.pdx-btn-buy:active { transform: translateY(0) scale(.995); }
.pdx-btn-buy:focus-visible { outline: 3px solid var(--pdx-gold); outline-offset: 3px; }
.pdx-btn-buy:disabled { opacity: .45; cursor: not-allowed; transform: none; }

/* Wishlist steps back to a quiet tertiary action so it cannot compete with
   the two primary CTAs above it. */
.pdx .wishlist-toggle-btn {
    height: 48px !important; border-radius: 12px !important;
    border: 0 !important; background: transparent !important;
    font-size: .9375rem !important; font-weight: 600 !important;
    color: var(--pdx-muted) !important;
}
.pdx .wishlist-toggle-btn:hover { color: var(--pdx-gold-ink) !important; background: var(--pdx-gold-soft) !important; }
.pdx .wishlist-toggle-btn:focus-visible { outline: 2px solid var(--pdx-ink); outline-offset: 2px; }

/* ── Trust badges — compact reassurance directly under the CTAs ─────── */
.pdx-trust {
    display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px 14px; margin-top: 18px;
}
.pdx-trust__i {
    display: flex; align-items: center; gap: 8px;
    font-size: .875rem; font-weight: 500; color: var(--pdx-body);
}
.pdx-trust__i svg { flex: none; color: var(--pdx-ok); }

/* ── Delivery box ───────────────────────────────────────────────────────
   Dates come from config('checkout.delivery') via DeliveryEstimate, the same
   source the shipping policy renders, so the two can never disagree. There
   is deliberately no "order within HH:MM" countdown: no dispatch cutoff is
   defined anywhere in the app, so a timer would be a fabricated deadline. */
.pdx.pd-delivery,
.pdx-delivery {
    display: block;
    background: linear-gradient(180deg, #FCFAF7 0%, var(--pdx-gold-soft) 100%);
    border: 1px solid #EADFCD;
    border-radius: 18px;
    padding: 18px 18px 16px;
    margin-top: 22px;
}
.pdx-delivery__top { display: flex; align-items: flex-start; gap: 13px; }
.pdx-delivery__ico {
    display: grid; place-items: center; flex: none;
    width: 44px; height: 44px;
    border-radius: 13px;
    background: #fff;
    border: 1px solid #EADFCD;
    color: var(--pdx-gold-ink);
    box-shadow: 0 2px 8px -3px rgba(138, 106, 63, .3);
}
.pdx-delivery__head {
    font-size: 1rem; font-weight: 700; color: var(--pdx-ink);
    margin: 0 0 3px; line-height: 1.35;
}
.pdx-delivery__head em { font-style: normal; color: var(--pdx-gold-ink); }
.pdx-delivery__date { font-size: .9375rem; color: var(--pdx-body); margin: 0; line-height: 1.5; }
.pdx-delivery__date strong { color: var(--pdx-ink); font-weight: 700; white-space: nowrap; }
.pdx-delivery__meta {
    display: flex; flex-wrap: wrap; gap: 8px 18px;
    margin: 14px 0 0; padding-top: 13px;
    border-top: 1px solid rgba(138, 106, 63, .16);
}
.pdx-delivery__meta span {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .875rem; font-weight: 600; color: var(--pdx-gold-ink);
}

/* ── Feature cards — four equal cards ───────────────────────────────── */
.pdx-features {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px; margin-top: 22px;
}
@media (min-width: 480px) and (max-width: 767.98px) { .pdx-features { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
@media (min-width: 1100px) { .pdx-features { grid-template-columns: repeat(4, minmax(0, 1fr)); } }

.pdx-feature {
    background: var(--pdx-surface);
    border: 1px solid var(--pdx-line);
    border-radius: 16px;
    padding: 16px 13px;
    text-align: center;
    transition: transform .22s var(--pdx-lift), border-color .22s var(--pdx-ease), box-shadow .22s var(--pdx-ease);
}
.pdx-feature:hover {
    transform: translateY(-3px);
    border-color: var(--pdx-gold);
    box-shadow: 0 16px 30px -18px rgba(16, 24, 32, .35);
}
.pdx-feature__ico {
    display: grid; place-items: center;
    width: 40px; height: 40px; margin: 0 auto 10px;
    border-radius: 12px;
    background: #fff;
    border: 1px solid var(--pdx-line);
    color: var(--pdx-gold-ink);
}
.pdx-feature__t { font-size: .875rem; font-weight: 700; color: var(--pdx-ink); margin: 0 0 3px; line-height: 1.3; }
.pdx-feature__d { font-size: .78125rem; color: var(--pdx-muted); margin: 0; line-height: 1.45; }

/* ── Payment ────────────────────────────────────────────────────────── */
.pdx-pay { margin-top: 26px; padding-top: 22px; border-top: 1px solid var(--pdx-line-soft); }
.pdx-pay__row {
    display: flex; align-items: center; justify-content: center;
    gap: 10px; flex-wrap: wrap; margin-bottom: 12px;
}
.pdx-pay__note {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    font-size: .875rem; color: var(--pdx-muted); text-align: center; margin: 0;
}
.pdx-pay__note svg { flex: none; color: var(--pdx-ok); }

/* ── Product benefits — two columns ─────────────────────────────────── */
.pdx-benefits {
    display: grid; grid-template-columns: 1fr; gap: 10px 20px;
    margin: 14px 0 0; padding: 0; list-style: none;
}
@media (min-width: 420px) { .pdx-benefits { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
.pdx-benefits li {
    display: flex; align-items: flex-start; gap: 9px;
    font-size: .9375rem; line-height: 1.55; color: var(--pdx-body);
}
.pdx-benefits li svg { flex: none; margin-top: 3px; color: var(--pdx-ok); }

/* ── Share ──────────────────────────────────────────────────────────── */
.pdx-share { display: flex; align-items: center; gap: 10px; margin-top: 22px; }
.pdx-share__k { font-size: .8125rem; font-weight: 600; text-transform: uppercase; letter-spacing: .09em; color: var(--pdx-muted); }
.pdx-share a {
    display: grid; place-items: center;
    width: 38px; height: 38px; border-radius: 50%;
    border: 1px solid var(--pdx-line); color: var(--pdx-muted);
    transition: border-color .2s var(--pdx-ease), color .2s var(--pdx-ease), transform .2s var(--pdx-lift);
}
.pdx-share a:hover { border-color: var(--pdx-gold); color: var(--pdx-gold-ink); transform: translateY(-2px); }
.pdx-share a:focus-visible { outline: 2px solid var(--pdx-ink); outline-offset: 2px; }

/* ── Entrance animation ─────────────────────────────────────────────── */
@media (prefers-reduced-motion: no-preference) {
    .pdx-fade { animation: pdx-fade .5s var(--pdx-ease) both; }
    @keyframes pdx-fade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
}

/* ── Mobile ─────────────────────────────────────────────────────────────
   16px floor on every control (below that iOS zooms the viewport on focus),
   full-width CTAs, and nothing allowed to push the page sideways. */
@media (max-width: 767.98px) {
    .pdx.pd-info-col { border-radius: 16px; }
    .pdx-body { font-size: 1rem; line-height: 1.75; }
    .pdx .pd-variant-pill { min-height: 52px; font-size: 1rem; }
    .pdx .pd-variant-pill--swatch { width: 52px; height: 52px; }
    .pdx.pd-qty-wrap, .pdx .pd-qty-wrap { height: 56px; }
    .pdx .pd-qty-btn { width: 56px; }
    .pdx #pd-qty { width: 56px !important; font-size: 1rem !important; }
    .pdx .pd-btn-cart, .pdx-btn-buy { height: 58px; font-size: 1rem; }
    .pdx-features { gap: 10px; }
    .pdx-trust { gap: 10px 12px; }
}
/* ── Action buttons ─────────────────────────────────────────────────────
   Mobile (default): the stack this block has always been — Add to Cart, then
   Buy It Now, then wishlist, 12px apart. Identical to the old
   `flex flex-col gap-3`, just owned here instead of by utilities.

   Desktop: the same three buttons, re-placed by grid. Three full-width bars
   stacked was a lot of weight for one column and left Add to Cart and Buy It
   Now competing at identical size; pairing the cart with the wishlist and
   giving Buy It Now the full span reads as one deliberate block and takes a
   row less. Source order never changes, so nothing about the tab order or the
   mobile rendering depends on this. */
.pdx-actions { display: flex; flex-direction: column; gap: .75rem; }

@media (min-width: 768px) {
    .pdx-actions {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
        gap: 12px;
    }
    /* Explicit placement — the buttons stay in source order in the DOM. */
    .pdx-actions .pd-btn-cart        { grid-area: 1 / 1; }
    .pdx-actions .wishlist-toggle-btn{ grid-area: 1 / 2; }
    .pdx-actions .pdx-btn-buy        { grid-area: 2 / 1 / 3 / -1; }

    /* 60px was sized for a button alone on its row. Two side by side at that
       height read as slabs, so the whole set comes down to 54px. */
    .pdx .pdx-actions .pd-btn-cart,
    .pdx .pdx-actions .pdx-btn-buy { height: 54px; font-size: .9375rem; }
    .pdx .pdx-actions .wishlist-toggle-btn { height: 54px !important; }

    /* The wishlist is a quiet tertiary when it sits alone under two full-width
       CTAs. As a peer beside Add to Cart it needs an edge or it reads as
       floating text — a hairline in the existing line token, nothing more. Its
       gold hover is untouched. */
    .pdx .pdx-actions .wishlist-toggle-btn {
        border: 1.5px solid var(--pdx-line) !important;
        border-radius: 16px !important;
    }
    .pdx .pdx-actions .wishlist-toggle-btn:hover { border-color: var(--pdx-gold) !important; }

    /* Neither label may wrap inside a fixed-height pill at narrow desktop. */
    .pdx-actions #pd-add-btn-label,
    .pdx-actions .wishlist-btn-text { white-space: nowrap; }
}

/* Between 768 and ~1100 the info column is at its narrowest and "Add to
   wishlist" beside a cart button runs out of room before the icon does. */
@media (min-width: 768px) and (max-width: 1099.98px) {
    .pdx .pdx-actions .pd-btn-cart,
    .pdx .pdx-actions .pdx-btn-buy { font-size: .875rem; }
    .pdx .pdx-actions .wishlist-toggle-btn { font-size: .8125rem !important; }
    .pdx-actions { gap: 10px; }
}

.pdx, .pdx * { min-width: 0; }
.pdx img, .pdx svg { max-width: 100%; }

@media (prefers-reduced-motion: reduce) {
    .pdx *, .pdx *::before, .pdx *::after {
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
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
   an inline height would have needed !important to beat.
   The pill radius overrides the markup's `rounded-xl`: sitting directly
   beside a 999px Add to Cart, a 12px-radius rectangle read as a control
   borrowed from another page. Both buttons are one pair, so one shape. */
.wishlist-toggle-btn { height: 60px; border-radius: 999px; }

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
    /* Clears the two-row bar (colour + qty above price + CTA) so it never
       covers page content. Was 74px when the bar was a single row. */
    body { padding-bottom: 122px; }

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

    /* Two rows: colour + qty on a slim top row, the original price + CTA row
       untouched underneath. One row cannot hold four controls at 320px — the
       CTA would drop under a readable width — and the CTA is the one thing on
       this bar that must never be cramped. */
    .pd-sticky-bar {
        display: flex; flex-direction: column; align-items: stretch; gap: 7px;
        position: fixed; left: 0; right: 0; bottom: 0; z-index: 9990;
        padding: 8px 14px calc(9px + env(safe-area-inset-bottom, 0px));
        background: #fff; border-top: 1px solid #ece7df;
        box-shadow: 0 -6px 20px rgba(0,0,0,.10);
    }
    .pd-sticky-bar__top  { display: flex; align-items: center; gap: 10px; }
    .pd-sticky-bar__main { display: flex; align-items: center; gap: 12px; }
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

    /* ── Selected colour, inside the sticky bar ──
       The swatch row sits near the top of the info column, so a customer who
       has scrolled down to the purchase area had to scroll all the way back up
       to change colour. This chip carries the current selection down with them
       and opens the sheet below. Rendered only when the product has colours. */
    .pd-sticky-color {
        display: inline-flex; align-items: center; gap: 7px;
        flex: 0 1 auto; min-width: 0;
        min-height: 44px; padding: 0 9px 0 8px;
        font: inherit; text-align: left; color: #172430;
        background: #fff; border: 1.5px solid #e3ddd3; border-radius: 999px;
        cursor: pointer;
        transition: border-color .18s ease, background .18s ease;
    }
    .pd-sticky-color:active { border-color: #bb976d; background: #F7F1E8; }
    .pd-sticky-color:focus-visible { outline: 2px solid #172430; outline-offset: 2px; }
    .dark .pd-sticky-color { background: #1f2c38; border-color: #38454f; color: #fff; }
    .pd-sticky-color__dot {
        width: 24px; height: 24px; border-radius: 50%; flex: none;
        border: 2px solid #fff; box-shadow: inset 0 0 0 1px rgba(16,24,32,.18);
    }
    .dark .pd-sticky-color__dot { border-color: #1f2c38; box-shadow: inset 0 0 0 1px rgba(255,255,255,.28); }
    .pd-sticky-color__name {
        font-size: 13px; font-weight: 600; line-height: 1.1;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .pd-sticky-color__caret { flex: none; color: #8a9199; }

    /* ── Quantity, inside the sticky bar ──
       A display, not a second <input name="qty">: #pd-qty in the form stays the
       only control that submits, and these buttons drive it. Two inputs with
       the same name would post twice. */
    .pd-sticky-qty {
        display: inline-flex; align-items: center; flex: none; margin-left: auto;
        height: 44px; border: 1.5px solid #e3ddd3; border-radius: 999px; overflow: hidden;
        background: #fff;
    }
    .dark .pd-sticky-qty { background: #1f2c38; border-color: #38454f; }
    .pd-sticky-qty__btn {
        width: 40px; height: 100%; border: 0; background: transparent;
        display: grid; place-items: center; cursor: pointer; color: #172430;
    }
    .dark .pd-sticky-qty__btn { color: #fff; }
    .pd-sticky-qty__btn:active { background: #F7F1E8; }
    .dark .pd-sticky-qty__btn:active { background: rgba(255,255,255,.08); }
    .pd-sticky-qty__btn:disabled { opacity: .35; cursor: not-allowed; }
    .pd-sticky-qty__btn:focus-visible { outline: 2px solid #172430; outline-offset: -3px; }
    .pd-sticky-qty__val {
        min-width: 26px; text-align: center;
        font-size: 15px; font-weight: 700; color: #172430;
        font-variant-numeric: tabular-nums;
    }
    .dark .pd-sticky-qty__val { color: #fff; }

    /* ── Colour sheet ──
       Only this rule reveals it, so the sheet is unreachable above 768px even
       if the class is left on the element. */
    .pd-color-sheet.is-open { display: block; }

    /* 360px and under. The top row has slack, so only the price + CTA row
       tightens — the CTA keeps a readable label rather than losing the glyph
       and the price both. */
    @media (max-width: 360px) {
        .pd-sticky-bar { padding-left: 10px; padding-right: 10px; }
        .pd-sticky-bar__main { gap: 9px; }
        .pd-sticky-color { padding: 0 7px 0 6px; gap: 5px; }
        .pd-sticky-color__dot { width: 21px; height: 21px; }
        .pd-sticky-qty__btn { width: 36px; }
        .pd-sticky-bar__btn { font-size: 13px; letter-spacing: .02em; gap: 6px; }
        .pd-sticky-bar__btn svg { display: none; }
    }
}

/* ── Mobile colour bottom sheet ──────────────────────────────────────────
   Hidden at every width by default; the rule that shows it lives inside the
   mobile media query above, so the desktop page is untouched by all of this.
   Everything below is inert until `.is-open` is added. */
.pd-color-sheet { display: none; }
.pd-color-sheet__backdrop {
    position: fixed; inset: 0; z-index: 9998;
    background: rgba(16, 24, 32, .48);
    opacity: 0; transition: opacity .26s cubic-bezier(.4,0,.2,1);
    -webkit-tap-highlight-color: transparent;
}
.pd-color-sheet.is-shown .pd-color-sheet__backdrop { opacity: 1; }

.pd-color-sheet__panel {
    position: fixed; left: 0; right: 0; bottom: 0; z-index: 9999;
    display: flex; flex-direction: column;
    max-height: min(72vh, 560px);
    background: #fff;
    border-radius: 20px 20px 0 0;
    box-shadow: 0 -10px 40px rgba(16, 24, 32, .28);
    transform: translateY(100%);
    transition: transform .3s cubic-bezier(.32, .72, 0, 1);
    /* Safe-area inset is paid by .pd-color-sheet__foot, not here — adding it in
       both places would leave a dead band under the Done button on an iPhone. */
}
.pd-color-sheet.is-shown .pd-color-sheet__panel { transform: translateY(0); }
.dark .pd-color-sheet__panel { background: #172430; }

/* Head is also the drag handle, so it never scrolls away from the thumb. */
.pd-color-sheet__head {
    flex: none; padding: 8px 16px 12px;
    border-bottom: 1px solid #f1eee9;
    touch-action: none;
}
.dark .pd-color-sheet__head { border-bottom-color: #2f3b45; }
.pd-color-sheet__grab {
    width: 40px; height: 4px; margin: 0 auto 12px;
    border-radius: 999px; background: #ddd7cd;
}
.dark .pd-color-sheet__grab { background: #3d4a55; }
.pd-color-sheet__row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.pd-color-sheet__title {
    font-family: var(--pg-font-heading, inherit);
    font-size: 1.0625rem; font-weight: 700; line-height: 1.25;
    color: #101820; margin: 0;
}
.dark .pd-color-sheet__title { color: #fff; }
.pd-color-sheet__close {
    display: grid; place-items: center; flex: none;
    width: 36px; height: 36px; margin-right: -6px;
    border: 0; border-radius: 50%;
    background: #f4f1ec; color: #6B7280; cursor: pointer;
}
.pd-color-sheet__close:active { background: #e9e4db; }
.pd-color-sheet__close:focus-visible { outline: 2px solid #101820; outline-offset: 2px; }
.dark .pd-color-sheet__close { background: rgba(255,255,255,.08); color: #fff; }

/* Scrolls on its own axis so a product with many colours never grows the
   sheet past its cap. */
.pd-color-sheet__list {
    flex: 1 1 auto; min-height: 0;
    overflow-y: auto; -webkit-overflow-scrolling: touch; overscroll-behavior: contain;
    padding: 8px 10px 10px;
}

.pd-color-sheet__kicker {
    font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .09em;
    color: #6B7280; margin: 4px 0 6px; padding: 0 12px;
}
.dark .pd-color-sheet__kicker { color: rgba(255,255,255,.55); }

.pd-cs-opt {
    display: flex; align-items: center; gap: 13px; width: 100%;
    min-height: 56px; padding: 8px 12px;
    font: inherit; text-align: left; cursor: pointer;
    background: transparent; border: 1.5px solid transparent; border-radius: 14px;
    transition: background .16s ease, border-color .16s ease;
}
.pd-cs-opt + .pd-cs-opt { margin-top: 2px; }
.pd-cs-opt:active { background: #f7f4f0; }
.pd-cs-opt:focus-visible { outline: 2px solid #101820; outline-offset: -2px; }
.pd-cs-opt.is-selected { background: #F7F1E8; border-color: #8A6A3F; }
.dark .pd-cs-opt:active { background: rgba(255,255,255,.06); }
.dark .pd-cs-opt.is-selected { background: rgba(187,151,109,.18); border-color: #bb976d; }

/* Same treatment as the .pdx-swatch__dot used by the main selector — white
   ring, hairline inset — so the two read as one control, not two designs. */
.pd-cs-opt__dot {
    width: 34px; height: 34px; flex: none; border-radius: 50%;
    border: 2px solid #fff; box-shadow: inset 0 0 0 1px rgba(16, 24, 32, .16);
}
.dark .pd-cs-opt__dot { border-color: #172430; box-shadow: inset 0 0 0 1px rgba(255,255,255,.26); }
.pd-cs-opt__txt { flex: 1 1 auto; min-width: 0; }
.pd-cs-opt__name {
    display: block; font-size: .9375rem; font-weight: 600; line-height: 1.3; color: #101820;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.dark .pd-cs-opt__name { color: #fff; }
.pd-cs-opt.is-selected .pd-cs-opt__name { color: #8A6A3F; }
.dark .pd-cs-opt.is-selected .pd-cs-opt__name { color: #e6c99b; }
.pd-cs-opt__stock { display: block; font-size: .75rem; font-weight: 500; color: #C2321F; margin-top: 2px; }
.pd-cs-opt__tick { flex: none; display: none; color: #8A6A3F; }
.pd-cs-opt.is-selected .pd-cs-opt__tick { display: block; }
.dark .pd-cs-opt.is-selected .pd-cs-opt__tick { color: #e6c99b; }

/* Footer sits outside the scrolling list, so Done stays reachable on a product
   with twenty colours. */
.pd-color-sheet__foot {
    flex: none; padding: 10px 16px calc(14px + env(safe-area-inset-bottom, 0px));
    border-top: 1px solid #f1eee9;
}
.dark .pd-color-sheet__foot { border-top-color: #2f3b45; }
.pd-color-sheet__done {
    display: flex; align-items: center; justify-content: center;
    width: 100%; height: 52px;
    border: 0; border-radius: 999px; cursor: pointer;
    background: #172430; color: #fff;
    font-size: .9375rem; font-weight: 700; letter-spacing: .02em;
}
.pd-color-sheet__done:active { transform: scale(.99); }
.pd-color-sheet__done:focus-visible { outline: 3px solid #bb976d; outline-offset: 2px; }
.dark .pd-color-sheet__done { background: #bb976d; }

@media (prefers-reduced-motion: reduce) {
    .pd-color-sheet__panel, .pd-color-sheet__backdrop { transition-duration: .01ms; }
}

/* ── Buy controls: tablet ────────────────────────────────────────────────
   Between 768px and 1200px the buy row is already side-by-side (sm:flex-row)
   but the info column is at its narrowest, so each button gets barely half of
   it. The desktop label — uppercase, 700 weight, .08em tracking, with a cart
   glyph in front — filled that half edge to edge with no breathing room, and
   the 14px drop shadow spread past the pill until the button read as a dark
   blob rather than a control. Below 768px the buttons stack and none of this
   applies, which is why the existing mobile block never caught it.          */
@media (min-width: 768px) and (max-width: 1199.98px) {
    .pd-buy-row { gap: .625rem; }

    /* Add to Cart is the primary action, so it takes the larger share
       instead of splitting the row down the middle with a secondary one. */
    .pd-buy-row .pd-buy-cart { flex: 1.45; }
    .pd-buy-row .pd-buy-wish { flex: 1; }

    .pd-btn-cart {
        height: 54px;
        font-size: .78rem; letter-spacing: .045em;
        box-shadow: 0 3px 12px rgba(23,36,48,.18);
    }
    .wishlist-toggle-btn { height: 54px; font-size: .8125rem; }

    /* Neither label may wrap to a second line inside a fixed-height pill. */
    #pd-add-btn-label, .wishlist-btn-text { white-space: nowrap; }
    .wishlist-toggle-btn { gap: .375rem; padding-inline: .5rem; }
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
                <div class="pd-info-col pdx pdx-fade">

                    {{-- Category + badges row --}}
                    <div class="pdx-eyebrow">
                        @if($item->category)
                        <a href="{{ route('category.landing', $item->category->slug) }}" class="pdx-eyebrow__cat">
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
                    <h1 class="pdx-title">{{ $item->name }}</h1>

                    {{-- SKU --}}
                    @if($item->sku)
                    <p class="text-[13px] text-[#6B7280] mb-3">SKU: <span class="text-[#101820] font-semibold">{{ $item->sku }}</span></p>
                    @endif

                    {{-- Stars --}}
                    @php
                        $avgR = $item->reviews_avg_rating ?? 0;
                        $revC = $item->reviews_count ?? 0;
                    @endphp
                    {{-- Social proof.
                         Rendered only when the product genuinely has reviews. There
                         is deliberately no hardcoded rating, "N bought today" or
                         "N people viewing": none of those have a source in the data,
                         and stating them as fact would be a fabricated claim (and,
                         for reviews specifically, actionable under FTC 16 CFR 465).
                         When there are no reviews the trust row below the CTA
                         carries the reassurance instead. --}}
                    @if($revC > 0)
                    <div class="pdx-social">
                        <span class="pdx-stars" role="img" aria-label="Rated {{ number_format($avgR,1) }} out of 5 from {{ $revC }} {{ Str::plural('review',$revC) }}">
                            @for($s=1;$s<=5;$s++)
                            <svg width="17" height="17" viewBox="0 0 20 20" fill="{{ $s <= round($avgR) ? '#E8A33D' : '#DEDBD6' }}" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </span>
                        <span class="pdx-social__score">{{ number_format($avgR,1) }}</span>
                        <span class="pdx-social__count">({{ number_format($revC) }} {{ Str::plural('review',$revC) }})</span>
                        {{-- Reviews are their own section now, so this is a plain
                             anchor jump — no tab to activate first. --}}
                        <a href="#reviews" class="pdx-social__link">Read reviews</a>
                    </div>
                    @endif

                    {{-- Price block — stable DOM updated by the variant JS when a
                         colour/size with its own price is selected. Initialised with
                         the product's base price. --}}
                    @php
                        $baseNow = (float) $item->effective_price;
                        $baseWas = $item->has_strike ? (float) $item->price : null;
                    @endphp
                    <div class="pd-price-block pdx">
                        <div class="pdx-price-row">
                            <span id="pd-price-now" class="pdx-price">${{ number_format($baseNow, 2) }}</span>
                            <span id="pd-price-was" class="pdx-was" style="{{ $baseWas ? '' : 'display:none' }}">${{ number_format($baseWas ?? 0, 2) }}</span>
                            <span id="pd-price-badge" class="pdx-off" style="{{ $baseWas ? '' : 'display:none' }}"></span>
                        </div>
                        <p id="pd-price-save" class="pdx-save" style="{{ $baseWas ? '' : 'display:none' }}"></p>
                    </div>

                    {{-- Key features are rendered once, as the two-column "Why you'll
                         like it" list further down — they were previously repeated
                         here and there from the same source. --}}
                    @php $keyFeatures = $item->key_features ? json_decode($item->key_features, true) : []; @endphp

                    {{-- Sizes --}}
                    @if(!empty($item->sizes) && count($item->sizes))
                    <div class="pdx-block">
                        <div class="flex items-center justify-between mb-3 gap-3">
                            <p class="pdx-label">Size</p>
                            @if(!empty($item->size_chart))
                            <button type="button" id="sizeGuideBtn"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#8A6A3F] underline underline-offset-2 hover:text-[#6e532f] cursor-pointer">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 8h20v8H2z"/><path d="M6 8v3M10 8v2M14 8v3M18 8v2"/></svg>
                                Size guide
                            </button>
                            @endif
                        </div>
                        {{-- radiogroup so the set is announced as one control and the
                             arrow keys move between options. --}}
                        <div class="pdx-options" id="size-options" role="radiogroup" aria-label="Size">
                            @foreach($item->sizes as $si => $sz)
                            <label class="cursor-pointer">
                                {{-- Visually hidden but NOT display:none — `hidden` took
                                     these out of the tab order, so sizes could not be
                                     chosen with a keyboard at all. --}}
                                <input class="pdx-vinput size-radio" type="radio" name="size_display" value="{{ $sz }}" {{ $si===0?'checked':'' }}>
                                <span class="pd-variant-pill {{ $si===0?'active':'' }}">
                                    <span class="pdx-check" aria-hidden="true">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                    {{ $sz }}
                                </span>
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
                    <div class="pdx-block">
                        <p class="pdx-label mb-3">
                            Color:
                            <span id="selected-color-label" class="normal-case tracking-normal font-semibold text-[#101820]">{{ $item->colors[0] }}</span>
                        </p>
                        {{-- Real swatches rather than text buttons. The hex comes from
                             Product::colorHex(), the same resolver the colour variants
                             already use, so a name it cannot map degrades to its grey
                             fallback instead of rendering a wrong colour. --}}
                        <div class="pdx-options" id="color-options" role="radiogroup" aria-label="Color">
                            @foreach($item->colors as $ci => $clr)
                            <label class="cursor-pointer">
                                <input class="pdx-vinput color-radio" type="radio" name="color_display" value="{{ $clr }}" {{ $ci===0?'checked':'' }}>
                                <span class="pd-variant-pill pd-variant-pill--swatch {{ $ci===0?'active':'' }}" title="{{ $clr }}">
                                    <span class="pdx-swatch__dot" style="background:{{ \App\Models\Product::colorHex($clr) }}"></span>
                                    <span class="pdx-check" aria-hidden="true">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </span>
                                </span>
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
                        <div class="pdx-block flex items-center gap-5">
                            <p class="pdx-label">Qty</p>
                            <div class="pd-qty-wrap">
                                <button type="button" id="pd-dec" class="pd-qty-btn" aria-label="Decrease quantity">
                                    <svg width="14" height="2" viewBox="0 0 12 2" fill="none" aria-hidden="true"><path d="M1 1H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                                <input id="pd-qty" name="qty" type="number" value="1" min="1" aria-label="Quantity"
                                       style="text-align:center;background:transparent;border:none;outline:none;color:#101820;">
                                <button type="button" id="pd-inc" class="pd-qty-btn" aria-label="Increase quantity">
                                    <svg width="14" height="14" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Mobile keeps the stack: Add to Cart, Buy It Now, wishlist.
                             Desktop re-lays the same three buttons as a grid — cart and
                             wishlist share row 1, Buy It Now spans row 2 — which is why
                             the source order is unchanged and only CSS moves them.
                             The Tailwind flex utilities were dropped from this wrapper so
                             both layouts are defined in one place and cannot be
                             overridden by utility-order surprises. --}}
                        <div class="pdx-block pdx-actions">
                            <button type="submit" id="pd-add-btn" class="pd-btn-cart">
                                <svg class="pdx-cart-ico" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                </svg>
                                {{-- Spinner shares the icon's slot, so the label never shifts --}}
                                <svg class="pdx-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                                    <path d="M21 12a9 9 0 1 1-6.2-8.6" opacity=".9"/>
                                </svg>
                                <span id="pd-add-btn-label">Add to Cart</span>
                            </button>

                            {{-- Buy Now: adds this exact selection to the cart, then goes
                                 straight to checkout. It posts the same form to the same
                                 route as Add to Cart — no new endpoint, no controller
                                 change — and falls back to a normal submit if fetch fails. --}}
                            <button type="button" id="pdx-buy-now" class="pdx-btn-buy"
                                    {{ $pdInStock ? '' : 'disabled aria-disabled=true' }}>
                                <svg class="pdx-cart-ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M13 2 3 14h8l-1 8 10-12h-8z"/>
                                </svg>
                                <svg class="pdx-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                                    <path d="M21 12a9 9 0 1 1-6.2-8.6" opacity=".9"/>
                                </svg>
                                <span id="pdx-buy-now-label">Buy It Now</span>
                            </button>

                            <button type="button"
                                    class="wishlist-toggle-btn w-full flex items-center justify-center gap-2 transition-all duration-200"
                                    style="cursor:pointer;"
                                    data-product-id="{{ $item->id }}"
                                    data-text-add="Add to wishlist"
                                    data-text-remove="In Wishlist">
                                <svg class="wishlist-btn-icon flex-shrink-0" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                                <span class="wishlist-btn-text">Add to wishlist</span>
                            </button>
                        </div>

                        {{-- Trust badges — directly under the CTAs, where the hesitation is --}}
                        <div class="pdx-trust">
                            @foreach([
                                'Secure checkout',
                                'SSL encrypted',
                                '30-day money back',
                                'Free shipping',
                            ] as $badge)
                            <span class="pdx-trust__i">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                {{ $badge }}
                            </span>
                            @endforeach
                        </div>
                    </form>

                    {{-- Delivery estimate.
                         Dates are computed from config('checkout.delivery') — the
                         same numbers the shipping policy page renders — so the
                         window quoted here can never contradict the policy.
                         Worded as an estimate, not a guarantee: business days skip
                         weekends but not public holidays (see DeliveryEstimate). --}}
                    <div class="pdx-delivery">
                        <div class="pdx-delivery__top">
                            <span class="pdx-delivery__ico" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                                </svg>
                            </span>
                            <div>
                                <p class="pdx-delivery__head"><em>FREE</em> delivery on this order</p>
                                <p class="pdx-delivery__date">
                                    Estimated
                                    <strong>{{ $delivery['earliest']->format('D j M') }}</strong>
                                    @unless($delivery['earliest']->isSameDay($delivery['latest']))
                                        – <strong>{{ $delivery['latest']->format('D j M') }}</strong>
                                    @endunless
                                    · {{ $delivery['min_days'] }}–{{ $delivery['max_days'] }} business days
                                </p>
                            </div>
                        </div>
                        <p class="pdx-delivery__meta">
                            <span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                Tracking included
                            </span>
                            <span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                Free returns
                            </span>
                            <span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                No minimum spend
                            </span>
                        </p>
                    </div>

                    {{-- Four feature cards --}}
                    @php
                        $pdxFeatures = [
                            ['t' => 'Free shipping',  'd' => 'On every order',      'i' => 'truck'],
                            ['t' => '30-day returns', 'd' => 'Easy, no quibbles',   'i' => 'return'],
                            ['t' => 'Secure checkout','d' => 'SSL encrypted',       'i' => 'lock'],
                            ['t' => 'Quality checked','d' => 'Before it ships',     'i' => 'star'],
                        ];
                    @endphp
                    <div class="pdx-features">
                        @foreach($pdxFeatures as $f)
                        <div class="pdx-feature">
                            <span class="pdx-feature__ico" aria-hidden="true">
                                @switch($f['i'])
                                    @case('truck')
                                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                        @break
                                    @case('return')
                                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                                        @break
                                    @case('lock')
                                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        @break
                                    @case('star')
                                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        @break
                                @endswitch
                            </span>
                            <p class="pdx-feature__t">{{ $f['t'] }}</p>
                            <p class="pdx-feature__d">{{ $f['d'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Payment icons + benefits + Share --}}
                    <div class="pdx-pay">
                        <div class="pdx-pay__row">
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

                        <p class="pdx-pay__note">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Secure payments powered by trusted providers.
                        </p>

                        {{-- Product benefits.
                             Sourced from the product's own key_features, so the list is
                             true of the item being viewed. Hardcoding "Handmade / No
                             batteries" here would print it on all 503 products,
                             including car chargers. Hidden when the product has none. --}}
                        @if(!empty($keyFeatures))
                        <div class="pdx-block pdx-block--sep">
                            <h2 class="pdx-h3">Why you'll like it</h2>
                            <ul class="pdx-benefits">
                                @foreach($keyFeatures as $feat)
                                <li>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                    <span>{{ $feat }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="pdx-share">
                            <span class="pdx-share__k">Share</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('product-details', $item->slug)) }}" target="_blank" rel="noopener noreferrer nofollow" aria-label="Share on Facebook"
                               >
                                <svg width="8" height="15" viewBox="0 0 9 17" fill="currentColor"><path d="M6.60577 3.57091H8.06641V1.01793C7.35979 0.939731 6.64934 0.901696 5.93845 0.904012C5.44674 0.875673 4.9548 0.955623 4.49713 1.13826C4.03945 1.32089 3.6271 1.60179 3.28898 1.96127C2.95087 2.32075 2.69516 2.7501 2.5398 3.21924C2.38443 3.68838 2.33316 4.18596 2.38957 4.67708V6.92589H0.0664062V9.78076H2.38957V16.9578H5.2382V9.78076H7.46831L7.8224 6.92589H5.2382V4.95961C5.23934 4.13482 5.46065 3.57091 6.60577 3.57091Z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($item->name.' — PeytonGhalib') }}&url={{ urlencode(route('product-details', $item->slug)) }}" target="_blank" rel="noopener noreferrer nofollow" aria-label="Share on Twitter"
                               >
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

    {{-- Top row: current colour + quantity. Both are mirrors of the controls in
         the info column — neither holds state of its own. --}}
    <div class="pd-sticky-bar__top">
        @if(!empty($item->colors) && count($item->colors))
        {{-- Opens the colour sheet. The swatch and name are written from
             whichever .color-radio is checked, so this can never disagree with
             the selector up the page. --}}
        <button type="button" id="pd-sticky-color" class="pd-sticky-color"
                aria-haspopup="dialog" aria-expanded="false" aria-controls="pd-color-sheet"
                aria-label="Color: {{ $item->colors[0] }}. Change color">
            <span class="pd-sticky-color__dot" style="background:{{ \App\Models\Product::colorHex($item->colors[0]) }}" aria-hidden="true"></span>
            <span class="pd-sticky-color__name">{{ $item->colors[0] }}</span>
            <svg class="pd-sticky-color__caret" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        @endif

        {{-- Drives #pd-qty in the form. A <span>, not an input: a second
             control named "qty" would post the quantity twice. --}}
        <div class="pd-sticky-qty" role="group" aria-label="Quantity">
            <button type="button" id="pd-sticky-dec" class="pd-sticky-qty__btn" aria-label="Decrease quantity">
                <svg width="13" height="2" viewBox="0 0 12 2" fill="none" aria-hidden="true"><path d="M1 1H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
            <span class="pd-sticky-qty__val" id="pd-sticky-qty" aria-live="polite">1</span>
            <button type="button" id="pd-sticky-inc" class="pd-sticky-qty__btn" aria-label="Increase quantity">
                <svg width="13" height="13" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    {{-- Bottom row: unchanged from the single-row bar. --}}
    <div class="pd-sticky-bar__main">
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
</div>

@if(!empty($item->colors) && count($item->colors))
{{-- Mobile colour sheet. The options are buttons that check the matching
     .color-radio and fire its change event — the radios stay the single source
     of truth, so the label, the hidden cart input, the gallery jump, the pill
     highlight and the variant price/stock update all run through the handlers
     that already exist. Nothing here renders above 768px. --}}
<div class="pd-color-sheet" id="pd-color-sheet">
    <div class="pd-color-sheet__backdrop" data-pd-cs-dismiss></div>
    <div class="pd-color-sheet__panel" role="dialog" aria-modal="true" aria-labelledby="pd-color-sheet-title">
        <div class="pd-color-sheet__head">
            <div class="pd-color-sheet__grab" aria-hidden="true"></div>
            <div class="pd-color-sheet__row">
                <h2 class="pd-color-sheet__title" id="pd-color-sheet-title">Choose Color</h2>
                <button type="button" class="pd-color-sheet__close" data-pd-cs-dismiss aria-label="Close color picker">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="pd-color-sheet__list" role="radiogroup" aria-labelledby="pd-color-sheet-title">
            <p class="pd-color-sheet__kicker">Color</p>
            @foreach($item->colors as $ci => $clr)
            @php
                // Only flagged when this colour has its own variant row reporting
                // zero — a colour with no variant falls back to product stock and
                // gets no claim made about it either way.
                $csKey   = strtolower(trim($clr));
                $csOut   = isset($pdVariants['color'][$csKey]) && (int) $pdVariants['color'][$csKey]['stock'] <= 0;
            @endphp
            <button type="button" class="pd-cs-opt {{ $ci===0?'is-selected':'' }}" role="radio"
                    aria-checked="{{ $ci===0?'true':'false' }}" tabindex="{{ $ci===0?'0':'-1' }}"
                    data-color="{{ $clr }}">
                <span class="pd-cs-opt__dot" style="background:{{ \App\Models\Product::colorHex($clr) }}" aria-hidden="true"></span>
                <span class="pd-cs-opt__txt">
                    <span class="pd-cs-opt__name">{{ $clr }}</span>
                    @if($csOut)<span class="pd-cs-opt__stock">Out of stock</span>@endif
                </span>
                <svg class="pd-cs-opt__tick" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            </button>
            @endforeach
        </div>
        {{-- Tapping a colour already applies it and closes the sheet. Done is
             the explicit way out for someone who opened the sheet to look
             rather than to change something. --}}
        <div class="pd-color-sheet__foot">
            <button type="button" class="pd-color-sheet__done" data-pd-cs-dismiss>Done</button>
        </div>
    </div>
</div>

<script>
/* Mobile colour sheet — see the markup note above. Mobile-only by construction:
   the trigger lives in the sticky bar and the sheet's only display rule sits
   inside the max-width:767.98px block. */
(function () {
    var sheet   = document.getElementById('pd-color-sheet');
    var trigger = document.getElementById('pd-sticky-color');
    if (!sheet || !trigger) return;

    var panel  = sheet.querySelector('.pd-color-sheet__panel');
    var head   = sheet.querySelector('.pd-color-sheet__head');
    var closeB = sheet.querySelector('.pd-color-sheet__close');
    var doneB  = sheet.querySelector('.pd-color-sheet__done');
    var opts   = Array.prototype.slice.call(sheet.querySelectorAll('.pd-cs-opt'));
    var dotEl  = trigger.querySelector('.pd-sticky-color__dot');
    var nameEl = trigger.querySelector('.pd-sticky-color__name');
    var radios = Array.prototype.slice.call(document.querySelectorAll('.color-radio'));
    var mq     = window.matchMedia('(max-width: 767.98px)');
    var lastFocus = null, hideTimer = null;

    // ── Read the selection back out of the radios ──
    function currentRadio() {
        for (var i = 0; i < radios.length; i++) { if (radios[i].checked) return radios[i]; }
        return radios[0] || null;
    }
    // The hex is already on the main selector's swatch, set inline by Blade, so
    // it is read from there rather than shipped a second time as a JS map.
    function hexOf(radio) {
        var pill = radio.nextElementSibling;
        var dot  = pill ? pill.querySelector('.pdx-swatch__dot') : null;
        return dot ? dot.style.background : '';
    }
    function sync() {
        var r = currentRadio();
        if (!r) return;
        var hex = hexOf(r);
        if (dotEl && hex) dotEl.style.background = hex;
        if (nameEl) nameEl.textContent = r.value;
        trigger.setAttribute('aria-label', 'Color: ' + r.value + '. Change color');
        opts.forEach(function (o) {
            var on = o.getAttribute('data-color') === r.value;
            o.classList.toggle('is-selected', on);
            o.setAttribute('aria-checked', on ? 'true' : 'false');
            o.tabIndex = on ? 0 : -1;
        });
    }
    // Fires for a pick made anywhere — the swatch row up the page, or the sheet.
    radios.forEach(function (r) { r.addEventListener('change', sync); });
    sync();

    // ── Selecting writes through to the radio, never to local state ──
    function pick(value) {
        var target = null;
        radios.forEach(function (r) { if (r.value === value) target = r; });
        if (target && !target.checked) {
            target.checked = true;
            target.dispatchEvent(new Event('change', { bubbles: true }));
        }
        close();
    }

    // ── Open / close ──
    // Scroll is held with overflow on the scrolling elements — the same approach
    // the size-chart modal uses — rather than a position:fixed body lock, which
    // would drop the page back to the top when released.
    function open() {
        if (!mq.matches) return;
        clearTimeout(hideTimer);
        lastFocus = document.activeElement;
        sheet.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
        trigger.setAttribute('aria-expanded', 'true');
        requestAnimationFrame(function () { sheet.classList.add('is-shown'); });
        var sel = sheet.querySelector('.pd-cs-opt.is-selected') || closeB;
        if (sel) sel.focus({ preventScroll: true });
    }
    function close() {
        if (!sheet.classList.contains('is-open')) return;
        sheet.classList.remove('is-shown');
        trigger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        // Focus returns to the chip, which is fixed to the viewport — so the page
        // stays exactly where the customer left it. iOS does not focus a button
        // on tap, so lastFocus is <body> there; the chip is the useful target.
        var back = (lastFocus && lastFocus !== document.body && lastFocus.focus) ? lastFocus : trigger;
        back.focus({ preventScroll: true });
        hideTimer = setTimeout(function () { sheet.classList.remove('is-open'); }, 320);
    }

    trigger.addEventListener('click', open);
    sheet.addEventListener('click', function (e) {
        if (e.target.closest('[data-pd-cs-dismiss]')) { close(); return; }
        var opt = e.target.closest('.pd-cs-opt');
        if (opt) pick(opt.getAttribute('data-color'));
    });

    // ── Keyboard: Escape closes, arrows move between options, Tab is trapped ──
    sheet.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { e.preventDefault(); close(); return; }

        if (e.key === 'ArrowDown' || e.key === 'ArrowRight' || e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
            var cur = opts.indexOf(document.activeElement);
            if (cur === -1) return;
            e.preventDefault();
            var step = (e.key === 'ArrowDown' || e.key === 'ArrowRight') ? 1 : -1;
            var next = opts[(cur + step + opts.length) % opts.length];
            next.tabIndex = 0;
            next.focus({ preventScroll: true });
            return;
        }

        if (e.key === 'Tab') {
            var f = [closeB]
                .concat(opts.filter(function (o) { return o.tabIndex === 0; }))
                .concat([doneB]);
            f = f.filter(Boolean);
            if (!f.length) return;
            var first = f[0], last = f[f.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus({ preventScroll: true }); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus({ preventScroll: true }); }
        }
    });

    // ── Drag the head down to dismiss ──
    var startY = 0, dy = 0, dragging = false;
    head.addEventListener('touchstart', function (e) {
        if (!e.touches || e.touches.length !== 1) return;
        dragging = true; startY = e.touches[0].clientY; dy = 0;
        panel.style.transition = 'none';
    }, { passive: true });
    head.addEventListener('touchmove', function (e) {
        if (!dragging) return;
        dy = Math.max(0, e.touches[0].clientY - startY);
        panel.style.transform = 'translateY(' + dy + 'px)';
    }, { passive: true });
    function endDrag() {
        if (!dragging) return;
        dragging = false;
        panel.style.transition = '';
        panel.style.transform = '';
        if (dy > 80) close();
    }
    head.addEventListener('touchend', endDrag);
    head.addEventListener('touchcancel', endDrag);

    // Rotating or resizing past the breakpoint takes the trigger off screen, so
    // the sheet must not be left hanging over the desktop layout.
    var onMq = function () { if (!mq.matches) close(); };
    if (mq.addEventListener) mq.addEventListener('change', onMq);
    else if (mq.addListener) mq.addListener(onMq);
}());
</script>
@endif

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
    // #pd-qty is the one control that submits; the stepper in the mobile sticky
    // bar is a mirror of it, not a second quantity. Both sets of buttons go
    // through setQty so the two can never show different numbers.
    var qtyEl = document.getElementById('pd-qty');
    var dec = document.getElementById('pd-dec');
    var inc = document.getElementById('pd-inc');
    var sDec = document.getElementById('pd-sticky-dec');
    var sInc = document.getElementById('pd-sticky-inc');
    var sVal = document.getElementById('pd-sticky-qty');

    function readQty(){ var v = parseInt(qtyEl && qtyEl.value, 10); return (isNaN(v) || v < 1) ? 1 : v; }
    function paintQty(){
        var v = readQty();
        if (sVal) sVal.textContent = v;
        if (sDec) sDec.disabled = v <= 1;
        if (dec)  dec.disabled  = v <= 1;
    }
    function setQty(v){
        if (!qtyEl) return;
        qtyEl.value = Math.max(1, v);
        paintQty();
    }

    if (qtyEl) {
        if (dec)  dec.addEventListener('click',  function(){ setQty(readQty() - 1); });
        if (inc)  inc.addEventListener('click',  function(){ setQty(readQty() + 1); });
        if (sDec) sDec.addEventListener('click', function(){ setQty(readQty() - 1); });
        if (sInc) sInc.addEventListener('click', function(){ setQty(readQty() + 1); });
        // Typing straight into the field is still allowed, so mirror that too.
        qtyEl.addEventListener('input',  paintQty);
        qtyEl.addEventListener('change', paintQty);
        paintQty();
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
                    <div class="rich-content pd-prose">
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
                <div class="rich-content pd-prose">
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


{{-- ═══ Premium product-info behaviour (pdx) ═══
     Additive only: nothing here rebinds or replaces an existing handler. The
     cart form, variant sync and sticky bar keep the listeners they already had. --}}
@push('scripts')
<script>
(function () {
    var form = document.getElementById('pd-cart-form');
    if (!form) return;

    // ── Ripple ──────────────────────────────────────────────────────────
    function ripple(btn, ev) {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        var r = btn.getBoundingClientRect();
        var d = Math.max(r.width, r.height);
        var s = document.createElement('span');
        s.className = 'pdx-ripple';
        s.style.width = s.style.height = d + 'px';
        s.style.left = ((ev.clientX || r.left + r.width / 2) - r.left - d / 2) + 'px';
        s.style.top  = ((ev.clientY || r.top + r.height / 2) - r.top - d / 2) + 'px';
        btn.appendChild(s);
        setTimeout(function () { s.remove(); }, 600);
    }

    [document.getElementById('pd-add-btn'), document.getElementById('pdx-buy-now')].forEach(function (b) {
        if (b) b.addEventListener('click', function (e) { ripple(b, e); });
    });

    // ── Buy It Now ──────────────────────────────────────────────────────
    // Posts the SAME form to the SAME route as Add to Cart, then forwards to
    // checkout. No new endpoint and no controller change: the server sees an
    // ordinary cart.add request carrying the chosen qty / size / colour.
    //
    // If anything about the fetch fails (offline, CSRF rotation, 5xx) it falls
    // back to a plain form submit, so the worst case is the normal add-to-cart
    // flow rather than a dead button.
    var buy = document.getElementById('pdx-buy-now');

    if (buy) {
        buy.addEventListener('click', function () {
            if (buy.disabled || buy.dataset.loading === 'true') return;

            buy.dataset.loading = 'true';
            buy.disabled = true;
            var label = document.getElementById('pdx-buy-now-label');
            var prev  = label ? label.textContent : '';
            if (label) label.textContent = 'Taking you to checkout…';

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                redirect: 'follow'
            }).then(function (res) {
                if (!res.ok) throw new Error('add failed');
                window.location.assign(@json(route('checkout')));
            }).catch(function () {
                // Let the browser do it the ordinary way.
                buy.dataset.loading = 'false';
                buy.disabled = false;
                if (label) label.textContent = prev;
                form.submit();
            });
        });
    }

    // ── Add to Cart loading state ───────────────────────────────────────
    // Submit is left completely alone — this only paints the spinner so the
    // 60px button never looks inert while the POST is in flight.
    var add = document.getElementById('pd-add-btn');

    if (add) {
        form.addEventListener('submit', function () {
            add.dataset.loading = 'true';
        });
    }
})();
</script>
@endpush
