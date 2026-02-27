@php
$shippings = [
    [
        'class' => '',
        'title' => 'Shipping & Delivery',
        'desc' => "We offer standard and express delivery options across all product categories. Orders are typically processed within 1–2 business days. Delivery times vary by location — standard delivery takes 3–7 business days, while express delivery arrives within 1–2 business days. Free shipping is available on orders over a set threshold. Once shipped, you will receive a tracking number via email.",
    ],
    [
        'class' => 'mt-5 sm:mt-6',
        'title' => 'Returns & Exchanges',
        'desc' => "Not happy with your purchase? We offer a 30-day hassle-free return policy on most items. Products must be in their original, unused condition with all packaging intact. To initiate a return, contact our support team with your order number and reason for return. Refunds are processed within 5–7 business days after we receive the item.",
    ],
    [
        'class' => 'mt-5 sm:mt-6',
        'title' => 'Damaged or Incorrect Items',
        'desc' => "If you received a damaged, defective, or incorrect item, please contact us within 48 hours of delivery. Attach a photo of the item and your order details to your message. We will arrange a replacement or full refund at no extra cost to you. Your satisfaction is our top priority and we resolve all such issues promptly.",
    ]
];
@endphp

@foreach ($shippings as $item)
    <div class="{{ $item['class'] }}">
        <h4 class="text-xl sm:text-2xl leading-none font-medium">{{ $item['title'] }}</h4>
        <p class="sm:text-lg mt-3">{{ $item['desc'] }}</p>
    </div>
@endforeach