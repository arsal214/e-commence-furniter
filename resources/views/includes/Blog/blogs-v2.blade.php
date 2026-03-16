@php
$blogs = [
    [
        'id' => 4,
        'img' => 'assets/img/shortcode/blog/blog-27.jpg',
        'title' => 'Creating Your Dream Sanctuary: Home Decor Inspiration from PeytonGhalib',
        'tag' => 'Home & Living',
        'date' => '5 Jan, 2026',
        'desc' => 'Transform any room into a haven of comfort and style. Discover our curated home decor picks that blend elegance with everyday practicality.',
    ],
    [
        'id' => 5,
        'img' => 'assets/img/shortcode/blog/blog-28.jpg',
        'title' => 'From Drab to Fab: Room Makeover Ideas on Any Budget',
        'tag' => 'Home & Living',
        'date' => '12 Jan, 2026',
        'desc' => 'You don\'t need to spend a fortune to make your home look brand new. Explore budget-friendly makeover ideas that deliver maximum impact.',
    ],
    [
        'id' => 6,
        'img' => 'assets/img/shortcode/blog/blog-29.jpg',
        'title' => 'Small Space, Big Style: Smart Storage & Decor Solutions',
        'tag' => 'Home & Living',
        'date' => '19 Jan, 2026',
        'desc' => 'Living in a compact space doesn\'t mean compromising on style. Discover clever storage and decor solutions to maximise every inch of your home.',
    ],
    [
        'id' => 7,
        'img' => 'assets/img/shortcode/blog/blog-22.jpg',
        'title' => 'How to Build a Capsule Wardrobe That Works All Year Round',
        'tag' => 'Fashion',
        'date' => '26 Jan, 2026',
        'desc' => 'A capsule wardrobe saves you time, money, and stress. Learn which versatile fashion essentials to invest in for a polished look every season.',
    ],
    [
        'id' => 8,
        'img' => 'assets/img/shortcode/blog/blog-23.jpg',
        'title' => 'The Best Skincare Products for Every Budget in 2026',
        'tag' => 'Beauty',
        'date' => '2 Feb, 2026',
        'desc' => 'Great skincare doesn\'t have to break the bank. We round up the top-performing products at every price point to keep your skin looking its best.',
    ],
    [
        'id' => 9,
        'img' => 'assets/img/shortcode/blog/blog-24.jpg',
        'title' => 'Top 5 Fitness Gadgets to Supercharge Your Workouts',
        'tag' => 'Sports',
        'date' => '9 Feb, 2026',
        'desc' => 'Whether you\'re a gym regular or a home workout enthusiast, these must-have fitness gadgets will help you train smarter and reach your goals faster.',
    ],
    [
        'id' => 10,
        'img' => 'assets/img/shortcode/blog/blog-25.jpg',
        'title' => 'Choosing the Right Smartphone: What to Look for in 2026',
        'tag' => 'Electronics',
        'date' => '16 Feb, 2026',
        'desc' => 'With hundreds of models on the market, choosing a smartphone can be overwhelming. Our guide breaks down the key specs to help you find the perfect match.',
    ],
    [
        'id' => 11,
        'img' => 'assets/img/shortcode/blog/blog-26.jpg',
        'title' => 'Transform Your Home: Trending Decor Styles to Try This Year',
        'tag' => 'Home & Living',
        'date' => '23 Feb, 2026',
        'desc' => 'From minimalist Scandinavian aesthetics to bold maximalist palettes, explore the hottest interior design trends making waves in 2026.',
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <a href="{{ route('blog-details-v2', ['title' => Str::slug($item['title'])]) }}" class="overflow-hidden block">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </a>
        <div class="p-5 relative z-10 before:absolute before:-z-10 before:top-0 before:left-0 before:w-full before:h-full before:bg-secondary dark:before:bg-dark-secondary before:transition-all before:duration-300 overflow-hidden before:opacity-0 group-hover:before:opacity-10 dark:group-hover:before:opacity-100">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md group-hover:bg-primary group-hover:text-white duration-300 bg-[#dbcbbd]">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ route('blog-details-v2', ['title' => Str::slug($item['title'])]) }}" class="text-underline">{{ $item['title'] }} </a></h5>
            <p class="text-base md:text-lg mt-3 dark:text-white-light">{{ $item['desc'] }} </p>
        </div>
    </div>
@endforeach