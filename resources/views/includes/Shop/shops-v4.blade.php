@php
$shops = [
    [
        'id' => 75,
        'img' => 'assets/img/gallery/shop-04/shop-01.jpg', 
        'price' => '$122.75', 
        'title' => 'Design your apps', 
        'rate' => '( 123 )', 
        'tag' => 'OFF', 
        'span' => 'true', 
        'class' => 'shop-item Interior', 
    ],
    [
        'id' => 76,
        'img' => 'assets/img/gallery/shop-04/shop-02.jpg', 
        'price' => '$122.75', 
        'title' => 'your own way', 
        'rate' => '( 123 )', 
        'tag' => '', 
        'span' => 'false', 
        'class' => 'shop-item', 
    ],
    [
        'id' => 77,
        'img' => 'assets/img/gallery/shop-04/shop-03.jpg', 
        'price' => '$122.75', 
        'title' => 'Smartest Applications', 
        'rate' => '( 123 )', 
        'tag' => 'OFF', 
        'span' => 'true', 
        'class' => 'shop-item Interior Vase Table', 
    ],
    [
        'id' => 78,
        'img' => 'assets/img/gallery/shop-04/shop-04.jpg', 
        'price' => '$122.75', 
        'title' => 'For Business', 
        'rate' => '( 123 )', 
        'tag' => '', 
        'span' => 'false', 
        'class' => 'shop-item Table Design', 
    ],
    [
        'id' => 79,
        'img' => 'assets/img/gallery/shop-04/shop-05.jpg', 
        'price' => '$122.75', 
        'title' => 'Change Your Strategy', 
        'rate' => '( 123 )', 
        'tag' => 'OFF', 
        'span' => 'true', 
        'class' => 'shop-item Interior Table', 
    ],
    [
        'id' => 80,
        'img' => 'assets/img/gallery/shop-04/shop-06.jpg', 
        'price' => '$122.75', 
        'title' => 'Provide denounce', 
        'rate' => '( 123 )', 
        'tag' => '', 
        'span' => 'false', 
        'class' => 'shop-item Vase', 
    ],
    [
        'id' => 81,
        'img' => 'assets/img/gallery/shop-04/shop-07.jpg', 
        'price' => '$122.75', 
        'title' => 'Offer Prognosis', 
        'rate' => '( 123 )', 
        'tag' => '', 
        'span' => 'false', 
        'class' => 'shop-item Table Design', 
    ],
    [
        'id' => 82,
        'img' => 'assets/img/gallery/shop-04/shop-08.jpg', 
        'price' => '$122.75', 
        'title' => 'Mobile Marketing', 
        'rate' => '( 123 )', 
        'tag' => '', 
        'span' => 'false', 
        'class' => 'shop-item Vase', 
    ],
    [
        'id' => 83,
        'img' => 'assets/img/gallery/shop-04/shop-09.jpg', 
        'price' => '$122.75', 
        'title' => 'You Covered', 
        'rate' => '( 123 )', 
        'tag' => '', 
        'span' => 'false', 
        'class' => 'shop-item Table Design', 
    ]
];
@endphp

