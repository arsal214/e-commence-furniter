@extends('admin.layouts.app')

@section('title', 'Flash Deal')
@section('page-title', 'Flash Deal')

@section('content')
<div class="max-w-2xl">

    @if(session('success'))
    <div class="mb-5 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-lg text-sm">
        <ul class="text-red-700 space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.flash-deal.update') }}">
        @csrf @method('PUT')

        {{-- On / Off toggle --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">Flash Deal Status</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Toggle the flash deal banner on the homepage.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                           {{ $deal->is_active ? 'checked' : '' }}>
                    <div class="w-12 h-6 bg-gray-200 rounded-full peer
                                peer-checked:bg-[#bb976d]
                                after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:rounded-full after:h-5 after:w-5
                                after:transition-all peer-checked:after:translate-x-6"></div>
                    <span class="ml-3 text-sm font-semibold {{ $deal->is_active ? 'text-[#bb976d]' : 'text-gray-400' }}">
                        {{ $deal->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </label>
            </div>
        </div>

        {{-- Content fields --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider pb-2 border-b border-gray-100">Deal Content</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Badge Text</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', $deal->badge_text) }}"
                           placeholder="e.g. Limited Time"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Discount Label</label>
                    <input type="text" name="discount_label" value="{{ old('discount_label', $deal->discount_label) }}"
                           placeholder="e.g. Up to 40% OFF"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title', $deal->title) }}" required
                       placeholder="e.g. Flash Deal"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Subtitle <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $deal->subtitle) }}"
                       placeholder="e.g. Exclusive savings on premium furniture & home decor."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Deal Ends At
                    <span class="text-gray-400 font-normal">(leave blank to hide countdown)</span>
                </label>
                <input type="datetime-local" name="ends_at"
                       value="{{ old('ends_at', $deal->ends_at ? $deal->ends_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA Button Text</label>
                    <input type="text" name="cta_text" value="{{ old('cta_text', $deal->cta_text) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA URL</label>
                    <input type="text" name="cta_url" value="{{ old('cta_url', $deal->cta_url) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Background Color</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="bg_color" value="{{ old('bg_color', $deal->bg_color) }}"
                           class="w-10 h-10 rounded border border-gray-300 cursor-pointer p-0.5">
                    <input type="text" id="bg_color_text" value="{{ old('bg_color', $deal->bg_color) }}"
                           class="w-32 border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]"
                           oninput="document.querySelector('[name=bg_color]').value=this.value">
                    <div class="flex gap-2 flex-wrap">
                        @foreach(['#0F1E2E','#172430','#1a1a1a','#bb976d','#7c3aed','#dc2626'] as $c)
                        <button type="button" onclick="document.querySelector('[name=bg_color]').value='{{ $c }}';document.getElementById('bg_color_text').value='{{ $c }}'"
                                style="background:{{ $c }}"
                                class="w-7 h-7 rounded border-2 border-transparent hover:border-gray-400 transition-all" title="{{ $c }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-3 flex items-center gap-3 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#bb976d] text-white text-sm font-semibold rounded-lg hover:bg-[#a8845a] transition-colors">
                    Save Changes
                </button>
                <a href="{{ url('/') }}" target="_blank"
                   class="px-4 py-2.5 border border-gray-300 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Preview Homepage ↗
                </a>
            </div>
        </div>

    </form>

    {{-- Live preview --}}
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Preview</h3>
        <div id="fd-preview" style="background:{{ $deal->bg_color }};padding:28px 32px;text-align:center;">
            <span style="display:inline-block;background:#bb976d;color:#fff;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:4px 12px;margin-bottom:10px;">{{ $deal->badge_text }}</span>
            <div style="font-size:28px;font-weight:800;color:#fff;margin-bottom:6px;">{{ $deal->discount_label }}</div>
            <div style="font-size:16px;font-weight:700;color:#fff;margin-bottom:4px;">{{ $deal->title }}</div>
            @if($deal->subtitle)
            <div style="font-size:12px;color:rgba(255,255,255,.65);margin-bottom:14px;">{{ $deal->subtitle }}</div>
            @endif
            <a href="{{ $deal->cta_url }}" style="display:inline-block;background:#bb976d;color:#fff;padding:10px 24px;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;text-decoration:none;">{{ $deal->cta_text }} →</a>
        </div>
    </div>

</div>
@endsection
