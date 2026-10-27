@extends('seller.onboarding.layout', ['step' => 4])
@section('title', 'Business owner details')

@section('content')
@php $has = fn ($t) => $seller->documents->where('type', $t)->count() ? ' · already uploaded' : ''; @endphp
<h1>Business owner details</h1>
<p class="pg-muted">Required by US law to verify who controls the business. Kept private and encrypted.</p>

<form method="POST" action="{{ route('seller.owner.save') }}" enctype="multipart/form-data" data-once style="display:flex;flex-direction:column;gap:14px">
  @csrf
  <div class="pg-grid two">
    <x-pg.field label="Full legal name" name="owner_name" :value="$seller->owner_name" autocomplete="name" required />
    <x-pg.field label="Title" name="owner_title" :value="$seller->owner_title" placeholder="Owner / Managing Member" required />
    <x-pg.field label="Email" name="owner_email" type="email" :value="$seller->owner_email ?? auth()->user()->email" required />
    <x-pg.field label="Phone" name="owner_phone" type="tel" :value="$seller->owner_phone" inputmode="tel" required />
    <x-pg.field label="Date of birth" name="owner_dob" type="date" :value="null" autocomplete="bday" required />
    <x-pg.field label="SSN (last 4)" name="owner_ssn_last4" inputmode="numeric" maxlength="4" autocomplete="off" required />
  </div>
  <x-pg.field label="Home address" name="owner_address" :value="$seller->owner_address" autocomplete="street-address" required />
  <x-pg.field label="Government photo ID, front" name="id_front" type="file" accept=".pdf,.jpg,.jpeg,.png" hint="Driver's license or passport · up to 10 MB{{ $has('id_front') }}" />
  <x-pg.field label="Government photo ID, back" name="id_back" type="file" accept=".pdf,.jpg,.jpeg,.png" hint="Up to 10 MB{{ $has('id_back') }}" />
  <x-pg.field label="EIN letter (IRS CP 575)" name="ein_letter" type="file" accept=".pdf,.jpg,.jpeg,.png" hint="Up to 10 MB{{ $has('ein_letter') }}" />
  <button class="pg-btn" type="submit">Continue</button>
</form>
@endsection
