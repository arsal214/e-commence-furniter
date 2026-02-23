<!-- resources/views/index-v5.blade.php -->
@extends('layouts.main')

@section('title', 'Index-V5 Page')

@section('content')

@include('includes.navbar')

<!-- Search -->
<div class="search_popup fixed top-0 left-0 bg-red dark:bg-[#39434D] bg-opacity-90 dark:bg-opacity-80 backdrop-blur-[3px] dark:backdrop-blur-[7.5px] w-full h-screen z-[999] px-[15px] md:px-[30px] py-12 md:py-[70px] overflow-y-auto transform scale-90 opacity-0 invisible transition-all duration-300 flex items-center justify-center">
    <div class="container">
        <div class="relative max-w-4xl mx-auto hdr-search-wrapper">
            <button class="hdr_search_close w-[36px] h-[36px] absolute bottom-full md:top-0 right-0 flex items-center justify-center bg-title dark:bg-white text-white dark:text-title">
                <svg class="fill-current" width="15" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.742 12.0717C11.6006 12.2131 11.445 12.2838 11.2753 12.2838C11.1056 12.2838 10.9501 12.2131 10.8086 12.0717L6.16295 7.42598L1.55968 12.0292C1.41826 12.1707 1.2627 12.2414 1.09299 12.2414C0.923289 12.2414 0.767726 12.1707 0.626304 12.0292L0.32932 11.7323C0.187898 11.5908 0.117187 11.4353 0.117188 11.2656C0.117187 11.0959 0.187898 10.9403 0.329319 10.7989L4.93258 6.19561L0.414172 1.6772C0.272751 1.53578 0.20204 1.38021 0.20204 1.21051C0.20204 1.0408 0.272751 0.885239 0.414172 0.743817L0.73237 0.42562C0.873792 0.284198 1.02935 0.213487 1.19906 0.213487C1.36877 0.213488 1.52433 0.284198 1.66575 0.42562L6.18416 4.94403L10.8086 0.319553C10.9501 0.178132 11.1056 0.107421 11.2753 0.107422C11.445 0.107422 11.6006 0.178133 11.742 0.319554L12.039 0.616539C12.1804 0.75796 12.2511 0.913524 12.2511 1.08323C12.2511 1.25293 12.1804 1.4085 12.039 1.54992L7.41453 6.1744L12.0602 10.8201C12.2016 10.9615 12.2724 11.1171 12.2724 11.2868C12.2724 11.4565 12.2016 11.612 12.0602 11.7535L11.742 12.0717Z"/>
                </svg>
            </button>

            <div class="bg-white dark:bg-title py-8 sm:py-10 md:py-[60px] px-5 sm:px-8">
                <!-- Input -->
                <div class="relative">
                    <input class="outline-none border-b border-bdr-clr dark:border-bdr-clr-drk pb-4 md:pb-[22px] text-title w-full pr-7 md:pr-10 leading-none font-lg placeholder:text-title bg-transparent dark:bg-transparent dark:text-white dark:placeholder:text-white" type="text" placeholder="Type your keyword">
                    <button class="absolute right-0 top-0">
                        <svg class="fill-current text-title dark:text-white w-5 md:w-[30px]" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M29.5439 28.2361L22.1484 20.5625C24.0499 18.3074 25.0917 15.4701 25.0917 12.5162C25.0917 5.61489 19.4635 0 12.5459 0C5.62818 0 0 5.61489 0 12.5162C0 19.4176 5.62818 25.0325 12.5459 25.0325C15.1429 25.0325 17.6177 24.251 19.7335 22.7676L27.1852 30.4994C27.4967 30.8221 27.9156 31 28.3646 31C28.7895 31 29.1926 30.8384 29.4986 30.5445C30.1488 29.9203 30.1695 28.8853 29.5439 28.2361ZM12.5459 3.26511C17.6591 3.26511 21.8189 7.41506 21.8189 12.5162C21.8189 17.6174 17.6591 21.7674 12.5459 21.7674C7.43261 21.7674 3.27283 17.6174 3.27283 12.5162C3.27283 7.41506 7.43261 3.26511 12.5459 3.26511Z"/>
                        </svg>
                    </button>
                </div>
                <!-- Tags -->
                <div class="mt-10 md:mt-12">
                    <h4 class="font-medium leading-none text-2xl">Popular Tags</h4>
                    <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Chair"><span>Chair</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Art & Paint"><span>Art & Paint</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Mirror"><span>Mirror</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Table"><span>Table</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Lamp"><span>Lamp</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Header End -->

