<!-- resources/views/shop-v4.blade.php -->
@extends('layouts.main')

@section('title', 'Shop-V4 Page')

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

<!-- Shop Area Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container-fluid">
        <div class="flex items-start justify-between gap-8 max-w-[1720px] mx-auto flex-col-reverse lg:flex-row pb-8 md:pb-[50px]">
            <!-- Choose Category -->
            <div>
                <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Choose Category</h4>
                <div class="shop-button flex gap-[15px] flex-wrap">
                    <button class="active btn btn-sm btn-theme-outline" data-filter="*" data-text="Sofa & Chair">
                        <span>Sofa & Chair</span>
                    </button>
                    <button class="btn btn-sm btn-theme-outline" data-filter=".Interior" data-text="Full Interior">
                        <span>Full Interior</span>
                    </button>
                    <button class="btn btn-sm btn-theme-outline" data-filter=".Vase" data-text="Lamp & Vase">
                        <span>Lamp & Vase</span>
                    </button>
                    <button class="btn btn-sm btn-theme-outline" data-filter=".Table" data-text="Table">
                        <span>Table</span>
                    </button>
                    <button class="btn btn-sm btn-theme-outline" data-filter=".Design" data-text="Art Design">
                        <span>Art Design</span>
                    </button>
                </div>
            </div>
            <div class="max-w-[562px] w-full grid sm:grid-cols-2 gap-8 md:gap-12">
                <!-- Price Range -->
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
                <!-- Choose Brand -->
                <div>
                    <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Choose Brand</h4>
                    <select class="outline-select small-select">
                        <option value="1">Navana Furniture</option>
                        <option value="2">RFL Furniture</option>
                        <option value="2">Gazi Furniture</option>
                        <option value="2">Plastic Furniture</option>
                        <option value="2">Luxury Furniture</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Product -->
        <div class="shop-isotope max-w-[1722px] mx-auto" data-aos="fade-up" data-aos-delay="300">
            <div class="shop-sizer"></div>
            
            <!-- includes/Shop/shops-v4.blade.php -->
            @include('includes.Shop.shops-v4')

        </div>
        <div class="text-center mt-7 md:mt-12">
            <a href="{{ url('/shop-v1') }}" class="btn btn-outline" data-text="Load More">
                <span>Load More</span>
            </a>
        </div>
    </div>
</div>
<!-- Shop Area End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer')
  
@endsection