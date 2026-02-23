@php
$sliders = [
    [
        'img' => 'assets/img/gallery/shop-03/shop-slider-01.jpg', 
        'title' => "Table & Chair", 
        'name' => '40 Prodcuts', 
    ],
    [
        'img' => 'assets/img/gallery/shop-03/shop-slider-02.jpg', 
        'title' => "Table & Chair", 
        'name' => '40 Prodcuts', 
    ],
    [
        'img' => 'assets/img/gallery/shop-03/shop-slider-03.jpg', 
        'title' => "Table & Chair", 
        'name' => '40 Prodcuts', 
    ],
    [
        'img' => 'assets/img/gallery/shop-03/shop-slider-04.jpg', 
        'title' => "Table & Chair", 
        'name' => '40 Prodcuts', 
    ],
    [
        'img' => 'assets/img/gallery/shop-03/shop-slider-05.jpg', 
        'title' => "Table & Chair", 
        'name' => '40 Prodcuts', 
    ]
];
@endphp

@foreach ($sliders as $item)
    <a href="{{ url('/product-category') }}" class="relative before:absolute before:top-0 before:left-0 before:w-full before:h-full before:bg-title before:bg-opacity-70 group before:opacity-0 before:duration-300 hover:before:opacity-100 overflow-hidden before:z-10 block">
        <img class="w-full object-cover transform duration-300 group-hover:scale-110" src="{{ asset($item['img']) }}" alt="shop">
        <div class="absolute z-20 w-full h-full flex top-0 left-0 flex-col items-center justify-center px-5">
            <h3 class="text-white leading-tight font-semibold transform -translate-y-5 opacity-0 duration-300 group-hover:-translate-y-0 group-hover:opacity-100 text-3xl">{{ $item['title'] }}</h3>
            <span class="text-white leading-none divide-black mt-3 transform translate-y-5 opacity-0 duration-300 group-hover:translate-y-0 group-hover:opacity-100">{{ $item['name'] }}</span>
        </div>
    </a>
@endforeach