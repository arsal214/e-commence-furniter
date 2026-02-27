@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

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

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            {{-- ── Basic Info ── --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Basic Info</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors bg-white">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Price ($) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sale Price ($) <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Badge Tag</label>
                        <select name="tag"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors bg-white">
                            <option value="">None</option>
                            <option value="Sale"  {{ old('tag', $product->tag) === 'Sale'  ? 'selected' : '' }}>Hot Sale (green)</option>
                            <option value="NEW"   {{ old('tag', $product->tag) === 'NEW'   ? 'selected' : '' }}>NEW (purple)</option>
                            <option value="OFF"   {{ old('tag', $product->tag) === 'OFF'   ? 'selected' : '' }}>10% OFF (red)</option>
                            <option value="OFF1"  {{ old('tag', $product->tag) === 'OFF1'  ? 'selected' : '' }}>15% OFF (red)</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" class="tinymce-editor">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Review Content --}}
                    <div class="sm:col-span-2 border-t border-gray-100 pt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Reviews / Testimonials</label>
                        <p class="text-xs text-gray-400 mb-2">Shown in the "Reviews" tab on the product page.</p>
                        <textarea name="review_content" id="review_content" class="tinymce-editor">{{ old('review_content', $product->review_content) }}</textarea>
                    </div>

                    {{-- Shipping Info --}}
                    <div class="sm:col-span-2 border-t border-gray-100 pt-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipping Information</label>
                        <p class="text-xs text-gray-400 mb-2">Shown in the "Shipping" tab on the product page.</p>
                        <textarea name="shipping_info" id="shipping_info" class="tinymce-editor">{{ old('shipping_info', $product->shipping_info) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Image</label>
                        @if ($product->image)
                            <div class="mb-3">
                                @if (str_starts_with($product->image, 'assets/'))
                                    <img id="imagePreview" src="{{ asset($product->image) }}" class="w-28 h-28 object-cover rounded-lg border border-gray-200" alt="">
                                @else
                                    <img id="imagePreview" src="{{ Storage::url($product->image) }}" class="w-28 h-28 object-cover rounded-lg border border-gray-200" alt="">
                                @endif
                                <p class="text-xs text-gray-400 mt-1">Current image — upload below to replace</p>
                            </div>
                        @else
                            <img id="imagePreview" src="#" alt="Preview" class="mb-3 w-28 h-28 object-cover rounded-lg border border-gray-200 hidden">
                        @endif
                        <input type="file" name="image" accept="image/*" id="imageInput"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#bb976d] transition-colors file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d]">
                        <p class="text-xs text-gray-400 mt-1">Max 4MB. JPG, PNG, WEBP.</p>
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
                    <input type="hidden" name="colors_raw" class="variant-hidden"
                           value="{{ old('colors_raw', implode(',', $product->colors ?? [])) }}">
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
                    <input type="hidden" name="sizes_raw" class="variant-hidden"
                           value="{{ old('sizes_raw', implode(',', $product->sizes ?? [])) }}">
                </div>

                {{-- Size Chart --}}
                <div class="border-t border-gray-100 pt-5 mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Size Chart Image
                        <span class="text-gray-400 font-normal ml-1 text-xs">optional — shown to customers as a popup</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-3">Upload a size guide image (JPG, PNG, WEBP). Max 8MB. Customers will see a "Size Guide" button on the product page.</p>

                    @if($product->size_chart)
                    <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200 inline-block">
                        <img id="sizeChartPreview"
                             src="{{ Storage::url($product->size_chart) }}"
                             alt="Current size chart"
                             class="max-w-xs max-h-48 object-contain rounded">
                        <p class="text-xs text-gray-400 mt-1">Current size chart</p>
                        <label class="flex items-center gap-2 mt-2 cursor-pointer" id="removeChartLabel">
                            <input type="checkbox" name="remove_size_chart" value="1" id="removeChartCheck"
                                   class="w-4 h-4 rounded border-gray-300 accent-red-500">
                            <span class="text-xs text-red-500 font-medium">Remove this size chart</span>
                        </label>
                    </div>
                    @else
                    <img id="sizeChartPreview" src="#" alt="Size chart preview"
                         class="mb-3 max-w-xs rounded-lg border border-gray-200 hidden">
                    @endif

                    <div id="sizeChartUploadWrap">
                        <input type="file" name="size_chart" accept="image/*" id="sizeChartInput"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#bb976d] transition-colors file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d]">
                        <p class="text-xs text-gray-400 mt-1">Upload to replace. Max 8MB.</p>
                    </div>
                </div>
            </div>

            {{-- ── Toggles ── --}}
            <div class="flex items-center gap-6 border-t border-gray-100 pt-5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1"
                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 accent-[#bb976d]">
                    <span class="text-sm font-medium text-gray-700">Featured product</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 accent-[#bb976d]">
                    <span class="text-sm font-medium text-gray-700">Active (show in store)</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                    Update Product
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
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
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
    license_key: 'gpl',
});
document.getElementById('imageInput').addEventListener('change', function () {
    const preview = document.getElementById('imagePreview');
    if (this.files && this.files[0]) {
        preview.src = URL.createObjectURL(this.files[0]);
        preview.classList.remove('hidden');
    }
});

// Size chart — preview new upload
var scInput = document.getElementById('sizeChartInput');
if (scInput) {
    scInput.addEventListener('change', function () {
        const preview = document.getElementById('sizeChartPreview');
        if (this.files && this.files[0]) {
            preview.src = URL.createObjectURL(this.files[0]);
            preview.classList.remove('hidden');
        }
    });
}

// Size chart — hide upload field when "remove" is checked
var removeCheck = document.getElementById('removeChartCheck');
if (removeCheck) {
    removeCheck.addEventListener('change', function () {
        var wrap = document.getElementById('sizeChartUploadWrap');
        wrap.style.opacity = this.checked ? '0.4' : '1';
        wrap.style.pointerEvents = this.checked ? 'none' : '';
        if (this.checked && scInput) scInput.value = '';
    });
}
</script>
@endpush
@endsection
