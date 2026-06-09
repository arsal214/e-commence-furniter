@extends('layouts.main')
@section('title', 'Return Policy | PeytonGhalib')
@section('meta_description', 'PeytonGhalib offers a 30-day hassle-free return policy on all furniture and home decor items. Learn how to return a product easily.')

@push('schema')
@php
$schemaPage = ['@context'=>'https://schema.org','@type'=>'WebPage','name'=>'Return Policy — PeytonGhalib',
    'description'=>'PeytonGhalib offers a 30-day hassle-free return policy on all furniture and home decor items.',
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
        <h2>30-Day Return Policy</h2>
        <p>At PeytonGhalib, your satisfaction is our priority. If you are not completely happy with your purchase, you may return it within <strong>30 days</strong> of delivery for a full refund or exchange.</p>
        <h3>Eligibility</h3>
        <ul>
            <li>Items must be unused, undamaged, and in original packaging.</li>
            <li>Returns must be requested within 30 days of the delivery date.</li>
            <li>Proof of purchase (order number or receipt) is required.</li>
            <li>Custom-made or personalised items are non-returnable unless defective.</li>
        </ul>
        <h3>How to Return</h3>
        <ol>
            <li>Email <a href="mailto:support@peytonghalib.com">support@peytonghalib.com</a> with your order number and reason for return.</li>
            <li>Our team will respond within 1–2 business days with a return authorisation and instructions.</li>
            <li>Pack the item securely and attach the return label provided.</li>
            <li>Drop off at your nearest courier or schedule a collection.</li>
        </ol>
        <h3>Refund Timeline</h3>
        <p>Once we receive and inspect the returned item, your refund will be processed within <strong>5–7 business days</strong> to your original payment method.</p>
        <h3>Damaged or Defective Items</h3>
        <p>If your item arrives damaged or defective, please contact us within <strong>48 hours</strong> of delivery with photos. We will arrange a free replacement or full refund at no extra cost.</p>
        <p class="text-sm text-gray-400">Last updated: June 2026</p>
    </div></div>
</section>
@include('includes.footer')
@endsection
