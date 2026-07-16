@extends('admin.layouts.app')

@section('title', 'Reviews')
@section('page-title', 'Reviews')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <p class="text-sm text-gray-500">{{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }} total</p>
    <a href="{{ route('admin.reviews.create', ['product_id' => $productId]) }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
        <i class="mdi mdi-plus"></i> Add Review
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col sm:flex-row gap-3 mb-5">
    <select name="product_id" onchange="this.form.submit()"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#bb976d]/40">
        <option value="">All products</option>
        @foreach ($products as $product)
            <option value="{{ $product->id }}" @selected((string) $productId === (string) $product->id)>{{ $product->name }}</option>
        @endforeach
    </select>
    <div class="flex gap-2 flex-1">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search reviewer, comment, product…"
               class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#bb976d]/40">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="mdi mdi-magnify"></i>
        </button>
        @if ($search || $productId)
            <a href="{{ route('admin.reviews.index') }}"
               class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Clear</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Product</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Reviewer</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Rating</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Comment</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($reviews as $review)
            <tr class="hover:bg-gray-50 transition-colors align-top">
                <td class="px-5 py-3 font-medium text-gray-800 max-w-[180px]">
                    {{ $review->product?->name ?? '—' }}
                </td>
                <td class="px-5 py-3 text-gray-700">
                    {{ $review->author_name }}
                    @if ($review->user_id)
                        <span class="ml-1 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-blue-50 text-blue-600">
                            <i class="mdi mdi-account-check text-[10px]"></i> Customer
                        </span>
                    @else
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-500">Admin</span>
                    @endif
                </td>
                <td class="px-5 py-3 whitespace-nowrap text-amber-500">
                    @for ($s = 1; $s <= 5; $s++)
                        <i class="mdi {{ $s <= $review->rating ? 'mdi-star' : 'mdi-star-outline text-gray-300' }}"></i>
                    @endfor
                </td>
                <td class="px-5 py-3 text-gray-500 max-w-[280px]">
                    <span class="line-clamp-2">{{ $review->comment ?: '—' }}</span>
                </td>
                <td class="px-5 py-3 text-gray-400 whitespace-nowrap text-xs">{{ $review->created_at->format('M j, Y') }}</td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.reviews.edit', $review) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#bb976d] border border-[#bb976d] rounded-lg hover:bg-[#bb976d]/10 transition-colors">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                              onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="mdi mdi-delete"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                    No reviews found. <a href="{{ route('admin.reviews.create', ['product_id' => $productId]) }}" class="text-[#bb976d] hover:underline">Add one?</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@if ($reviews->hasPages())
    <div class="mt-4">{{ $reviews->links() }}</div>
@endif
@endsection
