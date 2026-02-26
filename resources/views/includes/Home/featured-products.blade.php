@php
$leftProducts  = $featuredProducts->take(4);
$rightProducts = $featuredProducts->skip(4)->take(2);
@endphp

{{-- Left 2×2 grid --}}
<div class="grid sm:grid-cols-2 gap-5 sm:gap-8 lg:max-w-[766px] w-full">
    @forelse ($leftProducts as $item)
        <div class="group">
            <div class="relative overflow-hidden">
                <a href="{{ route('product-details', $item->slug) }}">
                    @if ($item->image)
                        @if (str_starts_with($item->image, 'assets/'))
                            <img class="w-full transform group-hover:scale-110 duration-300 sm:max-h-[320px] object-cover" src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                        @else
                            <img class="w-full transform group-hover:scale-110 duration-300 sm:max-h-[320px] object-cover" src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                        @endif
                    @else
                        <div class="w-full sm:max-h-[320px] h-52 bg-gray-100 flex items-center justify-center">
                            <i class="mdi mdi-image-off text-gray-300 text-5xl"></i>
                        </div>
                    @endif
                </a>

                @if ($item->tag)
                    @php
                        $tagClasses = match($item->tag) {
                            'Sale' => 'bg-[#1CB28E]',
                            'NEW'  => 'bg-[#9739E1]',
                            default => 'bg-[#E13939]',
                        };
                        $tagLabel = match($item->tag) {
                            'Sale' => 'Hot Sale',
                            'NEW'  => 'NEW',
                            default => '15% OFF',
                        };
                    @endphp
                    <div class="absolute z-10 top-4 left-4 pt-[10px] pb-2 px-3 {{ $tagClasses }} rounded-[30px] font-primary text-[14px] text-white font-semibold leading-none">
                        {{ $tagLabel }}
                    </div>
                @endif

                <div class="absolute z-10 top-[80%] right-3 transform -translate-y-[40%] opacity-0 duration-300 transition-all group-hover:-translate-y-1/2 group-hover:opacity-100 flex flex-col items-end gap-3">
                    <button type="button"
                        class="wishlist-toggle-btn bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon"
                        data-product-id="{{ $item->id }}">
                        <svg class="fill-current wishlist-icon-outline" width="20" height="22" viewBox="0 0 24 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969Z"/></svg>
                        <span class="mt-1 wishlist-btn-text">Add to wishlist</span>
                    </button>
                    <form action="{{ route('cart.add') }}" method="POST" class="contents">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon">
                            <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826ZM9.99503 1.89166C12.0111 1.89166 13.6896 3.36302 14.014 5.28826H5.97605C6.30043 3.36302 7.97898 1.89166 9.99503 1.89166ZM17.4675 20.2333H2.52252V6.98655H4.22082V9.534C4.22082 10.003 4.60098 10.3832 5.06997 10.3832C5.53895 10.3832 5.91912 10.003 5.91912 9.534V6.98655H14.0709V9.534C14.0709 10.003 14.4511 10.3832 14.9201 10.3832C15.3891 10.3832 15.7692 10.003 15.7692 9.534V6.98655H17.4675V20.2333Z"/></svg>
                            <span class="mt-1">Add to Cart</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="lg:pt-6 pt-5 flex gap-3 md:gap-4 flex-col">
                <h4 class="font-medium leading-none dark:text-white text-lg">
                    {{ $item->display_price }}
                    @if ($item->sale_price)
                        <span class="text-title/50 line-through pl-2 inline-block">${{ number_format($item->price, 2) }}</span>
                    @endif
                </h4>
                <div>
                    <h5 class="font-normal dark:text-white text-xl leading-[1.5]">
                        <a href="{{ route('product-details', $item->slug) }}" class="text-underline">{{ $item->name }}</a>
                    </h5>
                    @include('includes.Home._stars')
                </div>
            </div>
        </div>
    @empty
        <p class="text-gray-400 col-span-2 py-8 text-center">No featured products yet.</p>
    @endforelse
</div>

{{-- Right tall column --}}
<div class="grid sm:grid-cols-2 gap-5 sm:gap-8 lg:max-w-[925px] w-full">
    @foreach ($rightProducts as $item)
        <div class="group flex flex-col">
            <div class="relative overflow-hidden flex-1">
                <a href="{{ route('product-details', $item->slug) }}">
                    @if ($item->image)
                        @if (str_starts_with($item->image, 'assets/'))
                            <img class="w-full transform group-hover:scale-110 duration-300 h-full object-cover" src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                        @else
                            <img class="w-full transform group-hover:scale-110 duration-300 h-full object-cover" src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                        @endif
                    @else
                        <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                            <i class="mdi mdi-image-off text-gray-300 text-5xl"></i>
                        </div>
                    @endif
                </a>

                @if ($item->tag)
                    @php
                        $tagClasses = match($item->tag) { 'Sale' => 'bg-[#1CB28E]', 'NEW' => 'bg-[#9739E1]', default => 'bg-[#E13939]' };
                        $tagLabel   = match($item->tag) { 'Sale' => 'Hot Sale', 'NEW' => 'NEW', default => '15% OFF' };
                    @endphp
                    <div class="absolute z-10 top-4 left-4 pt-[10px] pb-2 px-3 {{ $tagClasses }} rounded-[30px] font-primary text-[14px] text-white font-semibold leading-none">
                        {{ $tagLabel }}
                    </div>
                @endif

                <div class="absolute z-10 top-[62%] right-3 transform -translate-y-[40%] opacity-0 duration-300 transition-all group-hover:-translate-y-1/2 group-hover:opacity-100 flex flex-col items-end gap-3">
                    <button type="button"
                        class="wishlist-toggle-btn bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon"
                        data-product-id="{{ $item->id }}">
                        <svg class="fill-current wishlist-icon-outline" width="20" height="22" viewBox="0 0 24 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969Z"/></svg>
                        <span class="mt-1 wishlist-btn-text">Add to wishlist</span>
                    </button>
                    <form action="{{ route('cart.add') }}" method="POST" class="contents">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon">
                            <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826ZM9.99503 1.89166C12.0111 1.89166 13.6896 3.36302 14.014 5.28826H5.97605C6.30043 3.36302 7.97898 1.89166 9.99503 1.89166ZM17.4675 20.2333H2.52252V6.98655H4.22082V9.534C4.22082 10.003 4.60098 10.3832 5.06997 10.3832C5.53895 10.3832 5.91912 10.003 5.91912 9.534V6.98655H14.0709V9.534C14.0709 10.003 14.4511 10.3832 14.9201 10.3832C15.3891 10.3832 15.7692 10.003 15.7692 9.534V6.98655H17.4675V20.2333Z"/></svg>
                            <span class="mt-1">Add to Cart</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="lg:pt-6 pt-5 flex gap-3 md:gap-4 flex-col">
                <h4 class="font-medium leading-none dark:text-white text-lg">
                    {{ $item->display_price }}
                    @if ($item->sale_price)
                        <span class="text-title/50 line-through pl-2 inline-block">${{ number_format($item->price, 2) }}</span>
                    @endif
                </h4>
                <div>
                    <h5 class="font-normal dark:text-white text-xl leading-[1.5]">
                        <a href="{{ route('product-details', $item->slug) }}" class="text-underline">{{ $item->name }}</a>
                    </h5>
                    @include('includes.Home._stars')
                </div>
            </div>
        </div>
    @endforeach
</div>
