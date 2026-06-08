<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashDeal extends Model
{
    protected $fillable = [
        'is_active', 'title', 'subtitle', 'discount_label',
        'badge_text', 'ends_at', 'cta_text', 'cta_url', 'bg_color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ends_at'   => 'datetime',
    ];

    public static function current(): self
    {
        return self::firstOrCreate([], [
            'is_active'      => false,
            'title'          => 'Flash Deal',
            'subtitle'       => 'Exclusive savings on premium furniture & home decor.',
            'discount_label' => 'Up to 40% OFF',
            'badge_text'     => 'Limited Time',
            'ends_at'        => null,
            'cta_text'       => 'Shop the Deal',
            'cta_url'        => '/shop',
            'bg_color'       => '#0F1E2E',
        ]);
    }
}
