@extends('admin.layouts.app')

@section('title', 'Site Text & SEO')
@section('page-title', 'Site Text & SEO')

@section('content')
@php $in = 'w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#EA580C]'; @endphp
<div class="max-w-4xl space-y-4">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <details class="p-4 bg-white rounded-xl border border-gray-100 text-sm text-gray-600">
        <summary class="cursor-pointer font-medium text-gray-700">How this works</summary>
        <div class="mt-2 space-y-1">
            <p>Pick a page tab, edit its text, then press <strong>Save</strong>. One Save stores the changes on every tab. Leave a field blank to use the default (shown as the grey text).</p>
            <p>Tokens: <code>{shipping}</code> becomes "Free US shipping" (or "Free US shipping on $35+" when the free-shipping threshold is on); <code>{delivery}</code> becomes the delivery window, e.g. 5–7. The brand name is added to titles automatically, once.</p>
            <p>Category pages use the category's own meta (Admin → Categories) first, then the fallback on the Categories tab. Product pages use each product's own meta fields, or are built from its name and description.</p>
        </div>
    </details>

    <form method="POST" action="{{ route('admin.site-text.update') }}" id="st-form">
        @csrf @method('PUT')
        <input type="hidden" name="tab" id="st-tab" value="{{ $active }}">

        {{-- Tabs: one per page. Scrolls sideways on a phone instead of wrapping. --}}
        <div class="border-b border-gray-200 overflow-x-auto" role="tablist" aria-label="Pages">
            <div class="flex gap-1 min-w-max">
                @foreach($panels as $slug => $panel)
                    <button type="button" role="tab" id="tab-{{ $slug }}" data-tab="{{ $slug }}" aria-controls="panel-{{ $slug }}"
                            class="st-tab px-4 py-2.5 text-sm font-medium border-b-2 -mb-px whitespace-nowrap text-gray-500 border-transparent hover:text-gray-800">
                        {{ $panel['label'] }}
                        @if($panel['edited'])
                            <span class="ml-1 inline-block text-[11px] leading-none rounded-full bg-[#EA580C] text-white px-1.5 py-1" title="{{ $panel['edited'] }} customised">{{ $panel['edited'] }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        @foreach($panels as $slug => $panel)
            <section id="panel-{{ $slug }}" role="tabpanel" aria-labelledby="tab-{{ $slug }}" data-panel="{{ $slug }}" class="st-panel bg-white rounded-b-xl rounded-tr-xl shadow-sm border border-gray-100 border-t-0 p-5 sm:p-6 space-y-5" hidden>
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-base font-semibold text-gray-800">{{ $panel['label'] }}</h3>
                    <a href="{{ $panel['url'] }}" target="_blank" rel="noopener" class="text-sm text-[#EA580C] font-medium hover:underline">View page &rarr;</a>
                </div>

                @php $lastGroup = null; @endphp
                @forelse($panel['fields'] as $field)
                    @php
                        $key = $field['key'];
                        $isMeta = str_starts_with($key, 'meta.');
                        $group = $isMeta ? 'Search results (title and description)' : $field['group'];
                        $val = old("texts.$key", $overrides[$key] ?? '');
                        $id = 'f-' . md5($key);
                        $limit = $isMeta ? (str_ends_with($key, '.title') ? 60 : 155) : null;
                    @endphp

                    @if($group !== $lastGroup)
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider pt-2">{{ $group }}</p>
                        @php $lastGroup = $group; @endphp
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="{{ $id }}">{{ $field['label'] }}</label>
                        @if(! empty($field['long']))
                            <textarea id="{{ $id }}" name="texts[{{ $key }}]" rows="3" maxlength="2000" class="{{ $in }}" placeholder="{{ $field['default'] }}"
                                      @if($limit) data-limit="{{ $limit }}" @endif>{{ $val }}</textarea>
                        @else
                            <input id="{{ $id }}" name="texts[{{ $key }}]" maxlength="2000" class="{{ $in }}" value="{{ $val }}" placeholder="{{ $field['default'] }}"
                                   @if($limit) data-limit="{{ $limit }}" @endif>
                        @endif
                        @if($limit)
                            <p class="text-xs text-gray-400 mt-1"><span data-count-for="{{ $id }}"></span> Search results show about {{ $limit }} characters.</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Nothing editable here yet.</p>
                @endforelse

                <div class="pt-2 border-t border-gray-100">
                    <button class="bg-[#EA580C] text-white font-semibold rounded-lg px-6 py-2.5 text-sm">Save</button>
                </div>
            </section>
        @endforeach
    </form>
</div>

<script>
(function () {
    var tabs = document.querySelectorAll('.st-tab'), panels = document.querySelectorAll('.st-panel'), hidden = document.getElementById('st-tab');

    function show(slug) {
        tabs.forEach(function (t) {
            var on = t.dataset.tab === slug;
            t.setAttribute('aria-selected', on ? 'true' : 'false');
            t.classList.toggle('border-[#EA580C]', on); t.classList.toggle('text-[#EA580C]', on);
            t.classList.toggle('border-transparent', !on); t.classList.toggle('text-gray-500', !on);
        });
        panels.forEach(function (p) { p.hidden = p.dataset.panel !== slug; });
        hidden.value = slug;
        try { history.replaceState(null, '', '?tab=' + encodeURIComponent(slug)); } catch (e) {}
    }

    tabs.forEach(function (t) { t.addEventListener('click', function () { show(t.dataset.tab); }); });

    // Left/Right arrows move between tabs, as people expect from a tab list.
    document.querySelector('[role=tablist]').addEventListener('keydown', function (e) {
        if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
        var list = Array.prototype.slice.call(tabs), i = list.indexOf(document.activeElement);
        if (i < 0) return;
        var next = list[(i + (e.key === 'ArrowRight' ? 1 : list.length - 1)) % list.length];
        next.focus(); show(next.dataset.tab);
    });

    // Live length hint for search-result fields (placeholder length when the field is blank).
    document.querySelectorAll('[data-limit]').forEach(function (el) {
        var out = document.querySelector('[data-count-for="' + el.id + '"]'), limit = +el.dataset.limit;
        function update() {
            var n = (el.value || el.placeholder).length;
            out.textContent = n + ' characters.'; out.style.color = n > limit ? '#b45309' : '';
        }
        el.addEventListener('input', update); update();
    });

    show(@json($active));
})();
</script>
@endsection
