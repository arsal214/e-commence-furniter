<!-- resources/views/checkout-stripe.blade.php -->
@extends('layouts.main')

@section('title', 'Complete Payment')

@section('content')

@include('includes.navbar')

<!-- Banner Start -->
<div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70" style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
    <div class="text-center w-full">
        <h2 class="text-white md:text-[40px] font-normal leading-none text-center text-2xl">Complete Payment</h2>
    </div>
</div>
<!-- Banner End -->

<div class="s-py-100">
    <div class="container">
        <div class="max-w-[600px] mx-auto bg-[#FAFAFA] dark:bg-dark-secondary p-[30px] md:p-[50px] border border-[#17243026] rounded-xl">
            <h4 class="font-semibold text-xl md:text-2xl mb-2 dark:text-white">Order #{{ $order->id }}</h4>
            <p class="text-gray-500 mb-6">Total: <strong class="text-title dark:text-white">${{ number_format($order->total, 2) }}</strong></p>

            <div id="payment-element" class="mb-6"></div>
            <div id="payment-message" class="text-red-500 text-sm mb-4 hidden"></div>

            <button id="pay-btn" class="btn btn-theme-solid w-full" data-text="Pay Now">
                <span>Pay Now</span>
            </button>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ $stripeKey }}');
const elements = stripe.elements({ clientSecret: '{{ $clientSecret }}' });
const paymentElement = elements.create('payment');
paymentElement.mount('#payment-element');

document.getElementById('pay-btn').addEventListener('click', async () => {
    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    btn.querySelector('span').textContent = 'Processing...';

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: '{{ route("checkout.stripe-success") }}',
        },
    });

    if (error) {
        const msg = document.getElementById('payment-message');
        msg.textContent = error.message;
        msg.classList.remove('hidden');
        btn.disabled = false;
        btn.querySelector('span').textContent = 'Pay Now';
    }
});
</script>

@include('includes.footer6')

@endsection
