{{-- Interactive star-rating picker (vanilla JS, no Alpine). Expects $current (int 1–5). --}}
@php $current = (int) ($current ?? 5); @endphp
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Rating <span class="text-red-500">*</span></label>
    <div class="flex items-center gap-1" id="star-picker">
        @for ($s = 1; $s <= 5; $s++)
            <button type="button" data-value="{{ $s }}"
                    class="star-pick-btn text-3xl leading-none text-gray-300 hover:scale-110 transition-transform focus:outline-none"
                    onclick="setRating({{ $s }})" aria-label="{{ $s }} star{{ $s > 1 ? 's' : '' }}">
                <i class="mdi mdi-star"></i>
            </button>
        @endfor
        <span class="ml-2 text-sm text-gray-500" id="rating-label"></span>
    </div>
    <input type="hidden" name="rating" id="rating-input" value="{{ $current }}">
    @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

@push('scripts')
<script>
(function () {
    var current = {{ $current }};
    var label   = document.getElementById('rating-label');
    function paint(n) {
        document.querySelectorAll('.star-pick-btn').forEach(function (btn) {
            btn.style.color = parseInt(btn.dataset.value) <= n ? '#f59e0b' : '#D1D5DB';
        });
    }
    function updateLabel(n) { if (label) label.textContent = n + ' / 5'; }
    paint(current);
    updateLabel(current);
    window.setRating = function (n) {
        current = n;
        document.getElementById('rating-input').value = n;
        paint(n);
        updateLabel(n);
    };
    document.querySelectorAll('.star-pick-btn').forEach(function (btn) {
        btn.addEventListener('mouseover', function () { paint(parseInt(btn.dataset.value)); });
        btn.addEventListener('mouseout',  function () { paint(current); });
    });
})();
</script>
@endpush
