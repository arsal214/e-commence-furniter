<!-- resources/views/portfolio-v3.blade.php -->
@extends('layouts.main')

@section('title', 'Portfolio-V3 Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Portfolio</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Portfolio</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Portfolio v1 Area Start -->
<div class="s-py-100 overflow-hidden relative">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <!-- portfolio Buttons -->
            <div class="portfolio2-button flex items-center gap-3 md:gap-4 lg:gap-6 flex-wrap mb-8 md:mb-12" data-aos="fade-up" data-aos-delay="100">
                <button class="active text-base md:text-[17px] leading-none font-medium hover:text-primary duration-300" data-filter="*">
                    All
                </button>
                <svg class="fill-current text-paragraph dark:text-white-light" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.894 0.194247C6.054 2.48625 5.214 4.77825 4.374 7.07025C3.534 9.36225 2.688 11.6542 1.836 13.9462H0.306C1.158 11.6542 2.004 9.36225 2.844 7.07025C3.684 4.77825 4.53 2.48625 5.382 0.194247H6.894Z"/>
                </svg>
                <button class="text-base md:text-[17px] leading-none font-medium hover:text-primary duration-300" data-filter=".Interior">
                    Full Interior
                </button>
                <svg class="fill-current text-paragraph dark:text-white-light" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.894 0.194247C6.054 2.48625 5.214 4.77825 4.374 7.07025C3.534 9.36225 2.688 11.6542 1.836 13.9462H0.306C1.158 11.6542 2.004 9.36225 2.844 7.07025C3.684 4.77825 4.53 2.48625 5.382 0.194247H6.894Z"/>
                </svg>
                <button class="text-base md:text-[17px] leading-none font-medium hover:text-primary duration-300" data-filter=".Vase">
                    Lamp & Vase
                </button>
                <svg class="fill-current text-paragraph dark:text-white-light" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.894 0.194247C6.054 2.48625 5.214 4.77825 4.374 7.07025C3.534 9.36225 2.688 11.6542 1.836 13.9462H0.306C1.158 11.6542 2.004 9.36225 2.844 7.07025C3.684 4.77825 4.53 2.48625 5.382 0.194247H6.894Z"/>
                </svg>
                <button class="text-base md:text-[17px] leading-none font-medium hover:text-primary duration-300" data-filter=".Table">
                    Table
                </button>
                <svg class="fill-current text-paragraph dark:text-white-light" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.894 0.194247C6.054 2.48625 5.214 4.77825 4.374 7.07025C3.534 9.36225 2.688 11.6542 1.836 13.9462H0.306C1.158 11.6542 2.004 9.36225 2.844 7.07025C3.684 4.77825 4.53 2.48625 5.382 0.194247H6.894Z"/>
                </svg>
                <button class="text-base md:text-[17px] leading-none font-medium hover:text-primary duration-300" data-filter=".Design">
                    Art Design
                </button>
            </div>
            <!-- Gallery -->
            <div class="portfolio2-isotope -m-[10px] sm:-m-[15px]" data-aos="fade-up" data-aos-delay="200">
                <div class="grid-sizer"></div>
                
                <!-- includes/Pages/portfolios-v3.blade.php -->
                @include('includes.Pages.portfolios-v3')

            </div>
        </div>
    </div>
</div>
<!-- Portfolio v1 Area End -->

@include('includes.footer')
  
@endsection