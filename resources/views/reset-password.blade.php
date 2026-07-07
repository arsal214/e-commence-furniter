<!-- resources/views/reset-password.blade.php -->
@extends('layouts.main')

@section('title', 'Reset Password')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

<div class="flex">
    <div class="w-1/2 hidden md:block lg:flex-1">
        <img class="h-full object-cover" src="{{ asset('assets/img/bg/forget-pass.jpg') }}" alt="reset password">
    </div>
    <div class="w-full md:w-1/2 lg:max-w-lg xl:max-w-3xl lg:w-full py-16 px-[20px] sm:px-8 lg:p-16 xl:p-24 relative z-10 flex items-center overflow-hidden">
        <div class="mx-auto md:mx-0 max-w-md w-full">
            <h2 class="leading-none text-4xl font-bold" data-aos="fade-up">Reset Password</h2>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="100">Choose a new password for your PeytonGhalib account</p>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mt-7" data-aos="fade-up" data-aos-delay="200">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Email</label>
                    <input name="email" value="{{ old('email', $email) }}" required class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300" type="email" placeholder="Enter your email address">
                    @error('email')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-5" data-aos="fade-up" data-aos-delay="300">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">New Password</label>
                    <input name="password" required class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300" type="password" placeholder="Enter new password">
                    @error('password')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-5" data-aos="fade-up" data-aos-delay="400">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Confirm Password</label>
                    <input name="password_confirmation" required class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300" type="password" placeholder="Confirm new password">
                </div>

                <div data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" class="btn btn-theme-solid mt-[15px]" data-text="Reset Password">
                        <span>Reset Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('includes.footer')

@endsection
