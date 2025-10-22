<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_date',
        'total'
    ];
}
