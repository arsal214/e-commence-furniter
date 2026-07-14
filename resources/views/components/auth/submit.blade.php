{{--
    Primary auth button. The shared script flips aria-busy on submit, which
    swaps the label for the spinner and blocks a second click.
--}}
@props([
    'label'   => 'Continue',
    'loading' => 'Please wait…',
])

<button class="pgh-submit" type="submit" data-submit {{ $attributes }}>
    <svg class="pgh-submit__spinner" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
        <path d="M12 3a9 9 0 1 0 9 9" />
    </svg>
    <span class="pgh-submit__label">{{ $label }}</span>

    {{-- Announced to screen readers the moment the button enters its busy state --}}
    <span class="sr-only" aria-live="polite">
        <span hidden data-loading-text>{{ $loading }}</span>
    </span>
</button>
