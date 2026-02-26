<script>
(function () {
    function initVariantBox(box) {
        const input       = box.querySelector('.variant-input');
        const placeholder = box.querySelector('.variant-placeholder');
        // hidden field sits in the parent <div> right after the box wrapper
        const hidden      = box.parentElement.querySelector('.variant-hidden');
        const tags        = [];

        function syncHidden() {
            hidden.value = tags.join(',');
        }

        function updatePlaceholder() {
            placeholder.style.display = tags.length > 0 ? 'none' : '';
        }

        function buildChip(value) {
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-[#bb976d]/15 text-[#7a5c35] border border-[#bb976d]/30 whitespace-nowrap';
            chip.dataset.value = value;

            const label = document.createElement('span');
            label.textContent = value;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'ml-0.5 hover:text-red-500 transition-colors leading-none text-[#9a7a55] font-bold';
            btn.innerHTML = '&times;';
            btn.addEventListener('click', function () {
                const idx = tags.indexOf(value);
                if (idx !== -1) tags.splice(idx, 1);
                chip.remove();
                syncHidden();
                updatePlaceholder();
            });

            chip.appendChild(label);
            chip.appendChild(btn);
            return chip;
        }

        function addTag(value) {
            value = value.trim();
            if (!value || tags.includes(value)) return;
            tags.push(value);
            box.insertBefore(buildChip(value), input);
            syncHidden();
            updatePlaceholder();
        }

        // Restore existing values from hidden field on page load
        if (hidden.value.trim()) {
            hidden.value.split(',').forEach(function (v) {
                var trimmed = v.trim();
                if (trimmed) addTag(trimmed);
            });
        }

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                addTag(this.value);
                this.value = '';
            } else if (e.key === 'Backspace' && this.value === '' && tags.length) {
                const chips = box.querySelectorAll('span[data-value]');
                if (chips.length) {
                    const last = chips[chips.length - 1];
                    const idx = tags.indexOf(last.dataset.value);
                    if (idx !== -1) tags.splice(idx, 1);
                    last.remove();
                    syncHidden();
                    updatePlaceholder();
                }
            }
        });

        input.addEventListener('blur', function () {
            if (this.value.trim()) {
                addTag(this.value);
                this.value = '';
            }
        });

        box.addEventListener('click', function (e) {
            if (e.target !== input) input.focus();
        });

        updatePlaceholder();
    }

    document.querySelectorAll('.variant-tag-box').forEach(initVariantBox);
})();
</script>
