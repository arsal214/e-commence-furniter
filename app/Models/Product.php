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
     * Unknown names fall back to a neutral grey; the name is always shown as text too,
     * so an unmapped colour is never conveyed by the swatch alone.
     */
    public const COLOR_HEX = [
        'beige'        => '#D8C3A5',
        'cream'        => '#EFE6D5',
        'white'        => '#FFFFFF',
        'black'        => '#1A1A1A',
        'charcoal'     => '#36393D',
        'gray'         => '#9AA0A6',
        'grey'         => '#9AA0A6',
        'navy'         => '#1F3352',
        'blue'         => '#3B6FB6',
        'teal'         => '#2F8C86',
        'green'        => '#4F7A4A',
        'red'          => '#B5423A',
        'brown'        => '#7A5A42',
        'tan'          => '#B08A5E',
        'oak'          => '#C89F66',
        'walnut'       => '#5C4033',
        'natural wood' => '#C4A484',
        'gold'         => '#BB976D',
        'silver'       => '#C9CBCD',
    ];

    public static function colorHex(string $name): string
    {
        return self::COLOR_HEX[strtolower(trim($name))] ?? '#9AA0A6';
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
     * Stock available for a colour/size selection, using the same size-over-colour
     * precedence as pricing. Falls back to the product's own stock.
     */
    public function effectiveStockFor(?string $color, ?string $size = null): int
    {
        $variant = $this->variantFor('size', $size) ?? $this->variantFor('color', $color);

        return $variant ? (int) $variant->stock : (int) $this->stock;
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
