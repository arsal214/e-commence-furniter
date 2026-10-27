@extends('seller.onboarding.layout', ['step' => 3])
@section('title', 'Business details')

@section('content')
<h1>Business details</h1>
<p class="pg-muted">Must match your IRS and state registration records.</p>

<form method="POST" action="{{ route('seller.business.save') }}" enctype="multipart/form-data" data-once style="display:flex;flex-direction:column;gap:14px">
  @csrf
  <x-pg.field label="Legal business name" name="business_name" :value="$seller->business_name" required />
  <x-pg.field label="Store name (shown to shoppers)" name="store_name" :value="$seller->store_name" required />
  <div class="pg-grid two">
    <label class="pg-field {{ $errors->has('business_type') ? 'has-err' : '' }}">Business type
      <select name="business_type" required>
        @foreach(['LLC', 'Corporation', 'Sole proprietorship', 'Partnership'] as $t)
          <option @selected(old('business_type', $seller->business_type) === $t)>{{ $t }}</option>
        @endforeach
      </select>
      @error('business_type')<span class="err">{{ $message }}</span>@enderror
    </label>
    <x-pg.field label="EIN" name="ein" :value="$seller->ein" hint="Format 12-3456789" inputmode="numeric" required />
    <label class="pg-field {{ $errors->has('state') ? 'has-err' : '' }}">State of registration
      <select name="state" required>
        <option value="">Select…</option>
        @foreach($states as $code => $label)
          <option value="{{ $code }}" @selected(old('state', $seller->state) === $code)>{{ $label }}</option>
        @endforeach
      </select>
      @error('state')<span class="err">{{ $message }}</span>@enderror
    </label>
    <x-pg.field label="Business phone" name="business_phone" type="tel" :value="$seller->business_phone" inputmode="tel" required />
  </div>
  <x-pg.field label="Registered business address" name="address" :value="$seller->address" autocomplete="address-line1" required />
  <div class="pg-grid two">
    <x-pg.field label="City" name="city" :value="$seller->city" autocomplete="address-level2" required />
    <x-pg.field label="ZIP code" name="zip" :value="$seller->zip" inputmode="numeric" autocomplete="postal-code" required />
  </div>
  <x-pg.field label="Business registration certificate" name="certificate" type="file" accept=".pdf,.jpg,.jpeg,.png"
     hint="Articles of organization, certificate of formation or state business license · PDF, JPG or PNG up to 10 MB{{ $seller->documents->where('type','registration')->count() ? ' · already uploaded, choose a file only to replace it' : '' }}" />
  <button class="pg-btn" type="submit">Continue</button>
</form>
@endsection
