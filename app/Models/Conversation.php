<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['buyer_id', 'seller_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /** Messages the other side has written that this side has not opened yet. */
    public function unreadFor(string $side)
    {
        return $this->messages()->where('from', '!=', $side)->whereNull('read_at');
    }
}
