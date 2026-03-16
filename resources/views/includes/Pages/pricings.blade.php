@php
$checkIcon = '<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.80386 12.9623L4.6596 12.7166C3.57757 10.8731 0.699387 6.95693 0.670357 6.91758L0.585938 6.80285L1.66876 5.73317L4.76732 7.89681C6.70934 5.38699 8.51847 3.65968 9.70196 2.6474C11.0094 1.52905 11.8372 1.02863 11.8716 1.00787L11.9107 0.984375H13.8186L13.4955 1.27213C9.41548 4.90614 4.98911 12.6371 4.94486 12.7147L4.80386 12.9623Z" fill="#BB976D"/></svg>';
@endphp

<!-- Starter Plan -->
<div class="w-full sm:w-[47%] lg:w-auto flex flex-col justify-between bg-primary bg-opacity-10 px-7 md:px-10 py-8 md:py-12 group" data-aos="fade-up" data-aos-delay="100">
    <div>
        <h4 class="font-normal leading-none text-2xl">Starter Member</h4>
        <p class="text-sm text-gray-500 dark:text-white-light mt-2">Perfect for occasional shoppers</p>
        <span class="block text-title dark:text-white text-4xl md:text-5xl font-bold leading-none mt-4 md:mt-6 duration-300 price" data-monthly="0" data-yearly="0">Free</span>
        <ul class="mt-4 md:mt-6 flex flex-col gap-[15px]">
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Access to all product categories
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Save items to wishlist
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Order tracking &amp; history
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Standard delivery rates apply
            </li>
        </ul>
    </div>
    <div class="mt-6 md:mt-8">
        <a class="btn btn-theme-outline duration-300 group-hover:border-primary" href="{{ url('/register') }}" data-text="Get Started Free">
            <span class="duration-300 group-hover:text-primary">Get Started Free</span>
        </a>
    </div>
</div>

<!-- Silver Plan -->
<div class="w-full sm:w-[47%] lg:w-auto flex flex-col bg-primary px-7 md:px-10 py-8 md:py-12 group justify-between relative" data-aos="fade-up" data-aos-delay="200">
    <div class="absolute top-4 right-4 bg-white text-primary text-xs font-bold px-3 py-1 uppercase tracking-wider">Most Popular</div>
    <div>
        <h4 class="font-normal leading-none text-2xl text-white">Silver Member</h4>
        <p class="text-sm text-white/70 mt-2">For regular shoppers who want more</p>
        <span class="block text-white text-4xl md:text-5xl font-bold leading-none mt-4 md:mt-6 duration-300 price" data-monthly="9" data-yearly="89">$9</span>
        <ul class="mt-4 md:mt-6 flex flex-col gap-[15px]">
            <li class="flex items-center gap-[10px] text-white">
                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.80386 12.9623L4.6596 12.7166C3.57757 10.8731 0.699387 6.95693 0.670357 6.91758L0.585938 6.80285L1.66876 5.73317L4.76732 7.89681C6.70934 5.38699 8.51847 3.65968 9.70196 2.6474C11.0094 1.52905 11.8372 1.02863 11.8716 1.00787L11.9107 0.984375H13.8186L13.4955 1.27213C9.41548 4.90614 4.98911 12.6371 4.94486 12.7147L4.80386 12.9623Z" fill="white"/></svg>
                Everything in Starter
            </li>
            <li class="flex items-center gap-[10px] text-white">
                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.80386 12.9623L4.6596 12.7166C3.57757 10.8731 0.699387 6.95693 0.670357 6.91758L0.585938 6.80285L1.66876 5.73317L4.76732 7.89681C6.70934 5.38699 8.51847 3.65968 9.70196 2.6474C11.0094 1.52905 11.8372 1.02863 11.8716 1.00787L11.9107 0.984375H13.8186L13.4955 1.27213C9.41548 4.90614 4.98911 12.6371 4.94486 12.7147L4.80386 12.9623Z" fill="white"/></svg>
                5% discount on every order
            </li>
            <li class="flex items-center gap-[10px] text-white">
                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.80386 12.9623L4.6596 12.7166C3.57757 10.8731 0.699387 6.95693 0.670357 6.91758L0.585938 6.80285L1.66876 5.73317L4.76732 7.89681C6.70934 5.38699 8.51847 3.65968 9.70196 2.6474C11.0094 1.52905 11.8372 1.02863 11.8716 1.00787L11.9107 0.984375H13.8186L13.4955 1.27213C9.41548 4.90614 4.98911 12.6371 4.94486 12.7147L4.80386 12.9623Z" fill="white"/></svg>
                Free standard shipping on all orders
            </li>
            <li class="flex items-center gap-[10px] text-white">
                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.80386 12.9623L4.6596 12.7166C3.57757 10.8731 0.699387 6.95693 0.670357 6.91758L0.585938 6.80285L1.66876 5.73317L4.76732 7.89681C6.70934 5.38699 8.51847 3.65968 9.70196 2.6474C11.0094 1.52905 11.8372 1.02863 11.8716 1.00787L11.9107 0.984375H13.8186L13.4955 1.27213C9.41548 4.90614 4.98911 12.6371 4.94486 12.7147L4.80386 12.9623Z" fill="white"/></svg>
                Early access to new arrivals &amp; sales
            </li>
            <li class="flex items-center gap-[10px] text-white">
                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.80386 12.9623L4.6596 12.7166C3.57757 10.8731 0.699387 6.95693 0.670357 6.91758L0.585938 6.80285L1.66876 5.73317L4.76732 7.89681C6.70934 5.38699 8.51847 3.65968 9.70196 2.6474C11.0094 1.52905 11.8372 1.02863 11.8716 1.00787L11.9107 0.984375H13.8186L13.4955 1.27213C9.41548 4.90614 4.98911 12.6371 4.94486 12.7147L4.80386 12.9623Z" fill="white"/></svg>
                Priority customer support
            </li>
        </ul>
    </div>
    <div class="mt-6 md:mt-8">
        <a class="btn bg-white text-primary border-white hover:bg-transparent hover:text-white duration-300 w-full text-center px-6 py-3 font-medium" href="{{ url('/register') }}" data-text="Join Silver">
            <span>Join Silver</span>
        </a>
    </div>
</div>

<!-- Gold Plan -->
<div class="w-full sm:w-[47%] lg:w-auto flex flex-col bg-primary bg-opacity-10 px-7 md:px-10 py-8 md:py-12 group justify-between" data-aos="fade-up" data-aos-delay="300">
    <div>
        <h4 class="font-normal leading-none text-2xl">Gold Member</h4>
        <p class="text-sm text-gray-500 dark:text-white-light mt-2">For power shoppers who demand the best</p>
        <span class="block text-title dark:text-white text-4xl md:text-5xl font-bold leading-none mt-4 md:mt-6 duration-300 price" data-monthly="19" data-yearly="179">$19</span>
        <ul class="mt-4 md:mt-6 flex flex-col gap-[15px]">
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Everything in Silver
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                10% discount on every order
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Free express delivery on all orders
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Exclusive member-only deals &amp; flash sales
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Dedicated VIP customer support line
            </li>
            <li class="flex items-center gap-[10px] text-title dark:text-white-light">
                {!! $checkIcon !!}
                Extended 60-day return window
            </li>
        </ul>
    </div>
    <div class="mt-6 md:mt-8">
        <a class="btn btn-theme-outline duration-300 group-hover:border-primary" href="{{ url('/register') }}" data-text="Join Gold">
            <span class="duration-300 group-hover:text-primary">Join Gold</span>
        </a>
    </div>
</div>
