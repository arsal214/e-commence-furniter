<!-- resources/views/blog-tag.blade.php -->
@extends('layouts.main')

@section('title', 'Blog-Tag Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Blog Tags</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li><a href="{{ url('/blog-v1') }}">Blog</a></li>
            <li>/</li>
            <li class="text-primary">Vase</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Tag Post Start -->
<div class="s-py-100">
    <div class="container-fluid">
        <div class="max-w-[1720px] mx-auto">
            <!-- Title -->
            <!-- <h3 class="font-medium leading-none text-2xl md:text-3xl mb-5 md:mb-6" data-aos="fade-up">Latest Posts</h3> -->
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-[30px]" data-aos="fade-up" data-aos-delay="100">
                
                <!-- includes/Blog/blogs-v1.blade.php -->
                @include('includes.Blog.blogs-v1')

            </div>
            <div class="text-center mt-7 md:mt-12">
                <a href="#" class="btn btn-outline" data-text="Load More">
                    <span>Load More</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Tag Post End -->

@include('includes.footer')
  
@endsection