@php
$blogs = [
    [
        'id' => 1,
        'img' => 'assets/img/shortcode/blog/blog-05.jpg', 
        'title' => 'The Key Components of a Quality Sofa habitant vel tempor varius.', 
        'tag' => 'Sofa', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 2,
        'img' => 'assets/img/shortcode/blog/blog-06.jpg', 
        'title' => 'Elevate Your Space: 10 Stunning Room Decor Ideas from Furnixar', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 3,
        'img' => 'assets/img/shortcode/blog/blog-21.jpg', 
        'title' => 'Transform Your Home: Room Decor Tips and Trends with Furnixar', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group sm:flex items-center">
        <a href="{{ route('blog-details-v2', ['title' => Str::slug($item['title'])]) }}" class="sm:w-1/2 overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="bg-snow dark:bg-title p-5 md:p-6 sm:ml-[-10%] sm:w-[60%] relative z-10">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md group-hover:bg-primary group-hover:text-white duration-300 bg-[#dbcbbd]">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog-details-v2', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }} </a></h5>
        </div>
    </div>
@endforeach