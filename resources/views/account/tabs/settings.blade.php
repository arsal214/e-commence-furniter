@php
  $card = 'border:1px solid #E5E7EB;border-radius:20px;padding:18px;display:flex;flex-direction:column;gap:12px';
  $label = 'display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600';
  $input = 'height:50px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0';
  $button = 'align-self:flex-start;height:46px;padding:0 22px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;cursor:pointer';
@endphp
@if($errors->any())<span role="alert" style="font-size:13px;font-weight:600;color:#B91C1C;background:#FEE2E2;border-radius:12px;padding:10px 12px">{{ $errors->first() }}</span>@endif

<form method="POST" action="{{ url('/my-account/settings') }}" data-once style="{{ $card }}">
  @csrf
  <b style="font-size:16px">Your details</b>
  <label style="{{ $label }}">Name<input name="name" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name" style="{{ $input }}"></label>
  <label style="{{ $label }}">Email<input type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email" style="{{ $input }}"></label>
  <button type="submit" style="{{ $button }}">Save details</button>
</form>

<form method="POST" action="{{ url('/my-account/password') }}" data-once style="{{ $card }}">
  @csrf
  <b style="font-size:16px">Change password</b>
  <label style="{{ $label }}">Current password<input type="password" name="current_password" required autocomplete="current-password" style="{{ $input }}"></label>
  <label style="{{ $label }}">New password<input type="password" name="password" required minlength="8" autocomplete="new-password" style="{{ $input }}"></label>
  <label style="{{ $label }}">Confirm new password<input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" style="{{ $input }}"></label>
  <button type="submit" style="{{ $button }}">Change password</button>
</form>

<form method="POST" action="{{ url('/my-account/notifications') }}" data-once style="{{ $card }}">
  @csrf
  <b style="font-size:16px">Emails</b>
  <span style="font-size:13px;color:#4B5563">Order and shipping emails are always sent. This controls deals and offers only.</span>
  <label style="display:flex;gap:10px;align-items:center;font-size:14px;font-weight:600"><input type="checkbox" name="marketing" value="1" style="width:20px;height:20px" @checked($user->acceptsMarketing())> Send me deals and offers</label>
  <button type="submit" style="{{ $button }}">Save choice</button>
</form>

<div style="display:flex;gap:10px;flex-wrap:wrap"><a href="/privacy-policy" style="height:42px;padding:0 16px;border-radius:999px;border:1.5px solid #D1D5DB;color:#111827;font-weight:700;font-size:13px;display:flex;align-items:center">Privacy choices</a></div>
