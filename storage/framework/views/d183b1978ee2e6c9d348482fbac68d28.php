<?php $__env->startSection('title', 'Film Mendatang'); ?>

<?php $__env->startPush('css'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                <?php $__empty_1 = true; $__currentLoopData = $films; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="movie-card fade-up" tabindex="0">
                    <div class="poster-wrap">
                        <a href="<?php echo e(route('films.show', $item->id)); ?>" aria-label="Lihat detail <?php echo e($item->title); ?>">
                            <img src="<?php echo e(asset('storage/' . $item->poster_image)); ?>" alt="Poster <?php echo e($item->title); ?>">
                        </a>

                        <div class="poster-overlay">
                            <div style="display:flex;justify-content:center;align-items:center;">
                                <div class="coming-soon-badge">
                                    <i class="fas fa-calendar-alt"></i>
                                    Coming Soon
                                </div>
                            </div>
                            <div style="display:flex;gap:.5rem;align-items:center;margin-top:.6rem;justify-content:center;">
                                <?php $__currentLoopData = array_slice(is_array($item->genre) ? $item->genre : json_decode($item->genre ?? '[]', true), 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="tag"><?php echo e($genre); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="meta">
                        <a href="<?php echo e(route('films.show', $item->id)); ?>" class="text-decoration-none">
                            <h3 class="title"><?php echo e($item->title); ?></h3>
                        </a>

                        <div class="row-meta">
                            <div class="info">
                                <i class="fas fa-clock" style="margin-right:.45rem"></i><?php echo e($item->duration); ?> menit
                            </div>
                            
                            <div style="display:flex;gap:.4rem;flex-wrap:wrap">
                                <?php $__currentLoopData = array_slice(is_array($item->genre) ? $item->genre : json_decode($item->genre ?? '[]', true), 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge"><?php echo e($genre); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <?php if($item->description): ?>
                        <p class="description"><?php echo e(Str::limit($item->description, 100)); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty">
                    <i class="fas fa-calendar-times" style="font-size:4rem;color:rgba(255,255,255,0.12);margin-bottom:1rem"></i>
                    <h4 style="opacity:.9;color:rgba(255,255,255,0.85)">Belum ada film yang akan datang</h4>
                    <p style="opacity:.75">Silakan cek kembali nanti untuk film terbaru 🎬</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
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
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ThinkPad\movie-ticket-laravel\resources\views/upcoming-movies.blade.php ENDPATH**/ ?>