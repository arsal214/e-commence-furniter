@extends('admin.layouts.app')

@section('title', 'Bundles')
@section('page-title', 'Bundles')

@section('content')
@php $in = 'w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#bb976d]'; @endphp
<div class="max-w-4xl space-y-5">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm"><ul class="text-red-700 space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul></div>
    @endif

    <p class="text-sm text-gray-500">A set sells only while every item is active and in stock. Prices of the parts are read live; the set price below is what the customer pays.</p>

    @forelse($bundles as $bundle)
        <form method="POST" action="{{ route('admin.bundles.update', $bundle) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            @csrf @method('PUT')
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">{{ $bundle->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $bundle->items->map(fn ($i) => $i->product->name . ' ($' . number_format($i->unitPrice(), 2) . ')')->implode(' + ') }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        Regular total ${{ number_format($bundle->regularTotal(), 2) }}
                        · Saving ${{ number_format($bundle->savings(), 2) }}
                        · Sets available: {{ $bundle->availableQty() }}
                        @if($bundle->is_active && ! $bundle->isPurchasable()) · <span class="text-amber-600 font-semibold">not purchasable right now</span> @endif
                    </p>
                </div>
                <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 shrink-0">
                    <input type="checkbox" name="is_active" value="1" {{ $bundle->is_active ? 'checked' : '' }}> Active
                </label>
            </div>
            <div class="grid sm:grid-cols-3 gap-3">
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Set price ($)</label><input class="{{ $in }}" type="number" step="0.01" min="0.01" name="price" value="{{ $bundle->price }}" required></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Starts (optional)</label><input class="{{ $in }}" type="datetime-local" name="starts_at" value="{{ $bundle->starts_at?->format('Y-m-d\TH:i') }}"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Ends (optional)</label><input class="{{ $in }}" type="datetime-local" name="ends_at" value="{{ $bundle->ends_at?->format('Y-m-d\TH:i') }}"></div>
            </div>
            <button class="bg-[#bb976d] text-white font-semibold rounded-lg px-5 py-2 text-sm">Save</button>
        </form>
    @empty
        <div class="bg-white rounded-xl border border-gray-100 p-6 text-sm text-gray-500">No bundles yet. Run <code>php artisan db:seed --class=BundleSeeder</code> after your products are loaded.</div>
    @endforelse
</div>
@endsection
