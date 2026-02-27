<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'review_content', 'shipping_info',
        'price', 'sale_price', 'image', 'tag', 'is_featured', 'is_active', 'stock', 'sku',
        'colors', 'sizes', 'size_chart',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'colors'      => 'array',
        'sizes'       => 'array',
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
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Returns a display-friendly price string (e.g. "$122.75" or "$122.75 - $140.99")
     */
    public function getDisplayPriceAttribute(): string
    {
        $main = '$' . number_format($this->price, 2);
        if ($this->sale_price) {
            return $main . ' - $' . number_format($this->sale_price, 2);
        }
        return $main;
    }

    /**
     * Whether the product has an original price crossed out (sale_price is the discounted one)
     */
    public function getHasStrikeAttribute(): bool
    {
        return !is_null($this->sale_price);
    }
}
