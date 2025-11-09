@extends('layouts.app')
@section('title', 'Pembayaran')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Pembayaran Tiket</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Kode Booking:</strong><br>
                                    {{ $booking->booking_code }}
                                </div>
                                <div class="col-md-6 text-end">
                                    <strong>Total:</strong><br>
                                    <span class="h5 text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="alert alert-warning text-center">
                                    <strong>Waktu Pembayaran:</strong> <span id="countdown" class="fw-bold"></span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Detail Film:</strong><br>
                                {{ $booking->schedule->film->title ?? 'Film tidak ditemukan' }}<br>
                                <small class="text-muted">
                                    Studio: {{ $booking->schedule->studio->name ?? '-' }} | 
                                    Kursi: {{ is_array($booking->seats) ? implode(', ', $booking->seats) : $booking->seats }} | 
                                    {{ \Carbon\Carbon::parse($booking->schedule->show_date)->format('d M Y') }} | 
                                    {{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('H:i') }}
                                </small>
                            </div>

                            <div class="text-center">
                                <button id="pay-button" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        // Countdown timer
        const createdAt = new Date('{{ $booking->created_at->toISOString() }}');
        const expireTime = new Date(createdAt.getTime() + 60 * 1000);
        
        function updateCountdown() {
            const now = new Date();
            const timeLeft = expireTime - now;
            
            if (timeLeft <= 0) {
                document.getElementById('countdown').innerHTML = 'KADALUARSA';
                document.getElementById('pay-button').disabled = true;
                alert('Waktu pembayaran habis. Booking telah kadaluarsa.');
                window.location.href = "{{ route('transactions') }}";
                return;
            }
            
            const minutes = Math.floor(timeLeft / 60000);
            const seconds = Math.floor((timeLeft % 60000) / 1000);
            document.getElementById('countdown').innerHTML = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Pembayaran berhasil!");
                    window.location.href = "{{ route('transactions') }}";
                },
                onPending: function(result){
                    alert("Menunggu pembayaran!");
                    window.location.href = "{{ route('transactions') }}";
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>
@endsection