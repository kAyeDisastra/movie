@extends('layouts.app')
@section('title', 'Jadwal ' . $film->title)

@push('css')
<style>
:root{
    --bg-1: #0b1020;
    --bg-2: #0f1724;
    --accent1: #6c63ff;
    --accent2: #ff6b6b;
    --accent3: #00e0ff;
}
body{background:var(--bg-1);color:#e6eef8;font-family:Inter,system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial}
.container{max-width:1200px;margin:0 auto;padding:0 1rem}

.schedule-hero{
    position:relative;min-height:40vh;display:flex;align-items:center;
    background:linear-gradient(135deg,rgba(108,99,255,0.1),rgba(0,224,255,0.05));
    overflow:hidden;padding:2rem 0
}
.film-info{display:flex;gap:2rem;align-items:center;margin-bottom:2rem}
.poster-mini{width:120px;height:180px;border-radius:12px;overflow:hidden;box-shadow:0 10px 30px rgba(2,6,23,0.6)}
.poster-mini img{width:100%;height:100%;object-fit:cover}
.film-details h1{font-size:2rem;font-weight:800;color:#fff;margin-bottom:.5rem}
.film-meta{color:rgba(230,238,248,0.8);margin-bottom:.5rem}

.schedule-section{padding:2rem 0;background:linear-gradient(180deg,#071021 0%, #07141c 100%)}
.section-title{font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:1.5rem;text-align:center}
.date-group{margin-bottom:2rem}
.date-header{font-size:1.2rem;font-weight:600;color:var(--accent3);margin-bottom:1rem;padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,0.1)}
.schedule-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem}

.schedule-card{
    background:linear-gradient(180deg,rgba(255,255,255,0.03),rgba(255,255,255,0.01));
    border:1px solid rgba(255,255,255,0.04);border-radius:12px;padding:1.5rem;
    transition:all .3s ease;cursor:pointer
}
.schedule-card:hover{transform:translateY(-3px);border-color:rgba(0,224,255,0.2);box-shadow:0 10px 30px rgba(2,6,23,0.8)}
.studio-name{font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.5rem}
.time-slot{font-size:1.3rem;font-weight:800;color:var(--accent3);margin-bottom:.5rem}
.price{color:var(--accent2);font-weight:600;margin-bottom:1rem}
.btn-book{
    width:100%;background:linear-gradient(90deg,var(--accent1),var(--accent3));
    border:none;padding:.8rem;border-radius:8px;color:#fff;font-weight:700;
    transition:transform .2s ease
}
.btn-book:hover{transform:translateY(-2px)}

.back-btn{
    position:absolute;top:2rem;left:2rem;
    background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
    padding:.6rem 1rem;border-radius:999px;color:#fff;text-decoration:none;
    backdrop-filter:blur(10px);transition:all .2s ease
}
.back-btn:hover{background:rgba(255,255,255,0.15)}
</style>
@endpush

@section('content')
<div class="schedule-hero">
    <a href="{{ route('dashboard') }}" class="back-btn">← Kembali</a>
    
    <!-- Breadcrumb -->
    <div style="position:absolute;top:5rem;left:2rem;color:rgba(230,238,248,0.7);font-size:.9rem">
        <a href="{{ route('dashboard') }}" style="color:var(--accent3);text-decoration:none">Beranda</a>
        <span style="margin:0 .5rem">/</span>
        <a href="{{ route('films.show', $film->id) }}" style="color:var(--accent3);text-decoration:none">{{ $film->title }}</a>
        <span style="margin:0 .5rem">/</span>
        <span>Jadwal</span>
    </div>
    
    <div class="container">
        <div class="film-info">
            <div class="poster-mini">
                <img src="{{ asset('storage/' . $film->poster_image) }}" alt="{{ $film->title }}">
            </div>
            <div class="film-details">
                <h1>{{ $film->title }}</h1>
                <div class="film-meta">{{ $film->duration }} menit • {{ implode(', ', array_slice(is_array($film->genre) ? $film->genre : json_decode($film->genre ?? '[]', true), 0, 3)) }}</div>
                @if($film->rating)
                <div class="film-meta">★ {{ $film->rating }}/10</div>
                @endif
                <div style="margin-top:1rem">
                    <a href="{{ route('films.show', $film->id) }}" style="color:var(--accent3);text-decoration:none;font-weight:600">
                        📝 Lihat Detail Film
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="schedule-section">
    <div class="container">
        <h2 class="section-title">Pilih Jadwal Tayang</h2>
        
        @php
            $groupedSchedules = $film->schedules->groupBy(function($schedule) {
                return \Carbon\Carbon::parse($schedule->show_date)->format('Y-m-d');
            });
        @endphp
        
        @foreach($groupedSchedules as $date => $schedules)
        <div class="date-group">
            <div class="date-header">
                {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
            </div>
            
            <div class="schedule-grid">
                @foreach($schedules as $schedule)
                <div class="schedule-card" onclick="bookTicket('{{ $schedule->id }}')">
                    <div class="studio-name">{{ $schedule->studio->name ?? 'Studio' }}</div>
                    <div class="time-slot">{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}</div>
                    <div class="price">Rp {{ number_format($schedule->price->price ?? 0, 0, ',', '.') }}</div>
                    <button class="btn-book">Pesan Tiket</button>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        
        @if($film->schedules->count() == 0)
        <div style="text-align:center;padding:3rem;color:rgba(255,255,255,0.6)">
            <h3>Belum ada jadwal tersedia</h3>
            <p>Silakan cek kembali nanti</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('js')
<script>
function bookTicket(scheduleId) {
    @auth
        window.location.href = `/studios/${scheduleId}`;
    @else
        window.location.href = '{{ route("login") }}';
    @endauth
}
</script>
@endpush