<!-- Banner Area Start -->
<div class="bg-primary-light dark:bg-dark-secondary relative overflow-hidden">
    <!-- Category -->
    <div class="absolute min-h-full left-0 top-0 w-[300px] xl:w-96 h-full bg-[#DBCBBD] dark:bg-primary home-v5-ctagory pt-[30px] xl:pt-16 xl:pl-24 2xl:pb-7 z-10 transform translate-x-[-300px] xl:translate-x-0 duration-300">
        <button class="ctgry-slider-btn flex items-center gap-2 text-base font-medium text-title bg-primary py-[10px] px-5 absolute left-full top-5 xl:opacity-0">
            Category 
            <svg class="fill-current text-title" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.28754 10.875C0.104206 10.6917 0.01254 10.4583 0.01254 10.175C0.01254 9.89167 0.104206 9.65833 0.28754 9.475L4.16254 5.575L0.28754 1.675C0.104206 1.49167 0.00853994 1.26233 0.000539938 0.987C-0.00812673 0.712333 0.0875396 0.475 0.28754 0.275C0.470873 0.0916663 0.704207 0 0.98754 0C1.27087 0 1.50421 0.0916663 1.68754 0.275L6.28754 4.875C6.38754 4.975 6.45854 5.08333 6.50054 5.2C6.54187 5.31667 6.56254 5.44167 6.56254 5.575C6.56254 5.70833 6.54187 5.83333 6.50054 5.95C6.45854 6.06667 6.38754 6.175 6.28754 6.275L1.68754 10.875C1.50421 11.0583 1.27487 11.154 0.99954 11.162C0.724873 11.1707 0.48754 11.075 0.28754 10.875ZM6.88754 10.875C6.70421 10.6917 6.61254 10.4583 6.61254 10.175C6.61254 9.89167 6.70421 9.65833 6.88754 9.475L10.7625 5.575L6.88754 1.675C6.70421 1.49167 6.60821 1.26233 6.59954 0.987C6.59154 0.712333 6.68754 0.475 6.88754 0.275C7.07087 0.0916663 7.30421 0 7.58754 0C7.87087 0 8.10421 0.0916663 8.28754 0.275L12.8875 4.875C12.9875 4.975 13.0582 5.08333 13.0995 5.2C13.1415 5.31667 13.1625 5.44167 13.1625 5.575C13.1625 5.70833 13.1415 5.83333 13.0995 5.95C13.0582 6.06667 12.9875 6.175 12.8875 6.275L8.28754 10.875C8.10421 11.0583 7.87521 11.154 7.60054 11.162C7.32521 11.1707 7.08754 11.075 6.88754 10.875Z"/>
            </svg>                    
        </button>
        <h3 class="font-semibold leading-none mb-6 pl-5 xl:pl-0 text-title dark:text-title text-3xl">Product Category</h3>
        <ul class="divide-y divide-[#C5B7AA] dark:divide-bdr-clr-drk sm:text-lg leading-none sm:leading-none text-title dark:text-white pb-5">
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Chair</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Table</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Sofa Set</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Vases</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Lamp & Light</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Almirah</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Dressing table</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Watch</a></li>
            <li class="pl-5 xl:pl-0 py-4 sm:py-5 xl:py-6 relative z-[1] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:z-[-1] block before:duration-500 dark:text-title"><a href="{{ url('/product-category') }}">Mirror</a></li> 
        </ul>
    </div>
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <div class="xl:max-w-[800px] 2xl:max-w-[1180px] ml-auto flex items-center justify-between gap-10 flex-col sm:flex-row">
                <div class="max-w-[285px] md:max-w-[364px] w-full sm:pb-12">
                    <div class="carousel-slider-one home-v5-banner-slider owl-carousel sm:h-[500px] md:h-[600px] xl:h-[765px] pb-8 sm:pb-0" data-carousel-dots="true" data-carousel-items="1" data-carousel-margin="0" data-carousel-loop="true">
                        <!-- Single -->
                        <div class="max-w-[200px] md:max-w-[240px] mx-auto lg:w-[300px] flex-none crsl-slider-one-thumb">
                            <div class="relative h-[350px] md:h-[500px] xl:h-auto">
                                <img class="h-full xl:h-auto mx-auto" src="{{ asset('assets/img/shortcode/carousel/carousel-01.png') }}" alt="carousel">
                                <!-- Price -->
                                <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 dark:bg-white dark:bg-opacity-20 rounded-full p-3 right-0 sm:right-[-10%] bottom-[17%]">
                                    <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full dark:bg-title flex items-center justify-center">
                                        <h4 class="font-normal leading-none text-title text-2xl"><sup>$</sup>25</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-6 md:mt-9 xl:mt-12 group">
                                <a href="#" class="text-lg leading-tight text-underline text-title dark:text-white inline-block">Buy Now</a>
                            </div>
                        </div>
                        <!-- Single -->
                        <div class="max-w-[240px] mx-auto lg:w-[326px] flex-none crsl-slider-one-thumb pt-12 sm:pt-[100px]">
                            <div class="relative h-[350px] md:h-[500px] xl:h-auto">
                                <img class="h-full xl:h-auto mx-auto" src="{{ asset('assets/img/shortcode/carousel/carousel-022.png') }}" alt="carousel">
                                <!-- Price -->
                                <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 dark:bg-white dark:bg-opacity-20 rounded-full p-3 right-[30%] bottom-[30%]">
                                    <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full dark:bg-title flex items-center justify-center">
                                        <h4 class="font-normal leading-none text-title text-2xl"><sup>$</sup>55</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-6 md:mt-9 xl:mt-12 group">
                                <a href="{{ url('/shop-v1') }}" class="text-lg leading-tight text-underline text-title dark:text-white inline-block">Buy Now</a>
                            </div>
                        </div>
                        <!-- Single -->
                        <div class="max-w-[240px] mx-auto lg:w-[326px] flex-none crsl-slider-one-thumb pt-12 sm:pt-[100px]">
                            <div class="relative h-[350px] md:h-[500px] xl:h-auto">
                                <img class="h-full xl:h-auto mx-auto" src="{{ asset('assets/img/shortcode/carousel/carousel-03.png') }}" alt="carousel">
                                <!-- Price -->
                                <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 dark:bg-white dark:bg-opacity-20 rounded-full p-3 right-[18%] bottom-[24%]">
                                    <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full dark:bg-title flex items-center justify-center">
                                        <h4 class="font-normal leading-none text-title text-2xl"><sup>$</sup>120</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-6 md:mt-9 xl:mt-12 group">
                                <a href="{{ url('/shop-v2') }}" class="text-lg leading-tight text-underline text-title dark:text-white inline-block">Buy Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="max-w-[735px] w-full relative pb-12 sm:pb-0">
                    <svg class="w-full fill-[#BB976D] absolute top-[164%] left-[100%] transfrom -translate-x-1/2 -translate-y-1/2" viewBox="0 0 735 614" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.1" d="M465.663 12.3984C608.469 51.1584 736.824 180.997 734.98 300.933C733.582 421.174 601.229 531.645 449.583 582.603C298.382 633.866 126.374 625.88 49.0544 532.389C-27.9532 438.459 -11.0285 259.156 76.9747 142.994C164.533 26.5264 322.546 -25.924 465.663 12.3984Z"/>
                    </svg>                            
                    <div class="text-center sm:text-left max-w-[438px] mx-auto">
                        <h4 class="leading-none font-medium dark:text-white text-2xl">All products in store</h4>
                        <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-primary">25%</span> Off</h2>
                        <p class="mt-4 dark:text-white-light">Unlock unbeatable savings on every purchase. Enjoy a generous 25% off on all in-store products today. </p>
                        <div class="button">
                            <a class="btn btn-outline mt-6" href="{{ url('/shop-v1') }}" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                        </div>
                    </div>
                </div>

                <!-- Old Slider-->
                <div class="pt-0 pb-12 md:pb-[70px] lg:pb-8 2xl:pb-52 hidden">
                    <div class="carousel-slider-one home-v5-banner-slider owl-carousel" data-carousel-dots="true" data-carousel-items="1" data-carousel-margin="0" data-carousel-loop="true">

                        <!-- Banner Old Single Slide -->
                        <div>
                            <div class="flex items-center xl:items-end gap-10 sm:gap-16 2xl:gap-20 justify-between flex-col sm:flex-row">
                                <div class="max-w-xs mx-auto lg:w-[326px] flex-none crsl-slider-one-thumb">
                                    <div class="relative h-[300px] md:h-[400px] xl:h-auto">
                                        <img class="h-full xl:h-auto" src="{{ asset('assets/img/shortcode/carousel/carousel-01.png') }}" alt="carousel">
                                        <!-- Price -->
                                        <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 dark:bg-white dark:bg-opacity-20 rounded-full p-3 right-0 sm:right-[-10%] bottom-[17%]">
                                            <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full dark:bg-title flex items-center justify-center">
                                                <h4 class="font-normal leading-none text-title"><sup>$</sup>25</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-6 md:mt-9 xl:mt-12 group">
                                        <a href="{{ url('/shop-v2') }}" class="text-lg leading-tight text-underline text-title dark:text-white inline-block">Buy Now</a>
                                    </div>
                                </div>
                                <div class="xl:flex-1 max-w-[400px] sm:max-w-xl xl:pb-16 2xl:pb-24 crsl-slider-one-content text-center sm:text-left">
                                    <h4 class="leading-none font-medium dark:text-white">All products in store</h4>
                                    <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-primary">25%</span> Off</h2>
                                    <p class="mt-4 md:mt-5 dark:text-white-light">Unlock unbeatable savings on every purchase. Enjoy a generous 25% off on all in-store products today. </p>
                                    <div class="button">
                                        <a class="btn btn-outline mt-6" href="{{ url('/shop-v1') }}" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Banner Old Single Slide -->
                        <div class="pt-8">
                            <div class="flex items-center xl:items-end gap-10 sm:gap-16 2xl:gap-20 justify-between flex-col sm:flex-row">
                                <div class="max-w-xs mx-auto lg:w-[326px] flex-none crsl-slider-one-thumb">
                                    <div class="relative h-[300px] md:h-[400px] xl:h-auto">
                                        <img class="h-full xl:h-auto" src="{{ asset('assets/img/shortcode/carousel/carousel-02.png') }}" alt="carousel">
                                        <!-- Price -->
                                        <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 dark:bg-white dark:bg-opacity-20 rounded-full p-3 right-[30%] bottom-[30%]">
                                            <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full dark:bg-title flex items-center justify-center">
                                                <h4 class="font-normal leading-none text-title"><sup>$</sup>55</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 md:mt-9 xl:mt-12 pl-14 group">
                                        <a href="{{ url('/shop-v1') }}" class="text-lg leading-tight text-underline text-title dark:text-white inline-block">Buy Now</a>
                                    </div>
                                </div>
                                <div class="xl:flex-1 max-w-[400px] sm:max-w-xl xl:pb-16 2xl:pb-24 crsl-slider-one-content text-center sm:text-left">
                                    <h4 class="leading-none font-medium dark:text-white">All products in store</h4>
                                    <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-primary">55%</span> Off</h2>
                                    <p class="mt-4 md:mt-5 dark:text-white-light">Unlock unbeatable savings on every purchase. Enjoy a generous 25% off on all in-store products today. </p>
                                    <div class="button">
                                        <a class="btn btn-outline mt-6" href="{{ url('/shop-v2') }}" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Banner Old Single Slide -->
                        <div class="pt-8">
                            <div class="flex items-center xl:items-end gap-10 sm:gap-16 2xl:gap-20 justify-between flex-col sm:flex-row">
                                <div class="max-w-xs mx-auto lg:w-[326px] flex-none crsl-slider-one-thumb">
                                    <div class="relative h-[300px] md:h-[400px] xl:h-auto">
                                        <img class="h-full xl:h-auto" src="{{ asset('assets/img/shortcode/carousel/carousel-03.png') }}" alt="carousel">
                                        <!-- Price -->
                                        <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 dark:bg-white dark:bg-opacity-20 rounded-full p-3 right-[18%] bottom-[24%]">
                                            <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full dark:bg-title flex items-center justify-center">
                                                <h4 class="font-normal leading-none text-title"><sup>$</sup>120</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pl-20 mt-6 md:mt-9 xl:mt-12 group">
                                        <a href="{{ url('/shop-v2') }}" class="text-lg leading-tight text-underline text-title dark:text-white inline-block">Buy Now</a>
                                    </div>
                                </div>
                                <div class="xl:flex-1 max-w-[400px] sm:max-w-xl xl:pb-16 2xl:pb-24 crsl-slider-one-content text-center sm:text-left">
                                    <h4 class="leading-none font-medium dark:text-white">All products in store</h4>
                                    <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-primary">120%</span> Off</h2>
                                    <p class="mt-4 md:mt-5 dark:text-white-light">Furnixar is a modern furniture HTML template for an eCommerce website designed to help you create an impressive online store for your furniture. </p>
                                    <div class="button">
                                        <a class="btn btn-outline mt-6" href="{{ url('/shop-v3') }}" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner Area End -->

