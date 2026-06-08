@extends('layouts.main')
@section('title', 'Refund Policy | PeytonGhalib')
@section('meta_description', 'Learn about PeytonGhalib\'s refund policy. Full refunds processed within 5–7 business days for eligible returns.')
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
        <h2>Our Refund Commitment</h2>
        <p>PeytonGhalib is committed to fair and transparent refunds. Once a return is approved and the item received, we process refunds promptly.</p>
        <h3>Refund Methods</h3>
        <ul>
            <li><strong>Credit/Debit Card:</strong> Refunded to your original card within 5–7 business days.</li>
            <li><strong>Bank Transfer:</strong> Processed within 7–10 business days.</li>
            <li><strong>Store Credit:</strong> Issued instantly if you prefer credit for a future purchase.</li>
        </ul>
        <h3>Non-Refundable Items</h3>
        <ul>
            <li>Custom or personalised products (unless defective).</li>
            <li>Items returned after the 30-day window without prior authorisation.</li>
            <li>Delivery charges (unless the error was on our part).</li>
        </ul>
        <h3>Partial Refunds</h3>
        <p>Items returned with signs of use, missing parts, or damaged packaging may be subject to a partial refund at our discretion.</p>
        <h3>Contact Us</h3>
        <p>For refund queries, email <a href="mailto:support@peytonghalib.com">support@peytonghalib.com</a> or call <a href="tel:+97141234567">+971 4 123 4567</a>.</p>
        <p class="text-sm text-gray-400">Last updated: June 2026</p>
    </div></div>
</section>
@include('includes.footer')
@endsection
