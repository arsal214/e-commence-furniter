@php
$blogs = [
    [
        'id' => 4,
        'img' => 'assets/img/shortcode/blog/blog-27.jpg', 
        'title' => 'Creating Your Dream Sanctuary: Inspirational Room Decor with Furnixar', 
        'tag' => 'Chair', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'id' => 5,
        'img' => 'assets/img/shortcode/blog/blog-28.jpg', 
        'title' => 'From Drab to Fab: Room Makeover Inspiration by Furnixar', 
        'tag' => 'Sofa', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'id' => 6,
        'img' => 'assets/img/shortcode/blog/blog-29.jpg', 
        'title' => 'Small Space, Big Style: Room Decor Solutions from Furnixar', 
        'tag' => 'Vases', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'id' => 7,
        'img' => 'assets/img/shortcode/blog/blog-22.jpg', 
        'title' => 'Innovative Room Decor: Unleashing Creativity with Furnixar', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'id' => 8,
        'img' => 'assets/img/shortcode/blog/blog-23.jpg', 
        'title' => 'Timeless Elegance: Classic Room Decor Ideas from Furnixar', 
        'tag' => 'Chair', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'id' => 9,
        'img' => 'assets/img/shortcode/blog/blog-24.jpg', 
        'title' => 'Budget-Friendly Brilliance: Room Decor Hacks by Furnixar', 
        'tag' => 'Sofa', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'id' => 10,
        'img' => 'assets/img/shortcode/blog/blog-25.jpg', 
        'title' => 'Personalize Your Space: Custom Room Decor Options with Furnixar', 
        'tag' => 'Vases', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'id' => 11,
        'img' => 'assets/img/shortcode/blog/blog-26.jpg', 
        'title' => 'ransform Your Home: Room Decor Tips and Trends with Furnixar', 
        'tag' => 'Lamp', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
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