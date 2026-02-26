<!-- resources/views/login.blade.php -->
@extends('layouts.main')

@section('title', 'Login Page')

@section('content')

@include('includes.navbar')

<div class="flex justify-center items-center py-16 px-[20px] sm:px-8">
    <div class="max-w-md w-full">
            <h2 class="leading-none text-4xl font-bold" data-aos="fade-up">Welcome back !</h2>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="100">Buy & sale your exclusive product only on PeytonGhalib</p>

            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mt-7" data-aos="fade-up" data-aos-delay="200">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Email</label>
                    <input class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300 @error('email') border-red-500 @enderror"
                           type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                </div>
                <div class="mt-5" data-aos="fade-up" data-aos-delay="300">
                    <label class="text-base sm:text-lg font-medium leading-none mb-2.5 block dark:text-white">Password</label>
                    <input class="w-full h-12 md:h-14 bg-white dark:bg-transparent border border-bdr-clr focus:border-primary p-4 outline-none duration-300 placeholder:text-xl placeholder:transform placeholder:translate-y-[10px]"
                           type="password" name="password" placeholder="* * * * * * * *">
                </div>
                <div class="mt-7" data-aos="fade-up" data-aos-delay="400">
                    <label class="flex items-center gap-2 iam-agree">
                        <input class="appearance-none hidden" type="checkbox" name="remember">
                        <span class="w-[18px] h-[18px] rounded-[5px] border-2 border-title dark:border-white flex items-center justify-center duration-300">
                            <svg class="duration-300 opacity-0 fill-current text-title dark:text-white" width="9" height="8" viewBox="0 0 9 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.05203 7.04122C2.87283 7.04122 2.69433 6.97322 2.5562 6.83864L0.532492 4.8553C0.253409 4.58189 0.249159 4.13351 0.522576 3.85372C0.796701 3.57393 1.24578 3.57039 1.52416 3.84309L3.05203 5.34122L7.61512 0.868804C7.89491 0.595387 8.34328 0.59822 8.6167 0.87872C8.89082 1.1578 8.88657 1.60689 8.60749 1.8803L3.54787 6.83864C3.40974 6.97322 3.23124 7.04122 3.05203 7.04122Z"/>
                            </svg>
                        </span>
                        <span class="text-base sm:text-lg text-title dark:text-white leading-none sm:leading-none select-none inline-block transform translate-y-[3px]">Remember Me</span>
                    </label>
                </div>
                <div data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" class="btn btn-theme-solid mt-[15px]" data-text="Login"><span>Login</span></button>
                </div>
            </form>
            <p class="text-lg mt-[15px]" data-aos="fade-up" data-aos-delay="600">Don't have an account yet? <a href="{{ url('/register') }}" class="text-primary font-medium ml-1 inline-block">Register</a></p>
    </div>
</div>

@include('includes.footer6')

@endsection
