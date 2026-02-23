@php
$blogs = [
    [
        'id' => 1,
        'img' => 'assets/img/shortcode/blog/blog-01.jpg', 
        'title' => 'Auctor sit elementum habitant vel tempor varius.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 2,
        'img' => 'assets/img/shortcode/blog/blog-02.jpg', 
        'title' => 'Consectetur purus habitasse ut diam habitant varius.', 
        'tag' => 'Chair', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 3,
        'img' => 'assets/img/shortcode/blog/blog-03.jpg', 
        'title' => 'Far far away of furniture of this habitant vel tempor.', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 26,
        'img' => 'assets/img/shortcode/blog/blog-14.jpg', 
        'title' => 'Good Ideas to Update your Living Room.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 27,
        'img' => 'assets/img/shortcode/blog/blog-15.jpg', 
        'title' => 'Tips and Tricks to Avoid the Stress of Clutter.', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 28,
        'img' => 'assets/img/shortcode/blog/blog-16.jpg', 
        'title' => "Name Brand Children's Bedroom Furniture Built to Last.", 
        'tag' => 'Sofa', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 29,
        'img' => 'assets/img/shortcode/blog/blog-17.jpg', 
        'title' => 'Stop Worrying About Deadlines! We Got You Covered', 
        'tag' => 'Chair', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 30,
        'img' => 'assets/img/shortcode/blog/blog-18.jpg', 
        'title' => 'Change Your Strategy: Find a Business Consultant', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 31,
        'img' => 'assets/img/shortcode/blog/blog-19.jpg', 
        'title' => 'How to Make a Small Bedroom Look Bigger.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'id' => 32,
        'img' => 'assets/img/shortcode/blog/blog-20.jpg', 
        'title' => '6 Tips to Warm Up Your Gray and White Decor.', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
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