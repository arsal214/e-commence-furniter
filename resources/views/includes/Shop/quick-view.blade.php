{{-- Injected into the #pg-qv modal by the shop grid. No layout — partial only.
     Uses scoped pg-qv* classes rather than Tailwind utilities, because style.css
     loads after the Vite build and silently overrides Vite-only variants. --}}
@php
    $img = $product->image
        ? (str_starts_with($product->image, 'assets/') ? asset($product->image) : Storage::url($product->image))
        : null;

    $inStock = $product->stock === null || $product->stock > 0;
    $rating  = $product->avgRating();
    $reviews = $product->reviewCount();
@endphp

<style>
    .pg-qv-grid { display: grid; grid-template-columns: 1fr; }
    @media (min-width: 768px) { .pg-qv-grid { grid-template-columns: 1fr 1fr; } }
    .pg-qv-media { background: #F6F6F6; aspect-ratio: 4 / 5; }
    .dark .pg-qv-media { background: rgba(255,255,255,.06); }
    .pg-qv-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pg-qv-media__empty { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #6B6560; font-size: 14px; }
    .pg-qv-info { padding: 28px 28px 28px 24px; }
    .pg-qv-cat { font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #8A6A3F; margin: 0 0 8px; }
    .dark .pg-qv-cat { color: #BB976D; }
    .pg-qv-title { font-size: 22px; line-height: 1.3; font-weight: 700; color: #172430; margin: 0; }
    .dark .pg-qv-title { color: #fff; }
    .pg-qv-rating { display: flex; align-items: center; gap: 8px; margin-top: 10px; font-size: 13px; color: #6B6560; }
    .dark .pg-qv-rating { color: #DBDBDB; }
    .pg-qv-stars { display: inline-flex; gap: 2px; color: #BB976D; }
    .pg-qv-price { display: flex; align-items: baseline; gap: 10px; margin-top: 16px; flex-wrap: wrap; }
    .pg-qv-price__now { font-size: 24px; font-weight: 700; color: #172430; font-variant-numeric: tabular-nums; }
    .dark .pg-qv-price__now { color: #fff; }
    .pg-qv-price__was { font-size: 14px; color: #6B6560; text-decoration: line-through; font-variant-numeric: tabular-nums; }
    .pg-qv-price__save { font-size: 12px; font-weight: 700; color: #fff; background: #172430; border-radius: 999px; padding: 3px 9px; }
    .dark .pg-qv-price__save { background: #BB976D; color: #172430; }
    .pg-qv-desc { margin-top: 16px; font-size: 14px; line-height: 1.65; color: #3C474E; }
    .dark .pg-qv-desc { color: #DBDBDB; }
    .pg-qv-stock { margin-top: 14px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }
    .pg-qv-stock--in  { color: #1F7A4C; }
    .pg-qv-stock--out { color: #C62828; }
    .dark .pg-qv-stock--in { color: #fff; }
    .pg-qv-actions { margin-top: 22px; display: flex; flex-direction: column; gap: 10px; }
    .pg-qv-btn {
        min-height: 48px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;
        border: 1px solid transparent; text-decoration: none; width: 100%;
        transition: opacity .2s ease, border-color .2s ease, color .2s ease;
        touch-action: manipulation;
    }
    .pg-qv-btn--primary { background: #BB976D; color: #172430; }
    .pg-qv-btn--primary:hover { opacity: .9; }
    .pg-qv-btn--ghost { border-color: #E8E1D7; color: #172430; background: transparent; }
    .pg-qv-btn--ghost:hover { border-color: #BB976D; color: #BB976D; }
    .dark .pg-qv-btn--ghost { border-color: #2F3B45; color: #fff; }
    .pg-qv-btn:focus-visible { outline: 2px solid #BB976D; outline-offset: 2px; }
    .pg-qv-btn[disabled] { opacity: .5; cursor: not-allowed; }
</style>

<div class="pg-qv-grid">
    <div class="pg-qv-media">
        @if ($img)
            <img src="{{ $img }}" alt="{{ $product->name }}" width="400" height="500">
        @else
            <span class="pg-qv-media__empty">No image</span>
        @endif
    </div>

    <div class="pg-qv-info">
        @if ($product->category?->name)
            <p class="pg-qv-cat">{{ $product->category->name }}</p>
        @endif

        {{-- id matches aria-labelledby on the dialog --}}
        <h2 class="pg-qv-title" id="pg-qv-title">{{ $product->name }}</h2>

        @if ($reviews > 0)
            <div class="pg-qv-rating">
                <span class="pg-qv-stars" aria-hidden="true">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg width="14" height="14" viewBox="0 0 24 24"
                             fill="{{ $i <= round($rating) ? 'currentColor' : 'none' }}"
                             stroke="currentColor" stroke-width="1.5">
                            <path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1z"/>
                        </svg>
                    @endfor
                </span>
                {{-- Not colour/shape only: the rating is stated in text too --}}
                <span>{{ number_format($rating, 1) }} out of 5 ({{ $reviews }} {{ \Str::plural('review', $reviews) }})</span>
            </div>
        @endif

        {{-- display_price / was_price come from the model — the same values the cart charges --}}
        <div class="pg-qv-price">
            <span class="pg-qv-price__now">{{ $product->display_price }}</span>
            @if ($product->was_price)
                <span class="pg-qv-price__was">{{ $product->was_price }}</span>
                @php $savePct = (int) round((($product->price - $product->effective_price) / $product->price) * 100); @endphp
                @if ($savePct > 0)
                    <span class="pg-qv-price__save">Save {{ $savePct }}%</span>
                @endif
            @endif
        </div>

        @if ($product->description)
            <p class="pg-qv-desc">{{ \Str::limit(strip_tags($product->description), 180) }}</p>
        @endif

        <p class="pg-qv-stock {{ $inStock ? 'pg-qv-stock--in' : 'pg-qv-stock--out' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                @if ($inStock)
                    <path d="M20 6 9 17l-5-5"/>
                @else
                    <circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>
                @endif
            </svg>
            {{ $inStock ? 'In stock' : 'Out of stock' }}
        </p>

        <div class="pg-qv-actions">
            @if ($inStock)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="pg-qv-btn pg-qv-btn--primary">Add to Cart</button>
                </form>
            @else
                <button type="button" class="pg-qv-btn pg-qv-btn--primary" disabled aria-disabled="true">Out of Stock</button>
            @endif

            <a href="{{ route('product-details', ['slug' => $product->slug]) }}" class="pg-qv-btn pg-qv-btn--ghost">
                View Full Details
            </a>
        </div>
    </div>
</div>
