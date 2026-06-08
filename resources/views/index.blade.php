@extends('layouts.main')
@section('title', 'PeytonGhalib - Quality Furniture & Home Decor')
@section('meta_description', 'Shop quality furniture, home decor, ceramics and more at PeytonGhalib. Thousands of products at unbeatable prices with fast, reliable delivery nationwide.')
@section('content')
@include('includes.navbar')
<!-- Banner Start -->
<div class="carousel-slider-four owl-carousel" data-carousel-dots="true">
    @forelse ($sliders as $slide)
    <div class="relative pt-12 md:pt-20 xl:pt-[100px] pb-12 sm:pb-20 xl:pb-24 px-[15px] sm:px-12 bg-[#F5F5F5] dark:bg-title">
        <div class="max-w-[1720px] mx-auto">

            {{-- Floating badge blob (only if badge_price or badge_label set) --}}
            @if ($slide->badge_price || $slide->badge_label)
            <div class="absolute top-5 right-[30%] z-10 hidden lg:block shape-01">
                <svg class="w-[300px] xl:w-[500px] h-[250px] xl:h-[409px]" viewBox="0 0 501 410" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.1" d="M93.5685 350.941C17.9186 286.326 -22.6142 169.412 13.177 95.6561C48.7857 21.5837 161.217 -9.19765 268.179 2.36595C374.958 13.6135 477.265 67.4732 497.265 147.363C516.948 227.436 454.823 333.672 367.72 380.59C280.8 427.824 169.535 415.374 93.5685 350.941Z" fill="{{ $slide->badge_color }}"/>
                </svg>
                <div class="absolute top-1/4 left-[10%] xl:left-[20%] z-30">
                    @if($slide->badge_price)
                        <h4 class="leading-none font-semibold text-2xl md:text-1xl" style="color:{{ $slide->badge_color }}">{{ $slide->badge_price }}</h4>
                    @endif
                    @if($slide->badge_label)
                        <h3 class="leading-none md:mt-4 text-3xl md:text-1xl font-bold">{{ $slide->badge_label }}</h3>
                    @endif
                </div>
            </div>
            {{-- <div class="absolute z-10 right-[10%] xl:right-[40%] bottom-1/4 hidden md:block shape-02">
                <svg width="101" height="83" viewBox="0 0 101 83" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M29.1775 77.3654C11.2192 68.7991 -2.66244 48.3121 0.433831 32.2115C3.4785 16.0593 23.6043 4.29344 44.8653 0.990749C66.0748 -2.36354 88.626 2.74531 96.6247 17.143C104.572 31.5922 98.0696 55.3303 83.6719 68.9023C69.3259 82.5259 47.1875 85.8802 29.1775 77.3654Z" fill="{{ $slide->badge_color }}"/>
                </svg>
                <div class="text-center absolute [top:80%] [left:74%] transform z-30 -translate-x-1/2 -translate-y-1/2">
                    <h3 class="font-semibold leading-none text-white text-3xl">-5%</h3>
                    <p class="leading-none text-white mt-1">OFF</p>
                </div>
            </div> --}}
            @endif

            <div class="flex items-center justify-between gap-8 flex-col sm:flex-row">
                <div class="relative z-10 sm:max-w-[632px] w-full slider-content">
                    <div class="flex items-end content-top">
                        <span class="font-bold text-5xl sm:text-7xl xl:text-9xl text-title leading-none dark:text-white">{{ $slide->year_text }}</span>
                        <img class="-ml-5 sm:-ml-10 w-[150px] sm:w-[200px] lg:w-[250px] xl:w-full" src="{{ asset('assets/img/shortcode/carousel/Summer.png') }}" alt="summer">
                    </div>
                    <h1 class="mt-[10px] font-normal text-3xl sm:text-4xl xl:text-5xl !leading-[1.3] dark:text-white">{{ $slide->title }}</h1>
                    @if($slide->description)
                        <p class="dark:text-white-light mt-3 md:mt-4 sm:max-w-[450px] xl:max-w-full">{{ $slide->description }}</p>
                    @endif
                    <div class="mt-6 md:mt-8 flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 sm:gap-4">
                        {{-- Primary CTA --}}
                        <a href="{{ $slide->button_url }}"
                           class="group inline-flex items-center justify-center gap-3 px-7 sm:px-8 py-4
                                  bg-[#bb976d] text-white text-sm sm:text-base font-bold tracking-widest uppercase
                                  hover:bg-[#a8845a] transition-all duration-300
                                  shadow-[0_4px_20px_rgba(187,151,109,0.4)] hover:shadow-[0_6px_28px_rgba(187,151,109,0.55)]
                                  hover:-translate-y-0.5 w-full sm:w-auto">
                            {{ $slide->button_text }}
                            <svg class="flex-none transition-transform duration-300 group-hover:translate-x-1" width="16" height="12" viewBox="0 0 16 12" fill="none">
                                <path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

                        {{-- Track Order --}}
                        <a href="{{ route('track-order') }}"
                           class="group inline-flex items-center justify-center gap-3 px-7 sm:px-8 py-4
                                  bg-white dark:bg-white/10 border-2 border-[#bb976d]/40 dark:border-white/20
                                  text-title dark:text-white text-sm sm:text-base font-semibold
                                  hover:border-[#bb976d] hover:bg-[#fdf6ee] dark:hover:bg-white/15
                                  transition-all duration-300 hover:-translate-y-0.5
                                  shadow-[0_2px_12px_rgba(0,0,0,0.08)] hover:shadow-[0_4px_20px_rgba(187,151,109,0.2)]
                                  w-full sm:w-auto">
                            {{-- Package / tracking icon --}}
                            <span class="flex-none w-9 h-9 rounded-full bg-[#bb976d]/10 group-hover:bg-[#bb976d]/20
                                         flex items-center justify-center transition-colors duration-300">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                                    <polyline points="16.5 9.4 7.55 4.24"/>
                                    <polyline points="3.29 7 12 12 20.71 7"/>
                                    <line x1="12" y1="22" x2="12" y2="12"/>
                                    <circle cx="18.5" cy="15.5" r="2.5"/>
                                    <path d="M20.27 17.27 22 19"/>
                                </svg>
                            </span>
                            <span>
                                <span class="block text-xs text-gray-400 dark:text-white/50 font-normal leading-none mb-0.5">Where's my package?</span>
                                <span class="block font-bold text-title dark:text-white group-hover:text-[#bb976d] transition-colors duration-300">Track Your Order</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="sm:max-w-[750px] w-full">
                    @if ($slide->image)
                        <img class="slider-img" src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title }}">
                    @else
                        <img class="slider-img" src="{{ asset('assets/img/home-v1/banner.png') }}" alt="{{ $slide->title }}">
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    {{-- Fallback: show the original hardcoded slides if no DB slides exist --}}
    <div class="relative pt-12 md:pt-20 xl:pt-[100px] pb-12 sm:pb-20 xl:pb-24 px-[15px] sm:px-12 bg-[#F5F5F5] dark:bg-title">
        <div class="max-w-[1720px] mx-auto">
            <div class="flex items-center justify-between gap-8 flex-col sm:flex-row">
                <div class="relative z-10 sm:max-w-[632px] w-full slider-content">
                    <div class="flex items-end content-top">
                        <span class="font-bold text-4xl sm:text-5xl xl:text-7xl text-title leading-none dark:text-white">2026</span>
                        <img class="-ml-3 sm:-ml-6 w-[110px] sm:w-[145px] lg:w-[175px] xl:w-[210px]" src="{{ asset('assets/img/shortcode/carousel/Summer.png') }}" alt="summer">
                    </div>
                    <h1 class="mt-[8px] font-normal text-2xl sm:text-3xl xl:text-4xl !leading-[1.3] dark:text-white">Buy Furniture, Home Decor &amp; Ceramics Online | PeytonGhalib</h1>
                    <p class="dark:text-white-light mt-3 md:mt-4 sm:max-w-[450px] xl:max-w-full">Discover quality furniture, handpicked home decor, ceramics, and interior design pieces — delivered fast, nationwide.</p>
                    <div class="mt-6 md:mt-8 flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 sm:gap-4">
                        {{-- Primary CTA --}}
                        <a href="{{ url('/shop') }}"
                           class="group inline-flex items-center justify-center gap-3 px-7 sm:px-8 py-4
                                  bg-[#bb976d] text-white text-sm sm:text-base font-bold tracking-widest uppercase
                                  hover:bg-[#a8845a] transition-all duration-300
                                  shadow-[0_4px_20px_rgba(187,151,109,0.4)] hover:shadow-[0_6px_28px_rgba(187,151,109,0.55)]
                                  hover:-translate-y-0.5 w-full sm:w-auto">
                            Shop Now
                            <svg class="flex-none transition-transform duration-300 group-hover:translate-x-1" width="16" height="12" viewBox="0 0 16 12" fill="none">
                                <path d="M1 6H15M15 6L10 1M15 6L10 11" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

                        {{-- Track Order --}}
                        <a href="{{ route('track-order') }}"
                           class="group inline-flex items-center justify-center gap-3 px-7 sm:px-8 py-4
                                  bg-white dark:bg-white/10 border-2 border-[#bb976d]/40 dark:border-white/20
                                  text-title dark:text-white text-sm sm:text-base font-semibold
                                  hover:border-[#bb976d] hover:bg-[#fdf6ee] dark:hover:bg-white/15
                                  transition-all duration-300 hover:-translate-y-0.5
                                  shadow-[0_2px_12px_rgba(0,0,0,0.08)] hover:shadow-[0_4px_20px_rgba(187,151,109,0.2)]
                                  w-full sm:w-auto">
                            {{-- Package / tracking icon --}}
                            <span class="flex-none w-9 h-9 rounded-full bg-[#bb976d]/10 group-hover:bg-[#bb976d]/20
                                         flex items-center justify-center transition-colors duration-300">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                                    <polyline points="16.5 9.4 7.55 4.24"/>
                                    <polyline points="3.29 7 12 12 20.71 7"/>
                                    <line x1="12" y1="22" x2="12" y2="12"/>
                                    <circle cx="18.5" cy="15.5" r="2.5"/>
                                    <path d="M20.27 17.27 22 19"/>
                                </svg>
                            </span>
                            <span>
                                <span class="block text-xs text-gray-400 dark:text-white/50 font-normal leading-none mb-0.5">Where's my package?</span>
                                <span class="block font-bold text-title dark:text-white group-hover:text-[#bb976d] transition-colors duration-300">Track Your Order</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="sm:max-w-[750px] w-full">
                    <img class="slider-img" src="{{ asset('assets/img/home-v1/banner.png') }}" alt="banner-slider">
                </div>
            </div>
        </div>
    </div>
    @endforelse

</div>
<!-- Banner End -->

<!-- Business & Category Clarity Start -->
<section class="bg-white dark:bg-title border-b border-[#E3E5E6] dark:border-white/10">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto py-10 md:py-16">

            <!-- Value Proposition -->
            <div class="text-center mb-8 md:mb-10" data-aos="fade-up">
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Welcome to PeytonGhalib</span>
                <h2 class="mt-2 text-2xl md:text-3xl font-bold text-title dark:text-white leading-tight">
                    Quality Furniture &amp; Home Decor, Delivered to Your Door
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-paragraph dark:text-white-light text-sm md:text-base">
                    From premium sofas and dining sets to handcrafted ceramics and interior accessories — everything you need to make your home beautiful, all in one place.
                </p>
            </div>

            <!-- Core Category Pills — keyword-rich text links to category landing pages -->
            <div class="flex flex-wrap justify-center gap-2 md:gap-3 mb-10 md:mb-12" data-aos="fade-up" data-aos-delay="100">
                @foreach($categories->take(6) as $category)
                <a href="{{ route('category.landing', $category->slug) }}"
                   class="px-4 py-2 text-sm font-medium border border-[#E3E5E6] dark:border-white/20
                          text-title dark:text-white/80 bg-[#FAFAFA] dark:bg-white/5
                          hover:border-primary hover:text-primary hover:bg-[#fdf6ee] dark:hover:bg-white/10
                          transition-all duration-200 rounded-full">
                    Shop {{ $category->name }}
                </a>
                @endforeach
                <a href="{{ url('/shop') }}"
                   class="px-4 py-2 text-sm font-medium border border-primary/40 dark:border-primary/30
                          text-primary bg-[#fdf6ee] dark:bg-primary/10
                          hover:bg-primary hover:text-white hover:border-primary
                          transition-all duration-200 rounded-full">
                    View All Categories &rarr;
                </a>
            </div>

            <!-- Trust & Shopping Advantages -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 md:gap-8" data-aos="fade-up" data-aos-delay="150">

                <!-- Free Delivery -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 text-center sm:text-left">
                    <span class="flex-none w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-semibold text-sm text-title dark:text-white">Free Delivery</h4>
                        <p class="text-xs text-paragraph dark:text-white-light mt-0.5">On orders over $99 nationwide</p>
                    </div>
                </div>

                <!-- Secure Payments -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 text-center sm:text-left">
                    <span class="flex-none w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-semibold text-sm text-title dark:text-white">Secure Payments</h4>
                        <p class="text-xs text-paragraph dark:text-white-light mt-0.5">SSL encrypted &amp; 100% safe checkout</p>
                    </div>
                </div>

                <!-- Easy Returns -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 text-center sm:text-left">
                    <span class="flex-none w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.5"/>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-semibold text-sm text-title dark:text-white">Easy Returns</h4>
                        <p class="text-xs text-paragraph dark:text-white-light mt-0.5">30-day hassle-free return policy</p>
                    </div>
                </div>

                <!-- Quality Guarantee -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 text-center sm:text-left">
                    <span class="flex-none w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#bb976d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-semibold text-sm text-title dark:text-white">Quality Guarantee</h4>
                        <p class="text-xs text-paragraph dark:text-white-light mt-0.5">Curated products, premium materials</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Business & Category Clarity End -->

<!-- Product Category Area Start -->
<div class="s-py-100-50 overflow-hidden">
    <div class="container-fluid">

        <!-- Section Title -->
        <div class="flex items-end justify-between gap-4 mb-8 md:mb-10 max-w-[1720px] mx-auto" data-aos="fade-up">
            <div>
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Explore our range</span>
                <h2 class="leading-tight mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">Featured Categories</h2>
                <p class="mt-1.5 text-sm text-gray-400 dark:text-gray-500 hidden sm:block">Shop furniture, decor, ceramics and more</p>
            </div>
            <a href="{{ url('/categories') }}"
               class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-title dark:text-white
                      border border-current px-5 py-2.5 rounded-full hover:text-primary hover:border-primary
                      duration-300 whitespace-nowrap">
                All Categories
                <svg width="14" height="10" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/>
                </svg>
            </a>
        </div>

        <!-- Slider Wrapper -->
        <div class="max-w-[1720px] mx-auto relative" data-aos="fade-up" data-aos-delay="100">
            <div class="owl-carousel hv1-pdct-ctgry-slider"
                 data-carousel-items="4"
                 data-carousel-xl="4"
                 data-carousel-lg="3"
                 data-carousel-md="3"
                 data-carousel-sm="2"
                 data-carousel-xs="1"
                 data-carousel-margin="16"
                 data-carousel-loop="false"
                 data-carousel-autoplay="false">
                @include('includes.Home.product-category')
            </div>

            <!-- Prev Button -->
            <button class="hv1pdct_prev
                           absolute top-1/2 -translate-y-1/2 -left-3 sm:-left-5 z-[99]
                           w-10 h-10 md:w-12 md:h-12 rounded-full
                           bg-white dark:bg-title shadow-lg border border-gray-100 dark:border-gray-700
                           flex items-center justify-center
                           text-title dark:text-white hover:bg-primary hover:text-white hover:border-primary
                           duration-300"
                    aria-label="Previous">
                <svg width="14" height="12" viewBox="0 0 24 14" fill="none">
                    <path d="M0.18 7.39L5.62 12.83C5.82 13.06 6.16 13.09 6.39 12.89C6.62 12.70 6.65 12.35 6.45 12.12L1.88 7.55L23.43 7.55C23.73 7.55 23.98 7.30 23.98 7.00C23.98 6.70 23.73 6.46 23.43 6.46L1.88 6.46L6.39 1.94C6.62 1.75 6.65 1.40 6.45 1.18C6.26.95 5.91.92 5.68 1.12L0.18 6.62C-0.03 6.83-0.03 7.17 0.18 7.39Z" fill="currentColor"/>
                </svg>
            </button>

            <!-- Next Button -->
            <button class="hv1pdct_next
                           absolute top-1/2 -translate-y-1/2 -right-3 sm:-right-5 z-[99]
                           w-10 h-10 md:w-12 md:h-12 rounded-full
                           bg-white dark:bg-title shadow-lg border border-gray-100 dark:border-gray-700
                           flex items-center justify-center
                           text-title dark:text-white hover:bg-primary hover:text-white hover:border-primary
                           duration-300"
                    aria-label="Next">
                <svg width="14" height="12" viewBox="0 0 24 14" fill="none">
                    <path d="M23.82 6.62L18.38 1.18C18.18.95 17.84.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46L.57 6.46C.27 6.46.02 6.71.02 7.01C.02 7.31.27 7.55.57 7.55L22.12 7.55L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z" fill="currentColor"/>
                </svg>
            </button>
        </div>

    </div>
</div>
<!-- Product Category Area End -->

<!-- New Arrivals Area Start -->
<section class="s-py-50-100">
    <div class="container-fluid">
        <div class="flex items-end justify-between gap-4 mb-8 md:mb-12 max-w-[1720px] mx-auto" data-aos="fade-up">
            <div>
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Just landed</span>
                <h2 class="leading-tight mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">New Arrivals</h2>
                <p class="mt-1.5 text-sm text-gray-400 dark:text-gray-500 hidden sm:block">Fresh pieces added to our collection</p>
            </div>
            <a href="{{ url('/shop') }}"
               class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-title dark:text-white
                      border border-current px-5 py-2.5 rounded-full hover:text-primary hover:border-primary
                      duration-300 whitespace-nowrap">
                View All
                <svg width="14" height="10" viewBox="0 0 24 14" fill="none"><path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/></svg>
            </a>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-8" data-aos="fade-up" data-aos-delay="100">
            @include('includes.Home.new-products')
        </div>
        <div class="text-center mt-8 md:mt-12">
            <a href="{{ url('/shop') }}" class="btn btn-outline" data-text="Shop All New Arrivals">
                <span>Shop All New Arrivals</span>
            </a>
        </div>
    </div>
</section>
<!-- New Arrivals Area End -->

<!-- Why Shop with PeytonGhalib Start -->
<section class="s-py-100 bg-overlay dark:before:bg-title dark:before:bg-opacity-80" style="background-image: url('{{ asset('assets/img/home-v1/bg.png') }}');">
    <img class="absolute top-0 right-0 w-[20%] z-[-1]" src="{{ asset('assets/img/home-v1/shape-01.png') }}" alt="shape">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <div class="max-w-[1186px] ml-auto">
                <div class="mb-8 md:mb-12" data-aos="fade-up">
                    <span class="text-xs uppercase tracking-widest text-primary font-semibold">Our promise</span>
                    <h2 class="leading-tight mt-2 text-2xl md:text-3xl font-bold text-title dark:text-white">Why Shop with PeytonGhalib</h2>
                    <p class="mt-3 max-w-xl text-paragraph dark:text-white-light">We're committed to delivering quality furniture and home decor with service you can count on — from browsing to your doorstep.</p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-[30px]">
                    @include('includes.Home.services')
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why Shop with PeytonGhalib End -->

<!-- Best Selling Products Start -->
<section class="s-py-100-50 bg-[#FAFAF8] dark:bg-dark-secondary">
    <div class="container-fluid">
        <div class="flex items-end justify-between gap-4 mb-8 md:mb-12 max-w-[1720px] mx-auto" data-aos="fade-up">
            <div>
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Customer favourites</span>
                <h2 class="leading-tight mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">Best Selling Products</h2>
                <p class="mt-1.5 text-sm text-gray-400 dark:text-gray-500 hidden sm:block">Most loved pieces by our customers</p>
            </div>
            <a href="{{ url('/shop') }}"
               class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-title dark:text-white
                      border border-current px-5 py-2.5 rounded-full hover:text-primary hover:border-primary
                      duration-300 whitespace-nowrap">
                Shop All
                <svg width="14" height="10" viewBox="0 0 24 14" fill="none"><path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/></svg>
            </a>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-8" data-aos="fade-up" data-aos-delay="100">
            @include('includes.Home.best-sellers')
        </div>
        <div class="text-center mt-8 md:mt-12">
            <a href="{{ url('/shop') }}" class="btn btn-outline" data-text="Shop All Best Sellers">
                <span>Shop All Best Sellers</span>
            </a>
        </div>
    </div>
</section>
<!-- Best Selling Products End -->

<!-- Blog Start -->
{{-- <div class="s-py-50">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <!-- Section Title -->
            <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
                <div> 
                    <svg class="mx-auto w-14 sm:w-24" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M54.1712 13.3447C51.1919 10.3694 47.603 8.07541 43.6517 6.62074C39.7004 5.16606 35.4808 4.58531 31.2834 4.91849C27.583 5.20919 23.9747 6.21786 20.66 7.88816C20.3943 8.02261 20.1928 8.25689 20.0996 8.53966C20.0064 8.82242 20.0291 9.13061 20.1627 9.39668C20.2963 9.66275 20.5299 9.865 20.8124 9.9591C21.0949 10.0532 21.4032 10.0315 21.6696 9.89869C24.7242 8.35948 28.0493 7.42992 31.4592 7.16195C35.6479 6.82393 39.8575 7.47425 43.749 9.06055C47.6404 10.6469 51.105 13.1248 53.8637 16.2949C56.6224 19.4649 58.5981 23.2385 59.6318 27.3117C60.6655 31.3849 60.7282 35.644 59.815 39.7459C58.9018 43.8478 57.0381 47.6779 54.3739 50.9279C51.7098 54.1778 48.3198 56.7568 44.4768 58.457C40.6337 60.1573 36.4452 60.9313 32.2483 60.7169C28.0514 60.5024 23.9636 59.3054 20.3139 57.2223C19.7228 56.8884 19.0678 56.6832 18.3918 56.6203C17.7159 56.5573 17.0343 56.638 16.3917 56.857L7.80458 59.7162L10.6627 51.1324C10.8835 50.4918 10.9652 49.8115 10.9025 49.1368C10.8398 48.4621 10.6341 47.8086 10.2991 47.2196C7.75663 42.7571 6.54397 37.6604 6.80425 32.5311C6.99787 28.7145 8.02183 24.9862 9.8047 21.6061C9.87344 21.4753 9.91576 21.3322 9.92922 21.1851C9.94269 21.0379 9.92704 20.8896 9.88317 20.7485C9.8393 20.6074 9.76807 20.4763 9.67355 20.3628C9.57902 20.2492 9.46306 20.1554 9.33227 20.0866C9.20148 20.0179 9.05843 19.9756 8.91129 19.9621C8.76415 19.9486 8.6158 19.9643 8.47471 20.0081C8.33361 20.052 8.20254 20.1232 8.08898 20.2178C7.97541 20.3123 7.88157 20.4283 7.81283 20.559C5.87824 24.2277 4.76714 28.2741 4.55699 32.4163C4.2749 37.9775 5.59054 43.5033 8.34846 48.3408C8.52452 48.6541 8.63172 49.0014 8.66288 49.3595C8.69405 49.7176 8.64847 50.0782 8.5292 50.4172L4.95962 61.1385C4.89365 61.3366 4.88416 61.5493 4.93218 61.7525C4.98021 61.9558 5.08386 62.1416 5.23154 62.2893C5.37922 62.437 5.56509 62.5407 5.76835 62.5887C5.9716 62.6367 6.18421 62.6272 6.38237 62.5612L17.1069 58.9906C17.4487 58.8722 17.8116 58.8275 18.1718 58.8594C18.5321 58.8913 18.8815 58.9991 19.1971 59.1757C23.5981 61.6894 28.5801 63.0079 33.6483 63.0002C34.1334 63.0002 34.6193 62.9881 35.1062 62.9639C40.7246 62.6746 46.1386 60.7621 50.692 57.4581C55.2455 54.154 58.743 49.6004 60.7608 44.3488C62.7786 39.0972 63.23 33.3732 62.0604 27.8702C60.8909 22.3673 58.1504 17.3216 54.1712 13.3447V13.3447Z" fill="#BB976D"/>
                        <path d="M22.8322 29.2756L29.7565 31.1351C29.9473 31.1863 30.1483 31.1863 30.3391 31.1352C30.5299 31.0841 30.7039 30.9837 30.8436 30.844C30.9833 30.7043 31.0838 30.5303 31.1349 30.3394C31.186 30.1486 31.186 29.9477 31.1348 29.7568L29.2753 22.8315C29.1152 22.2369 28.8019 21.6948 28.3667 21.2593L11.3368 4.22982L11.3359 4.22845L11.3345 4.22755L8.13439 1.02749C7.47535 0.369542 6.58216 0 5.6509 0C4.71965 0 3.82645 0.369542 3.16741 1.02749L1.02717 3.16714H1.02662C0.369089 3.82655 -0.000103318 4.71981 2.16884e-08 5.65103C0.000103362 6.58226 0.369494 7.47544 1.02717 8.13471L21.2595 28.3665C21.6951 28.802 22.2375 29.1155 22.8322 29.2756ZM26.4488 22.5231C26.4147 22.5431 26.3818 22.565 26.3501 22.5886L22.5889 26.3499C22.5652 26.3815 22.5433 26.4145 22.5233 26.4486L6.61474 10.5404L10.5402 6.61488L26.4488 22.5231ZM28.4557 28.456L24.6789 27.4416L27.4415 24.679L28.4557 28.456ZM2.61858 4.75799L4.75822 2.61835C4.99515 2.38196 5.31618 2.2492 5.65087 2.2492C5.98557 2.2492 6.30659 2.38196 6.54353 2.61835L8.94927 5.02409L5.02381 8.94964L2.61804 6.54385C2.38159 6.3068 2.24885 5.98562 2.24895 5.65081C2.24905 5.31599 2.38199 4.9949 2.61858 4.75799Z" fill="#BB976D"/>
                        <path d="M52.0664 36.4375H21.3457C21.0474 36.4375 20.7612 36.556 20.5502 36.767C20.3392 36.978 20.2207 37.2642 20.2207 37.5625C20.2207 37.8609 20.3392 38.1471 20.5502 38.358C20.7612 38.569 21.0474 38.6876 21.3457 38.6876H52.0664C52.3648 38.6876 52.6509 38.569 52.8619 38.358C53.0729 38.1471 53.1914 37.8609 53.1914 37.5625C53.1914 37.2642 53.0729 36.978 52.8619 36.767C52.6509 36.556 52.3648 36.4375 52.0664 36.4375Z" fill="#BB976D"/>
                        <path d="M52.0665 43.9521H30.0489C29.7505 43.9521 29.4643 44.0707 29.2533 44.2817C29.0424 44.4926 28.9238 44.7788 28.9238 45.0772C28.9238 45.3755 29.0424 45.6617 29.2533 45.8727C29.4643 46.0837 29.7505 46.2022 30.0489 46.2022H52.0665C52.3649 46.2022 52.651 46.0837 52.862 45.8727C53.073 45.6617 53.1915 45.3755 53.1915 45.0772C53.1915 44.7788 53.073 44.4926 52.862 44.2817C52.651 44.0707 52.3649 43.9521 52.0665 43.9521Z" fill="#BB976D"/>
                        <path d="M22.1914 45.0766C22.1914 45.3128 22.2614 45.5436 22.3926 45.7399C22.5238 45.9363 22.7102 46.0893 22.9284 46.1797C23.1465 46.27 23.3866 46.2937 23.6182 46.2476C23.8498 46.2015 24.0625 46.0878 24.2295 45.9209C24.3964 45.7539 24.5101 45.5412 24.5562 45.3096C24.6023 45.078 24.5786 44.8379 24.4883 44.6198C24.3979 44.4016 24.2449 44.2152 24.0485 44.084C23.8522 43.9528 23.6214 43.8828 23.3852 43.8828C23.0686 43.8828 22.765 44.0086 22.5411 44.2325C22.3172 44.4564 22.1914 44.76 22.1914 45.0766Z" fill="#BB976D"/>
                    </svg>                                                  
                </div>
                <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Latest Blog</h3>
                <p class="mt-3">Stay informed and inspired with our latest blog posts. Explore insightful content that keeps you ahead of trends and informed on topics you love. </p>
            </div>
            <!-- Blog Wrapper -->
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-5 md:gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                
                <!-- includes/Home/blog.blade.php -->
                @include('includes.Home.blog')

            </div>
        </div>
    </div>
</div> --}}
<!-- Blog End -->


<!-- Customer Reviews Start -->
<div class="s-py-50-100 bg-[#F8F6F3] dark:bg-title">
    <div class="container-fluid">
        <!-- Section Title -->
        <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
            <span class="text-xs uppercase tracking-widest text-primary font-semibold">What our customers say</span>
            <h2 class="leading-tight mt-2 text-2xl md:text-3xl font-bold text-title dark:text-white">Customer Reviews</h2>
            <p class="mt-3 text-paragraph dark:text-white-light">Real stories from real shoppers. See why thousands of customers love shopping with PeytonGhalib.</p>
        </div>

        <!-- Reviews Slider -->
        <div class="max-w-[1720px] mx-auto relative group" data-aos="fade-up" data-aos-delay="100">
            <div class="owl-carousel reviews-slider"
                 data-carousel-items="3"
                 data-carousel-xl="3"
                 data-carousel-lg="2"
                 data-carousel-md="2"
                 data-carousel-sm="1"
                 data-carousel-xs="1"
                 data-carousel-margin="24"
                 data-carousel-loop="true"
                 data-carousel-autoplay="true"
                 data-carousel-dots="true">

                @php
                $reviews = [
                    ['name' => 'Sarah Mitchell',   'rating' => 5, 'product' => 'Nordic Oak Dining Table', 'date' => 'May 2026',   'review' => 'Our new dining table is absolutely stunning. The solid oak finish looks even better in person — rich, warm, and exactly as pictured. Assembly was straightforward and delivery arrived ahead of schedule.'],
                    ['name' => 'James Reynolds',   'rating' => 5, 'product' => 'Velvet Sofa Set',          'date' => 'April 2026', 'review' => 'The velvet sofa arrived beautifully packaged and looks incredible in our living room. Upholstery quality is premium and the frame feels very sturdy. The whole process was seamless from checkout to delivery.'],
                    ['name' => 'Aisha Karimi',     'rating' => 4, 'product' => 'Ceramic Vase Collection',  'date' => 'May 2026',   'review' => 'Ordered the ceramic vase collection as a housewarming gift and the recipient was thrilled. The craftsmanship is beautiful and the packaging was gift-worthy. Delivery took a day longer than expected, but well worth it.'],
                    ['name' => 'Daniel Thompson',  'rating' => 5, 'product' => 'Scandinavian Wardrobe',    'date' => 'March 2026', 'review' => 'I\'ve been shopping here for months and every order has been perfect. The Scandinavian wardrobe is spacious, beautifully finished, and worth every penny. Fast shipping, incredible quality — 10/10!'],
                    ['name' => 'Priya Sharma',     'rating' => 5, 'product' => 'Abstract Wall Art Set',    'date' => 'April 2026', 'review' => 'The wall art set transformed our hallway completely. Colours are vibrant, frames are solid, and they arrived perfectly protected. Exactly what the photos showed — no surprises, just great quality.'],
                    ['name' => 'Michael Barnes',   'rating' => 4, 'product' => 'Bookshelf with Drawers',   'date' => 'May 2026',   'review' => 'Great bookshelf at a very fair price. The drawers glide smoothly and the shelves are deeper than expected — loads of storage. Took about 45 minutes to assemble with clear instructions. Very satisfied overall.'],
                    ['name' => 'Fatima Al-Rashid', 'rating' => 5, 'product' => 'Rattan Accent Chair',      'date' => 'May 2026',   'review' => 'This rattan chair is a showstopper — every guest asks where I got it! The natural woven finish is perfect, the cushion is comfortable, and it has held up beautifully. Exceptional experience from start to finish.'],
                    ['name' => 'Chris Lawrence',   'rating' => 5, 'product' => 'Marble Coffee Table',      'date' => 'April 2026', 'review' => 'The marble coffee table is even more impressive in person. The surface is smooth and cool to the touch, and the brushed gold legs are a perfect contrast. Fast delivery, exactly as described. Will definitely shop here again.'],
                ];
                @endphp

                @foreach($reviews as $review)
                <div class="bg-white dark:bg-[#1a1a2e] rounded-sm p-6 md:p-8 shadow-sm flex flex-col h-full border border-transparent hover:border-primary duration-300">

                    <!-- Stars -->
                    <div class="flex items-center gap-1 mb-4">
                        @for($s = 1; $s <= 5; $s++)
                            @if($s <= $review['rating'])
                                <svg class="w-4 h-4 text-[#F5A623] fill-current" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                            @else
                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                            @endif
                        @endfor
                        <span class="ml-1 text-xs text-gray-400 dark:text-white-light font-medium">{{ $review['rating'] }}.0</span>
                    </div>

                    <!-- Review Text -->
                    <p class="text-paragraph dark:text-white-light text-sm leading-relaxed flex-1">&ldquo;{{ $review['review'] }}&rdquo;</p>

                    <!-- Product Reference -->
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1.5 text-xs bg-[#F5F0EB] dark:bg-primary/10 text-primary px-2.5 py-1 rounded-full font-medium">
                            <svg class="w-3 h-3 fill-current flex-none" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3z"/></svg>
                            {{ $review['product'] }}
                        </span>
                    </div>

                    <!-- Divider -->
                    <div class="my-5 border-t border-[#E3E5E6] dark:border-bdr-clr-drk"></div>

                    <!-- Customer Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-none">
                            <span class="text-primary font-bold text-base leading-none">{{ strtoupper(substr($review['name'], 0, 1)) }}</span>
                        </div>
                        <div>
                            <h5 class="font-semibold text-sm text-title dark:text-white leading-none">{{ $review['name'] }}</h5>
                            <span class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 fill-current flex-none" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Verified Purchase &middot; {{ $review['date'] }}
                            </span>
                        </div>
                        <div class="ml-auto">
                            <svg class="w-7 h-7 text-primary/20 fill-current" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>
                    </div>

                </div>
                @endforeach

            </div>

            <!-- Slider Navigation -->
            <button class="icon reviews_prev w-9 h-9 md:w-12 md:h-12 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary hover:text-white transform p-2 absolute top-1/2 -translate-y-1/2 -left-4 md:-left-6 z-[999] shadow-md" aria-label="Previous Review">
                <svg class="fill-current" width="18" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z"/>
                </svg>
            </button>
            <button class="icon reviews_next w-9 h-9 md:w-12 md:h-12 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary hover:text-white transform p-2 absolute top-1/2 -translate-y-1/2 -right-4 md:-right-6 z-[999] shadow-md" aria-label="Next Review">
                <svg class="fill-current" width="18" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z"/>
                </svg>
            </button>
        </div>

    </div>
</div>
<!-- Customer Reviews End -->

<!-- Internal Linking — Explore Our Range Start -->
<section class="bg-white dark:bg-title border-t border-[#E3E5E6] dark:border-white/10">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto py-10 md:py-14">

            <div class="flex items-end justify-between gap-4 mb-8" data-aos="fade-up">
                <div>
                    <span class="text-xs uppercase tracking-widest text-primary font-semibold">Everything in one place</span>
                    <h2 class="leading-tight mt-1 text-xl md:text-2xl font-bold text-title dark:text-white">Explore Our Full Range</h2>
                </div>
                <a href="{{ url('/shop') }}"
                   class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline whitespace-nowrap">
                    Shop All Products
                    <svg width="12" height="8" viewBox="0 0 24 14" fill="none" class="fill-current"><path d="M23.82 6.62L18.38 1.18C18.18 0.95 17.84 0.92 17.61 1.12C17.38 1.31 17.35 1.66 17.55 1.88L22.12 6.46H0.57C0.27 6.46 0.02 6.71 0.02 7.01C0.02 7.31 0.27 7.55 0.57 7.55H22.12L17.61 12.06C17.38 12.26 17.35 12.60 17.55 12.83C17.74 13.06 18.09 13.09 18.32 12.89L23.82 7.39C24.03 7.17 24.03 6.83 23.82 6.62Z"/></svg>
                </a>
            </div>

            {{-- All DB categories as keyword-rich text links --}}
            @if($categories->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-10" data-aos="fade-up" data-aos-delay="50">
                @php $verbs = ['Shop','Browse','Explore','Discover','Shop','Browse','Explore','Discover','Shop','Browse','Explore','Discover']; @endphp
                @foreach($categories as $idx => $category)
                <a href="{{ route('category.landing', $category->slug) }}"
                   class="flex items-center justify-between py-3 border-b border-[#F0EDE8] dark:border-white/5 group">
                    <span class="text-sm font-medium text-paragraph dark:text-white-light group-hover:text-primary transition-colors duration-200">
                        {{ $verbs[$idx % 12] }} {{ $category->name }} Online
                        @if($category->products_count > 0)
                            <span class="text-xs text-gray-400 font-normal ml-1">({{ $category->products_count }})</span>
                        @endif
                    </span>
                    <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-primary flex-none transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Curated room & style keyword pill links --}}
            <div class="mt-8 pt-6 border-t border-[#F0EDE8] dark:border-white/5" data-aos="fade-up" data-aos-delay="100">
                <p class="text-xs uppercase tracking-widest text-gray-400 font-semibold mb-3">Popular searches</p>
                <div class="flex flex-wrap gap-2">
                    @php
                    $popularLinks = [
                        'Shop Living Room Furniture',
                        'Browse Bedroom Furniture Sets',
                        'Explore Dining Tables &amp; Chairs',
                        'View Sofas &amp; Armchairs',
                        'Shop Ceramic Home Decor',
                        'Browse Wall Art &amp; Mirrors',
                        'View New Arrivals',
                        'Shop Best Selling Products',
                        'Browse Home Accessories',
                        'Explore Storage &amp; Shelving',
                    ];
                    @endphp
                    @foreach($popularLinks as $label)
                    <a href="{{ url('/shop') }}"
                       class="text-xs px-3 py-1.5 border border-[#E3E5E6] dark:border-white/15
                              text-paragraph dark:text-white-light
                              hover:border-primary hover:text-primary
                              transition-all duration-200 rounded-full whitespace-nowrap">
                        {!! $label !!}
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Internal Linking — Explore Our Range End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')

@include('includes.footer')

@push('scripts')
<script>
    $(document).ready(function () {
        var reviewsSlider = $('.reviews-slider');
        $('.reviews_next').on('click', function () {
            reviewsSlider.trigger('next.owl.carousel');
        });
        $('.reviews_prev').on('click', function () {
            reviewsSlider.trigger('prev.owl.carousel', [300]);
        });
    });
</script>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    var $cat = $('.hv1-pdct-ctgry-slider');
    if (!$cat.length) return;

    // Destroy the generic init so we can reinitialize with rewind
    $cat.trigger('destroy.owl.carousel').removeClass('owl-loaded owl-drag');
    $cat.find('.owl-stage-outer').children().unwrap();

    $cat.owlCarousel({
        items       : 4,
        margin      : 16,
        loop        : false,
        rewind      : true,
        autoplay    : false,
        smartSpeed  : 500,
        mouseDrag   : true,
        touchDrag   : true,
        responsive  : {
            0   : { items: 1 },
            576 : { items: 2 },
            768 : { items: 3 },
            1024: { items: 4 },
        }
    });

    // Wire nav buttons
    $('.hv1pdct_next').off('click').on('click', function () {
        $cat.trigger('next.owl.carousel');
    });
    $('.hv1pdct_prev').off('click').on('click', function () {
        $cat.trigger('prev.owl.carousel');
    });
});
</script>
@endpush

@endsection

@push('scripts')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "PeytonGhalib",
  "url": "https://peytonghalib.com",
  "logo": "https://peytonghalib.com/assets/img/logo.svg",
  "email": "support@@peytonghalib.com",
  "description": "PeytonGhalib — Your one-stop online destination for quality furniture, home decor, ceramics, and more at unbeatable prices with fast delivery.",
  "sameAs": [
    "https://www.facebook.com/peytonghalib",
    "https://twitter.com/peytonghalib",
    "https://www.instagram.com/peytonghalib",
    "https://www.linkedin.com/company/peytonghalib"
  ]
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebSite",
  "name": "PeytonGhalib",
  "url": "https://peytonghalib.com",
  "potentialAction": {
    "@@type": "SearchAction",
    "target": "https://peytonghalib.com/shop?search={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "LocalBusiness",
  "name": "PeytonGhalib",
  "url": "https://peytonghalib.com",
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "4.9",
    "reviewCount": "2134",
    "bestRating": "5",
    "worstRating": "1"
  },
  "review": [
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Sarah Mitchell"},
      "datePublished": "2026-05-10",
      "name": "Stunning Nordic Oak Dining Table",
      "reviewBody": "Our new dining table is absolutely stunning. The solid oak finish looks even better in person — rich, warm, and exactly as pictured. Assembly was straightforward and delivery arrived ahead of schedule.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "James Reynolds"},
      "datePublished": "2026-04-22",
      "name": "Premium quality Velvet Sofa Set",
      "reviewBody": "The velvet sofa arrived beautifully packaged and looks incredible in our living room. Upholstery quality is premium and the frame feels very sturdy. The whole process was seamless from checkout to delivery.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Aisha Karimi"},
      "datePublished": "2026-05-03",
      "name": "Beautiful Ceramic Vase Collection — perfect gift",
      "reviewBody": "Ordered the ceramic vase collection as a housewarming gift and the recipient was thrilled. The craftsmanship is beautiful and the packaging was gift-worthy. Delivery took a day longer than expected, but well worth it.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "4", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Daniel Thompson"},
      "datePublished": "2026-03-15",
      "name": "Spacious and beautifully finished Scandinavian Wardrobe",
      "reviewBody": "I've been shopping here for months and every order has been perfect. The Scandinavian wardrobe is spacious, beautifully finished, and worth every penny. Fast shipping, incredible quality.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Priya Sharma"},
      "datePublished": "2026-04-18",
      "name": "Abstract Wall Art Set transformed our hallway",
      "reviewBody": "The wall art set transformed our hallway completely. Colours are vibrant, frames are solid, and they arrived perfectly protected. Exactly what the photos showed — no surprises, just great quality.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Michael Barnes"},
      "datePublished": "2026-05-07",
      "name": "Great Bookshelf with Drawers at a fair price",
      "reviewBody": "Great bookshelf at a very fair price. The drawers glide smoothly and the shelves are deeper than expected — loads of storage. Took about 45 minutes to assemble with clear instructions. Very satisfied overall.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "4", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Fatima Al-Rashid"},
      "datePublished": "2026-05-14",
      "name": "Rattan Accent Chair — a showstopper",
      "reviewBody": "This rattan chair is a showstopper — every guest asks where I got it! The natural woven finish is perfect, the cushion is comfortable, and it has held up beautifully. Exceptional experience from start to finish.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    },
    {
      "@@type": "Review",
      "author": {"@@type": "Person", "name": "Chris Lawrence"},
      "datePublished": "2026-04-29",
      "name": "Impressive Marble Coffee Table — worth every penny",
      "reviewBody": "The marble coffee table is even more impressive in person. The surface is smooth and cool to the touch, and the brushed gold legs are a perfect contrast. Fast delivery, exactly as described.",
      "reviewRating": {"@@type": "Rating", "ratingValue": "5", "bestRating": "5"}
    }
  ]
}
</script>
@endpush
