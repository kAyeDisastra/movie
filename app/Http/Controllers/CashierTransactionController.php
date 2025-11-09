<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Booking::with(['schedule.film', 'schedule.studio', 'user']);
        
        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('schedule.film', function($filmQuery) use ($search) {
                      $filmQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        // Date filter
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Show all statuses by default
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('cashier.transactions', compact('transactions'));
    }
}