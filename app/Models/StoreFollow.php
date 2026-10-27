<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreFollow extends Model
{
    protected $fillable = ['user_id', 'seller_id'];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
