<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'schedule_id', 
        'invoice_number',
        'invoice_date',
        'seats', // JSON array kursi yang dibeli
        'total_amount',
        'payment_status',
        'payment_method',
        'midtrans_order_id',
        'midtrans_transaction_id'
    ];
    
    protected $casts = [
        'invoice_date' => 'datetime',
        'seats' => 'array' // Cast ke array untuk JSON
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    // Generate nomor invoice otomatis
    public static function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('invoice_date', now())->count() + 1;
        return 'INV-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
