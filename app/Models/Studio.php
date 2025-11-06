<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'capacity',
        'created_by'
    ];

    public function seats()
    {
        return $this->hasManyThrough(Seat::class, Schedule::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
