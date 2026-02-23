<!-- resources/views/team.blade.php -->
@extends('layouts.main')

@section('title', 'Team Page')

@section('content')

@include('includes.navbar')

    <!-- Banner Start -->
    <div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
        <div class="text-center w-full">
            <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Team Member</h2>
            <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>/</li>
                <li class="text-primary">Team</li>
            </ul>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Team Start -->
    <div class="py-16">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto grid lg:grid-cols-2 gap-[30px]">
                
                <!-- includes/Pages/teams.blade.php -->
                @include('includes.Pages.teams')

            </div>
        </div>
    </div>
    <!-- Team End -->

@include('includes.footer6')
  
@endsection