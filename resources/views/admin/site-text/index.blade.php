@extends('admin.layouts.app')

@section('title', 'Site Text & SEO')
@section('page-title', 'Site Text & SEO')

@section('content')
@php $in = 'w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#bb976d]'; @endphp
<div class="max-w-4xl space-y-5">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="p-4 bg-white rounded-xl border border-gray-100 text-sm text-gray-600 space-y-1">
        <p>Edit the wording below. Leave a field blank to use the default (shown as the grey placeholder).</p>
        <p>Tokens: <code>{shipping}</code> becomes "Free US shipping" (or "Free US shipping on $35+" when the free-shipping threshold is on); <code>{delivery}</code> becomes the delivery window, e.g. 5–7. The brand name is added to titles automatically, once.</p>
        <p>Category pages use the category's own meta (Admin → Categories) first, then the fallback here. Product pages use each product's own meta fields, or are built from its name and description.</p>
    </div>

    <form method="POST" action="{{ route('admin.site-text.update') }}" class="space-y-5">
        @csrf @method('PUT')

        @foreach($fields as $group => $items)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider pb-2 border-b border-gray-100">{{ $group }}</h3>
                @foreach($items as $key => $field)
                    @php $val = old("texts.$key", $overrides[$key] ?? ''); @endphp
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="t-{{ $loop->parent->index }}-{{ $loop->index }}">{{ $field['label'] }}</label>
                        @if(! empty($field['long']))
                            <textarea id="t-{{ $loop->parent->index }}-{{ $loop->index }}" name="texts[{{ $key }}]" rows="2" maxlength="2000" class="{{ $in }}" placeholder="{{ $field['default'] }}">{{ $val }}</textarea>
                        @else
                            <input id="t-{{ $loop->parent->index }}-{{ $loop->index }}" name="texts[{{ $key }}]" maxlength="2000" class="{{ $in }}" value="{{ $val }}" placeholder="{{ $field['default'] }}">
                        @endif
                        @if(! empty($field['max']) && str_starts_with($key, 'meta.'))
                            <p class="text-xs text-gray-400 mt-1">Search results show about {{ str_ends_with($key, 'title') ? '60' : '155' }} characters.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach

        <button class="bg-[#bb976d] text-white font-semibold rounded-lg px-6 py-2.5 text-sm">Save</button>
    </form>
</div>
@endsection
