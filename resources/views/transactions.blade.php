@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

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
.btn-group .btn{border-radius:999px!important;margin-right:.5rem;font-weight:700;transition:all .3s ease}
.btn-success{background:linear-gradient(90deg,#10b981,#059669);border:none}
.btn-warning{background:linear-gradient(90deg,#f59e0b,#d97706);border:none}
.btn-danger{background:linear-gradient(90deg,#ef4444,#dc2626);border:none}
.btn-primary{background:linear-gradient(90deg,var(--accent1),var(--accent3));border:none}
.btn-outline-success{border:2px solid #10b981;color:#10b981}
.btn-outline-warning{border:2px solid #f59e0b;color:#f59e0b}
.btn-outline-danger{border:2px solid #ef4444;color:#ef4444}
.btn-outline-primary{border:2px solid var(--accent1);color:var(--accent1)}
h3{color:#fff;font-weight:800;margin-bottom:2rem}
.text-muted{color:rgba(255,255,255,0.6)!important}
small{color:rgba(255,255,255,0.7)!important}
strong{color:#fff!important}
h6{color:#fff!important}
.alert-warning{background:rgba(245,158,11,0.2);border:1px solid rgba(245,158,11,0.3);color:#fbbf24!important}
.transaction-poster{width:120px;height:180px;border-radius:12px;overflow:hidden;box-shadow:0 8px 25px rgba(2,6,23,0.7);flex-shrink:0;margin-right:1.5rem}
.transaction-poster img{width:100%;height:100%;object-fit:cover}
.transaction-content{flex:1}
</style>
@endpush

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <div class="my-4">
                <h3 class="mb-3">Riwayat Transaksi</h3>
                <div class="btn-group" role="group">
                    <a href="{{ route('transactions', ['status' => 'confirmed']) }}" class="btn {{ $status === 'confirmed' ? 'btn-success' : 'btn-outline-success' }} rounded-pill me-2">Berhasil</a>
                    <a href="{{ route('transactions', ['status' => 'pending']) }}" class="btn {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill me-2">Menunggu Pembayaran</a>
                    <a href="{{ route('transactions', ['status' => 'expired']) }}" class="btn {{ $status === 'expired' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill me-2">Kadaluarsa</a>
                    <a href="{{ route('transactions', ['status' => 'all']) }}" class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill">Semua</a>
                </div>
            </div>
            
            @forelse($transactions as $transaction)
                <div class="transaction-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Order #{{ $transaction->id }}</strong>
                            <span class="badge bg-{{ $transaction->status == 'confirmed' ? 'success' : ($transaction->status == 'expired' ? 'danger' : 'warning') }}">
                                {{ $transaction->status == 'confirmed' ? 'Pembayaran Berhasil' : ($transaction->status == 'expired' ? 'Kadaluarsa' : 'Menunggu Pembayaran') }}
                            </span>
                        </div>
                        <small class="text-muted">Transaksi: {{ isset($transaction->payment_date) ? \Carbon\Carbon::parse($transaction->payment_date)->format('d M Y, H:i') : \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <div class="transaction-poster">
                                <img src="{{ $transaction->schedule->film->poster_image ? Storage::url($transaction->schedule->film->poster_image) : asset('images/placeholder.svg') }}" alt="{{ $transaction->schedule->film->title ?? 'Film' }}">
                            </div>
                            <div class="transaction-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
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
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Total Pembayaran</strong>
                            </div>
                            <div class="col-md-4 text-end">
                                <strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @if($transaction->status === 'expired')
                        <hr>
                        <div class="row">
                            <div class="col-12 text-end">
                                <form action="{{ route('transactions.delete', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    <h5>Belum ada transaksi</h5>
                    <p>Anda belum melakukan pemesanan tiket</p>
                </div>
            @endforelse
        </div>
    </main>

    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        // No payment functionality needed
    </script>
@endsection