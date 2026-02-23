@php
$blogs = [
    [
        'id' => 19,
        'img' => 'assets/img/shortcode/blog/blog-10.jpg', 
        'title' => 'The Art of Interior Design: Choosing the Furniture Online', 
        'tag' => 'Home Decor', 
        'date' => '25 Jan, 2025', 
    ],
    [
        'id' => 20,
        'img' => 'assets/img/shortcode/blog/blog-11.jpg', 
        'title' => 'Transform Your Space: Top Furniture Trends for Homes', 
        'tag' => 'Interior', 
        'date' => '20 Jan, 2025', 
    ],
    [
        'id' => 21,
        'img' => 'assets/img/home-v6/blog.jpg', 
        'title' => 'How to Choose the Perfect Furniture for Every Room in Home', 
        'tag' => 'Interior', 
        'date' => '28 Jan, 2025', 
    ],
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="p-[10px] sm:p-[15px] bg-[#F3F3F3] transition-all duration-300 group-hover:bg-opacity-0 overflow-hidden block">
            <img class="duration-200 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="pt-5 md:pt-6 md:px-4 text-center">
            <ul class="text-[15px] font-medium flex items-center justify-center gap-2 md:gap-4">
                <li class="dark:text-white leading-none">{{ $item['date'] }}</li>
                <li class="w-1 h-1 rounded-full bg-[#bb976d] relative"></li>
                <li class="leading-none"><a class="text-secondary leading-none" href="{{ url('/blog-tag') }}">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 text-xl font-medium dark:text-white leading-[1.5]"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }} </a></h5>
            <div class="mt-5 md:mt-6">
                <a class="font-medium text-base text-title md:text-[17px] leading-none py-[15px] px-5 relative z-10 before:absolute before:bottom-0 before:left-0 before:bg-primary-midum before:w-full before:h-2/4 before:-z-10 blog-btn-hover before:transition-all before:duration-300 dark:text-white inline-block" href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}">Read More</a>
            </div>
        </div>
    </div>
@endforeach