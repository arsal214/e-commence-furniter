<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = ['order_id', 'seller_id', 'status', 'carrier', 'tracking_number', 'shipped_at', 'auto_deliver_at', 'delivered_at', 'gross', 'fee', 'net', 'release_at', 'payout_id'];

    protected $casts = [
        'shipped_at' => 'datetime', 'auto_deliver_at' => 'datetime', 'delivered_at' => 'datetime', 'release_at' => 'datetime',
        'gross' => 'decimal:2', 'fee' => 'decimal:2', 'net' => 'decimal:2',
    ];

    public function order()  { return $this->belongsTo(Order::class); }
    public function seller() { return $this->belongsTo(Seller::class); }
    public function payout() { return $this->belongsTo(Payout::class); }

    /** The order lines this parcel is made of. */
    public function lines()
    {
        return OrderItem::where('order_id', $this->order_id)->where('seller_id', $this->seller_id);
    }

    public function openDispute(): ?Dispute
    {
        return Dispute::where('order_id', $this->order_id)->where('seller_id', $this->seller_id)->where('status', 'open')->first();
    }
}
