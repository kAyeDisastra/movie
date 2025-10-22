<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'period',
        'total_income',
        'total_expense',
        'owner_id'
    ];
}
