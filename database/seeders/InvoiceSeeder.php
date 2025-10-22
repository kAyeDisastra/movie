<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $payments = Payment::where('status', 'completed')->get();

        $invoiceData = [];
        foreach ($payments as $payment) {
            $invoiceData[] = [
                'order_id' => $payment->order_id,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($payment->order_id, 4, '0', STR_PAD_LEFT),
                'invoice_date' => $payment->payment_time,
                'total' => $payment->total_amount,
            ];
        }
        
        if (!empty($invoiceData)) {
            Invoice::insert($invoiceData);
        }
    }
}