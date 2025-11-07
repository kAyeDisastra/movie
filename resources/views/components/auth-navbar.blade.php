<nav class="navbar navbar-expand-lg fixed-top" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
    <div class="container">
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="{{ route('dashboard') }}">
            <div class="me-2 p-2 rounded-circle" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-film" style="font-size: 20px;"></i>
            </div>
            <span style="font-size: 24px; letter-spacing: 1px;">{{ config('app.name') }}</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="color: white;">
            <i class="fas fa-bars"></i>
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
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-light rounded-pill px-4 py-2" href="{{ route('register') }}" style="background: rgba(255,255,255,0.9); color: #1e3c72; font-weight: 600; transition: all 0.3s ease;">
                        <i class="fas fa-user-plus me-2"></i>Daftar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.btn-light:hover {
    background: white !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
</style>