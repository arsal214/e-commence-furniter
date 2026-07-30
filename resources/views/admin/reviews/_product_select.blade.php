{{-- Searchable product picker (vanilla JS combobox, no Alpine). Expects $products and $selected (id|null). --}}
@php
    $selectedId   = (string) ($selected ?? '');
    $selectedName = optional($products->firstWhere('id', (int) $selectedId))->name;
@endphp
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="product-search">Product <span class="text-red-500">*</span></label>

    <div class="relative" id="product-picker">
        <input type="hidden" name="product_id" id="product-id" value="{{ $selectedId }}">

        <input type="text" id="product-search" autocomplete="off" role="combobox" aria-expanded="false"
               aria-controls="product-options" aria-autocomplete="list"
               placeholder="Search products…" value="{{ $selectedName }}"
               class="w-full border border-gray-300 rounded-lg pl-9 pr-9 py-2.5 text-sm bg-white focus:outline-none focus:border-[#bb976d] transition-colors">

        <i class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base pointer-events-none"></i>
        <button type="button" id="product-clear" title="Clear"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none {{ $selectedName ? '' : 'hidden' }}">
            <i class="mdi mdi-close text-base"></i>
        </button>
        <i class="mdi mdi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-base pointer-events-none {{ $selectedName ? 'hidden' : '' }}"
           id="product-caret"></i>

        <div id="product-options" role="listbox"
             class="hidden absolute z-30 mt-1 w-full max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg py-1">
            @foreach ($products as $product)
                <button type="button" role="option" class="product-option block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                        aria-selected="{{ (string) $product->id === $selectedId ? 'true' : 'false' }}">
                    {{ $product->name }}
                </button>
            @endforeach
            <p id="product-empty" class="hidden px-3 py-2 text-sm text-gray-400">No products match your search.</p>
        </div>
    </div>

    <p id="product-error" class="text-red-500 text-xs mt-1 hidden">Please pick a product from the list.</p>
    @error('product_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

@push('scripts')
<script>
(function () {
    var wrap = document.getElementById('product-picker');
    if (!wrap) return;

    var search  = document.getElementById('product-search');
    var hidden  = document.getElementById('product-id');
    var list    = document.getElementById('product-options');
    var empty   = document.getElementById('product-empty');
    var error   = document.getElementById('product-error');
    var clear   = document.getElementById('product-clear');
    var caret   = document.getElementById('product-caret');
    var options = Array.prototype.slice.call(list.querySelectorAll('.product-option'));
    var activeEl = null;

    function shown() {
        return options.filter(function (o) { return !o.classList.contains('hidden'); });
    }
    function setActive(el) {
        if (activeEl) activeEl.classList.remove('bg-gray-100');
        activeEl = el;
        if (activeEl) {
            activeEl.classList.add('bg-gray-100');
            activeEl.scrollIntoView({ block: 'nearest' });
        }
    }
    function filter(term) {
        term = (term || '').trim().toLowerCase();
        var visible = 0;
        options.forEach(function (o) {
            var match = !term || o.dataset.name.toLowerCase().indexOf(term) !== -1;
            o.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        empty.classList.toggle('hidden', visible > 0);
        setActive(null);
    }
    function isOpen() { return !list.classList.contains('hidden'); }
    function open() {
        list.classList.remove('hidden');
        search.setAttribute('aria-expanded', 'true');
    }
    function close() {
        list.classList.add('hidden');
        search.setAttribute('aria-expanded', 'false');
        setActive(null);
        // Text must always mirror the actual selection — never a half-typed search term.
        search.value = hidden.value ? selectedName() : '';
        filter('');
        toggleIcons();
    }
    function selectedName() {
        var match = options.filter(function (o) { return o.dataset.id === hidden.value; })[0];
        return match ? match.dataset.name : '';
    }
    function toggleIcons() {
        clear.classList.toggle('hidden', !hidden.value);
        caret.classList.toggle('hidden', !!hidden.value);
    }
    function select(el) {
        hidden.value = el.dataset.id;
        options.forEach(function (o) { o.setAttribute('aria-selected', o === el ? 'true' : 'false'); });
        error.classList.add('hidden');
        close();
    }

    search.addEventListener('focus', function () { filter(''); open(); });
    search.addEventListener('click', function () { filter(search.value === selectedName() ? '' : search.value); open(); });

    search.addEventListener('input', function () {
        hidden.value = '';
        toggleIcons();
        filter(search.value);
        open();
    });

    search.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            if (!isOpen()) { filter(''); open(); }
            var items = shown();
            if (!items.length) return;
            var i = items.indexOf(activeEl);
            i = e.key === 'ArrowDown' ? (i + 1) % items.length : (i <= 0 ? items.length - 1 : i - 1);
            setActive(items[i]);
        } else if (e.key === 'Enter') {
            if (isOpen()) {
                e.preventDefault();
                var items = shown();
                if (activeEl) select(activeEl);
                else if (items.length === 1) select(items[0]);
            }
        } else if (e.key === 'Escape') {
            if (isOpen()) { e.preventDefault(); close(); }
        }
    });

    options.forEach(function (o) {
        o.addEventListener('click', function () { select(o); search.focus(); });
        o.addEventListener('mouseenter', function () { setActive(o); });
    });

    clear.addEventListener('click', function () {
        hidden.value = '';
        options.forEach(function (o) { o.setAttribute('aria-selected', 'false'); });
        close();
        search.focus();
    });

    document.addEventListener('click', function (e) {
        if (isOpen() && !wrap.contains(e.target)) close();
    });

    // A hidden input can't use the browser's `required`, so guard the submit here.
    var form = search.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!hidden.value) {
                e.preventDefault();
                error.classList.remove('hidden');
                filter('');
                open();
                search.focus();
            }
        });
    }

    toggleIcons();
})();
</script>
@endpush
