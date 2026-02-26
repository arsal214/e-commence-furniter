@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')

{{-- Status filter tabs --}}
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
$currentStatus = request('status');
@endphp

<div class="flex flex-wrap gap-2 mb-5">
    <a href="{{ route('admin.orders.index') }}"
       class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors {{ !$currentStatus ? 'bg-[#bb976d] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        All <span class="ml-1 text-xs opacity-70">{{ $counts['all'] }}</span>
    </a>
    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
    <a href="{{ route('admin.orders.index', ['status' => $s]) }}"
       class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors {{ $currentStatus === $s ? 'bg-[#bb976d] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        {{ ucfirst($s) }} <span class="ml-1 text-xs opacity-70">{{ $counts[$s] }}</span>
    </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">#</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Customer</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Items</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Total</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Payment</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($orders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 font-medium text-gray-800">#{{ $order->id }}</td>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $order->name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->email }}</p>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $order->items->count() }} item(s)</td>
                <td class="px-5 py-3 font-semibold text-gray-800">${{ number_format($order->total, 2) }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $order->payment_method === 'cod' ? 'COD' : 'Stripe' }} · {{ ucfirst($order->payment_status) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs">
                    {{ $order->created_at->format('d M Y') }}<br>
                    <span class="text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                </td>
                <td class="px-5 py-3 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#bb976d] border border-[#bb976d] rounded-lg hover:bg-[#bb976d]/10 transition-colors">
                        <i class="mdi mdi-eye"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-10 text-center text-gray-400">
                    No orders found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($orders->hasPages())
    <div class="mt-4">{{ $orders->links() }}</div>
@endif

@endsection
