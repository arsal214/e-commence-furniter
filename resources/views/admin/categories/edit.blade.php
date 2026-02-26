@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

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

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d] transition-colors resize-none">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Image</label>

                @if ($category->image)
                {{-- Current image card --}}
                <div id="current-image-card" class="mb-3 flex items-start gap-3 p-3 border border-gray-200 rounded-lg bg-gray-50">
                    @if (str_starts_with($category->image, 'assets/'))
                        <img src="{{ asset($category->image) }}" class="w-20 h-20 object-cover rounded-lg flex-none border border-gray-200" alt="">
                    @else
                        <img src="{{ Storage::url($category->image) }}" class="w-20 h-20 object-cover rounded-lg flex-none border border-gray-200" alt="">
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 mb-1">Current image</p>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" id="remove-image-cb"
                                   class="w-4 h-4 rounded border-gray-300 text-red-500"
                                   onchange="toggleRemove(this)">
                            <span class="text-xs text-red-500">Remove this image</span>
                        </label>
                    </div>
                </div>
                @endif

                {{-- Upload zone --}}
                <div id="upload-zone"
                     class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-[#bb976d] transition-colors {{ $category->image ? 'hidden' : '' }}"
                     id="upload-zone"
                     onclick="document.getElementById('image-input').click()">

                    <div id="upload-placeholder">
                        <svg class="mx-auto w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Click to upload or drag & drop</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 2MB</p>
                    </div>

                    <div id="upload-preview" class="hidden">
                        <img id="preview-img" src="" alt="Preview" class="mx-auto max-h-40 rounded-lg object-contain">
                        <p id="preview-name" class="text-xs text-gray-500 mt-2 truncate"></p>
                        <button type="button" onclick="event.stopPropagation(); clearPreview()"
                            class="mt-2 text-xs text-red-500 hover:text-red-700 underline">Remove</button>
                    </div>

                    <input type="file" id="image-input" name="image" accept="image/*" class="hidden">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ $category->is_active ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-[#bb976d]">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible in store)</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="px-5 py-2.5 border border-gray-300 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const input       = document.getElementById('image-input');
const zone        = document.getElementById('upload-zone');
const placeholder = document.getElementById('upload-placeholder');
const preview     = document.getElementById('upload-preview');
const previewImg  = document.getElementById('preview-img');
const previewName = document.getElementById('preview-name');

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewName.textContent = file.name;
        placeholder.classList.add('hidden');
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function clearPreview() {
    input.value = '';
    previewImg.src = '';
    placeholder.classList.remove('hidden');
    preview.classList.add('hidden');
    // If we had a current image card, restore it
    const card = document.getElementById('current-image-card');
    if (card) {
        card.classList.remove('hidden');
        zone.classList.add('hidden');
        const cb = document.getElementById('remove-image-cb');
        if (cb) cb.checked = false;
    }
}

function toggleRemove(cb) {
    // When "Remove image" is checked, show the upload zone so admin can optionally upload a new one
    if (cb.checked) {
        zone.classList.remove('hidden');
    } else {
        zone.classList.add('hidden');
        clearPreview();
    }
}

if (input) {
    input.addEventListener('change', () => { if (input.files[0]) showPreview(input.files[0]); });
}
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-[#bb976d]', 'bg-[#bb976d]/5'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('border-[#bb976d]', 'bg-[#bb976d]/5'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('border-[#bb976d]', 'bg-[#bb976d]/5');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showPreview(file);
        }
    });
}
</script>
@endpush
