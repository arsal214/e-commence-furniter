@extends('layouts.main')
@section('title', \App\Support\SiteCopy::title('return-policy'))
@section('meta_description', \App\Support\SiteCopy::description('return-policy'))

@push('schema')
@php
$schemaPage = ['@context'=>'https://schema.org','@type'=>'WebPage','name'=>'Return Policy — PeytonGhalib',
    'description'=>\App\Support\SiteCopy::description('return-policy'),
    'url'=>url()->current(),
    'breadcrumb'=>['@type'=>'BreadcrumbList','itemListElement'=>[
        ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Return Policy','item'=>url()->current()],
    ]]];
@endphp
<script type="application/ld+json">{!! json_encode($schemaPage, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@include('includes.navbar')
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h1 class="text-white text-3xl md:text-[40px] font-bold leading-tight">Return Policy</h1>
        <ul class="flex items-center justify-center gap-[10px] text-base text-white mt-3">
            <li><a href="{{ url('/') }}">Home</a></li><li>/</li><li class="text-primary">Return Policy</li>
        </ul>
    </div>
</div>
<section class="s-py-100">
    <div class="container-fluid"><div class="max-w-3xl mx-auto prose dark:prose-invert prose-headings:text-title dark:prose-headings:text-white prose-p:text-paragraph dark:prose-p:text-white-light prose-li:text-paragraph dark:prose-li:text-white-light">
        <h2>30-Day Returns</h2>
        <p>We want every order to land well. If it is not the right fit, you can return it within <strong>30 days</strong> of delivery for a full refund or an exchange.</p>
        <h3>What We Accept</h3>
        <ul>
            <li>Items that are unused and undamaged, in their original packaging.</li>
            <li>Sets and multi-piece products with every piece, card, and part included.</li>
            <li>Returns requested within 30 days of the delivery date.</li>
            <li>An order number or receipt as proof of purchase.</li>
        </ul>
        <p>We cannot accept custom-made or personalized items unless they arrive defective.</p>
        <h3>How to Start a Return</h3>
        <ol>
            <li>Email <a href="mailto:info@peytonghalib.com">info@peytonghalib.com</a> with your order number and the reason for the return.</li>
            <li>We reply within 1–2 business days with a return authorization and instructions.</li>
            <li>Pack the item securely in its original packaging and attach the return label we send you.</li>
            <li>Drop the parcel off at your nearest carrier location or schedule a pickup.</li>
        </ol>
        <h3>When You Get Your Refund</h3>
        <p>We inspect your return when it arrives and refund your original payment method within <strong>5–7 business days</strong>.</p>
        <h3>Damaged, Defective, or Wrong Items</h3>
        <p>If an item arrives damaged, defective, or not what you ordered, contact us within <strong>48 hours</strong> of delivery with your order number and a photo. We will send a free replacement or refund you in full.</p>
        <h3>Product Recalls</h3>
        <p>You can check current product recalls at <a href="https://www.cpsc.gov/Recalls" rel="noopener noreferrer" target="_blank">cpsc.gov/Recalls</a>. If you have a question about a product you bought from us, contact us and we will help.</p>
        <p class="text-sm text-gray-400">Last updated: October 2026</p>
    </div></div>
</section>
@include('includes.footer')
@endsection
