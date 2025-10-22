@extends('layouts.app')
@section('title', 'Upcoming Movies')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <h3 class="my-4">Film Yang Akan Datang</h3>
            <div class="row">
                @forelse($films as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ asset('storage/' . $item->poster_image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 300px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->title }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">Genre: {{ implode(', ', $item->genre ?? []) }}</small><br>
                                    <small class="text-muted">Durasi: {{ $item->duration }} menit</small>
                                </p>
                                <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                                <span class="badge bg-warning">Coming Soon</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>Belum ada film yang akan datang</h5>
                            <p>Silakan cek kembali nanti</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection