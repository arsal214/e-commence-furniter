@extends('seller.center.layout')
@section('title', $pageTitle ?? 'Seller Center')

@section('content')
<div style="display:flex;flex-direction:column;gap:18px">@include('seller.dc.' . $page)</div>
@endsection
