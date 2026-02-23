@php
$blogs = [
    [
        'img' => 'assets/img/shortcode/blog/blog-07.jpg', 
        'title' => 'Auctor sit elementum habitant vel tempor varius.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-08.jpg', 
        'title' => 'Consectetur purus habitasse ut diam habitant varius.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-09.jpg', 
        'title' => 'Far far away of furniture of this habitant vel tempor.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
        'desc' => 'Nibh purus integer elementum in. ipsuim for now dolor sit amet of this conqure varius . . .', 
    ]
];
@endphp

@foreach ($blogs as $item)
    <div class="group">
        <div class="overflow-hidden">
            <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset($item['img']) }}" alt="blog-card">
        </div>
        <div class="p-5 relative z-10 before:absolute before:-z-10 before:top-0 before:left-0 before:w-full before:h-full before:bg-secondary dark:before:bg-title before:bg-opacity-10 before:transition-all before:duration-300 overflow-hidden before:opacity-0 group-hover:before:opacity-10">
            <ul class="flex items-center gap-[10px] flex-wrap">
                <li class="text-[15px] leading-none dark:text-white">{{ $item['date'] }}</li>
                <li><a href="#" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-[#dbcbbd]">{{ $item['tag'] }}</a></li>
            </ul>
            <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="#" class="text-underline">{{ $item['title'] }}</a></h5>
            <p class="text-base md:text-lg mt-3 dark:text-white-light">{{ $item['desc'] }} </p>
        </div>
    </div>
@endforeach