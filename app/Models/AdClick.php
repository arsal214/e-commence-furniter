<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdClick extends Model
{
    protected $fillable = ['seller_id', 'product_id', 'cost', 'billed_at'];

    protected $casts = ['billed_at' => 'datetime', 'cost' => 'decimal:2'];
}