<!-- Why Chose Us Area Start -->
<div class="s-py-100-50">
    <div class="container">
        <div class="grid sm:grid-cols-2 lg:flex lg:justify-between gap-7 flex-wrap lg:flex-nowrap max-w-1366 mx-auto">
            
            <!-- includes/Home/services2.blade.php -->
            @include('includes.Home.services2')

        </div>
    </div>
</div>
<!-- Why Chose Us Area End -->

    <!-- Product Area Start -->
    <div class="s-py-50" data-aos="fade-up">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto grid sm:grid-cols-2 lg:grid-cols-3">
            
            <!-- includes/Home/product.blade.php -->
            @include('includes.Home.product')

        </div>
    </div>
    </div>
    <!-- Product Area End -->

<!-- Latest Products Start -->
<div class="s-py-50">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <!-- Title -->
            <div class="max-w-[547px] mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
                <svg class="mx-auto" width="76" height="63" viewBox="0 0 76 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M71.749 29.234V12.4939C71.749 10.1443 69.8384 8.23257 67.4896 8.23257H60.3281C57.3673 8.23257 54.4966 7.17838 52.245 5.26413C48.2516 1.8695 43.1632 0 37.9167 0C32.6701 0 27.5817 1.8695 23.5883 5.26413C21.3367 7.17838 18.466 8.23257 15.5053 8.23257H8.34374C5.99513 8.23257 4.08434 10.1442 4.08434 12.4939V29.234C1.61146 31.0362 0 33.9535 0 37.2419V60.3639C0 61.8175 1.18193 63 2.63476 63H5.16674C6.61972 63 7.80165 61.8175 7.80165 60.3639V58.2235C7.80165 57.0665 8.74246 56.1254 9.89877 56.1254H65.9349C67.0912 56.1254 68.0317 57.0667 68.0317 58.2234V60.3639C68.0317 61.8175 69.2136 63 70.6666 63H73.1986C74.6514 63 75.8333 61.8175 75.8333 60.3639V37.2419C75.8333 33.9535 74.2219 31.0362 71.749 29.234V29.234ZM6.30602 12.4939C6.30602 11.3698 7.22016 10.4553 8.34374 10.4553H15.5053C18.9925 10.4553 22.3741 9.21326 25.0269 6.95803C28.6191 3.90438 33.1966 2.22278 37.9167 2.22278C42.6367 2.22278 47.2143 3.90453 50.8064 6.95818C53.4592 9.21341 56.8408 10.4555 60.3281 10.4555H67.4896C68.6132 10.4555 69.5273 11.3699 69.5273 12.4941V28.0165C68.4128 27.5805 67.2018 27.3384 65.9347 27.3384H62.1352V18.7672C62.1352 17.0955 60.7758 15.7353 59.105 15.7353H45.6749C44.0041 15.7353 42.6445 17.0955 42.6445 18.7672V20.0503C42.6445 20.6643 43.1418 21.1617 43.7554 21.1617C44.369 21.1617 44.8662 20.6643 44.8662 20.0503V18.7672C44.8662 18.321 45.229 17.9581 45.6749 17.9581H59.105C59.5508 17.9581 59.9135 18.321 59.9135 18.7672V27.3384H44.8662V25.2461C44.8662 24.6322 44.369 24.1348 43.7554 24.1348C43.1418 24.1348 42.6445 24.6322 42.6445 25.2461V27.3384H33.1888V18.7672C33.1888 17.0955 31.8293 15.7353 30.1584 15.7353H16.7284C15.0575 15.7353 13.6981 17.0955 13.6981 18.7672V27.3384H9.89862C8.63152 27.3384 7.42056 27.5804 6.30602 28.0165V12.4939ZM15.9198 27.3384V18.7672C15.9198 18.321 16.2825 17.9581 16.7284 17.9581H30.1584C30.6044 17.9581 30.9671 18.321 30.9671 18.7672V27.3384H15.9198ZM73.1986 60.7772H70.6666C70.4388 60.7772 70.2534 60.5918 70.2534 60.3639V58.2234C70.2534 55.8409 68.3161 53.9026 65.9349 53.9026H9.89877C7.51742 53.9026 5.57997 55.841 5.57997 58.2235V60.3639C5.57997 60.5918 5.39453 60.7772 5.16674 60.7772H2.63476C2.40697 60.7772 2.22168 60.5918 2.22168 60.3639V46.5593H5.61596C6.22959 46.5593 6.7268 46.0619 6.7268 45.4479C6.7268 44.834 6.22959 44.3365 5.61596 44.3365H2.22168V37.2419C2.22168 33.0067 5.66558 29.5611 9.89862 29.5611H65.9347C70.1678 29.5611 73.6117 33.0067 73.6117 37.2419V44.3365H10.8369C10.2233 44.3365 9.72607 44.834 9.72607 45.4479C9.72607 46.0619 10.2233 46.5593 10.8369 46.5593H73.6117V60.3639C73.6117 60.5918 73.4264 60.7772 73.1986 60.7772V60.7772Z" fill="#BB976D"/>
                </svg>                   
                <h2 class="mt-[15px] leading-none text-4xl font-bold">Latest Products</h2>
                <p class="mt-[10px] md:mt-[15px]">Be the first to experience innovation with our latest arrivals. Stay ahead of the curve and discover what's new in style, technology, and more. </p>
            </div>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 lg:gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                
                <!-- includes/Home/latest-products.blade.php -->
                @include('includes.Home.latest-products')

            </div>
            <div class="text-center mt-7 md:mt-12" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ url('/shop-v1') }}" class="btn btn-outline" data-text="See all Products">
                    <span>See all Products</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Latest Products End -->

