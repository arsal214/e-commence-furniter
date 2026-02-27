<!-- resources/views/terms-and-conditions.blade.php -->
@extends('layouts.main')

@section('title', 'Terms-And-Conditions Page')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">Terms & Conditions</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">Terms & Conditions</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Condition Area Start -->
<div class="s-py-100">
    <div class="container">
        <div class="max-w-[940px] mx-auto" data-aos="fade-up">
            <article class="prose prose-h3:!text-3xl prose-h4:!text-2xl sm:prose-lg dark:prose-p:text-white-light dark:prose-li:text-white-light max-w-full prose-li:list-none prose-li:before:relative prose-li:before:content-[url('{{ asset('assets/img/icon/check.svg') }}')] prose-ol:!pl-0 sm:prose-ol:!pl-0 prose-ul:pl-0 sm:prose-ul:pl-0 prose-li:flex prose-li:items-start prose-li:gap-2">
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing or using the PeytonGhalib website and placing orders, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our services. We reserve the right to update these terms at any time, and continued use of our platform constitutes acceptance of any changes.</p>

                <h3>2. Products & Pricing</h3>
                <p>We sell a wide range of products across multiple categories including electronics, home & living, fashion, sports, and more. All prices are displayed in USD and are inclusive of applicable taxes unless stated otherwise. We reserve the right to change prices at any time without prior notice. In the event of a pricing error, we will notify you and give you the option to proceed at the correct price or cancel your order.</p>

                <h3>3. Ordering & Payment</h3>
                <p>By placing an order, you confirm that the information provided is accurate and that you are authorised to use the chosen payment method. We accept major credit cards, debit cards, and other online payment gateways. All transactions are secured with SSL encryption. Your order is confirmed only after successful payment verification.</p>
                <ul>
                    <li>Orders are subject to product availability at the time of purchase.</li>
                    <li>You will receive an order confirmation email after successful checkout.</li>
                    <li>We reserve the right to refuse or cancel any order at our discretion.</li>
                    <li>Payment must be completed before orders are dispatched.</li>
                </ul>

                <h3>4. Shipping & Delivery</h3>
                <p>We aim to dispatch all orders within 1–2 business days. Delivery times depend on your location and chosen shipping method. While we strive to meet estimated delivery times, we are not liable for delays caused by couriers, customs, or circumstances beyond our control. Risk of loss and title for products pass to you upon delivery.</p>

                <h3>5. Returns & Refunds</h3>
                <p>We offer a 30-day return policy for most items, provided they are unused, in original condition, and in original packaging. Certain product categories (e.g. perishables, digital downloads, personalised items) are non-returnable. Refunds are processed within 5–7 business days of receiving the returned item. We do not cover return shipping costs unless the item is faulty or incorrectly sent.</p>

                <h3>6. Order Cancellation</h3>
                <p>You may cancel an order before it has been dispatched. Once an order has shipped, our standard returns process applies. To request a cancellation, contact our support team immediately with your order number. We process cancellations promptly during business hours.</p>
                <ol>
                    <li>Contact support with your order number to request cancellation.</li>
                    <li>Cancellations are only accepted before the order is dispatched.</li>
                    <li>Approved cancellations are refunded within 3–5 business days.</li>
                </ol>

                <h3>7. Intellectual Property</h3>
                <p>All content on the PeytonGhalib website — including logos, product images, text, and design — is the property of PeytonGhalib or its licensors and is protected by copyright law. You may not reproduce, distribute, or use any content without prior written permission.</p>

                <h3>8. Limitation of Liability</h3>
                <p>PeytonGhalib shall not be liable for any indirect, incidental, or consequential damages arising from the use or inability to use our products or services. Our total liability in any matter shall not exceed the amount paid for the order in question. Nothing in these terms limits our liability for death, personal injury, or fraud.</p>
            </article>
        </div>
    </div>
</div>
<!-- Condition Area End -->

@include('includes.footer6')
  
@endsection