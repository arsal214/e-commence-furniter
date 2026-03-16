<!-- resources/views/categories.blade.php -->
@extends('layouts.main')

@section('title', 'All Categories')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">All Categories</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Categories</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Categories Grid Start -->
<div class="s-py-100">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">

            <!-- Section Title -->
            <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
                <h3 class="font-medium leading-none text-2xl md:text-3xl">Shop by Category</h3>
                <p class="mt-3 text-paragraph dark:text-white-light">Explore our full range of product categories. Click any category to browse all available products.</p>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                @php
                $fallbackImages = [
                    'assets/img/home-v1/pdct-cgry-01.jpg',
                    'assets/img/home-v1/pdct-cgry-02.jpg',
                    'assets/img/home-v1/pdct-cgry-03.jpg',
                ];
                @endphp

                @forelse ($categories as $index => $category)
                    @php
                        $imgSrc = null;
                        if ($category->image) {
                            $imgSrc = str_starts_with($category->image, 'assets/')
                                ? asset($category->image)
                                : Storage::url($category->image);
                        } else {
                            $imgSrc = asset($fallbackImages[$index % count($fallbackImages)]);
                        }
                    @endphp

                    <a class="relative block overflow-hidden group h-[260px] sm:h-[280px]"
                       href="{{ url('/shop-v1?category=' . $category->slug) }}">

                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             src="{{ $imgSrc }}"
                             alt="{{ $category->name }}">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent duration-300 group-hover:from-black/85"></div>

                        <div class="absolute bottom-0 left-0 w-full px-5 pb-5 pt-8">
                            <span class="text-xs font-medium uppercase tracking-widest text-primary bg-black/40 px-2 py-1 rounded-sm">
                                {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                            </span>
                            <h4 class="text-lg sm:text-xl font-semibold text-white mt-2 leading-tight group-hover:text-primary duration-300">
                                {{ $category->name }}
                            </h4>
                        </div>

                        <div class="absolute top-4 right-4 w-8 h-8 bg-primary flex items-center justify-center opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 duration-300">
                            <svg width="14" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.8198 6.61958L18.3757 1.17541C18.1801 0.947054 17.8364 0.920433 17.608 1.11604C17.3797 1.31161 17.3531 1.65529 17.5487 1.88366C17.5669 1.90494 17.5868 1.92483 17.608 1.94303L22.1212 6.46168L0.567835 6.46168C0.267191 6.46168 0.0234375 6.70543 0.0234375 7.00612C0.0234375 7.30681 0.267191 7.55052 0.567835 7.55052L22.1212 7.55052L17.608 12.0637C17.3797 12.2593 17.3531 12.6029 17.5487 12.8313C17.7443 13.0597 18.0879 13.0863 18.3163 12.8907C18.3376 12.8724 18.3575 12.8526 18.3757 12.8313L23.8198 7.38714C24.0309 7.17488 24.0309 6.83194 23.8198 6.61958Z" fill="white"/>
                            </svg>
                        </div>

                    </a>
                @empty
                    <div class="col-span-4 py-16 text-center text-gray-400">
                        <p class="text-lg">No categories found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Browse All Products CTA -->
            <div class="mt-10 md:mt-14 text-center" data-aos="fade-up">
                <a href="{{ url('/shop-v1') }}" class="btn btn-theme-solid" data-text="Browse All Products">
                    <span>Browse All Products</span>
                </a>
            </div>

        </div>
    </div>
</div>
<!-- Categories Grid End -->

@include('includes.footer')

@endsection
