<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeOffer extends Model
{
    protected $fillable = ['name', 'is_active', 'starts_at', 'ends_at', 'expiry_hours', 'display_settings'];

    protected $casts = [
        'is_active'        => 'boolean',
        'starts_at'        => 'datetime',
        'ends_at'          => 'datetime',
        'display_settings' => 'array',
    ];

    /** Copy shown in the bar/modal; admin-editable via display_settings. */
    public const DEFAULT_COPY = [
        'bar_text'     => 'New here? Unlock your welcome deal: up to $10 off your first order.',
        'bar_button'   => 'Unlock',
        'modal_title'  => 'Your welcome deal',
        'modal_button' => 'Claim my welcome deal',
        'sms_text'     => 'Text me occasional offers. Msg & data rates may apply; reply STOP to opt out.',
    ];

    public function tiers()
    {
        return $this->hasMany(WelcomeOfferTier::class)->orderBy('sort_order')->orderBy('id');
    }

    public function claims()
    {
        return $this->hasMany(WelcomeClaim::class);
    }

    public function copy(string $key): string
    {
        return $this->display_settings[$key] ?? self::DEFAULT_COPY[$key];
    }

    /** The single offer that is switched on and inside its date window, if any. */
    public static function live(): ?self
    {
        if (! config('checkout.welcome_offer_enabled', true)) {
            return null;
        }

        return static::query()
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->with('tiers')
            ->latest('id')
            ->first();
    }
}
