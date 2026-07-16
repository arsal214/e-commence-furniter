@extends('admin.layouts.app')

@section('title', 'Edit Review')
@section('page-title', 'Edit Review')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @if ($errors->any())
            <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-lg text-sm">
                <ul class="text-red-700 space-y-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Product <span class="text-red-500">*</span></label>
                <select name="product_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:border-[#bb976d] transition-colors">
                    <option value="">Select a product…</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected((string) old('product_id', $review->product_id) === (string) $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Reviewer Name <span class="text-red-500">*</span></label>
                @if ($review->user_id)
                    {{-- Real customer review: name comes from their account and can't be edited here. --}}
                    <input type="text" value="{{ $review->author_name }}" disabled
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-gray-50 text-gray-500">
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="mdi mdi-account-check"></i> Written by a registered customer — name is locked to their account.
                    </p>
                @else
                    <input type="text" name="reviewer_name" value="{{ old('reviewer_name', $review->reviewer_name) }}" required maxlength="100"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                @endif
            </div>

            @include('admin.reviews._rating', ['current' => old('rating', $review->rating)])

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Comment <span class="text-gray-400 font-normal">(optional, max 1000 chars)</span></label>
                <textarea name="comment" rows="4" maxlength="1000"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors resize-none">{{ old('comment', $review->comment) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                    Update Review
                </button>
                <a href="{{ route('admin.reviews.index') }}"
                   class="px-5 py-2.5 border border-gray-300 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
