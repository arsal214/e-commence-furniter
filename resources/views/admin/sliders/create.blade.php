@extends('admin.layouts.app')

@section('title', 'Add Slide')
@section('page-title', 'Add Slide')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.sliders.index') }}" class="text-sm text-gray-500 hover:text-[#bb976d] flex items-center gap-1">
        <i class="mdi mdi-arrow-left"></i> Back to Sliders
    </a>
</div>

<form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
    @csrf

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Slide Content</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]"
                       placeholder="e.g. Brand-New Arrival Alert">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]"
                          placeholder="Short description shown under the title">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Button Text <span class="text-red-500">*</span></label>
                <input type="text" name="button_text" value="{{ old('button_text', 'Shop Now') }}" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Button URL <span class="text-red-500">*</span></label>
                <input type="text" name="button_url" value="{{ old('button_url', '/shop-v1') }}" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]"
                       placeholder="/shop-v1">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Year Text</label>
                <input type="text" name="year_text" value="{{ old('year_text', '2026') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]"
                       placeholder="2026">
                <p class="text-gray-400 text-xs mt-1">Large decorative number on the left side.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Badge (floating card)</h2>
        <p class="text-xs text-gray-500">Optional floating card that appears on the decorative blob shape.</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Price / Tag</label>
                <input type="text" name="badge_price" value="{{ old('badge_price') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]"
                       placeholder="$140">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                <input type="text" name="badge_label" value="{{ old('badge_label') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]"
                       placeholder="Aurora Flexible Sofa">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Blob Color</label>
                <input type="color" name="badge_color" value="{{ old('badge_color', '#BB976D') }}"
                       class="h-[38px] w-full border border-gray-200 rounded-lg px-1 py-1 outline-none">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Banner Image</h2>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Image (right-side product photo)</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d] hover:file:bg-[#bb976d]/20">
            <p class="text-gray-400 text-xs mt-1">Recommended: PNG with transparent background, ~750×600 px.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Settings</h2>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                <p class="text-gray-400 text-xs mt-1">Lower number = shown first.</p>
            </div>
            <div class="flex items-center gap-3 pt-5">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-[#bb976d]">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (show on homepage)</label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit"
                class="px-6 py-2.5 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
            Create Slide
        </button>
        <a href="{{ route('admin.sliders.index') }}"
           class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

@endsection
