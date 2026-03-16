@php
$blogs = [
    [
        'img' => 'assets/img/shortcode/blog/blog-07.jpg',
        'title' => 'How to Choose the Right Laptop for Work and Study in 2026',
        'tag' => 'Electronics',
        'date' => '12 Jan, 2026',
        'desc' => 'With so many options on the market, picking the right laptop can feel overwhelming. We break down the key specs and features to look for when shopping for your next device...',
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-08.jpg',
        'title' => '5 Ways to Transform Your Home With Simple Decor Upgrades',
        'tag' => 'Home & Living',
        'date' => '20 Jan, 2026',
        'desc' => 'You do not need a major renovation to make your home feel brand new. From statement pieces to clever storage solutions, discover easy ways to upgrade your space on a budget...',
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-09.jpg',
        'title' => 'The Best Athletic Wear to Level Up Your Workout in 2026',
        'tag' => 'Sports',
        'date' => '28 Jan, 2026',
        'desc' => 'Whether you are hitting the gym, running outdoors, or doing yoga at home, the right athletic wear makes a difference. Explore our top picks for performance and style...',
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <div class="overflow-hidden">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog">
        </div>
        <div class="pt-5 md:pt-6">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="{{ url('/blog-tag') }}" class="inline-block text-title font-medium text-[15px] leading-none py-2 px-[10px] rounded-md bg-primary-midum">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="{{ url('/blog-v1') }}" class="text-underline">{{ $item['title'] }}</a></h5>
            <p class="mt-3 text-base leading-relaxed">{{ $item['desc'] }}</p>
        </div>
    </div>
@endforeach
