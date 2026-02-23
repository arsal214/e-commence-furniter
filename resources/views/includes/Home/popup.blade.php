<!-- Quick View Popup Start -->
<div class="quick-view-popup bg-white dark:bg-title fixed z-[9999999] top-[100px] left-[95%] md:left-[98%] [@media(min-width:1440px)]:left-[77%] transfrom -translate-x-1/2 flex max-w-[1024px] mx-auto rounded-2xl">
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-8 w-full quick-view-popup-inner">
        <div>
            <div class="relative">
                <div class="portfolio-v3-slider owl-carousel quick-preview-slider" data-carousel-animateout="false" data-carousel-loop="true" data-carousel-margin="0">
                    <img class="w-full h-full object-cover" src="{{ asset('assets/img/gallery/product-detls/product-01.jpg') }}" alt="Portfolio">
                    <img class="w-full h-full object-cover" src="{{ asset('assets/img/gallery/product-detls/product-02.jpg') }}" alt="Portfolio">
                    <img class="w-full h-full object-cover" src="{{ asset('assets/img/gallery/product-detls/product-03.jpg') }}" alt="Portfolio">
                </div>
                <div class="flex justify-between absolute [top:57%] transform -translate-y-1/2 z-20 w-full">
                    <button class="prtflo03_prev w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2">
                        <svg class="fill-current" width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 9C19.5523 9 20 8.55228 20 8C20 7.44772 19.5523 7 19 7L19 9ZM0.292893 7.29289C-0.097631 7.68341 -0.0976311 8.31658 0.292893 8.7071L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34314C8.46159 1.95262 8.46159 1.31945 8.07107 0.92893C7.68054 0.538406 7.04738 0.538406 6.65686 0.92893L0.292893 7.29289ZM19 7L1 7L1 9L19 9L19 7Z"/>
                        </svg>
                    </button>
                    <button class="prtflo03_next w-9 h-9 md:w-14 md:h-14 flex items-center justify-center text-title duration-300 bg-white hover:text-white hover:bg-primary p-2">
                        <svg class="fill-current" width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 7C0.447715 7 4.82823e-08 7.44772 0 8C-4.82823e-08 8.55228 0.447715 9 1 9L1 7ZM19.7071 8.70711C20.0976 8.31658 20.0976 7.68342 19.7071 7.29289L13.3431 0.928933C12.9526 0.538409 12.3195 0.538409 11.9289 0.928933C11.5384 1.31946 11.5384 1.95262 11.9289 2.34315L17.5858 8L11.9289 13.6569C11.5384 14.0474 11.5384 14.6805 11.9289 15.0711C12.3195 15.4616 12.9526 15.4616 13.3431 15.0711L19.7071 8.70711ZM1 9L19 9L19 7L1 7L1 9Z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="lg:max-w-[635px] w-full p-5 sm:p-8 md:pl-0 md:py-10 md:pr-8">
            <div class="pb-4 sm:pb-6 border-b border-bdr-clr dark:border-bdr-clr-drk">
                <h2 class="font-semibold leading-none text-[30px]">Classic Relaxable Chair</h2>
                <div class="flex gap-4 items-center mt-[15px]">
                    <span class="text-lg leading-none block relative before:absolute before:border-b before:border-[1.5px] before:border-paragraph dark:before:border-white-light before:top-[6px] before:left-0 before:w-full">$185.00</span>
                    <span class="text-2xl text-primary leading-none block">$85.00</span>
                </div>
                <div class="mt-5 md:mt-7 overflow-auto">
                    <div class="py-2 px-3 bg-[#FAF2F2] rounded-[51px] flex items-end gap-[6px] w-[360px]">
                        <svg width="15" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.6923 7.59087C12.6383 7.52329 12.573 7.53657 12.5387 7.55036C12.51 7.562 12.4442 7.59919 12.4533 7.69239C12.4642 7.80431 12.4704 7.91841 12.4715 8.03157C12.4764 8.50102 12.2881 8.96094 11.9549 9.2934C11.6238 9.62371 11.1884 9.80168 10.7247 9.79652C10.0913 9.78844 9.56601 9.45809 9.20551 8.84118C8.90742 8.33106 9.03844 7.67313 9.17715 6.97654C9.25832 6.5688 9.34227 6.14716 9.34227 5.74588C9.34227 2.62132 7.24173 0.818669 5.98962 0.0222265C5.96373 0.00578123 5.93908 0 5.91724 0C5.88173 0 5.85361 0.0153124 5.83974 0.0246874C5.81287 0.0428905 5.76986 0.0843747 5.78369 0.157812C6.26228 2.69929 4.83478 4.22783 3.32346 5.84611C1.76566 7.51419 0 9.40485 0 12.8147C0 16.7767 3.22331 20 7.18532 20C10.4475 20 13.3237 17.7256 14.1796 14.4692C14.7633 12.2487 14.1517 9.42031 12.6923 7.59087ZM7.36458 18.4663C6.37247 18.5115 5.42896 18.1557 4.7083 17.4667C3.99537 16.7849 3.58647 15.8336 3.58647 14.8565C3.58647 13.0228 4.28756 11.6768 6.17326 9.88973C6.20412 9.86047 6.23572 9.85121 6.26326 9.85121C6.28822 9.85121 6.30986 9.85883 6.32474 9.86598C6.35611 9.88109 6.40767 9.91852 6.40072 9.99945C6.33329 10.784 6.33447 11.4352 6.40415 11.9351C6.58228 13.2118 7.51692 14.0697 8.73 14.0697C9.32477 14.0697 9.89129 13.8458 10.3252 13.4394C10.3756 13.3922 10.4318 13.3982 10.4534 13.4028C10.4819 13.409 10.5202 13.4265 10.5402 13.4748C10.7202 13.9092 10.8121 14.3703 10.8135 14.8453C10.8193 16.7564 9.27207 18.3808 7.36458 18.4663Z" fill="#BB976D"/>
                        </svg>                                
                        <h6 class="text-lg font-medium leading-none !text-[#BB976D]">Sale Ends :</h6>
                        <div class="countdown-clock flex gap-[10px] items-center">
                            <div class="countdown-item flex">
                                <div class="ci-inner text-lg font-medium leading-none text-[#BB976D]">
                                    <div class="clock-days ci-value"></div>
                                </div>
                                <p class="text-lg font-medium leading-none text-[#BB976D]">D</p>
                            </div>
                            <p class="text-lg font-medium leading-none text-[#BB976D]">:</p>
                            <div class="countdown-item flex">
                                <div class="ci-inner text-lg font-medium leading-none text-[#BB976D]">
                                    <div class="clock-hours ci-value"></div>
                                </div>
                                <p class="text-lg font-medium leading-none text-[#BB976D]">H</p>
                            </div>
                            <p class="text-lg font-medium leading-none text-[#BB976D]">:</p>
                            <div class="countdown-item flex">
                                <div class="ci-inner text-lg font-medium leading-none text-[#BB976D]">
                                    <div class="clock-minutes ci-value"></div>
                                </div>
                                <p class="text-lg font-medium leading-none text-[#BB976D]">M</p>
                            </div>
                            <p class="text-lg font-medium leading-none text-[#BB976D]">:</p>
                            <div class="countdown-item flex">
                                <div class="ci-inner text-lg font-medium leading-none text-[#BB976D]">
                                    <div class="clock-seconds ci-value"></div>
                                </div>
                                <p class="text-lg font-medium leading-none text-[#BB976D]">S</p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="sm:text-lg mt-5 md:mt-7">
                    Experience the epitome of relaxation with our Classic Relaxable Chair. Crafted with plush cushioning and ergonomic design.
                </p>
            </div>
            <div class="py-4 sm:py-6 border-b border-bdr-clr dark:border-bdr-clr-drk">
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
                <div class="flex gap-4 mt-4 sm:mt-6 flex-wrap">
                    <a href="{{ url('/cart') }}" class="btn btn-solid btn-sm" data-text="Add to Cart">
                        <span>Add to Cart</span>
                    </a>
                    <a href="#" class="btn btn-outline btn-sm" data-text="Add to Wishlist">
                        <span>Add to Wishlist</span>
                    </a>
                </div>
            </div>
            <div class="pt-4 sm:pt-6 ">
                <div class="flex gap-x-12 gap-y-3 flex-wrap">
                    <h6 class="leading-none font-medium">SKU : CH_0015</h6>
                    <h6 class="leading-none font-medium">Category : Chair</h6>
                </div>
                <div class="flex gap-x-12 lg:gap-x-24 gap-y-3 flex-wrap mt-5 sm:mt-10">
                    <div class="flex gap-[10px] items-center">
                        <h6 class="leading-none font-medium">Size :</h6>
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
                        <h6 class="leading-none font-medium">Color :</h6>
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
        </div>
    </div>
    <button class="quick-popup-close w-12 h-12 rounded-full flex items-center justify-center text-title dark:text-white bg-title/10 dark:bg-white/10 absolute top-5 right-5 z-[999999999] duration-300 hover:text-white hover:bg-primary dark:hove:bg-primary dark:hover:text-white">
        <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.273438 2.12808L2.12613 0.275391L8.97678 7.12604L15.8274 0.275391L17.637 2.085L10.7864 8.93564L17.7232 15.8725L15.8705 17.7252L8.93369 10.7883L2.08304 17.639L0.273438 15.8294L7.12409 8.97873L0.273438 2.12808Z"/>
        </svg>                
    </button>
</div>
<!-- Quick View Popup End -->