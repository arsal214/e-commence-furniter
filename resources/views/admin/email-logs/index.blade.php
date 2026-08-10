@extends('admin.layouts.app')

@section('title', 'Email Logs')
@section('page-title', 'Email Logs')

@section('content')

<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Total Logged</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($sentCount + $failedCount) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Delivered</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($sentCount) }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border {{ $failedCount ? 'border-red-200' : 'border-gray-100' }}">
        <p class="text-sm text-gray-500 font-medium">Failed</p>
        <p class="text-2xl font-bold {{ $failedCount ? 'text-red-600' : 'text-gray-800' }} mt-1">{{ number_format($failedCount) }}</p>
    </div>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Email address, name or subject"
               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
        <select name="type" class="text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
            <option value="">All types</option>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
        <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
            <option value="">All</option>
            <option value="sent"   {{ request('status') === 'sent' ? 'selected' : '' }}>Delivered</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">Filter</button>
    @if(request()->hasAny(['q','type','status']))
        <a href="{{ route('admin.email-logs.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
    @endif
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[860px]">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Sent</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Recipient</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Type</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Subject</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Order</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                    {{ optional($log->sent_at ?? $log->created_at)->format('d M Y, H:i') }}
                    <span class="block text-xs text-gray-400">UTC</span>
                </td>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $log->to_email }}</p>
                    @if($log->to_name)<p class="text-xs text-gray-400">{{ $log->to_name }}</p>@endif
                    @if($log->user_id)
                        <span class="text-xs text-gray-400">Account #{{ $log->user_id }}</span>
                    @else
                        <span class="text-xs text-gray-300">Guest</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-700 whitespace-nowrap">{{ $log->type_label }}</td>
                <td class="px-5 py-3 text-gray-600 max-w-xs truncate" title="{{ $log->subject }}">{{ $log->subject ?: '—' }}</td>
                <td class="px-5 py-3">
                    @if($log->order_id)
                        <a href="{{ route('admin.orders.show', $log->order_id) }}" class="text-[#bb976d] hover:underline">#{{ $log->order_id }}</a>
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    @if($log->failed())
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700" title="{{ $log->error }}">Failed</span>
                        @if($log->error)
                            <p class="text-xs text-red-500 mt-1 max-w-xs truncate" title="{{ $log->error }}">{{ $log->error }}</p>
                        @endif
                    @else
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Delivered</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                    <i class="mdi mdi-email-off-outline text-3xl block mb-2"></i>
                    No emails logged yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->hasPages())
<div class="mt-5">{{ $logs->links() }}</div>
@endif

<p class="text-xs text-gray-400 mt-4">
    "Delivered" means the message was accepted by the mail server, which is not the same as it reaching the inbox.
    Bounces and spam filtering happen after this point and are not visible here.
</p>

@endsection
