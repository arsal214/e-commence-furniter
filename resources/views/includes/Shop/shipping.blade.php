@php
$shippings = [
    [
        'class' => '', 
        'title' => 'For Shipping', 
        'desc' => "Shipping times may vary based on your location and the selected delivery option. Please review our shipping policies for details on processing times, charges, and tracking updates. Contact us for any shipping-related inquiries or assistance.", 
    ],
    [
        'class' => 'mt-5 sm:mt-6', 
        'title' => 'Item Return', 
        'desc' => "We offer a hassle-free process to ensure your satisfaction. Please review our return policy for eligibility and steps to initiate a return. we offer a hassle-free process to ensure your satisfaction. Please review our return policy for eligibility and steps to initiate a return.", 
    ],
    [
        'class' => 'mt-5 sm:mt-6', 
        'title' => 'Accepted Problem Issue', 
        'desc' => "Choose from multiple methods, including credit cards, debit cards, and online payment gateways. All transactions are encrypted to ensure your information remains safe. For any payment-related concerns, our support team is here to assist.", 
    ]
];
@endphp

@foreach ($shippings as $item)
    <div class="{{ $item['class'] }}">
        <h4 class="text-xl sm:text-2xl leading-none font-medium">{{ $item['title'] }}</h4>
        <p class="sm:text-lg mt-3">{{ $item['desc'] }}</p>
    </div>
@endforeach