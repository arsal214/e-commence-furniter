<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Generates narrow width variants of the hero (LCP) image.
 *
 * The source art is only 577x433, so this can only ever produce *smaller*
 * variants — a 2x variant would be an upscale, which costs bytes and adds no
 * detail. That matters for the srcset: on a 412px phone the image renders at
 * roughly 384 CSS px, so a DPR-2 device already wants ~768px and will pick the
 * 577w original regardless. The narrow variants exist for DPR-1 phones, small
 * viewports and data-saver modes, where they cut the LCP download meaningfully.
 *
 * Kept as a command rather than a one-off script so the variants can be
 * regenerated after an art change without hand-running an image editor.
 */
class GenerateResponsiveHero extends Command
{
    protected $signature = 'images:responsive
        {--widths=320,448 : Comma-separated widths to emit}
        {--quality=82 : WebP encoder quality}';

    protected $description = 'Generate narrow width variants of the hero LCP image for srcset';

    /** Hero slide art, relative to public/. */
    private const SOURCES = [
        'assets/img/home-v1/home-decor.webp',
        'assets/img/home-v1/electronics.webp',
    ];

    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('GD is missing WebP support.');

            return self::FAILURE;
        }

        $widths = array_filter(array_map('intval', explode(',', $this->option('widths'))));
        $quality = max(0, min(100, (int) $this->option('quality')));

        foreach (self::SOURCES as $relative) {
            $source = public_path($relative);

            if (! is_file($source)) {
                $this->error("Missing {$relative}");

                return self::FAILURE;
            }

            $image = @imagecreatefromwebp($source);

            if ($image === false) {
                $this->error("Could not decode {$relative}");

                return self::FAILURE;
            }

            $nativeWidth = imagesx($image);

            foreach ($widths as $width) {
                if ($width >= $nativeWidth) {
                    $this->line("  skip {$width}w — not smaller than the {$nativeWidth}w source");
                    continue;
                }

                $height = (int) round(imagesy($image) * ($width / $nativeWidth));
                $resized = imagescale($image, $width, $height);

                if ($resized === false) {
                    $this->error("Resize to {$width}w failed for {$relative}");
                    imagedestroy($image);

                    return self::FAILURE;
                }

                imagealphablending($resized, false);
                imagesavealpha($resized, true);

                $target = $this->variantPath($source, $width);

                if (! @imagewebp($resized, $target, $quality)) {
                    $this->error("Could not write {$target}");
                    imagedestroy($resized);
                    imagedestroy($image);

                    return self::FAILURE;
                }

                imagedestroy($resized);
                clearstatcache(true, $target);

                $this->line(sprintf(
                    '  %s  %dx%d  %s KB',
                    basename($target),
                    $width,
                    $height,
                    number_format(filesize($target) / 1024, 1)
                ));
            }

            imagedestroy($image);
        }

        $this->info('Responsive hero variants generated.');

        return self::SUCCESS;
    }

    private function variantPath(string $source, int $width): string
    {
        return preg_replace('/\.webp$/', "-{$width}w.webp", $source);
    }
}
