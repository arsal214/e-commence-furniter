@php
$trendings = [
    [
        'id' => 29,
        'img' => 'assets/img/home-v4/treading-01.jpg', 
        'price' => '$122.75', 
        'title' => 'Fashionable Woodens Sofa', 
        'rate' => '( 123 )',
    ],
    [
        'id' => 30,
        'img' => 'assets/img/home-v4/treading-02.jpg', 
        'price' => '$122.75', 
        'title' => 'Vase for Table', 
        'rate' => '( 123 )',
    ],
    [
        'id' => 31,
        'img' => 'assets/img/home-v4/treading-03.jpg', 
        'price' => '$122.75 - $310.50', 
        'title' => 'New Modern Luxury Table with Pops Lorem ipsum Furniture', 
        'rate' => '( 123 )',
    ],
    [
        'id' => 32,
        'img' => 'assets/img/home-v4/treading-04.jpg', 
        'price' => '$122.75', 
        'title' => 'Vintage Table / Chair', 
        'rate' => '( 123 )',
    ],
];
@endphp

@foreach ($trendings as $item)
    <div class="group">
        <div class="relative overflow-hidden before:absolute card-gradient-overlay before:w-full before:h-full before:top-0 before:left-0 before:z-10 before:opacity-0 group-hover:before:opacity-100 before:duration-300 ">
            <img class="w-full transform duration-300 group-hover:scale-110" src="{{ asset($item['img']) }}" alt="product-card">

            <div class="flex flex-col gap-[10px] absolute z-20 bottom-5 right-5">
                <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 flex items-center justify-center transition-all duration-300 bg-white dark:bg-title bg-opacity-10 dark:bg-opacity-80 transform translate-y-8 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 quick-view tooltip-icon-2">
                    <svg class="fill-current transition-all duration-300 text-white" width="25" height="16" viewBox="0 0 25 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.6808 8.44208C20.5899 12.1678 16.6246 14.4822 12.3344 14.4822C8.04145 14.4822 4.07619 12.1678 1.98524 8.44208C1.8242 8.15278 1.8242 7.84697 1.98524 7.55792C4.07619 3.83229 8.04145 1.51813 12.3344 1.51813C16.6246 1.51813 20.5898 3.83229 22.6808 7.55792C22.8447 7.84697 22.8447 8.15278 22.6808 8.44208ZM24.0164 6.81924C21.6562 2.61279 17.1799 0 12.3344 0C7.48607 0 3.00986 2.61279 0.649566 6.81924C0.227488 7.56977 0.227488 8.43023 0.649566 9.1802C3.00986 13.3867 7.48607 16 12.3344 16C17.1799 16 21.6562 13.3867 24.0164 9.1802C24.4385 8.43028 24.4385 7.56977 24.0164 6.81924ZM12.3344 11.1141C14.0644 11.1141 15.4722 9.71721 15.4722 7.99987C15.4722 6.28253 14.0644 4.88562 12.3344 4.88562C10.6017 4.88562 9.19381 6.28253 9.19381 7.99987C9.19381 9.71721 10.6017 11.1141 12.3344 11.1141ZM12.3344 3.36749C9.7575 3.36749 7.66381 5.44578 7.66381 7.99993C7.66381 10.5546 9.75755 12.6321 12.3344 12.6321C14.9085 12.6321 17.0022 10.5546 17.0022 7.99993C17.0022 5.44578 14.9085 3.36749 12.3344 3.36749Z"/>
                    </svg>  
                    <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute right-[70px] top-[80%] transform -translate-y-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Quick View
                        <span class="w-3 h-3 bg-white dark:bg-title absolute -right-[6px] top-[46%] transform -translate-y-1/2 rotate-[222deg]"></span>
                    </span>
                </button>
                
                <a href="{{ url('/cart') }}" class="w-9 lg:w-12 h-9 p-2 lg:h-12 flex items-center justify-center transition-all duration-500 bg-white dark:bg-title bg-opacity-10 dark:bg-opacity-60 transform translate-y-8 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 tooltip-icon-2">
                    <svg class="fill-current transition-all duration-300 text-white" width="20" height="23" viewBox="0 0 20 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.3186 5.63982H15.731C15.3937 2.77487 12.951 0.544922 9.99699 0.544922C7.04292 0.544922 4.60026 2.77487 4.26293 5.63982H1.67532C1.20634 5.63982 0.826172 6.01998 0.826172 6.48897V21.434C0.826172 21.903 1.20634 22.2831 1.67532 22.2831H18.3186C18.7876 22.2831 19.1678 21.903 19.1678 21.434V6.48897C19.1678 6.01998 18.7876 5.63982 18.3186 5.63982ZM9.99699 2.24322C12.013 2.24322 13.6916 3.71458 14.016 5.63982H5.978C6.30238 3.71458 7.98093 2.24322 9.99699 2.24322ZM17.4695 20.5848H2.52447V7.33812H4.22277V9.88556C4.22277 10.3545 4.60293 10.7347 5.07192 10.7347C5.5409 10.7347 5.92107 10.3545 5.92107 9.88556V7.33812H14.0729V9.88556C14.0729 10.3545 14.4531 10.7347 14.9221 10.7347C15.391 10.7347 15.7712 10.3545 15.7712 9.88556V7.33812H17.4695V20.5848Z" />
                    </svg>
                    <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute right-[70px] top-[80%] transform -translate-y-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Add to Cart
                        <span class="w-3 h-3 bg-white dark:bg-title absolute -right-[6px] top-[46%] transform -translate-y-1/2 rotate-[222deg]"></span>
                    </span>
                </a>

                <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 flex items-center justify-center transition-all duration-700 bg-white dark:bg-title bg-opacity-10 dark:bg-opacity-80 faveIcon transform translate-y-8 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 tooltip-icon-2">
                    <svg class="fill-current transition-all duration-300 text-white" width="26" height="22" viewBox="0 0 25 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.0607 0.293945C16.1143 0.293945 14.408 1.16184 13.1264 2.80385C12.9554 3.02285 12.8031 3.24194 12.668 3.45412C12.533 3.24189 12.3806 3.02285 12.2097 2.80385C10.928 1.16184 9.22178 0.293945 7.2754 0.293945C3.59853 0.293945 0.96875 3.37264 0.96875 7.06692C0.96875 11.2911 4.43087 15.2723 12.1944 19.9754C12.34 20.0636 12.504 20.1077 12.668 20.1077C12.8321 20.1077 12.9961 20.0636 13.1416 19.9755C20.9052 15.2723 24.3673 11.2912 24.3673 7.06696C24.3673 3.3746 21.7397 0.293945 18.0607 0.293945ZM20.1244 12.3268C18.5081 14.1302 16.0656 16.0292 12.668 18.1227C9.27045 16.0292 6.82799 14.1302 5.21171 12.3268C3.58669 10.5136 2.79676 8.79311 2.79676 7.06696C2.79676 4.40702 4.59434 2.12196 7.2754 2.12196C8.64074 2.12196 9.80491 2.71561 10.7356 3.88645C11.4798 4.8228 11.7933 5.78969 11.7955 5.79659C11.9146 6.17841 12.2681 6.43849 12.6681 6.43849C13.0681 6.43849 13.4216 6.17846 13.5406 5.79659C13.5435 5.78735 13.8477 4.85064 14.5674 3.92858C15.5031 2.72977 16.6784 2.12191 18.0607 2.12191C20.7446 2.12191 22.5393 4.40917 22.5393 7.06692C22.5393 8.79307 21.7494 10.5136 20.1244 12.3268Z"/>
                    </svg>
                    <span class="p-2 bg-white dark:bg-title text-xs text-title dark:text-white absolute right-[70px] top-[80%] transform -translate-y-1/2 whitespace-nowrap rounded-[4px] opacity-0 invisible duration-300">Add to wishlist
                        <span class="w-3 h-3 bg-white dark:bg-title absolute -right-[6px] top-[46%] transform -translate-y-1/2 rotate-[222deg]"></span>
                    </span>
                </button>
            </div>

            <ul class="flex items-center gap-2 mt-1 absolute z-20 left-5 bottom-5 transform -translate-x-8 opacity-0 group-hover:opacity-100 group-hover:-translate-x-0 duration-300">
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
        <div class="mt-5 md:mt-7">
            <h4 class="font-medium leading-none dark:text-white text-lg">{{ $item['price'] }}</h4>
            <h5 class="mt-3 text-xl font-normal dark:text-white leading-[1.5]"><a href="{{ route('product-details', ['title' => Str::slug($item['title'])]) }}">{{ $item['title'] }}</a></h5>
        </div>
    </div>
@endforeach