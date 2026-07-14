{{-- resources/views/edit-account.blade.php --}}
@extends('layouts.main')

@section('title', 'Edit Account — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

<x-account.shell
    active="edit"
    :user="$user"
    crumb="Edit Account"
    heading="Edit account"
    subheading="Update your details, or set a new password."
>

    <form method="POST" action="{{ route('account.update') }}" data-acc-form novalidate>
        @csrf

        {{-- Server-side error summary, announced immediately --}}
        @if ($errors->any())
            <div class="acc-alert" role="alert">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="acc-panel">

            <fieldset class="acc-fieldset">
                <legend class="acc-fieldset__legend">Personal information</legend>
                <p class="acc-fieldset__hint">This is the name and email we use for your orders and receipts.</p>

                <div class="acc-form__grid">
                    <x-account.field
                        name="name"
                        label="Full name"
                        icon="user"
                        :value="$user->name"
                        placeholder="Jane Doe"
                        autocomplete="name"
                        required
                    />

                    <x-account.field
                        name="email"
                        label="Email address"
                        type="email"
                        icon="mail"
                        :value="$user->email"
                        placeholder="name@example.com"
                        autocomplete="email"
                        inputmode="email"
                        required
                    />
                </div>
            </fieldset>

            <fieldset class="acc-fieldset">
                <legend class="acc-fieldset__legend">Change password</legend>
                <p class="acc-fieldset__hint">Leave these blank to keep your current password.</p>

                <div class="acc-form__grid">
                    <x-account.field
                        name="current_password"
                        label="Current password"
                        type="password"
                        icon="lock"
                        placeholder="Enter your current password"
                        autocomplete="current-password"
                        toggle
                        full
                    />

                    {{-- requiredWith: filling "new password" makes the current one mandatory,
                         which is exactly what HomeController::updateAccount enforces. --}}
                    <x-account.field
                        name="password"
                        label="New password"
                        type="password"
                        icon="lock"
                        placeholder="Create a new password"
                        autocomplete="new-password"
                        minlength="6"
                        toggle
                        required-with="current_password"
                    />

                    <x-account.field
                        name="password_confirmation"
                        label="Confirm new password"
                        type="password"
                        icon="lock"
                        placeholder="Re-enter new password"
                        autocomplete="new-password"
                        match="password"
                        toggle
                    />
                </div>
            </fieldset>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:8px">
                <button class="acc-btn" type="submit" data-acc-submit>
                    <svg class="acc-btn__spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9"/></svg>
                    Save changes
                </button>
                <a class="acc-btn acc-btn--ghost" href="{{ url('/my-account') }}">Cancel</a>
            </div>

        </section>
    </form>

</x-account.shell>

@include('includes.footer')

@endsection