@foreach ($shops as $item)
    <div class="{{ $item['class'] }}">
        <div class="relative before:absolute before:top-0 before:left-0 before:w-full before:h-full before:bg-title before:opacity-0 before:duration-300 hover:before:opacity-70 group overflow-hidden">
            <img class="w-full h-full object-cover" src="{{ asset($item['img']) }}" alt="shop">
            
            @if ($item['tag'] === 'OFF')
                <div class="absolute z-10 top-7 left-7 pt-[10px] pb-2 px-3 bg-[#E13939] rounded-[30px] font-primary text-[14px] text-white font-semibold leading-none">
                    15% OFF
                </div>
            @endif

            <div class="absolute z-10 top-0 left-0 w-full h-full items-start justify-end flex flex-col p-7">
                <div>

                @if ($item['span'] === 'false')
                    <h4 class="text-lg font-medium leading-none text-white transition-all group-hover:duration-100 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">{{ $item['price'] }}</h4>
                @elseif ($item['span'] === 'true')
                    <h4 class="text-lg font-medium leading-none text-white transition-all group-hover:duration-100 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">{{ $item['price'] }}<span class="text-white/60 line-through pl-2 inline-block">$140.99</span></h4>
                @endif
                    
                    <h6 class="text-xl md:text-2xl font-normal mt-3 md:mt-4 text-white transition-all group-hover:duration-300 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100"><a href="{{ route('product-details', ['slug' => Str::slug($item['title'])]) }}">{{ $item['title'] }}</a></h6>
                    <ul class="flex items-center gap-2 mt-3 md:mt-4 transition-all group-hover:duration-500 transform translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100">
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
                        <li class="text-gray-100">{{ $item['rate'] }}</li>
                    </ul>
                </div>  
            </div>
            <div class="absolute z-10 top-[71%] right-7 transform -translate-y-[40%] opacity-0 duration-300 transition-all group-hover:-translate-y-1/2 group-hover:opacity-100 flex flex-col items-end gap-3">
                <a href="#" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon">
                    <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.3927 0.0917969C15.4463 0.0917969 13.7401 0.959692 12.4584 2.60171C12.2875 2.8207 12.1351 3.03979 12.0001 3.25198C11.865 3.03974 11.7127 2.8207 11.5417 2.60171C10.2601 0.959692 8.55381 0.0917969 6.60743 0.0917969C2.93056 0.0917969 0.300781 3.17049 0.300781 6.86477C0.300781 11.089 3.7629 15.0701 11.5265 19.7733C11.672 19.8614 11.8361 19.9055 12.0001 19.9055C12.1641 19.9055 12.3281 19.8615 12.4737 19.7733C20.2372 15.0702 23.6994 11.089 23.6994 6.86482C23.6994 3.17246 21.0717 0.0917969 17.3927 0.0917969ZM19.4564 12.1247C17.8401 13.9281 15.3977 15.827 12.0001 17.9205C8.60248 15.827 6.16002 13.9281 4.54374 12.1247C2.91873 10.3115 2.1288 8.59096 2.1288 6.86482C2.1288 4.20487 3.92637 1.91981 6.60743 1.91981C7.97277 1.91981 9.13694 2.51346 10.0676 3.6843C10.8118 4.62066 11.1254 5.58754 11.1276 5.59444C11.2466 5.97626 11.6001 6.23634 12.0001 6.23634C12.4001 6.23634 12.7536 5.97631 12.8727 5.59444C12.8756 5.58521 13.1797 4.64849 13.8994 3.72644C14.8351 2.52762 16.0105 1.91976 17.3927 1.91976C20.0766 1.91976 21.8713 4.20702 21.8713 6.86477C21.8713 8.59092 21.0814 10.3114 19.4564 12.1247Z"/>
                    </svg>                                                                      
                    <span class="mt-1">Add to wishlist</span>
                </a>
                <a href="#" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon">
                    <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826ZM9.99503 1.89166C12.0111 1.89166 13.6896 3.36302 14.014 5.28826H5.97605C6.30043 3.36302 7.97898 1.89166 9.99503 1.89166ZM17.4675 20.2333H2.52252V6.98655H4.22082V9.534C4.22082 10.003 4.60098 10.3832 5.06997 10.3832C5.53895 10.3832 5.91912 10.003 5.91912 9.534V6.98655H14.0709V9.534C14.0709 10.003 14.4511 10.3832 14.9201 10.3832C15.3891 10.3832 15.7692 10.003 15.7692 9.534V6.98655H17.4675V20.2333Z"/>
                    </svg>    
                    <span class="mt-1">Add to Cart</span>
                </a>
                <button class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 px-4 py-[10px] text-base leading-none text-title rounded-[40px] h-14 overflow-hidden new-product-icon quick-view">
                    <svg class="dark:text-white fill-current" width="20" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.3478 8.44208C20.2569 12.1678 16.2916 14.4822 12.0014 14.4822C7.70844 14.4822 3.74319 12.1678 1.65223 8.44208C1.49119 8.15278 1.49119 7.84697 1.65223 7.55792C3.74319 3.83229 7.70844 1.51813 12.0014 1.51813C16.2916 1.51813 20.2568 3.83229 22.3478 7.55792C22.5116 7.84697 22.5116 8.15278 22.3478 8.44208ZM23.6834 6.81924C21.3231 2.61279 16.8469 0 12.0014 0C7.15306 0 2.67686 2.61279 0.316559 6.81924C-0.10552 7.56977 -0.10552 8.43023 0.316559 9.1802C2.67686 13.3867 7.15306 16 12.0014 16C16.8469 16 21.3231 13.3867 23.6834 9.1802C24.1055 8.43028 24.1055 7.56977 23.6834 6.81924ZM12.0014 11.1141C13.7314 11.1141 15.1392 9.71721 15.1392 7.99987C15.1392 6.28253 13.7314 4.88562 12.0014 4.88562C10.2686 4.88562 8.86081 6.28253 8.86081 7.99987C8.86081 9.71721 10.2687 11.1141 12.0014 11.1141ZM12.0014 3.36749C9.42449 3.36749 7.3308 5.44578 7.3308 7.99993C7.3308 10.5546 9.42454 12.6321 12.0014 12.6321C14.5755 12.6321 16.6692 10.5546 16.6692 7.99993C16.6692 5.44578 14.5755 3.36749 12.0014 3.36749Z"/>
                    </svg>                                       
                    <span class="mt-1">Quick View</span>
                </button>
            </div>
        </div>
    </div>
@endforeach