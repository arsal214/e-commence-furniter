{{--
    Account form field. Validation rules live on the input (required / type /
    minlength / match / required-with) and the shell's script reads them.

    Props:
      icon          user | mail | lock
      toggle        show/hide password button
      match         id of the field this must equal
      requiredWith  id of a field that, once filled, makes this one required
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
    'toggle'       => false,
    'match'        => null,
    'requiredWith' => null,
    'full'         => false,
])

<div class="acc-field @if ($full) acc-field--full @endif">
    <label class="acc-field__label" for="{{ $name }}">
        {{ $label }}
        @if ($required)
            <span class="acc-field__req" aria-hidden="true">*</span>
            <span class="acc-sr">(required)</span>
        @endif
    </label>

    <div class="acc-field__box">
        @if ($icon === 'user')
            <svg class="acc-field__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
        @elseif ($icon === 'mail')
            <svg class="acc-field__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></svg>
        @elseif ($icon === 'lock')
            <svg class="acc-field__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
        @endif

        <input
            class="acc-field__input @if ($toggle) acc-field__input--toggle @endif"
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
            @if ($requiredWith) data-required-with="{{ $requiredWith }}" @endif
            @if ($required) required @endif
            @error($name) aria-invalid="true" @enderror
            aria-describedby="{{ $name }}-error"
            {{ $attributes }}
        >

        @if ($toggle)
            <button class="acc-field__toggle" type="button" data-pw-toggle aria-controls="{{ $name }}" aria-pressed="false" aria-label="Show password">
                <svg class="acc-eye-on" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg class="acc-eye-off" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.6 6.2A9.9 9.9 0 0 1 12 6c6.4 0 10 6 10 6a17 17 0 0 1-2.8 3.4M6.6 6.8A17 17 0 0 0 2 12s3.6 7 10 7a9.8 9.8 0 0 0 4.2-.9"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2M2 2l20 20"/></svg>
            </button>
        @endif
    </div>

    <p class="acc-field__error @error($name) is-visible @enderror" id="{{ $name }}-error" role="alert">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <span data-error-text>@error($name){{ $message }}@enderror</span>
    </p>
</div>
