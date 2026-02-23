@php
$testimonials = [
    [
        'img' => 'assets/img/testimonial/tmnl-02.jpg', 
        'name' => 'Jennifer Smith', 
        'title' => 'Berminghum ,UK', 
        'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Non, lobortis in in tortor lectus elementum. Nibh purus integer elementum in. Tellus  habitant vel tempor lorem ipsuim dolor sit amet of this conqure varius.', 
    ],
    [
        'img' => 'assets/img/testimonial/tmnl-03.jpg', 
        'name' => 'Jackyer Smith', 
        'title' => 'Berminghum ,UK', 
        'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Non, lobortis in in tortor lectus elementum. Nibh purus integer elementum in. Tellus  habitant vel tempor lorem ipsuim dolor sit amet of this conqure varius.', 
    ]
];
@endphp

@foreach ($testimonials as $item)
    <div class="text-center">
        <h6 class="dark:text-white italic font-normal text-lg">{{ $item['desc'] }} </h6>
        <div class="flex items-center justify-center gap-3 mt-6 author">
            <div class="w-11 h-11 rounded-full overflow-hidden p-1 bg-[#85A3B4]">
                <img class="rounded-full" src="{{ asset($item['img']) }}" alt="testimonial">
            </div>
            <div class="text-left">
                <h5 class="dark:text-white font-medium leading-none text-xl">{{ $item['name'] }}</h5>
                <span class="block text-[14px] leading-none text-[#bb976d] mt-[5px]">{{ $item['title'] }}</span>
            </div>
        </div>
    </div>
@endforeach