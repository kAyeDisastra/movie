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
                                        @elseif(in_array($seat->id, $pendingSeatIds))
                                            <button class="btn btn-warning btn-sm w-100" disabled
                                                    title="Menunggu pembayaran">
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
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="selected-count">0</span> kursi dipilih
                                    <div id="total-price" class="text-success fw-bold"></div>
                                </div>
                                <button id="book-btn" class="btn btn-primary" disabled onclick="showBookingModal()">Book
                                    Kursi
                                </button>
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
                            <p><strong>Harga:</strong>
                                Rp {{ number_format($schedule->price->amount ?? 0, 0, ',', '.') }}</p>
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="btn btn-outline-success btn-sm me-2"
                                         style="width: 30px; height: 30px;"></div>
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

    <!-- Booking Confirmation Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pemesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Film:</strong> {{ $schedule->film->title ?? '-' }}
                    </div>
                    <div class="mb-3">
                        <strong>Studio:</strong> {{ $studio->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Tanggal & Waktu:</strong> {{ $schedule->show_date }} - {{ $schedule->show_time }}
                    </div>
                    <div class="mb-3">
                        <strong>Kursi:</strong> <span id="modal-seats"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Jumlah Kursi:</strong> <span id="modal-seat-count"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Harga per Kursi:</strong>
                        Rp {{ number_format($schedule->price->amount ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="mb-3">
                        <strong>Total Harga:</strong> <span id="modal-total-price" class="text-success fw-bold"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="proceedToPayment()">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        let selectedSeats = [];

        document.addEventListener('DOMContentLoaded', function () {
            const seatButtons = document.querySelectorAll('.seat-btn');

            seatButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    // Only allow selection of available seats
                    if (!this.disabled && this.dataset.status === 'available') {
                        const seatId = parseInt(this.dataset.seatId);
                        const seatCode = this.dataset.seatCode;

                        if (this.classList.contains('btn-outline-success')) {
                            this.classList.remove('btn-outline-success');
                            this.classList.add('btn-success');
                            selectedSeats.push({id: seatId, code: seatCode});
                        } else if (this.classList.contains('btn-success')) {
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-success');
                            selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
                        }

                        updateBookingInfo();
                    }
                });
            });

            // Add click handler for all seats (including disabled ones) to show feedback
            document.querySelectorAll('.btn-warning, .btn-secondary').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (this.classList.contains('btn-warning')) {
                        alert('Kursi ini sedang dipesan oleh orang lain. Silakan pilih kursi lain.');
                    } else if (this.classList.contains('btn-secondary')) {
                        alert('Kursi ini sudah terisi. Silakan pilih kursi lain.');
                    }
                });
            });
        });

        function updateBookingInfo() {
            const count = selectedSeats.length;
            const pricePerSeat = {{ $schedule->price->amount ?? 0 }};
            const totalPrice = count * pricePerSeat;

            document.getElementById('selected-count').textContent = count;
            document.getElementById('book-btn').disabled = count === 0;

            if (count > 0) {
                document.getElementById('total-price').textContent = 'Total: Rp ' + totalPrice.toLocaleString('id-ID');
            } else {
                document.getElementById('total-price').textContent = '';
            }
        }

        function showBookingModal() {
            if (selectedSeats.length === 0) return;

            const seatCodes = selectedSeats.map(seat => seat.code).join(', ');
            const count = selectedSeats.length;
            const pricePerSeat = {{ $schedule->price->amount ?? 0 }};
            const totalPrice = count * pricePerSeat;

            document.getElementById('modal-seats').textContent = seatCodes;
            document.getElementById('modal-seat-count').textContent = count;
            document.getElementById('modal-total-price').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');

            new bootstrap.Modal(document.getElementById('bookingModal')).show();
        }

        function proceedToPayment() {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
            // Proceed with booking
            bookSeats();
        }

        function bookSeats() {
            if (selectedSeats.length === 0) return;

            const seatIds = selectedSeats.map(seat => seat.id);

            fetch('{{ route("booking.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    schedule_id: {{ $schedule->id }},
                    seat_ids: seatIds
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Gagal membuat pesanan');
                        return;
                    }

                    console.log('Token Midtrans:', data.snap_token);
                    snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            fetch('{{ route("booking.confirm") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({order_id: data.order_id})
                            }).then(() => {
                                selectedSeats.forEach(seat => {
                                    const btn = document.querySelector(`[data-seat-id="${seat.id}"]`);
                                    if (btn) {
                                        btn.className = 'btn btn-secondary btn-sm w-100';
                                        btn.disabled = true;
                                    }
                                });
                                selectedSeats = [];
                                updateBookingInfo();
                                alert('Pembayaran berhasil!');
                                window.location.href = '{{ route("transactions") }}';
                            });
                        },
                        onPending: function () {
                            alert('Pembayaran pending, silakan selesaikan di Midtrans.');
                        },
                        onError: function () {
                            alert('Pembayaran gagal.');
                        }
                    });
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan: ' + err.message);
                });
        }
    </script>
@endpush
