@extends('layouts.auth')
@section('title', 'Register')

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
    
    body {
        margin: 0;
        padding: 0;
        font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        background: var(--bg-1);
        color: #e6eef8;
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }

    .hero-bg-layer {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: -1;
    }
    
    .hero-gradient {
        position: absolute;
        inset: -20% -10% auto -10%;
        height: 140%;
        background: radial-gradient(800px 400px at 10% 20%, rgba(108,99,255,0.18), transparent 10%), radial-gradient(600px 350px at 90% 80%, rgba(0,224,255,0.12), transparent 15%);
        mix-blend-mode: screen;
        filter: blur(60px);
    }
    
    .hero-shapes {
        position: absolute;
        inset: 0;
        z-index: 0;
    }
    
    .hero-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.55;
    }
    
    .hero-shape.s1 {
        width: 600px;
        height: 600px;
        left: -120px;
        top: -140px;
        background: linear-gradient(45deg,var(--accent1),#8a76ff);
    }
    
    .hero-shape.s2 {
        width: 420px;
        height: 420px;
        right: -80px;
        bottom: -100px;
        background: linear-gradient(45deg,var(--accent3),#2fe7ff);
    }
    
    .hero-shape.s3 {
        width: 320px;
        height: 320px;
        left: 60%;
        top: 10%;
        background: linear-gradient(45deg,var(--accent2),#ff8f6b);
    }
    
    .hero-lens {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg,transparent,#ffffff06);
        mix-blend-mode: overlay;
    }

    .register-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .register-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
        border: 1px solid rgba(255,255,255,0.04);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        border-radius: 20px;
        width: 100%;
        max-width: 400px;
        padding: 40px;
        box-shadow: 0 8px 30px rgba(2,6,23,0.7);
        position: relative;
        overflow: hidden;
        transition: transform .35s cubic-bezier(.2,.9,.2,1), box-shadow .35s;
    }
    
    .register-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(2,6,23,0.85);
        border-color: rgba(0,224,255,0.12);
    }

    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -50%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(108,99,255,0.1), transparent);
        transform: skewX(-25deg);
        animation: shine 4s infinite;
    }

    @keyframes shine {
        0% { left: -50%; }
        100% { left: 150%; }
    }

    .register-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .register-header h3 {
        color: #fff;
        font-size: 28px;
        font-weight: 600;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 15px;
        font-size: 14px;
        color: #fff;
        width: 100%;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .form-control:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
    }

    .btn-register {
        background: linear-gradient(90deg,var(--accent1),var(--accent3));
        color: #fff;
        border: none;
        border-radius: 999px;
        width: 100%;
        padding: 15px;
        font-size: 16px;
        font-weight: 700;
        margin-top: 10px;
        cursor: pointer;
        transition: transform .22s ease, box-shadow .22s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(108,99,255,0.14);
    }

    .btn-register::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-register:hover::before {
        left: 100%;
    }

    .btn-register:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 40px rgba(108,99,255,0.22);
    }

    .link-section {
        text-align: center;
        margin-top: 25px;
        font-size: 14px;
    }

    .link-section p {
        color: rgba(255, 255, 255, 0.8);
        margin: 10px 0;
    }

    .link-section a {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .link-section a:hover {
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .alert {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .text-danger {
        color: #ff6b6b !important;
        background: rgba(255, 107, 107, 0.1);
        padding: 10px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .invalid-feedback {
        color: #ff6b6b;
        font-size: 12px;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="hero-bg-layer">
    <div class="hero-gradient"></div>
    <div class="hero-shapes">
        <div class="hero-shape s1"></div>
        <div class="hero-shape s2"></div>
        <div class="hero-shape s3"></div>
    </div>
    <div class="hero-lens"></div>
</div>

<div class="register-wrapper">
    <div class="register-card">
        <div class="register-header">
            <h3>🎆 Daftar Akun</h3>
        </div>
        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            @if(Session::has('success'))
                <div class="alert">{{ Session::get('success') }}</div>
            @endif

            <div>
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Masukkan nama lengkap"
                       required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Masukkan email"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="Masukkan password"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if(Session::has('error'))
                <div class="text-danger text-center">{{ Session::get('error') }}</div>
            @endif

            <button type="submit" class="btn-register">✨ Daftar</button>

            <div class="link-section">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
// Animasi loading saat submit
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.querySelector('.btn-register');
    btn.innerHTML = '⏳ Memproses...';
    btn.disabled = true;
});
</script>
@endpush
