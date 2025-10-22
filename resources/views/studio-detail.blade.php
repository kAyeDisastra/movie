@extends('layouts.app')
@section('title', 'Studio Detail')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('studios') }}" class="btn btn-outline-secondary me-3">← Kembali</a>
                <h3>{{ $studio->name }}</h3>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Layout Kursi</h5>
                            <small class="text-muted">Layar</small>
                            <div class="bg-dark text-white p-2 mb-3">SCREEN</div>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                @foreach($seats as $index => $seat)
                                    <div class="col-1 mb-2">
                                        @if(in_array($seat->id, $bookedSeatIds))
                                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                                {{ $seat->seat_code }}
                                            </button>
                                        @else
                                            <button class="btn btn-outline-success btn-sm w-100 seat-btn" data-seat-id="{{ $seat->id }}" data-seat-code="{{ $seat->seat_code }}">
                                                {{ $seat->seat_code }}
                                            </button>
                                        @endif
                                    </div>
                                    @if(($index + 1) % 10 == 0)
                                        </div><div class="row justify-content-center">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="selected-count">0</span> kursi dipilih
                                </div>
                                <button id="book-btn" class="btn btn-primary" disabled onclick="bookSeats()">Book Kursi</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Info Studio</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Film:</strong> {{ $schedule->film->title ?? '-' }}</p>
                            <p><strong>Nama:</strong> {{ $studio->name }}</p>
                            <p><strong>Kapasitas:</strong> {{ $studio->capacity }} kursi</p>
                            <p><strong>Tanggal:</strong> {{ $schedule->show_date }}</p>
                            <p><strong>Jam:</strong> {{ $schedule->show_time }}</p>
                            <p><strong>Harga:</strong> Rp {{ number_format($schedule->price->amount, 0, ',', '.') }}</p>
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="btn btn-outline-success btn-sm me-2" style="width: 30px; height: 30px;"></div>
                                    <small>Tersedia</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="btn btn-success btn-sm me-2" style="width: 30px; height: 30px;"></div>
                                    <small>Dipilih</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="btn btn-secondary btn-sm me-2" style="width: 30px; height: 30px;"></div>
                                    <small>Terisi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
<script>
console.log('Script loaded');
let selectedSeats = [];

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, finding seat buttons...');
    const seatButtons = document.querySelectorAll('.seat-btn');
    console.log('Found', seatButtons.length, 'seat buttons');
    
    seatButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        if (!this.disabled) {
            const seatId = parseInt(this.dataset.seatId);
            
            if (this.classList.contains('btn-outline-success')) {
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success');
                selectedSeats.push(seatId);
            } else if (this.classList.contains('btn-success')) {
                this.classList.remove('btn-success');
                this.classList.add('btn-outline-success');
                selectedSeats = selectedSeats.filter(seat => seat !== seatId);
            }
            
            updateBookingInfo();
        }
    });
});
});

function updateBookingInfo() {
    document.getElementById('selected-count').textContent = selectedSeats.length;
    document.getElementById('book-btn').disabled = selectedSeats.length === 0;
}

function bookSeats() {
    if (selectedSeats.length === 0) return;
    
    fetch('{{ route("booking.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            schedule_id: {{ $schedule->id }},
            seat_ids: selectedSeats
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectedSeats.forEach(seatId => {
                const seatBtn = document.querySelector(`[data-seat-id="${seatId}"]`);
                seatBtn.className = 'btn btn-secondary btn-sm w-100';
                seatBtn.disabled = true;
            });
            
            selectedSeats = [];
            updateBookingInfo();
            alert('Tiket berhasil dibeli!');
            
            // Redirect to transactions page
            setTimeout(() => {
                window.location.href = '{{ route("transactions") }}';
            }, 1500);
        } else {
            alert(data.message || 'Gagal membeli tiket');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat booking kursi');
    });
}
</script>
@endpush