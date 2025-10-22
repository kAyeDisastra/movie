@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <h3 class="my-4">Film Hari Ini</h3>
            <div class="row">
                @forelse($films as $item)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="row g-0">
                                <div class="col-4">
                                    <a href="{{ route('films.show', $item->id) }}">
                                        <img src="{{ asset('storage/' . $item->poster_image) }}" class="img-fluid rounded-start h-100" alt="{{ $item->title }}" style="object-fit: cover; cursor: pointer;">
                                    </a>
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <a href="{{ route('films.show', $item->id) }}" class="text-decoration-none">
                                            <h5 class="card-title text-dark">{{ $item->title }}</h5>
                                        </a>
                                        <p class="card-text">
                                            <small class="text-muted">Genre: {{ is_array($item->genre) ? implode(', ', $item->genre) : (is_string($item->genre) ? $item->genre : '-') }}</small><br>
                                            <small class="text-muted">Durasi: {{ $item->duration }} menit</small>
                                        </p>
                                        <div class="mb-2">
                                            @foreach ($item->schedules as $schedule)
                                                <span class="badge bg-secondary me-1">{{ $schedule->studio->name ?? '-' }}</span>
                                            @endforeach
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            @foreach ($item->schedules as $schedule)
                                                <a href="{{ route('studios.show', ['id' => $schedule->studio_id, 'schedule_id' => $schedule->id]) }}" class="btn btn-outline-primary btn-sm me-1 mb-1">
                                                    {{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>Tidak ada film yang tersedia hari ini</h5>
                            <p>Silakan cek kembali nanti atau hubungi customer service</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection