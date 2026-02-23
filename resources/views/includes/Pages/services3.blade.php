@php
$services = [
    [
        'img' => 'assets/img/svg/car.svg', 
        'title' => "Free Shipping", 
        'class' => "", 
        'style' => "", 
        'desc' => "Enjoy hassle-free shopping with complimentary shipping on all orders. Elevate your experience without the extra cost.", 
    ],
    [
        'img' => 'assets/img/svg/box.svg', 
        'title' => "Easy to Return", 
        'class' => "xl:max-w-[290px] w-full 2xl:flex items-center justify-between gap-7", 
        'style' => "w-[1px] h-[120px] border-l border-dashed border-primary hidden 2xl:block", 
        'desc' => "Satisfaction guaranteed or your money back! Enjoy stress-free returns with our hassle-free process.", 
    ],
    [
        'img' => 'assets/img/svg/card.svg', 
        'title' => "Secure Payment", 
        'class' => "xl:max-w-[290px] w-full 2xl:flex items-center justify-between gap-7", 
        'style' => "w-[1px] h-[120px] border-l border-dashed border-primary hidden 2xl:block", 
        'desc' => "Shop with confidence knowing your payments are secure. Our encrypted checkout ensures your information stays protected.", 
    ],
    [
        'img' => 'assets/img/svg/support.svg', 
        'title' => "Customer Support", 
        'class' => "xl:max-w-[290px] w-full 2xl:flex items-center justify-between gap-7", 
        'style' => "w-[1px] h-[120px] border-l border-dashed border-primary hidden 2xl:block", 
        'desc' => "Experience dedicated support tailored to your needs. Our team is here to assist you every step of the way.", 
    ],
    [
        'img' => 'assets/img/svg/award.svg', 
        'title' => "Product QC Team", 
        'class' => "xl:max-w-[290px] w-full 2xl:flex items-center justify-between gap-7", 
        'style' => "w-[1px] h-[120px] border-l border-dashed border-primary hidden 2xl:block", 
        'desc' => "Our meticulous product QC team ensures every item meets our highest standards. Trust in quality assurance that goes beyond expectation.", 
    ],
];
@endphp

@foreach ($services as $item)
    <div class="{{ $item['class'] }}">
        <div class="{{ $item['style'] }}"></div>
        <div class="text-center sm:text-left xl:max-w-[205px] w-full">
            <img src="{{ asset($item['img']) }}" class="w-12 h-12" alt="">
            <h5 class="font-semibold text-xl md:text-2xl mt-3 md:mt-7">{{ $item['title'] }}</h5>
            <p class="md:text-lg mt-2 sm:mt-3">{{ $item['desc'] }} </p>
        </div>
    </div>
@endforeach