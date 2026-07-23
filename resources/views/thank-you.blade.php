{{-- resources/views/thank-you.blade.php --}}
@extends('layouts.main')

@section('title', 'Order Confirmed | PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

@php
    // Only dark: utilities that exist in the compiled template CSS are used here.
    // The Vite/Tailwind build emits dark: as a media query, which would ignore the
    // site's localStorage class toggle.
    $shippingLabels = ['free' => 'Free Shipping', 'fast' => 'Fast Shipping', 'local' => 'Local Pickup'];
@endphp

@if ($order)
    @php
        $firstName     = \Str::of($order->name)->trim()->explode(' ')->first();
        $shippingLabel = $shippingLabels[$order->shipping] ?? ucfirst($order->shipping);
        $isPaid        = $order->payment_status === 'paid';
        $trackUrl      = url('/track-order?tracking=' . $order->tracking_number);
    @endphp

    {{-- ── Confirmation hero ──────────────────────────────────────────── --}}
    {{-- Arbitrary values: a base pt-*/py-* from style.css (which loads after the Vite
         CSS) would override the sm: variant and the bump would never apply. --}}
    <section class="bg-title pt-[64px] pb-[112px] sm:pt-[80px] sm:pb-[128px]">
        <div class="max-w-3xl mx-auto px-5 text-center">
            <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary">
                <svg class="w-8 h-8 text-title" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </span>

            <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-primary">Order Confirmed</p>

            <h1 class="mt-3 text-3xl sm:text-4xl font-bold text-white leading-tight">
                Thank you, {{ $firstName }}.
            </h1>

            <p class="mt-4 text-base text-white-light leading-relaxed">
                Your order is in. We've sent a confirmation with your receipt and tracking details to
                <span class="font-semibold text-white">{{ $order->email }}</span>.
            </p>
        </div>
    </section>

    {{-- ── Tracking card (overlaps the hero) ──────────────────────────── --}}
    <div class="max-w-3xl mx-auto px-5">
        <div class="-mt-16 bg-white dark:bg-dark-card-bg border border-gray-100 dark:border-bdr-clr-drk shadow-lg rounded-2xl p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-widest text-paragraph dark:text-white-light">
                        Tracking Number
                    </p>
                    <div class="mt-2 flex items-center gap-3 flex-wrap">
                        <span id="tracking-number"
                              class="text-2xl sm:text-3xl font-bold tracking-widest text-title dark:text-white break-all">
                            {{ $order->tracking_number }}
                        </span>

                        <button type="button"
                                id="copy-tracking"
                                data-tracking="{{ $order->tracking_number }}"
                                aria-label="Copy tracking number to clipboard"
                                class="inline-flex items-center justify-center gap-1.5 min-h-[44px] px-3 rounded-lg border border-gray-200 dark:border-bdr-clr-drk text-sm font-medium text-title dark:text-white hover:border-primary hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-colors duration-200 cursor-pointer">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="9" y="9" width="13" height="13" rx="2"/>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            <span id="copy-label">Copy</span>
                        </button>
                    </div>

                    {{-- Announced to screen readers; not colour-only feedback --}}
                    <p id="copy-status" role="status" aria-live="polite" class="sr-only"></p>
                </div>

                <a href="{{ $trackUrl }}"
                   class="shrink-0 inline-flex items-center justify-center gap-2 min-h-[48px] px-6 rounded-lg bg-primary text-title font-semibold text-sm hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-opacity duration-200 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    Track My Order
                </a>
            </div>
        </div>
    </div>

    {{-- ── What happens next ──────────────────────────────────────────── --}}
    <section class="max-w-3xl mx-auto px-5 pt-14">
        <h2 class="text-lg font-bold text-title dark:text-white">What happens next</h2>

        <ol class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
            @php
                $next = [
                    ['title' => 'Confirmation sent', 'body' => "Your receipt is on its way to your inbox. Check spam if it hasn't arrived in a few minutes.",
                     'icon'  => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>'],
                    ['title' => 'We prepare it', 'body' => 'Our team picks and packs your order. You can change it by replying to the email within 24 hours.',
                     'icon'  => '<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>'],
                    ['title' => 'On its way', 'body' => "We'll email you the moment it ships, with carrier details so you can follow it to your door.",
                     'icon'  => '<path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>'],
                ];
            @endphp

            @foreach ($next as $i => $step)
                <li class="rounded-xl border border-gray-100 dark:border-bdr-clr-drk bg-white dark:bg-dark-card-bg p-5">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-snow dark:bg-white/10">
                        <svg class="w-5 h-5 text-title dark:text-white" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                             stroke-linejoin="round" aria-hidden="true">
                            {!! $step['icon'] !!}
                        </svg>
                    </span>
                    <p class="mt-4 text-sm font-bold text-title dark:text-white">
                        {{ $i + 1 }}. {{ $step['title'] }}
                    </p>
                    <p class="mt-1.5 text-sm leading-relaxed text-paragraph dark:text-white-light">
                        {{ $step['body'] }}
                    </p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- ── Order summary ──────────────────────────────────────────────── --}}
    <section class="max-w-3xl mx-auto px-5 pt-14">
        <div class="rounded-2xl border border-gray-100 dark:border-bdr-clr-drk bg-white dark:bg-dark-card-bg overflow-hidden">

            <div class="flex items-center justify-between flex-wrap gap-2 px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-bdr-clr-drk">
                <h2 class="text-lg font-bold text-title dark:text-white">Order summary</h2>
                <p class="text-sm text-paragraph dark:text-white-light">
                    Placed {{ $order->created_at->format('d M Y') }}
                </p>
            </div>

            {{-- Items --}}
            <ul class="px-6 sm:px-8 divide-y divide-gray-100 dark:divide-bdr-clr-drk">
                @foreach ($order->items as $item)
                    @php
                        $raw = optional($item->product)->image;
                        $img = $raw
                            ? (\Str::startsWith($raw, ['http://', 'https://'])
                                ? $raw
                                : (\Str::startsWith($raw, 'assets/') ? asset($raw) : \Storage::url($raw)))
                            : null;
                    @endphp
                    <li class="flex items-center gap-4 py-4">
                        @if ($img)
                            <img src="{{ $img }}" alt="{{ $item->name }}" width="64" height="64" loading="lazy"
                                 class="w-16 h-16 shrink-0 object-cover rounded-lg border border-gray-100 dark:border-bdr-clr-drk bg-snow">
                        @else
                            <span class="w-16 h-16 shrink-0 rounded-lg border border-gray-100 dark:border-bdr-clr-drk bg-snow dark:bg-white/10" aria-hidden="true"></span>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-title dark:text-white truncate">{{ $item->name }}</p>
                            <p class="mt-0.5 text-sm text-paragraph dark:text-white-light">
                                Qty {{ $item->qty }} &middot; ${{ number_format($item->price, 2) }} each
                            </p>
                        </div>

                        <p class="shrink-0 text-sm font-bold text-title dark:text-white tabular-nums">
                            ${{ number_format($item->total, 2) }}
                        </p>
                    </li>
                @endforeach
            </ul>

            {{-- Totals --}}
            <div class="px-6 sm:px-8 py-5 border-t border-gray-100 dark:border-bdr-clr-drk">
                <dl class="space-y-2.5 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-paragraph dark:text-white-light">Subtotal</dt>
                        <dd class="text-title dark:text-white tabular-nums">${{ number_format($order->subtotal, 2) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-paragraph dark:text-white-light">{{ $shippingLabel }}</dt>
                        <dd class="text-title dark:text-white tabular-nums">
                            {{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-bdr-clr-drk">
                        <dt class="text-base font-bold text-title dark:text-white">Total</dt>
                        <dd class="text-lg font-bold text-title dark:text-white tabular-nums">
                            ${{ number_format($order->total, 2) }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Delivery + payment --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 px-6 sm:px-8 py-6 border-t border-gray-100 dark:border-bdr-clr-drk bg-snow dark:bg-white/10">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-paragraph dark:text-white-light">
                        Delivering to
                    </h3>
                    <address class="mt-2 not-italic text-sm leading-relaxed text-title dark:text-white">
                        <span class="font-semibold">{{ $order->name }}</span><br>
                        {{ $order->address }}@if ($order->address2)<br>{{ $order->address2 }}@endif
                        @if ($order->city || $order->zip)
                            <br>{{ collect([$order->city, $order->zip])->filter()->implode(', ') }}
                        @endif
                        <br>{{ $order->phone }}
                    </address>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-paragraph dark:text-white-light">
                        Payment
                    </h3>
                    <p class="mt-2 text-sm text-title dark:text-white">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card' }}
                    </p>
                    {{-- Icon + text, so status isn't carried by colour alone. The dark variants
                         come from the template CSS — arbitrary dark:text-[#hex] would compile to a
                         media query and ignore the site's class toggle. --}}
                    <p class="mt-1.5 inline-flex items-center gap-1.5 text-sm font-semibold {{ $isPaid ? 'text-[#1F7A4C] dark:text-white' : 'text-[#8A6A3F] dark:text-primary' }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @if ($isPaid)
                                <path d="M20 6 9 17l-5-5"/>
                            @else
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            @endif
                        </svg>
                        {{ $isPaid ? 'Paid' : 'Due on delivery' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Footer actions ─────────────────────────────────────────────── --}}
    <section class="max-w-3xl mx-auto px-5 py-14">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center min-h-[48px] px-8 rounded-lg bg-title dark:bg-primary text-white dark:text-title font-semibold text-sm hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-opacity duration-200 cursor-pointer">
                Continue Shopping
            </a>
            <a href="{{ url('/faq') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center min-h-[48px] px-8 rounded-lg border border-gray-200 dark:border-bdr-clr-drk text-title dark:text-white font-semibold text-sm hover:border-primary hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-colors duration-200 cursor-pointer">
                Need Help?
            </a>
        </div>
    </section>

@else

    {{-- ── Fallback: no order in session (direct visit or expired) ────── --}}
    <section class="max-w-xl mx-auto px-5 py-[80px] sm:py-[112px] text-center">
        <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary">
            <svg class="w-8 h-8 text-title" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </span>

        <h1 class="mt-6 text-3xl font-bold text-title dark:text-white">Thank you for shopping with us.</h1>

        <p class="mt-4 text-base leading-relaxed text-paragraph dark:text-white-light">
            We don't have a recent order open in this session. If you've just placed one, your confirmation
            email has the tracking number — you can look it up any time.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/track-order') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center min-h-[48px] px-8 rounded-lg bg-primary text-title font-semibold text-sm hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-opacity duration-200 cursor-pointer">
                Track an Order
            </a>
            <a href="{{ url('/') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center min-h-[48px] px-8 rounded-lg border border-gray-200 dark:border-bdr-clr-drk text-title dark:text-white font-semibold text-sm hover:border-primary hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-colors duration-200 cursor-pointer">
                Back to Home
            </a>
        </div>
    </section>

@endif

@include('includes.footer')

@endsection

@push('scripts')
@if ($order)
<script>
    (function () {
        var btn = document.getElementById('copy-tracking');
        if (!btn) return;

        var label  = document.getElementById('copy-label');
        var status = document.getElementById('copy-status');
        var reset;

        function confirmCopied() {
            label.textContent = 'Copied';
            status.textContent = 'Tracking number copied to clipboard.';
            clearTimeout(reset);
            reset = setTimeout(function () {
                label.textContent = 'Copy';
                status.textContent = '';
            }, 2000);
        }

        btn.addEventListener('click', function () {
            var value = btn.dataset.tracking;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(value).then(confirmCopied);
                return;
            }

            // http:// and older browsers have no clipboard API.
            var field = document.createElement('textarea');
            field.value = value;
            field.setAttribute('readonly', '');
            field.style.position = 'fixed';
            field.style.opacity = '0';
            document.body.appendChild(field);
            field.select();
            try { document.execCommand('copy'); confirmCopied(); } catch (e) { /* leave the number selectable */ }
            document.body.removeChild(field);
        });
    })();
</script>
@endif

{{-- ── Meta Pixel Purchase ──────────────────────────────────────────────
     Gated on the flashed order_total, so refreshing /thank-you cannot register
     the same purchase twice. The payload is built from the order row while it
     is still in session; the flashed total is the fallback so a sale is never
     reported without a value. eventID follows the TikTok convention
     (deterministic on the order id) so a Conversions API twin added later
     deduplicates against this browser event. --}}
@if (session()->has('order_total'))
    @php
        $pixelValue = (float) ($order?->total ?? session('order_total'));

        // This store carries no catalog SKUs (see TikTokEventsService::contents),
        // so content ids fall back to the product id the feed is keyed on.
        $pixelContents = $order
            ? $order->items->map(fn ($item) => [
                'id'         => (string) ($item->sku ?: $item->product_id),
                'quantity'   => (int) $item->qty,
                'item_price' => (float) $item->price,
            ])->values()->all()
            : [];
    @endphp
<script>
    if (typeof fbq === 'function') {
        fbq('track', 'Purchase', {
            value: {{ number_format($pixelValue, 2, '.', '') }},
            currency: 'USD',
            content_type: 'product',
            contents: {!! json_encode($pixelContents, JSON_UNESCAPED_SLASHES) !!},
            num_items: {{ (int) ($order ? $order->items->sum('qty') : 0) }}
        }@if ($order), { eventID: @json('Purchase.order-' . $order->id) }@endif);
    }
</script>
@endif
@endpush
