@php
$blogs = [
    [
        'id' => 12,
        'img' => 'assets/img/shortcode/blog/blog-07.jpg', 
        'title' => 'Maximizing Small Spaces: Smart Furniture Solutions', 
        'desc' => 'The finest collections from top furniture brands known for quality, style, and durability. . .', 
        'tag' => 'Living room', 
        'date' => '12 Jan, 2025', 
    ],
    [
        'id' => 13,
        'img' => 'assets/img/shortcode/blog/blog-08.jpg', 
        'title' => 'The Ultimate Guide to Styling Your Living Room Furniture', 
        'desc' => 'Shop from top brands and enjoy exclusive discounts on timeless designs. Elevate your living . . .', 
        'tag' => 'Table & Chair', 
        'date' => '23 Feb, 2025', 
    ],
    [
        'id' => 14,
        'img' => 'assets/img/shortcode/blog/blog-09.jpg', 
        'title' => 'Top Furniture Trends That Will Dominate This Year', 
        'desc' => 'Explore trending designs, space-saving solutions, and smart shopping advice! . . .', 
        'tag' => 'Interior', 
        'date' => '27 Jan, 2025', 
    ],
    [
        'id' => 15,
        'img' => 'assets/img/home-v4/blog.jpg', 
        'title' => 'How to Choose Furniture That Matches Your Interior Design', 
        'desc' => 'Discover expert tips and creative ideas to select and style furniture that elevates your home. . .', 
        'tag' => 'Sofa', 
        'date' => '29 Jan, 2025', 
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="p-5 relative z-10 before:absolute before:-z-10 before:top-0 before:left-0 before:w-full before:h-full before:bg-secondary dark:before:bg-dark-secondary before:bg-opacity-10 before:transition-all before:duration-300 overflow-hidden before:opacity-0 group-hover:before:opacity-10 group-hover:dark:before:opacity-100">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium text-xl dark:text-white leading-[1.5]"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }}</a></h5>
            <p class="text-base md:text-lg mt-3 dark:text-white-light">{{ $item['desc'] }} </p>
        </div>
    </div>
@endforeach