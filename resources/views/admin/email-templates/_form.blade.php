@php
    /** Shared by create and edit. $template is null when creating. */
    $template = $template ?? null;
    $value = fn (string $field, $fallback = '') => old($field, $template->{$field} ?? $fallback);
@endphp

@if ($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
        <p class="font-medium mb-1">Please fix the following:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Template name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ $value('name') }}" required maxlength="80"
                       placeholder="Seasonal sale — 20% off"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                <p class="text-xs text-gray-400 mt-1">Internal only. This is what staff pick from on the compose screen.</p>
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" value="{{ $value('subject') }}" required maxlength="180"
                       placeholder="@{{first_name}}, 20% off our new collection"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="eyebrow" class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                    <input type="text" name="eyebrow" id="eyebrow" value="{{ $value('eyebrow') }}" maxlength="60"
                           placeholder="Limited Time Offer"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
                <div class="sm:col-span-2">
                    <label for="heading" class="block text-sm font-medium text-gray-700 mb-1">Headline</label>
                    <input type="text" name="heading" id="heading" value="{{ $value('heading') }}" maxlength="120"
                           placeholder="20% off everything this week"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <label for="body_html" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-400 mb-3">The greeting, store header, footer and unsubscribe link are added automatically when the email is sent.</p>
            <textarea name="body_html" id="body_html" class="tinymce-editor">{{ $value('body_html') }}</textarea>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-800">Offer options</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="promo_code" class="block text-sm font-medium text-gray-700 mb-1">Discount code</label>
                    <input type="text" name="promo_code" id="promo_code" value="{{ $value('promo_code') }}" maxlength="40"
                           placeholder="SUMMER20"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] uppercase">
                </div>
                <div>
                    <label for="promo_note" class="block text-sm font-medium text-gray-700 mb-1">Code caption</label>
                    <input type="text" name="promo_note" id="promo_note" value="{{ $value('promo_note') }}" maxlength="160"
                           placeholder="Valid until 30 September. One use per customer."
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-gray-100 pt-4">
                <div>
                    <label for="cta_label" class="block text-sm font-medium text-gray-700 mb-1">Button label</label>
                    <input type="text" name="cta_label" id="cta_label" value="{{ $value('cta_label') }}" maxlength="40"
                           placeholder="Shop the Sale"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
                <div class="sm:col-span-2">
                    <label for="cta_url" class="block text-sm font-medium text-gray-700 mb-1">Button link</label>
                    <input type="url" name="cta_url" id="cta_url" value="{{ $value('cta_url') }}" maxlength="500"
                           placeholder="{{ url('/shop') }}"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
            {{-- The hidden 0 keeps is_active present in old input, so an unchecked
                 box survives a validation error instead of re-checking itself. --}}
            <input type="hidden" name="is_active" value="0">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" {{ $value('is_active', true) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-[#bb976d] focus:ring-[#bb976d]">
                Available on the compose screen
            </label>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ $value('sort_order', 0) }}" min="0" max="9999"
                       class="w-24 text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
            </div>
            <button type="submit" class="w-full px-4 py-3 bg-[#bb976d] text-white text-sm font-semibold rounded-lg hover:bg-[#a8845a] transition-colors">
                {{ $template ? 'Save changes' : 'Create template' }}
            </button>
            <a href="{{ route('admin.email-templates.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs uppercase tracking-wide text-gray-400 font-medium mb-3">Merge tags</p>
            <p class="text-xs text-gray-500 mb-3">Usable in the subject, headline, caption and message — replaced with the recipient's details when the email is sent.</p>
            <ul class="space-y-1.5 text-xs">
                @foreach(\App\Support\EmailHtml::MERGE_TAGS as $tag => $label)
                    <li class="flex items-center justify-between gap-2">
                        <code class="text-[#bb976d] bg-[#bb976d]/8 px-1.5 py-0.5 rounded">{{ \App\Support\EmailHtml::tag($tag) }}</code>
                        <span class="text-gray-500">{{ $label }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '.tinymce-editor',
    license_key: 'gpl',
    plugins: 'lists link image table code wordcount',
    toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image | table | removeformat code',
    menubar: false,
    height: 420,
    skin: 'oxide',
    content_css: 'default',
    branding: false,
    promotion: false,
    content_style: "body{font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:24px;color:#3D3A36;} a{color:#8A6A3F;}",
});
</script>
@endpush
