@php
$sellers = [
    [
        'id' => 24,
        'img' => 'assets/img/shortcode/product-card/pdct-04.jpg', 
        'price' => '$120', 
        'title' => 'Classic Wall Clock', 
        'rate' => '( 123 )',
        'class' => 'bestSeller-item week',
    ],
    [
        'id' => 25,
        'img' => 'assets/img/shortcode/product-card/pdct-05.jpg', 
        'price' => '$120', 
        'title' => 'Classic Table Lamp', 
        'rate' => '( 123 )',
        'class' => 'bestSeller-item week',
    ],
    [
        'id' => 26,
        'img' => 'assets/img/home-v4/seller-02.jpg', 
        'price' => '$120', 
        'title' => 'Fashionable cool Sofa', 
        'rate' => '( 123 )',
        'class' => 'bestSeller-item week month bestSeller-big',
    ],
    [
        'id' => 27,
        'img' => 'assets/img/home-v4/seller-01.jpg', 
        'price' => '$120', 
        'title' => 'Vintage Table / Chair for Pops1', 
        'rate' => '( 123 )',
        'class' => 'bestSeller-item month',
    ],
    [
        'id' => 28,
        'img' => 'assets/img/shortcode/product-card/pdct-05.jpg', 
        'price' => '$120', 
        'title' => 'Classic Lamp', 
        'rate' => '( 123 )',
        'class' => 'bestSeller-item month',
    ],
];
@endphp

