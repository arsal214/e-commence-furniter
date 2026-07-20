<script>
/*
 | Keeps option <select>s in sync with the values typed into the variant boxes:
 |   .js-color-select  <- Colors box (colors_raw)
 |   .js-size-select   <- Sizes box  (sizes_raw)
 | Options are (re)built from the live list; each select preserves its own
 | current selection when still valid.
 |
 | Exposes window.pdFillColorSelect(select) and window.pdFillSizeSelect(select)
 | so dynamically-created selects (variant repeater rows, per-file gallery rows)
 | can be populated the moment they are added.
 */
(function () {
    // Each dimension: which hidden field feeds it and which selects it targets.
    var DIMENSIONS = [
        { field: 'colors_raw', cls: 'js-color-select', fill: 'pdFillColorSelect' },
        { field: 'sizes_raw',  cls: 'js-size-select',  fill: 'pdFillSizeSelect'  },
    ];

    function valuesFrom(field) {
        var hidden = document.querySelector('input[name="' + field + '"]');
        if (!hidden || !hidden.value.trim()) return [];
        return hidden.value.split(',').map(function (v) { return v.trim(); })
                                      .filter(function (v) { return v !== ''; });
    }

    function fill(select, values) {
        // Preserve the intended selection: explicit data-selected wins on first
        // fill, otherwise keep whatever is currently chosen.
        var want = select.dataset.selected != null && select.dataset.selected !== ''
            ? select.dataset.selected
            : select.value;
        select.innerHTML = '';

        var blank = document.createElement('option');
        blank.value = '';
        blank.textContent = '— select —';
        select.appendChild(blank);

        values.forEach(function (c) {
            var opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            if (c === want) opt.selected = true;
            select.appendChild(opt);
        });

        select.removeAttribute('data-selected');
    }

    DIMENSIONS.forEach(function (dim) {
        // Expose a per-dimension fill helper for dynamically-created selects.
        window[dim.fill] = function (select) { fill(select, valuesFrom(dim.field)); };

        function fillAll() {
            var values = valuesFrom(dim.field);
            document.querySelectorAll('.' + dim.cls).forEach(function (s) { fill(s, values); });
        }

        document.addEventListener('DOMContentLoaded', fillAll);
        document.addEventListener('variant:change', function (e) {
            if (e.detail && e.detail.field === dim.field) fillAll();
        });
    });
})();
</script>
