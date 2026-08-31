@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Customers</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($customerCount) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Subscribers</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($subscriberCount) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Offers Sent</p>
        <p class="text-2xl font-bold text-[#bb976d] mt-1">{{ number_format($offersSentCount) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Opted Out</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($optedOutCount) }}</p>
    </div>
</div>

{{-- Audience switch. Accounts and newsletter-only addresses are mailed the same
     way, but they are different lists and staff think of them separately. --}}
<div class="flex flex-wrap items-center gap-2 mb-5">
    <a href="{{ route('admin.customers.index') }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
              {{ $tab === 'customers' ? 'bg-[#bb976d] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:text-gray-800' }}">
        <i class="mdi mdi-account-multiple mr-1"></i> Accounts ({{ number_format($customerCount) }})
    </a>
    <a href="{{ route('admin.customers.index', ['tab' => 'subscribers']) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
              {{ $tab === 'subscribers' ? 'bg-[#bb976d] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:text-gray-800' }}">
        <i class="mdi mdi-email-newsletter mr-1"></i> Newsletter ({{ number_format($subscriberCount) }})
    </a>
    <a href="{{ route('admin.email-templates.index') }}"
       class="ml-auto px-4 py-2 rounded-lg text-sm font-medium bg-white border border-gray-200 text-gray-600 hover:text-gray-800 transition-colors">
        <i class="mdi mdi-file-document-edit-outline mr-1"></i> Email Templates
    </a>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="{{ $tab === 'customers' ? 'Name or email address' : 'Email address' }}"
               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Show</label>
        <select name="filter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
            <option value="">Everyone</option>
            @if($tab === 'customers')
                <option value="buyers"    {{ request('filter') === 'buyers' ? 'selected' : '' }}>Has ordered</option>
                <option value="no-orders" {{ request('filter') === 'no-orders' ? 'selected' : '' }}>Never ordered</option>
            @endif
            <option value="opted-out" {{ request('filter') === 'opted-out' ? 'selected' : '' }}>Unsubscribed</option>
        </select>
    </div>
    @if($tab === 'customers')
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Sort</label>
        <select name="sort" class="text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
            <option value="">Newest first</option>
            <option value="spend"  {{ request('sort') === 'spend' ? 'selected' : '' }}>Highest spend</option>
            <option value="orders" {{ request('sort') === 'orders' ? 'selected' : '' }}>Most orders</option>
        </select>
    </div>
    @endif
    <button type="submit" class="px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">Filter</button>
    @if(request()->hasAny(['q','filter','sort']))
        <a href="{{ route('admin.customers.index', ['tab' => $tab]) }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
    @endif
</form>

@if($tab === 'customers')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[880px]">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Customer</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Orders</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Spent</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Last Order</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Last Offer</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $customer->name }}</p>
                    <p class="text-xs text-gray-500">{{ $customer->email }}</p>
                    @if($customer->marketing_opt_out_at)
                        <span class="inline-block mt-1 text-[11px] font-medium px-1.5 py-0.5 rounded bg-red-50 text-red-600 border border-red-100">Unsubscribed</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-700">{{ $customer->orders_count }}</td>
                <td class="px-5 py-3 text-gray-700">${{ number_format((float) $customer->orders_total, 2) }}</td>
                <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                    {{ $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('d M Y') : '—' }}
                </td>
                <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                    {{ $customer->last_offer_at ? \Carbon\Carbon::parse($customer->last_offer_at)->format('d M Y') : '—' }}
                </td>
                <td class="px-5 py-3 text-right">
                    @if($customer->marketing_opt_out_at)
                        <span class="text-xs text-gray-400">Opted out</span>
                    @else
                        <a href="{{ route('admin.campaigns.compose', ['user' => $customer->id]) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#bb976d] text-white text-xs font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                            <i class="mdi mdi-email-fast-outline"></i> Send Offer
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No customers match this filter.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $customers->links() }}</div>

@else
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Email</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Subscribed</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Last Offer</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($subscribers as $subscriber)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $subscriber->email }}</p>
                    @if($subscriber->unsubscribed_at)
                        <span class="inline-block mt-1 text-[11px] font-medium px-1.5 py-0.5 rounded bg-red-50 text-red-600 border border-red-100">Unsubscribed</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-600 whitespace-nowrap">{{ $subscriber->created_at?->format('d M Y') ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                    {{ $subscriber->last_offer_at ? \Carbon\Carbon::parse($subscriber->last_offer_at)->format('d M Y') : '—' }}
                </td>
                <td class="px-5 py-3 text-right">
                    @if($subscriber->unsubscribed_at)
                        <span class="text-xs text-gray-400">Opted out</span>
                    @else
                        <a href="{{ route('admin.campaigns.compose', ['subscriber' => $subscriber->id]) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#bb976d] text-white text-xs font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                            <i class="mdi mdi-email-fast-outline"></i> Send Offer
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">No subscribers match this filter.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $subscribers->links() }}</div>
@endif

@endsection
