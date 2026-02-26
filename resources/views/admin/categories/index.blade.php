@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $categories->total() }} categories total</p>
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
        <i class="mdi mdi-plus"></i> Add Category
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Image</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Name</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Slug</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Products</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($categories as $category)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3">
                    @if ($category->image)
                        @if (str_starts_with($category->image, 'assets/'))
                            <img src="{{ asset($category->image) }}" class="w-10 h-10 rounded-lg object-cover" alt="">
                        @else
                            <img src="{{ Storage::url($category->image) }}" class="w-10 h-10 rounded-lg object-cover" alt="">
                        @endif
                    @else
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <i class="mdi mdi-image-off text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-5 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $category->products_count }}</td>
                <td class="px-5 py-3">
                    @if ($category->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            <i class="mdi mdi-check-circle text-xs"></i> Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Inactive
                        </span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#bb976d] border border-[#bb976d] rounded-lg hover:bg-[#bb976d]/10 transition-colors">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Delete this category?')">
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
                    No categories found. <a href="{{ route('admin.categories.create') }}" class="text-[#bb976d] hover:underline">Add one?</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($categories->hasPages())
    <div class="mt-4">{{ $categories->links() }}</div>
@endif
@endsection
