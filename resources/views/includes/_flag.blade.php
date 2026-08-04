@php
    /**
     * A country flag as inline SVG.
     *
     * Not an emoji: Windows ships no flag glyphs, so a regional-indicator pair
     * renders as the bare letters ("US") in every browser on that platform —
     * which is most desktop shoppers. An inline SVG looks identical everywhere
     * and costs no extra request.
     *
     * Unknown codes render nothing rather than a placeholder box.
     *
     * @var string|null $code  Two-letter country code.
     */
    $code = strtoupper((string) ($code ?? ''));
@endphp

@if ($code === 'US')
    <svg class="pd-flag" viewBox="0 0 20 14" role="img" aria-label="United States" focusable="false">
        <rect width="20" height="14" fill="#fff"/>
        <g fill="#B22234">
            <rect y="0"       width="20" height="1.077"/>
            <rect y="2.154"   width="20" height="1.077"/>
            <rect y="4.308"   width="20" height="1.077"/>
            <rect y="6.462"   width="20" height="1.077"/>
            <rect y="8.615"   width="20" height="1.077"/>
            <rect y="10.769"  width="20" height="1.077"/>
            <rect y="12.923"  width="20" height="1.077"/>
        </g>
        <rect width="8.5" height="7.54" fill="#3C3B6E"/>
        <g fill="#fff">
            <circle cx="1.6" cy="1.3" r=".45"/><circle cx="4.25" cy="1.3" r=".45"/><circle cx="6.9" cy="1.3" r=".45"/>
            <circle cx="2.9" cy="2.6" r=".45"/><circle cx="5.55" cy="2.6" r=".45"/>
            <circle cx="1.6" cy="3.9" r=".45"/><circle cx="4.25" cy="3.9" r=".45"/><circle cx="6.9" cy="3.9" r=".45"/>
            <circle cx="2.9" cy="5.2" r=".45"/><circle cx="5.55" cy="5.2" r=".45"/>
            <circle cx="1.6" cy="6.5" r=".45"/><circle cx="4.25" cy="6.5" r=".45"/><circle cx="6.9" cy="6.5" r=".45"/>
        </g>
    </svg>
@elseif ($code === 'CA')
    <svg class="pd-flag" viewBox="0 0 20 14" role="img" aria-label="Canada" focusable="false">
        <rect width="20" height="14" fill="#fff"/>
        <rect width="5" height="14" fill="#D52B1E"/>
        <rect x="15" width="5" height="14" fill="#D52B1E"/>
        <path fill="#D52B1E" d="M10 3.1l.72 1.42.9-.2-.4 1.5 1.28-.5-.7 1.25 1.4.2-1.1.85.3.55-1.7-.3.1 1.9h-.7l.1-1.9-1.7.3.3-.55-1.1-.85 1.4-.2-.7-1.25 1.28.5-.4-1.5.9.2z"/>
    </svg>
@endif
