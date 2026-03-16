@php
$blogs = [
    [
        'id' => 1,
        'img' => 'assets/img/shortcode/blog/blog-01.jpg', 
        'title' => 'How to Choose the Right Electronics for Your Lifestyle in 2026',
        'tag' => 'Electronics',
        'date' => '10 Jan, 2026',
    ],
    [
        'id' => 2,
        'img' => 'assets/img/shortcode/blog/blog-02.jpg', 
        'title' => 'The Ultimate Guide to Smart Shopping: Save More Every Time',
        'tag' => 'Shopping Tips',
        'date' => '18 Jan, 2026',
    ],
    [
        'id' => 3,
        'img' => 'assets/img/shortcode/blog/blog-03.jpg', 
        'title' => 'Top 7 Fashion Trends You Need to Know About This Season',
        'tag' => 'Fashion',
        'date' => '25 Jan, 2026',
    ],
    [
        'id' => 26,
        'img' => 'assets/img/shortcode/blog/blog-14.jpg', 
        'title' => 'Good Ideas to Refresh Your Living Space on a Budget',
        'tag' => 'Home & Living',
        'date' => '2 Feb, 2026',
    ],
    [
        'id' => 27,
        'img' => 'assets/img/shortcode/blog/blog-15.jpg', 
        'title' => 'Declutter Your Home: Essential Products That Help You Stay Organised',
        'tag' => 'Home & Living',
        'date' => '9 Feb, 2026',
    ],
    [
        'id' => 28,
        'img' => 'assets/img/shortcode/blog/blog-16.jpg', 
        'title' => "Best Kids' Products for a Safe and Fun Home Environment",
        'tag' => 'Kids & Baby',
        'date' => '16 Feb, 2026',
    ],
    [
        'id' => 29,
        'img' => 'assets/img/shortcode/blog/blog-17.jpg', 
        'title' => 'How to Build a Productive Home Office Without Overspending',
        'tag' => 'Electronics',
        'date' => '23 Feb, 2026',
    ],
    [
        'id' => 30,
        'img' => 'assets/img/shortcode/blog/blog-18.jpg', 
        'title' => 'Our New Spring Collection Is Here: What to Look Out For',
        'tag' => 'Fashion',
        'date' => '2 Mar, 2026',
    ],
    [
        'id' => 31,
        'img' => 'assets/img/shortcode/blog/blog-19.jpg', 
        'title' => 'The Best Sports Gear for Beginners Starting Their Fitness Journey',
        'tag' => 'Sports',
        'date' => '9 Mar, 2026',
    ],
    [
        'id' => 32,
        'img' => 'assets/img/shortcode/blog/blog-20.jpg', 
        'title' => 'Your Complete Guide to Skincare: What Works and Why',
        'tag' => 'Beauty',
        'date' => '16 Mar, 2026',
    ],
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
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog-details-v1', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }} </a></h5>
        </div>
    </div>
@endforeach