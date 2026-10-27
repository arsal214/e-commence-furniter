@extends('seller.center.layout')
@section('title', 'Storefront')

@section('content')
@php $card = 'background:#FFFFFF;border:1px solid #E5E7EB;border-radius:18px;padding:20px;display:flex;flex-direction:column;gap:12px'; @endphp

<form method="POST" action="{{ route('seller.storefront.save') }}" enctype="multipart/form-data" data-once style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-start">
  @csrf
  <div style="flex:1 1 420px;min-width:0;{{ $card }}">
    <b style="font-size:16px">Branding</b>
    <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap">
      <span style="width:88px;height:88px;border-radius:22px;background:#111827;color:#FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:32px;overflow:hidden;flex:none">
        @if($seller->logo)<img src="{{ \App\Models\Product::imageUrl($seller->logo) }}" alt="Your logo" style="width:100%;height:100%;object-fit:cover">@else{{ mb_strtoupper(mb_substr($seller->store_name, 0, 1)) }}@endif
      </span>
      <span style="flex:1 1 220px;font-size:13px;color:#4B5563;line-height:1.5">{{ \App\Rules\ImageSpec::hint('logo') }}. Shown on your store, products and messages.</span>
    </div>
    <x-pg.field label="Logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp" />
    @if($seller->logo)<label style="display:flex;gap:8px;align-items:center;font-size:14px"><input type="checkbox" name="remove_logo" value="1" style="width:18px;height:18px"> Remove my logo</label>@endif
    <label class="pg-field">Store name
      <input value="{{ $seller->store_name }}" disabled>
      <span class="pg-muted" style="font-size:12px">To change your store name, contact info@peytonghalib.com. It is tied to your verified business.</span>
    </label>
    <label class="pg-field {{ $errors->has('about') ? 'has-err' : '' }}">About your store
      <textarea name="about" rows="5" maxlength="1000" style="border-radius:12px;border:1px solid #D1D5DB;padding:12px 14px;font-size:16px;font-family:inherit;resize:vertical">{{ old('about', $seller->about) }}</textarea>
      @error('about')<span class="err">{{ $message }}</span>@enderror
    </label>
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
      <button type="submit" style="height:46px;padding:0 24px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer">Save storefront</button>
      @if($seller->isLive())<a href="{{ $seller->url() }}" style="font-weight:700;font-size:14px">Preview store</a>@endif
    </div>
  </div>
</form>
@endsection
