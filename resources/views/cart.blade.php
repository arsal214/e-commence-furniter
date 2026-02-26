<!-- resources/views/cart.blade.php -->
@extends('layouts.main')

@section('title', 'Cart Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center text-2xl">Cart</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Cart</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Cart Area Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container ">
        <div class="flex xl:flex-row flex-col gap-[30px] lg:gap-[30px] xl:gap-[70px]">
            <div class="flex-1 overflow-hidden">
                <table id="cart-table" class="responsive nowrap table-wrapper" style="width:100%">
                    <thead class="table-header">
                        <tr>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Product Info</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Price</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Quantity</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Total</th>
                            <th class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white">Remove</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse ($cartItems as $item)
                        <tr>
                            <td class="md:w-[42%]">
                                <div class="flex items-center gap-3 md:gap-4 lg:gap-6 cart-product">
                                    <div class="w-14 sm:w-20 flex-none">
                                        @if($item['image'] && str_starts_with($item['image'], 'assets/'))
                                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                                        @elseif($item['image'])
                                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
                                        @else
                                            <img src="{{ asset('assets/img/gallery/cart/cart-01.jpg') }}" alt="{{ $item['name'] }}">
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="font-semibold leading-none text-xl dark:text-white">
                                            <a href="{{ route('product-details', $item['slug']) }}">{{ $item['name'] }}</a>
                                        </h5>
                                        @if(!empty($item['color']) || !empty($item['size']))
                                        <div class="flex flex-wrap gap-x-3 gap-y-1 mt-2">
                                            @if(!empty($item['color']))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                <span class="font-medium text-title dark:text-white">Color:</span> {{ $item['color'] }}
                                            </span>
                                            @endif
                                            @if(!empty($item['size']))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                <span class="font-medium text-title dark:text-white">Size:</span> {{ $item['size'] }}
                                            </span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h6 class="text-base md:text-lg leading-none text-title dark:text-white font-semibold">${{ number_format($item['price'], 2) }}</h6>
                            </td>
                            <td>
                                <form action="{{ route('cart.update') }}" method="POST" class="inc-dec flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $item['key'] }}">
                                    <button type="button" class="dec w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                        <svg class="fill-current text-title dark:text-white" width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.4361 0.203613H12.0736L7.81774 0.203615H13.8729V1.80309H7.81774L3.50809 1.80309H1.87053L6.18017 1.80309H0.125V0.203615H6.18017L10.4361 0.203613Z"/>
                                        </svg>
                                    </button>
                                    <input class="w-6 h-auto outline-none bg-transparent text-base leading-none text-title dark:text-white text-center cart-qty-input" name="qty" type="number" value="{{ $item['qty'] }}" min="1">
                                    <button type="button" class="inc w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                                        <svg class="fill-current text-title dark:text-white" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.18017 0.110352H7.81774V6.16553H13.8729V7.76501H7.81774V13.8963H6.18017V7.76501H0.125V6.16553H6.18017V0.110352Z"/>
                                        </svg>
                                    </button>
                                    <button type="submit" class="ml-1 text-xs text-primary underline">Update</button>
                                </form>
                            </td>
                            <td>
                                <h6 class="text-base md:text-lg leading-none text-title dark:text-white font-semibold">${{ number_format($item['price'] * $item['qty'], 2) }}</h6>
                            </td>
                            <td>
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $item['key'] }}">
                                    <button type="submit" class="w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center ml-auto duration-300 text-title dark:text-white hover:bg-red-100">
                                        <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <p class="text-lg text-title dark:text-white">Your cart is empty.</p>
                                <a href="{{ url('/shop-v1') }}" class="btn btn-sm btn-theme-solid mt-4 inline-block !text-white">Continue Shopping</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                <div class="mb-[30px]">
                    <h4 class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white mb-[15px]">
                        Promo Code
                    </h4>
                    <div class="flex xs:flex-row gap-3">
                        <input class="h-12 md:h-14 bg-snow dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300 placeholder:text-title dark:placeholder:text-white flex-1" type="text" placeholder="Coupon Code">
                        <button class="btn btn-solid" data-text="Apply">
                            <span>Apply</span>
                        </button>
                    </div>
                </div>
                <div class="bg-[#FAFAFA] dark:bg-dark-secondary pt-[30px] md:pt-[40px] px-[30px] md:px-[40px] pb-[30px] border border-[#17243026] border-opacity-15 rounded-xl">   
                    <div class="text-right flex justify-end flex-col w-full ml-auto mr-0">
                        <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium">
                            <span>Sub Total:</span>
                            <span>${{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-bdr-clr dark:border-bdr-clr-drk">
                        <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium mt-3">
                            <div>
                                <label class="flex items-center gap-[10px] categoryies-iteem">
                                    <input class="appearance-none hidden" type="radio" name="item-type">
                                    <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                        <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                        </svg>
                                    </span>
                                    <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none">Free Shipping:</span>
                                </label>
                            </div>
                            <span> $0</span>
                        </div>
                        <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium mt-3">
                            <div>
                                <label class="flex items-center gap-[10px] categoryies-iteem">
                                    <input class="appearance-none hidden" type="radio" name="item-type">
                                    <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                        <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                        </svg>
                                    </span>
                                    <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none"> Fast Shipping:</span>
                                </label>
                            </div>
                            <span>$10</span>
                        </div>
                        <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium mt-3">
                            <div>
                                <label class="flex items-center gap-[10px] categoryies-iteem">
                                    <input class="appearance-none hidden" type="radio" name="item-type">
                                    <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                        <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                        </svg>
                                    </span>
                                    <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none"> Local Pickup:</span>
                                </label>
                            </div>
                            <span>$15</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-bdr-clr dark:border-bdr-clr-drk">
                        <div class="flex justify-between flex-wrap font-semibold leading-none text-2xl">
                            <span>Total:</span>
                            <span>&nbsp;${{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="sm:mt-[10px] py-5 flex items-end gap-3 flex-wrap justify-end">
                    <a href="{{ url('/shop-v1') }}" class="btn btn-sm btn-outline" data-text="Continue Shopping">
                        Continue Shopping
                    </a>
                    @auth
                        <a href="{{ url('/checkout') }}" class="btn btn-sm btn-theme-solid !text-white hover:!text-[#bb976d] before:!z-[-1]">
                            Checkout
                        </a>
                    @else
                        <button onclick="document.getElementById('login-required-modal').classList.remove('hidden')"
                            class="btn btn-sm btn-theme-solid !text-white hover:!text-[#bb976d] before:!z-[-1]">
                            Checkout
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart Area End -->

<!-- Login Required Modal -->
<div id="login-required-modal" class="hidden fixed inset-0 z-[999] flex items-center justify-center px-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-title bg-opacity-70 backdrop-blur-sm" onclick="document.getElementById('login-required-modal').classList.add('hidden')"></div>
    <!-- Modal Box -->
    <div class="relative bg-white dark:bg-title w-full max-w-md p-8 sm:p-10 z-10">
        <button onclick="document.getElementById('login-required-modal').classList.add('hidden')"
            class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-primary hover:text-white text-title dark:text-white duration-200">
            <svg class="fill-current w-3 h-3" viewBox="0 0 12 12"><path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/></svg>
        </button>
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 fill-current text-primary" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
            </div>
            <h3 class="text-2xl font-semibold text-title dark:text-white leading-none">Sign in to Checkout</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-3 text-base">Please login or create an account to complete your purchase.</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ url('/login') }}?redirect={{ url('/checkout') }}"
                class="btn btn-solid text-center" data-text="Login">
                <span>Login</span>
            </a>
            <a href="{{ url('/register') }}"
                class="btn btn-outline text-center" data-text="Register">
                <span>Register</span>
            </a>
        </div>
        <p class="text-center text-sm text-gray-400 dark:text-gray-500 mt-5">
            Already have an account? <a href="{{ url('/login') }}" class="text-primary hover:underline">Sign in here</a>
        </p>
    </div>
</div>

@include('includes.footer6')

@push('scripts')
<script>
document.querySelectorAll('.inc-dec').forEach(function(form) {
    var input = form.querySelector('.cart-qty-input');
    form.querySelector('.inc').addEventListener('click', function() {
        input.value = parseInt(input.value) + 1;
        form.submit();
    });
    form.querySelector('.dec').addEventListener('click', function() {
        var val = parseInt(input.value);
        if (val > 1) {
            input.value = val - 1;
            form.submit();
        }
    });
});
</script>
@endpush

@endsection