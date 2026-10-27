@extends('layouts.pg')
@section('title', 'Create your account | PeytonGhalib')
@section('robots', 'noindex, follow')

@section('content')
@php $loginUp = true; $inBg = 'transparent'; $upBg = '#FFFFFF'; @endphp
<form method="POST" action="{{ route('register') }}" data-once>
  @csrf
  @include('market.dc.login')
</form>
@endsection
