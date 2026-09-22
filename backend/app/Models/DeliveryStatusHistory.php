<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryStatusHistory extends Model
{
    public $timestamps = false;
    protected $fillable = ['delivery_id', 'changed_by', 'from_status', 'to_status', 'reason', 'created_at'];
}
