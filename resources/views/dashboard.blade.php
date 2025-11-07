@extends('layouts.app')
@section('title', 'Dashboard')

@push('css')
<style>
:root{
    --bg-1: #0b1020;
    --bg-2: #0f1724;
    --glass: rgba(255,255,255,0.06);
    --accent1: #6c63ff;
    --accent2: #ff6b6b;
    --accent3: #00e0ff;
}
/* ---------- GLOBAL ---------- */
*{box-sizing:border-box}
body{background:var(--bg-1); color:#e6eef8; font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;}
.container{max-width:1200px;margin:0 auto;padding:0 1rem}
.fade-up{opacity:0;transform:translateY(24px);transition:all 0.7s cubic-bezier(.2,.9,.2,1)}
.fade-up.show{opacity:1;transform:none}

/* ---------- CINEMATIC BACKDROP + PARALLAX ---------- */
.hero-section{
    position:relative;min-height:78vh;display:flex;align-items:center;overflow:hidden;padding:4rem 0
}
.hero-bg-layer{position:absolute;inset:0;pointer-events:none}
.hero-gradient{position:absolute;inset:-20% -10% auto -10%;height:140%;background:radial-gradient(800px 400px at 10% 20%, rgba(108,99,255,0.18), transparent 10%), radial-gradient(600px 350px at 90% 80%, rgba(0,224,255,0.12), transparent 15%);mix-blend-mode:screen;filter:blur(60px);}
.hero-shapes{position:absolute;inset:0;z-index:0}
.hero-shape{position:absolute;border-radius:50%;filter:blur(40px);opacity:0.55}
.hero-shape.s1{width:600px;height:600px;left:-120px;top:-140px;background:linear-gradient(45deg,var(--accent1),#8a76ff)}
.hero-shape.s2{width:420px;height:420px;right:-80px;bottom:-100px;background:linear-gradient(45deg,var(--accent3),#2fe7ff)}
.hero-shape.s3{width:320px;height:320px;left:60%;top:10%;background:linear-gradient(45deg,var(--accent2),#ff8f6b)}
.hero-lens{position:absolute;inset:0;background:linear-gradient(90deg,transparent,#ffffff06);mix-blend-mode:overlay}

/* ---------- HERO CONTENT ---------- */
.hero-card{position:relative;z-index:2;display:flex;gap:2rem;align-items:center}
.hero-left{flex:1}
.hero-right{flex:1;display:flex;justify-content:center;align-items:center}
.h1{font-size:3.1rem;font-weight:800;line-height:1.02;color:#fff}
.lead{font-size:1.05rem;color:rgba(230,238,248,0.9);margin-top:.6rem}
.btn-cta{display:inline-flex;align-items:center;gap:.75rem;padding:.85rem 1.25rem;border-radius:999px;background:linear-gradient(90deg,var(--accent1),var(--accent3));color:#fff;border:none;font-weight:700;box-shadow:0 10px 30px rgba(108,99,255,0.14);transition:transform .22s ease,box-shadow .22s ease}
.btn-cta:hover{transform:translateY(-4px);box-shadow:0 18px 40px rgba(108,99,255,0.22)}
.ghost-cta{background:transparent;border:1px solid rgba(255,255,255,0.08);padding:.7rem 1rem;color:rgba(255,255,255,0.9)}

/* ---------- MOVIES GRID ---------- */
main{padding:4rem 0;background:linear-gradient(180deg,#071021 0%, #07141c 100%)}
.section-title{font-size:2rem;font-weight:800;text-align:center;margin-bottom:2.25rem;background:linear-gradient(90deg,var(--accent1),var(--accent3));-webkit-background-clip:text;-webkit-text-fill-color:transparent;letter-spacing:.6px}
.grid{display:grid;grid-template-columns:repeat(1,1fr);gap:1.25rem}
@media(min-width:768px){.grid{grid-template-columns:repeat(2,1fr)}}
@media(min-width:1200px){.grid{grid-template-columns:repeat(3,1fr)}}

/* ---------- MOVIE CARD (Cinematic) ---------- */
.movie-card{position:relative;border-radius:16px;overflow:hidden;background:linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));border:1px solid rgba(255,255,255,0.04);backdrop-filter:blur(6px);box-shadow:0 8px 30px rgba(2,6,23,0.7);transition:transform .35s cubic-bezier(.2,.9,.2,1),box-shadow .35s;cursor:pointer}
.movie-card:hover{transform:translateY(-10px);box-shadow:0 30px 60px rgba(2,6,23,0.85);border-color:rgba(0,224,255,0.12)}
.movie-card:focus{outline:2px solid var(--accent3);outline-offset:2px}
.poster-wrap{position:relative;height:360px;overflow:hidden}
.poster-wrap img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease,filter .4s ease}
.movie-card:hover .poster-wrap img{transform:scale(1.08);filter:brightness(1)}

/* overlay on hover */
.poster-overlay{position:absolute;inset:0;background:linear-gradient(180deg,transparent 30%, rgba(2,6,23,0.6));display:flex;flex-direction:column;justify-content:flex-end;padding:1rem;gap:.5rem;opacity:0;transition:opacity .28s ease}
.movie-card:hover .poster-overlay{opacity:1}
.play-btn{display:inline-flex;align-items:center;gap:.6rem;padding:.5rem 1rem;border-radius:999px;background:linear-gradient(90deg,var(--accent3),var(--accent1));color:#031026;font-weight:800;box-shadow:0 6px 18px rgba(0,224,255,0.12);border:none}
.meta{padding:1rem 1.25rem;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));min-height:200px}
.title{font-size:1.05rem;font-weight:800;color:#fff;margin-bottom:.4rem}
.row-meta{display:flex;gap:.75rem;align-items:center;flex-wrap:wrap}
.badge{padding:.35rem .65rem;border-radius:999px;font-size:.75rem;background:linear-gradient(90deg,var(--accent1),var(--accent2));color:#fff}
.info{color:rgba(230,238,248,0.75);font-size:.92rem}
.action-row{display:flex;gap:.6rem;margin-top:.9rem}
.time-btn{background:transparent;border:1px solid rgba(255,255,255,0.06);padding:.45rem .9rem;border-radius:999px;color:rgba(255,255,255,0.9);font-weight:700;transition:all .18s ease}
.time-btn:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(108,99,255,0.06)}
.order-btn{background:linear-gradient(90deg,var(--accent1),var(--accent3));border:none;padding:.5rem .95rem;border-radius:999px;color:#fff;font-weight:800}

/* ---------- EMPTY STATE ---------- */
.empty{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 1rem;color:rgba(255,255,255,0.6)}

/* ---------- MICRO INTERACTIONS ---------- */
.tag{font-size:.75rem;padding:.25rem .5rem;border-radius:6px;background:rgba(255,255,255,0.04)}

/* accessibility adjustments */
@media (prefers-reduced-motion:reduce){*{transition:none!important;animation:none!important}}
</style>
@endpush

@section('content')
    @include('components.navbar')

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="hero-bg-layer">
            <div class="hero-gradient"></div>
            <div class="hero-shapes">
                <div class="hero-shape s1" data-parallax-speed="0.12"></div>
                <div class="hero-shape s2" data-parallax-speed="0.08"></div>
                <div class="hero-shape s3" data-parallax-speed="0.1"></div>
            </div>
            <div class="hero-lens"></div>
        </div>

        <div class="container">
            @guest
            <!-- Original Layout for Guests -->
            <div class="hero-card">
                <div class="hero-left fade-up">
                    <h1 class="h1">Nikmati Pengalaman Menonton yang Lebih <span style="background:linear-gradient(90deg,var(--accent1),var(--accent3));-webkit-background-clip:text;-webkit-text-fill-color:transparent">Cinematic</span></h1>
                    <p class="lead">Film terbaru, studio berkualitas, dan pengalaman pemesanan cepat. Hover kartu film untuk lihat trailer, jam tayang, dan pesan tiket langsung.</p>

                    <div style="margin-top:1.2rem;display:flex;gap:.75rem;flex-wrap:wrap">
                        <a href="{{ route('login') }}" class="btn-cta">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-cta" style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);">Daftar</a>
                    </div>
                </div>

                <div class="hero-right fade-up" aria-hidden="true">
                    <div id="hero-slideshow" style="width:450px;height:300px;border-radius:16px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);box-shadow:0 20px 60px rgba(2,6,23,0.8);position:relative;">
                        <div class="hero-slide active" style="position:absolute;inset:0;opacity:1;transition:opacity 1.5s ease;">
                            <img src="https://images.unsplash.com/photo-1489599904472-84978f312f2e?w=500&h=300&fit=crop" alt="Cinema Experience" style="width:100%;height:100%;object-fit:cover;">
                            <div style="position:absolute;inset:0;background:linear-gradient(45deg,rgba(108,99,255,0.4),transparent 60%,rgba(0,224,255,0.3));">
                                <div style="position:absolute;bottom:25px;left:25px;right:25px;">
                                    <h3 style="color:white;margin:0 0 10px 0;font-size:20px;font-weight:800;text-shadow:0 2px 10px rgba(0,0,0,0.6);">Studio Premium</h3>
                                    <p style="color:rgba(255,255,255,0.95);margin:0;font-size:14px;text-shadow:0 1px 6px rgba(0,0,0,0.5);">Teknologi audio visual terdepan</p>
                                </div>
                            </div>
                        </div>
                        <div class="hero-slide" style="position:absolute;inset:0;opacity:0;transition:opacity 1.5s ease;">
                            <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=500&h=300&fit=crop" alt="Cinema Snacks" style="width:100%;height:100%;object-fit:cover;">
                            <div style="position:absolute;inset:0;background:linear-gradient(45deg,rgba(255,107,107,0.4),transparent 60%,rgba(108,99,255,0.3));">
                                <div style="position:absolute;bottom:25px;left:25px;right:25px;">
                                    <h3 style="color:white;margin:0 0 10px 0;font-size:20px;font-weight:800;text-shadow:0 2px 10px rgba(0,0,0,0.6);">Snack & Minuman</h3>
                                    <p style="color:rgba(255,255,255,0.95);margin:0;font-size:14px;text-shadow:0 1px 6px rgba(0,0,0,0.5);">Nikmati camilan favorit Anda</p>
                                </div>
                            </div>
                        </div>
                        <div class="hero-slide" style="position:absolute;inset:0;opacity:0;transition:opacity 1.5s ease;">
                            <img src="https://images.unsplash.com/photo-1594909122845-11baa439b7bf?w=500&h=300&fit=crop" alt="Easy Booking" style="width:100%;height:100%;object-fit:cover;">
                            <div style="position:absolute;inset:0;background:linear-gradient(45deg,rgba(0,224,255,0.4),transparent 60%,rgba(255,107,107,0.3));">
                                <div style="position:absolute;bottom:25px;left:25px;right:25px;">
                                    <h3 style="color:white;margin:0 0 10px 0;font-size:20px;font-weight:800;text-shadow:0 2px 10px rgba(0,0,0,0.6);">Booking Mudah</h3>
                                    <p style="color:rgba(255,255,255,0.95);margin:0;font-size:14px;text-shadow:0 1px 6px rgba(0,0,0,0.5);">Pesan tiket dalam hitungan detik</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Large Centered Carousel for Authenticated Users -->
            <div class="hero-carousel fade-up" style="display:flex;justify-content:center;">
                <div class="carousel-container" style="width:800px;height:450px;border-radius:20px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);box-shadow:0 30px 80px rgba(2,6,23,0.8);position:relative;">
                    @foreach($films as $index => $film)
                    <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" style="position:absolute;inset:0;opacity:{{ $index === 0 ? '1' : '0' }};transition:opacity 1.5s ease-in-out;cursor:pointer;" onclick="window.location.href='{{ route('films.schedules', $film->id) }}'">
                        <img src="{{ $film->poster_image ? asset('storage/uploads/' . $film->poster_image) : 'https://picsum.photos/800/450?random=' . $film->id }}" alt="{{ $film->title }}" style="width:100%;height:100%;object-fit:cover;">
                        <div style="position:absolute;inset:0;background:linear-gradient(45deg,rgba(0,0,0,0.3),transparent 50%,rgba(0,0,0,0.6));">
                            <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.9));padding:2rem;">
                                <h2 style="color:white;margin:0 0 .5rem 0;font-size:2.5rem;font-weight:800;">{{ $film->title }}</h2>
                                <div style="display:flex;gap:1rem;align-items:center;margin-bottom:1rem;">
                                    <span style="color:rgba(255,255,255,0.9);font-size:1rem;"><i class="fas fa-clock" style="margin-right:.5rem;"></i>{{ $film->duration }} menit</span>
                                    @if($film->rating)
                                    <span style="color:var(--accent3);font-weight:700;font-size:1rem;">★ {{ $film->rating }}/10</span>
                                    @endif
                                </div>
                                @if($film->synopsis)
                                <p style="color:rgba(255,255,255,0.8);margin:0 0 1rem 0;font-size:1rem;line-height:1.5;max-width:600px;">{{ Str::limit($film->synopsis, 150) }}</p>
                                @endif
                                <button class="btn-cta" style="font-size:1rem;padding:1rem 2rem;">Pesan Tiket Sekarang</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Navigation arrows -->
                    <button id="prev-slide" style="position:absolute;left:20px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.5);border:none;color:white;width:50px;height:50px;border-radius:50%;cursor:pointer;font-size:1.2rem;transition:all 0.3s;">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="next-slide" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.5);border:none;color:white;width:50px;height:50px;border-radius:50%;cursor:pointer;font-size:1.2rem;transition:all 0.3s;">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <!-- Carousel indicators -->
                    <div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);display:flex;gap:10px;">
                        @foreach($films as $index => $film)
                        <div class="carousel-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}" style="width:12px;height:12px;border-radius:50%;background:{{ $index === 0 ? 'white' : 'rgba(255,255,255,0.4)' }};cursor:pointer;transition:all 0.3s;border:2px solid rgba(255,255,255,0.2);"></div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endguest
        </div>
    </section>

    <!-- Movies Section -->
    <main>
        <div class="container">
            <h2 class="section-title">Film Sedang Tayang</h2>

            <div class="grid">
                @forelse($films as $item)
                <article class="movie-card fade-up" tabindex="0" onclick="window.location.href='{{ route('films.schedules', $item->id) }}'" style="cursor: pointer;">
                    <div class="poster-wrap">
                        <img src="{{ $item->poster_image ? asset('storage/uploads/' . $item->poster_image) : 'https://picsum.photos/300/450?random=' . $item->id }}" alt="Poster {{ $item->title }}">

                        <div class="poster-overlay">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <button class="play-btn" data-trailer="{{ $item->trailer_url }}" aria-label="Putar trailer {{ $item->title }}" onclick="event.stopPropagation()">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 3v18l15-9L5 3z" fill="#001018"/></svg>
                                    Trailer
                                </button>

                                <a href="{{ auth()->check() ? route('studios.show', $item->schedules->first()->studio_id ?? 1) : route('login') }}" class="order-btn" onclick="event.stopPropagation()">Pesan</a>
                            </div>
                            <div style="display:flex;gap:.5rem;align-items:center;margin-top:.6rem">
                                @foreach(array_slice(is_array($item->genre) ? $item->genre : json_decode($item->genre ?? '[]', true), 0, 2) as $genre)
                                    <span class="tag">{{ $genre }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="meta">
                        <h3 class="title">{{ $item->title }}</h3>
                        
                        @if($item->rating)
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem">
                            <span style="color:var(--accent3);font-weight:700">★ {{ $item->rating }}/10</span>
                        </div>
                        @endif

                        <div class="row-meta">
                            <div class="info"><i class="fas fa-clock" style="margin-right:.45rem"></i>{{ $item->duration }} menit</div>
                            @if($item->director)
                            <div class="info"><i class="fas fa-user-tie" style="margin-right:.45rem"></i>{{ $item->director }}</div>
                            @endif
                        </div>

                        @if($item->synopsis)
                        <p style="color:rgba(230,238,248,0.7);font-size:.85rem;margin:.5rem 0;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $item->synopsis }}</p>
                        @endif

                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin:.5rem 0">
                            @foreach(array_slice(is_array($item->genre) ? $item->genre : json_decode($item->genre ?? '[]', true), 0, 3) as $genre)
                                <span class="badge">{{ $genre }}</span>
                            @endforeach
                        </div>

                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.5rem">
                            @foreach ($item->schedules->take(3) as $schedule)
                                <span style="font-size:.75rem;padding:.25rem .5rem;border-radius:6px;background:rgba(255,255,255,0.04);color:rgba(230,238,248,0.8)">{{ $schedule->studio->name ?? 'Studio' }}</span>
                            @endforeach
                        </div>

                        <div class="action-row">
                            @foreach ($item->schedules->take(4) as $schedule)
                                <a href="{{ auth()->check() ? route('studios.show', $schedule->studio_id ?? 1) : route('login') }}" class="time-btn" onclick="event.stopPropagation()">{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}</a>
                            @endforeach
                            @if($item->schedules->count() > 4)
                                <span style="color:rgba(230,238,248,0.6);font-size:.8rem;padding:.45rem .9rem">+{{ $item->schedules->count() - 4 }} lainnya</span>
                            @endif
                        </div>
                    </div>
                </article>
                @empty
                <div class="empty">
                    <i class="fas fa-film-slash" style="font-size:4rem;color:rgba(255,255,255,0.12);margin-bottom:1rem"></i>
                    <h4 style="opacity:.9;color:rgba(255,255,255,0.85)">Tidak ada film yang tersedia</h4>
                    <p style="opacity:.75">Silakan cek kembali nanti untuk film terbaru 🎬</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Trailer Modal (hidden) -->
    <div id="trailer-modal" style="position:fixed;top:0;left:0;width:100%;height:100%;display:none;align-items:center;justify-content:center;z-index:9999;padding:2rem;background:rgba(0,0,0,0.8);">
        <div style="width:min(1100px,95%);max-height:80vh;background:#000;border-radius:12px;overflow:hidden;position:relative;margin:auto;">
            <button id="modal-close" style="position:absolute;right:12px;top:8px;z-index:5;background:transparent;border:none;color:#fff;font-size:1.25rem;padding:.6rem">✕</button>
            <div id="trailer-container" style="width:100%;height:0;padding-bottom:56.25%;position:relative;"></div>
        </div>
    </div>

@endsection

@push('js')
<script>
// PARALLAX for hero shapes
(function(){
    const shapes = document.querySelectorAll('.hero-shape');
    if(!shapes.length) return;
    window.addEventListener('scroll',()=>{
        const top = window.scrollY;
        shapes.forEach(s=>{
            const speed = parseFloat(s.dataset.parallaxSpeed) || 0.08;
            s.style.transform = `translateY(${top * speed}px)`;
        })
    },{passive:true});
})();

// Reveal on scroll (IntersectionObserver)
(function(){
    const io = new IntersectionObserver((entries)=>{
        entries.forEach(e=>{
            if(e.isIntersecting){
                e.target.classList.add('show');
                e.target.classList.remove('fade-up');
                io.unobserve(e.target);
            }
        })
    },{threshold:0.12});

    document.querySelectorAll('.fade-up').forEach(el=>io.observe(el));
})();

// Trailer modal handling (uses $item->trailer_url if present)
(function(){
    const modal = document.getElementById('trailer-modal');
    const container = document.getElementById('trailer-container');
    const closeBtn = document.getElementById('modal-close');

    function closeModal(){
        modal.style.display = 'none';
        container.innerHTML = '';
        document.body.style.overflow = '';
    }
    closeBtn && closeBtn.addEventListener('click', closeModal);
    modal && modal.addEventListener('click', function(e){ if(e.target===modal) closeModal(); });

    document.querySelectorAll('.play-btn').forEach(btn=>{
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const src = btn.getAttribute('data-trailer') || '';
            console.log('Trailer URL:', src);
            if(!src || src.trim() === ''){
                // if no trailer url, just show a small toast-like message
                const t = document.createElement('div');
                t.textContent = 'Trailer belum tersedia';
                t.style.cssText = 'position:fixed;left:50%;transform:translateX(-50%);bottom:24px;padding:.6rem 1rem;background:#111;border-radius:8px;color:#fff;z-index:9999;opacity:0.95';
                document.body.appendChild(t);
                setTimeout(()=>t.remove(),1800);
                return;
            }

            // Support for common providers: if it's a youtube link, embed; else try to embed raw URL in video tag
            let embed = '';
            if(src.includes('youtube.com') || src.includes('youtu.be')){
                let id = '';
                if(src.includes('youtu.be')) id = src.split('/').pop();
                else{
                    const url = new URL(src);
                    id = url.searchParams.get('v');
                }
                embed = `<iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0" style="position:absolute;inset:0;border:0;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`;
            } else if(src.endsWith('.mp4') || src.endsWith('.webm')){
                embed = `<video src="${src}" controls autoplay style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover"></video>`;
            } else {
                // fallback: try iframe
                embed = `<iframe src="${src}" style="position:absolute;inset:0;border:0;" allowfullscreen></iframe>`;
            }

            container.innerHTML = embed;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
    })
})();

// Keyboard accessibility: close modal with Esc
document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape'){ const modal = document.getElementById('trailer-modal'); if(modal && modal.style.display==='flex'){ modal.style.display='none'; document.getElementById('trailer-container').innerHTML=''; document.body.style.overflow=''; } } });

// Header toggle functionality
(function(){
    const toggleBtn = document.getElementById('toggle-header');
    const headerContent = document.getElementById('header-content');
    const headerIcon = document.getElementById('header-icon');
    let isExpanded = true;
    
    if(!toggleBtn) return;
    
    toggleBtn.addEventListener('click', () => {
        if(isExpanded) {
            headerContent.style.maxHeight = '0';
            headerIcon.style.transform = 'rotate(-90deg)';
        } else {
            headerContent.style.maxHeight = '200px';
            headerIcon.style.transform = 'rotate(0deg)';
        }
        isExpanded = !isExpanded;
    });
})();

// Hero Slideshow functionality
(function(){
    const slides = document.querySelectorAll('.hero-slide');
    let currentSlide = 0;
    let autoAdvance;
    
    if(slides.length === 0) return;
    
    function showSlide(index) {
        slides.forEach(slide => slide.style.opacity = '0');
        slides[index].style.opacity = '1';
        currentSlide = index;
    }
    
    function nextSlide() {
        if (currentSlide < slides.length - 1) {
            showSlide(currentSlide + 1);
        } else {
            stopAutoAdvance();
        }
    }
    
    function startAutoAdvance() {
        autoAdvance = setInterval(nextSlide, 3000);
    }
    
    function stopAutoAdvance() {
        clearInterval(autoAdvance);
    }
    
    // Hover to pause
    const slideshow = document.getElementById('hero-slideshow');
    if(slideshow) {
        slideshow.addEventListener('mouseenter', stopAutoAdvance);
        slideshow.addEventListener('mouseleave', startAutoAdvance);
    }
    
    // Start auto-advance
    startAutoAdvance();
})();

// Hero Carousel functionality
(function(){
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.getElementById('prev-slide');
    const nextBtn = document.getElementById('next-slide');
    let currentSlide = 0;
    let autoAdvance;
    
    if(slides.length === 0) return;
    
    function showSlide(index) {
        slides.forEach(slide => slide.style.opacity = '0');
        dots.forEach(dot => {
            dot.style.background = 'rgba(255,255,255,0.4)';
            dot.classList.remove('active');
        });
        
        slides[index].style.opacity = '1';
        dots[index].style.background = 'white';
        dots[index].classList.add('active');
        
        currentSlide = index;
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    function startAutoAdvance() {
        autoAdvance = setInterval(nextSlide, 5000);
    }
    
    function stopAutoAdvance() {
        clearInterval(autoAdvance);
    }
    
    // Event listeners
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            stopAutoAdvance();
            setTimeout(startAutoAdvance, 3000);
        });
    });
    
    if(nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoAdvance();
            setTimeout(startAutoAdvance, 3000);
        });
    }
    
    if(prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoAdvance();
            setTimeout(startAutoAdvance, 3000);
        });
    }
    
    // Hover to pause
    const carousel = document.querySelector('.carousel-container');
    if(carousel) {
        carousel.addEventListener('mouseenter', stopAutoAdvance);
        carousel.addEventListener('mouseleave', startAutoAdvance);
    }
    
    // Start auto-advance
    startAutoAdvance();
})();
</script>
@endpush
