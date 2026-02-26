@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Products</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="mdi mdi-package-variant text-blue-600 text-2xl"></i>
            </div>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-blue-600 hover:underline mt-3 block">View all products →</a>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Orders</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</p>
                @if($pendingOrders > 0)
                    <p class="text-xs text-orange-500 font-medium mt-0.5">{{ $pendingOrders }} pending</p>
                @endif
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="mdi mdi-clipboard-list text-orange-600 text-2xl"></i>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-orange-600 hover:underline mt-3 block">View all orders →</a>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Revenue (Paid)</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($totalRevenue, 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <i class="mdi mdi-currency-usd text-green-600 text-2xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">From paid orders only</p>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Customers</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="mdi mdi-account-group text-purple-600 text-2xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Registered customers</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Quick actions -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="space-y-3">
            <a href="{{ route('admin.products.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg bg-[#bb976d]/10 hover:bg-[#bb976d]/20 transition-colors">
                <i class="mdi mdi-plus-circle text-[#bb976d] text-xl"></i>
                <span class="text-sm font-medium text-gray-700">Add New Product</span>
            </a>
            <a href="{{ route('admin.categories.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors">
                <i class="mdi mdi-plus-circle text-purple-600 text-xl"></i>
                <span class="text-sm font-medium text-gray-700">Add New Category</span>
            </a>
            <a href="{{ url('/shop-v1') }}" target="_blank"
               class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <i class="mdi mdi-store text-gray-500 text-xl"></i>
                <span class="text-sm font-medium text-gray-700">View Shop Page</span>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-800">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#bb976d] hover:underline">View all</a>
        </div>
        @php $recentOrders = \App\Models\Order::latest()->take(6)->get(); @endphp
        @if ($recentOrders->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">No orders yet.</p>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($recentOrders as $order)
                @php
                    $statusColors = [
                        'pending'    => 'bg-yellow-100 text-yellow-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'shipped'    => 'bg-indigo-100 text-indigo-700',
                        'delivered'  => 'bg-green-100 text-green-700',
                        'cancelled'  => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <li class="py-2.5 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#bb976d]/10 flex items-center justify-center flex-shrink-0">
                        <i class="mdi mdi-receipt text-[#bb976d] text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">#{{ $order->id }} · {{ $order->name }}</p>
                        <p class="text-xs text-gray-400">${{ number_format($order->total, 2) }} · {{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-xs text-[#bb976d] hover:underline ml-1">View</a>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
