<!-- resources/views/register.blade.php -->
@extends('layouts.main')

@section('title', 'Register Page')

@section('content')

@include('includes.navbar')

<!-- Register Area Start -->
<div class="flex justify-center items-center py-16 px-[20px] sm:px-8">
    <div class="max-w-md w-full">
            <h2 class="leading-none text-4xl font-bold" data-aos="fade-up">Create New Account</h2>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="100">Buy & sale your exclusive product only on PeytonGhalib</p>

            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded text-sm">
                    <ul class="list-disc list-inside text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mt-7" data-aos="fade-up" data-aos-delay="200">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Full Name</label>
                    <input class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300 @error('name') border-red-500 @enderror"
                           type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name">
                </div>
                <div class="mt-5" data-aos="fade-up" data-aos-delay="300">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Email</label>
                    <input class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300 @error('email') border-red-500 @enderror"
                           type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                </div>
                <div class="mt-5" data-aos="fade-up" data-aos-delay="400">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Password</label>
                    <input class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300 placeholder:text-xl placeholder:transform placeholder:translate-y-[10px]"
                           type="password" name="password" placeholder="* * * * * * * *">
                </div>
                <div class="mt-5" data-aos="fade-up" data-aos-delay="450">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Confirm Password</label>
                    <input class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300 placeholder:text-xl placeholder:transform placeholder:translate-y-[10px]"
                           type="password" name="password_confirmation" placeholder="* * * * * * * *">
                </div>
                <div data-aos="fade-up" data-aos-delay="600">
                    <button type="submit" class="btn btn-theme-solid mt-[15px]" data-text="Register"><span>Register</span></button>
                </div>
            </form>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="700">Already have an account? <a href="{{ url('/login') }}" class="text-primary font-medium ml-1 inline-block">Login</a></p>
    </div>
</div>
<!-- Register Area End -->

@include('includes.footer6')

@endsection
