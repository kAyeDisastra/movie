@extends('layouts.app')
@section('title', 'Pilih Kursi - Kasir')

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
.booking-card { 
    background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));
    border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:2rem;
    backdrop-filter:blur(10px);box-shadow:0 20px 40px rgba(2,6,23,0.8);
}
.form-control{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#e6eef8}
.form-control:focus{background:rgba(255,255,255,0.08);border-color:var(--accent3);color:#e6eef8;box-shadow:0 0 0 0.2rem rgba(0,224,255,0.25)}
.btn-primary{background:linear-gradient(90deg,var(--accent1),var(--accent3));border:none}
.btn-secondary{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#e6eef8}
.input-group-text{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#e6eef8}
.bg-light{background:rgba(255,255,255,0.05)!important;color:#e6eef8}
.bg-dark{background:linear-gradient(90deg,var(--accent1),var(--accent3))!important}
</style>
@endpush

@section('content')
@include('components.navbar')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="booking-card p-4">
                <h4>{{ $schedule->film->title }}</h4>
                <p class="text-muted">{{ $schedule->studio->name }} - {{ \Carbon\Carbon::parse($schedule->show_date)->format('d M Y') }} {{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}</p>
                
                <div class="text-center mb-4">
                    <small class="text-muted">Layar</small>
                    <div class="bg-dark text-white p-2 mb-3">SCREEN</div>
                </div>
                
                <div class="row justify-content-center mb-4">
                    @foreach($seats as $index => $seat)
                        <div class="col-1 mb-2">
                            @if($seat->status === 'booked')
                                <button class="btn btn-danger btn-sm w-100" disabled>
                                    {{ $seat->seat_code }}
                                </button>
                            @elseif($seat->status === 'pending')
                                <button class="btn btn-warning btn-sm w-100" disabled title="Menunggu pembayaran">
                                    {{ $seat->seat_code }}
                                </button>
                            @else
                                <button class="btn btn-outline-success btn-sm w-100 seat-btn"
                                        data-seat-id="{{ $seat->id }}"
                                        data-seat-code="{{ $seat->seat_code }}" data-status="available">
                                    {{ $seat->seat_code }}
                                </button>
                            @endif
                        </div>
                        @if(($index + 1) % 10 == 0)
                </div>
                <div class="row justify-content-center">
                        @endif
                    @endforeach
                </div>
                
                <div class="mt-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="btn btn-outline-success btn-sm me-2" style="width: 30px; height: 30px;"></div>
                        <small>Tersedia</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="btn btn-success btn-sm me-2" style="width: 30px; height: 30px;"></div>
                        <small>Dipilih</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="btn btn-warning btn-sm me-2" style="width: 30px; height: 30px;"></div>
                        <small>Menunggu Pembayaran</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="btn btn-danger btn-sm me-2" style="width: 30px; height: 30px;"></div>
                        <small>Terisi</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="booking-card p-4">
                <h5>Detail Booking</h5>
                <form action="{{ route('cashier.book') }}" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    <input type="hidden" name="seat_ids" id="selected-seats">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Customer</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No. HP Customer</label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input type="tel" name="customer_phone" class="form-control" required 
                                   pattern="[0-9]{9,12}" maxlength="12" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                   placeholder="8xxxxxxxxxx">
                        </div>
                        <small class="text-muted">Masukkan 9-12 digit angka (tanpa 0 di depan)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kursi Dipilih</label>
                        <div id="selected-seats-display" class="form-control bg-light">Belum ada kursi dipilih</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <div id="total-price" class="form-control bg-light">Rp 0</div>
                    </div>
                    
                    <input type="hidden" name="payment_method" value="confirmed">
                    
                    <button type="submit" class="btn btn-primary w-100" id="book-btn" disabled>Booking Sekarang</button>
                </form>
                
                <a href="{{ route('cashier.dashboard') }}" class="btn btn-secondary w-100 mt-2">Kembali</a>
            </div>
        </div>
    </div>
</div>

<script>
let selectedSeats = [];
const pricePerSeat = {{ $schedule->price->amount ?? 0 }};

document.querySelectorAll('.seat-btn').forEach(seat => {
    seat.addEventListener('click', function() {
        if (!this.disabled && this.dataset.status === 'available') {
            const seatId = this.dataset.seatId;
            const seatCode = this.dataset.seatCode;
            
            if (this.classList.contains('btn-outline-success')) {
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success');
                selectedSeats.push({id: seatId, code: seatCode});
            } else if (this.classList.contains('btn-success')) {
                this.classList.remove('btn-success');
                this.classList.add('btn-outline-success');
                selectedSeats = selectedSeats.filter(s => s.id !== seatId);
            }
            
            updateBookingInfo();
        }
    });
});

function updateBookingInfo() {
    const seatIds = selectedSeats.map(s => s.id);
    const seatCodes = selectedSeats.map(s => s.code);
    const totalPrice = selectedSeats.length * pricePerSeat;
    
    document.getElementById('selected-seats').value = seatIds.join(',');
    document.getElementById('selected-seats-display').textContent = seatCodes.length ? seatCodes.join(', ') : 'Belum ada kursi dipilih';
    document.getElementById('total-price').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    document.getElementById('book-btn').disabled = seatCodes.length === 0;
}
</script>
@endsection