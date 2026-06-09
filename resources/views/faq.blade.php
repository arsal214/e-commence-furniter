<!-- resources/views/faq.blade.php -->
@extends('layouts.main')

@section('title', 'FAQs | PeytonGhalib')
@section('meta_description', 'Find answers to frequently asked questions about orders, shipping, returns, payments, and more at PeytonGhalib.')

@push('schema')
@php
$faqItems = [
    ['q'=>'How long does delivery take?','a'=>'Delivery times depend on your location and chosen shipping method. Standard delivery takes 3-7 business days, express delivery takes 1-2 business days, and next-day delivery is available for orders placed before 2 PM. You will receive a tracking link by email once your order has been dispatched.'],
    ['q'=>'Do you offer free shipping?','a'=>'Yes! We offer free standard shipping on all orders. Free shipping is automatically applied at checkout when your order qualifies.'],
    ['q'=>'Can I track my order?','a'=>'Absolutely. Once your order is dispatched, you will receive a confirmation email with a tracking number and a link to track your delivery in real time. You can also log into your account and view your order status from the "My Orders" section.'],
    ['q'=>'Do you deliver on weekends?','a'=>'Weekend delivery availability depends on your location and the courier service. Express and next-day orders placed on Fridays may be delivered on Saturday. Standard deliveries are typically processed on business days only (Monday-Friday).'],
    ['q'=>'What is your return policy?','a'=>'We offer a 30-day return policy on most items. Products must be unused, in their original condition, and returned in original packaging. To start a return, contact our support team with your order number. Once we receive and inspect the item, your refund will be processed within 5-7 business days.'],
    ['q'=>'I received a damaged or wrong item. What should I do?','a'=>'We apologise for the inconvenience. Please contact our support team within 48 hours of delivery with a photo of the item and your order number. We will arrange a replacement or full refund at no cost to you. Our team aims to resolve all such issues within 24-48 hours.'],
    ['q'=>'How long does a refund take?','a'=>'Refunds are typically processed within 5-7 business days after we receive your returned item. The time it takes to appear in your account may vary depending on your bank or payment provider. You will receive an email confirmation once your refund has been issued.'],
    ['q'=>'What payment methods do you accept?','a'=>'We accept a wide range of payment methods including credit/debit cards (Visa, Mastercard), and other secure online payment options for your convenience.'],
    ['q'=>'Can I cancel or modify my order?','a'=>'You can cancel or modify your order before it has been dispatched. Please contact our support team as soon as possible with your order number. Once an order has been shipped, cancellation is no longer possible, but you may initiate a return after delivery.'],
    ['q'=>'Is my payment information secure?','a'=>'Yes, absolutely. All payments are processed through secure, encrypted payment gateways. We do not store your card details on our servers. Our platform uses SSL (Secure Socket Layer) technology to protect your personal and financial information at all times.'],
    ['q'=>'Do I need an account to place an order?','a'=>'You can browse our store freely without an account. However, creating a free account lets you track your orders, save your wishlist, view your purchase history, and enjoy a faster checkout experience. We recommend registering for the best shopping experience.'],
    ['q'=>'What product categories do you sell?','a'=>'PeytonGhalib offers a wide variety of products across multiple categories including furniture, home decor, ceramics, lifestyle products and more. We are constantly expanding our catalogue to bring you the best products.'],
    ['q'=>'Are the products authentic and genuine?','a'=>'Yes. We source all our products from verified suppliers and trusted brands. Every item listed on PeytonGhalib is authentic and quality-checked before it reaches you. If you ever receive a product that does not match the description, we will make it right immediately.'],
    ['q'=>'What if an item I want is out of stock?','a'=>'If a product is out of stock, you can add it to your wishlist and we will notify you as soon as it becomes available again. Alternatively, contact our support team and we will do our best to help you find a suitable alternative or provide an estimated restock date.'],
];

$schemaFaq = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(fn($item) => [
        '@type'          => 'Question',
        'name'           => $item['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
    ], $faqItems),
];
@endphp
<script type="application/ld+json">{!! json_encode($schemaFaq, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">FAQs</h2>
        <ul class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/</li>
            <li class="text-primary">FAQS</li>
        </ul>
    </div>
</div>
<!-- Banner End -->

<!-- Faq Area Start -->
<div class="s-py-100">
    <div class="container-fluid max-w-[1720px]">
        <div class="faq-wrapper grid lg:grid-cols-2 gap-12 2xl:gap-20">
            
            <!-- includes/Pages/faqs.blade.php -->
            @include('includes.Pages.faqs')

        </div>
    </div>
</div>
<!-- Faq Area End -->
   
@include('includes.footer')
  
@endsection