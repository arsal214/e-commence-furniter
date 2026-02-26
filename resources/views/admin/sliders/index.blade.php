@extends('admin.layouts.app')

@section('title', 'Sliders')
@section('page-title', 'Homepage Sliders')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">Manage the homepage carousel slides. Drag to reorder via the sort field.</p>
    <a href="{{ route('admin.sliders.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
        <i class="mdi mdi-plus"></i> Add Slide
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600 w-10">#</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Image</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Button</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Order</th>
                <th class="text-center px-5 py-3 font-medium text-gray-600">Active</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($sliders as $slider)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 text-gray-500">{{ $slider->id }}</td>
                <td class="px-5 py-3">
                    @if ($slider->image)
                        <img src="{{ Storage::url($slider->image) }}" class="w-20 h-12 rounded object-cover bg-gray-100" alt="">
                    @else
                        <div class="w-20 h-12 rounded bg-gray-100 flex items-center justify-center">
                            <i class="mdi mdi-image-off text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $slider->title }}</p>
                    @if($slider->subtitle)
                        <p class="text-xs text-gray-400">{{ $slider->subtitle }}</p>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-600">
                    <span class="font-medium">{{ $slider->button_text }}</span>
                    <span class="text-gray-400 text-xs block">{{ $slider->button_url }}</span>
                </td>
                <td class="px-5 py-3 text-center text-gray-700">{{ $slider->sort_order }}</td>
                <td class="px-5 py-3 text-center">
                    @if ($slider->is_active)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right space-x-2">
                    <a href="{{ route('admin.sliders.edit', $slider) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#bb976d] border border-[#bb976d] rounded-lg hover:bg-[#bb976d]/10 transition-colors">
                        <i class="mdi mdi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="inline"
                          onsubmit="return confirm('Delete this slide?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                            <i class="mdi mdi-trash-can-outline"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                    No slides yet. <a href="{{ route('admin.sliders.create') }}" class="text-[#bb976d] hover:underline">Add your first slide</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
