{{-- resources/views/unsubscribed.blade.php --}}
@extends('layouts.main')

@section('title', 'Unsubscribed | PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

<section class="bg-title pt-[64px] pb-[112px] sm:pt-[80px] sm:pb-[128px]">
    <div class="max-w-2xl mx-auto px-5 text-center">
        <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary">
            <svg class="w-8 h-8 text-title" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </span>

        <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-primary">Unsubscribed</p>

        <h1 class="mt-3 text-3xl sm:text-4xl font-bold text-white leading-tight">
            You're off our offers list
        </h1>

        <p class="mt-4 text-base text-white/70 leading-relaxed">
            @if($email)
                <span class="text-white font-medium">{{ $email }}</span> will no longer receive deals and promotional
                emails from us.
            @else
                You will no longer receive deals and promotional emails from us.
            @endif
            You'll still get order confirmations, shipping updates and anything else about a purchase you've made —
            those aren't marketing.
        </p>

        <p class="mt-4 text-sm text-white/50">
            Changed your mind? Subscribe again from the newsletter box at the bottom of any page.
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ url('/shop') }}"
               class="inline-block px-7 py-3 rounded bg-primary text-title text-sm font-bold uppercase tracking-wider hover:opacity-90 transition-opacity">
                Continue Shopping
            </a>
            <a href="{{ url('/contact') }}"
               class="inline-block px-7 py-3 rounded border border-white/25 text-white text-sm font-bold uppercase tracking-wider hover:border-white/60 transition-colors">
                Contact Us
            </a>
        </div>
    </div>
</section>

@include('includes.footer')

@endsection
