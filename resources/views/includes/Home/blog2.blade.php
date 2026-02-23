@php
$blogs = [
    [
        'id' => 9,
        'img' => 'assets/img/shortcode/blog/blog-05.jpg', 
        'title' => 'Transform Your Space: Top Furniture Trends for Modern Homes', 
        'tag' => 'Interior', 
        'date' => '27 Jan, 2025', 
    ],
    [
        'id' => 10,
        'img' => 'assets/img/shortcode/blog/blog-06.jpg', 
        'title' => 'The Art of Interior Design: Choosing the Perfect Furniture Online', 
        'tag' => 'Bedroom', 
        'date' => '20 Jan, 2025', 
    ],
    [
        'id' => 11,
        'img' => 'assets/img/home-v3/blog.jpg', 
        'title' => '5 Must-Have Features for an Outstanding Furniture', 
        'tag' => 'Interior', 
        'date' => '25 Jan, 2025', 
    ],
];
@endphp

@foreach ($blogs as $item)
    <div class="group sm:flex items-center">
        <a href="#" class="sm:w-1/2 overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="bg-snow dark:bg-dark-secondary p-5 md:p-6 sm:ml-[-10%] sm:w-[60%] relative z-10">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-2 px-[10px] rounded-md bg-primary-midum bg-[#dbcbbd]">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium text-xl dark:text-white leading-[1.5]"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }}</a></h5>
        </div>
    </div>
@endforeach