@extends('layouts.app')
@section('title', $film->title)

@push('meta')
<meta name="description" content="{{ Str::limit($film->description ?? 'Film ' . $film->title, 160) }}">
<meta property="og:title" content="{{ $film->title }}">
<meta property="og:description" content="{{ Str::limit($film->description ?? 'Film ' . $film->title, 160) }}">
<meta property="og:image" content="{{ asset('storage/' . $film->poster_image) }}">
<meta property="og:type" content="video.movie">
@endpush

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

.detail-hero{
    position:relative;min-height:70vh;display:flex;align-items:center;
    background:linear-gradient(135deg,rgba(108,99,255,0.1),rgba(0,224,255,0.05));
    overflow:hidden;padding:2rem 0
}
.detail-content{display:grid;grid-template-columns:300px 1fr;gap:2rem;align-items:start}
@media(max-width:768px){.detail-content{grid-template-columns:1fr;gap:1.5rem}}

.poster{
    border-radius:16px;overflow:hidden;
    box-shadow:0 20px 60px rgba(2,6,23,0.8);
    border:1px solid rgba(255,255,255,0.08)
}
.poster img{width:100%;height:auto;display:block}

.info{padding:1rem 0}
.title{font-size:2.5rem;font-weight:800;color:#fff;margin-bottom:.5rem}
.meta-row{display:flex;gap:1rem;align-items:center;margin-bottom:1rem;flex-wrap:wrap}
.genre-tag{
    padding:.4rem .8rem;border-radius:999px;font-size:.85rem;
    background:linear-gradient(90deg,var(--accent1),var(--accent2));color:#fff
}
.rating{color:var(--accent3);font-weight:700}
.synopsis{color:rgba(230,238,248,0.9);line-height:1.6;margin:1.5rem 0}
.detail-item{margin:.8rem 0;color:rgba(230,238,248,0.8)}
.detail-item strong{color:#fff}

.schedules{
    background:linear-gradient(180deg,rgba(255,255,255,0.03),rgba(255,255,255,0.01));
    border:1px solid rgba(255,255,255,0.04);border-radius:16px;
    padding:1.5rem;margin-top:2rem
}
.schedule-title{font-size:1.3rem;font-weight:700;color:#fff;margin-bottom:1rem}
.schedule-grid{display:grid;gap:1rem}
.schedule-item{
    background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);
    border-radius:12px;padding:1rem;display:flex;justify-content:space-between;align-items:center
}
.studio-info{color:#fff;font-weight:600}
.time-info{color:var(--accent3);font-weight:700}
.price-info{color:rgba(230,238,248,0.8)}

.btn-book{
    background:linear-gradient(90deg,var(--accent1),var(--accent3));
    border:none;padding:.6rem 1.2rem;border-radius:999px;
    color:#fff;font-weight:700;text-decoration:none;
    display:inline-flex;align-items:center;gap:.5rem;
    transition:transform .2s ease,box-shadow .2s ease
}
.btn-book:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(108,99,255,0.2)}

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
<div class="detail-hero">
    <a href="{{ route('dashboard') }}" class="back-btn">← Kembali</a>
    
    <!-- Breadcrumb -->
    <div style="position:absolute;top:5rem;left:2rem;color:rgba(230,238,248,0.7);font-size:.9rem">
        <a href="{{ route('dashboard') }}" style="color:var(--accent3);text-decoration:none">Beranda</a>
        <span style="margin:0 .5rem">/</span>
        <span>{{ $film->title }}</span>
    </div>
    
    <div class="container">
        <div class="detail-content">
            <div class="poster">
                @php
                    $posterMap = [
                        'Avengers: Endgame' => 'images/avengers-endgame.jpg',
                        'Spider-Man: No Way Home' => 'images/spiderman-nwh.jpg', 
                        'The Batman' => 'images/the-batman.jpg',
                        'Shawshank Redemption' => 'images/shawshank.jpg'
                    ];
                    $posterPath = $posterMap[$film->title] ?? 'images/placeholder.svg';
                @endphp
                <img src="{{ asset($posterPath) }}" alt="{{ $film->title }}">
            </div>
            
            <div class="info">
                <h1 class="title">{{ $film->title }}</h1>
                
                <div class="meta-row">
                    @foreach(array_slice(is_array($film->genre) ? $film->genre : json_decode($film->genre ?? '[]', true), 0, 3) as $genre)
                        <span class="genre-tag">{{ $genre }}</span>
                    @endforeach
                    @if($film->rating)
                        <span class="rating">★ {{ $film->rating }}/10</span>
                    @endif
                </div>
                
                <div class="synopsis">{{ $film->description ?? 'Deskripsi tidak tersedia.' }}</div>
                
                <div class="detail-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin:1.5rem 0">
                    <div class="detail-item"><strong>Durasi:</strong> {{ $film->duration }} menit</div>
                    <div class="detail-item"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $film->status)) }}</div>
                    <div class="detail-item"><strong>Total Jadwal:</strong> {{ $film->schedules->count() }} sesi</div>
                    <div class="detail-item"><strong>Dibuat:</strong> {{ $film->created_at->format('d M Y') }}</div>
                </div>
                
                <div style="margin-top:1rem;display:flex;gap:.75rem;flex-wrap:wrap">
                    @if($film->trailer_url)
                    <button class="btn-book" onclick="playTrailer('{{ $film->trailer_url }}')">
                        ▶ Tonton Trailer
                    </button>
                    @endif
                    <a href="{{ route('films.schedules', $film->id) }}" class="btn-book" style="text-decoration:none;background:linear-gradient(90deg,var(--accent2),var(--accent1))">
                        📅 Lihat Semua Jadwal
                    </a>
                    <button class="btn-book" onclick="shareFilm()" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2)">
                        🔗 Bagikan
                    </button>
                </div>
            </div>
        </div>
        
        @if($film->schedules->count() > 0)
        <div class="schedules">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                <h3 class="schedule-title" style="margin:0">Jadwal Terdekat</h3>
                <a href="{{ route('films.schedules', $film->id) }}" style="color:var(--accent3);text-decoration:none;font-weight:600">
                    Lihat Semua →
                </a>
            </div>
            <div class="schedule-grid">
                @foreach($film->schedules->take(4) as $schedule)
                <div class="schedule-item">
                    <div>
                        <div class="studio-info">{{ $schedule->studio->name ?? 'Studio' }}</div>
                        <div class="price-info">Rp {{ number_format($schedule->price->price ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="time-info">{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}</div>
                        <div class="price-info">{{ \Carbon\Carbon::parse($schedule->show_date)->format('d M Y') }}</div>
                    </div>
                    <a href="{{ auth()->check() ? route('studios.show', $schedule->studio_id) : route('login') }}" class="btn-book">
                        Pesan Tiket
                    </a>
                </div>
                @endforeach
            </div>
            @if($film->schedules->count() > 4)
            <div style="text-align:center;margin-top:1rem">
                <a href="{{ route('films.schedules', $film->id) }}" class="btn-book" style="display:inline-flex;text-decoration:none">
                    Lihat {{ $film->schedules->count() - 4 }} Jadwal Lainnya
                </a>
            </div>
            @endif
        </div>
        @else
        <div class="schedules">
            <h3 class="schedule-title">Jadwal Tayang</h3>
            <div style="text-align:center;padding:2rem;color:rgba(230,238,248,0.6)">
                <p>Belum ada jadwal tersedia untuk film ini.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Trailer Modal -->
<div id="trailer-modal" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;z-index:9999;padding:2rem;background:rgba(0,0,0,0.8)">
    <div style="width:min(1100px,95%);max-height:80vh;background:#000;border-radius:12px;overflow:hidden;position:relative;">
        <button onclick="closeTrailer()" style="position:absolute;right:12px;top:8px;z-index:5;background:transparent;border:none;color:#fff;font-size:1.25rem;padding:.6rem">✕</button>
        <div id="trailer-container" style="width:100%;height:0;padding-bottom:56.25%;position:relative;"></div>
    </div>
</div>
@endsection

@push('js')
<script>
function playTrailer(url) {
    const modal = document.getElementById('trailer-modal');
    const container = document.getElementById('trailer-container');
    
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
    
    container.innerHTML = embed;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeTrailer() {
    const modal = document.getElementById('trailer-modal');
    const container = document.getElementById('trailer-container');
    modal.style.display = 'none';
    container.innerHTML = '';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape') closeTrailer();
});

function shareFilm() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $film->title }}',
            text: 'Lihat film {{ $film->title }} - {{ Str::limit($film->description ?? "Film menarik", 100) }}',
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            const toast = document.createElement('div');
            toast.textContent = 'Link berhasil disalin!';
            toast.style.cssText = 'position:fixed;left:50%;transform:translateX(-50%);bottom:24px;padding:.8rem 1.2rem;background:var(--accent1);border-radius:8px;color:#fff;z-index:9999;font-weight:600';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        });
    }
}
</script>
@endpush