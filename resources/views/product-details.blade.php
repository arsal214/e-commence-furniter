<!-- resources/views/product-details.blade.php -->
@extends('layouts.main')

@section('title', 'Product-Details Page')

@section('content')

@include('includes.navbar')

<!-- Search -->
<div class="search_popup fixed top-0 left-0 bg-red dark:bg-[#39434D] bg-opacity-90 dark:bg-opacity-80 backdrop-blur-[3px] dark:backdrop-blur-[7.5px] w-full h-screen z-[999] px-[15px] md:px-[30px] py-12 md:py-[70px] overflow-y-auto transform scale-90 opacity-0 invisible transition-all duration-300 flex items-center justify-center">
    <div class="container">
        <div class="relative max-w-4xl mx-auto hdr-search-wrapper">
            <button class="hdr_search_close w-[36px] h-[36px] absolute bottom-full md:top-0 right-0 flex items-center justify-center bg-title dark:bg-white text-white dark:text-title">
                <svg class="fill-current" width="15" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.742 12.0717C11.6006 12.2131 11.445 12.2838 11.2753 12.2838C11.1056 12.2838 10.9501 12.2131 10.8086 12.0717L6.16295 7.42598L1.55968 12.0292C1.41826 12.1707 1.2627 12.2414 1.09299 12.2414C0.923289 12.2414 0.767726 12.1707 0.626304 12.0292L0.32932 11.7323C0.187898 11.5908 0.117187 11.4353 0.117188 11.2656C0.117187 11.0959 0.187898 10.9403 0.329319 10.7989L4.93258 6.19561L0.414172 1.6772C0.272751 1.53578 0.20204 1.38021 0.20204 1.21051C0.20204 1.0408 0.272751 0.885239 0.414172 0.743817L0.73237 0.42562C0.873792 0.284198 1.02935 0.213487 1.19906 0.213487C1.36877 0.213488 1.52433 0.284198 1.66575 0.42562L6.18416 4.94403L10.8086 0.319553C10.9501 0.178132 11.1056 0.107421 11.2753 0.107422C11.445 0.107422 11.6006 0.178133 11.742 0.319554L12.039 0.616539C12.1804 0.75796 12.2511 0.913524 12.2511 1.08323C12.2511 1.25293 12.1804 1.4085 12.039 1.54992L7.41453 6.1744L12.0602 10.8201C12.2016 10.9615 12.2724 11.1171 12.2724 11.2868C12.2724 11.4565 12.2016 11.612 12.0602 11.7535L11.742 12.0717Z"/>
                </svg>
            </button>

            <div class="bg-white dark:bg-title py-8 sm:py-10 md:py-[60px] px-5 sm:px-8">
                <!-- Input -->
                <div class="relative">
                    <input class="outline-none border-b border-bdr-clr dark:border-bdr-clr-drk pb-4 md:pb-[22px] text-title w-full pr-7 md:pr-10 leading-none font-lg placeholder:text-title bg-transparent dark:bg-transparent dark:text-white dark:placeholder:text-white" type="text" placeholder="Type your keyword">
                    <button class="absolute right-0 top-0">
                        <svg class="fill-current text-title dark:text-white w-5 md:w-[30px]" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M29.5439 28.2361L22.1484 20.5625C24.0499 18.3074 25.0917 15.4701 25.0917 12.5162C25.0917 5.61489 19.4635 0 12.5459 0C5.62818 0 0 5.61489 0 12.5162C0 19.4176 5.62818 25.0325 12.5459 25.0325C15.1429 25.0325 17.6177 24.251 19.7335 22.7676L27.1852 30.4994C27.4967 30.8221 27.9156 31 28.3646 31C28.7895 31 29.1926 30.8384 29.4986 30.5445C30.1488 29.9203 30.1695 28.8853 29.5439 28.2361ZM12.5459 3.26511C17.6591 3.26511 21.8189 7.41506 21.8189 12.5162C21.8189 17.6174 17.6591 21.7674 12.5459 21.7674C7.43261 21.7674 3.27283 17.6174 3.27283 12.5162C3.27283 7.41506 7.43261 3.26511 12.5459 3.26511Z"/>
                        </svg>
                    </button>
                </div>
                <!-- Tags -->
                <div class="mt-10 md:mt-12">
                    <h4 class="font-medium leading-none">Popular Tags</h4>
                    <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Chair"><span>Chair</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Art & Paint"><span>Art & Paint</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Mirror"><span>Mirror</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Table"><span>Table</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Lamp"><span>Lamp</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Banner Start -->
