@extends('layouts.pg')
@section('title', $title)
@section('meta_description', $description)
@if(! empty($robots))@section('robots', $robots)@endif

@section('content')
<div class="mk-wrap">
  <nav class="mk-crumbs" aria-label="Breadcrumb">
    <a href="{{ url('/') }}">Home</a> ›
    @if($parent && $category && $parent->id !== $category->id)<a href="{{ route('category.landing', $parent->slug) }}">{{ $parent->name }}</a> ›@endif
    <span>{{ $heading }}</span>
  </nav>

  @if($store)
    <div class="mk-box" style="margin-top:14px;flex-direction:row;align-items:center;gap:14px">
      <span class="mk-store__logo" style="width:64px;height:64px">@if($store->logo)<img src="{{ \App\Models\Product::imageUrl($store->logo) }}" alt="">@else{{ mb_substr($store->store_name, 0, 1) }}@endif</span>
      <span><h1 style="margin:0;font-size:22px">{{ $store->store_name }}</h1>
        <span class="mk-badge {{ $store->level }}">{{ ucfirst($store->level) }} Seller</span>
        <span class="pg-muted"> · {{ $store->city }}, {{ $store->state }}</span>
        @if($store->about)<p class="pg-muted" style="margin:6px 0 0">{{ $store->about }}</p>@endif</span>
    </div>
  @else
    <h1 style="margin:14px 0 0;font-size:clamp(22px,3.4vw,30px);letter-spacing:-.02em">{{ $heading }}</h1>
  @endif

  @if(! empty($foundStores) && $foundStores->isNotEmpty())
    <div class="mk-stores" style="margin-top:14px">
      @foreach($foundStores as $s)
        <a class="mk-store" href="{{ route('store.show', $s->slug) }}"><span class="mk-store__logo">{{ mb_substr($s->store_name, 0, 1) }}</span><span><b>{{ $s->store_name }}</b><small>Store</small></span></a>
      @endforeach
    </div>
  @endif

  <div class="mk-list">
    <aside class="mk-filters" aria-label="Filters">
      @if($category && $parent)
        <h3>{{ $parent->name }}</h3>
        @foreach($tree->firstWhere('id', $parent->id)?->children ?? [] as $sub)
          <a href="{{ route('category.landing', $sub->slug) }}" class="{{ $category->id === $sub->id ? 'on' : '' }}">{{ $sub->name }} <span class="pg-muted">({{ $sub->shop_count }})</span></a>
        @endforeach
      @elseif(! $store)
        <h3>Categories</h3>
        @foreach($tree as $c)<a href="{{ route('category.landing', $c->slug) }}">{{ $c->name }} <span class="pg-muted">({{ $c->shop_count }})</span></a>@endforeach
      @endif

      <form method="GET" style="display:flex;flex-direction:column;gap:10px">
        @if($query)<input type="hidden" name="q" value="{{ $query }}">@endif
        <input type="hidden" name="sort" value="{{ $sort }}">
        <h3>Price</h3>
        <div class="row"><input type="number" name="min" min="0" step="1" inputmode="decimal" placeholder="Min" value="{{ $min }}" aria-label="Minimum price"><input type="number" name="max" min="0" step="1" inputmode="decimal" placeholder="Max" value="{{ $max }}" aria-label="Maximum price"></div>
        <label style="display:flex;gap:8px;align-items:center"><input type="checkbox" name="instock" value="1" @checked(request()->boolean('instock')) style="width:18px;height:18px"> In stock only</label>
        <button class="pg-btn" type="submit" style="height:42px;font-size:14px">Apply</button>
      </form>
    </aside>

    <div>
      <form class="mk-bar" method="GET">
        <span>{{ number_format($products->total()) }} {{ \Illuminate\Support\Str::plural('result', $products->total()) }}</span>
        @foreach(['q' => $query, 'min' => $min, 'max' => $max, 'instock' => request('instock')] as $k => $v)@if($v !== null && $v !== '')<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif @endforeach
        <label class="mk-sort">Sort by
          <select name="sort" onchange="this.form.submit()">
            @foreach(['newest' => 'Newest', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'rating' => 'Top rated'] as $k => $label)<option value="{{ $k }}" @selected($sort === $k)>{{ $label }}</option>@endforeach
          </select>
        </label>
        <noscript><button class="pg-btn" type="submit" style="width:auto;height:40px">Sort</button></noscript>
      </form>

      @if($products->isEmpty())
        <div class="mk-box"><b>No products found.</b><span class="pg-muted">Try removing a filter or searching for something else.</span><a class="pg-btn" href="{{ route('shop') }}" style="width:auto;align-self:flex-start">Browse all products</a></div>
      @else
        <div class="mk-grid">@foreach($products as $p)<x-mk.card :p="$p" />@endforeach</div>
        <div class="mk-pages">{{ $products->links() }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
