<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardEntry extends Model
{
    protected $fillable = ['user_id', 'delta', 'reason', 'order_id', 'shipment_id', 'kind'];
}
