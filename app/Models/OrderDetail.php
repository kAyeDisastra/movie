<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'seat_id'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
