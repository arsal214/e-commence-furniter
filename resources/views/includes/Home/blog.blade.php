@php
$blogs = [
    [
        'id' => 1,
        'img' => 'assets/img/shortcode/blog/blog-01.jpg',
        'title' => '10 Must-Have Home Essentials to Upgrade Your Space This Year',
        'tag' => 'Home & Living',
        'date' => '10 Jan, 2026',
    ],
    [
        'id' => 2,
        'img' => 'assets/img/shortcode/blog/blog-02.jpg',
        'title' => 'Top 5 Trending Fashion Styles You Can Shop Right Now',
        'tag' => 'Fashion',
        'date' => '18 Jan, 2026',
    ],
    [
        'id' => 3,
        'img' => 'assets/img/shortcode/blog/blog-03.jpg',
        'title' => 'Best Budget Electronics Picks Under $100 in 2026',
        'tag' => 'Electronics',
        'date' => '25 Jan, 2026',
    ],
    [
        'id' => 4,
        'img' => 'assets/img/shortcode/blog/blog-14.jpg',
        'title' => 'How to Build a Complete Home Gym Without Breaking the Bank',
        'tag' => 'Sports',
        'date' => '2 Feb, 2026',
    ],
    [
        'id' => 5,
        'img' => 'assets/img/shortcode/blog/blog-15.jpg',
        'title' => 'Skincare Essentials: The Products Worth Investing In',
        'tag' => 'Beauty',
        'date' => '9 Feb, 2026',
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog">
        </a>
        <div class="text-center mt-4 px-3">
            <ul class="flex items-center justify-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-2 px-[10px] rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="text-xl mt-3 font-medium dark:text-white leading-[1.5]"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }} </a></h5>
        </div>
    </div>
@endforeach