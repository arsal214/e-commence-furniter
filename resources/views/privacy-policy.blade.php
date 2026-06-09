@extends('layouts.main')
@section('title', 'Privacy Policy | PeytonGhalib')
@section('meta_description', 'Read PeytonGhalib\'s privacy policy to understand how we collect, use, and protect your personal information.')

@push('schema')
@php
$schemaPage = ['@context'=>'https://schema.org','@type'=>'WebPage','name'=>'Privacy Policy — PeytonGhalib',
    'description'=>'Read PeytonGhalib\'s privacy policy to understand how we collect, use, and protect your personal information.',
    'url'=>url()->current(),
    'breadcrumb'=>['@type'=>'BreadcrumbList','itemListElement'=>[
        ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Privacy Policy','item'=>url()->current()],
    ]]];
@endphp
<script type="application/ld+json">{!! json_encode($schemaPage, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@include('includes.navbar')
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h1 class="text-white text-3xl md:text-[40px] font-bold leading-tight">Privacy Policy</h1>
        <ul class="flex items-center justify-center gap-[10px] text-base text-white mt-3">
            <li><a href="{{ url('/') }}">Home</a></li><li>/</li><li class="text-primary">Privacy Policy</li>
        </ul>
    </div>
</div>
<section class="s-py-100">
    <div class="container-fluid"><div class="max-w-3xl mx-auto prose dark:prose-invert prose-headings:text-title dark:prose-headings:text-white prose-p:text-paragraph dark:prose-p:text-white-light prose-li:text-paragraph dark:prose-li:text-white-light">
        <h2>Your Privacy Matters</h2>
        <p>PeytonGhalib is committed to protecting your personal information. This policy explains what data we collect, how we use it, and your rights.</p>
        <h3>Information We Collect</h3>
        <ul>
            <li><strong>Account data:</strong> Name, email, and password when you register.</li>
            <li><strong>Order data:</strong> Billing address, shipping address, and payment method.</li>
            <li><strong>Usage data:</strong> Pages visited, products viewed, and search queries (anonymised).</li>
            <li><strong>Communications:</strong> Emails and messages sent to our support team.</li>
        </ul>
        <h3>How We Use Your Data</h3>
        <ul>
            <li>To process and deliver your orders.</li>
            <li>To send order confirmations, shipping updates, and receipts.</li>
            <li>To provide customer support.</li>
            <li>To send marketing emails (with your consent — unsubscribe any time).</li>
            <li>To improve our website and product offerings.</li>
        </ul>
        <h3>Data Security</h3>
        <p>All transactions are protected by SSL encryption. We do not store your full card details. Payments are processed securely via Stripe, a PCI-DSS certified provider.</p>
        <h3>Your Rights</h3>
        <p>You have the right to access, correct, or delete your personal data at any time. Email <a href="mailto:support@peytonghalib.com">support@peytonghalib.com</a> to make a request.</p>
        <h3>Cookies</h3>
        <p>We use essential cookies for the shopping cart and authentication. Analytics cookies are used (anonymised) to improve site performance.</p>
        <h3>Contact</h3>
        <p>For privacy concerns, contact our Data Protection Officer at <a href="mailto:support@peytonghalib.com">support@peytonghalib.com</a>.</p>
        <p class="text-sm text-gray-400">Last updated: June 2026</p>
    </div></div>
</section>
@include('includes.footer')
@endsection
