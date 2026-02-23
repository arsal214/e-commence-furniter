<!-- resources/views/wishlist.blade.php -->
@extends('layouts.main')

@section('title', 'Wishlist Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Wishlist</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">wishlist</li>
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
                    <li class="py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/order-history') }}">Order History</a>
                    </li>
                    <li class="active text-primary py-3 lg:py-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/wishlist') }}">Wishlist</a>
                    </li>
                    <li class="pt-3 lg:pt-6 pl-6 lg:pl-12">
                        <a class="duration-300 hover:text-primary" href="{{ url('/login') }}">Logout</a>
                    </li>
                </ul>
            </div>
            <div class="w-full md:w-auto md:flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 sm:gap-6 lg::gap-8">
                    
                    <!-- includes/Pages/wishlists.blade.php -->
                    @include('includes.Pages.wishlists')

                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Profile End -->
    
@include('includes.footer6')
  
@endsection