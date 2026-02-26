@forelse ($newProducts as $product)
    <div class="group">
        <div class="relative overflow-hidden">
            <a href="{{ route('product-details', $product->slug) }}">
                @if ($product->image)
                    @if (str_starts_with($product->image, 'assets/'))
                        <img class="w-full transform group-hover:scale-110 duration-300" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <img class="w-full transform group-hover:scale-110 duration-300" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                    @endif
                @else
                    <div class="w-full h-52 bg-gray-100 flex items-center justify-center">
                        <i class="mdi mdi-image-off text-gray-300 text-5xl"></i>
                    </div>
                @endif
            </a>

            @if ($product->tag)
                @php
                    $tagClasses = match($product->tag) { 'Sale' => 'bg-[#1CB28E]', 'NEW' => 'bg-[#9739E1]', default => 'bg-[#E13939]' };
                    $tagLabel   = match($product->tag) { 'Sale' => 'Hot Sale', 'NEW' => 'NEW', default => '15% OFF' };
                @endphp
                <div class="absolute z-10 top-7 left-7 pt-[10px] pb-2 px-3 {{ $tagClasses }} rounded-[30px] font-primary text-[14px] text-white font-semibold leading-none">
                    {{ $tagLabel }}
                </div>
            @endif

            <div class="absolute z-10 top-[76%] right-3 transform -translate-y-[40%] opacity-0 duration-300 transition-all group-hover:-translate-y-1/2 group-hover:opacity-100 flex flex-col items-end gap-3">
                <a href="#" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon">
                    <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969Z"/></svg>
                    <span class="mt-1">Add to wishlist</span>
                </a>
                <form action="{{ route('cart.add') }}" method="POST" class="contents">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon">
                        <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826ZM9.99503 1.89166C12.0111 1.89166 13.6896 3.36302 14.014 5.28826H5.97605C6.30043 3.36302 7.97898 1.89166 9.99503 1.89166ZM17.4675 20.2333H2.52252V6.98655H4.22082V9.534C4.22082 10.003 4.60098 10.3832 5.06997 10.3832C5.53895 10.3832 5.91912 10.003 5.91912 9.534V6.98655H14.0709V9.534C14.0709 10.003 14.4511 10.3832 14.9201 10.3832C15.3891 10.3832 15.7692 10.003 15.7692 9.534V6.98655H17.4675V20.2333Z"/></svg>
                        <span class="mt-1">Add to Cart</span>
                    </button>
                </form>
                <button class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon quick-view">
                    <svg class="dark:text-white fill-current" width="20" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M22.3478 8.44208C20.2569 12.1678 16.2916 14.4822 12.0014 14.4822C7.70844 14.4822 3.74319 12.1678 1.65223 8.44208C1.49119 8.15278 1.49119 7.84697 1.65223 7.55792C3.74319 3.83229 7.70844 1.51813 12.0014 1.51813C16.2916 1.51813 20.2568 3.83229 22.3478 7.55792C22.5116 7.84697 22.5116 8.15278 22.3478 8.44208ZM23.6834 6.81924C21.3231 2.61279 16.8469 0 12.0014 0C7.15306 0 2.67686 2.61279 0.316559 6.81924C-0.10552 7.56977 -0.10552 8.43023 0.316559 9.1802C2.67686 13.3867 7.15306 16 12.0014 16C16.8469 16 21.3231 13.3867 23.6834 9.1802C24.1055 8.43028 24.1055 7.56977 23.6834 6.81924ZM12.0014 11.1141C13.7314 11.1141 15.1392 9.71721 15.1392 7.99987C15.1392 6.28253 13.7314 4.88562 12.0014 4.88562C10.2686 4.88562 8.86081 6.28253 8.86081 7.99987C8.86081 9.71721 10.2687 11.1141 12.0014 11.1141ZM12.0014 3.36749C9.42449 3.36749 7.3308 5.44578 7.3308 7.99993C7.3308 10.5546 9.42454 12.6321 12.0014 12.6321C14.5755 12.6321 16.6692 10.5546 16.6692 7.99993C16.6692 5.44578 14.5755 3.36749 12.0014 3.36749Z"/></svg>
                    <span class="mt-1">Quick View</span>
                </button>
            </div>
        </div>
        <div class="md:px-2 lg:px-4 xl:px-6 lg:pt-6 pt-5 flex gap-4 md:gap-5 flex-col">
            <h4 class="font-medium leading-none dark:text-white text-lg">
                {{ $product->display_price }}
                @if ($product->sale_price)
                    <span class="text-title/50 line-through pl-2 inline-block">${{ number_format($product->price, 2) }}</span>
                @endif
            </h4>
            <div>
                <h5 class="font-normal dark:text-white text-xl leading-[1.5]">
                    <a href="{{ route('product-details', $product->slug) }}" class="text-underline">{{ $product->name }}</a>
                </h5>
                @include('includes.Home._stars')
            </div>
        </div>
    </div>
@empty
    <div class="col-span-4 py-8 text-center text-gray-400">No products found.</div>
@endforelse
