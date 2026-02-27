@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @if ($errors->any())
            <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-lg text-sm">
                <ul class="text-red-700 space-y-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- ── Basic Info ── --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Basic Info</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors bg-white">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Price ($) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sale Price ($) <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Badge Tag</label>
                        <select name="tag"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors bg-white">
                            <option value="">None</option>
                            <option value="Sale"  {{ old('tag') === 'Sale'  ? 'selected' : '' }}>Hot Sale (green)</option>
                            <option value="NEW"   {{ old('tag') === 'NEW'   ? 'selected' : '' }}>NEW (purple)</option>
                            <option value="OFF"   {{ old('tag') === 'OFF'   ? 'selected' : '' }}>10% OFF (red)</option>
                            <option value="OFF1"  {{ old('tag') === 'OFF1'  ? 'selected' : '' }}>15% OFF (red)</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" class="tinymce-editor">{{ old('description') }}</textarea>
                    </div>

                    {{-- Review Content --}}
                    <div class="sm:col-span-2 border-t border-gray-100 pt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Reviews / Testimonials</label>
                        <p class="text-xs text-gray-400 mb-2">Shown in the "Reviews" tab on the product page.</p>
                        <textarea name="review_content" id="review_content" class="tinymce-editor">{{ old('review_content') }}</textarea>
                    </div>

                    {{-- Shipping Info --}}
                    <div class="sm:col-span-2 border-t border-gray-100 pt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipping Information</label>
                        <p class="text-xs text-gray-400 mb-2">Shown in the "Shipping" tab on the product page.</p>
                        <textarea name="shipping_info" id="shipping_info" class="tinymce-editor">{{ old('shipping_info') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Image</label>
                        <input type="file" name="image" accept="image/*" id="imageInput"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#bb976d] transition-colors file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d]">
                        <p class="text-xs text-gray-400 mt-1">Max 4MB. JPG, PNG, WEBP.</p>
                        <img id="imagePreview" src="#" alt="Preview" class="mt-3 w-28 h-28 object-cover rounded-lg border border-gray-200 hidden">
                    </div>
                </div>
            </div>

            {{-- ── Variants ── --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Product Variants</h3>
                <p class="text-xs text-gray-400 mb-5">
                    Add colors, sizes, or any variant options. Type a value and press
                    <kbd class="px-1 py-0.5 bg-gray-100 rounded text-[11px] font-mono">Enter</kbd> or
                    <kbd class="px-1 py-0.5 bg-gray-100 rounded text-[11px] font-mono">,</kbd>
                    to add it as a tag. Leave empty if not applicable.
                </p>

                {{-- Colors --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Colors
                        <span class="text-gray-400 font-normal ml-1 text-xs">e.g. Red, Blue, Black, Walnut, Ivory</span>
                    </label>
                    <div class="variant-tag-box border border-gray-300 rounded-lg px-3 py-2 min-h-[46px] flex flex-wrap gap-2 items-center cursor-text focus-within:border-[#bb976d] transition-colors">
                        <span class="variant-placeholder text-sm text-gray-400 select-none pointer-events-none">Type a color and press Enter...</span>
                        <input type="text" class="variant-input flex-1 min-w-[140px] outline-none text-sm bg-transparent py-0.5" autocomplete="off">
                    </div>
                    <input type="hidden" name="colors_raw" class="variant-hidden" value="{{ old('colors_raw') }}">
                </div>

                {{-- Sizes --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sizes
                        <span class="text-gray-400 font-normal ml-1 text-xs">e.g. S, M, L, XL &nbsp;·&nbsp; 38, 39, 40 &nbsp;·&nbsp; One Size</span>
                    </label>
                    <div class="variant-tag-box border border-gray-300 rounded-lg px-3 py-2 min-h-[46px] flex flex-wrap gap-2 items-center cursor-text focus-within:border-[#bb976d] transition-colors">
                        <span class="variant-placeholder text-sm text-gray-400 select-none pointer-events-none">Type a size and press Enter...</span>
                        <input type="text" class="variant-input flex-1 min-w-[140px] outline-none text-sm bg-transparent py-0.5" autocomplete="off">
                    </div>
                    <input type="hidden" name="sizes_raw" class="variant-hidden" value="{{ old('sizes_raw') }}">
                </div>

                {{-- Size Chart --}}
                <div class="border-t border-gray-100 pt-5 mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Size Chart Image
                        <span class="text-gray-400 font-normal ml-1 text-xs">optional — shown to customers as a popup</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-3">Upload a size guide image (JPG, PNG, WEBP). Max 8MB. Customers will see a "Size Guide" button on the product page.</p>
                    <input type="file" name="size_chart" accept="image/*" id="sizeChartInput"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#bb976d] transition-colors file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d]">
                    <img id="sizeChartPreview" src="#" alt="Size chart preview"
                         class="mt-3 max-w-xs rounded-lg border border-gray-200 hidden">
                </div>
            </div>

            {{-- ── Toggles ── --}}
            <div class="flex items-center gap-6 border-t border-gray-100 pt-5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1"
                           {{ old('is_featured') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 accent-[#bb976d]">
                    <span class="text-sm font-medium text-gray-700">Featured product</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-4 h-4 rounded border-gray-300 accent-[#bb976d]">
                    <span class="text-sm font-medium text-gray-700">Active (show in store)</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                    Create Product
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2.5 border border-gray-300 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
@include('admin.products._variant_script')
<script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY', 'no-api-key') }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '.tinymce-editor',
    plugins: 'lists link image table code wordcount',
    toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image | table | code',
    menubar: false,
    height: 300,
    skin: 'oxide',
    content_css: 'default',
    branding: false,
    promotion: false,
});
document.getElementById('imageInput').addEventListener('change', function () {
    const preview = document.getElementById('imagePreview');
    if (this.files && this.files[0]) {
        preview.src = URL.createObjectURL(this.files[0]);
        preview.classList.remove('hidden');
    }
});
document.getElementById('sizeChartInput').addEventListener('change', function () {
    const preview = document.getElementById('sizeChartPreview');
    if (this.files && this.files[0]) {
        preview.src = URL.createObjectURL(this.files[0]);
        preview.classList.remove('hidden');
    }
});
</script>
@endpush
@endsection
