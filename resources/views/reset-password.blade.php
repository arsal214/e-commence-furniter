{{-- resources/views/reset-password.blade.php --}}
@extends('layouts.no-header')

@section('title', 'Set a new password — PeytonGhalib')

@section('content')

<x-auth.shell
    eyebrow="Password Reset"
    heading="Set a new password"
    subheading="Choose a new password for your PeytonGhalib account."
>

    <form method="POST" action="{{ route('password.update') }}" data-auth-form novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-auth.field
            name="email"
            label="Email address"
            type="email"
            icon="mail"
            :value="$email"
            placeholder="name@example.com"
            autocomplete="email"
            inputmode="email"
            required
        />

        <x-auth.field
            name="password"
            label="New password"
            type="password"
            icon="lock"
            placeholder="Create a new password"
            autocomplete="new-password"
            minlength="8"
            required
            autofocus
            toggle
            strength
        />

        <x-auth.field
            name="password_confirmation"
            label="Confirm new password"
            type="password"
            icon="lock"
            placeholder="Re-enter your new password"
            autocomplete="new-password"
            match="password"
            required
            toggle
        />

        <div class="pgh-auth__spacer"></div>

        <x-auth.submit label="Reset password" loading="Updating your password…" />
    </form>

    <x-slot:footer>
        Changed your mind? <a href="{{ url('/login') }}">Back to sign in</a>
    </x-slot:footer>

</x-auth.shell>

@endsection
