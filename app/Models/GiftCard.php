<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    protected $fillable = ['code', 'amount', 'balance', 'status', 'buyer_id', 'owner_id', 'recipient_email', 'message', 'stripe_session_id', 'delivered_at'];

    protected $casts = ['amount' => 'decimal:2', 'balance' => 'decimal:2', 'delivered_at' => 'datetime'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
