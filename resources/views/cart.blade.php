@extends('layouts.pg')

@section('title', 'Your cart — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')
@include('market.dc.cart')

{{-- Extras that sit under the cart: sets that complete something in it, and companion products. --}}
@if(! empty($cartItems) && ($bundleOffers->isNotEmpty() || $crossSell->isNotEmpty()))
<div style="max-width:1320px;margin:0 auto;padding:28px clamp(12px,4vw,40px) 0;display:flex;flex-direction:column;gap:20px">
  @foreach ($bundleOffers as $bundle)
    <section aria-label="Complete the set" style="border:1px solid #E5E7EB;border-radius:20px;padding:20px;display:flex;flex-direction:column;gap:10px">
      <h2 style="margin:0;font-size:18px;font-weight:800">Complete the set &mdash; save ${{ number_format($bundle->savings(), 2) }}</h2>
      <p style="margin:0;font-size:14px;color:#4B5563;line-height:1.5">{{ $bundle->name }}: {{ $bundle->items->map(fn ($i) => $i->product->name . ' ($' . number_format($i->unitPrice(), 2) . ')')->implode(' + ') }}.
        Regular ${{ number_format($bundle->regularTotal(), 2) }}, set price <strong>${{ number_format($bundle->price, 2) }}</strong>.</p>
      <form method="POST" action="{{ route('cart.add-bundle') }}">
        @csrf
        <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
        <button type="submit" style="height:46px;padding:0 22px;border-radius:999px;border:1.5px solid #111827;background:#fff;font-weight:700;cursor:pointer">Add the set to cart</button>
      </form>
    </section>
  @endforeach

  @if($crossSell->isNotEmpty())
    <section aria-labelledby="xsell-title" style="display:flex;flex-direction:column;gap:12px">
      <div><h2 id="xsell-title" style="margin:0;font-size:20px;font-weight:800">You might also like</h2>
        <span style="font-size:14px;color:#4B5563">Popular add-ons shoppers grab before checkout.</span></div>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(176px,44%),1fr));gap:20px 16px">
        @foreach($crossSell as $p)
          <div style="display:flex;flex-direction:column;gap:8px;min-width:0">
            <a href="{{ route('product-details', $p->slug) }}" style="display:block;aspect-ratio:1;border-radius:16px;background:#F3F4F6;overflow:hidden">
              @if($p->image)<img src="{{ \App\Models\Product::imageUrl($p->image) }}" alt="{{ $p->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover">@endif
            </a>
            <a href="{{ route('product-details', $p->slug) }}" style="font-size:14px;line-height:1.35;color:#111827;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $p->name }}</a>
            <b style="font-size:16px;font-weight:800">{{ $p->display_price }}</b>
            <form method="POST" action="{{ route('cart.add') }}" data-track="{{ json_encode(['id' => $p->id, 'name' => $p->name, 'price' => round((float) $p->effective_price, 2)]) }}" style="margin-top:auto">
              @csrf
              <input type="hidden" name="product_id" value="{{ $p->id }}">
              <input type="hidden" name="qty" value="1">
              <button type="submit" aria-label="Add {{ $p->name }} to cart" style="width:100%;height:44px;border-radius:999px;border:1.5px solid #111827;background:#fff;font-weight:700;cursor:pointer">+ Add</button>
            </form>
          </div>
        @endforeach
      </div>
    </section>
  @endif
</div>
@endif
@endsection
