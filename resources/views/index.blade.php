<!-- resources/views/index.blade.php -->
@extends('layouts.main')

@section('title', 'Home')

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
                    <h2 class="mt-[10px] font-normal text-3xl sm:text-4xl xl:text-5xl !leading-[1.3] dark:text-white">{{ $slide->title }}</h2>
                    @if($slide->description)
                        <p class="dark:text-white-light mt-3 md:mt-4 sm:max-w-[450px] xl:max-w-full">{{ $slide->description }}</p>
                    @endif
                    <div class="button mt-4 md:mt-6">
                        <a class="btn btn-outline" href="{{ $slide->button_url }}" data-text="{{ $slide->button_text }}"><span>{{ $slide->button_text }}</span></a>
                    </div>
                </div>
                <div class="sm:max-w-[750px] w-full">
                    @if ($slide->image)
                        <img class="slider-img" src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title }}">
                    @else
                        <img class="slider-img" src="{{ asset('assets/img/home-v1/banner-01.png') }}" alt="{{ $slide->title }}">
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
                        <span class="font-bold text-5xl sm:text-7xl xl:text-9xl text-title leading-none dark:text-white">2026</span>
                        <img class="-ml-5 sm:-ml-10 w-[150px] sm:w-[200px] lg:w-[250px] xl:w-full" src="{{ asset('assets/img/shortcode/carousel/Summer.png') }}" alt="summer">
                    </div>
                    <h2 class="mt-[10px] font-normal text-3xl sm:text-4xl xl:text-5xl !leading-[1.3] dark:text-white">Brand-New Arrival Alert Your Next Favorite is Here!</h2>
                    <p class="dark:text-white-light mt-3 md:mt-4 sm:max-w-[450px] xl:max-w-full">Discover the latest must-have arrivals! Elevate your style with our newest collection of trendsetting items.</p>
                    <div class="button mt-4 md:mt-6">
                        <a class="btn btn-outline" href="{{ url('/shop-v1') }}" data-text="Shop Now"><span>Shop Now</span></a>
                    </div>
                </div>
                <div class="sm:max-w-[750px] w-full">
                    <img class="slider-img" src="{{ asset('assets/img/home-v1/banner-01.png') }}" alt="banner-slider">
                </div>
            </div>
        </div>
    </div>
    @endforelse

</div>
<!-- Banner End -->

<!-- Product Category Area Start -->
<div class="s-py-100-50 overflow-hidden">
    <div class="container-fluid">
        <!-- Section Title -->
        <div class="flex items-end justify-between gap-4 mb-6 md:mb-8 max-w-[1720px] mx-auto" data-aos="fade-up">
            <div>
                <span class="text-xs uppercase tracking-widest text-primary font-semibold">Browse by</span>
                <h3 class="leading-tight mt-1 text-2xl md:text-3xl font-bold text-title dark:text-white">Shop by Category</h3>
            </div>
            <a href="{{ url('/shop-v1') }}" class="hidden sm:flex items-center gap-2 text-sm font-medium text-title dark:text-white hover:text-primary duration-300 whitespace-nowrap">
                View All
                <svg width="16" height="10" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="currentColor"/>
                </svg>
            </a>
        </div>
        <!-- Products Wrapper -->
        <div class="max-w-[1720px] mx-auto relative group" data-aos="fade-up" data-aos-delay="100">
            <div class="owl-carousel hv1-pdct-ctgry-slider" data-carousel-items="3" data-carousel-xl="3" data-carousel-lg="2" data-carousel-md="2" data-carousel-sm="2" data-carousel-xs="1" data-carousel-margin="10" data-carousel-loop="true" data-carousel-autoplay="true"> 
                
                <!-- includes/Home/product-category.blade.php -->
                @include('includes.Home.product-category')

            </div>
            
            <!-- Slider Navigation -->
            <button class="icon hv1pdct_prev w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary transform p-2 absolute [top:57%]  -translate-y-1/2 left-0 z-[999]" aria-label="Prev Navigation">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z"/>
                </svg>
            </button>
            <button class="icon hv1pdct_next w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary transform p-2 absolute [top:57%] -translate-y-1/2 right-0 z-[999]" aria-label="Next Navigation">
                <svg class="fill-current" width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z"/>
                </svg>
            </button>
            
        </div>
    </div>
