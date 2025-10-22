<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'payment_method',
        'total_amount',
        'status',
        'payment_time'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
