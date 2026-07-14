{{-- resources/views/order-history.blade.php --}}
@extends('layouts.main')

@section('title', 'Order History — PeytonGhalib')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

<x-account.shell
    active="orders"
    :user="$user"
    crumb="Order History"
    heading="Order history"
    subheading="Every order you've placed, newest first."
>

    <section class="acc-panel" aria-labelledby="acc-orders-title">
        <div class="acc-panel__head">
            <h2 class="acc-panel__title" id="acc-orders-title">
                Your orders
                @if ($orders->isNotEmpty())
                    <span class="acc-order__date">({{ $orders->count() }})</span>
                @endif
            </h2>
        </div>

        @forelse ($orders as $order)
            <article class="acc-ordercard">

                <header class="acc-ordercard__head">
                    <div class="acc-ordercard__facts">
                        <div>
                            <span class="acc-ordercard__k">Order</span>
                            <span class="acc-ordercard__v">#{{ $order->id }}</span>
                        </div>
                        <div>
                            <span class="acc-ordercard__k">Date</span>
                            <span class="acc-ordercard__v">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="acc-ordercard__k">Total</span>
                            <span class="acc-ordercard__v">${{ number_format($order->total, 2) }}</span>
                        </div>
                        <div>
                            <span class="acc-ordercard__k">Payment</span>
                            <span class="acc-ordercard__v">
                                {{ $order->payment_method === 'cod' ? 'Cash on delivery' : ucfirst($order->payment_method) }}
                            </span>
                        </div>
                    </div>

                    <x-account.status :status="$order->status" />
                </header>

                <ul class="acc-ordercard__items">
                    @foreach ($order->items as $item)
                        <li class="acc-ordercard__item">
                            @php $img = $item->product?->image; @endphp
                            @if ($img)
                                <img
                                    class="acc-ordercard__thumb"
                                    src="{{ Str::startsWith($img, 'assets/') ? asset($img) : Storage::url($img) }}"
                                    alt="" width="46" height="46" loading="lazy"
                                >
                            @endif

                            <div style="min-width:0">
                                <p class="acc-ordercard__name">
                                    @if ($item->product?->slug)
                                        <a class="acc-link" href="{{ route('product-details', $item->product->slug) }}">{{ $item->name }}</a>
                                    @else
                                        {{ $item->name }}
                                    @endif
                                </p>
                                <p class="acc-ordercard__qty">
                                    Qty {{ $item->qty }} &times; ${{ number_format($item->price, 2) }}
                                </p>
                            </div>

                            <span class="acc-ordercard__line">${{ number_format($item->total, 2) }}</span>
                        </li>
                    @endforeach
                </ul>

                <footer class="acc-ordercard__foot">
                    <span class="acc-ordercard__track">
                        @if ($order->tracking_number)
                            Tracking <strong>{{ $order->tracking_number }}</strong>
                        @else
                            No tracking number yet
                        @endif
                        @if ($order->shipping_cost > 0)
                            &middot; Shipping ${{ number_format($order->shipping_cost, 2) }}
                        @else
                            &middot; Free shipping
                        @endif
                    </span>

                    <span class="acc-ordercard__total">Total ${{ number_format($order->total, 2) }}</span>
                </footer>

            </article>
        @empty
            <div class="acc-empty">
                <div class="acc-empty__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <p class="acc-empty__title">No orders yet</p>
                <p class="acc-empty__text">Once you place an order, you'll be able to track it right here.</p>
                <a class="acc-btn" href="{{ url('/shop') }}">Start shopping</a>
            </div>
        @endforelse
    </section>

</x-account.shell>

@include('includes.footer')

@endsection
