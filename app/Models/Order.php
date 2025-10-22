<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'schedule_id',
        'order_time',
        'status',
        'cashier_id'
    ];
    
    public $timestamps = false;
    
    protected $dates = ['created_at'];
    
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
