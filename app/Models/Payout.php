<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = ['seller_id', 'amount', 'status', 'release_at', 'paid_at', 'stripe_transfer_id', 'failure_reason'];

    protected $casts = ['amount' => 'decimal:2', 'release_at' => 'datetime', 'paid_at' => 'datetime'];

    public function seller() { return $this->belongsTo(Seller::class); }
    public function items()  { return $this->hasMany(OrderItem::class); }
}
