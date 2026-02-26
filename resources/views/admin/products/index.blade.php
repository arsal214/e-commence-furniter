@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $products->total() }} products total</p>
    <a href="{{ route('admin.products.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
        <i class="mdi mdi-plus"></i> Add Product
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Image</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Name</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Category</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Price</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Stock</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Tag</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($products as $product)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3">
                    @if ($product->image)
                        @if (str_starts_with($product->image, 'assets/'))
                            <img src="{{ asset($product->image) }}" class="w-12 h-12 rounded-lg object-cover" alt="">
                        @else
                            <img src="{{ Storage::url($product->image) }}" class="w-12 h-12 rounded-lg object-cover" alt="">
                        @endif
                    @else
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                            <i class="mdi mdi-image-off text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $product->name }}</p>
                    @if ($product->sku)<p class="text-xs text-gray-400">SKU: {{ $product->sku }}</p>@endif
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $product->category->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-800 font-medium">
                    ${{ number_format($product->price, 2) }}
                    @if ($product->sale_price)
                        <span class="text-xs text-gray-400 block">sale: ${{ number_format($product->sale_price, 2) }}</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <span class="{{ $product->stock > 0 ? 'text-gray-800' : 'text-red-500' }}">{{ $product->stock }}</span>
                </td>
                <td class="px-5 py-3">
                    @if ($product->tag)
                        @php
                            $tagColors = ['Sale' => 'bg-green-100 text-green-700', 'NEW' => 'bg-purple-100 text-purple-700', 'OFF' => 'bg-red-100 text-red-700', 'OFF1' => 'bg-orange-100 text-orange-700'];
                        @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $tagColors[$product->tag] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $product->tag }}
                        </span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    @if ($product->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            <i class="mdi mdi-check-circle text-xs"></i> Active
                        </span>
                    @else
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#bb976d] border border-[#bb976d] rounded-lg hover:bg-[#bb976d]/10 transition-colors">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-10 text-center text-gray-400">
                    No products found. <a href="{{ route('admin.products.create') }}" class="text-[#bb976d] hover:underline">Add one?</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($products->hasPages())
    <div class="mt-4">{{ $products->links() }}</div>
@endif
@endsection
