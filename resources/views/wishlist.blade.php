<!-- resources/views/wishlist.blade.php -->
@extends('layouts.main')

@section('title', 'My Wishlist')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Wishlist</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Wishlist</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Wishlist Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto flex items-start gap-8 md:gap-12 2xl:gap-24 flex-col md:flex-row my-profile-navtab">

            <!-- Sidebar Nav -->
            <div class="w-full md:w-[200px] lg:w-[300px] flex-none">
                <ul class="divide-y dark:divide-paragraph text-title dark:text-white text-base sm:text-lg lg:text-xl flex flex-col justify-center leading-none">
                    <li class="pb-3 lg:pb-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/my-account') }}">My Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/edit-account') }}">Edit Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/order-history') }}">Order History</a>
                    </li>
                    <li class="active text-primary py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/wishlist') }}">Wishlist</a>
                    </li>
                    <li class="pt-3 lg:pt-6 pl-6 lg:pl-12">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="duration-300 hover:text-primary">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Wishlist Content -->
            <div class="w-full md:w-auto md:flex-1">

                @if(session('success'))
                    <div class="bg-green-50 border border-green-300 text-green-700 px-5 py-3 rounded text-sm mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($wishlistItems->isEmpty())
                    <div class="text-center py-16 bg-[#F8F8F9] dark:bg-dark-secondary">
                        <svg class="w-16 h-16 fill-current text-gray-300 dark:text-gray-600 mx-auto mb-4" viewBox="0 0 25 21">
                            <path d="M17.9005 0.591797C15.9541 0.591797 14.2479 1.45969 12.9662 3.10171C12.7953 3.3207 12.6429 3.53979 12.5079 3.75198C12.3728 3.53974 12.2205 3.3207 12.0496 3.10171C10.7679 1.45969 9.06162 0.591797 7.11524 0.591797C3.43837 0.591797 0.808594 3.67049 0.808594 7.36477C0.808594 11.589 4.27071 15.5701 12.0343 20.2733C12.1798 20.3614 12.3439 20.4055 12.5079 20.4055C12.6719 20.4055 12.8359 20.3615 12.9815 20.2733C20.7451 15.5702 24.2072 11.589 24.2072 7.36482C24.2072 3.67246 21.5795 0.591797 17.9005 0.591797Z"/>
                        </svg>
                        <h4 class="text-xl font-semibold text-title dark:text-white mb-2">Your wishlist is empty</h4>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Save items you love and find them here anytime.</p>
                        <a href="{{ url('/shop-v1') }}" class="btn btn-solid" data-text="Browse Products">
                            <span>Browse Products</span>
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                        @foreach($wishlistItems as $wItem)
                            @if($wItem->product)
                            @php $product = $wItem->product; @endphp
                            <div class="group bg-white dark:bg-dark-secondary" data-aos="fade-up">
                                <!-- Image -->
                                <div class="relative overflow-hidden">
                                    <a href="{{ route('product-details', $product->slug) }}">
                                        @if($product->image)
                                            @if(str_starts_with($product->image, 'assets/'))
                                                <img class="w-full h-56 object-cover transform duration-300 group-hover:scale-110" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                            @else
                                                <img class="w-full h-56 object-cover transform duration-300 group-hover:scale-110" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                            @endif
                                        @else
                                            <div class="w-full h-56 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="w-12 h-12 fill-current text-gray-300" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                            </div>
                                        @endif
                                    </a>

                                    @if($product->tag)
                                        @php
                                            $tagClass = match($product->tag) { 'Sale' => 'bg-[#1CB28E]', 'NEW' => 'bg-[#9739E1]', default => 'bg-[#E13939]' };
                                            $tagText  = match($product->tag) { 'Sale' => 'Hot Sale', 'NEW' => 'NEW', default => '15% OFF' };
                                        @endphp
                                        <div class="absolute top-3 left-3 pt-[8px] pb-[6px] px-3 {{ $tagClass }} rounded-[30px] text-[13px] text-white font-semibold leading-none z-10">{{ $tagText }}</div>
                                    @endif

                                    <!-- Remove button overlay -->
                                    <div class="absolute top-3 right-3 z-10">
                                        <form action="{{ route('wishlist.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit"
                                                title="Remove from wishlist"
                                                class="w-9 h-9 bg-white dark:bg-title shadow flex items-center justify-center text-[#E13939] hover:bg-[#E13939] hover:text-white duration-200 group/rm">
                                                <svg class="fill-current w-4 h-4" viewBox="0 0 25 21">
                                                    <path d="M17.9005 0.591797C15.9541 0.591797 14.2479 1.45969 12.9662 3.10171C12.7953 3.3207 12.6429 3.53979 12.5079 3.75198C12.3728 3.53974 12.2205 3.3207 12.0496 3.10171C10.7679 1.45969 9.06162 0.591797 7.11524 0.591797C3.43837 0.591797 0.808594 3.67049 0.808594 7.36477C0.808594 11.589 4.27071 15.5701 12.0343 20.2733C12.1798 20.3614 12.3439 20.4055 12.5079 20.4055C12.6719 20.4055 12.8359 20.3615 12.9815 20.2733C20.7451 15.5702 24.2072 11.589 24.2072 7.36482C24.2072 3.67246 21.5795 0.591797 17.9005 0.591797Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Info + actions -->
                                <div class="p-4 sm:p-5">
                                    <h4 class="font-medium leading-none dark:text-white text-lg mb-1">
                                        {{ $product->display_price }}
                                        @if($product->sale_price)
                                            <span class="text-title/50 line-through pl-2 text-sm inline-block">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </h4>
                                    <h5 class="font-normal dark:text-white text-lg leading-snug mb-4">
                                        <a href="{{ route('product-details', $product->slug) }}" class="text-underline hover:text-primary duration-200">{{ $product->name }}</a>
                                    </h5>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="btn btn-solid btn-sm w-full" data-text="Add to Cart">
                                            <span>Add to Cart</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
<!-- Wishlist End -->

@include('includes.footer6')

@endsection
