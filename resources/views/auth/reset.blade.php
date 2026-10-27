@extends('auth.card', ['heading' => 'Choose a new password', 'action' => route('password.update'), 'button' => 'Save new password'])
@section('fields')
<input type="hidden" name="token" value="{{ $token }}">
<label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Email<input type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email" style="height:50px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0"></label>
<label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">New password<input type="password" name="password" required minlength="8" autocomplete="new-password" style="height:50px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0"></label>
<label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Confirm new password<input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" style="height:50px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0"></label>
@endsection
