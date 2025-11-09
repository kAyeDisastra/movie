<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Auto expire pending bookings that are over 1 minute old
        \App\Models\Booking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subMinute())
            ->update(['status' => 'expired']);
            
        $query = \App\Models\Booking::where('user_id', Auth::id())
            ->with(['schedule.film', 'schedule.studio']);
            
        $status = $request->status ?? 'confirmed';
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
            
        return view('transactions', compact('transactions', 'status'));
    }
    
    public function delete($id)
    {
        $booking = \App\Models\Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'expired')
            ->first();
            
        if ($booking) {
            $booking->delete();
            return back()->with('success', 'Transaksi kadaluarsa berhasil dihapus');
        }
        
        return back()->with('error', 'Transaksi tidak ditemukan atau tidak dapat dihapus');
    }
}