</div>
<!-- Product Category Area End -->

<!-- New Product Area Start -->
<div class="s-py-50-100">
    <div class="container-fluid">
        <!-- Section Title -->
        <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
            <div>  
                <svg class="mx-auto w-14 sm:w-24" viewBox="0 0 73 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.7435 61.797C13.7435 62.4613 13.2025 62.9998 12.5353 62.9998H1.20822C0.54098 62.9998 0 62.4613 0 61.797C0 61.1327 0.54098 60.5941 1.20822 60.5941H12.5353C13.2025 60.5941 13.7435 61.1327 13.7435 61.797ZM28.0911 8.72061C32.7545 8.72061 36.5486 12.4979 36.5486 17.1407V19.2457C36.5486 19.91 36.0076 20.4485 35.3404 20.4485H20.8418C20.1745 20.4485 19.6336 19.91 19.6336 19.2457V17.1407C19.6336 12.4979 23.4277 8.72061 28.0911 8.72061ZM22.05 17.1407V18.0428H34.1322V17.1407C34.1322 13.8244 31.4222 11.1263 28.0911 11.1263C24.76 11.1263 22.05 13.8244 22.05 17.1407ZM10.0433 58.1884C10.7106 58.1884 11.2524 57.6497 11.2515 56.9839L11.1881 9.97069C11.1825 5.79104 14.5782 2.40558 18.7768 2.40558H19.2944C21.7168 2.40558 23.9371 3.51672 25.3857 5.45409C25.7842 5.9868 26.5411 6.09732 27.0763 5.70067C27.6116 5.30403 27.7224 4.55043 27.324 4.01757C25.4428 1.50193 22.441 0 19.2944 0H18.7768C13.2418 0 8.76427 4.46308 8.77167 9.974L8.8351 56.9872C8.83601 57.6509 9.37669 58.1884 10.0433 58.1884V58.1884ZM67.0562 41.7994V33.9562C67.0562 30.4048 64.1539 27.5154 60.5866 27.5154H27.6134C24.0461 27.5154 21.1438 30.4048 21.1438 33.9562V36.8376C21.1438 37.5018 21.6848 38.0404 22.3521 38.0404C23.0193 38.0404 23.5603 37.5018 23.5603 36.8376V33.9562C23.5603 31.7312 25.3785 29.9211 27.6134 29.9211H43.0428V43.4533C43.0428 44.1176 43.5838 44.6562 44.251 44.6562C44.9183 44.6562 45.4592 44.1176 45.4592 43.4533V29.9211H60.5866C62.8215 29.9211 64.6397 31.7312 64.6397 33.9562V41.8312C61.9265 42.1223 59.8068 44.4153 59.8068 47.1927V48.2648C59.8068 48.929 60.3478 49.4676 61.0151 49.4676C61.6823 49.4676 62.2233 48.929 62.2233 48.2648V47.1927C62.2233 45.5454 63.5694 44.2051 65.224 44.2051H67.076C68.7306 44.2051 70.0767 45.5454 70.0767 47.1927V57.6067C70.0767 59.254 68.7306 60.5943 67.076 60.5943H62.2233C62.2233 52.7215 62.2292 53.206 62.2129 53.0764C62.305 52.3419 61.7281 51.7231 61.0151 51.7231H33.9812C33.3139 51.7231 32.7729 52.2617 32.7729 52.926C32.7729 53.5903 33.3139 54.1289 33.9812 54.1289H59.8068V61.7971C59.8068 62.4614 60.3478 63 61.0151 63H67.076C70.063 63 72.4932 60.5806 72.4932 57.6067V47.1927C72.4932 44.2577 70.1101 41.7994 67.0562 41.7994ZM54.8229 60.5941C53.7304 60.5941 23.1422 60.5941 21.4263 60.5941C19.7716 60.5941 18.4253 59.2538 18.4253 57.6065V47.1927C18.4253 45.5454 19.7716 44.2051 21.4263 44.2051H23.278C24.9327 44.2051 26.2789 45.5454 26.2789 47.1927V53.9784C26.2789 54.6426 26.8199 55.1812 27.4871 55.1812C28.1544 55.1812 28.6954 54.6426 28.6954 53.9784V47.1927C28.6954 44.2188 26.2652 41.7994 23.278 41.7994H21.4263C18.4391 41.7994 16.0089 44.2188 16.0089 47.1927V57.6067C16.0089 60.5806 18.4391 63 21.4263 63H54.8229C55.4902 63 56.0312 62.4614 56.0312 61.7971C56.0312 61.1329 55.4902 60.5941 54.8229 60.5941Z" fill="#BB976D"/>
                </svg>                                             
            </div>
            <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">New Products</h3>
            <p class="mt-3">Be the first to experience innovation with our latest arrivals. Stay ahead of the curve and discover what's new in style, technology, and more. </p>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-8" data-aos="fade-up" data-aos-delay="100">
            
            <!-- includes/Home/new-products.blade.php -->
            @include('includes.Home.new-products')

        </div>
        <div class="text-center mt-7 md:mt-12">
            <a href="{{ url('/shop-v1') }}" class="btn btn-outline" data-text="All Products">
                <span>All Products</span>
            </a>
        </div>
    </div>