<!-- Blog Area Start -->
<div class="s-py-50 overflow-hidden">
    <div class="container-fluid" data-aos="fade-up">
        <!-- Title -->
        <div class="max-w-[547px] mx-auto mb-8 sm:mb-[70px] text-center">
            <svg class="mx-auto" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M54.1712 13.3447C51.1919 10.3694 47.603 8.07541 43.6517 6.62074C39.7004 5.16606 35.4808 4.58531 31.2834 4.91849C27.583 5.20919 23.9747 6.21786 20.66 7.88816C20.3943 8.02261 20.1928 8.25689 20.0996 8.53966C20.0064 8.82242 20.0291 9.13061 20.1627 9.39668C20.2963 9.66275 20.5299 9.865 20.8124 9.9591C21.0949 10.0532 21.4032 10.0315 21.6696 9.89869C24.7242 8.35948 28.0493 7.42992 31.4592 7.16195C35.6479 6.82393 39.8575 7.47425 43.749 9.06055C47.6404 10.6469 51.105 13.1248 53.8637 16.2949C56.6224 19.4649 58.5981 23.2385 59.6318 27.3117C60.6655 31.3849 60.7282 35.644 59.815 39.7459C58.9018 43.8478 57.0381 47.6779 54.3739 50.9279C51.7098 54.1778 48.3198 56.7568 44.4768 58.457C40.6337 60.1573 36.4452 60.9313 32.2483 60.7169C28.0514 60.5024 23.9636 59.3054 20.3139 57.2223C19.7228 56.8884 19.0678 56.6832 18.3918 56.6203C17.7159 56.5573 17.0343 56.638 16.3917 56.857L7.80458 59.7162L10.6627 51.1324C10.8835 50.4918 10.9652 49.8115 10.9025 49.1368C10.8398 48.4621 10.6341 47.8086 10.2991 47.2196C7.75663 42.7571 6.54397 37.6604 6.80425 32.5311C6.99787 28.7145 8.02183 24.9862 9.8047 21.6061C9.87344 21.4753 9.91576 21.3322 9.92922 21.1851C9.94269 21.0379 9.92704 20.8896 9.88317 20.7485C9.8393 20.6074 9.76807 20.4763 9.67355 20.3628C9.57902 20.2492 9.46306 20.1554 9.33227 20.0866C9.20148 20.0179 9.05843 19.9756 8.91129 19.9621C8.76415 19.9486 8.6158 19.9643 8.47471 20.0081C8.33361 20.052 8.20254 20.1232 8.08898 20.2178C7.97541 20.3123 7.88157 20.4283 7.81283 20.559C5.87824 24.2277 4.76714 28.2741 4.55699 32.4163C4.2749 37.9775 5.59054 43.5033 8.34846 48.3408C8.52452 48.6541 8.63172 49.0014 8.66288 49.3595C8.69405 49.7176 8.64847 50.0782 8.5292 50.4172L4.95962 61.1385C4.89365 61.3366 4.88416 61.5493 4.93218 61.7525C4.98021 61.9558 5.08386 62.1416 5.23154 62.2893C5.37922 62.437 5.56509 62.5407 5.76835 62.5887C5.9716 62.6367 6.18421 62.6272 6.38237 62.5612L17.1069 58.9906C17.4487 58.8722 17.8116 58.8275 18.1718 58.8594C18.5321 58.8913 18.8815 58.9991 19.1971 59.1757C23.5981 61.6894 28.5801 63.0079 33.6483 63.0002C34.1334 63.0002 34.6193 62.9881 35.1062 62.9639C40.7246 62.6746 46.1386 60.7621 50.692 57.4581C55.2455 54.154 58.743 49.6004 60.7608 44.3488C62.7786 39.0972 63.23 33.3732 62.0604 27.8702C60.8909 22.3673 58.1504 17.3216 54.1712 13.3447V13.3447Z" fill="#BB976D"/>
                <path d="M22.8322 29.2756L29.7565 31.1351C29.9473 31.1863 30.1483 31.1863 30.3391 31.1352C30.5299 31.0841 30.7039 30.9837 30.8436 30.844C30.9833 30.7043 31.0838 30.5303 31.1349 30.3394C31.186 30.1486 31.186 29.9477 31.1348 29.7568L29.2753 22.8315C29.1152 22.2369 28.8019 21.6948 28.3667 21.2593L11.3368 4.22982L11.3359 4.22845L11.3345 4.22755L8.13439 1.02749C7.47535 0.369542 6.58216 0 5.6509 0C4.71965 0 3.82645 0.369542 3.16741 1.02749L1.02717 3.16714H1.02662C0.369089 3.82655 -0.000103318 4.71981 2.16884e-08 5.65103C0.000103362 6.58226 0.369494 7.47544 1.02717 8.13471L21.2595 28.3665C21.6951 28.802 22.2375 29.1155 22.8322 29.2756V29.2756ZM26.4488 22.5231C26.4147 22.5431 26.3818 22.565 26.3501 22.5886L22.5889 26.3499C22.5652 26.3815 22.5433 26.4145 22.5233 26.4486L6.61474 10.5404L10.5402 6.61488L26.4488 22.5231ZM28.4557 28.456L24.6789 27.4416L27.4415 24.679L28.4557 28.456ZM2.61858 4.75799L4.75822 2.61835C4.99515 2.38196 5.31618 2.2492 5.65087 2.2492C5.98557 2.2492 6.30659 2.38196 6.54353 2.61835L8.94927 5.02409L5.02381 8.94964L2.61804 6.54385C2.38159 6.3068 2.24885 5.98562 2.24895 5.65081C2.24905 5.31599 2.38199 4.9949 2.61858 4.75799V4.75799Z" fill="#BB976D"/>
                <path d="M52.0644 36.4375H21.3438C21.0454 36.4375 20.7592 36.556 20.5483 36.767C20.3373 36.978 20.2188 37.2642 20.2188 37.5625C20.2188 37.8609 20.3373 38.1471 20.5483 38.358C20.7592 38.569 21.0454 38.6876 21.3438 38.6876H52.0644C52.3628 38.6876 52.649 38.569 52.86 38.358C53.0709 38.1471 53.1895 37.8609 53.1895 37.5625C53.1895 37.2642 53.0709 36.978 52.86 36.767C52.649 36.556 52.3628 36.4375 52.0644 36.4375Z" fill="#BB976D"/>
                <path d="M52.0646 43.9521H30.0469C29.7485 43.9521 29.4624 44.0707 29.2514 44.2817C29.0404 44.4926 28.9219 44.7788 28.9219 45.0772C28.9219 45.3755 29.0404 45.6617 29.2514 45.8727C29.4624 46.0837 29.7485 46.2022 30.0469 46.2022H52.0646C52.3629 46.2022 52.6491 46.0837 52.8601 45.8727C53.0711 45.6617 53.1896 45.3755 53.1896 45.0772C53.1896 44.7788 53.0711 44.4926 52.8601 44.2817C52.6491 44.0707 52.3629 43.9521 52.0646 43.9521Z" fill="#BB976D"/>
                <path d="M22.1914 45.0766C22.1914 45.3128 22.2614 45.5436 22.3926 45.7399C22.5238 45.9363 22.7102 46.0893 22.9284 46.1797C23.1465 46.27 23.3866 46.2937 23.6182 46.2476C23.8498 46.2015 24.0625 46.0878 24.2295 45.9209C24.3964 45.7539 24.5101 45.5412 24.5562 45.3096C24.6023 45.078 24.5786 44.8379 24.4883 44.6198C24.3979 44.4016 24.2449 44.2152 24.0485 44.084C23.8522 43.9528 23.6214 43.8828 23.3852 43.8828C23.0686 43.8828 22.765 44.0086 22.5411 44.2325C22.3172 44.4564 22.1914 44.76 22.1914 45.0766Z" fill="#BB976D"/>
            </svg>   
            <h2 class="mt-[15px] leading-none text-4xl font-bold">From the Blog</h2>
            <p class="mt-[10px] md:mt-[15px]">Stay informed and inspired with our latest blog posts. Explore insightful content that keeps you ahead of trends and informed on topics you love. </p>
        </div>
    </div>
    <div data-aos="fade-up" data-aos-delay="100">
        <div class="home-v5-blog-slider owl-carousel sm:max-w-[500px] lg:max-w-[750px] mx-auto  sm:mb-8" data-carousel-center="true" data-carousel-loop="true" data-carousel-autoplay="true" data-carousel-animateout="false" data-carousel-animatein="false" data-carousel-margin="0">
            
            <!-- includes/Home/blog4.blade.php -->
            @include('includes.Home.blog4')

        </div>
    </div>
