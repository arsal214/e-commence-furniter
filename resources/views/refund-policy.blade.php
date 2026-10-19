@extends('layouts.main')
@section('title', 'Refund Policy for Toy Orders | PeytonGhalib')
@section('meta_description', 'How PeytonGhalib refunds toy orders: refund methods, timelines, partial refunds for used or incomplete toys, and who to contact.')

@push('schema')
@php
$schemaPage = ['@context'=>'https://schema.org','@type'=>'WebPage','name'=>'Refund Policy — PeytonGhalib',
    'description'=>'How PeytonGhalib refunds toy orders: refund methods, timelines, partial refunds for used or incomplete toys, and who to contact.',
    'url'=>url()->current(),
    'breadcrumb'=>['@type'=>'BreadcrumbList','itemListElement'=>[
        ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Refund Policy','item'=>url()->current()],
    ]]];
@endphp
<script type="application/ld+json">{!! json_encode($schemaPage, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@include('includes.navbar')
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h1 class="text-white text-3xl md:text-[40px] font-bold leading-tight">Refund Policy</h1>
        <ul class="flex items-center justify-center gap-[10px] text-base text-white mt-3">
            <li><a href="{{ url('/') }}">Home</a></li><li>/</li><li class="text-primary">Refund Policy</li>
        </ul>
    </div>
</div>
<section class="s-py-100">
    <div class="container-fluid"><div class="max-w-3xl mx-auto prose dark:prose-invert prose-headings:text-title dark:prose-headings:text-white prose-p:text-paragraph dark:prose-p:text-white-light prose-li:text-paragraph dark:prose-li:text-white-light">
        <h2>How Refunds Work</h2>
        <p>Once we approve a return and receive the toy, we refund you promptly and confirm by email.</p>
        <h3>Refund Methods</h3>
        <ul>
            <li><strong>Credit or debit card:</strong> We refund your original card within 5–7 business days. Your bank may take a few more days to show it.</li>
            <li><strong>Bank transfer:</strong> We process these within 7–10 business days.</li>
            <li><strong>Store credit:</strong> We issue credit instantly if you would rather use it on a future order.</li>
        </ul>
        <h3>What We Cannot Refund</h3>
        <ul>
            <li>Custom or personalized products, unless they arrive defective.</li>
            <li>Items returned after the 30-day window without our prior approval.</li>
            <li>Shipping charges, unless the mistake was ours.</li>
        </ul>
        <h3>Partial Refunds</h3>
        <p>Toys returned with signs of use, missing pieces, or damaged packaging may receive a partial refund, at our discretion.</p>
        <h3>Questions About a Refund</h3>
        <p>Email <a href="mailto:info@peytonghalib.com">info@peytonghalib.com</a> or call <a href="tel:+19294699864">+1 (929) 469-9864</a> and include your order number.</p>
        <p class="text-sm text-gray-400">Last updated: October 2026</p>
    </div></div>
</section>
@include('includes.footer')
@endsection
