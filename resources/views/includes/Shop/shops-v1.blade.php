@foreach ($products as $item)
    @php
        $img = $item->image
            ? (str_starts_with($item->image, 'assets/') ? asset($item->image) : Storage::url($item->image))
            : null;

        $url      = route('product-details', ['slug' => $item->slug]);
        $inStock  = $item->stock === null || $item->stock > 0;
        $savePct  = $item->has_strike && $item->price > 0
            ? (int) round((($item->price - $item->effective_price) / $item->price) * 100)
            : 0;

        $tagStyles = [
            'Sale' => ['label' => 'Hot Sale', 'class' => 'bg-[#1CB28E] text-white'],
            'NEW'  => ['label' => 'New',      'class' => 'bg-[#7B2FD1] text-white'],
            'OFF'  => ['label' => '10% Off',  'class' => 'bg-[#C62828] text-white'],
            'OFF1' => ['label' => '15% Off',  'class' => 'bg-[#C62828] text-white'],
        ];
        $tag = $tagStyles[$item->tag] ?? null;
    @endphp

    <article class="group flex flex-col">

        {{-- Media --}}
        <div class="relative overflow-hidden rounded-xl bg-snow dark:bg-white/10">
            <a href="{{ $url }}" class="block aspect-[4/5] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 rounded-xl">
                @if ($img)
                    {{-- aspect-ratio box + explicit dimensions: the image can't shift layout as it loads --}}
                    <img src="{{ $img }}" alt="{{ $item->name }}" width="400" height="500" loading="lazy"
                         class="w-full h-full object-cover transform group-hover:scale-105 duration-500">
                @else
                    <span class="w-full h-full flex items-center justify-center text-sm text-paragraph dark:text-white-light">
                        No image
                    </span>
                @endif
            </a>

            {{-- Badges --}}
            <div class="absolute top-3 left-3 z-10 flex flex-col items-start gap-1.5 pointer-events-none">
                @if ($tag)
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold leading-none {{ $tag['class'] }}">
                        {{ $tag['label'] }}
                    </span>
                @endif
                @if ($savePct > 0)
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold leading-none bg-title text-white">
                        Save {{ $savePct }}%
                    </span>
                @endif
                @if (! $inStock)
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold leading-none bg-white text-title border border-gray-200">
                        Out of Stock
                    </span>
                @endif
            </div>

            {{-- Actions: always visible. Hover-only actions are unreachable on touch. --}}
            <div class="absolute top-3 right-3 z-10 flex flex-col gap-2">
                <button type="button"
                        class="wishlist-toggle-btn w-11 h-11 inline-flex items-center justify-center rounded-full bg-white dark:bg-title border border-gray-200 dark:border-bdr-clr-drk text-title dark:text-white hover:border-primary hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary transition-colors duration-200 cursor-pointer touch-manipulation"
                        data-product-id="{{ $item->id }}"
                        data-product-name="{{ $item->name }}">
                    <svg class="fill-current wishlist-icon-outline w-[18px] h-[18px]" viewBox="0 0 24 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969Z"/>
                    </svg>
                    {{-- The toggle script rewrites this text; sr-only keeps the state announced --}}
                    <span class="sr-only wishlist-btn-text">Add to wishlist</span>
                </button>

                {{-- Quick view. Not the template's .quick-view class: that one toggles a
                     static demo popup with hardcoded images, regardless of the product. --}}
                <button type="button"
                        class="pg-qv-btn-trigger w-11 h-11 inline-flex items-center justify-center rounded-full bg-white dark:bg-title border border-gray-200 dark:border-bdr-clr-drk text-title dark:text-white hover:border-primary hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary transition-colors duration-200 cursor-pointer touch-manipulation"
                        data-qv-url="{{ route('quick-view', ['slug' => $item->slug]) }}">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span class="sr-only">Quick view: {{ $item->name }}</span>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="flex flex-col flex-1 pt-4">
            @if ($item->category?->name)
                <p class="text-[11px] font-bold uppercase tracking-widest text-[#8A6A3F] dark:text-primary mb-1.5">
                    {{ $item->category->name }}
                </p>
            @endif

            <h3 class="text-sm font-semibold leading-snug text-title dark:text-white line-clamp-2">
                <a href="{{ $url }}" class="hover:text-primary transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                    {{ $item->name }}
                </a>
            </h3>

            <div class="mt-2">
                @include('includes.Home._stars', ['rating' => $item->avgRating(), 'count' => $item->reviewCount()])
            </div>

            {{-- Price. display_price / was_price come from the model, which is also what
                 the cart charges — deriving it here is how the two drifted apart before. --}}
            <div class="mt-2.5 flex items-baseline gap-2 flex-wrap">
                <span class="text-base font-bold text-title dark:text-white tabular-nums">{{ $item->display_price }}</span>
                @if ($item->was_price)
                    <span class="text-xs text-paragraph dark:text-white-light line-through tabular-nums">{{ $item->was_price }}</span>
                @endif
            </div>

            @php
                $colors = collect($item->colors ?? [])->filter()->values();
                $sizes  = collect($item->sizes ?? [])->filter()->values();
                $bits   = [];
                if ($colors->count()) $bits[] = $colors->count() . ' ' . \Str::plural('colour', $colors->count());
                if ($sizes->count())  $bits[] = $sizes->count() . ' ' . \Str::plural('size', $sizes->count());
            @endphp

            {{-- Variant summary + swatches. Rendered only where the data exists, rather
                 than printing "0 colours" on the products that have none. --}}
            @if ($bits)
                <p class="mt-2 text-xs text-paragraph dark:text-white-light">{{ implode(' · ', $bits) }}</p>
            @endif

            @if ($colors->count())
                <ul class="mt-2 flex items-center gap-1.5" aria-label="Available colours">
                    @foreach ($colors->take(5) as $color)
                        <li class="w-4 h-4 rounded-full border border-black/15"
                            style="background: {{ \App\Models\Product::colorHex($color) }}"
                            title="{{ $color }}">
                            {{-- Name in text: the swatch alone never carries the meaning --}}
                            <span class="sr-only">{{ $color }}</span>
                        </li>
                    @endforeach
                    @if ($colors->count() > 5)
                        <li class="text-xs text-paragraph dark:text-white-light">+{{ $colors->count() - 5 }}</li>
                    @endif
                </ul>
            @endif

            {{-- Add to cart: always visible and tappable, not hover-revealed --}}
            <div class="mt-4">
                @if ($inStock)
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit"
                                class="w-full min-h-[44px] inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 dark:border-bdr-clr-drk text-sm font-semibold text-title dark:text-white hover:bg-primary hover:border-primary hover:text-title focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-colors duration-200 cursor-pointer touch-manipulation">
                            <svg class="w-[18px] h-[18px] fill-current" viewBox="0 0 20 22" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826ZM9.99503 1.89166C12.0111 1.89166 13.6896 3.36302 14.014 5.28826H5.97605C6.30043 3.36302 7.97898 1.89166 9.99503 1.89166ZM17.4675 20.2333H2.52252V6.98655H4.22082V9.534C4.22082 10.003 4.60098 10.3832 5.06997 10.3832C5.53895 10.3832 5.91912 10.003 5.91912 9.534V6.98655H14.0709V9.534C14.0709 10.003 14.4511 10.3832 14.9201 10.3832C15.3891 10.3832 15.7692 10.003 15.7692 9.534V6.98655H17.4675V20.2333Z"/>
                            </svg>
                            Add to Cart
                        </button>
                    </form>
                @else
                    <button type="button" disabled aria-disabled="true"
                            class="w-full min-h-[44px] inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-bdr-clr-drk text-sm font-semibold text-paragraph dark:text-white-light opacity-50 cursor-not-allowed">
                        Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </article>
@endforeach
