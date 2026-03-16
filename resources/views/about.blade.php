<!-- resources/views/about.blade.php -->
@extends('layouts.main')

@section('title', 'About Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">About Us</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4 flex-wrap">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">About</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- About Area Start -->
<div class="s-pb-100 pt-12 md:pt-16" data-aos="fade-up">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto flex flex-col-reverse lg:grid lg:grid-cols-2">
            <!-- About Slider -->
            <div class="lg:bg-[#F8F8F9] lg:dark:bg-dark-secondary lg:pr-10 2xl:pr-0">
                <div class="about-slider owl-carousel h-full" data-carousel-dots="true" data-carousel-margin="0">
                    <div><img class="object-cover w-full" src="{{ asset('assets/img/about/about-banner-01.jpg') }}" alt="about"></div>
                    <div><img class="object-cover w-full" src="{{ asset('assets/img/about/about-banner-02.jpg') }}" alt="about"></div>
                    <div><img class="object-cover w-full" src="{{ asset('assets/img/about/about-banner-03.jpg') }}" alt="about"></div>
                </div>
            </div>
            <!-- About Content -->
            <div class="flex items-center py-8 sm:py-12 px-5 sm:px-12 md:px-8 lg:pr-12 lg:pl-16 2xl:pl-[160px] bg-[#F8F8F9] dark:bg-dark-secondary">
                <div class="lg:max-w-[600px]">
                    <div>
                        <svg class="w-16" viewBox="0 0 68 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M66.9474 35.34H58.3624C60.294 31.9588 60.1455 27.7758 57.9789 24.5401C54.5907 19.309 56.176 17.3974 56.1818 17.3859C56.3998 17.1571 56.3912 16.7949 56.1624 16.5768C56.0566 16.4758 55.9161 16.4193 55.7697 16.4187H54.2301C54.3002 15.3447 54.4244 14.275 54.6021 13.2136C55.1068 13.389 55.636 13.4838 56.1703 13.4941C56.814 13.5248 57.4398 13.2767 57.8873 12.813C58.597 12.0232 60.1767 11.5023 60.749 11.365C61.0585 11.301 61.2576 10.9983 61.1935 10.6886C61.1296 10.3791 60.8268 10.18 60.5172 10.2441C60.5027 10.2471 60.4884 10.2506 60.4741 10.2547C60.3825 10.2547 58.1333 10.827 57.0401 12.0461C56.5823 12.5554 55.4891 12.3151 54.8137 12.0747C55.3174 9.64798 56.2331 6.86072 57.8929 5.01208C58.1047 4.77657 58.0855 4.41399 57.85 4.20223C57.6145 3.99047 57.2519 4.00964 57.0401 4.24515C56.3311 5.05701 55.7396 5.96444 55.2831 6.94084C54.1384 6.32845 52.9079 5.3841 53.1425 4.86327C53.5718 3.91892 54.4761 1.57236 53.6118 0.284608C53.451 0.0124633 53.1 -0.0776792 52.8279 0.0831464C52.5557 0.243972 52.4656 0.594955 52.6264 0.8671C52.6372 0.885271 52.649 0.902871 52.6616 0.919898C53.0222 1.49223 52.799 2.86583 52.0893 4.40541C51.3796 5.94498 53.6689 7.41016 54.8079 8.00538C53.816 10.7115 53.2373 13.5516 53.0909 16.4301H52.8848C52.877 15.6279 52.7888 14.8283 52.6216 14.0435C52.4908 13.4603 52.3207 12.8867 52.1122 12.3265C51.9848 11.9778 51.8398 11.6358 51.6772 11.302C51.56 10.9455 51.4833 10.5769 51.4483 10.2031C51.3338 9.38471 51.2251 8.60633 50.7901 8.17136C50.5675 7.94701 50.2052 7.94543 49.9807 8.16807C49.9795 8.16922 49.9784 8.17022 49.9774 8.17136C49.7555 8.39457 49.7555 8.75514 49.9774 8.97835C50.1728 9.41576 50.2872 9.88507 50.3151 10.3634C50.3584 10.8553 50.4702 11.3388 50.647 11.8C50.7844 12.0804 50.916 12.3723 51.0419 12.7214C51.1049 12.8988 51.1621 13.082 51.2193 13.2937C50.6718 13.2299 50.1841 12.9174 49.8973 12.4467C49.2422 11.5237 48.473 10.6871 47.6079 9.95704C47.3664 9.75315 47.0053 9.78362 46.8014 10.0251C46.8012 10.0253 46.8011 10.0256 46.8009 10.0257C46.597 10.2672 46.6275 10.6284 46.869 10.8323C46.8692 10.8324 46.8695 10.8326 46.8696 10.8327C47.6328 11.4895 48.3149 12.2349 48.9014 13.0534C49.4411 13.932 50.4059 14.459 51.4368 14.4384H51.5573C51.6865 15.0985 51.7555 15.7689 51.7634 16.4416H49.9777C49.7515 16.443 49.5473 16.5777 49.4569 16.785C49.3638 16.992 49.4021 17.2345 49.5541 17.4031C49.5541 17.4031 51.1567 19.3319 47.7685 24.563C45.6019 27.7987 45.4534 31.9817 47.385 35.3629H34.2614C34.6112 34.6687 34.8875 33.9398 35.0856 33.188H36.0528C37.3709 33.1882 38.4396 32.1198 38.4396 30.8016C38.4397 29.4835 37.3712 28.4148 36.0531 28.4148C36.0529 28.4148 36.0528 28.4148 36.0527 28.4148H35.4803C35.4338 27.8689 35.3554 27.3262 35.2457 26.7894C35.1904 26.5185 34.9496 26.3259 34.6733 26.3315H26.3859C26.1097 26.3259 25.8688 26.5185 25.8136 26.7894C25.6142 27.7399 25.5144 28.7085 25.516 29.6796C25.4901 31.6466 25.9232 33.5925 26.7809 35.3629H21.916V31.3566C21.916 31.0405 21.6598 30.7843 21.3437 30.7843H20.0731V29.5022H21.3437C21.6598 29.5022 21.916 29.246 21.916 28.9299V25.8851C21.916 25.569 21.6598 25.3127 21.3437 25.3127H3.93918C3.62311 25.3127 3.36685 25.569 3.36685 25.8851V28.3518H2.09627C1.78019 28.3518 1.52393 28.6081 1.52393 28.9242V31.3394C1.52393 31.6555 1.78019 31.9117 2.09627 31.9117H3.36685V35.3457H1.0489C0.732825 35.3457 0.476562 35.602 0.476562 35.9181V48.9272C0.476562 49.2433 0.732825 49.4995 1.0489 49.4995H1.52966V51.9262C1.52966 52.2423 1.78592 52.4986 2.10199 52.4986H8.88986L9.59956 62.6689C9.62059 62.9693 9.87084 63.2019 10.1719 63.2012H13.0336C13.3346 63.2019 13.5849 62.9693 13.6059 62.6689L14.3156 52.4986H53.6807L54.3904 62.6689C54.4114 62.9693 54.6616 63.2019 54.9627 63.2012H57.8244C58.1254 63.2019 58.3757 62.9693 58.3967 62.6689L59.1064 52.4986H65.8943C66.2103 52.4986 66.4666 52.2423 66.4666 51.9262V49.4995H66.9474C67.2634 49.4995 67.5197 49.2433 67.5197 48.9272V35.9124C67.5197 35.5963 67.2634 35.34 66.9474 35.34ZM35.5205 29.6568V29.5366H36.0528C36.7387 29.5364 37.2949 30.0924 37.2949 30.7784C37.2951 31.4643 36.739 32.0205 36.0531 32.0205C36.0529 32.0205 36.0528 32.0205 36.0527 32.0205H35.3201C35.4539 31.2397 35.521 30.449 35.5205 29.6568ZM48.7071 25.1582C51.294 21.1519 51.2425 18.7653 50.8705 17.5634H54.8768C54.5048 18.7653 54.4533 21.1633 57.0403 25.1582C59.1206 28.2251 59.1206 32.2503 57.0403 35.3171H48.7185C48.7185 35.3171 48.7128 35.3229 48.7071 35.3171C46.6566 32.2413 46.6566 28.2342 48.7071 25.1582ZM26.678 29.6568C26.6787 28.9184 26.7401 28.1816 26.8611 27.4533H34.1927C34.3166 28.1811 34.3779 28.9184 34.3759 29.6568C34.4228 31.6459 33.9282 33.6103 32.945 35.34H28.1088C27.1257 33.6103 26.6311 31.6459 26.678 29.6568ZM20.7715 31.9175V35.3515H4.51151V31.9175H20.7715ZM4.51151 26.4574H20.7715V28.3518H4.51151V26.4574ZM2.6686 30.7728V29.5022H3.7732C3.82614 29.523 3.88238 29.5346 3.93918 29.5366C3.99584 29.5334 4.05179 29.5218 4.10516 29.5022H18.9286V30.7728H2.6686ZM1.62123 48.3549V36.4961H33.4258V48.3549H1.62123ZM12.4956 62.0565H10.7042L10.0403 52.4986H13.1709L12.4956 62.0565ZM57.2864 62.0565H55.4892L54.8196 52.4986H57.9503L57.2864 62.0565ZM65.299 51.3539H2.67432V49.4995H65.3219L65.299 51.3539ZM66.3521 48.3549H34.5705V36.4847H66.375L66.3521 48.3549Z" fill="#BB976D"></path>
                            <path d="M60.0067 41.8481H56.1778C55.8617 41.8481 55.6055 42.1044 55.6055 42.4205C55.6055 42.7365 55.8617 42.9928 56.1778 42.9928H60.0067C60.3228 42.9928 60.579 42.7365 60.579 42.4205C60.579 42.1044 60.3228 41.8481 60.0067 41.8481Z" fill="#BB976D"></path>
                            <path d="M8.59566 41.8472H4.40046C4.08439 41.8472 3.82812 42.1034 3.82812 42.4195C3.82812 42.7356 4.08439 42.9918 4.40046 42.9918H8.59566C8.91173 42.9918 9.16799 42.7356 9.16799 42.4195C9.16799 42.1034 8.91173 41.8472 8.59566 41.8472Z" fill="#BB976D"></path>
                            <path d="M55.3901 27.1234C55.2373 26.8467 54.8892 26.7463 54.6125 26.8991C54.6085 26.9014 54.6043 26.9036 54.6003 26.9059C54.3193 27.0506 54.2087 27.3956 54.3532 27.6767C54.3535 27.6773 54.3539 27.678 54.3542 27.6786C54.3542 27.6786 55.207 29.3956 54.3142 32.429C54.2219 32.7313 54.3921 33.0512 54.6945 33.1434C54.6955 33.1437 54.6966 33.1439 54.6976 33.1444C54.7528 33.1499 54.8084 33.1499 54.8636 33.1444C55.1264 33.1548 55.3625 32.9848 55.4359 32.7323C56.4718 29.2239 55.4359 27.2093 55.3901 27.1234Z" fill="#BB976D"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium leading-none mt-4 md:mt-6 text-2xl md:text-3xl">Our Story & Journey</h3>
                    <p class="mt-3 text-base sm:text-lg">At PeytonGhalib, our story began with a simple belief — that everyone deserves access to quality products across every aspect of their life. From home essentials and electronics to fashion, sports, and lifestyle goods, we set out to build a one-stop destination where customers can discover thousands of products from trusted brands and independent sellers alike.</p>
                    <p class="mt-3 text-base sm:text-lg">Over the years, our commitment to affordability, variety, and exceptional customer service has driven our growth. We've built a platform that connects buyers with the products they love, backed by fast shipping, easy returns, and a shopping experience designed around you. Today, PeytonGhalib serves customers nationwide, and we're just getting started.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Area End -->

<!-- Why Choose Us Start -->
<div class="s-pb-100 pt-12 md:pt-16 bg-[#F8F6F3] dark:bg-title">
    <div class="container-fluid">
        <!-- Title -->
        <div class="max-w-xl mx-auto mb-10 md:mb-14 text-center" data-aos="fade-up">
            <span class="text-xs uppercase tracking-widest text-primary font-semibold">Our Promise</span>
            <h3 class="font-bold leading-tight mt-2 text-2xl md:text-3xl text-title dark:text-white">Why Choose PeytonGhalib</h3>
            <p class="mt-3 text-paragraph dark:text-white-light">We offer thousands of products across multiple categories — all in one place — with unbeatable prices, fast delivery, and a customer experience that keeps you coming back.</p>
        </div>

        <!-- Cards Grid -->
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6" data-aos="fade-up" data-aos-delay="100">

            @php
            $whyUs = [
                [
                    'icon'  => 'assets/img/svg/car.svg',
                    'title' => 'Free Shipping',
                    'desc'  => 'Enjoy hassle-free shopping with complimentary shipping on all orders — no minimum purchase required.',
                    'badge' => 'Always Free',
                ],
                [
                    'icon'  => 'assets/img/svg/box.svg',
                    'title' => 'Easy Returns',
                    'desc'  => 'Changed your mind? No problem. Our hassle-free return process ensures your complete satisfaction.',
                    'badge' => '30-Day Policy',
                ],
                [
                    'icon'  => 'assets/img/svg/card.svg',
                    'title' => 'Secure Payment',
                    'desc'  => 'Shop with confidence. Our encrypted checkout keeps your payment information safe at every step.',
                    'badge' => '100% Secure',
                ],
                [
                    'icon'  => 'assets/img/svg/support.svg',
                    'title' => '24/7 Support',
                    'desc'  => 'Our dedicated support team is always ready to help you — any time, any day, any question.',
                    'badge' => 'Always Here',
                ],
                [
                    'icon'  => 'assets/img/svg/award.svg',
                    'title' => 'Quality Assured',
                    'desc'  => 'Every product passes our rigorous QC standards. Trust in quality that goes beyond your expectations.',
                    'badge' => 'QC Certified',
                ],
            ];
            @endphp

            @foreach($whyUs as $item)
            <div class="group bg-white dark:bg-dark-secondary rounded-sm p-7 md:p-8 flex flex-col items-center text-center border border-transparent hover:border-primary duration-300 shadow-sm hover:shadow-lg">
                <!-- Icon -->
                <div class="w-16 h-16 rounded-full bg-primary/10 group-hover:bg-primary/20 duration-300 flex items-center justify-center mb-5 flex-none">
                    <img src="{{ asset($item['icon']) }}" class="w-8 h-8" alt="{{ $item['title'] }}">
                </div>
                <!-- Badge -->
                <span class="text-[10px] uppercase tracking-widest text-primary font-semibold mb-2">{{ $item['badge'] }}</span>
                <!-- Title -->
                <h5 class="font-bold text-lg text-title dark:text-white mb-3">{{ $item['title'] }}</h5>
                <!-- Divider -->
                <div class="w-8 h-[2px] bg-primary mb-4 group-hover:w-14 duration-300"></div>
                <!-- Desc -->
                <p class="text-sm text-paragraph dark:text-white-light leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach

        </div>
    </div>
</div>
<!-- Why Choose Us End -->

<!-- Customer Reviews Start -->
<div class="s-py-50-100 dark:bg-title">
    <div class="container-fluid">
        <!-- Section Title -->
        <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
            <span class="text-xs uppercase tracking-widest text-primary font-semibold">What our customers say</span>
            <h3 class="leading-tight mt-2 text-2xl md:text-3xl font-bold text-title dark:text-white">Customer Reviews</h3>
            <p class="mt-3 text-paragraph dark:text-white-light">Real stories from real shoppers. See why thousands of customers love shopping with PeytonGhalib.</p>
        </div>

        <!-- Reviews Slider -->
        <div class="max-w-[1720px] mx-auto relative group" data-aos="fade-up" data-aos-delay="100">
            <div class="owl-carousel reviews-slider-about"
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
                    ['name' => 'Sarah M.',   'rating' => 5, 'review' => 'Absolutely love my purchase! The quality exceeded my expectations and delivery was super fast. PeytonGhalib is now my go-to store for everything.'],
                    ['name' => 'James R.',   'rating' => 5, 'review' => 'Great selection and amazing prices. The checkout process was smooth and my order arrived well-packaged. Highly recommend!'],
                    ['name' => 'Aisha K.',   'rating' => 4, 'review' => 'Really impressed with the customer service. They resolved my query within hours. The product itself is top-notch quality.'],
                    ['name' => 'Daniel T.',  'rating' => 5, 'review' => 'I\'ve been shopping here for months and every single order has been perfect. Fast shipping, great products — 10/10!'],
                    ['name' => 'Priya S.',   'rating' => 5, 'review' => 'The product photos are exactly what you get in real life. No surprises — just great quality. I\'ll definitely be ordering again soon.'],
                    ['name' => 'Michael B.', 'rating' => 4, 'review' => 'PeytonGhalib has an incredible range of products. Found exactly what I was looking for at a fair price. Very satisfied with my order.'],
                    ['name' => 'Fatima A.',  'rating' => 5, 'review' => 'Exceptional shopping experience from start to finish. The packaging was beautiful and the product quality is outstanding. Love it!'],
                    ['name' => 'Chris L.',   'rating' => 5, 'review' => 'Fast delivery and the items were exactly as described. This store genuinely cares about customer satisfaction. Will shop again!'],
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
            <button class="icon about_reviews_prev w-9 h-9 md:w-12 md:h-12 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary hover:text-white transform p-2 absolute top-1/2 -translate-y-1/2 -left-4 md:-left-6 z-[999] shadow-md" aria-label="Previous Review">
                <svg class="fill-current" width="18" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.180223 7.38726L5.62434 12.8314C5.8199 13.0598 6.16359 13.0864 6.39195 12.8908C6.62031 12.6952 6.64693 12.3515 6.45132 12.1232C6.43307 12.1019 6.41324 12.082 6.39195 12.0638L1.87877 7.54516L23.4322 7.54516C23.7328 7.54516 23.9766 7.30141 23.9766 7.00072C23.9766 6.70003 23.7328 6.45632 23.4322 6.45632L1.87877 6.45632L6.39195 1.94314C6.62031 1.74758 6.64693 1.40389 6.45132 1.17553C6.25571 0.947171 5.91207 0.920551 5.68371 1.11616C5.66242 1.13441 5.64254 1.15424 5.62434 1.17553L0.180175 6.6197C-0.0308748 6.83196 -0.0308748 7.1749 0.180223 7.38726Z"/>
                </svg>
            </button>
            <button class="icon about_reviews_next w-9 h-9 md:w-12 md:h-12 flex items-center justify-center text-title duration-300 bg-white hover:bg-primary hover:text-white transform p-2 absolute top-1/2 -translate-y-1/2 -right-4 md:-right-6 z-[999] shadow-md" aria-label="Next Review">
                <svg class="fill-current" width="18" height="12" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z"/>
                </svg>
            </button>
        </div>

    </div>
</div>
<!-- Customer Reviews End -->

@include('includes.footer6')

@push('scripts')
<script>
    $(document).ready(function () {
        var slider = $('.reviews-slider-about');
        $('.about_reviews_next').on('click', function () {
            slider.trigger('next.owl.carousel');
        });
        $('.about_reviews_prev').on('click', function () {
            slider.trigger('prev.owl.carousel', [300]);
        });
    });
</script>
@endpush

@endsection
