@php
$blogs = [
    [
        'id' => 6,
        'img' => 'assets/img/home-v2/blog-03.jpg', 
        'title' => 'How to Choose the Perfect Furniture for Every Room in Your Home', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 7,
        'img' => 'assets/img/home-v2/blog-01.jpg', 
        'title' => '10 Furniture Shopping Tips for a Seamless Online Experience', 
        'tag' => 'Bedroom', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 8,
        'img' => 'assets/img/home-v2/blog-02.jpg', 
        'title' => 'Sustainable Furniture: Eco-Friendly Choices for a Greener Home', 
        'tag' => 'Sofa', 
        'date' => '6 Sep, 2025', 
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="relative group">
        <a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="sm:bg-white sm:bg-opacity-90 sm:dark:bg-title sm:dark:bg-opacity-90 mt-4 sm:p-5 md:p-6 sm:absolute z-10 bottom-0 left-0 sm:w-11/12 max-w-md px-5 sm:px-0 ">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }}</a></h5>
        </div>
    </div>
@endforeach