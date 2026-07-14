{{--
    Order status pill. Carries an icon as well as a colour, so the state is
    still readable without colour perception (and in print/greyscale).

    Statuses come from Admin\OrderController: pending, processing, shipped,
    delivered, cancelled.
--}}
@props(['status'])

@php
    $key  = strtolower((string) $status);
    $tone = match ($key) {
        'delivered'  => 'success',
        'shipped'    => 'info',
        'processing' => 'progress',
        'cancelled'  => 'danger',
        default      => 'pending',   // pending, or anything unrecognised
    };
@endphp

<span class="acc-pill acc-pill--{{ $tone }}">
    <span class="acc-pill__icon" aria-hidden="true">
        @switch($tone)
            @case('success')
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                @break
            @case('info')
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/></svg>
                @break
            @case('progress')
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg>
                @break
            @case('danger')
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                @break
            @default
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        @endswitch
    </span>
    {{ ucfirst($key ?: 'pending') }}
</span>
