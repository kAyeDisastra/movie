@extends('layouts.app')
@section('title', 'Film Mendatang')

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
    position:relative;min-height:50vh;display:flex;align-items:center;overflow:hidden;padding:3rem 0
}
.hero-bg-layer{position:absolute;inset:0;pointer-events:none}
.hero-gradient{position:absolute;inset:-20% -10% auto -10%;height:140%;background:radial-gradient(800px 400px at 10% 20%, rgba(255,107,107,0.18), transparent 10%), radial-gradient(600px 350px at 90% 80%, rgba(108,99,255,0.12), transparent 15%);mix-blend-mode:screen;filter:blur(60px);}
.hero-shapes{position:absolute;inset:0;z-index:0}
.hero-shape{position:absolute;border-radius:50%;filter:blur(40px);opacity:0.55}
.hero-shape.s1{width:500px;height:500px;left:-100px;top:-120px;background:linear-gradient(45deg,var(--accent2),#ff8f6b)}
.hero-shape.s2{width:380px;height:380px;right:-60px;bottom:-80px;background:linear-gradient(45deg,var(--accent1),#8a76ff)}
.hero-shape.s3{width:280px;height:280px;left:50%;top:20%;background:linear-gradient(45deg,var(--accent3),#2fe7ff)}
.hero-lens{position:absolute;inset:0;background:linear-gradient(90deg,transparent,#ffffff06);mix-blend-mode:overlay}

/* ---------- HERO CONTENT ---------- */
.hero-card{position:relative;z-index:2;text-align:center}
.h1{font-size:2.8rem;font-weight:800;line-height:1.1;color:#fff;margin-bottom:1rem}
.lead{font-size:1.1rem;color:rgba(230,238,248,0.9);margin-bottom:2rem;max-width:600px;margin-left:auto;margin-right:auto}

/* ---------- MOVIES GRID ---------- */
main{padding:4rem 0;background:linear-gradient(180deg,#071021 0%, #07141c 100%)}
.section-title{font-size:2rem;font-weight:800;text-align:center;margin-bottom:2.25rem;background:linear-gradient(90deg,var(--accent2),var(--accent1));-webkit-background-clip:text;-webkit-text-fill-color:transparent;letter-spacing:.6px}
.grid{display:grid;grid-template-columns:repeat(1,1fr);gap:1.25rem}
@media(min-width:768px){.grid{grid-template-columns:repeat(2,1fr)}}
@media(min-width:1200px){.grid{grid-template-columns:repeat(3,1fr)}}

/* ---------- MOVIE CARD (Cinematic) ---------- */
.movie-card{position:relative;border-radius:16px;overflow:hidden;background:linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));border:1px solid rgba(255,255,255,0.04);backdrop-filter:blur(6px);box-shadow:0 8px 30px rgba(2,6,23,0.7);transition:transform .35s cubic-bezier(.2,.9,.2,1),box-shadow .35s}
.movie-card:hover{transform:translateY(-10px);box-shadow:0 30px 60px rgba(2,6,23,0.85);border-color:rgba(255,107,107,0.12)}
.poster-wrap{position:relative;height:360px;overflow:hidden}
.poster-wrap img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease,filter .4s ease}
.movie-card:hover .poster-wrap img{transform:scale(1.08);filter:brightness(1.1)}

/* overlay on hover */
.poster-overlay{position:absolute;inset:0;background:linear-gradient(180deg,transparent 30%, rgba(2,6,23,0.6));display:flex;flex-direction:column;justify-content:flex-end;padding:1rem;gap:.5rem;opacity:0;transition:opacity .28s ease}
.movie-card:hover .poster-overlay{opacity:1}
.play-btn{display:inline-flex;align-items:center;gap:.6rem;padding:.5rem 1rem;border-radius:999px;background:linear-gradient(90deg,var(--accent3),var(--accent1));color:#031026;font-weight:800;box-shadow:0 6px 18px rgba(0,224,255,0.12);border:none}
.coming-soon-badge{display:inline-flex;align-items:center;gap:.6rem;padding:.6rem 1.2rem;border-radius:999px;background:linear-gradient(90deg,var(--accent2),var(--accent1));color:#fff;font-weight:800;box-shadow:0 6px 18px rgba(255,107,107,0.15);border:none;font-size:.85rem}
.meta{padding:1rem 1.25rem;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01))}
.title{font-size:1.05rem;font-weight:800;color:#fff;margin-bottom:.4rem}
.row-meta{display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;margin-bottom:.8rem}
.badge{padding:.35rem .65rem;border-radius:999px;font-size:.75rem;background:linear-gradient(90deg,var(--accent2),var(--accent1));color:#fff}
.info{color:rgba(230,238,248,0.75);font-size:.92rem}
.description{color:rgba(230,238,248,0.7);font-size:.9rem;line-height:1.4;margin-top:.5rem}

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
            <div class="hero-card fade-up">
                <h1 class="h1">Film <span style="background:linear-gradient(90deg,var(--accent2),var(--accent1));-webkit-background-clip:text;-webkit-text-fill-color:transparent">Mendatang</span></h1>
                <p class="lead">Jangan lewatkan film-film terbaru yang akan segera tayang. Siapkan diri untuk pengalaman menonton yang tak terlupakan.</p>
            </div>
        </div>
    </section>

    <!-- Movies Section -->
    <main>
        <div class="container">
            <h2 class="section-title">Coming Soon</h2>

            <div class="grid">
                @forelse($films as $item)
                <article class="movie-card fade-up" tabindex="0">
                    <div class="poster-wrap">
                        <a href="{{ route('films.show', $item->id) }}" aria-label="Lihat detail {{ $item->title }}">
                            <img src="{{ $item->poster_image ? Storage::url($item->poster_image) : asset('images/placeholder.svg') }}" alt="Poster {{ $item->title }}">
                        </a>

                        <div class="poster-overlay">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <button class="play-btn" data-trailer="{{ $item->trailer_url }}" aria-label="Putar trailer {{ $item->title }}" onclick="event.stopPropagation()">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 3v18l15-9L5 3z" fill="#001018"/></svg>
                                    Trailer
                                </button>
                                
                                <a href="{{ route('films.show', $item->id) }}" class="coming-soon-badge" onclick="event.stopPropagation()" style="text-decoration:none;">
                                    <i class="fas fa-info-circle"></i>
                                    Detail
                                </a>
                            </div>
                            <div style="display:flex;gap:.5rem;align-items:center;margin-top:.6rem;justify-content:center;">
                                @foreach(array_slice(is_array($item->genre) ? $item->genre : json_decode($item->genre ?? '[]', true), 0, 2) as $genre)
                                    <span class="tag">{{ $genre }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="meta">
                        <a href="{{ route('films.show', $item->id) }}" class="text-decoration-none">
                            <h3 class="title">{{ $item->title }}</h3>
                        </a>

                        <div class="row-meta">
                            <div class="info">
                                <i class="fas fa-clock" style="margin-right:.45rem"></i>{{ $item->duration }} menit
                            </div>
                            
                            <div style="display:flex;gap:.4rem;flex-wrap:wrap">
                                @foreach(array_slice(is_array($item->genre) ? $item->genre : json_decode($item->genre ?? '[]', true), 0, 3) as $genre)
                                    <span class="badge">{{ $genre }}</span>
                                @endforeach
                            </div>
                        </div>

                        @if($item->description)
                        <p class="description">{{ Str::limit($item->description, 100) }}</p>
                        @endif
                    </div>
                </article>
                @empty
                <div class="empty">
                    <i class="fas fa-calendar-times" style="font-size:4rem;color:rgba(255,255,255,0.12);margin-bottom:1rem"></i>
                    <h4 style="opacity:.9;color:rgba(255,255,255,0.85)">Belum ada film yang akan datang</h4>
                    <p style="opacity:.75">Silakan cek kembali nanti untuk film terbaru 🎬</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Trailer Modal (hidden) -->
    <div id="trailer-modal" style="position:fixed;top:0;left:0;width:100vw;height:100vh;display:none;align-items:center;justify-content:center;z-index:9999;background:rgba(0,0,0,0.95);backdrop-filter:blur(10px);">
        <div style="width:90vw;height:90vh;background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));border:1px solid rgba(255,255,255,0.08);border-radius:20px;overflow:hidden;position:relative;box-shadow:0 30px 80px rgba(2,6,23,0.9);">
            <button id="modal-close" style="position:absolute;right:20px;top:20px;z-index:10;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;font-size:1.5rem;padding:12px 16px;border-radius:50%;width:50px;height:50px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s ease;">
                <i class="fas fa-times"></i>
            </button>
            <div id="trailer-container" style="width:100%;height:100%;position:relative;border-radius:20px;overflow:hidden;"></div>
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

// Trailer modal handling
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
            if(!src || src.trim() === ''){
                const t = document.createElement('div');
                t.textContent = 'Trailer belum tersedia';
                t.style.cssText = 'position:fixed;left:50%;transform:translateX(-50%);bottom:24px;padding:.6rem 1rem;background:#111;border-radius:8px;color:#fff;z-index:9999;opacity:0.95';
                document.body.appendChild(t);
                setTimeout(()=>t.remove(),1800);
                return;
            }

            let embed = '';
            if(src.includes('youtube.com') || src.includes('youtu.be')){
                let id = '';
                if(src.includes('youtu.be')) id = src.split('/').pop();
                else{
                    const url = new URL(src);
                    id = url.searchParams.get('v');
                }
                embed = `<iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0&modestbranding=1&iv_load_policy=3" style="position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:20px;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`;
            } else if(src.endsWith('.mp4') || src.endsWith('.webm')){
                embed = `<video src="${src}" controls autoplay style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;background:#000;border-radius:20px;"></video>`;
            } else {
                embed = `<iframe src="${src}" style="position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:20px;" allowfullscreen></iframe>`;
            }

            container.innerHTML = embed;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
    })
})();

// Keyboard accessibility: close modal with Esc
document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape'){ const modal = document.getElementById('trailer-modal'); if(modal && modal.style.display==='flex'){ modal.style.display='none'; document.getElementById('trailer-container').innerHTML=''; document.body.style.overflow=''; } } });

// Add hover effect to close button
document.addEventListener('DOMContentLoaded', function() {
    const closeBtn = document.getElementById('modal-close');
    if (closeBtn) {
        closeBtn.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255,107,107,0.2)';
            this.style.borderColor = 'rgba(255,107,107,0.4)';
            this.style.transform = 'scale(1.1)';
        });
        closeBtn.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(255,255,255,0.1)';
            this.style.borderColor = 'rgba(255,255,255,0.2)';
            this.style.transform = 'scale(1)';
        });
    }
});
</script>
@endpush