<!-- resources/views/shop-v1.blade.php -->
@extends('layouts.main')

@section('title', 'Shop-V1 Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Shop</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Shop</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Shop Start -->
<div class="s-py-100">
    <div class="container-fluid">
        <!-- Shop Header -->
        <div class="flex items-start justify-between gap-8 max-w-[1720px] mx-auto flex-col lg:flex-row border-b border-bdr-clr dark:border-bdr-clr-drk pb-8 md:pb-[50px]" >
            <div>
                <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Choose Category</h4>
                <div class="flex flex-wrap gap-[10px] md:gap-[15px]">
                    <a class="btn btn-sm shop1-button {{ !$activeCategory ? 'btn-theme-solid' : 'btn-theme-outline' }}" href="{{ url('/shop-v1') }}" data-text="All"><span>All</span></a>
                    @foreach ($categories as $cat)
                    <a class="btn btn-sm shop1-button {{ $activeCategory === $cat->slug ? 'btn-theme-solid' : 'btn-theme-outline' }}" href="{{ url('/shop-v1') }}?category={{ $cat->slug }}" data-text="{{ $cat->name }}"><span>{{ $cat->name }}</span></a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-8 md:pt-[50px]" data-aos="fade-up" data-aos-delay="200">

            <!-- includes/Shop/shops-v1.blade.php -->
            @include('includes.Shop.shops-v1')

        </div>

        @if ($products->hasPages())
        <div class="flex items-center justify-center gap-2 mt-8 md:mt-12 flex-wrap">
            {{-- Previous --}}
            @if ($products->onFirstPage())
                <span class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk text-gray-300 cursor-not-allowed">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.18 7.39L5.62 12.83C5.82 13.06 6.16 13.09 6.39 12.89C6.62 12.70 6.65 12.35 6.45 12.12L1.88 7.55H23.43C23.73 7.55 23.98 7.30 23.98 7.00C23.98 6.70 23.73 6.46 23.43 6.46H1.88L6.39 1.94C6.62 1.75 6.65 1.40 6.45 1.18C6.26 0.95 5.91 0.92 5.68 1.12L0.18 6.62C-0.03 6.83 -0.03 7.17 0.18 7.39Z" fill="currentColor"/></svg>
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk hover:border-primary hover:text-primary duration-200">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.18 7.39L5.62 12.83C5.82 13.06 6.16 13.09 6.39 12.89C6.62 12.70 6.45 12.12L1.88 7.55H23.43C23.73 7.55 23.98 7.30 23.98 7.00C23.98 6.70 23.73 6.46 23.43 6.46H1.88L6.39 1.94C6.62 1.75 6.45 1.18C6.26 0.95 5.91 0.92 5.68 1.12L0.18 6.62C-0.03 6.83 -0.03 7.17 0.18 7.39Z" fill="currentColor"/></svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                @if ($page == $products->currentPage())
                    <span class="w-10 h-10 flex items-center justify-center bg-primary text-white font-semibold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk hover:border-primary hover:text-primary duration-200">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk hover:border-primary hover:text-primary duration-200">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.82 6.62L18.38 1.18C18.18 0.95 17.84 0.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46H0.57C0.27 6.46 0.02 6.71 0.02 7.01C0.02 7.31 0.27 7.55 0.57 7.55H22.12L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z" fill="currentColor"/></svg>
                </a>
            @else
                <span class="w-10 h-10 flex items-center justify-center border border-[#E3E5E6] dark:border-bdr-clr-drk text-gray-300 cursor-not-allowed">
                    <svg width="8" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.82 6.62L18.38 1.18C18.18 0.95 17.84 0.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46H0.57C0.27 6.46 0.02 6.71 0.02 7.01C0.02 7.31 0.27 7.55 0.57 7.55H22.12L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z" fill="currentColor"/></svg>
                </span>
            @endif
        </div>
        <p class="text-center text-sm text-gray-400 dark:text-white-light mt-3">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
        </p>
        @endif

    </div>
</div>
<!-- Shop End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer')

@endsection
