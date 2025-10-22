@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <h3 class="my-4">Riwayat Transaksi</h3>
            
            @forelse($transactions as $transaction)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Order #{{ $transaction->id }}</strong>
                            <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $transaction->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <div class="card-body">
                        @foreach($transaction->orderDetails as $detail)
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <h6>{{ $detail->schedule->film->title ?? 'Film tidak ditemukan' }}</h6>
                                    <small class="text-muted">
                                        Studio: {{ $detail->schedule->studio->name ?? '-' }} | 
                                        Kursi: {{ $detail->seat_code }} | 
                                        Tanggal: {{ \Carbon\Carbon::parse($detail->schedule->show_date)->format('d M Y') }} | 
                                        Jam: {{ \Carbon\Carbon::parse($detail->schedule->show_time)->format('H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <strong>Rp {{ number_format($detail->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Total</strong>
                            </div>
                            <div class="col-md-4 text-end">
                                <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                            </div>
                        </div>
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
@endsection