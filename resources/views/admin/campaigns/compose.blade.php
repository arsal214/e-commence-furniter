@extends('admin.layouts.app')

@section('title', 'Send Offer')
@section('page-title', 'Send Offer')

@section('content')

@php
    // Both the preview and the send action need to know who is being mailed.
    $recipientParam = $type === 'user' ? ['user' => $recipient->id] : ['subscriber' => $recipient->id];
@endphp

<div class="mb-5 flex flex-wrap items-center gap-3">
    <a href="{{ route('admin.customers.index', $type === 'subscriber' ? ['tab' => 'subscribers'] : []) }}"
       class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
        <i class="mdi mdi-arrow-left"></i> Back to customers
    </a>
</div>

@if ($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
        <p class="font-medium mb-1">Please fix the following:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

@if ($optedOut)
    <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm flex items-start gap-2">
        <i class="mdi mdi-alert text-amber-600 mt-0.5"></i>
        <span>{{ $email }} has unsubscribed from offers. You can still preview and send yourself a test, but the send button is disabled.</span>
    </div>
@endif

<form method="POST" action="{{ route('admin.campaigns.send', $recipientParam) }}" id="composeForm">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ── Composer ──────────────────────────────────────────────── --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Template picker: fills the whole form in one click. --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between gap-3 mb-2">
                    <label for="templatePicker" class="block text-sm font-medium text-gray-700">Start from a template</label>
                    <a href="{{ route('admin.email-templates.index') }}" class="text-xs text-[#bb976d] hover:underline">Manage templates</a>
                </div>
                <div class="flex flex-wrap gap-2">
                    <select id="templatePicker"
                            class="flex-1 min-w-[220px] text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] bg-white text-gray-800">
                        <option value="">— Blank email —</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="applyTemplate"
                            class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        Load
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-2">Loading a template replaces everything below. Edit freely afterwards — nothing is sent until you press Send.</p>
            </div>

            {{-- Subject + headline --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required maxlength="180"
                           placeholder="e.g. @{{first_name}}, 20% off our new sofa collection"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="eyebrow" class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                        <input type="text" name="eyebrow" id="eyebrow" value="{{ old('eyebrow') }}" maxlength="60"
                               placeholder="Limited Time Offer"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                        <p class="text-xs text-gray-400 mt-1">Small gold line above the headline.</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="heading" class="block text-sm font-medium text-gray-700 mb-1">Headline</label>
                        <input type="text" name="heading" id="heading" value="{{ old('heading') }}" maxlength="120"
                               placeholder="20% off everything this week"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                        <p class="text-xs text-gray-400 mt-1">Leave both blank to open straight with your message.</p>
                    </div>
                </div>
            </div>

            {{-- Rich text body --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <label for="body_html" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                <p class="text-xs text-gray-400 mb-3">
                    @php $greetingName = $name ? \Illuminate\Support\Str::before($name, ' ') : null; @endphp
                    @if($greetingName)
                        The greeting (&ldquo;Hi {{ $greetingName }},&rdquo;) and the store header, footer and unsubscribe link are added automatically.
                    @else
                        The store header, footer and unsubscribe link are added automatically. There is no name on file for this address, so no greeting line is added — open with one yourself.
                    @endif
                </p>
                <div class="flex flex-wrap gap-1.5 mb-2">
                    <span class="text-xs text-gray-500 self-center mr-1">Insert:</span>
                    @foreach($mergeTags as $merge)
                        <button type="button" data-merge-tag="{{ $merge['tag'] }}"
                                class="px-2 py-1 text-xs rounded border border-gray-200 text-gray-600 hover:border-[#bb976d] hover:text-[#bb976d] transition-colors">{{ $merge['label'] }}</button>
                    @endforeach
                </div>
                <textarea name="body_html" id="body_html" class="tinymce-editor">{{ old('body_html') }}</textarea>
            </div>

            {{-- Offer extras --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
                <h3 class="text-sm font-semibold text-gray-800">Offer options</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="promo_code" class="block text-sm font-medium text-gray-700 mb-1">Discount code</label>
                        <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code') }}" maxlength="40"
                               placeholder="SUMMER20"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d] uppercase">
                        <p class="text-xs text-gray-400 mt-1">Shown in a boxed panel. Leave blank to hide it.</p>
                    </div>
                    <div>
                        <label for="promo_note" class="block text-sm font-medium text-gray-700 mb-1">Code caption</label>
                        <input type="text" name="promo_note" id="promo_note" value="{{ old('promo_note') }}" maxlength="160"
                               placeholder="Valid until 30 September. One use per customer."
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label for="cta_label" class="block text-sm font-medium text-gray-700 mb-1">Button label</label>
                        <input type="text" name="cta_label" id="cta_label" value="{{ old('cta_label') }}" maxlength="40"
                               placeholder="Shop the Sale"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="cta_url" class="block text-sm font-medium text-gray-700 mb-1">Button link</label>
                        <input type="url" name="cta_url" id="cta_url" value="{{ old('cta_url') }}" maxlength="500" list="quickLinks"
                               placeholder="{{ url('/shop') }}"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#bb976d]">
                        <datalist id="quickLinks">
                            @foreach($quickLinks as $link)
                                <option value="{{ $link['url'] }}">{{ $link['label'] }}</option>
                            @endforeach
                        </datalist>
                        <p class="text-xs text-gray-400 mt-1">Pick a store page from the list, or paste any link. Label and link go together — fill both or neither.</p>
                    </div>
                </div>
            </div>

            {{-- Preview, rendered by the server into an iframe so the email's own
                 CSS cannot bleed into the admin panel. --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between gap-3 px-5 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">Preview</h3>
                    <button type="submit" id="previewBtn"
                            formaction="{{ route('admin.campaigns.preview', $recipientParam) }}"
                            formtarget="previewFrame" formnovalidate
                            class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-700 hover:border-[#bb976d] hover:text-[#bb976d] transition-colors">
                        <i class="mdi mdi-eye-outline mr-1"></i> Refresh preview
                    </button>
                </div>
                <div id="previewWrap" class="hidden bg-[#F4F0EA]">
                    <iframe name="previewFrame" id="previewFrame" title="Email preview"
                            class="w-full h-[720px] border-0 bg-[#F4F0EA]"></iframe>
                </div>
                <p id="previewHint" class="px-5 py-8 text-center text-sm text-gray-400">
                    Press “Refresh preview” to see the email exactly as {{ $email }} will receive it.
                </p>
            </div>
        </div>

        {{-- ── Sidebar ───────────────────────────────────────────────── --}}
        <div class="space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-400 font-medium mb-3">Sending to</p>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#bb976d]/15 text-[#bb976d] flex items-center justify-center font-bold flex-shrink-0">
                        {{ strtoupper(substr($name ?: $email, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        @if($name)<p class="font-medium text-gray-800 truncate">{{ $name }}</p>@endif
                        <p class="text-sm text-gray-500 break-all">{{ $email }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $type === 'user' ? 'Registered account' : 'Newsletter subscriber' }}
                        </p>
                    </div>
                </div>
                @if($type === 'user')
                <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-gray-100 text-center">
                    <div>
                        <p class="text-lg font-bold text-gray-800">{{ $recipient->orders()->count() }}</p>
                        <p class="text-xs text-gray-400">Orders</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-800">${{ number_format((float) $recipient->orders()->sum('total'), 0) }}</p>
                        <p class="text-xs text-gray-400">Spent</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
                <button type="submit" id="sendBtn"
                        class="w-full px-4 py-3 bg-[#bb976d] text-white text-sm font-semibold rounded-lg hover:bg-[#a8845a] transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                        {{ $optedOut ? 'disabled' : '' }}>
                    <i class="mdi mdi-send mr-1"></i> Send to {{ $name ?: $email }}
                </button>
                <button type="submit" name="test" value="1"
                        class="w-full px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-[#bb976d] hover:text-[#bb976d] transition-colors">
                    <i class="mdi mdi-email-check-outline mr-1"></i> Send test to me
                </button>
                <p class="text-xs text-gray-400 text-center">
                    The test goes to {{ auth()->user()->email }} and does not count as an offer to this customer.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-400 font-medium mb-3">Merge tags</p>
                <p class="text-xs text-gray-500 mb-3">Usable in the subject, headline, caption and message. They are replaced when the email is sent.</p>
                <ul class="space-y-1.5 text-xs">
                    @foreach($mergeTags as $merge)
                        <li class="flex items-center justify-between gap-2">
                            <code class="text-[#bb976d] bg-[#bb976d]/8 px-1.5 py-0.5 rounded">{{ $merge['tag'] }}</code>
                            <span class="text-gray-500 truncate" title="{{ $merge['value'] }}">{{ $merge['value'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($recentOffers->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs uppercase tracking-wide text-gray-400 font-medium mb-3">Offers already sent</p>
                <ul class="space-y-2.5">
                    @foreach($recentOffers as $offer)
                        <li class="text-xs">
                            <p class="text-gray-700 truncate" title="{{ $offer->subject }}">{{ $offer->subject }}</p>
                            <p class="text-gray-400">
                                {{ optional($offer->sent_at ?? $offer->created_at)->format('d M Y, H:i') }}
                                @if($offer->failed())<span class="text-red-500 font-medium">· failed</span>@endif
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</form>

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
    // The body is dropped into an email shell that supplies its own type styles,
    // so the editor is shown with the same base as the rendered message.
    content_style: "body{font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:24px;color:#3D3A36;} a{color:#8A6A3F;}",
});

(function () {
    var templates = @json($templates->mapWithKeys(fn ($t) => [$t->id => $t->toFormPayload()]));

    function setValue(id, value) {
        var el = document.getElementById(id);
        if (el) el.value = value || '';
    }

    document.getElementById('applyTemplate').addEventListener('click', function () {
        var id = document.getElementById('templatePicker').value;
        if (!id || !templates[id]) return;

        var t = templates[id];
        setValue('subject', t.subject);
        setValue('eyebrow', t.eyebrow);
        setValue('heading', t.heading);
        setValue('cta_label', t.cta_label);
        setValue('cta_url', t.cta_url);
        setValue('promo_code', t.promo_code);
        setValue('promo_note', t.promo_note);

        var editor = tinymce.get('body_html');
        if (editor) editor.setContent(t.body_html || '');
    });

    // Merge-tag buttons write into the editor, or into whichever text input the
    // cursor was last in — otherwise a tag meant for the subject line has to be
    // typed by hand.
    var lastField = null;
    ['subject', 'eyebrow', 'heading', 'promo_note'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('focus', function () { lastField = el; });
    });

    document.querySelectorAll('[data-merge-tag]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tag = btn.getAttribute('data-merge-tag');

            if (lastField) {
                var start = lastField.selectionStart ?? lastField.value.length;
                var end   = lastField.selectionEnd ?? lastField.value.length;
                lastField.value = lastField.value.slice(0, start) + tag + lastField.value.slice(end);
                lastField.focus();
                lastField.setSelectionRange(start + tag.length, start + tag.length);
                return;
            }

            var editor = tinymce.get('body_html');
            if (editor) {
                editor.insertContent(tag);
                editor.focus();
            }
        });
    });

    // The preview posts the live form into the iframe; reveal the frame the
    // first time so an empty grey box is never on screen.
    document.getElementById('previewBtn').addEventListener('click', function () {
        var editor = tinymce.get('body_html');
        if (editor) editor.save(); // formnovalidate skips TinyMCE's own submit sync

        document.getElementById('previewWrap').classList.remove('hidden');
        document.getElementById('previewHint').classList.add('hidden');
    });

    // Guard against a double-click firing two sends — each press is a real email.
    document.getElementById('composeForm').addEventListener('submit', function (e) {
        if (e.submitter && e.submitter.id === 'previewBtn') return;

        var btn = e.submitter;
        if (btn) {
            setTimeout(function () { btn.disabled = true; }, 0);
        }
    });
})();
</script>
@endpush
@endsection
