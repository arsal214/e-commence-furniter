{{-- resources/views/register.blade.php --}}
@extends('layouts.no-header')

@section('title', 'Create your account — PeytonGhalib')

@section('content')

<x-auth.shell
    eyebrow="Create Account"
    heading="Join PeytonGhalib"
    subheading="Save your favourites, track every order, and check out in seconds."
    wide
>

    <form method="POST" action="{{ route('register') }}" data-auth-form novalidate>
        @csrf

        {{-- Two fields per row on tablet and up, stacked on mobile --}}
        <div class="pgh-auth__grid">
            <x-auth.field
                name="name"
                label="Full name"
                icon="user"
                placeholder="Jane Doe"
                autocomplete="name"
                required
                autofocus
            />

            <x-auth.field
                name="email"
                label="Email address"
                type="email"
                icon="mail"
                placeholder="name@example.com"
                autocomplete="email"
                inputmode="email"
                required
            />

            <x-auth.field
                name="password"
                label="Password"
                type="password"
                icon="lock"
                placeholder="Create a password"
                autocomplete="new-password"
                minlength="8"
                required
                toggle
                strength
            />

            <x-auth.field
                name="password_confirmation"
                label="Confirm password"
                type="password"
                icon="lock"
                placeholder="Re-enter password"
                autocomplete="new-password"
                match="password"
                required
                toggle
            />
        </div>

        <div class="pgh-auth__terms">
            By creating an account you agree to our
            <a class="pgh-auth__link" href="{{ url('/terms-and-conditions') }}">Terms</a> and
            <a class="pgh-auth__link" href="{{ route('privacy-policy') }}">Privacy Policy</a>.
        </div>

        <x-auth.submit label="Create account" loading="Creating your account…" />
    </form>

    <x-slot:footer>
        Already have an account? <a href="{{ url('/login') }}">Sign in</a>
    </x-slot:footer>

</x-auth.shell>

@endsection
