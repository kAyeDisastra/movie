<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Order::where('user_id', Auth::id())
            ->with(['orderDetails.seat.schedule.film', 'orderDetails.seat.schedule.studio', 'payment', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('transactions', compact('transactions'));
    }
}