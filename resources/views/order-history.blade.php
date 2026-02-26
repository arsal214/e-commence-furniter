<!-- resources/views/order-history.blade.php -->
@extends('layouts.main')

@section('title', 'Order History')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Order History</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Orders</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Order History Start -->
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
                    <li class="active text-primary py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/order-history') }}">Order History</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
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

            <!-- Main Content -->
            <div class="w-full md:w-auto md:flex-1 overflow-auto">
                <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px] order-history-table">
                    <h4 class="font-semibold text-xl md:text-2xl leading-none mb-6 dark:text-white">Your Orders</h4>

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 fill-current text-gray-300 dark:text-gray-600 mx-auto mb-4" viewBox="0 0 24 24"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2zm0 15l-5-2.18L7 18V5h10v13z"/></svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No orders yet.</p>
                            <a href="{{ url('/shop-v1') }}" class="btn btn-solid mt-6 inline-block" data-text="Start Shopping">
                                <span>Start Shopping</span>
                            </a>
                        </div>
                    @else
                        @foreach($orders as $order)
                        <div class="mb-6 border border-bdr-clr dark:border-bdr-clr-drk">
                            <!-- Order Header -->
                            <div class="flex flex-wrap items-center justify-between gap-4 p-4 sm:p-5 bg-white dark:bg-title border-b border-bdr-clr dark:border-bdr-clr-drk">
                                <div class="flex flex-wrap gap-6">
                                    <div>
                                        <span class="text-xs text-gray-400 uppercase tracking-wide block mb-1">Order</span>
                                        <span class="font-semibold text-title dark:text-white">#{{ $order->id }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-400 uppercase tracking-wide block mb-1">Date</span>
                                        <span class="font-semibold text-title dark:text-white">{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-400 uppercase tracking-wide block mb-1">Total</span>
                                        <span class="font-semibold text-title dark:text-white">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-400 uppercase tracking-wide block mb-1">Payment</span>
                                        <span class="font-semibold text-title dark:text-white capitalize">{{ $order->payment_method }}</span>
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $statusColor = match($order->status) {
                                            'completed'  => 'bg-[#31A051]',
                                            'processing' => 'bg-[#3B82F6]',
                                            'cancelled'  => 'bg-[#E13939]',
                                            default      => 'bg-[#EC991D]',
                                        };
                                    @endphp
                                    <span class="{{ $statusColor }} py-[6px] px-4 font-semibold leading-none text-white text-sm rounded capitalize">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="divide-y divide-bdr-clr dark:divide-bdr-clr-drk">
                                @foreach($order->items as $item)
                                <div class="flex items-center justify-between gap-4 p-4 sm:p-5">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1">
                                            <p class="font-semibold text-title dark:text-white text-base leading-none">{{ $item->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Qty: {{ $item->qty }}</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-title dark:text-white">${{ number_format($item->total, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Order History End -->

@include('includes.footer6')

@endsection
