<?php

namespace App\Support;

use App\Models\Product;
use App\Models\SiteText;
use App\Services\ShippingService;
use Illuminate\Support\Str;

/**
 * Site wording and SEO text from one place: config/site.php defaults, overridden by
 * whatever was saved on Admin -> Site Text & SEO.
 */
class SiteCopy
{
    private static ?array $overrides = null;

    /** Value for a key, tokens filled. Unknown key returns ''. */
    public static function get(string $key): string
    {
        $value = self::overrides()[$key] ?? null;
        $value = filled($value) ? $value : (string) (config('site.copy')[$key]['default'] ?? '');

        return self::fill($value);
    }

    /** Replace {shipping} and {delivery}; safe on any string (category/product meta too). */
    public static function fill(string $text): string
    {
        if (! str_contains($text, '{')) {
            return $text;
        }

        return strtr($text, [
            '{shipping}' => app(ShippingService::class)->shortPromo(),
            '{delivery}' => \App\Support\DeliveryEstimate::minDays() . '–' . \App\Support\DeliveryEstimate::maxDays(),
        ]);
    }

    /** Append the brand exactly once, whatever the stored title already contains. */
    public static function brandOnce(string $title): string
    {
        $brand   = config('site.brand', 'PeytonGhalib');
        $trailer = '/(\s*[|\-–—]\s*' . preg_quote($brand, '/') . ')+\s*$/iu';
        $title   = self::fill($title);
        $bare    = trim(preg_replace($trailer, '', $title));

        if ($bare === '') {
            return $brand;
        }

        // A title that already names the brand somewhere else ("About PeytonGhalib | Gifts...") is left alone.
        if (! preg_match($trailer, $title) && mb_stripos($bare, $brand) !== false) {
            return $bare;
        }

        return $bare . ' | ' . $brand;
    }

    /** Title for a static page key (meta.<key>.title); $suffix goes before the brand (e.g. " — Page 2"). */
    public static function title(string $pageKey, string $suffix = ''): string
    {
        $title = self::get("meta.$pageKey.title");
        $bare  = trim(preg_replace('/\s*\|\s*' . preg_quote(config('site.brand', 'PeytonGhalib'), '/') . '\s*$/iu', '', $title));

        return self::brandOnce($bare . $suffix);
    }

    public static function description(string $pageKey, string $suffix = ''): string
    {
        return self::get("meta.$pageKey.description") . $suffix;
    }

    /** Category pages: the category's own meta, else the slug's default, else a generic line. */
    public static function categoryTitle($category): string
    {
        $own = $category->meta_title ?: self::get("meta.category.{$category->slug}.title");

        return self::brandOnce($own !== '' ? $own : 'Shop ' . $category->name);
    }

    public static function categoryDescription($category): string
    {
        $own = $category->meta_description ?: self::get("meta.category.{$category->slug}.description");

        return self::fill($own !== '' ? $own : 'Shop ' . $category->name . ' at PeytonGhalib. {shipping} and 30-day returns.');
    }

    /** Which admin tab (one per page) a copy key belongs to. */
    public static function tabFor(string $key): string
    {
        $tabs = config('site.tabs');

        if (str_starts_with($key, 'meta.category.')) {
            return 'categories';
        }
        if (preg_match('/^meta\.([a-z\-]+)\.(title|description)$/', $key, $m) && isset($tabs[$m[1]])) {
            return $m[1];
        }

        $prefix = explode('.', $key)[0];

        return isset($tabs[$prefix]) ? $prefix : 'site';
    }

    /** Cut at a word boundary, never mid-word, with trailing punctuation removed. */
    public static function limit(string $text, int $max): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text));
        if (mb_strlen($text) <= $max) {
            return $text;
        }

        $cut = mb_substr($text, 0, $max + 1);
        $cut = mb_substr($cut, 0, max(1, (int) mb_strrpos($cut, ' ')));

        return rtrim($cut, " ,;:-–—|/&(");
    }

    /** "{short product name} | PeytonGhalib", 60 characters at most, cut at a word boundary. */
    public static function productTitle(Product $product): string
    {
        $brand = config('site.brand', 'PeytonGhalib');
        $name  = trim(preg_replace('/\s*\|\s*' . preg_quote($brand, '/') . '\s*$/iu', '', (string) ($product->meta_title ?: $product->name)));
        $room  = 60 - mb_strlen(' | ' . $brand);

        return self::limit($name !== '' ? $name : 'Product', $room) . ' | ' . $brand;
    }

    /** Meta description of 120-155 characters from the product's own summary. */
    public static function productDescription(Product $product): string
    {
        if (filled($product->meta_description)) {
            return self::limit(self::fill(strip_tags($product->meta_description)), 155);
        }

        $summary = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $product->description), ENT_QUOTES | ENT_HTML5)));
        if ($summary === '') {
            $summary = 'Shop ' . $product->name . ' at ' . config('site.brand', 'PeytonGhalib') . '.';
        }
        if (! preg_match('/[.!?]$/u', $summary) && mb_strlen($summary) <= 155) {
            $summary .= '.';
        }

        // Short summaries get the shipping/returns promise appended so the snippet is not thin.
        if (mb_strlen($summary) < 120) {
            $summary .= ' ' . self::fill('{shipping} and 30-day returns.');
        }

        $out = self::limit($summary, 155);

        return preg_match('/[.!?]$/u', $out) ? $out : $out . '.';
    }

    private static function overrides(): array
    {
        if (self::$overrides !== null) {
            return self::$overrides;
        }

        // A missing table (migration not run yet) must never take the storefront down.
        try {
            return self::$overrides = SiteText::pluck('value', 'key')->all();
        } catch (\Throwable $e) {
            return self::$overrides = [];
        }
    }

    public static function forget(): void
    {
        self::$overrides = null;
    }
}
