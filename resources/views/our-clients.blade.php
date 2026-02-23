<!-- resources/views/our-clients.blade.php -->
@extends('layouts.main')

@section('title', 'Our-Clients Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap mt-5 md:mt-7 bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Our Clients</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Clients</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Clients Area Start -->
<div class="s-py-100">
    <div class="container">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-x-10 gap-y-10 md:gap-y-16">
            
            <!-- includes/Pages/clients.blade.php -->
            @include('includes.Pages.clients')

        </div>
    </div>
</div>
<!-- Clients Area End -->

@include('includes.footer')
  
@endsection