</div>
<!-- New Product Area End -->

<!-- Choose Area Start -->
<div class="s-py-100 bg-overlay dark:before:bg-title dark:before:bg-opacity-80" style="background-image: url('{{ asset('assets/img/home-v1/bg.png') }}');">
    <img class="absolute top-0 right-0 w-[20%] z-[-1]" src="{{ asset('assets/img/home-v1/shape-01.png') }}" alt="shape">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <div class="max-w-[1186px] ml-auto">
                <!-- Section Title -->
                <div class="max-w-xl mb-8 md:mb-12" data-aos="fade-up">
                    <div> 
                        <svg class="w-14 sm:w-24" viewBox="0 0 64 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25.7294 12.8432L24.6844 18.9346C24.6552 19.1051 24.6744 19.2804 24.7396 19.4406C24.8048 19.6008 24.9136 19.7395 25.0535 19.8412C25.1935 19.9428 25.3591 20.0033 25.5316 20.0158C25.7041 20.0283 25.8767 19.9922 26.0298 19.9118L31.5002 17.0359L36.97 19.9118C37.1231 19.9922 37.2957 20.0283 37.4682 20.0158C37.6408 20.0033 37.8064 19.9428 37.9463 19.8412C38.0863 19.7395 38.195 19.6008 38.2602 19.4406C38.3255 19.2804 38.3446 19.1051 38.3155 18.9346L37.271 12.8432L41.6969 8.52966C41.8207 8.40881 41.9083 8.25572 41.9498 8.0877C41.9912 7.91968 41.9849 7.74341 41.9314 7.57882C41.8779 7.41423 41.7795 7.26786 41.6472 7.15627C41.5149 7.04468 41.3541 6.97231 41.1828 6.94733L35.0665 6.05918L32.3316 0.517299C32.2552 0.362046 32.1367 0.231305 31.9898 0.13988C31.8429 0.0484557 31.6732 0 31.5002 0C31.3271 0 31.1575 0.0484557 31.0106 0.13988C30.8636 0.231305 30.7452 0.362046 30.6687 0.517299L27.9333 6.05918L21.8175 6.94733C21.6463 6.9722 21.4854 7.04449 21.3531 7.15602C21.2208 7.26755 21.1223 7.41388 21.0688 7.57846C21.0154 7.74303 21.009 7.91929 21.0505 8.08729C21.0919 8.2553 21.1796 8.40835 21.3035 8.52913L25.7294 12.8432ZM28.6826 7.82434C28.8313 7.8026 28.9725 7.74509 29.094 7.65676C29.2156 7.56843 29.3139 7.45191 29.3805 7.31721L31.5002 3.02487L33.6199 7.31721C33.6865 7.45191 33.7848 7.56843 33.9063 7.65676C34.0279 7.74509 34.1691 7.8026 34.3178 7.82434L39.0573 8.51323L35.6277 11.8554C35.5203 11.9605 35.4401 12.0902 35.3939 12.2331C35.3478 12.3761 35.3371 12.5282 35.3628 12.6763L36.1719 17.3957L31.9326 15.1701C31.7995 15.1001 31.6514 15.0636 31.501 15.0636C31.3506 15.0636 31.2025 15.1001 31.0694 15.1701L26.83 17.3957L27.6397 12.6763C27.6653 12.5282 27.6545 12.3762 27.6084 12.2332C27.5622 12.0902 27.4821 11.9606 27.3748 11.8554L23.9457 8.51323L28.6826 7.82434Z" fill="#BB976D"/>
                            <path d="M62.9545 9.72055C62.901 9.55607 62.8026 9.40981 62.6704 9.29829C62.5382 9.18677 62.3774 9.11443 62.2063 9.08942L57.349 8.38357L55.1764 3.98525C55.0997 3.83018 54.9812 3.69965 54.8342 3.60838C54.6872 3.51711 54.5177 3.46875 54.3447 3.46875C54.1717 3.46875 54.0021 3.51711 53.8552 3.60838C53.7082 3.69965 53.5897 3.83018 53.513 3.98525L51.3403 8.38622L46.4831 9.09207C46.3119 9.11702 46.1511 9.18937 46.0188 9.30094C45.8866 9.41251 45.7882 9.55884 45.7348 9.7234C45.6813 9.88796 45.675 10.0642 45.7165 10.2322C45.758 10.4001 45.8457 10.5531 45.9696 10.6739L49.484 14.0998L48.6547 18.9374C48.6255 19.1079 48.6447 19.2832 48.7099 19.4434C48.7751 19.6036 48.8839 19.7424 49.0238 19.844C49.1638 19.9457 49.3294 20.0061 49.5019 20.0186C49.6744 20.0311 49.847 19.9951 50.0001 19.9146L54.3455 17.6306L58.6908 19.9146C58.8439 19.9951 59.0165 20.0311 59.189 20.0186C59.3616 20.0061 59.5272 19.9457 59.6671 19.844C59.8071 19.7424 59.9158 19.6036 59.981 19.4434C60.0463 19.2832 60.0654 19.1079 60.0363 18.9374L59.2064 14.0998L62.7214 10.6739C62.8456 10.5527 62.9333 10.3991 62.9745 10.2306C63.0157 10.0621 63.0088 9.88535 62.9545 9.72055ZM57.5621 13.112C57.4547 13.2171 57.3744 13.3468 57.3283 13.4898C57.2821 13.6327 57.2714 13.7848 57.2971 13.9329L57.8912 17.3985L54.7774 15.7616C54.6442 15.6917 54.4961 15.6551 54.3457 15.6551C54.1954 15.6551 54.0472 15.6917 53.9141 15.7616L50.8014 17.398L51.3959 13.9323C51.4216 13.7843 51.4109 13.6322 51.3648 13.4892C51.3186 13.3462 51.2384 13.2166 51.131 13.1115L48.6123 10.6569L52.0923 10.1514C52.241 10.1297 52.3823 10.0722 52.504 9.98391C52.6256 9.89557 52.724 9.77901 52.7907 9.64425L54.3471 6.4907L55.9034 9.64425C55.97 9.77895 56.0683 9.89546 56.1899 9.9838C56.3115 10.0721 56.4526 10.1296 56.6013 10.1514L60.0818 10.6569L57.5621 13.112Z" fill="#BB976D"/>
                            <path d="M13.4299 20.0176C13.565 20.0177 13.6984 19.9883 13.8209 19.9314C13.9434 19.8745 14.0519 19.7916 14.139 19.6884C14.2261 19.5851 14.2895 19.4641 14.325 19.3338C14.3604 19.2035 14.3669 19.067 14.344 18.9339L13.5147 14.0963L17.0296 10.6704C17.1536 10.5496 17.2412 10.3965 17.2827 10.2285C17.3242 10.0605 17.3178 9.88428 17.2643 9.71971C17.2108 9.55513 17.1124 9.4088 16.9801 9.29727C16.8477 9.18573 16.6869 9.11344 16.5156 9.08858L11.6584 8.38272L9.48573 3.9844C9.40912 3.82932 9.29065 3.69875 9.14373 3.60745C8.9968 3.51615 8.82727 3.46777 8.65429 3.46777C8.48131 3.46777 8.31177 3.51615 8.16485 3.60745C8.01792 3.69875 7.89946 3.82932 7.82285 3.9844L5.65018 8.38537L0.794542 9.08858C0.623248 9.11336 0.462309 9.18559 0.329933 9.29709C0.197556 9.4086 0.0990246 9.55492 0.045485 9.71951C-0.00805467 9.88409 -0.0144656 10.0604 0.0269773 10.2284C0.0684203 10.3965 0.156063 10.5496 0.279991 10.6704L3.79494 14.0963L2.96509 18.9339C2.93594 19.1044 2.95507 19.2797 3.0203 19.4399C3.08553 19.6001 3.19427 19.7389 3.33423 19.8405C3.47419 19.9422 3.63979 20.0026 3.81232 20.0151C3.98485 20.0276 4.15742 19.9916 4.31055 19.9111L8.65588 17.6271L13.0012 19.9111C13.1334 19.9807 13.2805 20.0173 13.4299 20.0176ZM9.08458 15.7608C8.95146 15.6908 8.80334 15.6543 8.65296 15.6543C8.50259 15.6543 8.35446 15.6908 8.22134 15.7608L5.11019 17.395L5.70476 13.9294C5.73031 13.7813 5.71954 13.6293 5.67339 13.4863C5.62724 13.3433 5.54709 13.2137 5.4398 13.1085L2.92163 10.654L6.40107 10.1484C6.54988 10.1268 6.69122 10.0694 6.81289 9.98106C6.93456 9.89271 7.03293 9.7761 7.09951 9.64128L8.65482 6.48721L10.2112 9.64075C10.2778 9.77557 10.3761 9.89218 10.4978 9.98053C10.6195 10.0689 10.7608 10.1263 10.9096 10.1479L14.3891 10.6534L11.8709 13.1112C11.7635 13.2163 11.6832 13.3459 11.637 13.4889C11.5909 13.6319 11.5802 13.784 11.6059 13.932L12.2 17.3977L9.08458 15.7608Z" fill="#BB976D"/>
                            <path d="M50.9001 42.2896C50.8989 41.2945 50.5031 40.3404 49.7995 39.6368C49.0958 38.9331 48.1418 38.5373 47.1466 38.5362H35.7099V26.3322C35.7099 25.2159 35.2665 24.1452 34.4771 23.3559C33.6877 22.5665 32.6171 22.123 31.5008 22.123C30.3845 22.123 29.3139 22.5665 28.5245 23.3559C27.7351 24.1452 27.2917 25.2159 27.2917 26.3322V28.6585C27.2945 29.9931 26.869 31.2933 26.0776 32.368L21.5527 38.5362H13.0289C12.783 38.5362 12.5471 38.6339 12.3732 38.8078C12.1993 38.9817 12.1016 39.2176 12.1016 39.4636V62.0725C12.1016 62.3185 12.1993 62.5543 12.3732 62.7283C12.5471 62.9022 12.783 62.9999 13.0289 62.9999H45.5569C46.2926 63.0002 47.0122 62.7843 47.6262 62.3788C48.2401 61.9734 48.7213 61.3964 49.0099 60.7196C49.2984 60.0429 49.3817 59.2962 49.2492 58.5725C49.1167 57.8488 48.7744 57.18 48.2648 56.6493C48.7037 56.337 49.0702 55.9337 49.3392 55.467C49.6082 55.0002 49.7735 54.481 49.8238 53.9446C49.8741 53.4082 49.8082 52.8673 49.6306 52.3587C49.453 51.8501 49.1679 51.3857 48.7947 50.9972C49.2336 50.6849 49.6001 50.2816 49.8691 49.8149C50.1382 49.3481 50.3035 48.8289 50.3538 48.2925C50.404 47.7561 50.3381 47.2152 50.1605 46.7066C49.983 46.198 49.6978 45.7336 49.3246 45.3451C49.8118 44.9983 50.2089 44.54 50.483 44.0085C50.7571 43.477 50.9 42.8876 50.9001 42.2896ZM13.9563 40.3909H21.0953V61.1452H13.9563V40.3909ZM47.1466 44.1883H46.6167C46.3708 44.1883 46.1349 44.286 45.961 44.4599C45.7871 44.6338 45.6894 44.8697 45.6894 45.1157C45.6894 45.3616 45.7871 45.5975 45.961 45.7714C46.1349 45.9453 46.3708 46.043 46.6167 46.043C47.1149 46.0513 47.5898 46.2551 47.9391 46.6103C48.2885 46.9655 48.4842 47.4438 48.4842 47.942C48.4842 48.4402 48.2885 48.9185 47.9391 49.2737C47.5898 49.6289 47.1149 49.8326 46.6167 49.8409H46.0868C45.8409 49.8409 45.605 49.9387 45.4311 50.1126C45.2571 50.2865 45.1594 50.5224 45.1594 50.7683C45.1594 51.0143 45.2571 51.2501 45.4311 51.424C45.605 51.598 45.8409 51.6957 46.0868 51.6957C46.5904 51.6957 47.0733 51.8957 47.4294 52.2518C47.7855 52.6078 47.9855 53.0908 47.9855 53.5944C47.9855 54.0979 47.7855 54.5809 47.4294 54.9369C47.0733 55.293 46.5904 55.4931 46.0868 55.4931H45.5569C45.3109 55.4931 45.0751 55.5908 44.9011 55.7647C44.7272 55.9386 44.6295 56.1745 44.6295 56.4204C44.6295 56.6664 44.7272 56.9022 44.9011 57.0761C45.0751 57.2501 45.3109 57.3478 45.5569 57.3478C46.0605 57.3478 46.5434 57.5478 46.8995 57.9039C47.2555 58.26 47.4556 58.7429 47.4556 59.2465C47.4556 59.75 47.2555 60.233 46.8995 60.589C46.5434 60.9451 46.0605 61.1452 45.5569 61.1452H22.95V39.7667L27.5731 33.4633C28.5984 32.0712 29.1498 30.3869 29.1464 28.658V26.3317C29.1464 25.7072 29.3944 25.1084 29.836 24.6668C30.2775 24.2253 30.8764 23.9772 31.5008 23.9772C32.1252 23.9772 32.7241 24.2253 33.1656 24.6668C33.6072 25.1084 33.8552 25.7072 33.8552 26.3317V39.463C33.8552 39.709 33.9529 39.9449 34.1269 40.1188C34.3008 40.2927 34.5366 40.3904 34.7826 40.3904H47.1466C47.6502 40.3904 48.1331 40.5904 48.4892 40.9465C48.8453 41.3026 49.0453 41.7855 49.0453 42.2891C49.0453 42.7926 48.8453 43.2756 48.4892 43.6317C48.1331 43.9877 47.6502 44.1878 47.1466 44.1878V44.1883Z" fill="#BB976D"/>
                        </svg>                                                      
                    </div>
                    <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Why you Choose Us</h3>
                    <p class="mt-3">Choose us for unparalleled quality, exceptional service, and a commitment to your satisfaction. Join countless others who rely on us for reliability. </p>
                </div>
                <!-- Chose Wrapper -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-[30px]">

                    <!-- includes/Home/services.blade.php -->
                    @include('includes.Home.services')

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Choose Area End -->

<!-- Feature Post Start -->
<div class="s-py-100-50">
    <div class="container-fluid">
        <!-- Section Title -->
        <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
            <div>
                <svg class="mx-auto w-14 sm:w-24" viewBox="0 0 76 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M71.749 29.234V12.4939C71.749 10.1443 69.8384 8.23257 67.4896 8.23257H60.3281C57.3673 8.23257 54.4966 7.17838 52.245 5.26413C48.2516 1.8695 43.1632 0 37.9167 0C32.6701 0 27.5817 1.8695 23.5883 5.26413C21.3367 7.17838 18.466 8.23257 15.5053 8.23257H8.34374C5.99513 8.23257 4.08434 10.1442 4.08434 12.4939V29.234C1.61146 31.0362 0 33.9535 0 37.2419V60.3639C0 61.8175 1.18193 63 2.63476 63H5.16674C6.61972 63 7.80165 61.8175 7.80165 60.3639V58.2235C7.80165 57.0665 8.74246 56.1254 9.89877 56.1254H65.9349C67.0912 56.1254 68.0317 57.0667 68.0317 58.2234V60.3639C68.0317 61.8175 69.2136 63 70.6666 63H73.1986C74.6514 63 75.8333 61.8175 75.8333 60.3639V37.2419C75.8333 33.9535 74.2219 31.0362 71.749 29.234ZM6.30602 12.4939C6.30602 11.3698 7.22016 10.4553 8.34374 10.4553H15.5053C18.9925 10.4553 22.3741 9.21326 25.0269 6.95803C28.6191 3.90438 33.1966 2.22278 37.9167 2.22278C42.6367 2.22278 47.2143 3.90453 50.8064 6.95818C53.4592 9.21341 56.8408 10.4555 60.3281 10.4555H67.4896C68.6132 10.4555 69.5273 11.3699 69.5273 12.4941V28.0165C68.4128 27.5805 67.2018 27.3384 65.9347 27.3384H62.1352V18.7672C62.1352 17.0955 60.7758 15.7353 59.105 15.7353H45.6749C44.0041 15.7353 42.6446 17.0955 42.6446 18.7672V20.0503C42.6446 20.6643 43.1418 21.1617 43.7554 21.1617C44.369 21.1617 44.8662 20.6643 44.8662 20.0503V18.7672C44.8662 18.321 45.229 17.9581 45.6749 17.9581H59.105C59.5508 17.9581 59.9135 18.321 59.9135 18.7672V27.3384H44.8662V25.2461C44.8662 24.6322 44.369 24.1348 43.7554 24.1348C43.1418 24.1348 42.6446 24.6322 42.6446 25.2461V27.3384H33.1888V18.7672C33.1888 17.0955 31.8293 15.7353 30.1584 15.7353H16.7284C15.0575 15.7353 13.6981 17.0955 13.6981 18.7672V27.3384H9.89862C8.63152 27.3384 7.42056 27.5804 6.30602 28.0165V12.4939ZM15.9198 27.3384V18.7672C15.9198 18.321 16.2825 17.9581 16.7284 17.9581H30.1584C30.6044 17.9581 30.9671 18.321 30.9671 18.7672V27.3384H15.9198ZM73.1986 60.7772H70.6666C70.4388 60.7772 70.2534 60.5918 70.2534 60.3639V58.2234C70.2534 55.8409 68.3161 53.9026 65.9349 53.9026H9.89877C7.51742 53.9026 5.57997 55.841 5.57997 58.2235V60.3639C5.57997 60.5918 5.39453 60.7772 5.16674 60.7772H2.63476C2.40697 60.7772 2.22168 60.5918 2.22168 60.3639V46.5593H5.61596C6.22959 46.5593 6.7268 46.0619 6.7268 45.4479C6.7268 44.834 6.22959 44.3365 5.61596 44.3365H2.22168V37.2419C2.22168 33.0067 5.66558 29.5611 9.89862 29.5611H65.9347C70.1678 29.5611 73.6117 33.0067 73.6117 37.2419V44.3365H10.8369C10.2233 44.3365 9.72607 44.834 9.72607 45.4479C9.72607 46.0619 10.2233 46.5593 10.8369 46.5593H73.6117V60.3639C73.6117 60.5918 73.4264 60.7772 73.1986 60.7772Z" fill="#BB976D"/>
                </svg>                                               
            </div>
            <h3 class="leading-none mt-4 md:mt-6 text-2xl md:text-3xl font-bold">Featured Products</h3>
            <p class="mt-3">Discover our handpicked selection of standout products. Elevate your lifestyle with our top picks that combine quality, style, and innovation. </p>
        </div>
        <!-- Feature Product Wrapper -->
        <div class="max-w-[1720px] mx-auto flex gap-5 sm:gap-8 flex-col lg:flex-row" data-aos="fade-up" data-aos-delay="100">
            
            <!-- includes/Home/featured-products.blade.php -->
            @include('includes.Home.featured-products')

        </div>
    </div>
