<!-- resources/views/error.blade.php -->
@extends('layouts.main')

@section('title', 'Error Page')

@section('content')

@include('includes.navbar')

<div class="pg-state-wrap">
    <div class="pg-state">
        <span class="pg-state__code">404</span>
        <h1>This page has moved on</h1>
        <p>The product may have sold out or the link is out of date.</p>
        <form action="{{ url('/shop') }}" method="GET" class="pg-hdr-search" style="width:100%;max-width:300px" role="search">
            <input type="search" name="search" placeholder="Search products" aria-label="Search products">
            <button type="submit">Go</button>
        </form>
        <div class="pg-state__actions">
            <a class="pg-state__btn" href="{{ url('/shop') }}">Start shopping</a>
            <a class="pg-state__btn pg-state__btn--ghost" href="{{ url('/') }}">Back to home</a>
        </div>
    </div>
</div>

@include('includes.footer')
  
@endsection