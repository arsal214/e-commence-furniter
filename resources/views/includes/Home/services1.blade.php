@php
$services = [
    [
        'img' => 'assets/img/svg/sofa.svg', 
        'title' => "Luxury Sofa Set", 
        'product' => "26 Products", 
        'desc' => "Elevate your living space with unparalleled comfort and style. Discover the epitome of luxury with our exquisite sofa sets. ", 
    ],
    [
        'img' => 'assets/img/svg/chair.svg', 
        'title' => "Table & Chair", 
        'product' => "40 Products", 
        'desc' => "Transform your dining area into a haven of elegance and functionality. Explore our versatile collection of tables and chairs. ", 
    ],
    [
        'img' => 'assets/img/svg/vase.svg', 
        'title' => "Vases & Lamp", 
        'product' => "14 Products", 
        'desc' => "Elevate your home decor with our stunning vases and lamps. Add a touch of sophistication and warmth to any room. ", 
    ],
    [
        'img' => 'assets/img/svg/lamp.svg', 
        'title' => "Interior Collection", 
        'product' => "32 Products", 
        'desc' => "Create a space that reflects your style and personality. Explore our curated interior collection for timeless pieces that inspire. ", 
    ],
];
@endphp

@foreach ($services as $item)
    <div class="text-center sm:text-left group" data-aos="fade-up">
        <div class="w-14 sm:w-[70px] h-14 sm:h-[70px] p-[10px] mx-auto sm:mx-0 flex items-center justify-center border border-primary rounded-[10px] border-[#bb976d]">
            <img src="{{ asset($item['img']) }}" alt="">           
        </div>
        <h4 class="mt-6 font-semibold leading-none text-xl lg:text-2xl"><a href="{{ url('/product-category') }}" class="text-underline">{{ $item['title'] }}</a></h4>
        <span class="block leading-none mt-[10px]">{{ $item['product'] }}</span>
        <p class="mt-[15px]">
            {{ $item['desc'] }} 
        </p>
    </div>
@endforeach