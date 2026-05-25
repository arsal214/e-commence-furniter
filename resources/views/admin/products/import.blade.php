@extends('admin.layouts.app')

@section('title', 'Import Products (CSV)')
@section('page-title', 'Import Products via CSV')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- ── Upload Card ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-9 h-9 rounded-lg bg-[#bb976d]/10 flex items-center justify-center">
                <i class="mdi mdi-file-upload text-[#bb976d] text-xl"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Bulk Update Products via CSV</h2>
                <p class="text-xs text-gray-400">Upload your spreadsheet to update product names, slugs, meta titles, meta descriptions, and descriptions all at once.</p>
            </div>
        </div>

        <div class="mt-5 p-4 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-700 space-y-1">
            <p class="font-medium">Required CSV columns (in any order):</p>
            <ul class="list-disc list-inside space-y-0.5 text-blue-600 text-xs mt-1">
                <li><strong>Original Supplier Title</strong> — must match the current product name in the database (used to find the product)</li>
                <li><strong>Optimized Product Title</strong> — the new clean product name</li>
                <li><strong>Meta Title</strong> — shown in browser tab &amp; Google (max 60 chars)</li>
                <li><strong>Meta Description</strong> — shown in Google search results (max 155 chars)</li>
                <li><strong>Product Description</strong> — the full product description</li>
                <li><strong>SEO Slug</strong> — the URL slug, e.g. <code>chunky-knit-throw-blanket-50x60</code></li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                @foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.import.store') }}" enctype="multipart/form-data" class="mt-5">
            @csrf

            <label class="block text-sm font-medium text-gray-700 mb-2">Choose CSV File</label>

            {{-- Drop zone --}}
            <label for="csv_file"
                   class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-[#bb976d] transition-colors group">
                <i class="mdi mdi-cloud-upload-outline text-4xl text-gray-300 group-hover:text-[#bb976d] transition-colors mb-2"></i>
                <p class="text-sm text-gray-500 group-hover:text-gray-700">Click to select or drag &amp; drop your CSV</p>
                <p id="fileName" class="text-xs text-gray-400 mt-1">No file chosen</p>
                <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" class="hidden">
            </label>

            <p class="text-xs text-gray-400 mt-2">Max file size: 5 MB. Save as CSV (comma-separated) from Excel or Google Sheets.</p>

            <div class="mt-5 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                    <i class="mdi mdi-upload"></i> Upload &amp; Update
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2.5 border border-gray-300 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- ── Results ── --}}
    @if (session('import_updated') || session('import_not_found') || session('import_skipped'))
    <div class="space-y-4">

        {{-- Updated --}}
        @if (session('import_updated'))
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <i class="mdi mdi-check-circle text-green-500 text-xl"></i>
                <h3 class="font-semibold text-gray-800 text-sm">
                    {{ count(session('import_updated')) }} product(s) updated successfully
                </h3>
            </div>
            <ul class="space-y-1">
                @foreach (session('import_updated') as $name)
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="mdi mdi-check text-green-400 text-base"></i>
                    {{ $name }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Not Found --}}
        @if (session('import_not_found'))
        <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <i class="mdi mdi-alert-circle text-amber-500 text-xl"></i>
                <h3 class="font-semibold text-gray-800 text-sm">
                    {{ count(session('import_not_found')) }} product(s) not found in database
                </h3>
            </div>
            <p class="text-xs text-gray-400 mb-3">These "Original Supplier Title" values did not match any product name in the database. Check for spelling differences.</p>
            <ul class="space-y-1">
                @foreach (session('import_not_found') as $name)
                <li class="flex items-start gap-2 text-sm text-amber-700 bg-amber-50 rounded-lg px-3 py-2">
                    <i class="mdi mdi-magnify-close text-base mt-0.5 flex-shrink-0"></i>
                    <span class="break-all">{{ $name }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Skipped / Warnings --}}
        @if (session('import_skipped'))
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <i class="mdi mdi-alert text-red-400 text-xl"></i>
                <h3 class="font-semibold text-gray-800 text-sm">
                    {{ count(session('import_skipped')) }} warning(s)
                </h3>
            </div>
            <ul class="space-y-1">
                @foreach (session('import_skipped') as $msg)
                <li class="text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2">{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                <i class="mdi mdi-package-variant"></i> View All Products
            </a>
            <a href="{{ route('admin.products.import') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <i class="mdi mdi-upload"></i> Import Another File
            </a>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
document.getElementById('csv_file').addEventListener('change', function () {
    document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : 'No file chosen';
});
</script>
@endpush
@endsection
