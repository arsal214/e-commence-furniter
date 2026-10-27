@extends('seller.center.layout')
@section('title', $product->exists ? 'Edit product' : 'Add product')

@section('content')
@php
  $exists = $product->exists;
  $rowsFor = function (string $type) use ($product) {
      $old = old($type === 'color' ? 'colors' : 'sizes');
      $rows = $old ?? $product->variants->where('type', $type)->map(fn ($v) => ['value' => $v->value, 'price' => $v->price, 'stock' => $v->stock, 'sku' => $v->sku])->values()->all();

      return array_pad($rows, max(3, count($rows) + 1), []);
  };
@endphp

<form method="POST" enctype="multipart/form-data" data-once class="sc-form"
      action="{{ $exists ? route('seller.products.update', $product) : route('seller.products.store') }}">
  @csrf
  @if($exists) @method('PUT') @endif

  @if($errors->any())<div class="pg-alert err" role="alert">Please fix the {{ $errors->count() }} highlighted {{ Str::plural('problem', $errors->count()) }} below.</div>@endif

  <div class="sc-edit">
  <div class="sc-edit__main">
  <section class="sc-box">
    <h2>Basics</h2>
    <x-pg.field label="Title" name="name" :value="$product->name" required />
    <label class="pg-field {{ $errors->has('category_id') ? 'has-err' : '' }}">Category
      <select name="category_id" required>
        <option value="">Choose a subcategory…</option>
        @foreach($categories as $parent)
          <optgroup label="{{ $parent->name }}">
            @foreach($parent->children as $sub)
              <option value="{{ $sub->id }}" @selected(old('category_id', $product->category_id) == $sub->id)>{{ $sub->name }}</option>
            @endforeach
          </optgroup>
        @endforeach
      </select>
      @error('category_id')<span class="err">{{ $message }}</span>@enderror
    </label>
    <label class="pg-field {{ $errors->has('description') ? 'has-err' : '' }}">Description
      <textarea name="description" rows="5" maxlength="5000">{{ old('description', $product->description) }}</textarea>
      @error('description')<span class="err">{{ $message }}</span>@enderror
    </label>
    <x-pg.field label="Brand (optional)" name="brand" :value="$product->brand" />
  </section>

  <section class="sc-box">
    <h2>Price and stock</h2>
    <div class="pg-grid two">
      <x-pg.field label="Price (USD)" name="price" type="number" step="0.01" min="0.5" inputmode="decimal" :value="$product->price" required />
      <x-pg.field label="Sale price (optional)" name="sale_price" type="number" step="0.01" min="0.5" inputmode="decimal" :value="$product->sale_price" />
      <x-pg.field label="Stock" name="stock" type="number" min="0" inputmode="numeric" :value="$product->stock ?? 0" hint="Used when the product has no colors or sizes" required />
      <x-pg.field label="SKU (optional)" name="sku" :value="$product->sku" />
    </div>
  </section>

  <section class="sc-box">
    <h2>Photos</h2>
    <x-pg.field label="Main photo" name="image" type="file" accept="image/jpeg,image/png,image/webp" :hint="\App\Rules\ImageSpec::hint('product')" />
    @if($exists && $product->image)<img class="sc-preview" src="{{ \App\Models\Product::imageUrl($product->image) }}" alt="">@endif
    <x-pg.field label="More photos (up to 8)" name="gallery[]" type="file" multiple accept="image/jpeg,image/png,image/webp" :hint="\App\Rules\ImageSpec::hint('product')" />
    @error('gallery.*')<span class="pg-err">{{ $message }}</span>@enderror
    @if($exists)
      <div class="sc-gallery">
        @foreach($product->productImages as $img)
          <label><img src="{{ \App\Models\Product::imageUrl($img->image) }}" alt=""><span><input type="checkbox" name="remove_images[]" value="{{ $img->id }}"> Remove</span></label>
        @endforeach
      </div>
    @endif
  </section>

  @foreach(['color' => ['colors', 'Colors', 'Cognac'], 'size' => ['sizes', 'Sizes', 'Large']] as $type => [$field, $title, $ph])
    <section class="sc-box" data-repeat>
      <h2>{{ $title }} <small class="pg-muted">· each with its own price, stock and photo</small></h2>
      @error($field)<span class="pg-err">{{ $message }}</span>@enderror
      <div class="sc-opts" data-rows>
        @foreach($rowsFor($type) as $i => $row)
          <div class="sc-opt">
            <input name="{{ $field }}[{{ $i }}][value]" value="{{ $row['value'] ?? '' }}" placeholder="{{ $ph }}" aria-label="{{ rtrim($title, 's') }} name">
            <input name="{{ $field }}[{{ $i }}][price]" value="{{ $row['price'] ?? '' }}" placeholder="Price" type="number" step="0.01" min="0.5" inputmode="decimal" aria-label="Price">
            <input name="{{ $field }}[{{ $i }}][stock]" value="{{ $row['stock'] ?? '' }}" placeholder="Stock" type="number" min="0" inputmode="numeric" aria-label="Stock">
            <input name="{{ $field }}[{{ $i }}][sku]" value="{{ $row['sku'] ?? '' }}" placeholder="SKU" aria-label="SKU">
            <input name="{{ $field }}[{{ $i }}][image]" type="file" accept="image/jpeg,image/png,image/webp" aria-label="Photo">
            @foreach(["{$field}.{$i}.price", "{$field}.{$i}.stock", "{$field}.{$i}.image", "{$field}.{$i}.value"] as $k)@error($k)<span class="pg-err">{{ $message }}</span>@enderror @endforeach
          </div>
        @endforeach
      </div>
      <button type="button" class="pg-btn ghost sm" data-add>+ Add another</button>
    </section>
  @endforeach

  </div>

  <aside class="sc-edit__side">
    <div class="sc-box">
      <b style="font-size:16px">Ready to submit?</b>
      @php
        $checks = [
          'Title (at least 3 characters)' => mb_strlen((string) old('name', $product->name)) >= 3,
          'Subcategory chosen' => (bool) old('category_id', $product->category_id),
          'Main photo added' => $exists && $product->image,
          'Price and stock set' => $exists && $product->price > 0,
        ];
      @endphp
      @foreach($checks as $label => $done)
        <span class="sc-check {{ $done ? 'done' : '' }}"><b>{{ $done ? '✓' : '○' }}</b>{{ $label }}</span>
      @endforeach
      <label style="display:flex;gap:10px;align-items:center;font-weight:600;font-size:14px">
        <input type="checkbox" name="is_active" value="1" style="width:18px;height:18px" @checked(old('is_active', $product->is_active))>
        Show to shoppers once approved
      </label>
      <div style="font-size:13px;color:#4B5563;line-height:1.5;background:#F9FAFB;border-radius:12px;padding:12px">New listings are reviewed by our team — usually within 24 hours. Price and stock changes go live at once.</div>
      <button class="pg-btn" type="submit">{{ $exists ? 'Save changes' : 'Submit for review' }}</button>
      <a href="{{ url('/prohibited-items') }}" style="font-size:13px;font-weight:700">Check the Prohibited Items policy</a>
    </div>
  </aside>
  </div>
</form>

@if($exists)
<form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')" style="margin-top:12px">
  @csrf @method('DELETE')
  <button class="pg-btn ghost" type="submit" style="color:var(--pg-bad);border-color:var(--pg-bad)">Delete product</button>
</form>
@endif

<script>
  // Adds one more blank row; the page also works without JavaScript using the spare rows already shown.
  document.querySelectorAll('[data-repeat]').forEach(function (box) {
    box.querySelector('[data-add]').addEventListener('click', function () {
      var rows = box.querySelector('[data-rows]'), last = rows.lastElementChild, n = rows.children.length;
      if (n >= {{ \App\Http\Controllers\Seller\ProductController::MAX_OPTIONS }}) return;
      var copy = last.cloneNode(true);
      copy.querySelectorAll('input').forEach(function (el) { el.name = el.name.replace(/\[\d+\]/, '[' + n + ']'); if (el.type !== 'file') el.value = ''; });
      copy.querySelectorAll('.pg-err').forEach(function (e) { e.remove(); });
      rows.appendChild(copy);
    });
  });
</script>
@endsection
