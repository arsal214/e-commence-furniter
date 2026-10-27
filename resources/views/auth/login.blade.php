@extends('layouts.pg')
@section('title', 'Sign in | PeytonGhalib')
@section('robots', 'noindex, follow')

@section('content')
@php $loginUp = false; $inBg = '#FFFFFF'; $upBg = 'transparent'; @endphp
<form method="POST" action="{{ route('login') }}" data-once>
  @csrf
  @include('market.dc.login')
</form>
@endsection
