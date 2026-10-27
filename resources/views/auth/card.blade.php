@extends('layouts.pg')
@section('title', $heading . ' | PeytonGhalib')
@section('robots', 'noindex, follow')

@section('content')
{{-- Same card as the design's sign-in page, for the password screens the design leaves out. --}}
<form method="POST" action="{{ $action }}" data-once style="max-width:460px;margin:0 auto;padding:48px 16px 0;display:flex;flex-direction:column;gap:18px">
  @csrf
  <h1 style="margin:0;font-size:30px;font-weight:800;letter-spacing:-.03em;text-align:center">{{ $heading }}</h1>
  @isset($intro)<p style="margin:0;font-size:14px;color:#4B5563;text-align:center;line-height:1.5">{{ $intro }}</p>@endisset
  @if($errors->any())<span role="alert" style="font-size:13px;font-weight:600;color:#B91C1C;background:#FEE2E2;border-radius:12px;padding:10px 12px">{{ $errors->first() }}</span>@endif
  @if(session('status'))<span role="status" style="font-size:13px;font-weight:600;color:#15803D;background:#ECFDF3;border-radius:12px;padding:10px 12px">{{ session('status') }}</span>@endif
  @yield('fields')
  <button type="submit" style="height:54px;border-radius:999px;border:0;background:#C2410C;color:#FFFFFF;font-weight:800;font-size:16px;cursor:pointer">{{ $button }}</button>
  <a href="{{ route('login') }}" style="text-align:center;font-size:14px;font-weight:600">Back to sign in</a>
</form>
@endsection
