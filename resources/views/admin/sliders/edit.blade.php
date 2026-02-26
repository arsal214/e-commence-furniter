@extends('admin.layouts.app')

@section('title', 'Edit Slide')
@section('page-title', 'Edit Slide')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.sliders.index') }}" class="text-sm text-gray-500 hover:text-[#bb976d] flex items-center gap-1">
        <i class="mdi mdi-arrow-left"></i> Back to Sliders
    </a>
</div>

<form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Slide Content</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $slider->title) }}" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">{{ old('description', $slider->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Button Text <span class="text-red-500">*</span></label>
                <input type="text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Button URL <span class="text-red-500">*</span></label>
                <input type="text" name="button_url" value="{{ old('button_url', $slider->button_url) }}" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Year Text</label>
                <input type="text" name="year_text" value="{{ old('year_text', $slider->year_text) }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Badge (floating card)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Price / Tag</label>
                <input type="text" name="badge_price" value="{{ old('badge_price', $slider->badge_price) }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]"
                       placeholder="$140">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                <input type="text" name="badge_label" value="{{ old('badge_label', $slider->badge_label) }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Blob Color</label>
                <input type="color" name="badge_color" value="{{ old('badge_color', $slider->badge_color) }}"
                       class="h-[38px] w-full border border-gray-200 rounded-lg px-1 py-1 outline-none">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Banner Image</h2>
        @if ($slider->image)
            <div class="mb-3">
                <p class="text-xs text-gray-500 mb-2">Current image:</p>
                <img src="{{ Storage::url($slider->image) }}" class="h-28 rounded object-cover bg-gray-100" alt="">
            </div>
        @endif
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Replace image</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d] hover:file:bg-[#bb976d]/20">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Settings</h2>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}" min="0"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
            </div>
            <div class="flex items-center gap-3 pt-5">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-[#bb976d]">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (show on homepage)</label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit"
                class="px-6 py-2.5 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
            Save Changes
        </button>
        <a href="{{ route('admin.sliders.index') }}"
           class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

@endsection
