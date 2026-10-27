<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDocument extends Model
{
    protected $fillable = ['seller_id', 'type', 'path'];

    public function seller() { return $this->belongsTo(Seller::class); }
}
