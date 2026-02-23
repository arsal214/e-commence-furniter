<!-- resources/views/order-history.blade.php -->
@extends('layouts.main')

@section('title', 'Order-History Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Order History</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">History</li>
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
                    <li class="pb-3 lg:pb-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/my-account') }}">My Account</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/edit-account') }}">Edit Account</a>
                    </li>
                    <li class="active text-primary py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/order-history') }}">Order History</a>
                    </li>
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/wishlist') }}">Wishlist</a>
                    </li>
                    <li class="pt-3 lg:pt-6 pl-6 lg:pl-12"><a class="duration-300 hover:text-primary" href="{{ url('/login') }}">Logout</a></li>
                </ul>
            </div>
            <div class="w-full md:w-auto md:flex-1 overflow-auto">
                <!-- Profile Content -->
                <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-8 lg:p-[50px] order-history-table">
                    <ul class="order-history">
                        <!-- Table Heading -->
                        <li class="title flex items-center justify-between gap-5 pb-[10px] sm:pb-5 border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <span class="cart-product-title text-lg md:text-xl font-semibold leading-none text-title dark:text-white block w-[270px] sm:w-[310px] xl:w-[330px]">Product</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[60px]">Price</span>
                            <span class="text-lg md:text-xl font-semibold leading-none text-title dark:text-white w-[100px]">Status</span>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-01.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">Interior</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4 text-xl"><a href="#">Modern Sofa Set</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$74</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#31A051] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Completed
                                </a>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-02.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">Chair</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4 text-xl"><a href="#">Classic Chair with Vase</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$34</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#EC991D] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Pending
                                </a>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-03.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">Light</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4 text-xl"><a href="#">Luxury Hanging Lamp</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$33</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#E13939] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Cancel
                                </a>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 py-[15px] sm:py-[15px] border-b border-bdr-clr dark:border-bdr-clr-drk">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-04.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none"> Lamp</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4 text-xl"><a href="#">Premium Quality Vase</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$48</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#31A051] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Completed
                                </a>
                            </div>
                        </li>
                        <!-- Single Table Row -->
                        <li class="flex items-center justify-between gap-5 pt-[15px] sm:pt-[15px] ">
                            <div class="flex items-center gap-3 md:gap-4 lg:gap-6 ordered-product w-[270px] sm:w-[310px] xl:w-[330px]">
                                <div class="w-16 sm:w-[90px] flex-none">
                                    <img src="{{ asset('assets/img/gallery/cart/cart-05.jpg') }}" alt="product">
                                </div>
                                <div class="flex-1">
                                    <span class="text-[15px] font-medium leading-none">Chair</span>
                                    <h5 class="font-semibold leading-none mt-2 md:mt-4 text-xl"><a href="#">Classic White Chair</a></h5>
                                </div>
                            </div>

                            <span class="text-base md:text-lg leading-none text-title dark:text-white font-semibold text-left w-[60px]">$88</span>

                            <div class="w-[100px]">
                                <a href="#" class="bg-[#31A051] py-[7px] px-[10px] font-semibold leading-none text-white text-sm rounded">
                                    Completed
                                </a>
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