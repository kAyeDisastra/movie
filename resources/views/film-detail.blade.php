@extends('layouts.app')
@section('title', 'Detail Film')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-3">← Kembali</a>
                <h3>{{ $film->title }}</h3>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('storage/' . $film->poster_image) }}" class="img-fluid rounded" alt="{{ $film->title }}">
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $film->title }}</h4>
                            <div class="mb-3">
                                <span class="badge bg-primary">{{ $film->status == 'play_now' ? 'Sedang Tayang' : 'Coming Soon' }}</span>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Genre:</strong></div>
                                <div class="col-sm-9">{{ is_array($film->genre) ? implode(', ', $film->genre) : (is_string($film->genre) ? $film->genre : '-') }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Durasi:</strong></div>
                                <div class="col-sm-9">{{ $film->duration }} menit</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Deskripsi:</strong></div>
                                <div class="col-sm-9">{{ $film->description ?? 'Tidak ada deskripsi' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection