<!-- resources/views/index-v6.blade.php -->
@extends('layouts.main')

@section('title', 'Index-V6 Page')

@section('content')

@include('includes.navbar4')

<div class="dark:bg-title bg-[#EBEBEB]">

    <!-- Banner Start -->
    <div class="hv6-banner bg-overlay py-20 sm:py-24 md:py-32 2xl:py-[245px] bg-[#EBEBEB] dark:bg-title dark:before:bg-dark-secondary dark:before:bg-opacity-50 2xl:dark:before:hidden">
        <div class="container">
            <div class="max-w-1366 mx-auto">
                <span class="text-xl sm:text-2xl lg:text-3xl leading-none block text-secondary" data-aos="fade-up">New Arrival</span>
                <h2 class="text-4xl sm:text-5xl lg:text-7xl sm:leading-tight leading-tight lg:leading-tight max-w-[300px] sm:max-w-[400px] lg:max-w-[602px] mt-5 font-bold" data-aos="fade-up" data-aos-delay="100">Premium Lounge Sofa Chair</h2>
                <p class="max-w-[450px] lg:max-w-[550px] mt-3 sm:mt-0" data-aos="fade-up" data-aos-delay="200">We offer you an extremely comfortable lounge sofa chair. Taking the time to relax at home or the office will be more appealing than ever when you choose a modern lounge sofa chair.</p>
                <div data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ url('/shop-v1') }}" class="btn btn-outline mt-6" data-text="Let's Shop Now">
                        <span>Let's Shop Now</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Service Area Start -->
    <div class="bg-[#EBEBEB] dark:bg-title s-py-100" data-aos="fade-up">
        <div class="container">
            <div class="max-w-1366 mx-auto">
                <div class="bg-[#F3F3F3] dark:bg-dark-secondary rounded-[10px] xl:flex xl:justify-between sm:gap-5 grid sm:grid-cols-2 relative">
                   
                    <!-- includes/Home/shop-area.blade.php -->
                    @include('includes.Home.shop-area')

                </div>
            </div>
        </div>
    </div>
    <!-- Service Area End -->

    <!-- Offer Area Start -->
    <div class="s-py-100 bg-[#F3F3F3] dark:bg-dark-secondary relative z-[1] overflow-hidden">
        <img class="absolute top-0 left-[6%] z-[-1] w-[10%] 2xl:w-auto" src="{{ asset('assets/img/home-v6/offer-01.png') }}" alt="offer-shape" data-aos="fade-down">
        <img class="absolute right-0 bottom-0 lg:bottom-auto lg:top-[84%] transform lg:-translate-y-1/2 z-[-1] w-1/4 2xl:w-auto" src="{{ asset('assets/img/home-v6/offer-02.png') }}" alt="offer-shape">
        <div class="container">
            <div class="max-w-[780px] mx-auto text-center">
                <h2 class="leading-none text-7xl sm:text-100 lg:text-[150px] xl:text-[200px] italic flex items-start justify-center font-bold" data-aos="fade-up">45% <sup class="text-xl sm:text-2xl lg:text-3xl font-normal text-secondary transform left-[5%] !rotate-90 inline-block not-italic translate-y-[20px] lg:translate-y-[40px]">OFF</sup></h2>
                <h3 class="font-normal leading-none mt-4 sm:mt-6 text-xl sm:text-2xl md:text-3xl" data-aos="fade-up" data-aos-delay="100">Hurry Up ! It,s Only for Today</h3>
                <p class="mt-5 sm:mt-6" data-aos="fade-up" data-aos-delay="200">Don't miss out on exclusive savings! Enjoy a massive 45% discount, available for today only. Hurry, grab your deal before it's gone!This special deal won’t last long, so claim it while you can! </p>
                <div data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ url('/shop-v2') }}" class="btn btn-outline mt-6" data-text="Claim Discount">
                        <span>Claim Discount</span>

                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Offer Area End -->

    <!-- Latest Products Area Start -->
    <div class="s-py-100-50" >
        <div class="container">
            <div class="max-w-1366 mx-auto">
                <div class="max-w-[547px] mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
                    <svg class="mx-auto" width="73" height="63" viewBox="0 0 73 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.7435 61.797C13.7435 62.4613 13.2025 62.9998 12.5353 62.9998H1.20822C0.54098 62.9998 0 62.4613 0 61.797C0 61.1327 0.54098 60.5941 1.20822 60.5941H12.5353C13.2025 60.5941 13.7435 61.1327 13.7435 61.797ZM28.0911 8.72061C32.7545 8.72061 36.5486 12.4979 36.5486 17.1407V19.2457C36.5486 19.91 36.0076 20.4485 35.3404 20.4485H20.8418C20.1745 20.4485 19.6336 19.91 19.6336 19.2457V17.1407C19.6336 12.4979 23.4277 8.72061 28.0911 8.72061ZM22.05 17.1407V18.0428H34.1322V17.1407C34.1322 13.8244 31.4222 11.1263 28.0911 11.1263C24.76 11.1263 22.05 13.8244 22.05 17.1407ZM10.0433 58.1884C10.7106 58.1884 11.2524 57.6497 11.2515 56.9839L11.1881 9.97069C11.1825 5.79104 14.5782 2.40558 18.7768 2.40558H19.2944C21.7168 2.40558 23.9371 3.51672 25.3857 5.45409C25.7842 5.9868 26.5411 6.09732 27.0763 5.70067C27.6116 5.30403 27.7224 4.55043 27.324 4.01757C25.4428 1.50193 22.441 0 19.2944 0H18.7768C13.2418 0 8.76427 4.46308 8.77167 9.974L8.8351 56.9872C8.83601 57.6509 9.37669 58.1884 10.0433 58.1884V58.1884ZM67.0562 41.7994V33.9562C67.0562 30.4048 64.1539 27.5154 60.5866 27.5154H27.6134C24.0461 27.5154 21.1438 30.4048 21.1438 33.9562V36.8376C21.1438 37.5018 21.6848 38.0404 22.3521 38.0404C23.0193 38.0404 23.5603 37.5018 23.5603 36.8376V33.9562C23.5603 31.7312 25.3785 29.9211 27.6134 29.9211H43.0428V43.4533C43.0428 44.1176 43.5838 44.6562 44.251 44.6562C44.9183 44.6562 45.4592 44.1176 45.4592 43.4533V29.9211H60.5866C62.8215 29.9211 64.6397 31.7312 64.6397 33.9562V41.8312C61.9265 42.1223 59.8068 44.4153 59.8068 47.1927V48.2648C59.8068 48.929 60.3478 49.4676 61.0151 49.4676C61.6823 49.4676 62.2233 48.929 62.2233 48.2648V47.1927C62.2233 45.5454 63.5694 44.2051 65.224 44.2051H67.076C68.7306 44.2051 70.0767 45.5454 70.0767 47.1927V57.6067C70.0767 59.254 68.7306 60.5943 67.076 60.5943H62.2233C62.2233 52.7215 62.2292 53.206 62.2129 53.0764C62.305 52.3419 61.7281 51.7231 61.0151 51.7231H33.9812C33.3139 51.7231 32.7729 52.2617 32.7729 52.926C32.7729 53.5903 33.3139 54.1289 33.9812 54.1289H59.8068V61.7971C59.8068 62.4614 60.3478 63 61.0151 63H67.076C70.063 63 72.4932 60.5806 72.4932 57.6067V47.1927C72.4932 44.2577 70.1101 41.7994 67.0562 41.7994ZM54.8229 60.5941C53.7304 60.5941 23.1422 60.5941 21.4263 60.5941C19.7716 60.5941 18.4253 59.2538 18.4253 57.6065V47.1927C18.4253 45.5454 19.7716 44.2051 21.4263 44.2051H23.278C24.9327 44.2051 26.2789 45.5454 26.2789 47.1927V53.9784C26.2789 54.6426 26.8199 55.1812 27.4871 55.1812C28.1544 55.1812 28.6954 54.6426 28.6954 53.9784V47.1927C28.6954 44.2188 26.2652 41.7994 23.278 41.7994H21.4263C18.4391 41.7994 16.0089 44.2188 16.0089 47.1927V57.6067C16.0089 60.5806 18.4391 63 21.4263 63H54.8229C55.4902 63 56.0312 62.4614 56.0312 61.7971C56.0312 61.1329 55.4902 60.5941 54.8229 60.5941Z" fill="#BB976D"/>
                    </svg>             
                    <h2 class="mt-[15px] leading-none text-4xl font-bold">Latest Products</h2>
                    <p class="mt-[10px] md:mt-[15px]">Be the first to experience innovation with our latest arrivals. Stay ahead of the curve and discover what's new in style. </p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-x-5 lg:gap-x-7 gap-y-[30px] lg:gap-y-12" data-aos="fade-up" data-aos-delay="100">
                   
                    <!-- includes/Home/latest-products1.blade.php -->
                    @include('includes.Home.latest-products1')

                </div>

                <div class="text-center mt-7 md:mt-12" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ url('/shop-v3') }}" class="btn btn-outline" data-text="See all Products">
                        <span>See all Products</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Latest Products Area End -->

    <!-- Shop Area Start -->
    <div class="s-py-50" data-aos="fade-up">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Shop Card -->
            <div class="relative">
                <img class="w-full object-cover" src="{{ asset('assets/img/home-v3/pdct-01.jpg') }}" alt="product">
                <div class="absolute w-[90%] h-[90%] flex items-center justify-center top-[95%] left-[95%] transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="bg-white dark:bg-title bg-opacity-90 dark:bg-opacity-90 p-5 sm:p-6 flex items-start flex-col">
                        <h4 class="font-normal leading-none text-2xl">Up to <span class="text-secondary">20% off</span> all furniture on store</h4>
                        <h2 class="text-3xl md:text-4xl xl:text-5xl leading-none mt-[15px] font-bold">Home & Office</h2>
                        <a href="{{ url('/shop-v4') }}" class="btn btn-outline btn-sm mt-4 sm:mt-6" data-text="Shop Now">
                            <span>Shop Now</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Shop Card -->
            <div class="relative">
                <img class="w-full object-cover" src="{{ asset('assets/img/home-v3/pdct-02.jpg') }}" alt="product">
                <div class="absolute w-[90%] h-[90%] flex items-center justify-center top-[95%] left-[95%] transform -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="bg-white dark:bg-title bg-opacity-90 dark:bg-opacity-90 p-5 sm:p-6 flex items-start flex-col">
                        <h4 class="font-normal leading-none text-2xl">Up to <span class="text-secondary">35% off</span> all Interior Items</h4>
                        <h2 class="text-3xl md:text-4xl xl:text-5xl leading-none mt-[15px] font-bold">Interior Setup</h2>
                        <a href="{{ url('/shop-v2') }}" class="btn btn-outline btn-sm mt-4 sm:mt-6" data-text="Shop Now">
                            <span>Shop Now</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Area End -->

    <!-- Blog Area Start -->
    <div class="s-py-50-100">
        <div class="container">
            <div class="max-w-1366 mx-auto">
                <!-- Title -->
                <div class="max-w-[547px] mx-auto mb-8 sm:mb-[70px] text-center" data-aos="fade-up">
                    <svg class="mx-auto" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M54.1712 13.3447C51.1919 10.3694 47.603 8.07541 43.6517 6.62074C39.7004 5.16606 35.4808 4.58531 31.2834 4.91849C27.583 5.20919 23.9747 6.21786 20.66 7.88816C20.3943 8.02261 20.1928 8.25689 20.0996 8.53966C20.0064 8.82242 20.0291 9.13061 20.1627 9.39668C20.2963 9.66275 20.5299 9.865 20.8124 9.9591C21.0949 10.0532 21.4032 10.0315 21.6696 9.89869C24.7242 8.35948 28.0493 7.42992 31.4592 7.16195C35.6479 6.82393 39.8575 7.47425 43.749 9.06055C47.6404 10.6469 51.105 13.1248 53.8637 16.2949C56.6224 19.4649 58.5981 23.2385 59.6318 27.3117C60.6655 31.3849 60.7282 35.644 59.815 39.7459C58.9018 43.8478 57.0381 47.6779 54.3739 50.9279C51.7098 54.1778 48.3198 56.7568 44.4768 58.457C40.6337 60.1573 36.4452 60.9313 32.2483 60.7169C28.0514 60.5024 23.9636 59.3054 20.3139 57.2223C19.7228 56.8884 19.0678 56.6832 18.3918 56.6203C17.7159 56.5573 17.0343 56.638 16.3917 56.857L7.80458 59.7162L10.6627 51.1324C10.8835 50.4918 10.9652 49.8115 10.9025 49.1368C10.8398 48.4621 10.6341 47.8086 10.2991 47.2196C7.75663 42.7571 6.54397 37.6604 6.80425 32.5311C6.99787 28.7145 8.02183 24.9862 9.8047 21.6061C9.87344 21.4753 9.91576 21.3322 9.92922 21.1851C9.94269 21.0379 9.92704 20.8896 9.88317 20.7485C9.8393 20.6074 9.76807 20.4763 9.67355 20.3628C9.57902 20.2492 9.46306 20.1554 9.33227 20.0866C9.20148 20.0179 9.05843 19.9756 8.91129 19.9621C8.76415 19.9486 8.6158 19.9643 8.47471 20.0081C8.33361 20.052 8.20254 20.1232 8.08898 20.2178C7.97541 20.3123 7.88157 20.4283 7.81283 20.559C5.87824 24.2277 4.76714 28.2741 4.55699 32.4163C4.2749 37.9775 5.59054 43.5033 8.34846 48.3408C8.52452 48.6541 8.63172 49.0014 8.66288 49.3595C8.69405 49.7176 8.64847 50.0782 8.5292 50.4172L4.95962 61.1385C4.89365 61.3366 4.88416 61.5493 4.93218 61.7525C4.98021 61.9558 5.08386 62.1416 5.23154 62.2893C5.37922 62.437 5.56509 62.5407 5.76835 62.5887C5.9716 62.6367 6.18421 62.6272 6.38237 62.5612L17.1069 58.9906C17.4487 58.8722 17.8116 58.8275 18.1718 58.8594C18.5321 58.8913 18.8815 58.9991 19.1971 59.1757C23.5981 61.6894 28.5801 63.0079 33.6483 63.0002C34.1334 63.0002 34.6193 62.9881 35.1062 62.9639C40.7246 62.6746 46.1386 60.7621 50.692 57.4581C55.2455 54.154 58.743 49.6004 60.7608 44.3488C62.7786 39.0972 63.23 33.3732 62.0604 27.8702C60.8909 22.3673 58.1504 17.3216 54.1712 13.3447V13.3447Z" fill="#BB976D"/>
                        <path d="M22.8322 29.2756L29.7565 31.1351C29.9473 31.1863 30.1483 31.1863 30.3391 31.1352C30.5299 31.0841 30.7039 30.9837 30.8436 30.844C30.9833 30.7043 31.0838 30.5303 31.1349 30.3394C31.186 30.1486 31.186 29.9477 31.1348 29.7568L29.2753 22.8315C29.1152 22.2369 28.8019 21.6948 28.3667 21.2593L11.3368 4.22982L11.3359 4.22845L11.3345 4.22755L8.13439 1.02749C7.47535 0.369542 6.58216 0 5.6509 0C4.71965 0 3.82645 0.369542 3.16741 1.02749L1.02717 3.16714H1.02662C0.369089 3.82655 -0.000103318 4.71981 2.16884e-08 5.65103C0.000103362 6.58226 0.369494 7.47544 1.02717 8.13471L21.2595 28.3665C21.6951 28.802 22.2375 29.1155 22.8322 29.2756V29.2756ZM26.4488 22.5231C26.4147 22.5431 26.3818 22.565 26.3501 22.5886L22.5889 26.3499C22.5652 26.3815 22.5433 26.4145 22.5233 26.4486L6.61474 10.5404L10.5402 6.61488L26.4488 22.5231ZM28.4557 28.456L24.6789 27.4416L27.4415 24.679L28.4557 28.456ZM2.61858 4.75799L4.75822 2.61835C4.99515 2.38196 5.31618 2.2492 5.65087 2.2492C5.98557 2.2492 6.30659 2.38196 6.54353 2.61835L8.94927 5.02409L5.02381 8.94964L2.61804 6.54385C2.38159 6.3068 2.24885 5.98562 2.24895 5.65081C2.24905 5.31599 2.38199 4.9949 2.61858 4.75799V4.75799Z" fill="#BB976D"/>
                        <path d="M52.0644 36.4375H21.3438C21.0454 36.4375 20.7592 36.556 20.5483 36.767C20.3373 36.978 20.2188 37.2642 20.2188 37.5625C20.2188 37.8609 20.3373 38.1471 20.5483 38.358C20.7592 38.569 21.0454 38.6876 21.3438 38.6876H52.0644C52.3628 38.6876 52.649 38.569 52.86 38.358C53.0709 38.1471 53.1895 37.8609 53.1895 37.5625C53.1895 37.2642 53.0709 36.978 52.86 36.767C52.649 36.556 52.3628 36.4375 52.0644 36.4375Z" fill="#BB976D"/>
                        <path d="M52.0646 43.9521H30.0469C29.7485 43.9521 29.4624 44.0707 29.2514 44.2817C29.0404 44.4926 28.9219 44.7788 28.9219 45.0772C28.9219 45.3755 29.0404 45.6617 29.2514 45.8727C29.4624 46.0837 29.7485 46.2022 30.0469 46.2022H52.0646C52.3629 46.2022 52.6491 46.0837 52.8601 45.8727C53.0711 45.6617 53.1896 45.3755 53.1896 45.0772C53.1896 44.7788 53.0711 44.4926 52.8601 44.2817C52.6491 44.0707 52.3629 43.9521 52.0646 43.9521Z" fill="#BB976D"/>
                        <path d="M22.1914 45.0766C22.1914 45.3128 22.2614 45.5436 22.3926 45.7399C22.5238 45.9363 22.7102 46.0893 22.9284 46.1797C23.1465 46.27 23.3866 46.2937 23.6182 46.2476C23.8498 46.2015 24.0625 46.0878 24.2295 45.9209C24.3964 45.7539 24.5101 45.5412 24.5562 45.3096C24.6023 45.078 24.5786 44.8379 24.4883 44.6198C24.3979 44.4016 24.2449 44.2152 24.0485 44.084C23.8522 43.9528 23.6214 43.8828 23.3852 43.8828C23.0686 43.8828 22.765 44.0086 22.5411 44.2325C22.3172 44.4564 22.1914 44.76 22.1914 45.0766Z" fill="#BB976D"/>
                    </svg>
                    <h2 class="mt-[15px] leading-none text-4xl font-bold">From the Blog</h2>
                    <p class="mt-[10px] md:mt-[15px]">Stay informed and inspired with our latest blog posts. Explore insightful content that keeps you ahead of trends. </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                    
                    <!-- includes/Home/blog5.blade.php -->
                    @include('includes.Home.blog5')

                </div>
            </div>
        </div>
    </div>
    <!-- Blog Area End -->

    <!-- Partner Area Start -->
    <div class="py-12 bg-[#F3F3F3] dark:bg-dark-secondary">
        <div class="container">
            <div class="max-w-[1265px] mx-auto">
                <div class="w-full relative">
                    <div class="owl-carousel partner-slider-01" data-carousel-items="5" data-carousel-margin="20" data-carousel-xl="4" data-carousel-lg="3" data-carousel-md="3" data-carousel-sm="3" data-carousel-xs="1" data-carousel-autoplay="true" data-carousel-loop="true" data-carousel-animateout="false">
                        
                        <!-- includes/Home/trusted-partner4.blade.php -->
                        @include('includes.Home.trusted-partner4')

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Partner Area End -->

    <!-- includes/Home/popup.blade.php -->
    @include('includes.Home.popup')
    
</div>

@include('includes.footer5')
  
@endsection