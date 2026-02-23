@php
$blogs = [
    [
        'id' => 22,
        'img' => 'assets/img/shortcode/blog/blog-04.jpg', 
        'title' => 'Design your apps in your own way', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 23,
        'img' => 'assets/img/shortcode/blog/blog-12.jpg', 
        'title' => 'How apps is changing the IT world', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 24,
        'img' => 'assets/img/shortcode/blog/blog-13.jpg', 
        'title' => 'The Key Components of a Quality Sofa.', 
        'tag' => 'Sofa', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 25,
        'img' => 'assets/img/shortcode/blog/blog-12.jpg', 
        'title' => 'Smartest Applications for Business', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="relative group">
        <a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="sm:bg-white sm:bg-opacity-90 sm:dark:bg-title sm:dark:bg-opacity-90 mt-4 sm:p-5 md:p-6 sm:absolute z-10 bottom-0 left-0 sm:w-11/12 max-w-md ">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-[#dbcbbd]">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }} </a></h5>
        </div>
    </div>
@endforeach