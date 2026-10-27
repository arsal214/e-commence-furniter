@extends('seller.onboarding.layout', ['step' => 1])
@section('title', 'Create your seller account')

@section('content')
<h1>Create your seller account</h1>
<p class="pg-muted">You'll verify your email, choose the plan, then add your business and bank details. No commission on your sales, just $19.99 a month.</p>

<form method="POST" action="{{ route('seller.signup.store') }}" data-once style="display:flex;flex-direction:column;gap:14px">
  @csrf
  <div class="pg-grid two">
    <x-pg.field label="First name" name="first_name" autocomplete="given-name" required />
    <x-pg.field label="Last name" name="last_name" autocomplete="family-name" required />
  </div>
  <x-pg.field label="Business email" name="email" type="email" autocomplete="email" required />
  <x-pg.field label="Mobile phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" hint="US numbers only" required />
  <x-pg.field label="Password" name="password" type="password" autocomplete="new-password" hint="At least 10 characters" required />

  <label style="display:flex;gap:10px;font-size:13px;line-height:1.5;align-items:flex-start">
    <input type="checkbox" name="agree" value="1" required style="margin-top:3px;width:18px;height:18px">
    <span>I agree to the Seller Agreement, the Prohibited Items policy and the Privacy Policy.</span>
  </label>
  @error('agree')<span class="pg-err">{{ $message }}</span>@enderror

  <button class="pg-btn" type="submit">Create account</button>
</form>
<p class="pg-muted">Already selling? <a href="{{ route('login') }}">Sign in</a></p>
@endsection
