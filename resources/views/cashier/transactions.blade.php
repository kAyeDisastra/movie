@extends('layouts.app')
@section('title', 'Riwayat Transaksi - Kasir')

@push('css')
<style>
:root{
    --bg-1: #0b1020;
    --bg-2: #0f1724;
    --accent1: #6c63ff;
    --accent2: #ff6b6b;
    --accent3: #00e0ff;
}
body{background:linear-gradient(180deg,#071021 0%, #07141c 100%);color:#e6eef8;font-family:Inter,system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial;min-height:100vh}
.search-card{
    background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));
    border:1px solid rgba(255,255,255,0.08);border-radius:20px;
    backdrop-filter:blur(10px);box-shadow:0 10px 30px rgba(2,6,23,0.6);
}
.transaction-card{
    background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));
    border:1px solid rgba(255,255,255,0.08);border-radius:20px;
    backdrop-filter:blur(10px);margin-bottom:2rem;padding:1.5rem;
    box-shadow:0 10px 30px rgba(2,6,23,0.6);transition:all .3s ease;
}
.transaction-card:hover{transform:translateY(-2px);box-shadow:0 15px 40px rgba(2,6,23,0.8)}
.card-header{background:rgba(255,255,255,0.03);border-bottom:1px solid rgba(255,255,255,0.1);color:#fff;font-weight:700}
.card-body{color:#e6eef8}
.badge{font-weight:700;padding:.5rem 1rem;border-radius:999px}
.bg-success{background:linear-gradient(90deg,#10b981,#059669)!important}
.bg-warning{background:linear-gradient(90deg,#f59e0b,#d97706)!important}
.bg-danger{background:linear-gradient(90deg,#ef4444,#dc2626)!important}
.btn{border-radius:8px;font-weight:700;transition:all .3s ease}
.btn-primary{background:linear-gradient(90deg,var(--accent1),var(--accent3));border:none}
.form-control{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff!important;border-radius:8px}
input[type="date"]{color-scheme:dark}
.form-control::placeholder{color:rgba(255,255,255,0.6)!important}
.form-control:focus{background:rgba(255,255,255,0.15);border-color:var(--accent3);color:#fff!important;box-shadow:0 0 0 0.2rem rgba(0,224,255,0.25)}
.form-select{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff!important;border-radius:8px}
.form-select option{background:#0f1724;color:#fff}
.form-select:focus{background:rgba(255,255,255,0.15);border-color:var(--accent3);color:#fff!important;box-shadow:0 0 0 0.2rem rgba(0,224,255,0.25)}
.text-muted{color:rgba(255,255,255,0.6)!important}
small{color:rgba(255,255,255,0.7)!important}
.transaction-poster{width:120px;height:180px;border-radius:12px;overflow:hidden;box-shadow:0 8px 25px rgba(2,6,23,0.7);flex-shrink:0;margin-right:1.5rem}
.transaction-poster img{width:100%;height:100%;object-fit:cover}
.transaction-content{flex:1}
h2{color:#fff;font-weight:800}
</style>
@endpush

@section('content')
@include('components.navbar')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Riwayat Transaksi</h2>
        <span class="badge bg-primary fs-6">{{ auth()->user()->name }}</span>
    </div>
    
    <!-- Search & Filter -->
    <div class="search-card p-3 mb-4">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari kode booking, nama customer, no HP, atau judul film..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
        </form>
    </div>
    
    <!-- Transactions -->
    @forelse($transactions as $transaction)
        <div class="transaction-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $transaction->booking_code }}</strong>
                </div>
                <small class="text-muted">{{ $transaction->created_at->format('d M Y, H:i') }}</small>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="transaction-poster">
                        <img src="{{ $transaction->schedule->film->poster_image ? Storage::url($transaction->schedule->film->poster_image) : asset('images/placeholder.svg') }}" alt="{{ $transaction->schedule->film->title ?? 'Film' }}">
                    </div>
                    <div class="transaction-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6>{{ $transaction->schedule->film->title ?? 'Film tidak ditemukan' }}</h6>
                                @if($transaction->customer_name)
                                    <div class="mb-1">
                                        <strong>Customer:</strong> {{ $transaction->customer_name }}
                                        @if($transaction->customer_phone)
                                            <span class="text-muted">| {{ $transaction->customer_phone }}</span>
                                        @endif
                                    </div>
                                @endif
                                <small class="text-muted">
                                    Studio: {{ $transaction->schedule->studio->name ?? '-' }} | 
                                    Kursi: {{ is_array($transaction->seats) ? implode(', ', $transaction->seats) : $transaction->seats }}<br>
                                    <strong>Film:</strong> {{ \Carbon\Carbon::parse($transaction->schedule->show_date)->format('d M Y') }} | 
                                    {{ \Carbon\Carbon::parse($transaction->schedule->show_time)->format('H:i') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <h5>Tidak ada transaksi ditemukan</h5>
            <p class="text-muted">{{ request('search') ? 'Coba kata kunci lain' : 'Belum ada transaksi' }}</p>
        </div>
    @endforelse
    
    <!-- Pagination -->
    @if($transactions->hasPages())
        <div class="d-flex justify-content-center">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection