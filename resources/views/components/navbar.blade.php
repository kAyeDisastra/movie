<nav class="navbar navbar-expand-lg fixed-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 20px rgba(0,0,0,0.1);">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            <i class="fas fa-film me-2"></i>{{ config('app.name') }}
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('upcoming.movies') }}">
                        <i class="fas fa-calendar me-1"></i>Film Mendatang
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('studios') }}">
                        <i class="fas fa-tv me-1"></i>Studio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('transactions') }}">
                        <i class="fas fa-history me-1"></i>Riwayat
                    </a>
                </li>
                @endauth
            </ul>
            
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light rounded-pill px-3" href="{{ route('login') }}">Masuk</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light rounded-pill px-3" href="{{ route('register') }}">Daftar</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>