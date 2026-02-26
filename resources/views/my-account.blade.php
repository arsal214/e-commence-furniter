<!-- resources/views/my-account.blade.php -->
@extends('layouts.main')

@section('title', 'My Account')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">My Account</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Account</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- My Account Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto flex items-start gap-8 md:gap-12 2xl:gap-24 flex-col md:flex-row my-profile-navtab">

            <!-- Sidebar Nav -->
            <div class="w-full md:w-[200px] lg:w-[300px] flex-none">
                <ul class="divide-y dark:divide-paragraph text-title dark:text-white text-base sm:text-lg lg:text-xl flex flex-col justify-center leading-none">
                    <li class="active text-primary pb-3 lg:pb-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/my-account') }}">My Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/edit-account') }}">Edit Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
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
            <div class="w-full md:w-auto md:flex-1 overflow-auto space-y-6">

                @if(session('success'))
                    <div class="bg-green-50 border border-green-300 text-green-700 px-5 py-3 rounded text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Profile Info -->
                <div class="w-full max-w-[951px] bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px]">
                    <div>
                        <h3 class="font-semibold leading-none text-3xl dark:text-white">{{ $user->name }}</h3>
                        <span class="leading-none mt-3 block text-gray-500 dark:text-gray-400">Customer</span>
                    </div>
                    <div class="mt-5 sm:mt-8 md:mt-10 grid gap-4 sm:gap-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-3 sm:w-[18px] flex-none" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.2 0.0615234H1.8C0.81 0.0615234 0.00899999 0.849023 0.00899999 1.81152L0 12.3115C0 13.274 0.81 14.0615 1.8 14.0615H16.2C17.19 14.0615 18 13.274 18 12.3115V1.81152C18 0.849023 17.19 0.0615234 16.2 0.0615234ZM16.2 3.56152L9 7.93652L1.8 3.56152V1.81152L9 6.18652L16.2 1.81152V3.56152Z" fill="#BB976D"/>
                            </svg>
                            <span class="leading-none font-medium text-base sm:text-lg text-title dark:text-white">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3 sm:w-[15px] flex-none" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.49927 0.0615234C3.36415 0.0615234 0 3.42567 0 7.56075C0 12.6925 6.71111 20.2262 6.99684 20.5444C7.26522 20.8434 7.7338 20.8428 8.00169 20.5444C8.28743 20.2262 14.9985 12.6925 14.9985 7.56075C14.9985 3.42567 11.6343 0.0615234 7.49927 0.0615234ZM7.49927 11.3338C5.41879 11.3338 3.72624 9.64123 3.72624 7.56075C3.72624 5.48027 5.41883 3.78772 7.49927 3.78772C9.57971 3.78772 11.2723 5.48031 11.2723 7.56079C11.2723 9.64127 9.57971 11.3338 7.49927 11.3338Z" fill="#BB976D"/>
                            </svg>
                            <span class="leading-none font-medium text-base sm:text-lg text-title dark:text-white">Member since {{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ url('/edit-account') }}" class="btn btn-solid" data-text="Edit Profile">
                            <span>Edit Profile</span>
                        </a>
                    </div>
                </div>

                <!-- Order History -->
                <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px] order-history-table">
                    <h4 class="font-semibold text-xl md:text-2xl leading-none mb-5 dark:text-white">Order History</h4>
                    @if($orders->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-base">You haven't placed any orders yet. <a href="{{ url('/shop-v1') }}" class="text-primary hover:underline">Start shopping</a>.</p>
                    @else
                    <ul class="order-history">
                        <!-- Table Heading -->
                        <li class="title flex items-center justify-between gap-5 pb-[10px] sm:pb-5 border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <span class="cart-product-title text-base md:text-lg font-semibold leading-none text-title dark:text-white block w-[200px] sm:w-[280px] xl:w-[320px]">Order</span>
                            <span class="text-base md:text-lg font-semibold leading-none text-title dark:text-white w-[80px]">Total</span>
                            <span class="text-base md:text-lg font-semibold leading-none text-title dark:text-white w-[110px]">Status</span>
                            <span class="text-base md:text-lg font-semibold leading-none text-title dark:text-white hidden sm:block w-[100px]">Date</span>
                        </li>

                        @foreach($orders as $order)
                        <li class="flex items-center justify-between gap-5 py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk last:border-b-0">
                            <div class="flex items-center gap-3 md:gap-4 w-[200px] sm:w-[280px] xl:w-[320px]">
                                <div class="flex-1">
                                    <span class="text-[13px] text-gray-500 dark:text-gray-400 leading-none">Order #{{ $order->id }}</span>
                                    <h5 class="font-semibold leading-none mt-2 text-base dark:text-white">
                                        {{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}
                                        @if($order->items->first())
                                            &mdash; {{ Str::limit($order->items->first()->name, 28) }}
                                        @endif
                                    </h5>
                                </div>
                            </div>

                            <span class="text-base leading-none text-title dark:text-white font-semibold w-[80px]">${{ number_format($order->total, 2) }}</span>

                            <div class="w-[110px]">
                                @php
                                    $statusColor = match($order->status) {
                                        'completed' => 'bg-[#31A051]',
                                        'processing' => 'bg-[#3B82F6]',
                                        'cancelled'  => 'bg-[#E13939]',
                                        default      => 'bg-[#EC991D]',
                                    };
                                @endphp
                                <span class="{{ $statusColor }} py-[6px] px-[10px] font-semibold leading-none text-white text-xs rounded capitalize">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:block w-[100px]">{{ $order->created_at->format('d M Y') }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
<!-- My Account End -->

@include('includes.footer6')

@endsection
