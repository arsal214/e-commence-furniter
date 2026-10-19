@extends('layouts.main')
@section('title', 'Shipping Policy: Free Toy Delivery Across the USA | PeytonGhalib')
@section('meta_description', 'Free standard shipping on every PeytonGhalib order to any U.S. address. See delivery times, tracking, and what to do if a toy arrives late or damaged.')

@push('schema')
@php
$schemaPage = ['@context'=>'https://schema.org','@type'=>'WebPage','name'=>'Shipping Policy — PeytonGhalib',
    'description'=>'Free standard shipping on every PeytonGhalib order to any U.S. address. See delivery times, tracking, and what to do if a toy arrives late or damaged.',
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
        <h2>Free Shipping on Every Toy Order</h2>
        <p>PeytonGhalib ships toys and games to addresses across the United States. Every order ships free, with no minimum spend, and we pack each one carefully so it arrives ready to play with.</p>
        <h3>Delivery Time</h3>
        {{-- Day counts come from config('checkout.delivery'), the same source
             the product-page estimate computes its dates from. Hardcoding them
             here is how the two drifted apart before. --}}
        <p>Standard delivery takes {{ \App\Support\DeliveryEstimate::minDays() }}–{{ \App\Support\DeliveryEstimate::maxDays() }} business days after your order ships. We process orders on business days (Monday through Friday), so an order placed on a weekend or holiday ships the next business day.</p>
        {{-- Express and Local Pickup are not offered: CheckoutController's
             $shippingOptions exposes 'free' only, so listing paid tiers here
             promised a choice the checkout never actually presents. --}}
        <h3>Where We Ship</h3>
        {{-- Written for a US-only store. config/checkout.php decides what
             checkout actually accepts, so keep the two in step. --}}
        <p>We deliver to U.S. addresses, including every state and the other U.S. destinations listed at checkout. We do not ship outside the United States at this time.</p>
        <h3>Gifts and Delivery Addresses</h3>
        <p>Sending a toy to a child, a friend, or a classroom? Enter the recipient's address as the shipping address at checkout.</p>
        <h3>Tracking Your Order</h3>
        <p>When your order ships, we email you a tracking number. You can follow it any time from your account or on our <a href="{{ url('/track-order') }}">Track Order</a> page.</p>
        <h3>Larger Toys</h3>
        <p>Bigger items, such as ride-ons and play sets, ship with extra protective packaging. If an order needs a delivery appointment, we will email you to arrange a convenient time.</p>
        <h3>Late, Lost, or Damaged Orders</h3>
        <p>If your order is late, goes missing, or arrives damaged, email <a href="mailto:info@peytonghalib.com">info@peytonghalib.com</a> with your order number within 48 hours of delivery. For damage, include a photo of the toy and its packaging. We will send a replacement or refund you in full.</p>
        <p class="text-sm text-gray-400">Last updated: October 2026</p>
    </div></div>
</section>
@include('includes.footer')
@endsection
