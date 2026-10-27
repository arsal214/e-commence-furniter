@extends('layouts.pg')
@section('title', $title)
@section('meta_description', $description)
@if(! empty($robots))@section('robots', $robots)@endif

@section('content')
@include('market.dc.list')
@endsection
