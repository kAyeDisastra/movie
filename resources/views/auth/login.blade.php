@extends('layouts.auth')
@section('title', 'Login')

@push('css')
<style>
    body {
        background-color: #0f1724;
        font-family: 'Poppins', sans-serif;
    }

    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px);
        padding: 20px 0;
    }

    .login-card {
        background: #fff;
        width: 420px;
        border-radius: 6px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .login-header {
        background: #f8f9fa;
        text-align: center;
        padding: 18px;
        border-bottom: 1px solid #ddd;
    }

    .login-header h3 {
        font-weight: 600;
        margin: 0;
    }

    .login-body {
        padding: 30px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
    }

    .form-control {
        background-color: #f1f6ff;
        border: 1px solid #cfd6e1;
        border-radius: 5px;
        padding: 10px;
        font-size: 14px;
    }

    .btn-login {
        background-color: #3a78b8;
        color: #fff;
        border: none;
        border-radius: 4px;
        width: 100%;
        padding: 10px;
        font-size: 15px;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background-color: #2f68a5;
    }

    .link-section {
        text-align: center;
        margin-top: 18px;
        font-size: 14px;
    }

    .link-section a {
        color: #1a4dab;
        text-decoration: none;
    }

    .link-section a:hover {
        text-decoration: underline;
    }

    .alert {
        font-size: 14px;
        padding: 8px;
    }
</style>
@endpush

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h3>Sign In</h3>
        </div>
        <div class="login-body">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                @if(Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif

                <div class="mb-3">
                    <label for="email" class="form-label">No Handphone</label>
                    <input type="text"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan no handphone"
                           required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
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
                    <div class="text-danger text-center mb-3">{{ Session::get('error') }}</div>
                @endif

                <button type="submit" class="btn-login">Masuk</button>

                <div class="link-section">
                    <p>Belum punya akun? <a href="{{ route('register') }}">Buat akun</a></p>
                    <p>Lupa password anda? <a href="#">Lupa Password</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
{{-- JS khusus halaman ini --}}
@endpush