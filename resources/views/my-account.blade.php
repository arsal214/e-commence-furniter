{{-- resources/views/my-account.blade.php --}}
@extends('layouts.main')

@section('title', 'My Account — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@php
    // Derived from the already-loaded collection — no extra queries.
    $orderCount = $orders->count();
    $totalSpent = $orders->sum('total');
    $inProgress = $orders->whereIn('status', ['pending', 'processing', 'shipped'])->count();
@endphp

@section('content')

@include('includes.navbar')

<x-account.shell
    active="dashboard"
    :user="$user"
    subheading="Here's what's happening with your orders."
>

    <x-slot:stats>
        <div class="acc-stat">
            <span class="acc-stat__label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                Orders
            </span>
            <span class="acc-stat__value">{{ $orderCount }}</span>
        </div>

        <div class="acc-stat">
            <span class="acc-stat__label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Total spent
            </span>
            <span class="acc-stat__value">${{ number_format($totalSpent, 2) }}</span>
        </div>

        <div class="acc-stat">
            <span class="acc-stat__label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg>
                In progress
            </span>
            <span class="acc-stat__value">{{ $inProgress }}</span>
        </div>

        <div class="acc-stat">
            <span class="acc-stat__label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1L12 21l7.7-7.7 1.1-1a5.5 5.5 0 0 0 0-7.7z"/></svg>
                Wishlist
            </span>
            <span class="acc-stat__value">{{ $wishlistCount }}</span>
        </div>
    </x-slot:stats>

    {{-- Profile --}}
    <section class="acc-panel" aria-labelledby="acc-profile-title">
        <div class="acc-panel__head">
            <h2 class="acc-panel__title" id="acc-profile-title">Profile</h2>
            <a class="acc-btn" href="{{ url('/edit-account') }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                Edit profile
            </a>
        </div>

        <div class="acc-detail">
            <div class="acc-detail__item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                <span>
                    <span class="acc-detail__k">Name</span>
                    <span class="acc-detail__v">{{ $user->name }}</span>
                </span>
            </div>

            <div class="acc-detail__item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></svg>
                <span>
                    <span class="acc-detail__k">Email</span>
                    <span class="acc-detail__v">{{ $user->email }}</span>
                </span>
            </div>

            <div class="acc-detail__item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                <span>
                    <span class="acc-detail__k">Member since</span>
                    <span class="acc-detail__v">{{ $user->created_at->format('F Y') }}</span>
                </span>
            </div>

            <div class="acc-detail__item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>
                    <span class="acc-detail__k">Account type</span>
                    <span class="acc-detail__v">{{ ucfirst($user->role ?? 'customer') }}</span>
                </span>
            </div>
        </div>
    </section>

    {{-- Recent orders --}}
    <section class="acc-panel" aria-labelledby="acc-orders-title">
        <div class="acc-panel__head">
            <h2 class="acc-panel__title" id="acc-orders-title">Recent orders</h2>
            @if ($orders->isNotEmpty())
                <a class="acc-link" href="{{ url('/order-history') }}">
                    View all
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

        @if ($orders->isEmpty())
            <div class="acc-empty">
                <div class="acc-empty__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <p class="acc-empty__title">No orders yet</p>
                <p class="acc-empty__text">When you place an order, it'll show up here with its status and tracking number.</p>
                <a class="acc-btn" href="{{ url('/shop') }}">Start shopping</a>
            </div>
        @else
            <ul class="acc-orders">
                @foreach ($orders->take(5) as $order)
                    <li class="acc-order">
                        <div class="acc-order__main">
                            <div class="acc-order__thumbs">
                                @foreach ($order->items->take(3) as $item)
                                    @php $img = $item->product?->image; @endphp
                                    @if ($img)
                                        <img
                                            class="acc-order__thumb"
                                            src="{{ Str::startsWith($img, 'assets/') ? asset($img) : Storage::url($img) }}"
                                            alt="" width="44" height="44" loading="lazy"
                                        >
                                    @endif
                                @endforeach

                                @if ($order->items->count() > 3)
                                    <span class="acc-order__more">+{{ $order->items->count() - 3 }}</span>
                                @endif
                            </div>

                            <div style="min-width:0">
                                <span class="acc-order__id">
                                    {{ $order->tracking_number ?? '#' . $order->id }}
                                    &middot; {{ $order->items->count() }} item{{ $order->items->count() === 1 ? '' : 's' }}
                                </span>
                                <p class="acc-order__name">{{ $order->items->first()?->name ?? 'Order' }}</p>
                            </div>
                        </div>

                        <div class="acc-order__meta">
                            <span class="acc-order__date">{{ $order->created_at->format('d M Y') }}</span>
                            <span class="acc-order__total">${{ number_format($order->total, 2) }}</span>
                            <x-account.status :status="$order->status" />
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

</x-account.shell>

@include('includes.footer')

@endsection
