{{-- resources/views/set-password.blade.php --}}
@extends('layouts.no-header')

@section('title', 'Set your password — PeytonGhalib')

@section('content')

<x-auth.shell
    eyebrow="One last step"
    heading="Set your password"
    subheading="Your account was created at checkout. Choose your own password to finish setting it up."
>

    <form method="POST" action="{{ route('account.set-password') }}" data-auth-form novalidate>
        @csrf

        <x-auth.field
            name="password"
            label="New password"
            type="password"
            icon="lock"
            placeholder="Create a password"
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

        <x-auth.submit label="Save password &amp; continue" loading="Saving…" />
    </form>

</x-auth.shell>

@endsection
