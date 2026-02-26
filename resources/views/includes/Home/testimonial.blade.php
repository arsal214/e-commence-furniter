@php
$testimonials = [
    [
        'img' => 'assets/img/testimonial/tmnl-02.jpg', 
        'name' => 'Jennifer Smith', 
        'title' => "Berminghum ,UK", 
        'desc' => "PeytonGhalib exceeded my expectations with their exceptional furniture pieces. The quality craftsmanship and attention to detail truly shine through in every product. My home has been transformed into a stylish sanctuary thanks to PeytonGhalib!", 
    ],
    [
        'img' => 'assets/img/testimonial/tmnl-03.jpg', 
        'name' => 'Jackyer Smith', 
        'title' => "Berminghum ,UK", 
        'desc' => "PeytonGhalib exceeded my expectations with its exceptional furniture pieces. The quality craftsmanship and attention to detail truly shine through in every product. My home has been transformed into a stylish sanctuary thanks to PeytonGhalib!", 
    ],
];
@endphp

@foreach ($testimonials as $item)
    <div class="text-center">
        <h6 class="dark:text-white italic font-normal text-lg">{{ $item['desc'] }} </h6>
        <div class="flex items-center justify-center gap-3 mt-6 author">
            <div class="w-11 h-11 rounded-full overflow-hidden p-1 bg-[#bb976d]">
                <img class="rounded-full" src="{{ asset($item['img']) }}" alt="testimonial">
            </div>
            <div class="text-left">
                <h5 class="dark:text-white font-medium leading-none text-xl">{{ $item['name'] }}</h5>
                <span class="block text-[14px] leading-none text-[#bb976d] mt-[5px]">{{ $item['title'] }}</span>
            </div>
        </div>
    </div>
@endforeach