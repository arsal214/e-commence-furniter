<script>
/*
 | Keeps every colour <select class="js-color-select"> in sync with the colours
 | typed into the Colors variant box. Options are (re)built from the live colour
 | list; each select preserves its own current selection when still valid.
 |
 | Also exposes window.pdFillColorSelect(select) so dynamically-created selects
 | (e.g. per-file gallery rows) can be populated the moment they are added.
 */
(function () {
    function currentColors() {
        var hidden = document.querySelector('input[name="colors_raw"]');
        if (!hidden || !hidden.value.trim()) return [];
        return hidden.value.split(',').map(function (v) { return v.trim(); })
                                      .filter(function (v) { return v !== ''; });
    }

    function fill(select, colors) {
        colors = colors || currentColors();
        // Preserve the intended selection: explicit data-selected wins on first
        // fill, otherwise keep whatever is currently chosen.
        var want = select.dataset.selected != null && select.dataset.selected !== ''
            ? select.dataset.selected
            : select.value;
        select.innerHTML = '';

        var blank = document.createElement('option');
        blank.value = '';
        blank.textContent = '— colour —';
        select.appendChild(blank);

        colors.forEach(function (c) {
            var opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            if (c === want) opt.selected = true;
            select.appendChild(opt);
        });

        // Once applied, clear data-selected so later user changes aren't overridden.
        select.removeAttribute('data-selected');
    }
    window.pdFillColorSelect = fill;

    function fillAll() {
        var colors = currentColors();
        document.querySelectorAll('.js-color-select').forEach(function (s) { fill(s, colors); });
    }

    // Populate on load, then whenever the colours change.
    document.addEventListener('DOMContentLoaded', fillAll);
    document.addEventListener('variant:change', function (e) {
        if (e.detail && e.detail.field === 'colors_raw') fillAll();
    });
})();
</script>
