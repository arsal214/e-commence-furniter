@extends('layouts.pg')

@php
  $p = $item;
  $seller = $p->seller;
  $img = fn ($path) => \App\Models\Product::imageUrl($path);
  $gallery = collect([$p->image])->merge($p->productImages->where('type', 'image')->pluck('image'))->filter()->unique()->values();
  $avg = round((float) ($p->reviews_avg_rating ?? 0), 1);
  $title = \App\Support\SiteCopy::productTitle($p);
  $desc = \App\Support\SiteCopy::productDescription($p);
  $json = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
@endphp

@section('title', $title)
@section('meta_description', $desc)
@section('og_type', 'product')
@if($p->image)@section('og_image', $img($p->image))@endif

@push('schema')
<script type="application/ld+json">{!! json_encode(array_filter([
  '@context' => 'https://schema.org', '@type' => 'Product', 'name' => $p->name, 'description' => $desc, 'sku' => $p->sku ?: $p->uuid,
  'image' => $gallery->map($img)->all(), 'brand' => $p->brand ? ['@type' => 'Brand', 'name' => $p->brand] : null,
  'offers' => ['@type' => 'Offer', 'priceCurrency' => 'USD', 'price' => number_format((float) $p->from_price, 2, '.', ''), 'url' => url()->current(),
    'availability' => $p->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
    'seller' => ['@type' => 'Organization', 'name' => $seller?->store_name ?? 'Peyton Ghalib']],
  'aggregateRating' => ($p->reviews_count ?? 0) > 0 ? ['@type' => 'AggregateRating', 'ratingValue' => $avg, 'reviewCount' => $p->reviews_count] : null,
]), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
@include('market.dc.product')
@endsection

@push('scripts')
<script>window.PDP = {!! json_encode($pdp['variants'], $json) !!};</script>
<script src="{{ asset('js/pdp.js') }}?v={{ filemtime(public_path('js/pdp.js')) }}" defer></script>
<script>
(function () {
  if (typeof fbq === 'function' && !window.__pgMetaViewContentFired) {
    window.__pgMetaViewContentFired = true;
    var payload = { content_type: 'product', content_ids: [{!! json_encode($p->uuid, $json) !!}], content_name: {!! json_encode($p->name, $json) !!}, value: {{ number_format((float) $p->effective_price, 2, '.', '') }}, currency: 'USD' };
    fbq('track', 'ViewContent', payload, { eventID: {!! json_encode($metaViewContentId, $json) !!} });
    if (typeof gtag === 'function') gtag('event', 'view_item', { currency: 'USD', value: payload.value, items: [{ item_id: payload.content_ids[0], item_name: payload.content_name, price: payload.value }] });
  }
})();
</script>
@endpush
