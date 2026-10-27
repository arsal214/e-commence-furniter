@extends('seller.onboarding.layout', ['step' => 1])
@section('title', 'Verify your email')

@section('content')
<h1>Verify your email</h1>
<p class="pg-muted">We sent a 6-digit code to <b>{{ $email }}</b>. It expires in 10 minutes.</p>

<form method="POST" action="{{ route('seller.verify.check') }}" data-once style="display:flex;flex-direction:column;gap:14px">
  @csrf
  <x-pg.field class="pg-code" label="Verification code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="••••••" required autofocus />
  <button class="pg-btn" type="submit">Verify email</button>
</form>

<form method="POST" action="{{ route('seller.verify.resend') }}" data-once>
  @csrf
  <button class="pg-btn ghost" type="submit">Send a new code</button>
</form>
@endsection
