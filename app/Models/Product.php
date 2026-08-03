<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'brand', 'slug', 'meta_title', 'meta_description',
        'description', 'key_features', 'review_content', 'shipping_info',
        'price', 'sale_price', 'image', 'image_color', 'tag', 'is_featured', 'is_best_seller', 'is_active', 'stock', 'sku',
        'gtin', 'mpn', 'specifications',
        'colors', 'sizes', 'size_chart',
        'supplier_name', 'supplier_url', 'supplier_sku',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'is_featured'    => 'boolean',
        'is_best_seller' => 'boolean',
        'is_active'      => 'boolean',
        'colors'      => 'array',
        'sizes'       => 'array',
        'specifications' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
        static::updating(function ($product) {
            // Only auto-regenerate slug when name changes AND slug was not explicitly set
            if ($product->isDirty('name') && !$product->isDirty('slug') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Swatch colour for a colour *name* — `colors` stores names ("Charcoal"), not hex.
     * Admins type these freely, so the map can never be complete; colorHex() layers
     * modifier stripping and word matching on top of it before giving up. The name is
     * always shown as text too, so an unmapped colour is never conveyed by the swatch alone.
     */
    public const COLOR_HEX = [
        // Neutrals
        'white'        => '#FFFFFF',
        'off white'    => '#F4F1EA',
        'ivory'        => '#F5F0E1',
        'cream'        => '#EFE6D5',
        'beige'        => '#D8C3A5',
        'sand'         => '#DCC9A6',
        'linen'        => '#E6DED1',
        'taupe'        => '#8B7D6F',
        'greige'       => '#B5AA9B',
        'khaki'        => '#B3A369',
        'camel'        => '#C19A6B',
        'stone'        => '#A8A29A',
        'gray'         => '#9AA0A6',
        'grey'         => '#9AA0A6',
        'silver'       => '#C9CBCD',
        'slate'        => '#5A6672',
        'graphite'     => '#43474C',
        'charcoal'     => '#36393D',
        'gunmetal'     => '#2C3539',
        'black'        => '#1A1A1A',
        'onyx'         => '#141414',
        'pearl'        => '#EAE6DF',
        // Metals
        'gold'         => '#BB976D',
        'rose gold'    => '#B76E79',
        'brass'        => '#B5A642',
        'antique brass'=> '#9C7C38',
        'bronze'       => '#8C7853',
        'copper'       => '#B87333',
        'chrome'       => '#C6C9CC',
        'nickel'       => '#B5B7BA',
        'steel'        => '#8A9199',
        'pewter'       => '#8E8E90',
        // Woods
        'oak'          => '#C89F66',
        'light oak'    => '#D9BE95',
        'natural wood' => '#C4A484',
        'natural'      => '#C4A484',
        'maple'        => '#D6B98C',
        'ash'          => '#C7BBA6',
        'teak'         => '#A9743F',
        'cherry'       => '#8C3A2B',
        'mahogany'     => '#804030',
        'walnut'       => '#5C4033',
        'wenge'        => '#3E2B23',
        'espresso'     => '#3B2A21',
        'coffee'       => '#4B3621',
        'chocolate'    => '#5A3A22',
        'tan'          => '#B08A5E',
        'brown'        => '#7A5A42',
        'rust'         => '#A64B2B',
        'terracotta'   => '#C56B4A',
        // Colours
        'red'          => '#B5423A',
        'burgundy'     => '#6E1B2A',
        'maroon'       => '#6B2029',
        'wine'         => '#722F37',
        'pink'         => '#D98BA0',
        'blush'        => '#E3B7B0',
        'rose'         => '#C97A83',
        'coral'        => '#E1705C',
        'orange'       => '#D4763C',
        'peach'        => '#EFB68F',
        'apricot'      => '#E8A56B',
        'yellow'       => '#D9B44A',
        'mustard'      => '#C4952B',
        'lime'         => '#9BB63C',
        'olive'        => '#6F7343',
        'sage'         => '#9AA88A',
        'green'        => '#4F7A4A',
        'forest'       => '#2E4A32',
        'emerald'      => '#2E7D5B',
        'mint'         => '#A7D5BE',
        'teal'         => '#2F8C86',
        'turquoise'    => '#3EAFA8',
        'aqua'         => '#6FC3C0',
        'sky'          => '#8FBEDD',
        'blue'         => '#3B6FB6',
        'royal blue'   => '#2A4FA8',
        'navy'         => '#1F3352',
        'midnight blue'=> '#16233C',
        'denim'        => '#4A6A94',
        'indigo'       => '#3B3B7A',
        'purple'       => '#6B4E8C',
        'plum'         => '#6B3A5B',
        'lavender'     => '#B0A2CB',
        'lilac'        => '#C0AFD4',
        'violet'       => '#7A57A8',
        'multicolor'   => '#BB976D',
        'transparent'  => '#E8EDF0',
        'clear'        => '#E8EDF0',
    ];

    /** Words that qualify a base colour rather than name one: "Dark Blue", "Matte Black". */
    private const COLOR_MODIFIERS = [
        'dark'    => -22, 'deep'    => -22, 'midnight' => -34, 'jet'   => -34,
        'light'   =>  24, 'pale'    =>  30, 'soft'     =>  22, 'baby'  =>  30,
        'bright'  =>  10, 'vintage' => -10, 'antique'  => -10, 'muted' =>  12,
        'matte'   =>   0, 'gloss'   =>   0, 'glossy'   =>   0, 'solid' =>   0,
        'metallic'=>   0, 'brushed' =>   0, 'polished' =>   0, 'satin' =>   0,
        'warm'    =>   0, 'cool'    =>   0, 'classic'  =>   0, 'rich'  => -10,
    ];

    /**
     * Best-effort hex for a free-text colour name. Resolution order:
     *   1. a literal hex the admin typed ("#B87333")
     *   2. the exact name          ("copper", "rose gold")
     *   3. name minus modifiers, shaded  ("dark blue" -> blue, darkened 22%)
     *   4. any single word that maps     ("brushed copper finish" -> copper)
     * Only a name that matches nothing falls back to neutral grey.
     */
    public static function colorHex(string $name): string
    {
        $raw = strtolower(trim($name));
        if ($raw === '') {
            return '#9AA0A6';
        }

        // 1. literal hex, with or without the leading #, 3 or 6 digits
        if (preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/', $raw, $m)) {
            $hex = $m[1];
            if (strlen($hex) === 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }

            return '#' . strtoupper($hex);
        }

        // Normalise separators and noise words so "Dark-Blue" == "dark blue"
        $clean = preg_replace('/[^a-z ]+/', ' ', str_replace(['-', '_', '/'], ' ', $raw));
        $clean = trim(preg_replace('/\s+/', ' ', $clean));

        // 2. exact match on the whole name
        if (isset(self::COLOR_HEX[$clean])) {
            return self::COLOR_HEX[$clean];
        }

        $words = $clean === '' ? [] : explode(' ', $clean);

        // 3. strip modifiers, then match the remainder and apply their shade
        $shade = 0;
        $base  = [];
        foreach ($words as $word) {
            if (array_key_exists($word, self::COLOR_MODIFIERS)) {
                $shade += self::COLOR_MODIFIERS[$word];
            } else {
                $base[] = $word;
            }
        }

        // 4. the longest run of words that maps, so "royal blue velvet" resolves to
        //    "royal blue" rather than plain "blue"
        for ($len = count($base); $len >= 1; $len--) {
            for ($start = 0; $start + $len <= count($base); $start++) {
                $phrase = implode(' ', array_slice($base, $start, $len));
                if (isset(self::COLOR_HEX[$phrase])) {
                    return self::shade(self::COLOR_HEX[$phrase], $shade);
                }
            }
        }

        return '#9AA0A6';
    }

    /** Lighten (positive) or darken (negative) a hex colour by a percentage. */
    private static function shade(string $hex, int $percent): string
    {
        if ($percent === 0) {
            return $hex;
        }

        $out = '#';
        foreach ([1, 3, 5] as $offset) {
            $channel = hexdec(substr($hex, $offset, 2));
            $channel = $percent > 0
                ? $channel + (255 - $channel) * ($percent / 100)
                : $channel * (1 + $percent / 100);
            $out .= str_pad(dechex((int) round(max(0, min(255, $channel)))), 2, '0', STR_PAD_LEFT);
        }

        return strtoupper($out);
    }

    /**
     * Swatches for the product's `colors` list: name, hex, and the photo that colour
     * should show. Image preference: the colour's own variant photo, then a gallery
     * image tagged with that colour, then the main photo when it is tagged. Null means
     * "no photo for this colour" — the card keeps whatever it is already showing.
     */
    public function colorSwatches(): array
    {
        $variantImages = $this->variants
            ->where('type', 'color')
            ->where('is_active', true)
            ->filter(fn ($v) => filled($v->image))
            ->keyBy(fn ($v) => strtolower(trim($v->value)));

        $galleryImages = $this->productImages
            ->filter(fn ($i) => filled($i->color))
            ->keyBy(fn ($i) => strtolower(trim($i->color)));

        $mainColor = filled($this->image_color) ? strtolower(trim($this->image_color)) : null;

        return collect($this->colors ?? [])->filter()->values()->map(function ($name) use ($variantImages, $galleryImages, $mainColor) {
            $key   = strtolower(trim($name));
            $image = $variantImages->get($key)->image
                  ?? $galleryImages->get($key)->image
                  ?? ($mainColor === $key ? $this->image : null);

            return [
                'name'  => $name,
                'hex'   => self::colorHex($name),
                'image' => $image ? self::imageUrl($image) : null,
            ];
        })->all();
    }

    /** Public URL for a stored image path, tolerating the seeded `assets/...` paths. */
    public static function imageUrl(string $path): string
    {
        return str_starts_with($path, 'assets/')
            ? asset($path)
            : \Illuminate\Support\Facades\Storage::url($path);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    /**
     * The active variant for an option (case-insensitive) in a given dimension
     * ('color' or 'size'), or null when there's no matching/active variant.
     * Selecting an option with no variant falls back to the product's own price.
     */
    public function variantFor(string $type, ?string $value): ?ProductVariant
    {
        if (!$value) {
            return null;
        }

        return $this->variants->first(fn ($v) => $v->is_active
            && $v->type === $type
            && strcasecmp((string) $v->value, $value) === 0);
    }

    /** True when the product has at least one active, priced variant (any dimension). */
    public function getHasVariantsAttribute(): bool
    {
        return $this->variants->contains(fn ($v) => $v->is_active);
    }

    /**
     * Effective price for a colour/size selection. Colour and size are priced
     * independently; when both have a variant, SIZE takes precedence (it's the
     * more specific physical spec). Falls back to the product's own price when
     * neither option has a variant. Single source of truth shared by the product
     * page, cart-add, and checkout so they can never disagree.
     */
    public function effectivePriceFor(?string $color, ?string $size = null): float
    {
        return $this->variantFor('size', $size)?->effective_price
            ?? $this->variantFor('color', $color)?->effective_price
            ?? $this->effective_price;
    }

    /**
     * Stock available for a colour/size selection.
     *
     * Deliberately does NOT use the size-over-colour precedence that pricing
     * uses. Price can reasonably have a winner; availability cannot. A selection
     * is only available if EVERY dimension of it is, so this takes the minimum
     * across each dimension that has a matching variant.
     *
     * The old first-match version returned the size variant's stock and never
     * looked at the colour, so picking an out-of-stock colour on a product that
     * also had sizes reported the size's stock — the page showed "In Stock" and
     * the colour could be sold while it had none.
     *
     * Falls back to the product's own stock when no dimension has a variant.
     */
    public function effectiveStockFor(?string $color, ?string $size = null): int
    {
        $stocks = collect([
            $this->variantFor('size', $size),
            $this->variantFor('color', $color),
        ])->filter()->map(fn ($v) => (int) $v->stock);

        return $stocks->isNotEmpty() ? (int) $stocks->min() : (int) $this->stock;
    }

    /**
     * Lowest effective price across active variants (the "From $X" figure), or the
     * product's own effective price when there are no variants.
     */
    public function getFromPriceAttribute(): float
    {
        $prices = $this->variants
            ->filter(fn ($v) => $v->is_active)
            ->map(fn ($v) => $v->effective_price);

        return $prices->isNotEmpty() ? (float) $prices->min() : $this->effective_price;
    }

    /**
     * True when price varies by option — i.e. active variants exist and their
     * prices (together with the base) aren't all identical. Listings use this to
     * decide whether to show a single price or a "From $X" figure.
     */
    public function getHasPriceRangeAttribute(): bool
    {
        $prices = $this->variants
            ->filter(fn ($v) => $v->is_active)
            ->map(fn ($v) => $v->effective_price);

        if ($prices->isEmpty()) {
            return false;
        }

        return $prices->push($this->effective_price)->unique()->count() > 1;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function avgRating(): float
    {
        // Use pre-loaded aggregate if available (set via withAvg), otherwise query
        return round((float) ($this->reviews_avg_rating ?? $this->reviews()->avg('rating') ?? 0), 1);
    }

    public function reviewCount(): int
    {
        return (int) ($this->reviews_count ?? $this->reviews()->count());
    }

    /**
     * The price a customer actually pays: sale_price when discounted, else price.
     * Single source of truth for the discount rule — everything that charges or
     * displays a price should go through this rather than re-deriving it.
     */
    public function getEffectivePriceAttribute(): float
    {
        $sale = $this->sale_price === null ? null : (float) $this->sale_price;

        return $sale !== null && $sale > 0 ? $sale : (float) $this->price;
    }

    /**
     * The effective price, formatted (e.g. "$122.75").
     *
     * Views pair this with a struck-through {{ $product->price }} when
     * has_strike is true, so it must be the *current* price, not a range.
     */
    public function getDisplayPriceAttribute(): string
    {
        return '$' . number_format($this->effective_price, 2);
    }

    /**
     * The original price to strike through, or null when nothing is discounted.
     */
    public function getWasPriceAttribute(): ?string
    {
        return $this->has_strike ? '$' . number_format($this->price, 2) : null;
    }

    /**
     * Whether price should be shown crossed out (sale_price is the discounted one).
     */
    public function getHasStrikeAttribute(): bool
    {
        return $this->effective_price < (float) $this->price;
    }
}
