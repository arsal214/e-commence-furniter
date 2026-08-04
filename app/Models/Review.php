<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'product_reviews';

    protected $fillable = [
        'product_id', 'user_id', 'reviewer_name', 'rating', 'title', 'comment',
        'image', 'country', 'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Avatar palette. Chosen by name hash so a given reviewer keeps the same
     * colour across renders — a colour that changes on refresh reads as noise.
     */
    private const AVATAR_COLORS = [
        '#8E7CC3', '#6AA84F', '#E06666', '#3D85C6',
        '#F6B26B', '#76A5AF', '#C27BA0', '#A64D79',
    ];

    /**
     * The store ships to the US, so a review has no country question to answer —
     * it is stamped here rather than asked for on any form. Done in one place so
     * admin-written and customer-written reviews cannot disagree, and left
     * overridable so an existing value (or a future second market) survives.
     */
    protected static function booted(): void
    {
        static::creating(function (self $review) {
            $review->country ??= config('checkout.default_country', 'US');
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Name shown for a review: the linked customer's name, else the admin-supplied
     * reviewer_name, else a neutral fallback. Single source of truth for review authorship.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name ?: ($this->reviewer_name ?: 'Customer');
    }

    /** First letter of the author's name, for the avatar circle. */
    public function getInitialAttribute(): string
    {
        return mb_strtoupper(mb_substr(trim($this->author_name), 0, 1)) ?: '?';
    }

    public function getAvatarColorAttribute(): string
    {
        $index = crc32(mb_strtolower($this->author_name)) % count(self::AVATAR_COLORS);

        return self::AVATAR_COLORS[$index];
    }

    /**
     * Public URL for the review photo. Mirrors the product-image convention:
     * a path under assets/ is a bundled file, anything else is an upload.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return str_starts_with($this->image, 'assets/')
            ? asset($this->image)
            : \Storage::url($this->image);
    }

    /**
     * Whether this reviewer actually bought this product.
     *
     * Matched on the account when there is one, otherwise on nothing at all —
     * an admin-authored review has no order behind it and must not be labelled
     * a verified purchase just because it shares a name with a customer.
     */
    public static function hasPurchased(?int $userId, int $productId): bool
    {
        if (! $userId) {
            return false;
        }

        return \App\Models\OrderItem::where('product_id', $productId)
            ->whereHas('order', fn ($q) => $q->where('user_id', $userId)->where('payment_status', 'paid'))
            ->exists();
    }
}
