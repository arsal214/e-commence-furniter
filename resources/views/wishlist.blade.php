{{-- resources/views/wishlist.blade.php --}}
@extends('layouts.main')

@section('title', 'My Wishlist — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@php
    // Wishlist rows whose product was deleted are skipped, so count what we'll actually show.
    $saved = $wishlistItems->filter(fn ($row) => $row->product);
@endphp

@section('content')

@include('includes.navbar')

<x-account.shell
    active="wishlist"
    :user="$user"
    crumb="Wishlist"
    heading="Wishlist"
    subheading="Things you've saved for later."
>

    <section class="acc-panel" aria-labelledby="acc-wishlist-title">
        <div class="acc-panel__head">
            <h2 class="acc-panel__title" id="acc-wishlist-title">
                Saved items
                @if ($saved->isNotEmpty())
                    <span class="acc-order__date">({{ $saved->count() }})</span>
                @endif
            </h2>

            @if ($saved->isNotEmpty())
                <a class="acc-link" href="{{ url('/shop') }}">
                    Keep browsing
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

        @if ($saved->isEmpty())
            <div class="acc-empty">
                <div class="acc-empty__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1L12 21l7.7-7.7 1.1-1a5.5 5.5 0 0 0 0-7.7z"/></svg>
                </div>
                <p class="acc-empty__title">Your wishlist is empty</p>
                <p class="acc-empty__text">Tap the heart on any product and it'll be waiting for you here.</p>
                <a class="acc-btn" href="{{ url('/shop') }}">Browse products</a>
            </div>
        @else
            <div class="acc-cards">
                @foreach ($saved as $row)
                    @php
                        $product  = $row->product;
                        $img      = $product->image;
                        $inStock  = (int) $product->stock > 0 || is_null($product->stock);
                    @endphp

                    <article class="acc-card">
                        <a class="acc-card__media" href="{{ route('product-details', $product->slug) }}">
                            @if ($img)
                                <img class="acc-card__img"
                                     src="{{ Str::startsWith($img, 'assets/') ? asset($img) : Storage::url($img) }}"
                                     alt="{{ $product->name }}" loading="lazy">
                            @else
                                <span class="acc-card__placeholder" aria-hidden="true">
                                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                </span>
                            @endif

                            @if ($product->tag)
                                <span class="acc-card__tag">{{ $product->tag }}</span>
                            @endif
                        </a>

                        <form method="POST" action="{{ route('wishlist.remove') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button class="acc-card__remove" type="submit"
                                    aria-label="Remove {{ $product->name }} from wishlist">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </button>
                        </form>

                        <div class="acc-card__body">
                            <h3 class="acc-card__name">
                                <a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a>
                            </h3>

                            <div class="acc-card__price">
                                <span class="acc-card__now">{{ $product->display_price }}</span>
                                @if ($product->has_strike)
                                    <span class="acc-card__was">{{ $product->was_price }}</span>
                                    <span class="acc-sr">reduced from {{ $product->was_price }}</span>
                                @endif
                            </div>

                            @if ($inStock)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button class="acc-card__btn" type="submit">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/><path d="M2 3h3l2.4 12h11L21 7H6"/></svg>
                                        Add to cart
                                    </button>
                                </form>
                            @else
                                <span class="acc-card__oos">Out of stock</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

</x-account.shell>

@include('includes.footer')

@endsection