</div>
<!-- Feature Post End -->

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
            <h3 class="leading-tight mt-2 text-2xl md:text-3xl font-bold text-title dark:text-white">Customer Reviews</h3>
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
                    ['name' => 'Sarah M.',       'rating' => 5, 'review' => 'Absolutely love my purchase! The quality exceeded my expectations and delivery was super fast. PeytonGhalib is now my go-to store for everything.'],
                    ['name' => 'James R.',       'rating' => 5, 'review' => 'Great selection and amazing prices. The checkout process was smooth and my order arrived well-packaged. Highly recommend!'],
                    ['name' => 'Aisha K.',       'rating' => 4, 'review' => 'Really impressed with the customer service. They resolved my query within hours. The product itself is top-notch quality.'],
                    ['name' => 'Daniel T.',      'rating' => 5, 'review' => 'I\'ve been shopping here for months and every single order has been perfect. Fast shipping, great products — 10/10!'],
                    ['name' => 'Priya S.',       'rating' => 5, 'review' => 'The product photos are exactly what you get in real life. No surprises — just great quality. I\'ll definitely be ordering again soon.'],
                    ['name' => 'Michael B.',     'rating' => 4, 'review' => 'PeytonGhalib has an incredible range of products. Found exactly what I was looking for at a fair price. Very satisfied with my order.'],
                    ['name' => 'Fatima A.',      'rating' => 5, 'review' => 'Exceptional shopping experience from start to finish. The packaging was beautiful and the product quality is outstanding. Love it!'],
                    ['name' => 'Chris L.',       'rating' => 5, 'review' => 'Fast delivery and the items were exactly as described. This store genuinely cares about customer satisfaction. Will shop again!'],
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

                    <!-- Divider -->
                    <div class="my-5 border-t border-[#E3E5E6] dark:border-bdr-clr-drk"></div>

                    <!-- Customer Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-none">
                            <span class="text-primary font-bold text-base leading-none">{{ strtoupper(substr($review['name'], 0, 1)) }}</span>
                        </div>
                        <div>
                            <h5 class="font-semibold text-sm text-title dark:text-white leading-none">{{ $review['name'] }}</h5>
                            <span class="text-xs text-gray-400 dark:text-white-light mt-1 block">Verified Buyer</span>
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

@endsection