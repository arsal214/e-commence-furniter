@extends('admin.layouts.app')

@section('title', 'Order #' . $order->id)
@section('page-title', 'Order #' . $order->id)

@section('content')
@php
$statusColors = [
    'pending'    => 'bg-yellow-100 text-yellow-700',
    'processing' => 'bg-blue-100 text-blue-700',
    'shipped'    => 'bg-indigo-100 text-indigo-700',
    'delivered'  => 'bg-green-100 text-green-700',
    'cancelled'  => 'bg-red-100 text-red-700',
];
$paymentColors = [
    'pending' => 'bg-orange-100 text-orange-700',
    'paid'    => 'bg-green-100 text-green-700',
];
@endphp

<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-[#bb976d] flex items-center gap-1">
        <i class="mdi mdi-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Order items + summary --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Items table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Order Items</h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Product</th>
                        <th class="text-center px-5 py-3 font-medium text-gray-600">Qty</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Unit Price</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($order->items as $item)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if ($item->product?->image)
                                    @if (str_starts_with($item->product->image, 'assets/'))
                                        <img src="{{ asset($item->product->image) }}" class="w-10 h-10 rounded object-cover bg-gray-100" alt="">
                                    @else
                                        <img src="{{ Storage::url($item->product->image) }}" class="w-10 h-10 rounded object-cover bg-gray-100" alt="">
                                    @endif
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center">
                                        <i class="mdi mdi-image-off text-gray-400 text-sm"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->name }}</p>
                                    @if($item->product)
                                        <a href="{{ route('admin.products.edit', $item->product) }}" class="text-xs text-[#bb976d] hover:underline">Edit product</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center text-gray-700">{{ $item->qty }}</td>
                        <td class="px-5 py-3 text-right text-gray-700">${{ number_format($item->price, 2) }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-800">${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping ({{ ucfirst($order->shipping) }})</span>
                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t border-gray-100">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Customer & Shipping info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Customer & Shipping</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Name</p>
                    <p class="font-medium text-gray-800">{{ $order->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Email</p>
                    <p class="text-gray-800">{{ $order->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Phone</p>
                    <p class="text-gray-800">{{ $order->phone }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">City / Zip</p>
                    <p class="text-gray-800">{{ $order->city ?? '—' }} {{ $order->zip ? '· ' . $order->zip : '' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Address</p>
                    <p class="text-gray-800">{{ $order->address }}{{ $order->address2 ? ', ' . $order->address2 : '' }}</p>
                </div>
                @if($order->notes)
                <div class="sm:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Notes</p>
                    <p class="text-gray-700 italic">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Status management --}}
    <div class="space-y-6">

        {{-- Current status card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Order Status</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Order Status</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Payment</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Method</span>
                    <span class="font-medium text-gray-700">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Stripe' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Placed</span>
                    <span class="text-gray-700">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Update order status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Update Status</h2>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Order Status</label>
                    <select name="status"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Payment Status</label>
                    <select name="payment_status"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <button type="submit"
                        class="w-full px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                    Save Changes
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
