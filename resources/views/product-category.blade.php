<!-- resources/views/product-category.blade.php -->
@extends('layouts.main')

@section('title', 'All Products | PeytonGhalib')
@section('meta_description', 'Browse our full product catalogue at PeytonGhalib. Furniture, home decor, ceramics and lifestyle products with fast delivery.')

@push('schema')
@php
$pcListItems = [];
foreach ($products as $i => $p) {
    $pImg = !empty($p->image)
        ? (str_starts_with($p->image, 'assets/') ? asset($p->image) : \Storage::url($p->image))
        : asset('assets/img/logo.svg');
    $pcListItems[] = [
        '@type'    => 'ListItem',
        'position' => $products->firstItem() + $i,
        'name'     => $p->name,
        'url'      => route('product-details', $p->slug),
        'image'    => $pImg,
    ];
}

$schemaPc = [
    '@context'   => 'https://schema.org',
    '@type'      => 'CollectionPage',
    'name'       => 'All Products — PeytonGhalib',
    'description'=> 'Browse our full product catalogue at PeytonGhalib.',
    'url'        => url()->current(),
    'breadcrumb' => [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>url('/')],
            ['@type'=>'ListItem','position'=>2,'name'=>'Products','item'=>url()->current()],
        ],
    ],
];

$schemaPcList = [
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'All Products — PeytonGhalib',
    'itemListElement' => $pcListItems,
];
@endphp
<script type="application/ld+json">{!! json_encode($schemaPc, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($schemaPcList, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Room Interior</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li><a href="{{ url('/shop') }}">Shop</a></li>
            <li>/</li>
            <li class="text-primary">Room Interior</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Shop Start -->
<div class="s-py-100" >
    <div class="container-fluid">
        <!-- Shopping Card -->
        <div data-aos="fade-up" data-aos-delay="200">
            <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                
                <!-- includes/Shop/room-interior.blade.php -->
                @include('includes.Shop.room-interior')

            </div>
            <!-- Pagination -->
            <div class="mt-10 md:mt-12 flex items-center justify-center gap-[10px]">
                <a href="#" class="text-title dark:text-white text-xl"><span class="lnr lnr-arrow-left"></span></a>         
                <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">01</a>        
                <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">02</a>        
                <a href="#" class="text-title dark:text-white text-3xl sm:text-4xl transform">...</a>          
                <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">09</a>        
                <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">10</a>        
                        
                <a href="#" class="text-title dark:text-white text-xl"><span class="lnr lnr-arrow-right"></span></a>         
            </div>
        </div>
    </div>
</div>
<!-- Shop End -->

{{-- Static demo quick-view removed (dummy 'Classic Relaxable Chair'). --}}

@include('includes.footer')
  
@endsection