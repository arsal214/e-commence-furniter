<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointEntry extends Model
{
    protected $fillable = ['seller_id', 'delta', 'reason'];
}
