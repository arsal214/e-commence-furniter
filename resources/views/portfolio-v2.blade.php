<!-- resources/views/portfolio-v2.blade.php -->
@extends('layouts.main')

@section('title', 'Portfolio-V2 Page')

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

<!-- Portfolio v2 Area Start -->
<div class="s-py-100 overflow-hidden relative">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto" data-aos="fade-up" data-aos-delay="100">
            <!-- portfolio Navs -->
            <div class="portfolio1-button flex justify-center gap-[15px] flex-wrap mb-8 md:mb-12">
                <button class="active btn btn-sm btn-theme-outline" data-filter="*" data-text="All">
                    <span>All</span>
                </button>
                <button class="btn btn-sm btn-theme-outline" data-filter=".Sofa" data-text="Sofa & Chair">
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
            <!-- Gallery -->
            <div class="portfolio1-isotope -m-[10px] md:-m-[15px]">
                
                <!-- includes/Pages/portfolios-v2.blade.php -->
                @include('includes.Pages.portfolios-v2')

            </div>
        </div>
    </div>
</div>
<!-- Portfolio v2 Area End -->

@include('includes.footer6')
  
@endsection