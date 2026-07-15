{{--
    Specifications repeater — shared by the create & edit product forms.
    Pass the current rows via:  @include('admin.products._specs-repeater', ['existingSpecs' => $product->specifications ?? []])
--}}
@php
    $existingSpecs = $existingSpecs ?? [];

    // On a validation redirect, old() input wins; otherwise use the saved rows.
    if (old('spec_label') !== null) {
        $specRows = [];
        foreach (old('spec_label', []) as $i => $lbl) {
            $specRows[] = ['label' => $lbl, 'value' => old('spec_value.' . $i, '')];
        }
    } else {
        $specRows = $existingSpecs;
    }

    $specInput = 'border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#bb976d] transition-colors';
    $specDel   = 'spec-remove flex-shrink-0 w-9 h-9 rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 transition-colors';
@endphp

<div class="sm:col-span-2 border-t border-gray-100 pt-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">Specifications
        <span class="text-gray-400 font-normal text-xs">(shown as a table on the product page)</span>
    </label>
    <p class="text-xs text-gray-400 mb-3">
        Add label / value pairs — e.g. <strong>Material</strong> / Solid oak, <strong>Dimensions</strong> / 120 × 60 × 45 cm, <strong>Warranty</strong> / 2 years. Empty rows are ignored.
    </p>

    <div id="specs-rows" class="space-y-2">
        @forelse($specRows as $row)
        <div class="spec-row flex items-center gap-2">
            <input type="text" name="spec_label[]" value="{{ $row['label'] ?? '' }}" placeholder="Label (e.g. Material)" class="w-1/3 {{ $specInput }}">
            <input type="text" name="spec_value[]" value="{{ $row['value'] ?? '' }}" placeholder="Value (e.g. Solid oak)" class="flex-1 {{ $specInput }}">
            <button type="button" class="{{ $specDel }}" aria-label="Remove specification">&times;</button>
        </div>
        @empty
        <div class="spec-row flex items-center gap-2">
            <input type="text" name="spec_label[]" placeholder="Label (e.g. Material)" class="w-1/3 {{ $specInput }}">
            <input type="text" name="spec_value[]" placeholder="Value (e.g. Solid oak)" class="flex-1 {{ $specInput }}">
            <button type="button" class="{{ $specDel }}" aria-label="Remove specification">&times;</button>
        </div>
        @endforelse
    </div>

    <button type="button" id="specs-add" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-[#bb976d] hover:text-[#a8845a]">
        <span class="text-lg leading-none">+</span> Add specification
    </button>
</div>

@push('scripts')
<script>
(function () {
    var rows = document.getElementById('specs-rows');
    var add  = document.getElementById('specs-add');
    if (!rows || !add) return;

    var inputCls = {!! json_encode($specInput) !!};
    var delCls   = {!! json_encode($specDel) !!};

    function newRow() {
        var d = document.createElement('div');
        d.className = 'spec-row flex items-center gap-2';
        d.innerHTML =
            '<input type="text" name="spec_label[]" placeholder="Label (e.g. Material)" class="w-1/3 ' + inputCls + '">' +
            '<input type="text" name="spec_value[]" placeholder="Value (e.g. Solid oak)" class="flex-1 ' + inputCls + '">' +
            '<button type="button" class="' + delCls + '" aria-label="Remove specification">×</button>';
        return d;
    }

    add.addEventListener('click', function () {
        var row = newRow();
        rows.appendChild(row);
        row.querySelector('input').focus();
    });

    // Remove a row; if it's the last one, just clear it so a row always remains.
    rows.addEventListener('click', function (e) {
        if (!e.target.closest('.spec-remove')) return;
        var row = e.target.closest('.spec-row');
        if (rows.querySelectorAll('.spec-row').length > 1) {
            row.remove();
        } else {
            row.querySelectorAll('input').forEach(function (i) { i.value = ''; });
        }
    });
})();
</script>
@endpush
