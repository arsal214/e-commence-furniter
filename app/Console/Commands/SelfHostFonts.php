<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Downloads the Google Fonts the site uses and rewrites them as a local
 * stylesheet.
 *
 * Loading fonts from Google costs two extra origins on the critical path:
 * fonts.googleapis.com for the CSS, then fonts.gstatic.com for the files it
 * points at — and the second cannot even start until the first has been fetched
 * and parsed. On mobile that is two DNS lookups plus two TLS handshakes plus a
 * serialised round-trip, which lands squarely in FCP and LCP. Served from our
 * own origin the connection is already open and the files inherit the
 * immutable year-long cache headers in public/.htaccess.
 *
 * Only the latin and latin-ext subsets are kept. Google's CSS also ships
 * devanagari for Poppins and there is no Devanagari text on this store; browsers
 * skip unused subsets via unicode-range anyway, so this only keeps the repo
 * smaller, it does not change what a visitor downloads.
 *
 * font-display: swap comes straight from Google's own output and is asserted
 * below rather than assumed, so text always paints in the fallback immediately.
 */
class SelfHostFonts extends Command
{
    protected $signature = 'fonts:self-host {--subsets=latin,latin-ext : Comma-separated unicode subsets to keep}';

    protected $description = 'Download Google Fonts locally and emit a self-hosted @font-face stylesheet';

    /**
     * Exactly the families and weights the layouts requested from Google, so
     * self-hosting cannot change how anything renders.
     *
     * Manrope and Inter are the site typography system (assets/css/typography.css):
     * Manrope for headings, Inter for body. Manrope stops at 800, which is the
     * heaviest weight the scale asks for, so nothing is synthesised.
     *
     * Poppins and Josefin Sans stay listed even though the unified system has
     * replaced them. Their files are already on disk so regenerating costs no
     * downloads, and an unused @font-face is never fetched by the browser — but
     * dropping them would 404 any inline stack still naming them, and there are
     * ~40 of those scattered through the older templates.
     */
    private const FAMILIES = [
        'Manrope' => 'family=Manrope:wght@400;500;600;700;800',
        'Inter' => 'family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400',
        'Poppins' => 'family=Poppins:wght@300;400;500;600;700',
        'Josefin Sans' => 'family=Josefin+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700',
    ];

    /** A modern UA is required or Google returns legacy TTF instead of woff2. */
    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    public function handle(): int
    {
        $subsets = array_filter(array_map('trim', explode(',', $this->option('subsets'))));

        $fontDir = public_path('assets/fonts');

        if (! is_dir($fontDir) && ! mkdir($fontDir, 0755, true) && ! is_dir($fontDir)) {
            $this->error("Could not create {$fontDir}");

            return self::FAILURE;
        }

        $css = [];
        $downloaded = 0;
        $bytes = 0;
        $skipped = 0;

        foreach (self::FAMILIES as $family => $query) {
            $url = "https://fonts.googleapis.com/css2?{$query}&display=swap";

            $response = Http::withHeaders(['User-Agent' => self::UA])->timeout(30)->get($url);

            if (! $response->successful()) {
                $this->error("Failed to fetch CSS for {$family} (HTTP {$response->status()})");

                return self::FAILURE;
            }

            $source = $response->body();

            if (! str_contains($source, 'font-display: swap')) {
                $this->error("Google returned CSS without font-display:swap for {$family}; aborting.");

                return self::FAILURE;
            }

            // Each @font-face is preceded by a /* subset */ comment.
            preg_match_all('/\/\*\s*([a-z0-9\-\[\]]+)\s*\*\/\s*(@font-face\s*\{[^}]+\})/i', $source, $matches, PREG_SET_ORDER);

            if ($matches === []) {
                $this->error("Could not parse any @font-face blocks for {$family}.");

                return self::FAILURE;
            }

            foreach ($matches as [, $subset, $block]) {
                if (! in_array($subset, $subsets, true)) {
                    $skipped++;
                    continue;
                }

                if (! preg_match('/src:\s*url\((https:\/\/fonts\.gstatic\.com\/[^)]+\.woff2)\)/', $block, $src)) {
                    continue;
                }

                $remote = $src[1];

                $weight = preg_match('/font-weight:\s*(\d+)/', $block, $w) ? $w[1] : '400';
                $style = str_contains($block, 'font-style: italic') ? 'italic' : 'normal';

                $name = sprintf(
                    '%s-%s-%s-%s.woff2',
                    strtolower(str_replace(' ', '-', $family)),
                    $weight,
                    $style,
                    $subset
                );

                $target = $fontDir.DIRECTORY_SEPARATOR.$name;

                if (! is_file($target)) {
                    $file = Http::withHeaders(['User-Agent' => self::UA])->timeout(30)->get($remote);

                    if (! $file->successful()) {
                        $this->error("Failed to download {$remote}");

                        return self::FAILURE;
                    }

                    file_put_contents($target, $file->body());
                    $downloaded++;
                }

                $bytes += filesize($target);

                // Point src at our own origin, leaving every other descriptor
                // (font-display, unicode-range, weight, style) exactly as Google
                // generated it.
                $css[] = str_replace($remote, "../fonts/{$name}", $block);
            }
        }

        if ($css === []) {
            $this->error('No @font-face blocks matched the requested subsets.');

            return self::FAILURE;
        }

        $header = "/* Generated by `php artisan fonts:self-host` — do not edit by hand. */\n";
        $out = public_path('assets/css/fonts.css');
        file_put_contents($out, $header.implode("\n", $css)."\n");

        $this->info(sprintf(
            '%d @font-face rules -> assets/css/fonts.css (%d files downloaded, %s KB on disk, %d off-subset rules skipped)',
            count($css),
            $downloaded,
            number_format($bytes / 1024),
            $skipped
        ));

        return self::SUCCESS;
    }
}
