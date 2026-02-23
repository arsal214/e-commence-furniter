@php
$products = [
    [
        'id' => 16,
        'img' => 'assets/img/shortcode/product-card/pdct-01.jpg', 
        'price' => '$122.75', 
        'title' => 'Classic Chair', 
        'rate' => '( 1,250 )', 
        'span' => 'false', 
    ],
    [
        'id' => 17,
        'img' => 'assets/img/shortcode/product-card/pdct-02.jpg', 
        'price' => '$122.75', 
        'title' => 'Modern Fashionable Wooden Sofa Chair', 
        'rate' => '( 1,250 )', 
        'span' => 'false', 
    ],
    [
        'id' => 18,
        'img' => 'assets/img/shortcode/product-card/pdct-03.jpg', 
        'price' => '$122.75', 
        'title' => 'Modern Wooden Sofa', 
        'rate' => '( 1,250 )', 
        'span' => 'false', 
    ],
    [
        'id' => 19,
        'img' => 'assets/img/shortcode/product-card/pdct-14.jpg', 
        'price' => '$122.75', 
        'title' => 'Vintage Light', 
        'rate' => '( 1,250 )', 
        'span' => 'true', 
    ]
];
@endphp

@foreach ($products as $item)
    <div class="relative overflow-hidden group">
        <a href="{{ route('product-details', ['title' => Str::slug($item['title'])]) }}">
            <img class="w-full transform duration-300 group-hover:scale-110" src="{{ asset($item['img']) }}" alt="product-card">
        </a>
        
        <!-- Product Content -->
        <div class="flex flex-col items-start gap-3 md:gap-4 absolute z-20 w-11/12 bottom-3 xl:bottom-5 left-[96%] transform -translate-x-1/2 p-4 xl:p-5 bg-white dark:bg-title bg-opacity-[85%] dark:bg-opacity-[85%] group-hover:-translate-y-1/2 duration-500 group-hover:opacity-0 group-hover:invisible">
            
            <h4 class="font-medium leading-none dark:text-white text-lg">
                {{ $item['price'] }}
                @if ($item['span'] === 'true')
                    <span class="text-title/50 line-through pl-2 inline-block">$140.99</span>
                @endif
            </h4> 
            
            <div>
                <h5 class="font-normal dark:text-white text-paragraph leading-[1.5] text-xl">
                    <a href="{{ route('product-details', ['title' => Str::slug($item['title'])]) }}">
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

        <!-- Product Icons -->
        <div class="absolute z-10 flex gap-2 justify-center bottom-5 md:bottom-7 w-full transform translate-y-5 opacity-0 duration-500 invisible group-hover:translate-y-0 group-hover:opacity-100 group-hover:visible">

            <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-80 flex items-center justify-center relative tooltip-icon rounded-full">
                <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="dark:text-white fill-current">
                    <path d="M17.3908 0.15625C15.4444 0.15625 13.7381 1.02415 12.4564 2.66616C12.2855 2.88515 12.1332 3.10424 11.9981 3.31643C11.8631 3.1042 11.7107 2.88515 11.5398 2.66616C10.2581 1.02415 8.55185 0.15625 6.60548 0.15625C2.92861 0.15625 0.298828 3.23495 0.298828 6.92922C0.298828 11.1534 3.76095 15.1346 11.5245 19.8377C11.6701 19.9259 11.8341 19.97 11.9981 19.97C12.1621 19.97 12.3262 19.9259 12.4717 19.8378C20.2353 15.1346 23.6974 11.1535 23.6974 6.92927C23.6974 3.23691 21.0698 0.15625 17.3908 0.15625ZM19.4545 12.1891C17.8382 13.9925 15.3957 15.8915 11.9981 17.985C8.60052 15.8915 6.15807 13.9925 4.54179 12.1891C2.91677 10.3759 2.12684 8.65542 2.12684 6.92927C2.12684 4.26933 3.92442 1.98426 6.60548 1.98426C7.97082 1.98426 9.13499 2.57791 10.0657 3.74875C10.8099 4.68511 11.1234 5.65199 11.1256 5.65889C11.2447 6.04072 11.5982 6.3008 11.9982 6.3008C12.3981 6.3008 12.7517 6.04076 12.8707 5.65889C12.8736 5.64966 13.1777 4.71294 13.8975 3.79089C14.8332 2.59208 16.0085 1.98422 17.3908 1.98422C20.0746 1.98422 21.8694 4.27147 21.8694 6.92922C21.8694 8.65537 21.0795 10.3759 19.4545 12.1891Z"/>
                </svg>
                <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute -top-[60px] left-[134%] transform -translate-x-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Add to wishlist
                    <span class="w-3 h-3 bg-white dark:bg-title absolute -bottom-[6px] left-[46%] transform -translate-x-1/2 rotate-[136deg]"></span>
                </span>
            </button>

            <a href="{{ url('/cart') }}" class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-80 flex items-center justify-center relative tooltip-icon rounded-full">
                <svg class="dark:text-white fill-current" width="20" height="24" viewBox="0 0 19 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.8186 5.5949H15.231C14.8937 2.72995 12.451 0.5 9.49699 0.5C6.54292 0.5 4.10026 2.72995 3.76293 5.5949H1.17532C0.706336 5.5949 0.326172 5.97506 0.326172 6.44405V21.3891C0.326172 21.8581 0.706336 22.2382 1.17532 22.2382H17.8186C18.2876 22.2382 18.6678 21.8581 18.6678 21.3891V6.44405C18.6678 5.97506 18.2876 5.5949 17.8186 5.5949ZM9.49699 2.1983C11.513 2.1983 13.1916 3.66966 13.516 5.5949H5.478C5.80238 3.66966 7.48093 2.1983 9.49699 2.1983ZM16.9695 20.5399H2.02447V7.29319H3.72277V9.84064C3.72277 10.3096 4.10293 10.6898 4.57192 10.6898C5.0409 10.6898 5.42107 10.3096 5.42107 9.84064V7.29319H13.5729V9.84064C13.5729 10.3096 13.9531 10.6898 14.4221 10.6898C14.891 10.6898 15.2712 10.3096 15.2712 9.84064V7.29319H16.9695V20.5399Z"/>
                </svg>
                <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute -top-[60px] left-[134%] transform -translate-x-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Add to Cart
                    <span class="w-3 h-3 bg-white dark:bg-title absolute -bottom-[6px] left-[46%] transform -translate-x-1/2 rotate-[136deg]"></span>
                </span>
            </a>

            <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-80 flex items-center justify-center relative quick-view tooltip-icon rounded-full">
                <svg class="dark:text-white fill-current" width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M22.3478 8.44208C20.2569 12.1678 16.2916 14.4822 12.0014 14.4822C7.70844 14.4822 3.74319 12.1678 1.65223 8.44208C1.49119 8.15278 1.49119 7.84697 1.65223 7.55792C3.74319 3.83229 7.70844 1.51813 12.0014 1.51813C16.2916 1.51813 20.2568 3.83229 22.3478 7.55792C22.5116 7.84697 22.5116 8.15278 22.3478 8.44208ZM23.6834 6.81924C21.3231 2.61279 16.8469 0 12.0014 0C7.15306 0 2.67686 2.61279 0.316559 6.81924C-0.10552 7.56977 -0.10552 8.43023 0.316559 9.1802C2.67686 13.3867 7.15306 16 12.0014 16C16.8469 16 21.3231 13.3867 23.6834 9.1802C24.1055 8.43028 24.1055 7.56977 23.6834 6.81924ZM12.0014 11.1141C13.7314 11.1141 15.1392 9.71721 15.1392 7.99987C15.1392 6.28253 13.7314 4.88562 12.0014 4.88562C10.2686 4.88562 8.86081 6.28253 8.86081 7.99987C8.86081 9.71721 10.2687 11.1141 12.0014 11.1141ZM12.0014 3.36749C9.42449 3.36749 7.3308 5.44578 7.3308 7.99993C7.3308 10.5546 9.42454 12.6321 12.0014 12.6321C14.5755 12.6321 16.6692 10.5546 16.6692 7.99993C16.6692 5.44578 14.5755 3.36749 12.0014 3.36749Z"/>
                </svg>
                <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute -top-[60px] left-[134%] transform -translate-x-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Quick Preview
                    <span class="w-3 h-3 bg-white dark:bg-title absolute -bottom-[6px] left-[46%] transform -translate-x-1/2 rotate-[136deg]"></span>
                </span>
            </button>
        </div>
    </div>
@endforeach