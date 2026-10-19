<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bundle extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'is_active', 'starts_at', 'ends_at'];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Bundle $b) {
            $b->slug = $b->slug ?: Str::slug($b->name);
        });
    }

    public function items()
    {
        return $this->hasMany(BundleItem::class);
    }

    /** Switched on and inside its (optional) date window. */
    public function scopeLive($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()));
    }

    /** What the parts cost bought separately, today. */
    public function regularTotal(): float
    {
        return round($this->items->sum(fn (BundleItem $i) => $i->unitPrice() * $i->qty), 2);
    }

    /** Honest saving: positive only when the set really is cheaper than the parts. */
    public function savings(): float
    {
        return max(0.0, round($this->regularTotal() - (float) $this->price, 2));
    }

    /** How many sets can be sold right now: limited by the scarcest component. 0 = not purchasable. */
    public function availableQty(): int
    {
        if ($this->items->isEmpty()) {
            return 0;
        }

        return (int) $this->items->map(fn (BundleItem $i) => $i->available())->min();
    }

    public function isPurchasable(): bool
    {
        return $this->is_active
            && (! $this->starts_at || $this->starts_at->isPast())
            && (! $this->ends_at || $this->ends_at->isFuture())
            && $this->availableQty() > 0;
    }

    public function image(): ?string
    {
        return $this->items->first()?->product?->image;
    }

    /** Live, purchasable bundles that contain any of these products, biggest saving first. */
    public static function offeredFor(iterable $productIds, int $limit = 1)
    {
        $ids = collect($productIds)->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return collect();
        }

        return static::live()
            ->whereHas('items', fn ($q) => $q->whereIn('product_id', $ids))
            ->with('items.product.variants', 'items.variant')
            ->get()
            ->filter(fn (Bundle $b) => $b->isPurchasable() && $b->savings() > 0)
            ->sortByDesc(fn (Bundle $b) => $b->savings())
            ->take($limit)
            ->values();
    }
}
