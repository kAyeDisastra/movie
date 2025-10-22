<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = [
        'title',
        'genre',
        'duration',
        'description',
        'status',
        'poster_image',
        'created_by'
    ];

    protected $casts = [
        'genre' => 'array',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
