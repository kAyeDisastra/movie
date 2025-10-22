<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'schedule_id',
        'seat_code',
        'status'
    ];
    
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
