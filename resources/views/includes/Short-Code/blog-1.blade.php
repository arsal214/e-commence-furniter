@php
$blogs = [
    [
        'img' => 'assets/img/shortcode/blog/blog-01.jpg', 
        'title' => 'Auctor sit elementum habitant vel tempor varius.', 
        'tag' => 'Interior', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-02.jpg', 
        'title' => 'Consectetur purus habitasse ut diam habitant varius.', 
        'tag' => 'Chair', 
        'date' => '6 Sep, 2025', 
    ],
    [
        'img' => 'assets/img/shortcode/blog/blog-03.jpg', 
        'title' => 'Far far away of furniture of this habitant vel tempor.', 
        'tag' => 'Vase', 
        'date' => '6 Sep, 2025', 
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