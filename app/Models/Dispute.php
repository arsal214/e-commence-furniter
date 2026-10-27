<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = ['order_id', 'seller_id', 'user_id', 'email', 'stripe_refund_id', 'reason', 'details', 'amount', 'status', 'resolution_note', 'resolved_at', 'seller_note', 'seller_declined_at'];

    protected $casts = ['amount' => 'decimal:2', 'resolved_at' => 'datetime', 'seller_declined_at' => 'datetime'];

    public function order()  { return $this->belongsTo(Order::class); }
    public function seller() { return $this->belongsTo(Seller::class); }
    public function user()   { return $this->belongsTo(User::class); }
}
