@php
$blogs = [
    [
        'img' => 'assets/img/shortcode/blog/blog-10.jpg', 
        'title' => 'Auctor sit elementum lorem ipsum dolor sit habitant vel tempor varius.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-11.jpg', 
        'title' => 'Consectetur from purus habitasse of this new furniture and element.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <div class="p-[15px] bg-[#F3F3F3] transition-all duration-300 group-hover:bg-opacity-0">
            <img class="duration-200 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </div>
        <div class="pt-5 md:pt-6 md:px-4 text-center">
            <ul class="text-[15px] font-medium flex items-center justify-center gap-2 md:gap-4">
                <li class="dark:text-white">{{ $item['date'] }}</li>
                <li class="w-1 h-1 rounded-full bg-[#bb976d] relative"></li>
                <li><a class="text-secondary" href="#">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="#" class="text-underline">{{ $item['title'] }} </a></h5>
            <div class="mt-5 md:mt-6">
                <a class="font-medium text-base text-title md:text-[17px] leading-none py-[15px] px-5 relative z-10 before:absolute before:bottom-0 before:left-0 before:bg-primary-midum before:w-full before:h-2/4 before:-z-10 blog-btn-hover before:transition-all before:duration-300 dark:text-white inline-block" href="#">Read More</a>
            </div>
        </div>
    </div>
@endforeach