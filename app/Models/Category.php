<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Category extends Model
{
    /** Cache key for the nav/footer category list. */
    private const NAV_CACHE_KEY = 'categories.navigation';
    protected $fillable = ['name', 'slug', 'description', 'meta_title', 'meta_description', 'image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Any write invalidates the nav list, so an admin edit shows up on the
        // storefront immediately rather than after the TTL expires.
        static::saved(fn () => static::forgetNavigation());
        static::deleted(fn () => static::forgetNavigation());
    }

    /**
     * The active category list rendered in the navbar and the footer.
     *
     * Both includes previously ran this identical query independently, so every
     * page on the site paid for it twice. It is memoised per request (one query
     * at most) and cached across requests, since categories change only when an
     * admin edits them — and those writes bust the cache above.
     */
    public static function navigation(): Collection
    {
        static $memo = null;

        if ($memo !== null) {
            return $memo;
        }

        return $memo = Cache::remember(
            self::NAV_CACHE_KEY,
            now()->addHours(6),
            fn () => static::where('is_active', true)->orderBy('name')->get()
        );
    }

    public static function forgetNavigation(): void
    {
        Cache::forget(self::NAV_CACHE_KEY);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Storefront counts should ignore products the admin has deactivated.
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }
}
