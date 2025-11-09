@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h3 class="mb-4">Edit Profile</h3>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Change Password -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Ubah Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Password Lama</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5>Hapus Akun</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-danger">Peringatan: Tindakan ini tidak dapat dibatalkan!</p>
                            <form method="POST" action="{{ route('profile.delete') }}" onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')">
                                @csrf
                                @method('DELETE')
                                <div class="mb-3">
                                    <label class="form-label">Masukkan Password untuk Konfirmasi</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-danger">Hapus Akun</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection