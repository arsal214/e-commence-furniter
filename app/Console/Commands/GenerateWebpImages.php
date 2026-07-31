<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Generates a .webp twin beside every JPEG/PNG under public/.
 *
 * The naming is deliberate: foo.jpg becomes foo.jpg.webp, not foo.webp. That
 * keeps the twin addressable from the original URL by simply appending an
 * extension, which is exactly what the rewrite in public/.htaccess does, and it
 * cannot collide with the handful of images that were already authored as WebP.
 *
 * Nothing references the twins directly — if this command never runs, or a file
 * fails to convert, the rewrite's -f check misses and the original JPEG is
 * served as before.
 */
class GenerateWebpImages extends Command
{
    protected $signature = 'images:webp
        {--path=assets/img : Directory under public/ to walk}
        {--quality=82 : WebP encoder quality, 0-100}
        {--max-width=0 : Downscale anything wider than this (0 disables)}
        {--force : Rebuild twins that are already up to date}';

    protected $description = 'Generate WebP twins for JPEG/PNG images so .htaccess can serve them';

    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('GD is missing WebP support — cannot generate twins.');

            return self::FAILURE;
        }

        $root = public_path($this->option('path'));

        if (! is_dir($root)) {
            $this->error("Not a directory: {$root}");

            return self::FAILURE;
        }

        $quality = max(0, min(100, (int) $this->option('quality')));
        $maxWidth = (int) $this->option('max-width');

        $converted = $skipped = $failed = 0;
        $bytesBefore = $bytesAfter = 0;

        foreach ($this->images($root) as $file) {
            $source = $file->getPathname();
            $target = $source.'.webp';

            // Up to date already: the twin exists and is newer than its source.
            if (! $this->option('force') && is_file($target) && filemtime($target) >= $file->getMTime()) {
                $skipped++;
                continue;
            }

            $result = $this->convert($source, $target, $quality, $maxWidth);

            if ($result === null) {
                $failed++;
                $this->line("  <fg=red>fail</> {$this->relative($source)}");
                continue;
            }

            // A WebP that came out bigger than the JPEG is worse than useless —
            // it would be served in preference to the smaller original. Drop it
            // so the rewrite's -f check misses and the original wins.
            if ($result >= $file->getSize()) {
                @unlink($target);
                $skipped++;
                continue;
            }

            $converted++;
            $bytesBefore += $file->getSize();
            $bytesAfter += $result;
        }

        $saved = $bytesBefore - $bytesAfter;

        $this->newLine();
        $this->info(sprintf(
            '%d converted, %d skipped, %d failed — %s KiB saved (%s%% smaller)',
            $converted,
            $skipped,
            $failed,
            number_format($saved / 1024),
            $bytesBefore > 0 ? number_format($saved / $bytesBefore * 100, 1) : '0'
        ));

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /** @return iterable<SplFileInfo> */
    private function images(string $root): iterable
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png'], true)) {
                yield $file;
            }
        }
    }

    /**
     * @return int|null Bytes written, or null if the image could not be converted.
     */
    private function convert(string $source, string $target, int $quality, int $maxWidth): ?int
    {
        // A 3 MB PNG decodes to width*height*4 bytes in memory, which is far more
        // than the file size suggests. Skip anything that would not fit rather
        // than let GD take the process down with it.
        if (! $this->fitsInMemory($source)) {
            return null;
        }

        $image = @imagecreatefromstring(file_get_contents($source));

        if ($image === false) {
            return null;
        }

        try {
            if ($maxWidth > 0 && imagesx($image) > $maxWidth) {
                $height = (int) round(imagesy($image) * ($maxWidth / imagesx($image)));
                $resized = imagescale($image, $maxWidth, $height);

                if ($resized !== false) {
                    imagedestroy($image);
                    $image = $resized;
                }
            }

            // PNG logos and product cut-outs rely on their alpha channel; without
            // this the transparent areas encode as black.
            imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);

            if (! @imagewebp($image, $target, $quality)) {
                return null;
            }
        } finally {
            imagedestroy($image);
        }

        clearstatcache(true, $target);

        return is_file($target) ? filesize($target) : null;
    }

    private function fitsInMemory(string $source): bool
    {
        $size = @getimagesize($source);

        if ($size === false) {
            return false;
        }

        $limit = $this->memoryLimitBytes();

        if ($limit < 0) {
            return true; // unlimited
        }

        // 4 bytes per pixel for the truecolor canvas, doubled to leave room for
        // the resize copy and the encoder's own buffers.
        $needed = $size[0] * $size[1] * 4 * 2;

        return $needed < ($limit - memory_get_usage(true));
    }

    private function memoryLimitBytes(): int
    {
        $limit = trim((string) ini_get('memory_limit'));

        if ($limit === '' || $limit === '-1') {
            return -1;
        }

        $unit = strtolower(substr($limit, -1));
        $value = (int) $limit;

        return match ($unit) {
            'g' => $value * 1024 ** 3,
            'm' => $value * 1024 ** 2,
            'k' => $value * 1024,
            default => $value,
        };
    }

    private function relative(string $path): string
    {
        return str_replace(public_path().DIRECTORY_SEPARATOR, '', $path);
    }
}
