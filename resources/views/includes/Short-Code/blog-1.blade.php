@php
$blogs = [
    [
        'img' => 'assets/img/shortcode/blog/blog-01.jpg', 
        'title' => '10 Must-Have Home Essentials to Upgrade Your Space This Year',
        'tag' => 'Home & Living',
        'date' => '10 Jan, 2026',
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-02.jpg', 
        'title' => 'The Ultimate Guide to Smart Shopping: Save More Every Time',
        'tag' => 'Shopping Tips',
        'date' => '18 Jan, 2026',
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-03.jpg', 
        'title' => 'Top 7 Fashion Trends You Need to Know About This Season',
        'tag' => 'Fashion',
        'date' => '25 Jan, 2026',
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <div class="overflow-hidden">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog">
        </div>
        <div class="text-center mt-4 px-3">
            <ul class="flex items-center justify-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="#" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium leading-snug dark:text-white text-xl"><a href="#" class="text-underline">{{ $item['title'] }} </a></h5>
        </div>
    </div>
@endforeach