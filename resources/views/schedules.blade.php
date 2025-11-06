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
        <div class="film-detail-card" style="background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:2rem;backdrop-filter:blur(10px);">
            <div class="film-info">
                <div class="poster-large" style="width:200px;height:300px;border-radius:16px;overflow:hidden;box-shadow:0 20px 40px rgba(2,6,23,0.8);">
                    @php
                        $posterMap = [
                            'Avengers: Endgame' => 'images/avengers-endgame.jpg',
                            'Spider-Man: No Way Home' => 'images/spiderman-nwh.jpg', 
                            'The Batman' => 'images/the-batman.jpg',
                            'Shawshank Redemption' => 'images/shawshank.jpg'
                        ];
                        $posterPath = $posterMap[$film->title] ?? 'images/placeholder.svg';
                    @endphp
                    <img src="{{ asset($posterPath) }}" alt="{{ $film->title }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div class="film-details" style="flex:1;">
                    <!-- Status Badge -->
                    <div style="margin-bottom:1rem;">
                        <span style="background:linear-gradient(90deg,var(--accent1),var(--accent3));color:#fff;padding:.5rem 1rem;border-radius:999px;font-size:.85rem;font-weight:700;">
                            🎬 SEDANG TAYANG
                        </span>
                    </div>
                    
                    <!-- Title & Basic Info -->
                    <h1 style="font-size:2.5rem;font-weight:800;color:#fff;margin-bottom:1rem;">{{ $film->title }}</h1>
                    
                    <div style="display:flex;gap:2rem;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;">
                        <div class="film-meta"><i class="fas fa-clock" style="margin-right:.5rem;color:var(--accent3);"></i>{{ $film->duration }} menit</div>
                        @if($film->rating)
                        <div class="film-meta"><i class="fas fa-star" style="margin-right:.5rem;color:var(--accent3);"></i>{{ $film->rating }}/10</div>
                        @endif
                        <div class="film-meta"><i class="fas fa-tags" style="margin-right:.5rem;color:var(--accent3);"></i>{{ implode(', ', array_slice(is_array($film->genre) ? $film->genre : json_decode($film->genre ?? '[]', true), 0, 3)) }}</div>
                    </div>
                    
                    <!-- Description -->
                    @if($film->description)
                    <div style="margin-bottom:2rem;">
                        <h3 style="color:#fff;font-size:1.2rem;font-weight:700;margin-bottom:.5rem;">Sinopsis</h3>
                        <p style="color:rgba(230,238,248,0.9);line-height:1.6;font-size:1rem;">{{ $film->description }}</p>
                    </div>
                    @endif
                    
                    <!-- Pricing Info -->
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:1.5rem;margin-bottom:2rem;">
                        <h3 style="color:#fff;font-size:1.2rem;font-weight:700;margin-bottom:1rem;">💰 Informasi Harga</h3>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
                            @php
                                $uniquePrices = $film->schedules->pluck('price.amount')->unique()->sort();
                            @endphp
                            @foreach($uniquePrices as $price)
                            <div style="text-align:center;padding:1rem;background:rgba(0,224,255,0.1);border:1px solid rgba(0,224,255,0.2);border-radius:8px;">
                                <div style="color:var(--accent3);font-size:1.5rem;font-weight:800;">Rp {{ number_format($price, 0, ',', '.') }}</div>
                                <div style="color:rgba(230,238,248,0.7);font-size:.9rem;">per tiket</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Quick Booking -->
                    <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
                        <a href="#jadwal-section" style="background:linear-gradient(90deg,var(--accent1),var(--accent3));color:#fff;padding:1rem 2rem;border-radius:999px;text-decoration:none;font-weight:700;font-size:1.1rem;display:inline-flex;align-items:center;gap:.5rem;transition:transform .2s ease;">
                            🎫 Pesan Tiket Sekarang
                        </a>
                        @if($film->trailer_url)
                        <button onclick="playTrailer('{{ $film->trailer_url }}')" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;padding:1rem 2rem;border-radius:999px;font-weight:700;cursor:pointer;transition:all .2s ease;">
                            ▶️ Tonton Trailer
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="schedule-section" id="jadwal-section">
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
                <div class="schedule-card" onclick="window.location.href='/studios/{{ $schedule->studio_id }}?schedule_id={{ $schedule->id }}'">
                    <div class="studio-name">{{ $schedule->studio->name ?? 'Studio' }}</div>
                    <div class="time-slot">{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}</div>
                    <div class="price">Rp {{ number_format($schedule->price->amount ?? 0, 0, ',', '.') }}</div>
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
function playTrailer(url) {
    if(!url) {
        alert('Trailer belum tersedia');
        return;
    }
    
    // Create modal
    const modal = document.createElement('div');
    modal.style.cssText = 'position:fixed;inset:0;display:flex;align-items:center;justify-content:center;z-index:9999;padding:2rem;background:rgba(0,0,0,0.8)';
    
    const container = document.createElement('div');
    container.style.cssText = 'width:min(1100px,95%);max-height:80vh;background:#000;border-radius:12px;overflow:hidden;position:relative;';
    
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '✕';
    closeBtn.style.cssText = 'position:absolute;right:12px;top:8px;z-index:5;background:transparent;border:none;color:#fff;font-size:1.25rem;padding:.6rem;cursor:pointer';
    
    const videoContainer = document.createElement('div');
    videoContainer.style.cssText = 'width:100%;height:0;padding-bottom:56.25%;position:relative;';
    
    let embed = '';
    if(url.includes('youtube.com') || url.includes('youtu.be')){
        let id = '';
        if(url.includes('youtu.be')) id = url.split('/').pop();
        else{
            const urlObj = new URL(url);
            id = urlObj.searchParams.get('v');
        }
        embed = `<iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0" style="position:absolute;inset:0;border:0;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`;
    } else {
        embed = `<iframe src="${url}" style="position:absolute;inset:0;border:0;" allowfullscreen></iframe>`;
    }
    
    videoContainer.innerHTML = embed;
    container.appendChild(closeBtn);
    container.appendChild(videoContainer);
    modal.appendChild(container);
    document.body.appendChild(modal);
    
    closeBtn.onclick = () => {
        document.body.removeChild(modal);
    };
    
    modal.onclick = (e) => {
        if(e.target === modal) {
            document.body.removeChild(modal);
        }
    };
}

// Smooth scroll to schedule section
document.addEventListener('DOMContentLoaded', function() {
    const bookingBtn = document.querySelector('a[href="#jadwal-section"]');
    if(bookingBtn) {
        bookingBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('jadwal-section').scrollIntoView({
                behavior: 'smooth'
            });
        });
    }
});
</script>
@endpush