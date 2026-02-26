<!-- resources/views/checkout.blade.php -->
@extends('layouts.main')

@section('title', 'Checkout Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center text-2xl">Checkout</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4 flex-wrap">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Checkout</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Checkout Area Start -->
<div class="s-py-100">
    <div class="container">
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
    @csrf
    <div class="max-w-[1220px] mx-auto grid lg:grid-cols-2 gap-[30px] lg:gap-[70px]">
        <!-- Left: Billing Info -->
        <div class="bg-[#FAFAFA] dark:bg-dark-secondary p-[30px] md:p-[40px] lg:p-[50px] border border-[#17243026] border-opacity-15 rounded-xl" data-aos="fade-up">
            <h4 class="font-semibold leading-none text-xl md:text-2xl mb-6 md:mb-[30px]">Billing Information</h4>

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid gap-5 md:gap-6">
                <div>
                    <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Full Name <span class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name', auth()->user()?->name) }}"
                        class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300 @error('name') border-red-400 @enderror"
                        type="text" placeholder="Enter your full name">
                </div>
                <div>
                    <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Email <span class="text-red-500">*</span></label>
                    <input name="email" value="{{ old('email', auth()->user()?->email) }}"
                        class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300 @error('email') border-red-400 @enderror"
                        type="email" placeholder="Enter your email address">
                </div>
                <div>
                    <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Phone No. <span class="text-red-500">*</span></label>
                    <input name="phone" value="{{ old('phone') }}"
                        class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300 @error('phone') border-red-400 @enderror"
                        type="tel" placeholder="Type your phone number">
                </div>
                <div class="grid md:grid-cols-2 gap-5 md:gap-6">
                    <div>
                        <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Town / City</label>
                        <input name="city" value="{{ old('city') }}"
                            class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                            type="text" placeholder="Your city">
                    </div>
                    <div>
                        <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Zip Code</label>
                        <input name="zip" value="{{ old('zip') }}"
                            class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                            type="text" placeholder="1217">
                    </div>
                </div>
                <div>
                    <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Address Line 1 <span class="text-red-500">*</span></label>
                    <input name="address" value="{{ old('address') }}"
                        class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300 @error('address') border-red-400 @enderror"
                        type="text" placeholder="Your full address">
                </div>
                <div>
                    <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Address Line 2</label>
                    <input name="address2" value="{{ old('address2') }}"
                        class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300"
                        type="text" placeholder="Apartment, suite, etc. (optional)">
                </div>
                <div>
                    <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Order Notes</label>
                    <textarea name="notes" class="w-full h-[120px] bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" placeholder="Notes about your order">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right: Order Summary + Payment -->
        <div data-aos="fade-up" data-aos-delay="200">
            <div class="bg-[#FAFAFA] dark:bg-dark-secondary pt-[30px] md:pt-[40px] lg:pt-[50px] px-[30px] md:px-[40px] lg:px-[50px] pb-[30px] border border-[#17243026] border-opacity-15 rounded-xl">
                <h4 class="font-semibold leading-none text-xl md:text-2xl mb-6 md:mb-10">Order Summary</h4>
                <div class="grid gap-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center justify-between gap-5">
                        <div class="flex items-center gap-3 md:gap-4 cart-product flex-wrap">
                            <div class="w-16 flex-none">
                                @if($item['image'] && str_starts_with($item['image'], 'assets/'))
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                                @elseif($item['image'])
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
                                @else
                                    <img src="{{ asset('assets/img/gallery/cart/cart-01.jpg') }}" alt="{{ $item['name'] }}">
                                @endif
                            </div>
                            <div class="flex-1">
                                <h5 class="font-semibold leading-none text-lg dark:text-white">{{ $item['name'] }}</h5>
                                <p class="text-sm mt-1 text-gray-500">Qty: {{ $item['qty'] }}</p>
                            </div>
                        </div>
                        <h6 class="leading-none text-lg font-bold dark:text-white whitespace-nowrap">${{ number_format($item['price'] * $item['qty'], 2) }}</h6>
                    </div>
                    @endforeach
                </div>

                <!-- Shipping selection -->
                <div class="mt-6 pt-6 border-t border-bdr-clr dark:border-bdr-clr-drk">
                    @foreach($shippingOptions as $key => $option)
                    <div class="flex justify-between flex-wrap text-base sm:text-lg text-title dark:text-white font-medium mt-3">
                        <label class="flex items-center gap-[10px] categoryies-iteem cursor-pointer">
                            <input class="appearance-none hidden" type="radio" name="shipping" value="{{ $key }}" {{ old('shipping', 'free') === $key ? 'checked' : '' }}>
                            <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                </svg>
                            </span>
                            <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none">{{ $option['label'] }}:</span>
                        </label>
                        <span>${{ number_format($option['cost'], 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="mt-6 pt-6 border-t border-bdr-clr dark:border-bdr-clr-drk text-right flex flex-col gap-2">
                    <div class="flex justify-between text-base sm:text-lg text-title dark:text-white font-medium">
                        <span>Sub Total:</span>
                        <span>${{ number_format($cartTotal, 2) }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-bdr-clr dark:border-bdr-clr-drk">
                    <div class="flex justify-between font-semibold leading-none text-2xl md:text-3xl dark:text-white">
                        <span>Total:</span>
                        <span id="checkout-total">${{ number_format($cartTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="mt-7 md:mt-12">
                <h4 class="font-semibold leading-none text-xl md:text-2xl mb-6 md:mb-10 dark:text-white">Payment Method</h4>
                <div class="flex gap-5 sm:gap-8 md:gap-12 flex-wrap">
                    <div>
                        <label class="flex items-center gap-[10px] categoryies-iteem cursor-pointer">
                            <input class="appearance-none hidden" type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                            <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                </svg>
                            </span>
                            <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none">Cash On Delivery</span>
                        </label>
                        <p class="ml-6 text-[15px] leading-none mt-2 text-gray-500">Delivered in 7–10 days</p>
                    </div>
                    <div>
                        <label class="flex items-center gap-[10px] categoryies-iteem cursor-pointer">
                            <input class="appearance-none hidden" type="radio" name="payment_method" value="stripe" {{ old('payment_method') === 'stripe' ? 'checked' : '' }}>
                            <span class="w-4 h-4 rounded-full border border-title dark:border-white flex items-center justify-center duration-300">
                                <svg class="duration-300 opacity-0" width="8" height="8" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="10" height="10" rx="5" fill="#BB976D"/>
                                </svg>
                            </span>
                            <span class="sm:text-lg text-title dark:text-white block sm:leading-none transform translate-y-[3px] select-none">Debit / Credit Card (Stripe)</span>
                        </label>
                        <p class="ml-6 text-[15px] leading-none mt-2 text-gray-500">Pay securely via Stripe</p>
                    </div>
                </div>

                <div class="mt-6 sm:mt-8 md:mt-10">
                    <label class="flex items-center gap-2 iam-agree cursor-pointer">
                        <input class="appearance-none hidden" type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
                        <span class="w-6 h-6 rounded-[5px] border-2 border-title dark:border-white flex items-center justify-center duration-300">
                            <svg class="duration-300 opacity-0 text-title dark:text-white fill-current" width="15" height="12" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3819 0.742676L6.10461 11.8998L2.25731 8.06381L0.763672 9.55745L6.20645 15.0002L20 2.32686L18.3819 0.742676Z"/>
                            </svg>
                        </span>
                        <span class="text-base sm:text-lg text-title dark:text-white leading-none sm:leading-none select-none inline-block transform translate-y-[3px]">I Agree all terms &amp; Conditions</span>
                    </label>
                    @error('agree')
                    <p class="text-red-500 text-sm mt-1 ml-8">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4 md:mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('cart') }}" class="btn btn-outline" data-text="Back to Cart">
                        <span>Back to Cart</span>
                    </a>
                    <button type="submit" class="btn btn-theme-solid" data-text="Place Order">
                        <span>Place Order</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </form>
    </div>
</div>
<!-- Checkout Area End -->

<script>
// Update total when shipping option changes
document.querySelectorAll('input[name="shipping"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var costs = { free: 0, fast: 10, local: 15 };
        var subtotal = {{ $cartTotal }};
        var total = subtotal + (costs[this.value] || 0);
        document.getElementById('checkout-total').textContent = '$' + total.toFixed(2);
    });
});
</script>

@include('includes.footer6')

@endsection
