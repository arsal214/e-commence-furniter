@extends('auth.card', ['heading' => 'Reset your password', 'action' => route('password.email'), 'button' => 'Email me a reset link', 'intro' => 'Enter the email you signed up with and we will send you a link to choose a new password.'])
@section('fields')
<label style="display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600">Email<input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" style="height:50px;border-radius:12px;border:1px solid #D1D5DB;padding:0 14px;font-size:16px;outline:0"></label>
@endsection
