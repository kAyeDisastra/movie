<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createPayment(Request $request)
    {
        try {
            $orderId = 'ORDER-' . time() . '-' . Auth::id();
            
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$request->total_amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => 'ticket',
                        'price' => (int)$request->price_per_seat,
                        'quantity' => count($request->seat_ids),
                        'name' => 'Tiket Bioskop - ' . $request->film_title
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            
            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        
        if($hashed == $request->signature_key) {
            if($request->transaction_status == 'settlement') {
                $order = Order::where('order_id', $request->order_id)->first();
                if($order) {
                    // Update payment status
                    Payment::where('order_id', $order->id)
                        ->update(['status' => 'completed', 'payment_time' => now()]);
                    
                    // Update order status
                    $order->update(['status' => 'confirmed']);
                }
            }
        }
        
        return response()->json(['status' => 'success']);
    }
}