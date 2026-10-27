<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/** Checks an upload against config('images.slots.<slot>'): real image, ratio, minimum size. */
class ImageSpec implements ValidationRule
{
    public function __construct(protected string $slot) {}

    public static function hint(string $slot): string
    {
        $s = config("images.slots.{$slot}");

        return "{$s['ratio'][0]}:{$s['ratio'][1]} picture, at least {$s['min'][0]}×{$s['min'][1]} px · JPG, PNG or WebP up to 4 MB";
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $spec = config("images.slots.{$this->slot}");
        $label = $spec['label'];

        // getimagesize reads the real header, so a renamed file cannot pass as an image.
        $info = $value instanceof UploadedFile ? @getimagesize($value->getRealPath()) : false;
        if (! $info || ! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            $fail("{$label} must be a JPG, PNG or WebP image.");

            return;
        }

        [$w, $h] = $info;
        $max = config('images.max_side');
        if ($w > $max || $h > $max) {
            $fail("{$label} is too large ({$w}×{$h} px). Keep each side under {$max} px.");

            return;
        }
        $want = $spec['ratio'][0] / $spec['ratio'][1];
        if (abs(($w / $h) / $want - 1) > config('images.tolerance')) {
            $fail("{$label} must be a {$spec['ratio'][0]}:{$spec['ratio'][1]} picture. Yours is {$w}×{$h}.");

            return;
        }

        // Ratio is checked first: a wrong-shaped photo is not fixed by making it bigger.
        if ($w < $spec['min'][0] || $h < $spec['min'][1]) {
            $fail("{$label} must be at least {$spec['min'][0]}×{$spec['min'][1]} px. Yours is {$w}×{$h}.");

            return;
        }
    }
}
