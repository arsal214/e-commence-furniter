@php
  $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:6px';
  $label = 'display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600';
  $input = 'height:48px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0;min-width:0';
  $states = config('checkout.countries.US.subdivisions', []);
@endphp
@if($errors->any())<span role="alert" style="font-size:13px;font-weight:600;color:#B91C1C;background:#FEE2E2;border-radius:12px;padding:10px 12px">{{ $errors->first() }}</span>@endif
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(260px,100%),1fr));gap:14px">
  @foreach($addresses as $a)
    <div style="{{ $card }}">
      <span style="font-size:11px;font-weight:800;letter-spacing:.1em;color:#6B7280">{{ $a->is_default ? 'DEFAULT · ' : '' }}{{ strtoupper($a->label) }}</span>
      <b>{{ $a->name }}</b>
      <span style="font-size:14px;color:#374151;line-height:1.5">{{ $a->address }}{{ $a->address2 ? ', ' . $a->address2 : '' }}<br>{{ $a->city }}, {{ $a->state }} {{ $a->zip }}</span>
      @if($a->phone)<span style="font-size:13px;color:#4B5563">{{ $a->phone }}</span>@endif
      <div style="display:flex;gap:12px;margin-top:6px">
        @unless($a->is_default)<form method="POST" action="{{ url('/my-account/addresses/' . $a->id . '/default') }}">@csrf<button type="submit" style="border:0;background:none;padding:0;font-weight:700;font-size:13px;cursor:pointer;color:#C2410C">Make default</button></form>@endunless
        <form method="POST" action="{{ url('/my-account/addresses/' . $a->id . '/delete') }}">@csrf<button type="submit" style="border:0;background:none;padding:0;font-weight:700;font-size:13px;cursor:pointer;color:#B91C1C">Remove</button></form>
      </div>
    </div>
  @endforeach
</div>

<form method="POST" action="{{ url('/my-account/addresses') }}" data-once style="border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:12px">
  @csrf
  <b style="font-size:16px">Add a US address</b>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
    <label style="{{ $label }}">Label<input name="label" value="{{ old('label', $addresses->isEmpty() ? 'Home' : '') }}" required maxlength="30" placeholder="Home, Work…" style="{{ $input }}"></label>
    <label style="{{ $label }}">Full name<input name="name" value="{{ old('name', $user->name) }}" required maxlength="120" autocomplete="name" style="{{ $input }}"></label>
    <label style="{{ $label }}">Mobile phone (optional)<input name="phone" value="{{ old('phone') }}" maxlength="30" autocomplete="tel" style="{{ $input }}"></label>
    <label style="{{ $label }}">Street address<input name="address" value="{{ old('address') }}" required maxlength="160" autocomplete="address-line1" style="{{ $input }}"></label>
    <label style="{{ $label }}">Apt, suite (optional)<input name="address2" value="{{ old('address2') }}" maxlength="80" autocomplete="address-line2" style="{{ $input }}"></label>
    <label style="{{ $label }}">City<input name="city" value="{{ old('city') }}" required maxlength="80" autocomplete="address-level2" style="{{ $input }}"></label>
    <label style="{{ $label }}">State<select name="state" required style="{{ $input }};background:#fff"><option value="">Choose…</option>@foreach($states as $code => $name)<option value="{{ $code }}" @selected(old('state') === $code)>{{ $name }}</option>@endforeach</select></label>
    <label style="{{ $label }}">ZIP code<input name="zip" value="{{ old('zip') }}" required maxlength="10" inputmode="numeric" autocomplete="postal-code" style="{{ $input }}"></label>
  </div>
  <label style="display:flex;gap:8px;align-items:center;font-size:14px"><input type="checkbox" name="is_default" value="1" @checked($addresses->isEmpty() || old('is_default')) style="width:18px;height:18px;accent-color:#C2410C"> Use as my default address at checkout</label>
  <button type="submit" style="align-self:flex-start;height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#fff;font-weight:800;cursor:pointer">Save address</button>
</form>
<span style="font-size:13px;color:#4B5563">Card details are entered on the secure payment step and handled by Stripe. Peyton Ghalib never stores your card number.</span>
