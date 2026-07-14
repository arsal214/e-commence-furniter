{{--
    Auth text field — label + icon + input + error slot, fully wired for a11y.

    The validation rules are declared on the input itself (required / type /
    minlength) and the shared script in <x-auth.shell> reads them, so adding a
    field never means touching the JS.

    Props:
      name       form field name (also the DOM id)
      label      visible label text
      type       text | email | password | tel …
      icon       mail | lock | user  (inline SVG, stroke 1.5, single family)
      toggle     true → render the show/hide password button
      match      id of a field this one must equal (confirm-password)
      strength   true → render the password strength meter
--}}
@props([
    'name',
    'label',
    'type'         => 'text',
    'icon'         => null,
    'value'        => null,
    'placeholder'  => null,
    'autocomplete' => null,
    'minlength'    => null,
    'required'     => false,
    'autofocus'    => false,
    'toggle'       => false,
    'match'        => null,
    'strength'     => false,
    'full'         => false,   // span both columns inside .pgh-auth__grid
])

<div class="pgh-field @if ($full) pgh-field--full @endif">
    <label class="pgh-field__label" for="{{ $name }}">
        {{ $label }}
        @if ($required)
            <span class="pgh-field__req" aria-hidden="true">*</span>
            <span class="sr-only">(required)</span>
        @endif
    </label>

    <div class="pgh-field__box">
        @if ($icon === 'mail')
            <svg class="pgh-field__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>
            </svg>
        @elseif ($icon === 'lock')
            <svg class="pgh-field__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>
            </svg>
        @elseif ($icon === 'user')
            <svg class="pgh-field__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>
            </svg>
        @endif

        <input
            class="pgh-field__input @if ($toggle) pgh-field__input--toggle @endif"
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            data-validate
            data-label="{{ $label }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if ($minlength) minlength="{{ $minlength }}" @endif
            @if ($match) data-match="{{ $match }}" @endif
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
            @error($name) aria-invalid="true" @enderror
            aria-describedby="{{ $name }}-error"
            {{ $attributes }}
        >

        @if ($toggle)
            <button
                class="pgh-field__toggle"
                type="button"
                data-pw-toggle
                aria-controls="{{ $name }}"
                aria-pressed="false"
                aria-label="Show password"
            >
                <svg class="au-eye-on" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                </svg>
                <svg class="au-eye-off" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10.6 6.2A9.9 9.9 0 0 1 12 6c6.4 0 10 6 10 6a17 17 0 0 1-2.8 3.4M6.6 6.8A17 17 0 0 0 2 12s3.6 7 10 7a9.8 9.8 0 0 0 4.2-.9"/>
                    <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2M2 2l20 20"/>
                </svg>
            </button>
        @endif
    </div>

    @if ($strength)
        {{-- Advisory only — the server rule (min 8) is what actually gates signup --}}
        <div class="pgh-strength" data-strength-for="{{ $name }}" data-score="0">
            <div class="pgh-strength__bars" aria-hidden="true">
                <span class="pgh-strength__bar"></span>
                <span class="pgh-strength__bar"></span>
                <span class="pgh-strength__bar"></span>
                <span class="pgh-strength__bar"></span>
            </div>
            <span class="pgh-strength__label" data-strength-label aria-live="polite">Use at least 8 characters.</span>
        </div>
    @endif

    {{-- One error node per field: the server fills it on reload, the client
         script fills it live. role="alert" so it is announced either way. --}}
    <p class="pgh-field__error @error($name) is-visible @enderror" id="{{ $name }}-error" role="alert">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
        </svg>
        <span data-error-text>@error($name){{ $message }}@enderror</span>
    </p>
</div>
