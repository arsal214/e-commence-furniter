{{-- resources/views/login.blade.php --}}
@extends('layouts.no-header')

@section('title', 'Sign in — PeytonGhalib')

@section('content')

<x-auth.shell
    eyebrow="Member Login"
    heading="Welcome back"
    subheading="Sign in to track orders, save favourites, and check out in seconds."
>

    <form method="POST" action="{{ route('login') }}" data-auth-form novalidate>
        @csrf

        <x-auth.field
            name="email"
            label="Email address"
            type="email"
            icon="mail"
            placeholder="name@example.com"
            autocomplete="username"
            inputmode="email"
            required
            autofocus
        />

        <x-auth.field
            name="password"
            label="Password"
            type="password"
            icon="lock"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
            toggle
        />

        <div class="pgh-auth__row">
            <label class="pgh-check">
                <input class="pgh-check__input" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                <span class="pgh-check__box">
                    <svg width="11" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </span>
                <span class="pgh-check__text">Remember me</span>
            </label>

            <a class="pgh-auth__link" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <x-auth.submit label="Sign in" loading="Signing you in…" />

        {{-- Social sign-in slot: intentionally empty. This app has no OAuth
             provider wired up (no Laravel Socialite), and a button that cannot
             complete a login is worse than no button. Install Socialite, add the
             provider routes, then render them here. --}}
    </form>

    <x-slot:footer>
        New to PeytonGhalib? <a href="{{ url('/register') }}">Create an account</a>
    </x-slot:footer>

</x-auth.shell>

@endsection
