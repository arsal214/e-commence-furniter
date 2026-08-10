@extends('admin.layouts.app')

@section('title', 'Order #' . $order->id)
@section('page-title', 'Order #' . $order->id)

@section('content')
@php
$statusColors = [
    'pending'    => 'bg-yellow-100 text-yellow-700',
    'processing' => 'bg-blue-100 text-blue-700',
    'shipped'    => 'bg-indigo-100 text-indigo-700',
    'delivered'  => 'bg-green-100 text-green-700',
    'cancelled'  => 'bg-red-100 text-red-700',
];
$paymentColors = [
    'pending' => 'bg-orange-100 text-orange-700',
    'paid'    => 'bg-green-100 text-green-700',
];
@endphp

<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-[#bb976d] flex items-center gap-1">
        <i class="mdi mdi-arrow-left"></i> Back to Orders
    </a>
</div>

@if($order->isStripeTestOrder())
<div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
    <i class="mdi mdi-flask-outline text-red-500 text-lg leading-none mt-0.5"></i>
    <div class="text-sm">
        <p class="font-semibold text-red-700">Sandbox test order — no real payment was taken.</p>
        <p class="text-red-600/80 text-xs mt-0.5">Placed against Stripe test keys. Do not fulfil this order or place it with a supplier.</p>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Order items + summary --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Items table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Order Items</h2>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[560px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Product</th>
                        <th class="text-center px-5 py-3 font-medium text-gray-600">Qty</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Unit Price</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($order->items as $item)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if ($item->product?->image)
                                    @if (str_starts_with($item->product->image, 'assets/'))
                                        <img src="{{ asset($item->product->image) }}" class="w-10 h-10 rounded object-cover bg-gray-100" alt="">
                                    @else
                                        <img src="{{ Storage::url($item->product->image) }}" class="w-10 h-10 rounded object-cover bg-gray-100" alt="">
                                    @endif
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center">
                                        <i class="mdi mdi-image-off text-gray-400 text-sm"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->name }}</p>
                                    @if($item->color || $item->size || $item->sku)
                                        <p class="text-xs text-gray-500">
                                            @if($item->color)<span>Colour: <span class="font-medium text-gray-700">{{ $item->color }}</span></span>@endif
                                            @if($item->size)<span class="ml-2">Size: <span class="font-medium text-gray-700">{{ $item->size }}</span></span>@endif
                                            @if($item->sku)<span class="ml-2">SKU: <span class="font-medium text-gray-700">{{ $item->sku }}</span></span>@endif
                                        </p>
                                    @endif
                                    @if($item->product)
                                        <a href="{{ route('admin.products.edit', $item->product) }}" class="text-xs text-[#bb976d] hover:underline">Edit product</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center text-gray-700">{{ $item->qty }}</td>
                        <td class="px-5 py-3 text-right text-gray-700">${{ number_format($item->price, 2) }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-800">${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping ({{ ucfirst($order->shipping) }})</span>
                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t border-gray-100">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Customer & Shipping info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Customer & Shipping</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Name</p>
                    <p class="font-medium text-gray-800">{{ $order->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Email</p>
                    <p class="text-gray-800">{{ $order->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Phone</p>
                    <p class="text-gray-800">{{ $order->phone }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Country</p>
                    <p class="text-gray-800">{{ $order->country_label ?? '—' }}</p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Shipping Address</p>
                    {{-- Label layout, ready to paste straight into the supplier's checkout. --}}
                    <p class="text-gray-800 whitespace-pre-line leading-relaxed">{{ $order->formatted_address ?: '—' }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Street</p>
                    <p class="text-gray-800">{{ $order->address ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Apt / Suite</p>
                    <p class="text-gray-800">{{ $order->address2 ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">City</p>
                    <p class="text-gray-800">{{ $order->city ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">State / Province</p>
                    <p class="text-gray-800">
                        {{ $order->state_label ?? '—' }}
                        @if($order->state && $order->state_label !== $order->state)
                            <span class="text-xs text-gray-400">({{ $order->state }})</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">ZIP / Postal Code</p>
                    <p class="text-gray-800">{{ $order->zip ?: '—' }}</p>
                </div>
                @if($order->notes)
                <div class="sm:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Notes</p>
                    <p class="text-gray-700 italic">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Proof of delivery (internal only) --}}
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-5">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-base font-semibold text-gray-800">Proof of Delivery</h2>
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Internal Only</span>
            </div>
            <p class="text-xs text-gray-400 mb-4">
                Courier POD photos, signed receipts or dispatch slips kept as a record against this order.
                Files are stored privately — they are never shown to the customer or linked in any email.
            </p>

            @if($order->deliveryProofs->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
                @foreach($order->deliveryProofs as $proof)
                <div class="border border-gray-100 rounded-lg overflow-hidden">
                    <a href="{{ route('admin.orders.delivery-proofs.show', [$order, $proof]) }}" target="_blank" rel="noopener"
                       class="bg-gray-50 aspect-[4/3] flex items-center justify-center overflow-hidden"
                       title="Open {{ $proof->display_name }}">
                        @if($proof->isImage())
                            <img src="{{ route('admin.orders.delivery-proofs.show', [$order, $proof]) }}"
                                 alt="Proof of delivery: {{ $proof->display_name }}" class="w-full h-full object-cover">
                        @else
                            <i class="mdi {{ $proof->isPdf() ? 'mdi-file-pdf-box text-red-500' : 'mdi-file-document-outline text-gray-400' }} text-4xl"></i>
                        @endif
                    </a>
                    <div class="p-2.5">
                        <p class="text-xs font-medium text-gray-700 truncate" title="{{ $proof->display_name }}">{{ $proof->display_name }}</p>
                        <p class="text-[11px] text-gray-400">
                            {{ $proof->human_size }} · {{ $proof->created_at->format('d M Y') }}
                            @if($proof->uploader) · {{ $proof->uploader->name }}@endif
                        </p>
                        @if($proof->note)
                            <p class="text-[11px] text-gray-600 mt-1 italic break-words">{{ $proof->note }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2">
                            <a href="{{ route('admin.orders.delivery-proofs.show', [$order, $proof]) }}" download="{{ $proof->display_name }}"
                               class="text-[11px] text-[#bb976d] hover:underline">Download</a>
                            <form action="{{ route('admin.orders.delivery-proofs.destroy', [$order, $proof]) }}" method="POST"
                                  onsubmit="return confirm('Delete this proof of delivery? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[11px] text-red-500 hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 mb-5">No proof of delivery uploaded for this order yet.</p>
            @endif

            <form action="{{ route('admin.orders.delivery-proofs.store', $order) }}" method="POST" enctype="multipart/form-data"
                  class="space-y-3 pt-4 border-t border-gray-100">
                @csrf
                <div>
                    <label for="proof-files" class="block text-xs font-medium text-gray-600 mb-1">Upload files</label>
                    <input type="file" name="files[]" id="proof-files" multiple
                           accept="image/jpeg,image/png,image/webp,image/gif,application/pdf"
                           class="w-full text-sm text-gray-600 border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]
                                  file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium
                                  file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP, GIF or PDF. Up to 10 files, 8 MB each.</p>
                    @error('files')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    @error('files.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="proof-note" class="block text-xs font-medium text-gray-600 mb-1">
                        Note <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <input type="text" name="note" id="proof-note" maxlength="1000" value="{{ old('note') }}"
                           placeholder="e.g. Left with neighbour at no. 12 — signed by J. Ahmed"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                    @error('note')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                    <i class="mdi mdi-cloud-upload-outline"></i> Upload Proof
                </button>
            </form>
        </div>
    </div>

    {{-- Right: Status management --}}
    <div class="space-y-6">

        {{-- Current status card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Order Status</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Order Status</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Payment</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Method</span>
                    <span class="font-medium text-gray-700">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Stripe' }}
                        @if($order->stripe_mode === 'test')
                            <span class="ml-1 inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 align-middle">TEST</span>
                        @elseif($order->stripe_mode === 'live')
                            <span class="ml-1 inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 align-middle">LIVE</span>
                        @elseif($order->payment_method === 'stripe')
                            <span class="ml-1 inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 align-middle">Mode unknown</span>
                        @endif
                    </span>
                </div>
                @if($order->stripe_payment_intent)
                <div class="flex justify-between items-center gap-2">
                    <span class="text-gray-500 shrink-0">Payment Intent</span>
                    {{-- Deep link into the matching Stripe dashboard; /test/ is a different account view. --}}
                    <a href="https://dashboard.stripe.com/{{ $order->stripe_mode === 'test' ? 'test/' : '' }}payments/{{ $order->stripe_payment_intent }}"
                       target="_blank" rel="noopener"
                       class="text-xs text-[#bb976d] hover:underline truncate" title="{{ $order->stripe_payment_intent }}">
                        {{ $order->stripe_payment_intent }}
                    </a>
                </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Placed</span>
                    <span class="text-gray-700">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Update order status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Update Status</h2>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Order Status</label>
                    <select name="status"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Payment Status</label>
                    <select name="payment_status"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <button type="submit"
                        class="w-full px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors">
                    Save Changes
                </button>
            </form>
        </div>

        {{-- Chase an unpaid order --}}
        @if($order->awaitingPayment())
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-5">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-base font-semibold text-gray-800">Request Payment</h2>
                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-medium">Unpaid</span>
            </div>
            <p class="text-xs text-gray-400 mb-4">
                Emails the customer a secure card-payment link for the full ${{ number_format($order->total, 2) }}.
                Paying marks the order <strong>Paid</strong> and moves it to <strong>Processing</strong> automatically.
            </p>

            @if($order->payment_requested_at)
            <div class="mb-4 flex items-start gap-2 rounded-lg bg-orange-50 border border-orange-100 px-3 py-2 text-xs text-orange-800">
                <i class="mdi mdi-email-clock-outline mt-0.5"></i>
                <span>
                    Last requested {{ $order->payment_requested_at->format('d M Y, H:i') }}
                    ({{ $order->payment_request_count }} {{ $order->payment_request_count === 1 ? 'time' : 'times' }} in total).
                </span>
            </div>
            @endif

            <form action="{{ route('admin.orders.request-payment', $order) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label for="payment-message" class="block text-xs font-medium text-gray-600 mb-1">
                        Note to customer <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea name="message" id="payment-message" rows="3" maxlength="1000"
                              placeholder="e.g. Your card was declined at checkout — please try again using the link below."
                              class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">{{ old('message') }}</textarea>
                    @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-400 mt-1">Shown in the email above the payment button.</p>
                </div>
                <button type="submit"
                        class="w-full px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="mdi mdi-email-fast-outline"></i>
                    {{ $order->payment_requested_at ? 'Send Reminder Again' : 'Email Payment Request' }}
                </button>
            </form>

            @if($order->payment_token)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <label for="pay-link" class="block text-xs font-medium text-gray-600 mb-1">Payment link</label>
                <div class="flex gap-2">
                    {{-- Same link the email carries, for sending over WhatsApp or reading out on a call. --}}
                    <input id="pay-link" type="text" readonly value="{{ $order->pay_url }}"
                           class="flex-1 min-w-0 text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-600">
                    <button type="button" data-copy="#pay-link"
                            class="shrink-0 px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 hover:border-[#bb976d] hover:text-[#bb976d] transition-colors">
                        <i class="mdi mdi-content-copy"></i> Copy
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Anyone with this link can pay this order. Stays valid until the order is paid or cancelled.</p>
            </div>
            @endif
        </div>
        @elseif($order->payment_requested_at)
        <div class="bg-white rounded-xl shadow-sm border border-green-200 p-5">
            <div class="flex items-start gap-2">
                <i class="mdi mdi-check-decagram text-green-600 text-lg leading-none mt-0.5"></i>
                <div class="text-sm">
                    <p class="font-semibold text-gray-800">Payment settled.</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $order->payment_request_count }} payment {{ $order->payment_request_count === 1 ? 'request was' : 'requests were' }} sent for this order,
                        the last on {{ $order->payment_requested_at->format('d M Y, H:i') }}.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Emails sent for this order --}}
        @php
            $orderEmails = \App\Models\EmailLog::where('order_id', $order->id)
                ->orderByDesc('sent_at')->orderByDesc('id')->get();
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-800">Emails Sent</h2>
                <a href="{{ route('admin.email-logs.index', ['q' => $order->email]) }}" class="text-xs text-[#bb976d] hover:underline">View all</a>
            </div>
            @forelse($orderEmails as $log)
                <div class="flex items-start gap-2 py-2 {{ ! $loop->last ? 'border-b border-gray-50' : '' }}">
                    <i class="mdi {{ $log->failed() ? 'mdi-email-alert text-red-500' : 'mdi-email-check text-green-500' }} mt-0.5"></i>
                    <div class="min-w-0 text-sm">
                        <p class="font-medium text-gray-800">{{ $log->type_label }}</p>
                        <p class="text-xs text-gray-400 truncate" title="{{ $log->to_email }}">{{ $log->to_email }}</p>
                        <p class="text-xs text-gray-400">{{ optional($log->sent_at ?? $log->created_at)->format('d M Y, H:i') }} UTC</p>
                        @if($log->failed() && $log->error)
                            <p class="text-xs text-red-500 mt-0.5">{{ $log->error }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">No emails logged for this order yet.</p>
            @endforelse
        </div>

        {{-- Supplier / Fulfillment Info (internal only) --}}
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-5">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-base font-semibold text-gray-800">Supplier Fulfillment</h2>
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Internal Only</span>
            </div>
            <p class="text-xs text-gray-400 mb-4">This info is never shown to the customer. Fill in once you place the order on the supplier site. Setting status to <strong>Shipped</strong> automatically emails the customer.</p>

            @if($order->tracking_number)
            <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-100 text-sm">
                <p class="text-xs text-gray-400 mb-0.5">Customer Tracking #</p>
                <p class="font-bold text-[#bb976d] tracking-widest text-base">{{ $order->tracking_number }}</p>
            </div>
            @endif

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Supplier Name</label>
                    <select name="supplier_name"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
                        <option value="">-- Select Supplier --</option>
                        @foreach(['Amazon','Walmart','eBay','AliExpress','Other'] as $sup)
                        <option value="{{ $sup }}" {{ $order->supplier_name === $sup ? 'selected' : '' }}>{{ $sup }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Supplier Order ID</label>
                    <input type="text" name="supplier_order_id" value="{{ old('supplier_order_id', $order->supplier_order_id) }}"
                           placeholder="e.g. AMZ-123-4567890"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Supplier Tracking #</label>
                    <input type="text" name="supplier_tracking" value="{{ old('supplier_tracking', $order->supplier_tracking) }}"
                           placeholder="e.g. 1Z999AA10123456784"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Carrier</label>
                    <select name="carrier"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
                        <option value="">-- Select Carrier --</option>
                        @foreach(['UPS','FedEx','USPS','DHL','Other'] as $carrier)
                        <option value="{{ strtolower($carrier) }}" {{ $order->carrier === strtolower($carrier) ? 'selected' : '' }}>{{ $carrier }}</option>
                        @endforeach
                    </select>
                </div>
                @if($order->shipped_at)
                <p class="text-xs text-gray-400">Shipped on: {{ \Carbon\Carbon::parse($order->shipped_at)->format('d M Y, H:i') }}</p>
                @endif
                <button type="submit"
                        class="w-full px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                    Save Supplier Info
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-copy]');
    if (!btn) return;

    var field = document.querySelector(btn.dataset.copy);
    if (!field) return;

    // select() first: it works on http:// where the clipboard API is unavailable,
    // and leaves the link highlighted for a manual Ctrl+C if the write is refused.
    field.select();
    field.setSelectionRange(0, field.value.length);

    var done = function () {
        var original = btn.innerHTML;
        btn.innerHTML = '<i class="mdi mdi-check"></i> Copied';
        setTimeout(function () { btn.innerHTML = original; }, 1800);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(field.value).then(done, function () {});
    } else if (document.execCommand('copy')) {
        done();
    }
});
</script>
@endpush
