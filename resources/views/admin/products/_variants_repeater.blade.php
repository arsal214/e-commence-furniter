{{--
    Option-variant repeater — shared by the create & edit product forms, and
    reused for BOTH dimensions (colour and size). Each row is one option value
    with its own price / sale / stock / SKU / image.

    Include once per dimension, e.g.:
        @include('admin.products._variants_repeater', [
            'dimension'   => 'color',
            'label'       => 'Colour Pricing & Stock',
            'optionLabel' => 'Colour',
            'selectClass' => 'js-color-select',
            'fillFn'      => 'pdFillColorSelect',
            'sourceLabel' => 'Colors',
            'existingVariants' => $product->variants->where('type', 'color'),
        ])

    Field names: variants[<dimension>][<i>][value|price|sale_price|stock|sku|image|id]
    Explicit numeric indices keep per-row file uploads (variants.<dim>.<i>.image)
    mapping correctly on the server.
--}}
@php
    $existingVariants = $existingVariants ?? collect();
    $optionLabel = $optionLabel ?? ucfirst($dimension);
    $sourceLabel = $sourceLabel ?? $optionLabel . 's';

    // On a validation redirect, old() wins (files can't be restored); else saved rows.
    if (old("variants.$dimension") !== null) {
        $variantRows = collect(old("variants.$dimension"))->map(fn ($r) => (object) array_merge(
            ['id' => null, 'value' => '', 'price' => '', 'sale_price' => '', 'stock' => '', 'sku' => '', 'image' => null],
            (array) $r
        ))->values();
    } else {
        $variantRows = collect($existingVariants)->values();
    }

    $vInput = 'border border-gray-300 rounded-lg px-2.5 py-2 text-sm focus:outline-none focus:border-[#bb976d] transition-colors';
@endphp

