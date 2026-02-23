<!-- resources/views/forger-password.blade.php -->
@extends('layouts.main')

@section('title', 'Forger-Password Page')

@section('content')

@include('includes.navbar')

<div class="flex">
    <div class="w-1/2 hidden md:block lg:flex-1">
        <img class="h-full object-cover" src="{{ asset('assets/img/bg/forget-pass.jpg') }}" alt="forget password">
    </div>
    <div class="w-full md:w-1/2 lg:max-w-lg xl:max-w-3xl lg:w-full py-16 px-[20px] sm:px-8 lg:p-16 xl:p-24 relative z-10 flex items-center overflow-hidden">
        <div class="mx-auto md:mx-0 max-w-md">
            <h2 class="leading-none text-4xl font-bold" data-aos="fade-up">Forget Password</h2>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="100">Buy & sale your exclusive product only on Furnixar</p>
            <div class="mt-7" data-aos="fade-up" data-aos-delay="200">
                <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Email</label>
                <input class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300" type="email" placeholder="Enter your email address">
            </div>
            <div data-aos="fade-up" data-aos-delay="300">
                <a href="#" class="btn btn-theme-solid mt-[15px]" data-text="Send Info">
                    <span>Send Info</span>
                </a>
            </div>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="400">
                Note: We will send a password reset link to your email
            </p>
        </div>
    </div>
</div>
  
@include('includes.footer6')
  
@endsection