<!-- resources/views/my-profile.blade.php -->
@extends('layouts.main')

@section('title', 'My-Profile Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">My Profile</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Profile</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- My Profile Start -->
<div class="s-py-100" data-aos="fade-up">
    <div class="container-fluid">
        <!-- portfolio Navs -->
        <div class="max-w-[1720px] mx-auto flex items-start gap-8 md:gap-12 2xl:gap-24 flex-col md:flex-row my-profile-navtab">
            <div class="w-full md:w-[200px] lg:w-[300px] flex-none">
                <ul class="divide-y dark:divide-paragraph text-title dark:text-white text-base sm:text-lg lg:text-xl flex flex-col justify-center leading-none">
                    <li class="active text-primary pb-3 lg:pb-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/my-account') }}">My Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/edit-account') }}">Edit Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/order-history') }}">Order History</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/wishlist') }}">Wishlist</a>
                    </li>
                    <li class="pt-3 lg:pt-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/login') }}">Logout</a>
                    </li>
                </ul>
            </div>
            <div class="w-full md:w-auto md:flex-1 overflow-auto">
                <!-- Profile Content -->
                <div class="w-full max-w-[951px] bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px]">
                    <div>
                        <h3 class="font-semibold leading-none text-3xl">Kathlene Roser</h3>
                        <span class="leading-none mt-3">Product Designer</span>
                    </div>
                    <p class="text-base sm:text-lg mt-5 sm:mt-8 md:mt-10 text-justify">
                        All the Lorem Ipsum generators on the Internet tend to repeat predefined on the Internet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Non, lobortis in in tortor lectus iaculis viverra. Adipiscing lobortis interdum fringilla euismod odio vitae nam pulvinar elementum. Nibh purus integer elementum in. Tellus vulputate habitasse ut vulputate posuere habitant vel tempor varius. 
                    </p>
                    <div class="mt-5 sm:mt-8 md:mt-10 grid gap-4 sm:gap-6">
                        <a href="#" class="flex items-center gap-2">
                            <svg class="w-3 sm:w-[17px]" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.065 11.7537C14.9033 11.7537 13.7794 11.5649 12.7311 11.2249C12.4006 11.1115 12.0322 11.1965 11.7772 11.4515L10.2944 13.3121C7.62167 12.0371 5.11889 9.62875 3.78722 6.86152L5.62889 5.29375C5.88389 5.0293 5.95944 4.66097 5.85556 4.33041C5.50611 3.28208 5.32667 2.15819 5.32667 0.996523C5.32667 0.486523 4.90167 0.0615234 4.39167 0.0615234H1.12389C0.613889 0.0615234 0 0.28819 0 0.996523C0 9.77041 7.30056 17.0615 16.065 17.0615C16.7356 17.0615 17 16.4665 17 15.9471V12.6887C17 12.1787 16.575 11.7537 16.065 11.7537Z" fill="#BB976D"/>
                            </svg>
                            <span class="leading-none font-medium text-base sm:text-lg">+111 - (1234 5678 99)</span>
                        </a>
                        <a href="#" class="flex items-center gap-2">
                            <svg class="w-3 sm:w-[18px]" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.2 0.0615234H1.8C0.81 0.0615234 0.00899999 0.849023 0.00899999 1.81152L0 12.3115C0 13.274 0.81 14.0615 1.8 14.0615H16.2C17.19 14.0615 18 13.274 18 12.3115V1.81152C18 0.849023 17.19 0.0615234 16.2 0.0615234ZM16.2 3.56152L9 7.93652L1.8 3.56152V1.81152L9 6.18652L16.2 1.81152V3.56152Z" fill="#BB976D"/>
                            </svg>
                            <span class="leading-none font-medium text-base sm:text-lg">furnixar123@gmail.com</span>
                        </a>
                        <a href="#" class="flex items-center gap-2">
                            <svg class="w-3 sm:w-[15px]" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.49927 0.0615234C3.36415 0.0615234 0 3.42567 0 7.56075C0 12.6925 6.71111 20.2262 6.99684 20.5444C7.26522 20.8434 7.7338 20.8428 8.00169 20.5444C8.28743 20.2262 14.9985 12.6925 14.9985 7.56075C14.9985 3.42567 11.6343 0.0615234 7.49927 0.0615234ZM7.49927 11.3338C5.41879 11.3338 3.72624 9.64123 3.72624 7.56075C3.72624 5.48027 5.41883 3.78772 7.49927 3.78772C9.57971 3.78772 11.2723 5.48031 11.2723 7.56079C11.2723 9.64127 9.57971 11.3338 7.49927 11.3338Z" fill="#BB976D"/>
                            </svg>
                            <span class="leading-none font-medium text-base sm:text-lg">23/ A Lake Side , New Arizona , USA</span>
                        </a>
                    </div>
                </div>
                <!-- Profile Content -->
                <div id="content2" class="w-full max-w-[951px] bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px]">
                    <div class="flex items-start flex-col lg:flex-row gap-5 sm:gap-6">
                        <div class="grid gap-5 sm:gap-6 w-full lg:w-1/2">
                            <div>
                                <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Full Name</label>
                                <input class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="text" placeholder="Enter your full name">
                            </div>
                            <div>
                                <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Designation</label>
                                <input class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="text" placeholder="Enter your designation">
                            </div>
                            <div>
                                <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Phone No.</label>
                                <input class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300 appearance-none" type="number" placeholder="Type your phone number">
                            </div>
                            <div>
                                <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Mail</label>
                                <input class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="email" placeholder="Enter your email address">
                            </div>
                        </div>
                        <div class="grid gap-5 sm:gap-6 w-full lg:w-1/2">
                            <div>
                                <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Location</label>
                                <input class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="text" placeholder="Enter your location">
                            </div>
                            <div>
                                <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Bio</label>
                                <textarea class="w-full h-28 md:h-[168px] bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" name="Message" placeholder="Write your bio . . ."></textarea>
                            </div>
                            <div>
                                <label class="text-base md:text-lg text-title dark:text-white leading-none mb-2 sm:mb-3 block">Web / Social Media</label>
                                <input class="w-full h-12 md:h-14 bg-white dark:bg-dark-secondary border border-[#E3E5E6] text-title dark:text-white focus:border-primary p-4 outline-none duration-300" type="text" placeholder="www.facebook.com/johndoe">
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-8 md:mt-12">
                        <button class="btn btn-solid" data-text="Save Change">
                            <span>Save Change</span>
                        </button>
                    </div>
                </div>
                <!-- Profile Content -->
                <div id="content3" class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px] order-history-table">
                    <ul class="order-history">
                        <!-- Table Heading -->
                        <li class="title flex items-center justify-between gap-5 pb-[10px] sm:pb-5 border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <span class="cart-product-title text-lg md:text-xl font-semibold leading-none text-title dark:text-white block w-[270px] sm:w-[310px] xl:w-[330px]">Product</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[60px]">Price</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[100px]">Status</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[72px]">Remove</span>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-01.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">13 November, 2023</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4"><a href="#">Modern Sofa Set</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$74</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#31A051] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Completed
                                </a>
                            </div>

                            <div class="w-[72px] flex justify-end">
                                <button class="w-8 h-8 flex-none bg-[#E8E9EA] dark:bg-title flex items-center justify-center duration-300 text-title dark:text-white">
                                    <svg class="fill-current " width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-02.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">13 November, 2023</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4"><a href="#">Classic Chair with Vase</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$34</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#EC991D] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Pending
                                </a>
                            </div>

                            <div class="w-[72px] flex justify-end">
                                <button class="w-8 h-8 flex-none bg-[#E8E9EA] dark:bg-title flex items-center justify-center duration-300 text-title dark:text-white">
                                    <svg class="fill-current " width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-03.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">13 November, 2023</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4"><a href="#">Luxury Hanging Lamp</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$33</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#E13939] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Cancel
                                </a>
                            </div>

                            <div class="w-[72px] flex justify-end">
                                <button class="w-8 h-8 flex-none bg-[#E8E9EA] dark:bg-title flex items-center justify-center duration-300 text-title dark:text-white">
                                    <svg class="fill-current " width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-04.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">13 November, 2023</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4"><a href="#">Premium Quality Vase</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$48</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#31A051] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Completed
                                </a>
                            </div>

                            <div class="w-[72px] flex justify-end">
                                <button class="w-8 h-8 flex-none bg-[#E8E9EA] dark:bg-title flex items-center justify-center duration-300 text-title dark:text-white">
                                    <svg class="fill-current " width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 pt-[15px] sm:pt-[15px] ">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-05.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">13 November, 2023</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4"><a href="#">Classic White Chair</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$88</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#31A051] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Completed
                                </a>
                            </div>

                            <div class="w-[72px] flex justify-end">
                                <button class="w-8 h-8 flex-none bg-[#E8E9EA] dark:bg-title flex items-center justify-center duration-300 text-title dark:text-white">
                                    <svg class="fill-current " width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.546875 1.70822L1.70481 0.550293L5.98646 4.83195L10.2681 0.550293L11.3991 1.6813L7.11746 5.96295L11.453 10.2985L10.295 11.4564L5.95953 7.12088L1.67788 11.4025L0.546875 10.2715L4.82853 5.98988L0.546875 1.70822Z"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Profile End -->
    
@include('includes.footer6')
  
@endsection