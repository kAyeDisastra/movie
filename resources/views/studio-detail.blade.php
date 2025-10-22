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
                                @foreach($seats as $seat)
                                    <div class="col-1 mb-2">
                                        @if(in_array($seat, $bookedSeats))
                                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                                {{ $seat }}
                                            </button>
                                        @else
                                            <button class="btn btn-outline-success btn-sm w-100 seat-btn" data-seat="{{ $seat }}">
                                                {{ $seat }}
                                            </button>
                                        @endif
                                    </div>
                                    @if($seat % 7 == 0)
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
                            <p><strong>Nama:</strong> {{ $studio->name }}</p>
                            <p><strong>Kapasitas:</strong> {{ $studio->capacity }} kursi</p>
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
let selectedSeats = [];

document.querySelectorAll('.seat-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!this.disabled) {
            const seatNumber = parseInt(this.dataset.seat);
            
            if (this.classList.contains('btn-outline-success')) {
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success');
                selectedSeats.push(seatNumber);
            } else if (this.classList.contains('btn-success')) {
                this.classList.remove('btn-success');
                this.classList.add('btn-outline-success');
                selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
            }
            
            updateBookingInfo();
        }
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
            studio_id: {{ $studio->id }},
            seats: selectedSeats
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectedSeats.forEach(seatNumber => {
                const seatBtn = document.querySelector(`[data-seat="${seatNumber}"]`);
                seatBtn.className = 'btn btn-secondary btn-sm w-100';
                seatBtn.disabled = true;
            });
            
            selectedSeats = [];
            updateBookingInfo();
            alert('Kursi berhasil dibooking!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat booking kursi');
    });
}
</script>
@endpush