@props(['p'])
@php
  $simple = ! $p->colors && ! $p->sizes && $p->variants->where('is_active', true)->isEmpty();
  $pct = $p->discount_percent;
  $rating = round((float) ($p->reviews_avg_rating ?? 0), 1);
@endphp
<article class="mk-card">
  <a class="mk-card__img" href="{{ route('product-details', $p->slug) }}" tabindex="-1" aria-hidden="true">
    @if($p->image)<img src="{{ \App\Models\Product::imageUrl($p->image) }}" alt="" loading="lazy" width="400" height="400">@endif
    @if($pct)<span class="mk-card__tag">-{{ $pct }}%</span>@endif
  </a>
  <div class="mk-card__body">
    <a class="mk-card__name" href="{{ route('product-details', $p->slug) }}">{{ $p->name }}</a>
    <span class="mk-card__seller">{{ $p->seller?->store_name ?? 'Peyton Ghalib' }}</span>
    @if(($p->reviews_count ?? 0) > 0)
      <span class="mk-stars"><i aria-hidden="true">{{ str_repeat('★', (int) round($rating)) }}{{ str_repeat('☆', 5 - (int) round($rating)) }}</i> {{ $rating }} ({{ $p->reviews_count }})</span>
    @endif
    @if($sold = \App\Services\SoldCounts::label($p->id))<span class="mk-card__seller">{{ $sold }}</span>@endif
    <span class="mk-price">
      @if($p->has_price_range)<span style="font-size:12px;color:var(--pg-ink-2)">From</span>@endif
      <b>${{ number_format($p->from_price, 2) }}</b>
      @if($p->has_strike)<s>${{ number_format((float) $p->price, 2) }}</s>@endif
    </span>
    @if($p->stock <= 0 && $simple)
      <span class="mk-instock out">Sold out</span>
    @elseif($simple)
      <form method="POST" action="{{ route('cart.add') }}" data-track="{{ json_encode(['id' => $p->uuid, 'name' => $p->name, 'price' => (float) $p->effective_price]) }}">
        @csrf <input type="hidden" name="product_id" value="{{ $p->uuid }}"><input type="hidden" name="qty" value="1">
        <button class="pg-btn" type="submit">Add to cart</button>
      </form>
    @else
      <a class="pg-btn ghost" href="{{ route('product-details', $p->slug) }}" style="height:40px;font-size:14px">Choose options</a>
    @endif
  </div>
</article>
