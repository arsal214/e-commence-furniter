<!-- resources/views/index-v4.blade.php -->
@extends('layouts.main')

@section('title', 'Index-V4 Page')

@section('content')

@include('includes.navbar3')

<!-- Banner Area Start -->
<div class="pt-40 sm:pt-52 lg:pt-[280px] pb-52 lg:pb-[350px] 2xl:pb-[450px] bg-overlay dark:before:bg-title dark:before:bg-opacity-70" style="background-image: url('{{ asset('assets/img/home-v4/banner-bg.jpg') }}');">
    <div class="container">
        <div class="max-w-[751px] mx-auto">
            <h2 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl leading-snug sm:leading-snug md:leading-snug lg:leading-snug font-bold" data-aos="fade-up">A Collection of World Top Class <span class="font-secondary text-secondary font-normal">Furniture</span></h2>
            <div data-aos="fade-up" data-aos-delay="100">
                <a href="{{ url('/shop-v1') }}" class="group md:text-lg font-medium leading-none text-title dark:text-white flex items-center gap-3 mt-3">
                    <span class="text-underline leading-none">Go to Shop </span>
                    <svg class="fill-current text-title dark:text-white w-5 md:w-7" viewBox="0 0 31 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M30.2303 6.58892C30.5232 6.29603 30.5232 5.82116 30.2303 5.52826L25.4574 0.755293C25.1645 0.462399 24.6896 0.462399 24.3967 0.755293C24.1038 1.04819 24.1038 1.52306 24.3967 1.81595L28.6393 6.05859L24.3967 10.3012C24.1038 10.5941 24.1038 11.069 24.3967 11.3619C24.6896 11.6548 25.1645 11.6548 25.4574 11.3619L30.2303 6.58892ZM0 6.80859L29.7 6.80859V5.30859L0 5.30859L0 6.80859Z"/>
                    </svg>                        
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Banner Area End -->

<!-- Shop Area Start -->
<div class="container">
    <div class="max-w-1366 mx-auto">
        <div class="hv3-service-wrapper bg-white dark:bg-title rounded-[10px] -mt-16 relative z-10 xl:flex xl:justify-between sm:gap-5 grid sm:grid-cols-2">
            
            <!-- includes/Home/shop-area.blade.php -->
            @include('includes.Home.shop-area')

        </div>
    </div>
</div>
<!-- Shop Area End -->

<!-- New Arrival Area Start -->
<div class="s-py-100">
    <div class="container">
        <div class="max-w-1366 mx-auto">
            <!-- Title -->
            <div class="flex items-center justify-between gap-5 flex-wrap mb-6 pb-4 md:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up">
                <h2 class="font-semibold leading-none text-2xl sm:text-3xl lg:text-4xl">New Arrival</h2>
                <a href="{{ url('/shop-v3') }}" class="group flex items-center gap-[10px] font-medium md:text-lg leading-none text-title dark:text-white">
                    <span class="text-underline leading-none">See All Collection</span>
                    <svg class="w-5 md:w-[30px] fill-current text-title dark:text-white" viewBox="0 0 31 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M30.5311 6.43072C30.824 6.13783 30.824 5.66295 30.5311 5.37006L25.7581 0.59709C25.4653 0.304196 24.9904 0.304196 24.6975 0.59709C24.4046 0.889983 24.4046 1.36486 24.6975 1.65775L28.9401 5.90039L24.6975 10.143C24.4046 10.4359 24.4046 10.9108 24.6975 11.2037C24.9904 11.4966 25.4653 11.4966 25.7581 11.2037L30.5311 6.43072ZM0.300781 6.65039L30.0008 6.65039V5.15039L0.300781 5.15039L0.300781 6.65039Z"/>
                    </svg>                        
                </a>
            </div>
            
            <!-- Product -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 md:gap-8" data-aos="fade-up" data-aos-delay="100">
                
                <!-- includes/Home/new-arrival1.blade.php -->
                @include('includes.Home.new-arrival1')

            </div>
        </div>
    </div>
</div>
<!-- New Arrival Area End -->

<!-- Why Chose Area Start -->
<div class="s-py-100 bg-[#F5F5F5] dark:bg-dark-secondary">
    <div class="container">
        <div class="max-w-1366 mx-auto">
            <!-- Title -->
            <div class="mb-6 pb-4 md:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up">
                <h2 class="font-semibold leading-none text-2xl sm:text-3xl lg:text-4xl text-center  sm:text-left">Why you Choose Us</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:flex lg:justify-between gap-7 flex-wrap lg:flex-nowrap">
                
                <!-- includes/Home/services2.blade.php -->
                @include('includes.Home.services2')

            </div>
        </div>
    </div>
</div>
<!-- Why Chose Area End -->

<!-- Best Sellers Area Start -->
<div class="s-py-100-50">
    <div class="container">
        <div class="max-w-1366 mx-auto">
            <!-- Title and Buttons -->
            <div class="flex items-center justify-between gap-5 flex-wrap mb-6 pb-4 md:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up">
                <h2 class="font-semibold leading-none text-2xl sm:text-3xl lg:text-4xl">Best Sellers</h2>
                <div class="best-seller-btn flex gap-4 sm:gap-6 flex-wrap">
                    <button class="active sm:text-lg leading-none sm:leading-none text-title dark:text-white font-medium" data-filter="*">
                        <span>Day</span>
                    </button>
                    <button class="sm:text-lg leading-none sm:leading-none text-title dark:text-white font-medium" data-filter=".week">
                        <span>Week</span>
                    </button>
                    <button class="sm:text-lg leading-none sm:leading-none text-title dark:text-white font-medium" data-filter=".month">
                        <span>Month</span>
                    </button>
                </div>
            </div>
            <div class="bestSeller-isotope" data-aos="fade-up" data-aos-delay="100">
                <div class="bestSeller-sizer"></div>
                
                <!-- includes/Home/best-sellers.blade.php -->
                @include('includes.Home.best-sellers')

            </div>
        </div>
    </div>
