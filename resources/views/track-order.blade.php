@extends('layouts.main')

@section('title', 'Track Your Order')

@section('content')

{{-- Page Header --}}
<div class="bg-[#1a1a1a] py-12 text-center">
    <h1 class="text-3xl font-bold text-white mb-2">Track Your Order</h1>
    <p class="text-gray-400 text-sm">Enter your tracking number to see the latest status of your order.</p>
</div>

<div class="max-w-2xl mx-auto px-4 py-16">

    {{-- Search Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#bb976d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Find My Order
        </h2>
        <form method="GET" action="{{ url('/track-order') }}" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tracking Number</label>
                <input type="text" name="tracking" value="{{ old('tracking', request('tracking')) }}"
                       placeholder="e.g. FRN-2026-XXXXXXXX"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#bb976d] transition-colors uppercase tracking-widest">
            </div>
            <button type="submit"
                    class="w-full py-3 bg-[#bb976d] text-white font-semibold rounded-lg hover:bg-[#a8845a] transition-colors text-sm">
                Track Order
            </button>
        </form>
    </div>

    {{-- Results --}}
    @if(isset($order))

    {{-- Tracking Number + Status Badge --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-[#bb976d] to-[#a8845a] px-8 py-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-sm opacity-80 mb-1">Tracking Number</p>
                    <p class="text-2xl font-bold tracking-widest">{{ $order->tracking_number }}</p>
                </div>
                @php
                $statusLabels = [
                    'pending'    => ['label' => 'Order Placed',  'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                    'processing' => ['label' => 'Processing',    'bg' => 'bg-blue-100',   'text' => 'text-blue-800'],
                    'shipped'    => ['label' => 'Shipped',       'bg' => 'bg-indigo-100', 'text' => 'text-indigo-800'],
                    'delivered'  => ['label' => 'Delivered',     'bg' => 'bg-green-100',  'text' => 'text-green-800'],
                    'cancelled'  => ['label' => 'Cancelled',     'bg' => 'bg-red-100',    'text' => 'text-red-800'],
                ];
                $s = $statusLabels[$order->status] ?? ['label' => ucfirst($order->status), 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                @endphp
                <span class="inline-flex px-4 py-2 rounded-full text-sm font-bold {{ $s['bg'] }} {{ $s['text'] }}">
                    {{ $s['label'] }}
                </span>
            </div>
        </div>

        {{-- Progress Steps --}}
        <div class="px-8 py-6 border-b border-gray-100">
            @php
            $steps = [
                ['key' => 'pending',    'label' => 'Order Placed',  'icon' => '&#10003;'],
                ['key' => 'processing', 'label' => 'Processing',    'icon' => '&#9881;'],
                ['key' => 'shipped',    'label' => 'Shipped',       'icon' => '&#128666;'],
                ['key' => 'delivered',  'label' => 'Delivered',     'icon' => '&#127968;'],
            ];
            $statusOrder = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3, 'cancelled' => -1];
            $currentStep = $statusOrder[$order->status] ?? 0;
            @endphp

            @if($order->status !== 'cancelled')
            <div class="flex items-center justify-between relative">
                {{-- Progress Line --}}
                <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200 z-0">
                    @php $pct = $currentStep > 0 ? (min($currentStep, 3) / 3 * 100) : 0; @endphp
                    <div class="h-full bg-[#bb976d] transition-all" style="width: {{ $pct }}%"></div>
                </div>

                @foreach($steps as $i => $step)
                @php $done = $i <= $currentStep; @endphp
                <div class="flex flex-col items-center relative z-10 flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all
                        {{ $done ? 'bg-[#bb976d] border-[#bb976d] text-white' : 'bg-white border-gray-300 text-gray-400' }}">
                        {!! $step['icon'] !!}
                    </div>
                    <p class="mt-2 text-xs font-medium text-center leading-tight
                        {{ $done ? 'text-[#bb976d]' : 'text-gray-400' }}">
                        {{ $step['label'] }}
                    </p>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-red-600 font-semibold">This order has been cancelled.</p>
                <p class="text-gray-400 text-sm mt-1">Please contact support if you have any questions.</p>
            </div>
            @endif
        </div>

        {{-- Order Details --}}
        <div class="px-8 py-6 space-y-5">

            {{-- Dates --}}
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Order Placed</p>
                    <p class="font-medium text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
                    <p class="text-gray-400 text-xs">{{ $order->created_at->format('H:i') }}</p>
                </div>
                @if($order->shipped_at)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Shipped On</p>
                    <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($order->shipped_at)->format('d M Y') }}</p>
                </div>
                @endif
            </div>

            {{-- Delivery Address --}}
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Delivering To</p>
                <p class="font-medium text-gray-800">{{ $order->name }}</p>
                <p class="text-gray-600 text-sm">{{ $order->address }}{{ $order->address2 ? ', '.$order->address2 : '' }}</p>
                <p class="text-gray-600 text-sm">{{ $order->city ?? '' }}{{ $order->zip ? ' · '.$order->zip : '' }}</p>
            </div>

            {{-- Items --}}
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Items Ordered ({{ $order->items->count() }})</p>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center text-sm py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->name }}</p>
                            <p class="text-gray-400 text-xs">Qty: {{ $item->qty }}</p>
                        </div>
                        <p class="font-semibold text-gray-700">${{ number_format($item->total, 2) }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="pt-3 flex justify-between font-bold text-gray-800">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>

        </div>
    </div>

    @elseif(request('tracking'))
    {{-- Not found --}}
    <div class="bg-white rounded-2xl border border-red-100 p-8 text-center">
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Order Not Found</h3>
        <p class="text-gray-500 text-sm">We couldn't find any order with tracking number <strong class="text-gray-700">{{ request('tracking') }}</strong>.</p>
        <p class="text-gray-400 text-xs mt-2">Please check your email for the correct tracking number, or contact our support team.</p>
    </div>
    @endif

    {{-- Help Text --}}
    <div class="mt-8 text-center text-sm text-gray-400">
        <p>Can't find your order? <a href="{{ route('contact.show') }}" class="text-[#bb976d] hover:underline">Contact Support</a></p>
    </div>

</div>

@endsection
