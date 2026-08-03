<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'city', 'state', 'zip', 'country',
        'address', 'address2', 'notes', 'payment_method',
        'payment_status', 'stripe_payment_intent', 'shipping',
        'subtotal', 'shipping_cost', 'total', 'status',
        'tracking_number', 'supplier_name', 'supplier_order_id',
        'supplier_tracking', 'carrier', 'shipped_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
