@extends('layouts.main')
@section('title', 'Shipping Policy | PeytonGhalib')
@section('meta_description', 'PeytonGhalib offers free delivery on all orders. Learn about our shipping methods, delivery times, and tracking options.')

@push('schema')
@php
$schemaPage = ['@context'=>'https://schema.org','@type'=>'WebPage','name'=>'Shipping Policy — PeytonGhalib',
    'description'=>'PeytonGhalib offers free delivery on all orders. Learn about our shipping methods, delivery times, and tracking options.',
    'url'=>url()->current(),
    'breadcrumb'=>['@type'=>'BreadcrumbList','itemListElement'=>[
        ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Shipping Policy','item'=>url()->current()],
    ]]];
@endphp
<script type="application/ld+json">{!! json_encode($schemaPage, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@include('includes.navbar')
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h1 class="text-white text-3xl md:text-[40px] font-bold leading-tight">Shipping Policy</h1>
        <ul class="flex items-center justify-center gap-[10px] text-base text-white mt-3">
            <li><a href="{{ url('/') }}">Home</a></li><li>/</li><li class="text-primary">Shipping Policy</li>
        </ul>
    </div>
</div>
<section class="s-py-100">
    <div class="container-fluid"><div class="max-w-3xl mx-auto prose dark:prose-invert prose-headings:text-title dark:prose-headings:text-white prose-p:text-paragraph dark:prose-p:text-white-light prose-li:text-paragraph dark:prose-li:text-white-light">
        <h2>Delivery Information</h2>
        <p>We ship all PeytonGhalib orders with care, ensuring your furniture and home decor arrives safely and on time.</p>
        <h3>Shipping Options</h3>
        <ul>
            <li><strong>Free Standard Shipping:</strong> Available on all orders over $99. Estimated 3–7 business days.</li>
            <li><strong>Express Shipping ($10):</strong> 1–3 business days. Available at checkout.</li>
            <li><strong>Local Pickup ($0):</strong> Collect from our warehouse. Scheduling confirmed by email.</li>
        </ul>
        <h3>Large Furniture Items</h3>
        <p>Sofas, dining sets, wardrobes, and other large items are delivered by our specialist two-person team. You will receive a call to schedule a delivery window. White-glove in-room placement is available on request.</p>
        <h3>Order Tracking</h3>
        <p>Once your order ships, you will receive a tracking number via email. You can also track your order at any time from <a href="{{ url('/track-order') }}">peytonghalib.com/track-order</a>.</p>
        <h3>Shipping Restrictions</h3>
        <p>We currently ship nationwide. International shipping is available for select items — contact us for a quote.</p>
        <h3>Delivery Issues</h3>
        <p>If your order is delayed or arrives damaged, contact <a href="mailto:support@peytonghalib.com">support@peytonghalib.com</a> within 48 hours. We will resolve the issue promptly.</p>
        <p class="text-sm text-gray-400">Last updated: June 2026</p>
    </div></div>
</section>
@include('includes.footer')
@endsection