<div class="sm:col-span-2 border-t border-gray-100 pt-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}
        <span class="text-gray-400 font-normal text-xs">(optional — set a price/stock per {{ strtolower($optionLabel) }})</span>
    </label>
    <p class="text-xs text-gray-400 mb-3">
        Add a row per {{ strtolower($optionLabel) }} to give it its <strong>own price, stock, SKU and photo</strong>.
        Values come from the <strong>{{ $sourceLabel }}</strong> field above — add them there first.
        Leave empty to use the product's base price.
    </p>

    <div id="variants-{{ $dimension }}-rows" class="space-y-3">
        @foreach($variantRows as $i => $row)
        @php $rid = $row->id ?? null; $rimg = $row->image ?? null; @endphp
        <div class="variant-row rounded-lg border border-gray-200 p-3" data-index="{{ $i }}">
            @if($rid)<input type="hidden" name="variants[{{ $dimension }}][{{ $i }}][id]" value="{{ $rid }}">@endif
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 items-end">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-[11px] font-medium text-gray-500 mb-1">{{ $optionLabel }}</label>
                    <select name="variants[{{ $dimension }}][{{ $i }}][value]" data-selected="{{ $row->value ?? '' }}"
                            class="{{ $selectClass }} w-full {{ $vInput }}">
                        <option value="">— {{ strtolower($optionLabel) }} —</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-gray-500 mb-1">Price</label>
                    <input type="number" step="0.01" min="0" name="variants[{{ $dimension }}][{{ $i }}][price]"
                           value="{{ $row->price ?? '' }}" placeholder="0.00" class="w-full {{ $vInput }}">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-gray-500 mb-1">Sale price</label>
                    <input type="number" step="0.01" min="0" name="variants[{{ $dimension }}][{{ $i }}][sale_price]"
                           value="{{ $row->sale_price ?? '' }}" placeholder="optional" class="w-full {{ $vInput }}">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-gray-500 mb-1">Stock</label>
                    <input type="number" step="1" min="0" name="variants[{{ $dimension }}][{{ $i }}][stock]"
                           value="{{ $row->stock ?? '' }}" placeholder="0" class="w-full {{ $vInput }}">
                </div>
                <div>
                    <label class="block text-[11px] font-medium text-gray-500 mb-1">SKU</label>
                    <input type="text" name="variants[{{ $dimension }}][{{ $i }}][sku]"
                           value="{{ $row->sku ?? '' }}" placeholder="optional" class="w-full {{ $vInput }}">
                </div>
                <div class="col-span-2 sm:col-span-1 flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-[11px] font-medium text-gray-500 mb-1">Image</label>
                        <div class="flex items-center gap-2">
                            @if($rimg)
                            <img src="{{ \Storage::url($rimg) }}" class="w-9 h-9 object-cover rounded border border-gray-200 flex-shrink-0" alt="">
                            @endif
                            <input type="file" accept="image/*" name="variants[{{ $dimension }}][{{ $i }}][image]"
                                   class="w-full text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[11px] file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d]">
                        </div>
                    </div>
                    <button type="button" class="variant-remove flex-shrink-0 w-9 h-9 rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 transition-colors" aria-label="Remove variant">&times;</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <button type="button" id="variants-{{ $dimension }}-add" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-[#bb976d] hover:text-[#a8845a]">
        <span class="text-lg leading-none">+</span> Add {{ strtolower($optionLabel) }} price
    </button>
</div>

@push('scripts')
<script>
(function () {
    var dimension = @json($dimension);
    var rows = document.getElementById('variants-' + dimension + '-rows');
    var add  = document.getElementById('variants-' + dimension + '-add');
    if (!rows || !add) return;

    var vInput      = {!! json_encode($vInput) !!};
    var selectClass = @json($selectClass);
    var fillFnName  = @json($fillFn);
    var optionLabel = @json($optionLabel);
    var placeholder = '— ' + optionLabel.toLowerCase() + ' —';
    var nextIndex   = {{ $variantRows->count() }};

    function fieldName(i, key) { return 'variants[' + dimension + '][' + i + '][' + key + ']'; }

    function rowHtml(i) {
        return '' +
        '<div class="grid grid-cols-2 sm:grid-cols-6 gap-2 items-end">' +
          '<div class="col-span-2 sm:col-span-1">' +
            '<label class="block text-[11px] font-medium text-gray-500 mb-1">' + optionLabel + '</label>' +
            '<select name="' + fieldName(i, 'value') + '" class="' + selectClass + ' w-full ' + vInput + '"><option value="">' + placeholder + '</option></select>' +
          '</div>' +
          '<div><label class="block text-[11px] font-medium text-gray-500 mb-1">Price</label>' +
            '<input type="number" step="0.01" min="0" name="' + fieldName(i, 'price') + '" placeholder="0.00" class="w-full ' + vInput + '"></div>' +
          '<div><label class="block text-[11px] font-medium text-gray-500 mb-1">Sale price</label>' +
            '<input type="number" step="0.01" min="0" name="' + fieldName(i, 'sale_price') + '" placeholder="optional" class="w-full ' + vInput + '"></div>' +
          '<div><label class="block text-[11px] font-medium text-gray-500 mb-1">Stock</label>' +
            '<input type="number" step="1" min="0" name="' + fieldName(i, 'stock') + '" placeholder="0" class="w-full ' + vInput + '"></div>' +
          '<div><label class="block text-[11px] font-medium text-gray-500 mb-1">SKU</label>' +
            '<input type="text" name="' + fieldName(i, 'sku') + '" placeholder="optional" class="w-full ' + vInput + '"></div>' +
          '<div class="col-span-2 sm:col-span-1 flex items-end gap-2">' +
            '<div class="flex-1"><label class="block text-[11px] font-medium text-gray-500 mb-1">Image</label>' +
              '<input type="file" accept="image/*" name="' + fieldName(i, 'image') + '" class="w-full text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[11px] file:font-medium file:bg-[#bb976d]/10 file:text-[#bb976d]"></div>' +
            '<button type="button" class="variant-remove flex-shrink-0 w-9 h-9 rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 transition-colors" aria-label="Remove variant">×</button>' +
          '</div>' +
        '</div>';
    }

    add.addEventListener('click', function () {
        var i = nextIndex++;
        var d = document.createElement('div');
        d.className = 'variant-row rounded-lg border border-gray-200 p-3';
        d.dataset.index = i;
        d.innerHTML = rowHtml(i);
        rows.appendChild(d);
        var sel = d.querySelector('.' + selectClass);
        if (sel && typeof window[fillFnName] === 'function') window[fillFnName](sel);
        if (sel) sel.focus();
    });

    rows.addEventListener('click', function (e) {
        if (!e.target.closest('.variant-remove')) return;
        var row = e.target.closest('.variant-row');
        if (row) row.remove();
    });
})();
</script>
@endpush
