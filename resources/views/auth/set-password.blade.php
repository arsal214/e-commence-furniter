@extends('auth.card', ['heading' => 'Set your password', 'action' => route('account.set-password.store'), 'button' => 'Save password', 'intro' => 'Your account was created when you checked out. Choose a password to sign in next time.'])
@section('fields')
<label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">New password<input type="password" name="password" required minlength="8" autocomplete="new-password" style="height:50px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0"></label>
<label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Confirm password<input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" style="height:50px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0"></label>
@endsection
