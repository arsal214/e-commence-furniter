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
            <div class="max-w-[562px] w-full grid sm:grid-cols-2 gap-8 md:gap-12">
                <div>
                    <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Price Range</h4>
                    <div class="grid grid-cols-2 gap-[15px]">
                        <div class="py-[10px] px-5 border border-title dark:border-white-light flex items-center justify-center gap-[5px]">
                            <span class="text-title dark:text-white font-medium leading-none">Min:</span>
                            <div class="relative">
                                <span class="text-title dark:text-white font-medium leading-none absolute left-0 top-[82%] block transform -translate-y-1/2">$</span>
                                <input class="pl-[10px] w-full appearance-none bg-transparent text-title dark:text-white font-medium leading-none placeholder:text-title dark:placeholder:text-white placeholder  placeholder:font-medium placeholder:leading-none outline-none " type="number" placeholder="0" value="0">
                            </div>
                        </div>
                        <div class="py-[10] px-5 border border-title dark:border-white-light flex items-center justify-center gap-[5px]">
                            <span class="text-title dark:text-white font-medium leading-none">Max:</span>
                            <div class="relative">
                                <span class="text-title dark:text-white  font-medium leading-none absolute left-0 top-[82%] block transform -translate-y-1/2">$</span>
                                <input class="pl-[10px] w-full appearance-none bg-transparent text-title dark:text-white font-medium leading-none placeholder:text-title dark:placeholder:text-white  placeholder:font-medium placeholder:leading-none outline-none " type="number" placeholder="100" value="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-8 md:pt-[50px]" data-aos="fade-up" data-aos-delay="200">
            
            <!-- includes/Shop/shops-v1.blade.php -->
            @include('includes.Shop.shops-v1')

        </div>
        <div class="text-center mt-7 md:mt-12">
            <a href="{{ url('/shop-v1') }}" class="btn btn-outline" data-text="Load More">
                <span>Load More</span>
            </a>
        </div>
    </div>
</div>
<!-- Shop End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer')
  
@endsection