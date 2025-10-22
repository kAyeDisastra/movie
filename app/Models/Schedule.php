<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'film_id',
        'studio_id',
        'price_id',
        'show_time',
        'show_date',
        'created_by'
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