</div> 
<!-- Best Sellers Area End -->

<!-- Trading Area Start -->
<div class="s-py-50-100">
    <div class="container">
        <div class="max-w-1366 mx-auto">
            <!-- Title -->
            <div class="flex items-center justify-between gap-5 flex-wrap mb-6 pb-4 md:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up">
                <h2 class="font-semibold leading-none text-2xl sm:text-3xl lg:text-4xl">Trending</h2>
                <a href="{{ url('/shop-v3') }}" class="group flex items-center gap-[10px] font-medium md:text-lg leading-none text-title dark:text-white">
                    <span class="text-underline leading-none">See All Collection</span>
                    <svg class="w-5 md:w-[30px] fill-current text-title dark:text-white" viewBox="0 0 31 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M30.5311 6.43072C30.824 6.13783 30.824 5.66295 30.5311 5.37006L25.7581 0.59709C25.4653 0.304196 24.9904 0.304196 24.6975 0.59709C24.4046 0.889983 24.4046 1.36486 24.6975 1.65775L28.9401 5.90039L24.6975 10.143C24.4046 10.4359 24.4046 10.9108 24.6975 11.2037C24.9904 11.4966 25.4653 11.4966 25.7581 11.2037L30.5311 6.43072ZM0.300781 6.65039L30.0008 6.65039V5.15039L0.300781 5.15039L0.300781 6.65039Z"/>
                    </svg>                        
                </a>
            </div>
            
            <!-- Product -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 sm:gap-8" data-aos="fade-up" data-aos-delay="100">
                
                <!-- includes/Home/trending.blade.php -->
                @include('includes.Home.trending')

            </div>
        </div>
    </div>
</div>
<!-- Trading Area End -->

<!-- Partner Slider Start -->        
<div class="s-py-100 bg-[#F5F5F5] dark:bg-dark-secondary" data-aos="fade-up">
    <div class="container">
        <div class="md:px-20 relative">
            <div class="owl-carousel partner-slider-02" data-carousel-items="5" data-carousel-margin="20" data-carousel-xl="4" data-carousel-lg="3" data-carousel-md="3" data-carousel-sm="2" data-carousel-xs="1" data-carousel-autoplay="true" data-carousel-loop="true" data-carousel-animateout="false">
                
                <!-- includes/Home/trusted-partner3.blade.php -->
                @include('includes.Home.trusted-partner3')

            </div>
            <div class="md:absolute md:top-[74%] md:left-0 transform md:-translate-y-2/4 mt-8 md:mt-0 w-full">
                <div class="flex gap-2 md:gap-4 justify-center md:justify-between">
                    <button class="icon ptnrSlider02_prev w-9 h-9 border dark:border-white flex items-center justify-center text-title border-title dark:text-white">
                        <span class="lnr lnr-arrow-left"></span>
                    </button>
                    <button class="icon ptnrSlider02_next w-9 h-9 border border-title dark:border-white flex items-center justify-center text-title dark:text-white">
                        <span class="lnr lnr-arrow-right"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Partner Slider End -->

<!-- Blog Area Start -->
<div class="s-py-100">
    <div class="container">
        <div class="max-w-1366 mx-auto">
            <div class="flex items-center justify-between gap-5 flex-wrap mb-6 pb-4 md:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up">
                <h2 class="font-semibold leading-none text-2xl sm:text-3xl lg:text-4xl">Latest Blog</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-5 md:gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                
                <!-- includes/Home/blog3.blade.php -->
                @include('includes.Home.blog3')

            </div>
        </div>
    </div>
</div>
<!-- Blog Area End -->

<!-- Newsletter Area Start -->
<div>
    <div class="container">
        <div class="bg-title bg-opacity-10 dark:bg-dark-secondary py-10 px-5 md:px-7 lg:px-12 xl:px-24 xl:pb-24 sm:pt-0 max-w-1366 mx-auto">

            <div class="max-w-[990px] flex justify-between items-end gap-7">
                <div class=" sm:max-w-md w-full xl:pt-20">
                    <h2 class="font-bold leading-none dark:text-white text-4xl" data-aos="fade-up">Newsletter</h2>
                    <p class="mt-3 md:mt-5 dark:text-white-light" data-aos="fade-up" data-aos-delay="100">Stay in the loop with exclusive offers and updates. Subscribe to our newsletter for the latest trends and promotions. </p>
                    <div class="mt-4 lg:mt-6 sm:flex" data-aos="fade-up" data-aos-delay="200">
                        <input class="w-full h-12 md:h-14 bg-snow border dark:bg-snow dark:bg-opacity-5 border-title focus:border-title dark:focus:border-primary border-opacity-10 p-4 outline-none dark:text-white sm:flex-1 sm:border-r-0 duration-300" type="text" placeholder="Enter your email address">
                        <button class="w-full h-12 bg-title text-white flex items-center justify-center text-base md:text-lg font-medium p-3 mt-3 sm:mt-0 sm:w-32 sm:h-auto sm:flex-none dark:bg-secondary dark:text-white">Subscribe</button>
                    </div>
                </div>
                <div class="hidden sm:block" data-aos="fade-down">
                    <img src="{{ asset('assets/img/shortcode/newsletter-2.png') }}" alt="Newsletter">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Newsletter Area End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer3')
  
@endsection