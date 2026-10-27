@extends('admin.layouts.app')

@section('title', $product->name)
@section('page-title', 'Review listing')

@section('content')
<a href="{{ route('admin.moderation.index', ['tab' => $product->moderation_status]) }}" class="text-sm text-gray-500 hover:text-[#EA580C]">← Back to queue</a>

<div class="mt-4 grid gap-5 lg:grid-cols-3">
  <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5 space-y-4">
    <div>
      <h2 class="text-lg font-bold">{{ $product->name }}</h2>
      <p class="text-sm text-gray-500">{{ $product->seller->store_name }} · {{ $product->category?->parent?->name }} › {{ $product->category?->name }} · ${{ number_format($product->effective_price, 2) }} · {{ $product->stock }} in stock</p>
      <p class="text-sm font-semibold mt-1">Status: {{ ucfirst($product->moderation_status) }}</p>
      @if($product->moderation_note)<p class="text-sm text-red-600 font-semibold">{{ $product->moderation_note }}</p>@endif
    </div>
    <div class="flex flex-wrap gap-3">
      @foreach(collect([$product->image])->merge($product->productImages->pluck('image'))->filter() as $img)
        <img src="{{ \App\Models\Product::imageUrl($img) }}" alt="" class="w-32 h-32 object-cover rounded-lg border border-gray-100">
      @endforeach
    </div>
    <p class="text-sm whitespace-pre-line">{{ $product->description ?: 'No description.' }}</p>
    @if($product->variants->isNotEmpty())
      <table class="text-sm w-full"><thead><tr class="text-left text-xs text-gray-500"><th>Option</th><th>Price</th><th>Stock</th></tr></thead>
        @foreach($product->variants as $v)<tr class="border-t border-gray-50"><td class="py-1">{{ ucfirst($v->type) }}: {{ $v->value }}</td><td>${{ number_format($v->price, 2) }}</td><td>{{ $v->stock }}</td></tr>@endforeach
      </table>
    @endif
  </div>

  <form method="POST" action="{{ route('admin.moderation.decide', $product) }}" class="bg-white rounded-xl border border-gray-100 p-5 space-y-3 self-start">
    @csrf
    <input type="hidden" name="back" value="{{ $product->moderation_status }}">
    <h2 class="font-bold">Decision</h2>
    <label class="block text-sm font-semibold">Reason (sent to the seller when removing)
      <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border border-gray-300 p-2 text-sm font-normal">{{ old('note') }}</textarea>
    </label>
    @error('note')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    <button name="action" value="approve" class="w-full h-11 rounded-full bg-green-700 text-white font-bold">Approve</button>
    <button name="action" value="remove" class="w-full h-11 rounded-full border-2 border-red-700 text-red-700 font-bold">Remove</button>
  </form>
</div>
@endsection
