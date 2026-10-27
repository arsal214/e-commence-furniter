@extends('seller.center.layout')
@section('title', 'Products')

@section('content')
<div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
  <span style="font-size:14px;color:#4B5563">{{ $liveCount }} live · {{ $reviewCount }} in review · new listings are reviewed before going live</span>
  <div style="margin-left:auto;display:flex;gap:8px">
    <a href="{{ route('seller.products.create') }}" style="height:42px;padding:0 18px;border-radius:999px;background:#C2410C;color:#FFFFFF;font-weight:800;font-size:13px;display:flex;align-items:center">+ Add product</a>
  </div>
</div>

<div class="sc-tabs">
  @foreach([null => 'All', 'live' => 'Live', 'review' => 'In review', 'rejected' => 'Rejected', 'draft' => 'Hidden'] as $key => $label)
    <a href="{{ route('seller.products.index', $key ? ['status' => $key] : []) }}" class="{{ $status == $key ? 'on' : '' }}">{{ $label }}</a>
  @endforeach
</div>

<div style="background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;overflow:hidden">
  @forelse($products as $p)
    @php
      [$tag, $bg, $fg] = match (true) {
        in_array($p->moderation_status, ['pending', 'flagged']) => ['In review', '#FEF3C7', '#92400E'],
        $p->moderation_status === 'removed' => ['Rejected', '#FEE2E2', '#B91C1C'],
        ! $p->is_active => ['Hidden', '#F3F4F6', '#374151'],
        default => ['Live', '#ECFDF3', '#15803D'],
      };
      $stock = (int) ($p->variants_count ? ($p->color_stock ?: $p->size_stock) : $p->stock);
    @endphp
    <div style="display:flex;gap:14px;padding:14px 18px;border-bottom:1px solid #F3F4F6;align-items:center;flex-wrap:wrap">
      <a href="{{ route('seller.products.edit', $p) }}" class="sc-thumb" style="width:56px;height:56px;border-radius:12px;background:#F6EBDD;flex:none;overflow:hidden">@if($p->image)<img src="{{ \App\Models\Product::imageUrl($p->image) }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover">@endif</a>
      <span style="flex:1 1 220px;display:flex;flex-direction:column;gap:2px;min-width:0">
        <b style="font-size:14px">{{ $p->name }}</b>
        <span style="font-size:12px;color:#6B7280">{{ $p->sku ?: 'No SKU' }} · {{ $p->variants_count ? $p->variants_count . ' ' . \Illuminate\Support\Str::plural('option', $p->variants_count) : 'No options' }}</span>
        @if($p->moderation_status === 'removed' && $p->moderation_note)<span style="font-size:12px;color:#B91C1C;font-weight:600">{{ $p->moderation_note }}</span>@endif
      </span>
      <span style="flex:0 0 100px;font-size:14px;font-weight:700">${{ number_format($p->effective_price, 2) }}</span>
      <span style="flex:0 0 110px;font-size:13px;font-weight:600;color:{{ $stock <= 0 ? '#B91C1C' : ($stock <= 5 ? '#92400E' : '#374151') }}">{{ $stock <= 0 ? 'Out of stock' : $stock . ' in stock' }}</span>
      <span style="flex:0 0 90px;font-size:13px;color:#4B5563">{{ (int) $p->sold_qty }} sold</span>
      <span style="flex:0 0 auto;height:26px;padding:0 10px;border-radius:999px;background:{{ $bg }};color:{{ $fg }};font-size:12px;font-weight:800;display:flex;align-items:center">{{ $tag }}</span>
      <a href="{{ route('seller.products.edit', $p) }}" style="font-size:13px;font-weight:700">Edit</a>
    </div>
  @empty
    <p style="padding:20px;margin:0;color:#4B5563">No products here yet. <a href="{{ route('seller.products.create') }}" style="font-weight:700">Add your first product</a>.</p>
  @endforelse
</div>
{{ $products->links() }}
@endsection
