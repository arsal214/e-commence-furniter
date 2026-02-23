@php
$wishlists = [
    [
        'id' => 11,
        'img' => 'assets/img/shortcode/product-card/pdct-01.jpg', 
        'price' => '$122.75', 
        'title' => 'Premium Chair and Vase', 
        'rate' => '( 1,230 )', 
        'span' => 'false', 
        'data' => '100', 
    ],
    [
        'id' => 12,
        'img' => 'assets/img/shortcode/product-card/pdct-02.jpg', 
        'price' => '$122.75', 
        'title' => 'Modern Fashionable Sofa', 
        'rate' => '( 1,230 )', 
        'span' => 'false', 
        'data' => '300', 
    ],
    [
        'id' => 13,
        'img' => 'assets/img/shortcode/product-card/pdct-03.jpg', 
        'price' => '$122.75', 
        'title' => 'Vintage Table  With Vase', 
        'rate' => '( 1,230 )', 
        'span' => 'true', 
        'data' => '500', 
    ],
    [
        'id' => 49,
        'img' => 'assets/img/shortcode/product-card/pdct-04.jpg', 
        'price' => '$122.75', 
        'title' => 'The Premium Chair and Vase', 
        'rate' => '( 1,230 )', 
        'span' => 'false', 
        'data' => '100', 
    ],
    [
        'id' => 50,
        'img' => 'assets/img/shortcode/product-card/pdct-05.jpg', 
        'price' => '$122.75', 
        'title' => 'Card Fashionable Sofa', 
        'rate' => '( 1,230 )', 
        'span' => 'false', 
        'data' => '300', 
    ],
    [
        'id' => 51,
        'img' => 'assets/img/shortcode/product-card/pdct-06.jpg', 
        'price' => '$122.75', 
        'title' => 'Vintage Vase', 
        'rate' => '( 1,230 )', 
        'span' => 'true', 
        'data' => '500', 
    ],
];
@endphp

