<!-- resources/views/short-code.blade.php -->
@extends('layouts.main')

@section('title', 'Short-Code Page')

@section('content')

@include('includes.navbar')

<div class="bg-snow dark:bg-title">
    <!-- Banner Start -->
    <div class="py-12 bg-overlay before:bg-title before:opacity-[0.85]"  style="background-image: url({{ asset('assets/img/banner/shortcode-banner.jpg') }});">
        <div class="container">
            <div class="text-center">
                <span class="text-3xl text-white leading-none md:text-4xl lg:text-5xl">Short Code</span>
                <p class="text-lg md:text-xl lg:text-2xl text-white mt-3 lg:mt-4 leading-none">Also provided some important shortcode</p>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <div class="max-w-[1720px] container-fluid mt-12 s-pb-100">
        <div class="md:gap-x-7 md:grid md:grid-cols-5">
            <div class="row-span-5 md:col-span-2 xl:col-span-1">
                <div class="bg-white dark:bg-dark-card-bg px-5 py-2 lg:px-7 lg:py-4 md:sticky md:top-[100px]">
                    <ul class="divide-y dark:divide-paragraph text-paragraph dark:text-white lg:text-lg">
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#typography">Typography / Colors</a></li>
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#product">Product Cart</a></li>
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#button">Button / Badges / Breadcumb</a></li>
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#carousel">Carousel / Slider</a></li>
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#gallery">Gallery</a></li>
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#newsletter">Newsletter</a></li>
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#blog">Blog Card</a></li>
                        <li class="pl-3 py-3 lg:py-4 hover:text-primary active:text-primary duration-300"><a href="#partner">Brand Logo / Partner Logo</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 md:mt-0 md:col-span-3 xl:col-span-4">
                <!-- Typography -->
                <div class="bg-white dark:bg-dark-card-bg p-5 lg:p-8 xl:p-12" id="typography">
                    <span class="text-secondary text-xl font-semibold lg:text-2xl xl:text-3xl">Font - Josefin Sans</span>
                    <h1 class="mt-7 dark:text-white text-5xl font-bold">This is Heading 123</h1>
                    <h2 class="mt-5 dark:text-white text-4xl font-bold">This is Heading 123</h2>
                    <h3 class="mt-5 dark:text-white text-3xl font-bold">This is Heading 123</h3>

                    <div class="mt-7 lg:mt-12">
                        <h3 class="leading-none text-2xl xl:text-3xl dark:text-white font-bold">H1 - H6</h3>
                        <ul class="flex flex-wrap gap-3 lg:gap-12 text-lg lg:text-xl xl:text-2xl text-title leading-none mt-5 dark:text-white">
                            <li>48px</li>
                            <li>36px</li>
                            <li>30px</li>
                            <li>24px</li>
                            <li>20px</li>
                            <li>18px</li>
                        </ul>
                    </div>

                    <div class="mt-7 lg:mt-12">
                        <h3 class="leading-none text-2xl xl:text-3xl dark:text-white font-bold">Line Height</h3>
                        <ul class="flex flex-wrap gap-3 lg:gap-12 text-lg lg:text-xl xl:text-2xl text-title leading-none mt-5 dark:text-white">
                            <li>48px</li>
                            <li>40px</li>
                            <li>36px</li>
                            <li>32px</li>
                            <li>28px</li>
                            <li>24px</li>
                        </ul>
                    </div>

                    <div class="mt-7 xl:mt-12 max-w-[906px] sm:flex items-start justify-between gap-7 flex-wrap">
                        <div>
                            <h3 class="leading-none text-2xl xl:text-3xl dark:text-white font-bold">Text Color</h3>
                            <div class="mt-4 lg:mt-6 flex gap-5 sm:gap-10 sm:justify-between flex-wrap">
                                <div class="w-12 sm:w-16 h-12 sm:h-16 bg-title"></div>
                                <div class="w-12 sm:w-16 h-12 sm:h-16 bg-paragraph"></div>
                                <div class="w-12 sm:w-16 h-12 sm:h-16 bg-snow border border-black"></div>
                                <div class="w-12 sm:w-16 h-12 sm:h-16 bg-white border border-black"></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="leading-none text-2xl xl:text-3xl mt-7 sm:mt-0 dark:text-white font-bold">Theme Color</h3>
                            <div class="mt-4 lg:mt-6 grid grid-cols-3 gap-4 justify-between w-full sm:w-[364px]">
                                <div class="h-8 bg-primary"></div>
                                <div class="h-8 bg-primary-midum"></div>
                                <div class="h-8 bg-primary-light"></div>
                            </div>
                            <div class="mt-4 lg:mt-6 xl:mt-7 grid grid-cols-3 gap-4 justify-between w-full sm:w-[364px]">
                                <div class="h-8 bg-secondary"></div>
                                <div class="h-8 bg-secondary-midum"></div>
                                <div class="h-8 bg-secondary-light"></div>
                            </div>
                            <div class="mt-4 lg:mt-6 xl:mt-7 grid grid-cols-3 gap-4 justify-between w-full sm:w-[364px]">
                                <div class="h-8 bg-tertiary"></div>
                                <div class="h-8 bg-tertiary-midum"></div>
                                <div class="h-8 bg-tertiary-light"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card -->
                <div class="bg-white dark:bg-dark-card-bg p-5 mt-5 md:mt-8 lg:p-8 xl:p-12" id="product">
                    <!-- Card 01 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-8 max-w-4xl">
                        <div class="group">
                            <div class="relative overflow-hidden">
                                <img class="w-full transform group-hover:scale-110 duration-300" src="{{ asset('assets/img/shortcode/product-card/pdct-01.jpg') }}" alt="product-card"> 
                                <button class="absolute z-10 top-3 left-0 btn-tag">
                                    Hot Sale
                                </button>
                                <a href="#" class="absolute z-10 top-3 right-3 bg-white text-title text-[15px] leading-none font-medium p-[10px]  group-hover:bg-primary transition-all duration-300 group-hover:text-white dark:bg-title dark:text-white">
                                    Luxury Chair
                                </a>
                                <div class="absolute z-10 top-[50%] right-3 transform translate-y-5 opacity-0 duration-300 transition-all group-hover:translate-y-0 group-hover:opacity-100">
                                    <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-80 flex items-center justify-center faveIcon">
                                        <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="dark:text-white fill-current">
                                            <path d="M17.3908 0.15625C15.4444 0.15625 13.7381 1.02415 12.4564 2.66616C12.2855 2.88515 12.1332 3.10424 11.9981 3.31643C11.8631 3.1042 11.7107 2.88515 11.5398 2.66616C10.2581 1.02415 8.55185 0.15625 6.60548 0.15625C2.92861 0.15625 0.298828 3.23495 0.298828 6.92922C0.298828 11.1534 3.76095 15.1346 11.5245 19.8377C11.6701 19.9259 11.8341 19.97 11.9981 19.97C12.1621 19.97 12.3262 19.9259 12.4717 19.8378C20.2353 15.1346 23.6974 11.1535 23.6974 6.92927C23.6974 3.23691 21.0698 0.15625 17.3908 0.15625ZM19.4545 12.1891C17.8382 13.9925 15.3957 15.8915 11.9981 17.985C8.60052 15.8915 6.15807 13.9925 4.54179 12.1891C2.91677 10.3759 2.12684 8.65542 2.12684 6.92927C2.12684 4.26933 3.92442 1.98426 6.60548 1.98426C7.97082 1.98426 9.13499 2.57791 10.0657 3.74875C10.8099 4.68511 11.1234 5.65199 11.1256 5.65889C11.2447 6.04072 11.5982 6.3008 11.9982 6.3008C12.3981 6.3008 12.7517 6.04076 12.8707 5.65889C12.8736 5.64966 13.1777 4.71294 13.8975 3.79089C14.8332 2.59208 16.0085 1.98422 17.3908 1.98422C20.0746 1.98422 21.8694 4.27147 21.8694 6.92922C21.8694 8.65537 21.0795 10.3759 19.4545 12.1891Z"/>
                                        </svg>
                                    </button>
                                    <a href="#" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 p-[10px] mt-3 text-base leading-none text-title">
                                        <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826ZM9.99503 1.89166C12.0111 1.89166 13.6896 3.36302 14.014 5.28826H5.97605C6.30043 3.36302 7.97898 1.89166 9.99503 1.89166ZM17.4675 20.2333H2.52252V6.98655H4.22082V9.534C4.22082 10.003 4.60098 10.3832 5.06997 10.3832C5.53895 10.3832 5.91912 10.003 5.91912 9.534V6.98655H14.0709V9.534C14.0709 10.003 14.4511 10.3832 14.9201 10.3832C15.3891 10.3832 15.7692 10.003 15.7692 9.534V6.98655H17.4675V20.2333Z"/>
                                        </svg>    
                                        <span class="mt-1">Add to Cart</span>
                                    </a>
                                </div>
                            </div>
                            <div class="md:px-2 lg:px-4 xl:px-6 lg:pt-6 pt-3 flex items-center justify-between gap-3 flex-wrap">
                                <div>
                                    <h5 class="font-normal dark:text-white text-xl">
                                        <a href="#" class="text-underline">
                                            White Minimal Chair
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
                                        <li class="dark:text-gray-100">( 1,250 )</li>
                                    </ul>
                                </div>
                                <h4 class="font-semibold leading-none italic dark:text-white text-xl md:text-2xl">$25</h4>
                            </div>
                        </div>
                        <div class="group">
                            <div class="relative overflow-hidden">
                                <img class="w-full transform group-hover:scale-110 duration-300" src="{{ asset('assets/img/shortcode/product-card/pdct-02.jpg') }}" alt="product-card">
                                <a href="#" class="absolute z-10 top-3 right-3 bg-white text-title text-[15px] leading-none font-medium p-[10px]  group-hover:bg-primary transition-all duration-300 group-hover:text-white dark:bg-title dark:text-white">
                                    Classic Chair
                                </a>
                                <div class="absolute z-10 top-[50%] right-3 transform translate-y-5 opacity-0 duration-300 transition-all group-hover:translate-y-0 group-hover:opacity-100">
                                    <button class="w-9 lg:w-12 h-9 p-2 lg:h-12 bg-white dark:bg-title bg-opacity-80 flex items-center justify-center faveIcon">
                                        <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="dark:text-white fill-current">
                                            <path d="M17.3908 0.15625C15.4444 0.15625 13.7381 1.02415 12.4564 2.66616C12.2855 2.88515 12.1332 3.10424 11.9981 3.31643C11.8631 3.1042 11.7107 2.88515 11.5398 2.66616C10.2581 1.02415 8.55185 0.15625 6.60548 0.15625C2.92861 0.15625 0.298828 3.23495 0.298828 6.92922C0.298828 11.1534 3.76095 15.1346 11.5245 19.8377C11.6701 19.9259 11.8341 19.97 11.9981 19.97C12.1621 19.97 12.3262 19.9259 12.4717 19.8378C20.2353 15.1346 23.6974 11.1535 23.6974 6.92927C23.6974 3.23691 21.0698 0.15625 17.3908 0.15625ZM19.4545 12.1891C17.8382 13.9925 15.3957 15.8915 11.9981 17.985C8.60052 15.8915 6.15807 13.9925 4.54179 12.1891C2.91677 10.3759 2.12684 8.65542 2.12684 6.92927C2.12684 4.26933 3.92442 1.98426 6.60548 1.98426C7.97082 1.98426 9.13499 2.57791 10.0657 3.74875C10.8099 4.68511 11.1234 5.65199 11.1256 5.65889C11.2447 6.04072 11.5982 6.3008 11.9982 6.3008C12.3981 6.3008 12.7517 6.04076 12.8707 5.65889C12.8736 5.64966 13.1777 4.71294 13.8975 3.79089C14.8332 2.59208 16.0085 1.98422 17.3908 1.98422C20.0746 1.98422 21.8694 4.27147 21.8694 6.92922C21.8694 8.65537 21.0795 10.3759 19.4545 12.1891Z"/>
                                        </svg>
                                    </button>
                                    <a href="#" class="bg-white dark:bg-title dark:text-white bg-opacity-80 flex items-center justify-center gap-2 p-[10px] mt-3 text-base leading-none text-title">
                                        <svg class="dark:text-white fill-current" width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.3167 5.28826H15.7291C15.3918 2.42331 12.9491 0.193359 9.99503 0.193359C7.04097 0.193359 4.59831 2.42331 4.26098 5.28826H1.67337C1.20438 5.28826 0.824219 5.66842 0.824219 6.1374V21.0824C0.824219 21.5514 1.20438 21.9316 1.67337 21.9316H18.3167C18.7857 21.9316 19.1658 21.5514 19.1658 21.0824V6.1374C19.1658 5.66842 18.7857 5.28826 18.3167 5.28826ZM9.99503 1.89166C12.0111 1.89166 13.6896 3.36302 14.014 5.28826H5.97605C6.30043 3.36302 7.97898 1.89166 9.99503 1.89166ZM17.4675 20.2333H2.52252V6.98655H4.22082V9.534C4.22082 10.003 4.60098 10.3832 5.06997 10.3832C5.53895 10.3832 5.91912 10.003 5.91912 9.534V6.98655H14.0709V9.534C14.0709 10.003 14.4511 10.3832 14.9201 10.3832C15.3891 10.3832 15.7692 10.003 15.7692 9.534V6.98655H17.4675V20.2333Z"/>
                                        </svg>    
                                        <span class="mt-1">Add to Cart</span>
                                    </a>
                                </div>
                            </div>
                            <div class="md:px-2 lg:px-4 xl:px-6 lg:pt-6 pt-3 flex items-center justify-between gap-3 flex-wrap">
                                <div>
                                    <h5 class="font-normal dark:text-white text-xl">
                                        <a href="#" class="text-underline">
                                            Premium Luxury Sofa
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
                                        <li class="dark:text-gray-100">( 1,230 )</li>
                                    </ul>
                                </div>
                                <h4 class="font-semibold leading-none italic dark:text-white text-xl md:text-2xl">$40</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 02 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-8 max-w-[903px] mt-12">
                        
                        <!-- includes/Short-Code/card-2.blade.php -->
                        @include('includes.Short-Code.card-2')

                    </div>

                    <!-- Card 03 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-8 max-w-5xl mt-12">

                        <!-- includes/Short-Code/card-3.blade.php -->
                        @include('includes.Short-Code.card-3')

                    </div>

                    <!-- Card 04 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-8 max-w-5xl mt-12">
                        
                        <!-- includes/Short-Code/card-4.blade.php -->
                        @include('includes.Short-Code.card-4')

                    </div>

                    <!-- Card 05 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-8 max-w-5xl mt-12">
                        
                        <!-- includes/Short-Code/card-5.blade.php -->
                        @include('includes.Short-Code.card-5')

                    </div>

                    <!-- Card 06 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-8 mt-12 max-w-[845px]">
                        
                        <!-- includes/Short-Code/card-6.blade.php -->
                        @include('includes.Short-Code.card-6')

                    </div>
                </div>

                <!-- Button Size & Pattern -->
                <div class="bg-white dark:bg-dark-card-bg p-5 mt-5 md:mt-8 lg:p-8 xl:p-12" id="button">
                    <h3 class="font-semibold dark:text-white text-2xl md:text-3xl">Button Size & Pattern</h3>
                    <div class="mt-5 md:mt-7 flex flex-wrap max-w-3xl justify-between gap-5">
                        <div class="flex items-center gap-2 sm:gap-4 flex-wrap">
                            <a class="btn btn-outline" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                            <a class="btn btn-outline btn-sm" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-4 flex-wrap">
                            <a class="btn btn-solid" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                            <a class="btn btn-solid btn-sm" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                        </div> 
                    </div>
                    <div class="mt-8 md:mt-12">
                        <h3 class="font-semibold dark:text-white text-2xl md:text-3xl">Badges</h3>
                        <div class="flex items-center gap-4 flex-wrap mt-5 md:mt-7">
                            <button class="text-title font-medium text-[15px] leading-none p-[10px] rounded-md bg-tertiary-midum">Interior</button>
                            <button class="text-title font-medium text-[15px] leading-none p-[10px] rounded-md bg-snow">Interior</button>
                            <button class="text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-primary-midum">Vase</button>
                        </div>
                    </div>
                    <div class="mt-8 md:mt-12">
                        <h3 class="font-semibold dark:text-white text-2xl md:text-3xl">Breadcumb</h3>
                        <div class="flex items-center gap-4 flex-wrap mt-5 md:mt-7 bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
                            <div class="text-center w-full">
                                <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Portfolio</h2>
                                <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
                                    <li><a href="#">Home</a></li>
                                    <li>/</li>
                                    <li class="text-primary">Portfolio</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 md:mt-12 text-center">
                        <h3 class="font-semibold dark:text-white text-2xl md:text-3xl text-center">Pagination</h3>
                        <!-- Pagination -->
                        <div class="mt-5 md:mt-7 flex items-center justify-center gap-[10px]">
                            <a href="#" class="text-title dark:text-white text-xl"><span class="lnr lnr-arrow-left"></span></a>         
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">01</a>        
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">02</a>        
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">03</a> 
                            <a href="#" class="text-title dark:text-white text-3xl sm:text-4xl transform">...</a>          
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">09</a>        
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">10</a>        
                                  
                            <a href="#" class="text-title dark:text-white text-xl"><span class="lnr lnr-arrow-right"></span></a>         
                        </div>

                        <div class="mt-3 md:mt-5 flex items-center gap-[10px] justify-center">
                            <a href="#" class="text-title dark:text-white text-xl"><span class="lnr lnr-arrow-left"></span></a>         
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">01</a>        
                            <a href="#" class="text-title dark:text-white text-3xl sm:text-4xl transform -translate-y-2">...</a>       
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">05</a> 
                            <a href="#" class="text-title dark:text-white text-3xl sm:text-4xl transform -translate-y-2">...</a>       
                            <a href="#" class="w-8 sm:w-10 h-8 sm:h-10 bg-title bg-opacity-5 flex items-center justify-center leading-none text-base sm:text-lg font-medium text-title transition-all duration-300 hover:bg-opacity-100 hover:text-white dark:bg-white dark:bg-opacity-5 dark:text-white dark:hover:bg-opacity-100 dark:hover:text-title">10</a>        
                            <a href="#" class="text-title dark:text-white text-3xl sm:text-4xl transform -translate-y-2">...</a>
                            <a href="#" class="text-title dark:text-white text-xl"><span class="lnr lnr-arrow-right"></span></a>         
                        </div>
                    </div>
                </div>

                <!-- Slider -->
                <div class="bg-white dark:bg-dark-card-bg p-5 mt-5 md:mt-8 lg:p-8 xl:p-12" id="carousel">
                    <div class="bg-[#FFF7F0] dark:bg-title px-5 md:px-10 lg:px-14 2xl:px-[90px] pb-12 md:pb-[70px] ">
                        <div class="carousel-slider-one owl-carousel" data-carousel-dots="true" data-carousel-items="1">
                            <div>
                                <div class="xl:flex xl:items-end gap-16 2xl:gap-20 justify-between">
                                    <div class="max-w-xs mx-auto lg:w-[200px] xl:w-[250px] 2xl:w-[326px] flex-none crsl-slider-one-thumb">
                                        <div class="relative">
                                            <img src="{{ asset('assets/img/shortcode/carousel/carousel-01.png') }}" alt="carousel">
                                            <!-- Price -->
                                            <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 rounded-full p-3 right-0 sm:right-[-10%] bottom-[17%]">
                                                <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full flex items-center justify-center">
                                                    <h4 class="font-normal leading-none text-2xl"><sup>$</sup>120</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-6 md:mt-9 xl:mt-12">
                                            <a href="#" class="text-lg leading-tight underline text-title dark:text-white underline-offset-4 inline-block">Buy Now</a>
                                        </div>
                                    </div>
                                    <div class="xl:flex-1 2xl:max-w-xl xl:pb-16 2xl:pb-24 mt-10 xl:mt-0 crsl-slider-one-content">
                                        <h4 class="leading-none font-medium dark:text-white text-2xl">All products in store</h4>
                                        <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-primary">25%</span> Off</h2>
                                        <p class="mt-4 md:mt-5 dark:text-white-light">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. From this Tellus vulputate habitasse ut diam habitant vel tempor varius. </p>
                                        <div class="button">
                                            <a class="btn btn-outline mt-6" href="#" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-8">
                                <div class="xl:flex xl:items-end gap-16 2xl:gap-20 justify-between">
                                    <div class="max-w-xs mx-auto lg:w-[200px] xl:w-[250px] 2xl:w-[326px] flex-none crsl-slider-one-thumb">
                                        <div class="relative">
                                            <img src="{{ asset('assets/img/shortcode/carousel/carousel-02.png') }}" alt="carousel">
                                            <!-- Price -->
                                            <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 rounded-full p-3 right-[30%] bottom-[30%]">
                                                <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full flex items-center justify-center">
                                                    <h4 class="font-normal leading-none text-2xl"><sup>$</sup>55</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6 md:mt-9 xl:mt-12 pl-14">
                                            <a href="#" class="text-lg leading-tight underline text-title dark:text-white underline-offset-4 inline-block">Buy Now</a>
                                        </div>
                                    </div>
                                    <div class="xl:flex-1 2xl:max-w-xl xl:pb-16 2xl:pb-24 mt-10 xl:mt-0 crsl-slider-one-content">
                                        <h4 class="leading-none font-medium dark:text-white text-2xl">All products in store</h4>
                                        <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-primary">25%</span> Off</h2>
                                        <p class="mt-4 md:mt-5 dark:text-white-light">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. From this Tellus vulputate habitasse ut diam habitant vel tempor varius. </p>
                                        <div class="button">
                                            <a class="btn btn-outline mt-6" href="#" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-8">
                                <div class="xl:flex xl:items-end gap-16 2xl:gap-20 justify-between">
                                    <div class="max-w-xs mx-auto lg:w-[200px] xl:w-[250px] 2xl:w-[326px] flex-none crsl-slider-one-thumb">
                                        <div class="relative">
                                            <img src="{{ asset('assets/img/shortcode/carousel/carousel-03.png') }}" alt="carousel">
                                            <!-- Price -->
                                            <div class="absolute z-30 flex items-center justify-center bg-[#BB976D] bg-opacity-20 rounded-full p-3 right-[18%] bottom-[24%]">
                                                <div class="w-16 md:w-20 h-16 md:h-20 bg-white rounded-full flex items-center justify-center">
                                                    <h4 class="font-normal leading-none text-2xl"><sup>$</sup>120</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pl-20 mt-6 md:mt-9 xl:mt-12">
                                            <a href="#" class="text-lg leading-tight underline text-title dark:text-white underline-offset-4 inline-block">Buy Now</a>
                                        </div>
                                    </div>
                                    <div class="xl:flex-1 2xl:max-w-xl xl:pb-16 2xl:pb-24 mt-10 xl:mt-0 crsl-slider-one-content">
                                        <h4 class="leading-none font-medium dark:text-white text-2xl">All products in store</h4>
                                        <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-primary">25%</span> Off</h2>
                                        <p class="mt-4 md:mt-5 dark:text-white-light">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. From this Tellus vulputate habitasse ut diam habitant vel tempor varius. </p>
                                        <div class="button">
                                            <a class="btn btn-outline mt-6" href="#" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Slider -->
                    <div class="py-8 md:py-12 px-5 md:px-10 bg-[#F5F5F5] dark:bg-title mt-8">
                        <div class="text-center mb-7">
                            <svg class="mx-auto" width="68" height="64" viewBox="0 0 68 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M66.9552 35.3366H58.3702C60.3018 31.9554 60.1533 27.7724 57.9867 24.5367C54.5985 19.3055 56.1839 17.394 56.1896 17.3825C56.4076 17.1537 56.3991 16.7914 56.1703 16.5734C56.0644 16.4724 55.9239 16.4158 55.7775 16.4153H54.2379C54.308 15.3413 54.4322 14.2716 54.6099 13.2102C55.1146 13.3856 55.6439 13.4803 56.1781 13.4906C56.8219 13.5214 57.4476 13.2733 57.8951 12.8096C58.6048 12.0197 60.1845 11.4989 60.7568 11.3616C61.0663 11.2976 61.2655 10.9948 61.2014 10.6852C61.1374 10.3757 60.8346 10.1765 60.525 10.2406C60.5106 10.2437 60.4962 10.2472 60.4819 10.2512C60.3904 10.2512 58.1411 10.8236 57.0479 12.0426C56.5901 12.552 55.4969 12.3116 54.8216 12.0713C55.3252 9.64456 56.2409 6.8573 57.9007 5.00866C58.1125 4.77315 58.0933 4.41057 57.8578 4.19881C57.6223 3.98705 57.2597 4.00622 57.0479 4.24174C56.339 5.05359 55.7475 5.96103 55.2909 6.93743C54.1462 6.32503 52.9157 5.38068 53.1503 4.85986C53.5796 3.91551 54.4839 1.56894 53.6197 0.28119C53.4588 0.00904531 53.1078 -0.0810972 52.8357 0.0797285C52.5636 0.240554 52.4734 0.591537 52.6342 0.863682C52.645 0.881854 52.6568 0.899453 52.6694 0.91648C53.03 1.48881 52.8068 2.86241 52.0971 4.40199C51.3874 5.94157 53.6767 7.40674 54.8157 8.00197C53.8238 10.7081 53.2451 13.5482 53.0987 16.4267H52.8927C52.8848 15.6244 52.7966 14.8249 52.6294 14.0401C52.4986 13.4569 52.3285 12.8833 52.12 12.3231C51.9927 11.9744 51.8476 11.6324 51.685 11.2986C51.5678 10.942 51.4912 10.5735 51.4561 10.1997C51.3416 9.38129 51.2329 8.60292 50.7979 8.16794C50.5753 7.94359 50.213 7.94201 49.9885 8.16465C49.9873 8.1658 49.9862 8.1668 49.9852 8.16794C49.7633 8.39115 49.7633 8.75172 49.9852 8.97493C50.1807 9.41234 50.295 9.88165 50.3229 10.36C50.3662 10.8519 50.478 11.3354 50.6548 11.7965C50.7922 12.077 50.9238 12.3689 51.0497 12.718C51.1127 12.8954 51.1699 13.0786 51.2272 13.2903C50.6796 13.2265 50.192 12.914 49.9051 12.4433C49.25 11.5202 48.4808 10.6836 47.6157 9.95362C47.3742 9.74973 47.0131 9.78021 46.8092 10.0217C46.809 10.0219 46.8089 10.0222 46.8088 10.0223C46.6049 10.2638 46.6353 10.625 46.8769 10.8289C46.877 10.829 46.8773 10.8292 46.8774 10.8293C47.6406 11.486 48.3227 12.2315 48.9092 13.0499C49.4489 13.9286 50.4137 14.4556 51.4446 14.435H51.5651C51.6943 15.095 51.7633 15.7655 51.7712 16.4382H49.9855C49.7593 16.4396 49.5551 16.5742 49.4647 16.7816C49.3717 16.9886 49.4099 17.2311 49.562 17.3997C49.562 17.3997 51.1645 19.3284 47.7763 24.5596C45.6097 27.7953 45.4612 31.9783 47.3928 35.3595H34.2692C34.619 34.6653 34.8953 33.9364 35.0934 33.1846H36.0606C37.3787 33.1848 38.4474 32.1164 38.4474 30.7981C38.4475 29.4801 37.379 28.4114 36.0609 28.4114C36.0608 28.4114 36.0606 28.4114 36.0605 28.4114H35.4881C35.4416 27.8655 35.3632 27.3228 35.2535 26.7859C35.1982 26.5151 34.9574 26.3225 34.6811 26.3281H26.3938C26.1175 26.3225 25.8767 26.5151 25.8214 26.7859C25.622 27.7364 25.5222 28.7051 25.5238 29.6762C25.4979 31.6432 25.931 33.5891 26.7887 35.3595H21.9238V31.3532C21.9238 31.0371 21.6676 30.7808 21.3515 30.7808H20.0809V29.4988H21.3515C21.6676 29.4988 21.9238 29.2425 21.9238 28.9265V25.8817C21.9238 25.5656 21.6676 25.3093 21.3515 25.3093H3.94699C3.63092 25.3093 3.37466 25.5656 3.37466 25.8817V28.3484H2.10408C1.78801 28.3484 1.53175 28.6047 1.53175 28.9207V31.336C1.53175 31.6521 1.78801 31.9083 2.10408 31.9083H3.37466V35.3423H1.05671C0.740637 35.3423 0.484375 35.5986 0.484375 35.9147V48.9238C0.484375 49.2399 0.740637 49.4961 1.05671 49.4961H1.53747V51.9228C1.53747 52.2389 1.79373 52.4952 2.1098 52.4952H8.89767L9.60737 62.6655C9.6284 62.9659 9.87865 63.1985 10.1797 63.1978H13.0414C13.3424 63.1985 13.5927 62.9659 13.6137 62.6655L14.3234 52.4952H53.6885L54.3982 62.6655C54.4192 62.9659 54.6695 63.1985 54.9705 63.1978H57.8322C58.1332 63.1985 58.3835 62.9659 58.4045 62.6655L59.1142 52.4952H65.9021C66.2181 52.4952 66.4744 52.2389 66.4744 51.9228V49.4961H66.9552C67.2712 49.4961 67.5275 49.2399 67.5275 48.9238V35.9089C67.5275 35.5929 67.2712 35.3366 66.9552 35.3366ZM35.5283 29.6533V29.5331H36.0606C36.7466 29.533 37.3027 30.089 37.3027 30.775C37.3029 31.4609 36.7468 32.0171 36.0609 32.0171C36.0608 32.0171 36.0606 32.0171 36.0605 32.0171H35.3279C35.4617 31.2363 35.5288 30.4456 35.5283 29.6533ZM48.7149 25.1548C51.3018 21.1485 51.2503 18.7618 50.8783 17.5599H54.8847C54.5126 18.7618 54.4611 21.1599 57.0481 25.1548C59.1284 28.2216 59.1284 32.2469 57.0481 35.3137H48.7264C48.7264 35.3137 48.7206 35.3194 48.7149 35.3137C46.6644 32.2379 46.6644 28.2308 48.7149 25.1548ZM26.6858 29.6533C26.6865 28.915 26.7479 28.1781 26.8689 27.4499H34.2005C34.3244 28.1777 34.3857 28.915 34.3837 29.6533C34.4306 31.6425 33.936 33.6069 32.9528 35.3366H28.1166C27.1335 33.6069 26.6389 31.6425 26.6858 29.6533ZM20.7793 31.9141V35.3481H4.51933V31.9141H20.7793ZM4.51933 26.454H20.7793V28.3484H4.51933V26.454ZM2.67641 30.7694V29.4988H3.78102C3.83396 29.5196 3.89019 29.5311 3.94699 29.5331C4.00365 29.53 4.0596 29.5184 4.11297 29.4988H18.9364V30.7694H2.67641ZM1.62904 48.3515V36.4927H33.4336V48.3515H1.62904ZM12.5034 62.0531H10.712L10.0481 52.4952H13.1787L12.5034 62.0531ZM57.2942 62.0531H55.4971L54.8274 52.4952H57.9581L57.2942 62.0531ZM65.3068 51.3505H2.68213V49.4961H65.3297L65.3068 51.3505ZM66.3599 48.3515H34.5783V36.4813H66.3828L66.3599 48.3515Z" fill="#BB976D"/>
                                <path d="M60.0145 41.8447H56.1856C55.8695 41.8447 55.6133 42.101 55.6133 42.4171C55.6133 42.7331 55.8695 42.9894 56.1856 42.9894H60.0145C60.3306 42.9894 60.5869 42.7331 60.5869 42.4171C60.5869 42.101 60.3306 41.8447 60.0145 41.8447Z" fill="#BB976D"/>
                                <path d="M8.60347 41.8438H4.40827C4.0922 41.8438 3.83594 42.1 3.83594 42.4161C3.83594 42.7322 4.0922 42.9884 4.40827 42.9884H8.60347C8.91954 42.9884 9.17581 42.7322 9.17581 42.4161C9.17581 42.1 8.91954 41.8438 8.60347 41.8438Z" fill="#BB976D"/>
                                <path d="M55.3979 27.12C55.2451 26.8433 54.897 26.7428 54.6203 26.8956C54.6163 26.8979 54.6121 26.9002 54.6081 26.9025C54.3271 27.0472 54.2165 27.3921 54.361 27.6733C54.3613 27.6739 54.3617 27.6746 54.362 27.6752C54.362 27.6752 55.2148 29.3922 54.322 32.4255C54.2297 32.7279 54.3999 33.0478 54.7023 33.1399C54.7033 33.1402 54.7044 33.1405 54.7054 33.1409C54.7607 33.1465 54.8162 33.1465 54.8714 33.1409C55.1342 33.1514 55.3703 32.9814 55.4437 32.7289C56.4797 29.2205 55.4437 27.2059 55.3979 27.12Z" fill="#BB976D"/>
                            </svg>
                            <h2 class="dark:text-white font-bold leading-none mt-4 text-4xl">Testimonial</h2>
                        </div>
                        <div class="relative lg:px-14">
                            <div class="max-w-3xl mx-auto carousel-slider-two owl-carousel" data-carousel-loop="true" data-carousel-autoplay="true">
                                
                                <!-- includes/Short-Code/testimonial.blade.php -->
                                @include('includes.Short-Code.testimonial')

                            </div>
                            <div class=" lg:absolute lg:top-[64%] lg:left-0 transform lg:-translate-y-2/4 mt-6 xl:mt-0 w-full">
                                <div class="flex gap-2 md:gap-4 justify-center lg:justify-between">
                                    <button class="crslSlider02_prev w-9 md:w-11 h-9 md:h-11 border border-tertiary flex items-center justify-center text-tertiary hover:bg-primary hover:text-title hover:border-transparent transition-all duration-300">
                                        <span class="lnr lnr-arrow-left"></span>
                                    </button>
                                    <button class="crslSlider02_next w-9 md:w-11 h-9 md:h-11 border border-tertiary flex items-center justify-center text-tertiary hover:bg-primary hover:text-title hover:border-transparent transition-all duration-300">
                                        <span class="lnr lnr-arrow-right"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slider 03 -->
                    <div class="mt-8">
                        <div class="carousel-slider-three owl-carousel" data-carousel-dots="true" data-carousel-items="1">
                            <div class="lg:flex items-center justify-between gap-8">
                                <div class="lg:max-w-lg w-full relative before:absolute before:w-full before:h-full before:top-0 before:left-0 slider-gradient-overlay crsl-slider-thumb">
                                    <img class="w-full" src="{{ asset('assets/img/shortcode/carousel/carousel-04.jpg') }}" alt="banner-slider">
                                </div>
                                <div class="lg:max-w-[617px] w-full mt-8 xl:mt-0 crsl-slider-content">
                                    <h4 class="leading-none font-medium dark:text-white text-2xl">All products in store</h4>
                                    <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-secondary">25%</span> Off</h2>
                                    <p class="mt-4 md:mt-5 dark:text-white-light">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. From this Tellus vulputate habitasse ut diam habitant vel tempor varius. </p>
                                    <div class="button">
                                        <a class="btn btn-outline mt-4 md:mt-6" href="#" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:flex items-center justify-between gap-8">
                                <div class="lg:max-w-lg w-full relative before:absolute before:w-full before:h-full before:top-0 before:left-0 slider-gradient-overlay crsl-slider-thumb">
                                    <img class="w-full" src="{{ asset('assets/img/shortcode/carousel/carousel-05.jpg') }}" alt="banner-slider">
                                </div>
                                <div class="lg:max-w-[617px] w-full mt-8 xl:mt-0 crsl-slider-content">
                                    <h4 class="leading-none font-medium dark:text-white">All products in store</h4>
                                    <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-secondary">25%</span> Off</h2>
                                    <p class="mt-4 md:mt-5 dark:text-white-light">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. From this Tellus vulputate habitasse ut diam habitant vel tempor varius. </p>
                                    <div class="button">
                                        <a class="btn btn-outline mt-4 md:mt-6" href="#" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:flex items-center justify-between gap-8">
                                <div class="lg:max-w-lg w-full relative before:absolute before:w-full before:h-full before:top-0 before:left-0 slider-gradient-overlay crsl-slider-thumb">
                                    <img class="w-full" src="{{ asset('assets/img/shortcode/carousel/carousel-06.jpg') }}" alt="banner-slider">
                                </div>
                                <div class="lg:max-w-[617px] w-full mt-8 xl:mt-0 crsl-slider-content">
                                    <h4 class="leading-none font-medium dark:text-white">All products in store</h4>
                                    <h2 class="leading-none text-4xl sm:text-5xl xl:text-6xl 2xl:text-7xl font-bold mt-6 dark:text-white">Get <span class="text-secondary">25%</span> Off</h2>
                                    <p class="mt-4 md:mt-5 dark:text-white-light">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. From this Tellus vulputate habitasse ut diam habitant vel tempor varius. </p>
                                    <div class="button">
                                        <a class="btn btn-outline mt-4 md:mt-6" href="#" data-text="Let's Shop Now"><span>Let's Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slider 04 -->
                    <div class="mt-8">
                        <div class="carousel-slider-four owl-carousel" data-carousel-dots="true" data-carousel-items="1">
                            <div class="relative pt-12 md:pt-16 pb-12 sm:pb-24 xl:pb-[155px] px-5 sm:px-12">
                                <div class="absolute w-full h-full top-0 left-0 bg-[#F5F5F5] dark:bg-title before:absolute before:w-full before:h-full before:top-0 before:left-0 dark:before:bg-title dark:before:bg-opacity-70 before:z-[1]">
                                    <img class="w-full h-full object-cover slider-img" src="{{ asset('assets/img/shortcode/carousel/carousel-07.png') }}" alt="banner-slider">
                                </div>
                                <div class="relative z-10 max-w-[632px] slider-content">
                                    <div class="flex items-end content-top">
                                        <span class="font-bold text-5xl sm:text-7xl xl:text-9xl text-title leading-none dark:text-white">2025</span>
                                        <img class="-ml-5 sm:-ml-10 w-[150px] sm:w-[200px] lg:w-[250px] xl:w-full" src="{{ asset('assets/img/shortcode/carousel/Summer.png') }}" alt="summer">
                                    </div>
                                    <h2 class="mt-[10px] font-normal text-3xl sm:text-4xl xl:text-5xl leading-none dark:text-white">New Arrival Item</h2>
                                    <p class="dark:text-white-light mt-3 md:mt-4">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. Tellus vulputate habitasse ut diam. Auctor sit elementum habitant vel tempor varius. </p>
                                    <div class="button mt-4 md:mt-6">
                                        <a class="btn btn-outline" href="#" data-text="Shop Now"><span>Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="relative pt-12 md:pt-16 pb-12 sm:pb-24 xl:pb-[155px] px-5 sm:px-12">
                                <div class="absolute w-full h-full top-0 left-0 bg-[#F5F5F5] dark:bg-title before:absolute before:w-full before:h-full before:top-0 before:left-0 dark:before:bg-title dark:before:bg-opacity-70 before:z-[1]">
                                    <img class="w-full h-full object-cover slider-img" src="{{ asset('assets/img/shortcode/carousel/carousel-08.png') }}" alt="banner-slider">
                                </div>
                                <div class="relative z-10 max-w-[632px] slider-content">
                                    <div class="flex items-end content-top">
                                        <span class="font-bold text-5xl sm:text-7xl xl:text-9xl text-title leading-none dark:text-white">2025</span>
                                        <img class="-ml-5 sm:-ml-10 w-[150px] sm:w-[200px] lg:w-[250px] xl:w-full" src="{{ asset('assets/img/shortcode/carousel/Summer.png') }}" alt="summer">
                                    </div>
                                    <h2 class="mt-[10px] font-normal text-3xl sm:text-4xl xl:text-5xl leading-none dark:text-white">New Arrival Item</h2>
                                    <p class="dark:text-white-light mt-3 md:mt-4">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. Tellus vulputate habitasse ut diam. Auctor sit elementum habitant vel tempor varius. </p>
                                    <div class="button mt-4 md:mt-6">
                                        <a class="btn btn-outline" href="#" data-text="Shop Now"><span>Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="relative pt-12 md:pt-16 pb-12 sm:pb-24 xl:pb-[155px] px-5 sm:px-12">
                                <div class="absolute w-full h-full top-0 left-0 bg-[#F5F5F5] dark:bg-title before:absolute before:w-full before:h-full before:top-0 before:left-0 dark:before:bg-title dark:before:bg-opacity-70 before:z-[1]">
                                    <img class="w-full h-full object-cover slider-img" src="{{ asset('assets/img/shortcode/carousel/carousel-09.png') }}" alt="banner-slider">
                                </div>
                                <div class="relative z-10 max-w-[632px] slider-content">
                                    <div class="flex items-end content-top">
                                        <span class="font-bold text-5xl sm:text-7xl xl:text-9xl text-title leading-none dark:text-white">2025</span>
                                        <img class="-ml-5 sm:-ml-10 w-[150px] sm:w-[200px] lg:w-[250px] xl:w-full" src="{{ asset('assets/img/shortcode/carousel/Summer.png') }}" alt="summer">
                                    </div>
                                    <h2 class="mt-[10px] font-normal text-3xl sm:text-4xl xl:text-5xl leading-none dark:text-white">New Arrival Item</h2>
                                    <p class="dark:text-white-light mt-3 md:mt-4">Lorem ipsum dolor sit amet, consectetur purus integer elementum in. Tellus vulputate habitasse ut diam. Auctor sit elementum habitant vel tempor varius. </p>
                                    <div class="button mt-4 md:mt-6">
                                        <a class="btn btn-outline" href="#" data-text="Shop Now"><span>Shop Now</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="bg-white dark:bg-dark-card-bg p-5 mt-5 md:mt-8 lg:p-8 xl:p-12" id="newsletter">
                    <div class="py-12">
                        <div class="max-w-xl mx-auto text-center">
                            <div class="flex items-center justify-center">
                                <img src="{{ asset('assets/img/shortcode/newsletter.svg') }}" alt="Newsletter">
                            </div>
                            <h2 class="font-bold leading-none mt-4 dark:text-white text-4xl">Newsletter</h2>
                            <p class="mt-4 dark:text-white-light ">Lorem ipsum dolor sit amet, consectetur purus habitasse ut diam. Auctor sit elementum habitant vel tempor varius. </p>
                            <div class="mt-6 lg:mt-12 sm:flex">
                                <input class="w-full h-12 md:h-14 bg-snow border dark:bg-snow dark:bg-opacity-5 border-title focus:border-tertiary border-opacity-10 p-4 outline-none dark:text-white sm:flex-1 sm:border-r-0" type="text" placeholder="Enter your email address">
                                <button class="w-full h-12 bg-title text-white flex items-center justify-center text-base md:text-lg font-medium p-3 mt-3 sm:mt-0 sm:w-32 sm:h-auto sm:flex-none">Subscribe</button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-title bg-opacity-10 dark:bg-title py-10 px-5 md:px-7 lg:px-12 xl:px-24 xl:pb-24 xl:pt-0 sm:pt-0 sm:pb-12 md:py-12">
                        <div class="max-w-4xl flex justify-between items-end gap-7">
                            <div class=" sm:max-w-md w-full xl:pt-20">
                                <h2 class="font-bold leading-none dark:text-white text-4xl">Newsletter</h2>
                                <p class="mt-3 md:mt-5 dark:text-white-light">Lorem ipsum dolor sit amet, consectetur purus habitasse ut diam. Auctor sit elementum habitant vel tempor varius. </p>
                                <div class="mt-4 lg:mt-6 sm:flex">
                                    <input class="w-full h-12 md:h-14 bg-snow border dark:bg-snow dark:bg-opacity-5 border-title focus:border-primary border-opacity-10 p-4 outline-none dark:text-white sm:flex-1 sm:border-r-0" type="text" placeholder="Enter your email address">
                                    <button class="w-full h-12 bg-title text-white flex items-center justify-center text-base md:text-lg font-medium p-3 mt-3 sm:mt-0 sm:w-32 sm:h-auto sm:flex-none dark:bg-primary dark:text-title">Subscribe</button>
                                </div>
                            </div>
                            <div class="hidden sm:block md:hidden xl:block">
                                <img src="{{ asset('assets/img/shortcode/newsletter-2.png') }}" alt="Newsletter">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blog -->
                <div class="bg-white dark:bg-dark-card-bg p-5 mt-5 md:mt-8 lg:p-8 xl:p-12" id="blog">
                    <!-- Blog Card 01 -->
                    <div class="max-w-5xl grid sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-7">
                        
                        <!-- includes/Short-Code/blog-1.blade.php -->
                        @include('includes.Short-Code.blog-1')

                    </div>

                    <!-- Blog Card 02 -->
                    <div class="max-w-[750px] mt-12">
                        <div class="relative group">
                            <div class="overflow-hidden">
                                <img class="duration-300 transform scale-100 group-hover:scale-110 w-full" src="{{ asset('assets/img/shortcode/blog/blog-04.jpg') }}" alt="blog-card">
                            </div>
                            <div class="sm:bg-white sm:bg-opacity-90 sm:dark:bg-title sm:dark:bg-opacity-90 mt-4 sm:p-5 md:p-6 sm:absolute z-10 bottom-0 left-0 sm:w-11/12 max-w-md ">
                                <ul class="flex items-center gap-[10px] flex-wrap">
                                    <li class="text-[15px] leading-none dark:text-white">6 Sep, 2025</li>
                                    <li><a href="#" class="inline-block text-title font-medium text-[15px] leading-none py-[10px] px-5 rounded-md bg-[#dbcbbd]">Interior</a></li>
                                </ul>
                                <h5 class="mt-3 font-medium dark:text-white leading-[1.5] text-xl"><a href="#" class="text-underline">Consectetur purus habitasse ut diam habitant varius. </a></h5>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Card 03 -->
                    <div class="max-w-6xl mt-12 grid grid-cols-1 xl:grid-cols-2 gap-7 md:gap-9">
                        
                        <!-- includes/Short-Code/blog-3.blade.php -->
                        @include('includes.Short-Code.blog-3')

                    </div>

                    <!-- Blog Card 04 -->
                    <div class="max-w-5xl mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-7">
                        
                        <!-- includes/Short-Code/blog-4.blade.php -->
                        @include('includes.Short-Code.blog-4')

                    </div>

                    <!-- Blog Card 05 -->
                    <div class="max-w-4xl mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-[30px]">
                        
                        <!-- includes/Short-Code/blog-5.blade.php -->
                        @include('includes.Short-Code.blog-5')

                    </div>
                </div>

                <!-- Partner -->
                <div class="bg-white dark:bg-dark-card-bg p-5 mt-5 md:mt-8 lg:p-8 xl:p-12" id="partner">
                    <div>
                        
                        <!-- includes/Short-Code/partner-1.blade.php -->
                        @include('includes.Short-Code.partner-1')

                    </div>

                    <!-- Partner 02 -->
                    <div class="bg-tertiary-light mt-12 px-5 py-7 md:py-12 dark:bg-tertiary dark:bg-opacity-20 bg-[#F5F5F5]">
                        
                        <!-- includes/Short-Code/partner-2.blade.php -->
                        @include('includes.Short-Code.partner-2')

                    </div>

                    <!-- Partner 03 -->
                    <div class="mt-12 bg-title py-9 md:py-12 px-6 md:px-10 lg:px-12 xl:px-16 2xl:flex items-center gap-10">
                        <div class="max-w-[273px] w-full">
                            <h4 class="text-white leading-none text-2xl font-bold">Top Brands</h4>
                            <p class="text-white mt-[10px]">Lorem ipsum dolor sitsit elementum habitant vel tempor varius. </p> 
                        </div>
                        <div class="w-[2px] h-12 bg-primary relative hidden 2xl:block"></div>

                        <div class="2xl:max-w-[737px] w-full md:pr-28 relative mt-10 2xl:mt-0">
                            
                            <!-- includes/Short-Code/partner-3.blade.php -->
                            @include('includes.Short-Code.partner-3')

                            <div class="md:absolute md:top-[72%] md:right-0 transform md:-translate-y-2/4 mt-6 md:mt-0">
                                <div class="flex gap-4">
                                    <button class="icon ptnrSlider01_prev w-9 h-9 border border-white flex items-center justify-center text-white">
                                        <span class="lnr lnr-arrow-left"></span>
                                    </button>
                                    <button class="icon ptnrSlider01_next w-9 h-9 border border-white flex items-center justify-center text-white">
                                        <span class="lnr lnr-arrow-right"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Partner 04 -->
                    <div class="mt-12 bg-[#F5F5F5] dark:bg-title py-10 md:py-14 xl:py-24 px-8">
                        <div class="md:px-20 relative">
                            
                            <!-- includes/Short-Code/partner-4.blade.php -->
                            @include('includes.Short-Code.partner-4')

                            <div class="md:absolute md:top-[74%] md:left-0 transform md:-translate-y-2/4 mt-6 md:mt-0 w-full">
                                <div class="flex gap-2 md:gap-4 justify-center md:justify-between">
                                    <button class="icon ptnrSlider02_prev w-9 h-9 border dark:border-white flex items-center justify-center text-title border-title dark:text-white">
                                        <span class="lnr lnr-arrow-left"></span>
                                    </button>
                                    <button class="icon ptnrSlider02_next w-9 h-9 border border-title dark:border-white flex items-center justify-center text-title dark:text-white">
                                        <span class="lnr lnr-arrow-right"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer6')
  
@endsection