</div>
<!-- Blog Area End -->

<!-- Subscribe Area Start -->
<div class="s-py-50-100">
    <div class="container">
        <div class="max-w-xl mx-auto text-center">
            <div data-aos="fade-up">
                <svg class="mx-auto" width="55" height="55" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_2129_1895)">
                    <path d="M16.144 26.1533H38.8631C39.3249 26.1533 39.6985 25.7795 39.6985 25.3178C39.6985 24.8561 39.3248 24.4824 38.8631 24.4824H16.144C15.6822 24.4824 15.3086 24.8561 15.3086 25.3178C15.3086 25.7795 15.6822 26.1533 16.144 26.1533V26.1533Z" fill="#BB976D"/>
                    <path d="M16.144 18.2021H38.8631C39.3249 18.2021 39.6985 17.8284 39.6985 17.3667C39.6985 16.905 39.3248 16.5312 38.8631 16.5312H16.144C15.6822 16.5312 15.3086 16.905 15.3086 17.3667C15.3086 17.8284 15.6822 18.2021 16.144 18.2021V18.2021Z" fill="#BB976D"/>
                    <path d="M16.144 10.2519H38.8631C39.3249 10.2519 39.6985 9.87817 39.6985 9.41647C39.6985 8.95467 39.3248 8.58105 38.8631 8.58105H16.144C15.6822 8.58105 15.3086 8.95478 15.3086 9.41647C15.3086 9.87828 15.6822 10.2519 16.144 10.2519V10.2519Z" fill="#BB976D"/>
                    <path d="M18.4525 37.4339C18.4609 37.4423 18.4777 37.4506 18.4943 37.4673V37.4589L18.4525 37.4339ZM44.7612 29.0876H44.7696C44.7696 29.0793 44.778 29.0709 44.7947 29.0709C44.7864 29.0709 44.778 29.0793 44.7612 29.0876V29.0876ZM53.4 35.2701C53.8614 35.2701 54.2354 34.896 54.2354 34.4347V25.6122C54.2354 24.1251 53.517 22.6547 52.3222 21.7607L46.5992 17.433V5.00955C46.5992 2.68408 44.7139 0.798828 42.3885 0.798828H12.6209C10.2954 0.798828 8.41019 2.68408 8.41019 5.00955V17.4329L2.68719 21.7606C1.58439 22.5877 0.765625 24.0331 0.765625 25.6121V49.3644C0.765625 51.971 2.90439 54.2017 5.61132 54.2017H49.398C52.1717 54.2017 54.2353 51.8958 54.2353 49.3644V40.8844C54.2353 40.423 53.8612 40.049 53.3999 40.049C52.9385 40.049 52.5644 40.423 52.5644 40.8844V49.3644C52.5644 49.8656 52.4391 50.3502 52.2052 50.793L35.5544 38.1858C37.9679 36.3532 48.8095 28.1033 52.3259 25.4866C52.4366 25.5449 52.4538 25.5539 52.5644 25.6121V34.4346C52.5644 34.896 52.9385 35.2701 53.4 35.2701V35.2701ZM3.68986 23.0974C3.69824 23.089 3.69824 23.089 3.69824 23.089L8.4103 19.5299V27.7258L3.10506 23.7072C3.23031 23.5401 3.3807 23.3229 3.68986 23.0974V23.0974ZM2.43668 49.3644C2.44506 42.8227 2.41992 25.9379 2.47009 25.3281C12.4205 32.8639 16.7482 36.139 18.4525 37.434C18.4609 37.4424 18.4777 37.4507 18.4943 37.4674V37.459C18.5195 37.4757 18.5361 37.4924 18.5611 37.5175C18.5779 37.5257 18.5863 37.5341 18.6029 37.5425C18.6197 37.5592 18.6447 37.5759 18.6614 37.5926C18.6781 37.601 18.6865 37.6094 18.7031 37.6178C18.7031 37.6261 18.7115 37.6261 18.7199 37.6344L18.745 37.6512C18.7617 37.6596 18.7784 37.6763 18.7952 37.693H18.8036C18.8286 37.7097 18.8537 37.7348 18.8788 37.7514L18.8955 37.7682C18.9206 37.7848 18.9373 37.8016 18.9624 37.81C18.9624 37.8183 18.9706 37.8267 18.979 37.8267C18.979 37.835 18.9874 37.835 18.9958 37.8434C19.0375 37.8685 19.0793 37.9018 19.1294 37.9436L19.1545 37.9604C19.1629 37.9687 19.1629 37.9687 19.1713 37.9771L19.1879 37.9855C19.1963 37.9938 19.1963 37.9938 19.1963 37.9938C19.1963 37.9938 19.1963 37.9938 19.2047 38.0021C19.2131 38.0021 19.2215 38.0105 19.2215 38.0105C19.2442 38.028 19.2447 38.0286 19.2587 38.0395C19.2658 38.0466 19.2728 38.0536 19.2799 38.0607C19.3802 38.1359 19.3969 38.1443 19.3301 38.0941C19.3384 38.1024 19.3552 38.1107 19.3635 38.1191C19.4219 38.1693 19.3885 38.1359 19.3885 38.1359C19.3885 38.1359 19.3969 38.1443 19.4053 38.1525L19.422 38.1609C19.4304 38.1693 19.447 38.1777 19.4554 38.186L2.80428 50.8014C2.57031 50.3586 2.43668 49.8656 2.43668 49.3644V49.3644ZM50.0497 51.2608L50.0665 51.2776H50.0748V51.2859H50.0832C50.1751 51.3527 50.4174 51.5365 51.069 52.0295C50.7098 52.2551 50.2001 52.5307 49.3981 52.5307H5.61143C5.12685 52.5307 4.55869 52.4221 3.94048 52.0295C6.23802 50.2834 25.5122 35.6961 25.5122 35.6961C26.6902 34.8189 28.3193 34.8189 29.489 35.6961C47.0505 48.9884 49.7239 51.0102 50.0497 51.2608V51.2608ZM44.9284 5.00955V5.40229V28.9623C44.9284 28.9623 44.92 28.9706 44.8866 28.9957C44.8448 29.0291 44.8114 29.0458 44.7947 29.0709C44.7864 29.0709 44.778 29.0793 44.7613 29.0876L44.7531 29.096C44.1849 29.5304 41.9125 31.2598 34.1677 37.1415C34.0841 37.0747 30.7256 34.5181 30.5084 34.3594C28.7372 33.031 26.2726 33.031 24.5097 34.3594C24.4262 34.4262 21.051 36.9827 20.8421 37.1415L10.0811 28.9874C10.0811 28.695 10.0811 28.603 10.0811 28.603V5.00955C10.0811 3.61436 11.2173 2.46978 12.6209 2.46978H42.3885C43.7921 2.46978 44.9199 3.60598 44.9283 5.00117V5.00955H44.9284ZM46.5993 27.7091V19.5299L51.3197 23.0974C51.6456 23.3396 51.8377 23.6153 51.9046 23.7072L46.5993 27.7091Z" fill="#BB976D"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_2129_1895">
                    <rect width="55" height="55" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>
            </div>
            <h2 class="font-bold leading-none mt-4 dark:text-white text-4xl" data-aos="fade-up" data-aos-delay="100">Newsletter</h2>
            <p class="mt-4 dark:text-white-light" data-aos="fade-up" data-aos-delay="200">Stay in the loop with exclusive offers and updates. Subscribe to our newsletter for the latest trends and promotions delivered straight to your inbox. </p>
            <div class="mt-6 lg:mt-12 sm:flex" data-aos="fade-up" data-aos-delay="300">
                <input class="w-full h-12 bg-transparent md:h-14 border border-title dark:border-primary p-4 outline-none dark:text-white sm:flex-1 sm:border-r-0 duration-300 placeholder:text-paragraph dark:placeholder:text-white-light" type="text" placeholder="Enter your email address">
                <button class="w-full h-12 bg-title text-white flex items-center justify-center text-base md:text-lg font-medium p-3 mt-3 sm:mt-0 sm:w-32 sm:h-auto sm:flex-none dark:bg-primary">Subscribe</button>
            </div>
        </div>
    </div>
</div>
<!-- Subscribe Area End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer4')
  
@endsection