@foreach ($wishlists as $item)
    <div class="group" data-aos="fade-up" data-aos-delay="{{ $item['data'] }}">
        <div class="relative overflow-hidden group z-[5] before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-title before:opacity-0 before:duration-300 before:z-[5] hover:before:opacity-80">
            <img class="w-full transform duration-300 group-hover:scale-110" src="{{ asset($item['img']) }}" alt="product-card">

            <div class="absolute z-10 top-[57%] left-[65%] transform -translate-y-2/4 -translate-x-2/4 flex gap-2">
                <a href="{{ url('/cart') }}" class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-10 flex items-center justify-center transform translate-y-8 opacity-0 transition-all group-hover:duration-500 group-hover:opacity-100 group-hover:translate-y-0 relative tooltip-icon">
                    <svg class="text-white fill-current" width="20" height="24" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.8186 5.5949H15.231C14.8937 2.72995 12.451 0.5 9.49699 0.5C6.54292 0.5 4.10026 2.72995 3.76293 5.5949H1.17532C0.706336 5.5949 0.326172 5.97506 0.326172 6.44405V21.3891C0.326172 21.8581 0.706336 22.2382 1.17532 22.2382H17.8186C18.2876 22.2382 18.6678 21.8581 18.6678 21.3891V6.44405C18.6678 5.97506 18.2876 5.5949 17.8186 5.5949ZM9.49699 2.1983C11.513 2.1983 13.1916 3.66966 13.516 5.5949H5.478C5.80238 3.66966 7.48093 2.1983 9.49699 2.1983ZM16.9695 20.5399H2.02447V7.29319H3.72277V9.84064C3.72277 10.3096 4.10293 10.6898 4.57192 10.6898C5.0409 10.6898 5.42107 10.3096 5.42107 9.84064V7.29319H13.5729V9.84064C13.5729 10.3096 13.9531 10.6898 14.4221 10.6898C14.891 10.6898 15.2712 10.3096 15.2712 9.84064V7.29319H16.9695V20.5399Z"/>
                    </svg>
                    <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute -top-[60px] left-[132%] transform -translate-x-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Add to Cart
                        <span class="w-3 h-3 bg-white dark:bg-title absolute -bottom-[6px] left-1/2 transform -translate-x-1/2 !rotate-45"></span>
                    </span>
                </a>
                <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-10 flex items-center justify-center translate-y-8 opacity-0 transition-all group-hover:duration-300 group-hover:opacity-100 group-hover:translate-y-0 relative tooltip-icon">                                            
                    <svg class="dark:text-white fill-current" width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.0961 2.0896C20.8537 0.742126 19.149 0 17.2956 0C15.9102 0 14.6415 0.437988 13.5245 1.3017C12.9609 1.73767 12.4503 2.27106 12 2.89362C11.5499 2.27124 11.0391 1.73767 10.4753 1.3017C9.35852 0.437988 8.08978 0 6.70441 0C4.85101 0 3.14612 0.742126 1.90375 2.0896C0.676208 3.42133 0 5.24066 0 7.21271C0 9.24243 0.756409 11.1004 2.38037 13.06C3.83313 14.8129 5.92108 16.5923 8.33899 18.6528C9.16461 19.3564 10.1005 20.1541 11.0722 21.0037C11.3289 21.2285 11.6583 21.3523 12 21.3523C12.3415 21.3523 12.6711 21.2285 12.9274 21.004C13.8992 20.1542 14.8356 19.3563 15.6616 18.6522C18.0791 16.5921 20.1671 14.8129 21.6198 13.0598C23.2438 11.1004 24 9.24243 24 7.21252C24 5.24066 23.3238 3.42133 22.0961 2.0896Z" fill="#F0264A"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="lg:pt-7 pt-5 flex gap-3 md:gap-4 flex-col">

            <h4 class="font-medium leading-none dark:text-white text-lg">
                {{ $item['price'] }}
                @if ($item['span'] === 'true')
                    <span class="text-title/50 line-through pl-2 inline-block">$140.99</span>
                @endif
            </h4>
            
                <div>
                <h5 class="font-normal dark:text-white text-xl leading-[1.5]">
                    <a href="{{ route('product-details', ['title' => Str::slug($item['title'])]) }}" class="text-underline">
                        {{ $item['title'] }}
                    </a>
                </h5>
                <ul class="flex items-center gap-2 mt-1">
                    <li>
                        <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z" fill="#EE9818"/>
                        </svg>
                    </li>
                    <li>
                        <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z" fill="#EE9818"/>
                        </svg>
                    </li>
                    <li>
                        <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z" fill="#EE9818"/>
                        </svg>
                    </li>
                    <li>
                        <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.1622 13.6923L7.181 11.201L3.19978 13.6922C3.05515 13.7839 2.86858 13.7769 2.72931 13.6758C2.59043 13.5751 2.52673 13.4001 2.56864 13.2337L3.70764 8.67717L0.150459 5.6612C0.0189569 5.55107 -0.0324041 5.37191 0.0206119 5.2088C0.0736279 5.04526 0.220726 4.93062 0.391668 4.9187L5.03447 4.59449L6.79065 0.23853C6.91968 -0.07951 7.44233 -0.07951 7.57136 0.23853L9.32754 4.59449L13.9703 4.9187C14.1413 4.93062 14.2884 5.04526 14.3414 5.2088C14.3944 5.37191 14.3431 5.55107 14.2115 5.6612L10.6543 8.67723L11.7933 13.2337C11.8353 13.4001 11.7716 13.5752 11.6327 13.6759C11.4905 13.7791 11.3045 13.7814 11.1622 13.6923Z" fill="#EE9818"/>
                        </svg>
                    </li>
                    <li>
                        <svg class="fill-current dark:text-white" width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.6075 13.6923L7.62632 11.201L3.64509 13.6922C3.50046 13.7839 3.3139 13.7769 3.17462 13.6758C3.03574 13.5751 2.97204 13.4001 3.01395 13.2337L4.15295 8.67717L0.595772 5.6612C0.464269 5.55107 0.412908 5.37191 0.465924 5.2088C0.51894 5.04526 0.666039 4.93062 0.836981 4.9187L5.47978 4.59449L7.23596 0.23853C7.365 -0.07951 7.88764 -0.07951 8.01667 0.23853L9.77285 4.59449L14.4157 4.9187C14.5866 4.93062 14.7337 5.04526 14.7867 5.2088C14.8397 5.37191 14.7884 5.55107 14.6569 5.6612L11.0997 8.67723L12.2387 13.2337C12.2806 13.4001 12.2169 13.5752 12.078 13.6759C11.9358 13.7791 11.7498 13.7814 11.6075 13.6923Z" fill-opacity="0.3"/>
                        </svg>
                    </li>
                    <li class="dark:text-gray-100">{{ $item['rate'] }}</li>
                </ul>
            </div>
        </div>
    </div>
@endforeach