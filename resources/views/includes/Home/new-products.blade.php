@forelse ($newProducts as $product)
    @php
        $imgSrc = $product->image
            ? (str_starts_with($product->image, 'assets/') ? asset($product->image) : Storage::url($product->image))
            : null;
        // Badge states only what is true: real sale, real bestseller, real new tag.
        $badge = null; $badgeCls = 'na-badge--dark';
        if ($product->was_price && $product->discount_percent) { $badge = 'Sale'; $badgeCls = 'na-badge--sale'; }
        elseif ($product->is_best_seller) { $badge = 'Bestseller'; }
        elseif ($product->tag === 'NEW') { $badge = 'New'; }
    @endphp
    <article class="na-card" data-cat="{{ $product->category->name ?? '' }}">
        <div class="na-media">
            <a href="{{ route('product-details', $product->slug) }}" class="na-media__link" aria-label="{{ $product->name }}">
                @if ($imgSrc)
                    <img loading="lazy" decoding="async" src="{{ $imgSrc }}" alt="{{ $product->name }}">
                @else
                    <span class="na-media__empty"><i class="mdi mdi-image-off"></i></span>
                @endif
            </a>
            @if ($badge)<span class="na-badge {{ $badgeCls }}">{{ $badge }}</span>@endif

            <button type="button" class="na-heart wishlist-toggle-btn"
                    data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" aria-label="Add to wishlist">
                <svg class="fill-current wishlist-icon-outline" width="16" height="15" viewBox="0 0 24 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969Z"/></svg>
                <span class="sr-only wishlist-btn-text">Add to wishlist</span>
            </button>

            @if ($product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="na-quick">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="qty" value="1">
                <button type="submit">Quick add</button>
            </form>
            @endif
        </div>
        <div class="na-info">
            <p class="na-cat">{{ $product->category->name ?? '' }}</p>
            <h3 class="na-name"><a href="{{ route('product-details', $product->slug) }}">{{ $product->name }}</a></h3>
            <div class="na-price">
                <span class="na-price__now">{{ $product->display_price }}</span>
                @if ($product->was_price)
                    <span class="na-price__was">{{ $product->was_price }}</span>
                    @if ($product->discount_percent)<span class="na-price__save">Save {{ $product->discount_percent }}%</span>@endif
                @endif
            </div>
            @if ($product->stock > 0 && $product->stock <= 5)
                <p class="na-low">Only {{ $product->stock }} left</p>
            @endif
        </div>
    </article>
@empty
    <div class="col-span-full py-8 text-center text-gray-400">No products found.</div>
@endforelse
