<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'status'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
