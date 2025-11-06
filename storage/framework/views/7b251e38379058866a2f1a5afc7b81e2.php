<nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="<?php echo e(route('dashboard')); ?>">
            <i class="fas fa-film me-2"></i><?php echo e(config('app.name')); ?>

        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('dashboard')); ?>">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('upcoming.movies')); ?>">
                        <i class="fas fa-calendar me-1"></i>Film Mendatang
                    </a>
                </li>
                <?php if(auth()->guard()->check()): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('studios')); ?>">
                        <i class="fas fa-tv me-1"></i>Studio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo e(route('transactions')); ?>">
                        <i class="fas fa-history me-1"></i>Riwayat
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if(auth()->guard()->check()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                            <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                        </div>
                        <?php echo e(Auth::user()->name); ?>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <li>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light rounded-pill px-3" href="<?php echo e(route('login')); ?>">Masuk</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light rounded-pill px-3" href="<?php echo e(route('register')); ?>">Daftar</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\ThinkPad\movie-ticket-laravel\resources\views/components/navbar.blade.php ENDPATH**/ ?>