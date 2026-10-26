<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeClaim extends Model
{
    protected $fillable = [
        'welcome_offer_id', 'email', 'email_key', 'code', 'visitor_id', 'ip_hash', 'sms_opt_in',
        'claimed_at', 'expires_at', 'used_order_id', 'used_at', 'used_tier_label', 'discount_given',
        'card_fingerprint', 'address_hash',
    ];

    protected $casts = [
        'sms_opt_in' => 'boolean',
        'claimed_at' => 'datetime',
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function offer()
    {
        return $this->belongsTo(WelcomeOffer::class, 'welcome_offer_id');
    }

    public function isUsable(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }
}
