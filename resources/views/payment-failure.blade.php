<!-- resources/views/payment-failure.blade.php -->
@extends('layouts.main')

@section('title', 'Payment-Failure Page')
@section('robots', 'noindex, nofollow')

@section('content')

@include('includes.navbar')

<div class="pg-state-wrap">
    <div class="pg-state">
        <span class="pg-state__code">PAYMENT DECLINED</span>
        <h1>Payment didn't go through</h1>
        <div class="pg-state__alert" role="alert">We tried to charge your card but something went wrong. No charge was made. Try another card or payment method.</div>
        <div class="pg-state__actions">
            <a class="pg-state__btn" href="{{ url('/payment-method') }}">Update payment method</a>
            <a class="pg-state__btn pg-state__btn--ghost" href="{{ url('/cart') }}">Back to bag</a>
        </div>
    </div>
</div>

@include('includes.footer')
  
@endsection