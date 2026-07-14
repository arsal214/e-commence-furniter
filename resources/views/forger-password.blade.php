{{-- resources/views/forger-password.blade.php --}}
@extends('layouts.no-header')

@section('title', 'Forgot your password? — PeytonGhalib')

@section('content')

<x-auth.shell
    eyebrow="Password Help"
    heading="Forgot your password?"
    subheading="Enter the email on your account and we'll send you a link to set a new one."
>

    <form method="POST" action="{{ route('password.email') }}" data-auth-form novalidate>
        @csrf

        <x-auth.field
            name="email"
            label="Email address"
            type="email"
            icon="mail"
            placeholder="name@example.com"
            autocomplete="email"
            inputmode="email"
            required
            autofocus
        />

        <div class="pgh-auth__note">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
            </svg>
            <span>The link expires after 60 minutes. Check your spam folder if it doesn't arrive.</span>
        </div>

        <x-auth.submit label="Send reset link" loading="Sending the link…" />
    </form>

    <x-slot:footer>
        Remembered it? <a href="{{ url('/login') }}">Back to sign in</a>
    </x-slot:footer>

</x-auth.shell>

@endsection