<div class="bg-[#F8F5F0] dark:bg-dark-secondary py-5 md:py-[30px]">
    <div class="container-fluid">
        <ul class="flex items-center gap-[10px] text-base md:text-lg leading-none font-normal text-title dark:text-white max-w-[1720px] mx-auto flex-wrap">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li><a href="{{ url('/shop-v1') }}">Shop</a></li>
            <li>/</li>
            <li class="text-primary">
                @if(!empty($item['title']))
                    {{ $item['title'] }}
                @else
                    Classic Relaxable Chair
                @endif
            </li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Product Slider Start -->
<div class="s-py-50" data-aos="fade-up">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto flex justify-between gap-10 flex-col lg:flex-row">
            <div class="w-full lg:w-[58%]">
                <div class="relative product-dtls-wrapper">
                    <button class="absolute top-5 left-0 p-2 !bg-[#E13939] text-lg leading-none text-white font-medium z-50">-10%</button>
                    <div class="product-dtls-slider ">
                        <div><img src="{{ !empty($item['img']) ? asset($item['img']) : asset('assets/img/gallery/product-detls/product-01.jpg') }}" class="w-full" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-02.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-03.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-04.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-01.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-02.jpg') }}" alt="product"></div>
                    </div>
                    <div class="product-dtls-nav">
                        <div><img src="{{ !empty($item['img']) ? asset($item['img']) : asset('assets/img/gallery/product-detls/product-01.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-02.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-03.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-04.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-01.jpg') }}" alt="product"></div>
                        <div><img src="{{ asset('assets/img/gallery/product-detls/product-02.jpg') }}" alt="product"></div>
                    </div>
                </div>
            </div>
            <div class="lg:max-w-[635px] w-full">
                <div class="pb-4 sm:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk">
                    <h2 class="font-semibold leading-none md:text-4xl">
                        @if(!empty($item['title']))
                            {{ $item['title'] }}
                        @else
                            Classic Relaxable Chair
                        @endif
                    </h2>
                    <div class="flex gap-4 items-center mt-[15px]">
                        <span class="text-lg sm:text-xl leading-none pb-[5px] text-title line-through pl-2 inline-block dark:text-white">$140.99</span>
                        <span class="text-2xl sm:text-3xl text-primary leading-none block">
                            @if(!empty($item['price']))
                                {{ $item['price'] }}
                            @else
                                $85.00
                            @endif
                        </span>
                    </div>

                    <div class="mt-5 md:mt-7 flex items-center gap-4 flex-wrap">
                        <h4 class="text-xl md:text-[22px] font-semibold !leading-none">Hurry Up!</h4>
                        <div class="overflow-auto">
                            <div class="py-2 px-3 bg-[#FAF2F2] rounded-[51px] flex items-end gap-[6px] w-[360px]">
                                <svg class="w-[15px]" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.6923 7.59087C12.6383 7.52329 12.573 7.53657 12.5387 7.55036C12.51 7.562 12.4442 7.59919 12.4533 7.69239C12.4642 7.80431 12.4704 7.91841 12.4715 8.03157C12.4764 8.50102 12.2881 8.96094 11.9549 9.2934C11.6238 9.62371 11.1884 9.80168 10.7247 9.79652C10.0913 9.78844 9.56601 9.45809 9.20551 8.84118C8.90742 8.33106 9.03844 7.67313 9.17715 6.97654C9.25832 6.5688 9.34227 6.14716 9.34227 5.74588C9.34227 2.62132 7.24173 0.818669 5.98962 0.0222265C5.96373 0.00578123 5.93908 0 5.91724 0C5.88173 0 5.85361 0.0153124 5.83974 0.0246874C5.81287 0.0428905 5.76986 0.0843747 5.78369 0.157812C6.26228 2.69929 4.83478 4.22783 3.32346 5.84611C1.76566 7.51419 0 9.40485 0 12.8147C0 16.7767 3.22331 20 7.18532 20C10.4475 20 13.3237 17.7256 14.1796 14.4692C14.7633 12.2487 14.1517 9.42031 12.6923 7.59087ZM7.36458 18.4663C6.37247 18.5115 5.42896 18.1557 4.7083 17.4667C3.99537 16.7849 3.58647 15.8336 3.58647 14.8565C3.58647 13.0228 4.28756 11.6768 6.17326 9.88973C6.20412 9.86047 6.23572 9.85121 6.26326 9.85121C6.28822 9.85121 6.30986 9.85883 6.32474 9.86598C6.35611 9.88109 6.40767 9.91852 6.40072 9.99945C6.33329 10.784 6.33447 11.4352 6.40415 11.9351C6.58228 13.2118 7.51692 14.0697 8.73 14.0697C9.32477 14.0697 9.89129 13.8458 10.3252 13.4394C10.3756 13.3922 10.4318 13.3982 10.4534 13.4028C10.4819 13.409 10.5202 13.4265 10.5402 13.4748C10.7202 13.9092 10.8121 14.3703 10.8135 14.8453C10.8193 16.7564 9.27207 18.3808 7.36458 18.4663Z" fill="#E13939"/>
                                </svg>                                
                                <h6 class="text-lg font-medium leading-none !text-[#E13939] whitespace-nowrap">Sale Ends :</h6>
                                <div class="countdown-clock flex gap-[10px] items-center">
                                    <div class="countdown-item flex">
                                        <div class="ci-inner text-lg font-medium leading-none text-[#E13939]">
                                            <div class="clock-days ci-value"></div>
                                        </div>
                                        <p class="text-lg font-medium leading-none text-[#E13939]">D</p>
                                    </div>
                                    <p class="text-lg font-medium leading-none text-[#E13939]">:</p>
                                    <div class="countdown-item flex">
                                        <div class="ci-inner text-lg font-medium leading-none text-[#E13939]">
                                            <div class="clock-hours ci-value"></div>
                                        </div>
                                        <p class="text-lg font-medium leading-none text-[#E13939]">H</p>
                                    </div>
                                    <p class="text-lg font-medium leading-none text-[#E13939]">:</p>
                                    <div class="countdown-item flex">
                                        <div class="ci-inner text-lg font-medium leading-none text-[#E13939]">
                                            <div class="clock-minutes ci-value"></div>
                                        </div>
                                        <p class="text-lg font-medium leading-none text-[#E13939]">M</p>
                                    </div>
                                    <p class="text-lg font-medium leading-none text-[#E13939]">:</p>
                                    <div class="countdown-item flex">
                                        <div class="ci-inner text-lg font-medium leading-none text-[#E13939]">
                                            <div class="clock-seconds ci-value"></div>
                                        </div>
                                        <p class="text-lg font-medium leading-none text-[#E13939]">S</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="sm:text-lg mt-5 md:mt-7">
                        Experience the epitome of relaxation with our Classic Relaxable Chair. Crafted with plush cushioning and ergonomic design, it offers unparalleled comfort for lounging or reading. Its timeless style seamlessly blends with any decor, while the sturdy construction ensures durability for years to come.
                    </p>
                </div>
                <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up" data-aos-delay="200">
                    <div class="inc-dec flex items-center gap-2">
                        <button class="dec w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                            <svg class="fill-current text-title dark:text-white" width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.4361 0.203613H12.0736L7.81774 0.203615H13.8729V1.80309H7.81774L3.50809 1.80309H1.87053L6.18017 1.80309H0.125V0.203615H6.18017L10.4361 0.203613Z"/>
                            </svg>
                        </button>
                        <input class="w-6 h-auto outline-none bg-transparent text-base mg:text-lg leading-none text-title dark:text-white text-center" type="text" value="1">
                        <button class="inc  w-8 h-8 bg-[#E8E9EA] dark:bg-dark-secondary flex items-center justify-center">
                            <svg class="fill-current text-title dark:text-white" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.18017 0.110352H7.81774V6.16553H13.8729V7.76501H7.81774V13.8963H6.18017V7.76501H0.125V6.16553H6.18017V0.110352Z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex gap-4 mt-4 sm:mt-6">
                        <a href="{{ url('/cart') }}" class="btn btn-solid" data-text="Add to Cart">
                            <span>Add to Cart</span>
                        </a>
                        <a href="#" class="btn btn-outline" data-text="Add to Wishlist">
                            <span>Add to Wishlist</span>
                        </a>
                    </div>
                </div>
                <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex gap-x-12 gap-y-3 flex-wrap">
                        <h6 class="leading-none font-medium text-lg">SKU : CH_0015</h6>
                        <h6 class="leading-none font-medium text-lg">Category : Chair</h6>
                    </div>
                    <div class="flex gap-x-12 lg:gap-x-24 gap-y-3 flex-wrap mt-5 sm:mt-10">
                        <div class="flex gap-[10px] items-center">
                            <h6 class="leading-none font-medium text-lg">Size :</h6>
                            <div class="flex gap-[10px]">
                                <label class="product-size">
                                    <input class="appearance-none hidden" type="radio" name="size" checked>
                                    <span class="w-6 h-6 flex items-center justify-center pt-[2px] text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white duration-300">S</span>
                                </label>
                                <label class="product-size">
                                    <input class="appearance-none hidden" type="radio" name="size" >
                                    <span class="w-6 h-6 flex items-center justify-center pt-[2px] text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white duration-300">M</span>
                                </label>
                                <label class="product-size">
                                    <input class="appearance-none hidden" type="radio" name="size">
                                    <span class="w-6 h-6 flex items-center justify-center pt-[2px] text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white duration-300">L</span>
                                </label>
                                <label class="product-size">
                                    <input class="appearance-none hidden" type="radio" name="size">
                                    <span class="w-6 h-6 flex items-center justify-center pt-[2px] text-sm leading-none bg-[#E8E9EA] dark:bg-dark-secondary text-title dark:text-white duration-300">XL</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-[10px] items-center">
                            <h6 class="leading-none font-medium text-lg">Color :</h6>
                            <div class="flex gap-[10px] items-center">
                                <label class="product-color">
                                    <input class="appearance-none hidden" type="radio" name="color" >
                                    <span class="border border-[#D68553] flex rounded-full border-opacity-0 duration-300 p-1">
                                        <span class="w-4 h-4 rounded-full bg-[#D68553] flex"></span>
                                    </span>
                                </label>
                                <label class="product-color">
                                    <input class="appearance-none hidden" type="radio" name="color" checked>
                                    <span class="border border-[#61646E] flex rounded-full border-opacity-0 duration-300 p-1">
                                        <span class="w-4 h-4 rounded-full bg-[#61646E] flex"></span>
                                    </span>
                                </label>
                                <label class="product-color">
                                    <input class="appearance-none hidden" type="radio" name="color">
                                    <span class="border border-[#E9E3DC] flex rounded-full border-opacity-0 duration-300 p-1">
                                        <span class="w-4 h-4 rounded-full bg-[#E9E3DC] flex"></span>
                                    </span>
                                </label>
                                <label class="product-color">
                                    <input class="appearance-none hidden" type="radio" name="color">
                                    <span class="border border-[#9A9088] flex rounded-full border-opacity-0 duration-300 p-1">
                                        <span class="w-4 h-4 rounded-full bg-[#9A9088] flex"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk" data-aos="fade-up" data-aos-delay="400">
                    <h4 class="font-medium leading-none text-2xl">Tags :</h4>
                    <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Chair"><span>Chair</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Art & Paint"><span>Art & Paint</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Mirror"><span>Mirror</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Table"><span>Table</span></a>
                        <a class="btn btn-theme-outline btn-xs" href="#" data-text="Lamp"><span>Lamp</span></a>
                    </div>
                </div>
                <div class="pt-4 sm:pt-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex items-center gap-6">
                        <h6 class="font-normal text-lg">Share : </h6>
                        <div class="flex gap-6">
                            <a href="#" class="text-paragraph duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                <svg class="fill-current" width="9" height="17" viewBox="0 0 9 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.85187 2.88048H8.3125V0.327504C7.60589 0.249301 6.89543 0.211267 6.18454 0.213583C5.69283 0.185244 5.2009 0.265194 4.74322 0.447828C4.28554 0.630463 3.87319 0.911363 3.53508 1.27084C3.19696 1.63032 2.94126 2.05967 2.78589 2.52881C2.63052 2.99795 2.57925 3.49553 2.63567 3.98665V6.23546H0.3125V9.09033H2.63567V16.2674H5.4843V9.09033H7.7144L8.06849 6.23546H5.4843V4.26918C5.48543 3.44439 5.70674 2.88048 6.85187 2.88048Z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-paragraph duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                <svg class="fill-current" width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.3125 1.93807C19.56 2.26226 18.7641 2.47762 17.9495 2.5775C18.8075 2.07421 19.4491 1.27744 19.7528 0.338011C18.9492 0.809117 18.0701 1.14092 17.1534 1.31907C16.5909 0.726685 15.8612 0.315117 15.0591 0.137768C14.257 -0.0395802 13.4195 0.0254805 12.6553 0.324511C11.891 0.623542 11.2354 1.14273 10.7734 1.81471C10.3114 2.48668 10.0644 3.28041 10.0644 4.09289C10.061 4.40344 10.0927 4.7134 10.1589 5.017C8.52829 4.93856 6.93277 4.52093 5.47658 3.79139C4.02038 3.06186 2.73628 2.03683 1.70816 0.783282C1.18069 1.67484 1.01735 2.73179 1.25147 3.73836C1.48559 4.74493 2.09952 5.62522 2.96794 6.19953C2.31904 6.18223 1.68386 6.01099 1.11593 5.70024V5.74404C1.117 6.6799 1.44419 7.58683 2.04242 8.3122C2.64065 9.03756 3.4734 9.53706 4.40052 9.72665C4.04967 9.81785 3.68811 9.86253 3.32535 9.85951C3.06466 9.86431 2.8042 9.84131 2.54851 9.79089C2.81297 10.5956 3.3235 11.2993 4.00969 11.805C4.69587 12.3107 5.5239 12.5935 6.37955 12.6143C4.92709 13.7358 3.13616 14.3434 1.29315 14.3399C0.965406 14.3422 0.637852 14.3236 0.3125 14.2845C2.18785 15.4772 4.37257 16.1075 6.60256 16.0991C8.13765 16.1094 9.65951 15.8181 11.0798 15.2422C12.5 14.6662 13.7904 13.8171 14.8759 12.7441C15.9614 11.671 16.8204 10.3955 17.403 8.99161C17.9857 7.58769 18.2804 6.08333 18.27 4.56589C18.27 4.38632 18.27 4.21406 18.2552 4.04179C19.0647 3.47007 19.7619 2.75716 20.3125 1.93807Z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-paragraph duration-300 dark:text-white hover:text-primary dark:hover:text-primary">
                                <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.6744 5.43486C17.6603 4.70332 17.5234 3.97955 17.2696 3.29456C17.0457 2.70824 16.7035 2.17572 16.265 1.73104C15.8265 1.28636 15.3012 0.93931 14.7229 0.712057C14.047 0.455155 13.3329 0.316424 12.6112 0.301775C11.678 0.257327 11.3823 0.24707 9.01876 0.24707C6.65526 0.24707 6.35954 0.257327 5.42966 0.298356C4.70792 0.31274 3.99386 0.45148 3.31803 0.708638C2.73547 0.931712 2.20843 1.28188 1.77422 1.73434C1.33244 2.17515 0.990246 2.70785 0.771257 3.2957C0.519468 3.97954 0.383746 4.70167 0.369845 5.43145C0.32262 6.37624 0.3125 6.67597 0.3125 9.0727C0.3125 11.4694 0.32262 11.768 0.363098 12.7094C0.377246 13.4409 0.514129 14.1647 0.767883 14.8497C0.99196 15.4361 1.33431 15.9687 1.77303 16.4134C2.21176 16.8581 2.73721 17.2051 3.31578 17.4322C3.99239 17.6893 4.70719 17.8281 5.42966 17.8425C6.35842 17.8835 6.65414 17.8938 9.01763 17.8938C11.3811 17.8938 11.6768 17.8835 12.6056 17.8425C13.3274 17.8282 14.0414 17.6895 14.7172 17.4322C15.296 17.2054 15.8216 16.8585 16.2604 16.4138C16.6991 15.9691 17.0414 15.4363 17.2651 14.8497C17.5185 14.1646 17.6554 13.4409 17.6699 12.7094C17.7104 11.768 17.7205 11.4683 17.7205 9.0727C17.7205 6.67711 17.7205 6.37738 17.6767 5.436L17.6744 5.43486ZM16.1115 12.6399C16.106 13.1992 16.0048 13.7533 15.8124 14.2776C15.6673 14.6582 15.4453 15.0038 15.1606 15.2923C14.876 15.5808 14.535 15.8058 14.1595 15.9529C13.6422 16.1476 13.0956 16.2501 12.5438 16.2561C11.6251 16.2971 11.3496 16.3074 9.02663 16.3074C6.70361 16.3074 6.42476 16.2971 5.50949 16.2561C4.95766 16.2505 4.41096 16.1479 3.89373 15.9529C3.51595 15.8122 3.17429 15.5871 2.89413 15.2942C2.60588 15.0096 2.38386 14.6635 2.24423 14.281C2.05182 13.7567 1.95025 13.2027 1.94401 12.6433C1.90353 11.7122 1.89341 11.433 1.89341 9.0784C1.89341 6.72384 1.90353 6.4412 1.94401 5.5135C1.94948 4.95417 2.05068 4.40005 2.2431 3.87579C2.38162 3.49439 2.60385 3.14989 2.89301 2.86832C3.17358 2.57595 3.51511 2.35088 3.8926 2.20959C4.40995 2.015 4.95658 1.91244 5.50837 1.90644C6.42701 1.86541 6.70249 1.85515 9.0255 1.85515C11.3485 1.85515 11.6274 1.86541 12.5426 1.90644C13.0945 1.91203 13.6412 2.0146 14.1584 2.20959C14.5362 2.35022 14.8779 2.57538 15.158 2.86832C15.4462 3.15288 15.6683 3.499 15.8079 3.88149C16.0009 4.40415 16.1036 4.95662 16.1115 5.51464C16.152 6.44576 16.1621 6.72498 16.1621 9.07954C16.1621 11.4341 16.152 11.7099 16.1115 12.641V12.6399Z"/>
                                    <path d="M9.01976 4.53613C8.13511 4.53613 7.27032 4.80206 6.53476 5.3003C5.7992 5.79853 5.2259 6.5067 4.88736 7.33523C4.54881 8.16377 4.46023 9.07547 4.63282 9.95503C4.80541 10.8346 5.23141 11.6425 5.85695 12.2767C6.48249 12.9108 7.27948 13.3426 8.14713 13.5176C9.01479 13.6926 9.91414 13.6028 10.7314 13.2596C11.5488 12.9164 12.2473 12.3352 12.7388 11.5896C13.2303 10.8439 13.4926 9.96723 13.4926 9.07043C13.4923 7.86795 13.021 6.71481 12.1822 5.86453C11.3435 5.01425 10.2059 4.53643 9.01976 4.53613ZM9.01976 12.0112C8.446 12.0112 7.88513 11.8387 7.40807 11.5156C6.93101 11.1925 6.55918 10.7332 6.33961 10.1958C6.12005 9.65846 6.0626 9.06717 6.17454 8.49671C6.28647 7.92625 6.56275 7.40225 6.96846 6.99097C7.37417 6.57969 7.89107 6.29961 8.4538 6.18614C9.01653 6.07267 9.59982 6.13091 10.1299 6.35349C10.66 6.57607 11.1131 6.953 11.4318 7.43661C11.7506 7.92023 11.9207 8.48879 11.9207 9.07043C11.9204 9.85028 11.6147 10.5981 11.0707 11.1496C10.5267 11.701 9.78905 12.0109 9.01976 12.0112Z"/>
                                    <path d="M14.7141 4.35722C14.7141 4.56674 14.6529 4.77156 14.5381 4.94577C14.4233 5.11999 14.2602 5.25576 14.0693 5.33594C13.8784 5.41613 13.6684 5.4371 13.4658 5.39623C13.2631 5.35535 13.077 5.25446 12.9309 5.10631C12.7849 4.95815 12.6854 4.76939 12.6451 4.5639C12.6048 4.3584 12.6254 4.14539 12.7045 3.95181C12.7836 3.75824 12.9175 3.5928 13.0892 3.47639C13.261 3.35999 13.463 3.29785 13.6696 3.29785C13.9466 3.29785 14.2123 3.40947 14.4082 3.60814C14.6041 3.80681 14.7141 4.07626 14.7141 4.35722Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product Slider End -->

