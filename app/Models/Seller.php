<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Seller extends Model
{
    public const SUBSCRIPTION_PRICE = 19.99;

    /** Delivered-sales thresholds (checked top-down) and the payout hold in days each level earns. */
    public const LEVELS = [
        'elite'  => ['min' => 50000, 'hold' => 0],
        'top'    => ['min' => 10000, 'hold' => 1],
        'rising' => ['min' => 1000,  'hold' => 2],
        'new'    => ['min' => 0,     'hold' => 3],
    ];

    protected $fillable = [
        'user_id', 'store_name', 'slug', 'business_name', 'business_type', 'ein', 'state', 'business_phone', 'address', 'city', 'zip',
        'main_category', 'owner_name', 'owner_title', 'owner_email', 'owner_phone', 'owner_dob', 'owner_ssn_last4', 'owner_address',
        'onboarding_step', 'submitted_at', 'logo', 'about', 'status', 'admin_note', 'approved_at',
        'level', 'delivered_sales', 'points', 'subscription_status', 'stripe_customer_id', 'stripe_subscription_id', 'subscription_event_at',
        'referral_code', 'referred_by', 'referral_credited_at', 'sale_percent', 'sale_active', 'ads_active', 'ads_daily_budget',
        'stripe_account_id', 'handling_days', 'return_window_days', 'return_shipping_payer', 'free_shipping_over',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'subscription_event_at' => 'datetime',
        'referral_credited_at' => 'datetime',
        'sale_active' => 'boolean',
        'ads_active' => 'boolean',
        'ads_daily_budget' => 'decimal:2',
        'owner_dob' => 'encrypted',
        'owner_ssn_last4' => 'encrypted',
        'delivered_sales' => 'decimal:2',
        'free_shipping_over' => 'decimal:2',
    ];

    /** Sign-up steps in order. A seller can open any step up to the one they have reached. */
    public const STEPS = ['verify', 'plan', 'business', 'owner', 'bank', 'done'];

    protected static function booted(): void
    {
        static::creating(function (Seller $s) {
            $s->uuid ??= Str::upper((string) Str::uuid());
        });
    }

    /** The store's public address: /stores/{slug}/page/{uuid}. The uuid, not the row id, identifies it. */
    public function url(): string
    {
        return route('store.show', [$this->slug, $this->uuid]);
    }

    public function coupons()
    {
        return $this->hasMany(SellerCoupon::class);
    }

    public function stepIndex(): int
    {
        return (int) array_search($this->onboarding_step, self::STEPS, true);
    }

    /** Move forward only; going back through a form never rewinds progress. */
    public function advanceTo(string $step): void
    {
        if (array_search($step, self::STEPS, true) > $this->stepIndex()) {
            $this->update(['onboarding_step' => $step]);
        }
    }

    public static function levelFor(float $deliveredSales): string
    {
        foreach (self::LEVELS as $name => $rule) {
            if ($deliveredSales >= $rule['min']) {
                return $name;
            }
        }
        return 'new';
    }

    public function holdDays(): int
    {
        return self::LEVELS[$this->level]['hold'] ?? 3;
    }

    /** Buyers see a store only when it is approved AND its $19.99 subscription is current. */
    public function isLive(): bool
    {
        return $this->status === 'approved' && $this->subscription_status === 'active';
    }

    public function scopeLive($query)
    {
        return $query->where('sellers.status', 'approved')->where('sellers.subscription_status', 'active');
    }

    public function user()       { return $this->belongsTo(User::class); }
    public function products()   { return $this->hasMany(Product::class); }
    public function documents()  { return $this->hasMany(SellerDocument::class); }
    public function payouts()    { return $this->hasMany(Payout::class); }
    public function disputes()   { return $this->hasMany(Dispute::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
}
