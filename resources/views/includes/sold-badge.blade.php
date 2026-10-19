{{-- Real units sold on paid orders in the last 30 days; renders nothing when the flag is off or the count is under the minimum. --}}
@php $soldLabel = \App\Services\SoldCounts::label((int) $pid); @endphp
@if ($soldLabel)
    <p class="text-xs text-gray-500 mt-1">{{ $soldLabel }}</p>
@endif