<!-- Description Start -->
<div class="s-py-50">
    <div class="container-fluid">
        <div class="max-w-[985px] mx-auto">
            <div class="product-dtls-navtab  border-y border-bdr-clr dark:border-bdr-clr-drk">
                <ul id="user-nav-tabs" class=" text-title dark:text-white text-base sm:text-lg lg:text-xl flex leading-none gap-3 sm:gap-6 md:gap-12 lg:gap-24 justify-between sm:justify-start max-w-md sm:max-w-full">
                    <li role="presentation" class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0 active "><a class="duration-300 hover:text-primary" href="#c1">Description</a></li>
                    <li role="presentation" class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0"><a class="duration-300 hover:text-primary" href="#c2">Vendor Info</a></li>
                    <li role="presentation" class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0"><a class="duration-300 hover:text-primary" href="#c3">Review</a></li>
                    <li role="presentation" class="py-3 sm:py-5 lg:6 relative before:absolute before:w-full before:h-[1px] before:bg-title before:top-full before:left-0 before:duration-300 dark:before:bg-white before:opacity-0"><a class="duration-300 hover:text-primary" href="#c4">Shipping</a></li>
                </ul>
            </div>
            <div id="content" class="mt-5 sm:mt-8 lg:mt-12 mx-0 sm:mr-5 md:mr-8 lg:mr-12">
                <div id="content1">
                    <p class="sm:text-lg">Crafted with plush cushioning and ergonomic design, it offers unparalleled comfort for lounging or reading. Its timeless style seamlessly blends with any decor, while the sturdy construction ensures durability for years to come. Whether you're unwinding after a long day or enjoying a leisurely weekend, this chair provides the perfect retreat.</p>
                    <ul class="mt-4 sm:mt-6 grid gap-4 sm:gap-5 sm:text-lg leading-none">
                        <li>Leather : From Japan</li>
                        <li>Brand : Navana</li>
                        <li>Weight : 1kg</li>
                        <li>Color : Wooden , Whtie , Blue , Orange</li>
                    </ul>
                </div>
                <div id="content2">
                    <div class="max-w-[680px] flex items-start justify-between gap-y-8 gap-x-10 flex-wrap">
                        <div>
                            <span class="text-primary sm:text-lg leading-none block">Shop Name</span>
                            <h4 class="font-medium mt-2 sm:mt-3 text-xl sm:text-2xl leading-none">John Furniture House</h4>
                            <ul class="mt-4 sm:mt-6 grid gap-3 sm:text-lg">
                                <li>Vendor : John Smith Doe</li>
                                <li>Shop : West New York, NY, 1234589</li>
                                <li>Mail : johnmsmith@gmail.com</li>
                                <li>Call : +11 - 01234 5678</li>
                            </ul>
                        </div>
                        <div>
                            <span class="text-primary sm:text-lg leading-none block">Shop Name</span>
                            <h4 class="font-medium mt-2 sm:mt-3 text-xl sm:text-2xl leading-none">Furniture Gallery</h4>
                            <ul class="mt-4 sm:mt-6 grid gap-3 sm:text-lg">
                                <li>Vendor : John Smith Doe</li>
                                <li>Shop : West New York, NY, 1234589</li>
                                <li>Mail : johnmsmith@gmail.com</li>
                                <li>Call : +11 - 01234 5678</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="content3">
                    <div class="max-w-[905px] flex items-start xl:justify-between gap-8 flex-wrap">
                        
                        <!-- includes/Shop/review.blade.php -->
                        @include('includes.Shop.review')

                    </div>
                </div>
                <div id="content4">
                    
                    <!-- includes/Shop/shipping.blade.php -->
                    @include('includes.Shop.shipping')

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Description End -->

<!-- Related Product Start -->
<div class="s-py-50-100">
    <div class="container-fluid">
        <div class="max-w-[547px] mx-auto text-center">
            <h6 class="text-2xl sm:text-3xl md:text-4xl leading-none font-bold">Related Products</h6>
            <p class="mt-3">Explore complementary options that enhance your experience. Discover related products curated just for you. </p>
        </div>
        <div class="max-w-[1720px] mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-8 pt-8 md:pt-[50px]">
            
            <!-- includes/Home/new-products.blade.php -->
            @include('includes.Home.new-products')

        </div>
    </div>
</div>
<!-- Related Product End -->

<!-- includes/Home/popup.blade.php -->
@include('includes.Home.popup')
    
@include('includes.footer')
  
@endsection