@foreach ($sellers as $item)
    <div class="{{ $item['class'] }}">
        <div class="relative group z-[5] before:absolute card-gradient-overlay before:w-full before:h-full before:top-0 before:left-0 before:opacity-0 before:duration-300 before:z-[5] overflow-hidden hover:before:opacity-100 best-seller-pdct">
            <img class="w-full transform duration-300 group-hover:scale-110" src="{{ asset($item['img']) }}" alt="product-card">
            <div class="absolute z-10 top-0 left-0 w-full h-full items-start justify-end flex flex-col p-7">
                <div>
                    <h4 class="text-lg font-medium leading-none text-white transition-all group-hover:duration-100 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">{{ $item['price'] }}</h4>
                    <h6 class="sm:text-xl font-normal text-white transition-all group-hover:duration-300 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 text-lg mt-3 md:mt-[15px] !leading-[1.5]"><a href="{{ route('product-details', ['title' => Str::slug($item['title'])]) }}">{{ $item['title'] }}</a></h6>
                    <ul class="flex items-center gap-2 mt-1 transition-all group-hover:duration-500 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">
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
                            <svg class="fill-current text-white" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.6075 14.1083L7.62632 11.617L3.64509 14.1083C3.50046 14.1999 3.3139 14.1929 3.17462 14.0918C3.03574 13.9911 2.97204 13.8161 3.01395 13.6497L4.15295 9.09319L0.595772 6.07722C0.464269 5.96709 0.412908 5.78793 0.465924 5.62481C0.51894 5.46128 0.666039 5.34663 0.836981 5.33471L5.47978 5.0105L7.23596 0.654546C7.365 0.336506 7.88764 0.336506 8.01667 0.654546L9.77285 5.0105L14.4157 5.33471C14.5866 5.34663 14.7337 5.46128 14.7867 5.62481C14.8397 5.78793 14.7884 5.96709 14.6569 6.07722L11.0997 9.09324L12.2387 13.6497C12.2806 13.8162 12.2169 13.9912 12.078 14.0919C11.9358 14.1951 11.7498 14.1974 11.6075 14.1083Z" />
                            </svg>
                        </li>
                        <li class="text-white">{{ $item['rate'] }}</li>
                    </ul>
                    <div class="mt-4 sm:mt-5 flex gap-[10px] transition-all group-hover:duration-700 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">
                        <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 flex items-center justify-center text-white  transition-all bg-white/20 duration-300 relative tooltip-icon quick-view">
                            <svg class="fill-current transition-all duration-300 text-white" width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M22.3478 8.44208C20.2569 12.1678 16.2916 14.4822 12.0014 14.4822C7.70844 14.4822 3.74319 12.1678 1.65223 8.44208C1.49119 8.15278 1.49119 7.84697 1.65223 7.55792C3.74319 3.83229 7.70844 1.51813 12.0014 1.51813C16.2916 1.51813 20.2568 3.83229 22.3478 7.55792C22.5116 7.84697 22.5116 8.15278 22.3478 8.44208ZM23.6834 6.81924C21.3231 2.61279 16.8469 0 12.0014 0C7.15306 0 2.67686 2.61279 0.316559 6.81924C-0.10552 7.56977 -0.10552 8.43023 0.316559 9.1802C2.67686 13.3867 7.15306 16 12.0014 16C16.8469 16 21.3231 13.3867 23.6834 9.1802C24.1055 8.43028 24.1055 7.56977 23.6834 6.81924ZM12.0014 11.1141C13.7314 11.1141 15.1392 9.71721 15.1392 7.99987C15.1392 6.28253 13.7314 4.88562 12.0014 4.88562C10.2686 4.88562 8.86081 6.28253 8.86081 7.99987C8.86081 9.71721 10.2687 11.1141 12.0014 11.1141ZM12.0014 3.36749C9.42449 3.36749 7.3308 5.44578 7.3308 7.99993C7.3308 10.5546 9.42454 12.6321 12.0014 12.6321C14.5755 12.6321 16.6692 10.5546 16.6692 7.99993C16.6692 5.44578 14.5755 3.36749 12.0014 3.36749Z"/>
                            </svg>
                            <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute -top-[60px] left-[126%] transform -translate-x-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Quick View
                                <span class="w-3 h-3 bg-white dark:bg-title absolute -bottom-[6px] left-[46%] transform -translate-x-1/2 rotate-[136deg]"></span>
                            </span>                          
                        </button>
                        <a href="{{ url('/cart') }}" class="w-9 lg:w-12 h-9 p-2 lg:h-12 flex items-center justify-center text-white  transition-all bg-white/20 duration-300 relative tooltip-icon">
                            <svg class="fill-current transition-all duration-300 text-white" width="20" height="23" viewBox="0 0 20 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3186 5.63982H15.731C15.3937 2.77487 12.951 0.544922 9.99699 0.544922C7.04292 0.544922 4.60026 2.77487 4.26293 5.63982H1.67532C1.20634 5.63982 0.826172 6.01998 0.826172 6.48897V21.434C0.826172 21.903 1.20634 22.2831 1.67532 22.2831H18.3186C18.7876 22.2831 19.1678 21.903 19.1678 21.434V6.48897C19.1678 6.01998 18.7876 5.63982 18.3186 5.63982ZM9.99699 2.24322C12.013 2.24322 13.6916 3.71458 14.016 5.63982H5.978C6.30238 3.71458 7.98093 2.24322 9.99699 2.24322ZM17.4695 20.5848H2.52447V7.33812H4.22277V9.88556C4.22277 10.3545 4.60293 10.7347 5.07192 10.7347C5.5409 10.7347 5.92107 10.3545 5.92107 9.88556V7.33812H14.0729V9.88556C14.0729 10.3545 14.4531 10.7347 14.9221 10.7347C15.391 10.7347 15.7712 10.3545 15.7712 9.88556V7.33812H17.4695V20.5848Z" />
                            </svg>  
                            <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute -top-[60px] left-[126%] transform -translate-x-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Add to Cart
                                <span class="w-3 h-3 bg-white dark:bg-title absolute -bottom-[6px] left-[46%] transform -translate-x-1/2 rotate-[136deg]"></span>
                            </span>
                        </a>
                        <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 flex items-center justify-center text-white  transition-all bg-white/20 duration-300 relative tooltip-icon">
                            <svg class="fill-current transition-all duration-300 text-white" width="24" height="21" viewBox="0 0 24 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.3927 0.507812C15.4463 0.507812 13.7401 1.37571 12.4584 3.01772C12.2875 3.23672 12.1351 3.4558 12.0001 3.66799C11.865 3.45576 11.7127 3.23672 11.5417 3.01772C10.2601 1.37571 8.55381 0.507812 6.60743 0.507812C2.93056 0.507812 0.300781 3.58651 0.300781 7.28079C0.300781 11.505 3.7629 15.4861 11.5265 20.1893C11.672 20.2775 11.8361 20.3216 12.0001 20.3216C12.1641 20.3216 12.3281 20.2775 12.4737 20.1893C20.2372 15.4862 23.6994 11.5051 23.6994 7.28083C23.6994 3.58847 21.0717 0.507812 17.3927 0.507812ZM19.4564 12.5407C17.8401 14.3441 15.3977 16.243 12.0001 18.3365C8.60248 16.243 6.16002 14.3441 4.54374 12.5407C2.91873 10.7275 2.1288 9.00698 2.1288 7.28083C2.1288 4.62089 3.92637 2.33583 6.60743 2.33583C7.97277 2.33583 9.13694 2.92947 10.0676 4.10032C10.8118 5.03667 11.1254 6.00355 11.1276 6.01045C11.2466 6.39228 11.6001 6.65236 12.0001 6.65236C12.4001 6.65236 12.7536 6.39233 12.8727 6.01045C12.8756 6.00122 13.1797 5.0645 13.8994 4.14245C14.8351 2.94364 16.0105 2.33578 17.3927 2.33578C20.0766 2.33578 21.8713 4.62304 21.8713 7.28079C21.8713 9.00693 21.0814 10.7275 19.4564 12.5407Z"/>
                            </svg>
                            <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute -top-[60px] left-[126%] transform -translate-x-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Add to wishlist
                                <span class="w-3 h-3 bg-white dark:bg-title absolute -bottom-[6px] left-[46%] transform -translate-x-1/2 rotate-[136deg]"></span>
                            </span>
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach