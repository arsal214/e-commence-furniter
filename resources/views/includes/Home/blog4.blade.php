@php
$blogs = [
    [
        'id' => 16,
        'img' => 'assets/img/home-v5/blog-01.jpg', 
        'title' => 'Home Office Storage Ideas to Boost Productivity in 2025.', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 17,
        'img' => 'assets/img/home-v5/blog-02.jpg', 
        'title' => 'The Consectetur purus habitasse ut diam habitant varius.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 18,
        'img' => 'assets/img/home-v5/blog-03.jpg', 
        'title' => 'The Key Components of a Quality Sofa habitant vel tempor.', 
        'tag' => 'Sofa', 
        'date' => '6 Sep, 2025', 
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="relative group mx-[15px] sm:mx-0">
        <a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="bg-white bg-opacity-90 dark:bg-title dark:bg-opacity-90 p-5 md:p-6 absolute z-10 w-11/12 max-w-md transform left-[96%] top-[92%] md:left-[80%] md:top-[64%] -translate-x-1/2 -translate-y-1/2">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }}</a></h5>
        </div>
    </div>
@endforeach