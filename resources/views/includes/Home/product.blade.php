@php
$products = [
    [
        'img' => 'assets/img/home-v5/pdct-01.jpg', 
        'title' => 'Luxury Sofa Set', 
        'name' => "26 Products", 
    ],
    [
        'img' => 'assets/img/home-v5/pdct-02.jpg', 
        'title' => 'Retro Style Vase', 
        'name' => "40 Products", 
    ],
    [
        'img' => 'assets/img/home-v5/pdct-03.jpg', 
        'title' => 'Table & Chair', 
        'name' => "14 Products", 
    ],
    [
        'img' => 'assets/img/home-v5/pdct-04.jpg', 
        'title' => 'Luxury Vase', 
        'name' => "32 Products", 
    ],
    [
        'img' => 'assets/img/home-v5/pdct-05.jpg', 
        'title' => 'Interior Element', 
        'name' => "26 Products", 
    ],
    [
        'img' => 'assets/img/home-v5/pdct-06.jpg', 
        'title' => 'Others', 
        'name' => "66 Products", 
    ]
];
@endphp

@foreach ($products as $item)
    <a href="{{ url('/product-category') }}" class="relative group overflow-hidden">
        <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="product">
        <div class="absolute bottom-5 sm:bottom-8 lg:bottom-12 w-full left-0 px-7 flex justify-center">
            <div class=" bg-white bg-opacity-80 dark:bg-title dark:bg-opacity-80 p-5 z-10">
                <h4 class="font-semibold leading-[1.5] text-2xl">{{ $item['title'] }}</h4>
                <p class="leading-none mt-[10px]">{{ $item['name'] }}</p>
            </div>
        </div>
    </a>
@endforeach