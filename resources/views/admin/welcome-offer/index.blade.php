@extends('admin.layouts.app')

@section('title', 'Welcome Offer')
@section('page-title', 'Welcome Offer')

@section('content')
@php
    $in = 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]';
    $tiers = old('tiers', $offer->tiers->map(fn ($t) => ['type' => $t->type, 'amount' => (float) $t->amount, 'min_subtotal' => (float) $t->min_subtotal, 'max_discount' => $t->max_discount])->all());
@endphp
<div class="max-w-4xl space-y-5">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm"><ul class="text-red-700 space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul></div>
    @endif

    {{-- Results --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        @foreach([['Claims', $stats['claims']], ['Redeemed', $stats['redeemed']], ['Redemption rate', $stats['rate'] . '%'], ['Discount given', '$' . number_format($stats['discount'], 2)], ['Revenue from redeemed', '$' . number_format($stats['revenue'], 2)]] as [$k, $v])
            <div class="bg-white rounded-xl border border-gray-100 p-4"><p class="text-xs text-gray-400">{{ $k }}</p><p class="text-lg font-semibold text-gray-800">{{ $v }}</p></div>
        @endforeach
    </div>
    @if($perTier->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-4 text-sm">
            <p class="font-semibold text-gray-700 mb-2">By tier</p>
            @foreach($perTier as $label => $row)
                <p class="text-gray-600">{{ $label }}: {{ $row['count'] }} redeemed, ${{ number_format($row['discount'], 2) }} given</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.welcome-offer.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-800">Offer status</h3>
                <p class="text-sm text-gray-400 mt-0.5">Off by default. When off, nothing shows on the site and nothing applies at checkout.</p>
            </div>
            <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $offer->is_active) ? 'checked' : '' }}> Active
            </label>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 grid sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Name</label><input class="{{ $in }}" name="name" value="{{ old('name', $offer->name) }}" required></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Expires after (hours, counted from the claim)</label><input class="{{ $in }}" type="number" min="1" max="720" name="expiry_hours" value="{{ old('expiry_hours', $offer->expiry_hours) }}" required></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Starts (optional)</label><input class="{{ $in }}" type="datetime-local" name="starts_at" value="{{ old('starts_at', $offer->starts_at?->format('Y-m-d\TH:i')) }}"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Ends (optional)</label><input class="{{ $in }}" type="datetime-local" name="ends_at" value="{{ old('ends_at', $offer->ends_at?->format('Y-m-d\TH:i')) }}"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" id="tiers">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider pb-2 mb-3 border-b border-gray-100">Tiers</h3>
            <p class="text-xs text-gray-400 mb-3">The best qualifying money-off tier applies automatically. A free-shipping tier does nothing (and is hidden from shoppers) until shipping has a fee.</p>
            @foreach($tiers as $i => $t)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
                    <select name="tiers[{{ $i }}][type]" class="{{ $in }}">
                        @foreach(['fixed_amount' => 'Fixed amount off', 'percent' => 'Percent off', 'free_shipping' => 'Free shipping'] as $val => $lab)
                            <option value="{{ $val }}" @selected(($t['type'] ?? '') === $val)>{{ $lab }}</option>
                        @endforeach
                    </select>
                    <input class="{{ $in }}" type="number" step="0.01" min="0" name="tiers[{{ $i }}][amount]" value="{{ $t['amount'] ?? 0 }}" placeholder="Amount ($ or %)">
                    <input class="{{ $in }}" type="number" step="0.01" min="0" name="tiers[{{ $i }}][min_subtotal]" value="{{ $t['min_subtotal'] ?? 0 }}" placeholder="Minimum subtotal">
                    <input class="{{ $in }}" type="number" step="0.01" min="0" name="tiers[{{ $i }}][max_discount]" value="{{ $t['max_discount'] ?? '' }}" placeholder="Max discount (percent only)">
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider pb-2 border-b border-gray-100">Text shown to shoppers (leave blank for the default)</h3>
            @foreach(['bar_text' => 'Sticky bar text', 'bar_button' => 'Bar button', 'modal_title' => 'Modal title', 'modal_button' => 'Modal button', 'sms_text' => 'SMS consent text'] as $key => $label)
                <div><label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
                    <input class="{{ $in }}" name="copy[{{ $key }}]" maxlength="200" value="{{ old("copy.$key", $offer->display_settings[$key] ?? '') }}" placeholder="{{ \App\Models\WelcomeOffer::DEFAULT_COPY[$key] }}"></div>
            @endforeach
        </div>

        <button class="bg-[#bb976d] text-white font-semibold rounded-lg px-6 py-2.5 text-sm">Save</button>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider pb-2 mb-3 border-b border-gray-100">Recent claims</h3>
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-400"><tr><th class="py-1 pr-4">Email</th><th class="pr-4">Claimed</th><th class="pr-4">Expires</th><th class="pr-4">SMS</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($recent as $c)
                <tr class="border-t border-gray-100"><td class="py-1.5 pr-4">{{ $c->email }}</td><td class="pr-4">{{ $c->claimed_at->format('M j, g:i A') }}</td><td class="pr-4">{{ $c->expires_at->format('M j, g:i A') }}</td><td class="pr-4">{{ $c->sms_opt_in ? 'Yes' : '' }}</td>
                    <td>{{ $c->used_at ? 'Used (order #' . $c->used_order_id . ', -$' . number_format((float) $c->discount_given, 2) . ')' : ($c->expires_at->isPast() ? 'Expired' : 'Active') }}</td></tr>
            @empty
                <tr><td colspan="5" class="py-3 text-gray-400">No claims yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
