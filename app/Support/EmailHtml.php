<?php

namespace App\Support;

/**
 * Helpers for the admin-authored HTML that goes into promotional emails.
 *
 * The body comes from TinyMCE in the admin panel, so the author is trusted —
 * but the output is still stripped of anything that has no business in an email
 * and would only get the message flagged or mangled by a mail client.
 */
class EmailHtml
{
    /** Tags that are dropped along with their contents. */
    private const STRIP_TAGS = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'link', 'meta', 'base'];

    /** Personalisation placeholders offered on the compose screen, tag => label. */
    public const MERGE_TAGS = [
        'first_name' => 'First name',
        'name'       => 'Full name',
        'email'      => 'Email',
        'store'      => 'Store name',
    ];

    /**
     * A merge tag in its written form.
     *
     * Built by concatenation rather than written out: the literal braces are
     * Blade's echo syntax, and a view that contained them would be compiled
     * instead of printed.
     */
    public static function tag(string $name): string
    {
        return '{' . '{' . $name . '}' . '}';
    }

    /**
     * The merge tags with the values this recipient would actually get, for the
     * reference panel on the compose screen.
     *
     * @return array<int, array{tag: string, label: string, value: string}>
     */
    public static function mergePreview(string $name, string $email): array
    {
        $resolved = self::resolveTags($name, $email);

        return array_map(fn (string $tag, string $label) => [
            'tag'   => self::tag($tag),
            'label' => $label,
            'value' => $resolved[$tag] !== '' ? $resolved[$tag] : '(nothing on file)',
        ], array_keys(self::MERGE_TAGS), array_values(self::MERGE_TAGS));
    }

    /**
     * Remove executable and non-email markup from an editor body.
     */
    public static function sanitize(?string $html): string
    {
        $html = (string) $html;

        if (trim($html) === '') {
            return '';
        }

        foreach (self::STRIP_TAGS as $tag) {
            // Paired form first, then any stray self-closing/unclosed opener.
            $html = preg_replace('#<' . $tag . '\b[^>]*>.*?</' . $tag . '\s*>#is', '', $html);
            $html = preg_replace('#</?' . $tag . '\b[^>]*>#i', '', $html);
        }

        // Inline event handlers: onclick="…", onerror='…', onload=…
        $html = preg_replace('#\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);

        // javascript:/vbscript:/data: URLs in href and src.
        $html = preg_replace('#\s(href|src)\s*=\s*("|\')\s*(javascript|vbscript|data)\s*:[^"\']*\2#i', ' $1="#"', $html);

        return trim($html);
    }

    /**
     * Substitute the merge tags offered on the compose screen.
     *
     * Unknown tags are left as typed rather than blanked, so a typo is visible
     * in the preview instead of silently producing an empty sentence.
     *
     * @param  array<string, string>  $extra  Additional tag => value pairs.
     */
    public static function merge(string $text, string $name, string $email, array $extra = []): string
    {
        foreach (array_merge(self::resolveTags($name, $email), $extra) as $tag => $value) {
            // Tolerate the spacing people actually type: {{name}} and {{ name }}.
            $text = preg_replace('/\{\{\s*' . preg_quote($tag, '/') . '\s*\}\}/', str_replace('$', '\$', (string) $value), $text);
        }

        return $text;
    }

    /**
     * @return array<string, string>
     */
    protected static function resolveTags(string $name, string $email): array
    {
        $first = trim(strtok(trim($name), ' ') ?: '');

        return [
            'first_name' => $first !== '' ? $first : $name,
            'name'       => $name,
            'email'      => $email,
            'store'      => (string) config('app.name'),
        ];
    }

    /**
     * A plain-text fallback for an HTML body, so the message is not HTML-only.
     */
    public static function toPlainText(string $html): string
    {
        $text = preg_replace('#<(br|/p|/div|/h[1-6]|/li|/tr)\s*/?>#i', "\n", $html);
        $text = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace("/[ \t]+/", ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }

    /**
     * The signed unsubscribe URL for a marketing recipient.
     *
     * Signed rather than tokenised: the link only has to prove it came from us,
     * and a signature avoids adding a column and a lookup for every subscriber.
     */
    public static function unsubscribeUrl(string $email): string
    {
        return \URL::signedRoute('marketing.unsubscribe', ['email' => $email]);
    }
}
