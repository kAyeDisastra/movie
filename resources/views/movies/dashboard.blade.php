@extends('layouts.app')
@section('title', 'List Movie')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <h3 class="my-4">List Film</h3>
            <div class="row">
                @foreach($films as $item)
                    <div class="col-6">
                        <div class="row d-flex mb-5">
                            <div class="col-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $item->poster_image)  }}" class="card-img-top" alt="KKN Di Desa Penari" style="height: 185px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="card-body mb-4">
                                    <h4 class="card-title mb-3"></h4>
                                    <div>
                                        <div class="row">
                                            <div class="col text-muted">Studio</div>
                                            <div class="col">
                                                @foreach ($item->schedules as $schedule)
                                                    <span class="badge bg-secondary">{{ $schedule->studio->name ?? '-' }}</span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col text-muted">Genre</div>
                                            <div class="col">{{ is_array($item->genre) ? implode(', ', $item->genre) : (is_string($item->genre) ? $item->genre : '-') }}</div>
                                        </div>

                                        <div class="row">
                                            <div class="col text-muted">Durasi</div>
                                            <div class="col">{{ $item->duration }} menit</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap">
                                    @foreach ($item->schedules as $schedule)
                                        <a href="" class="btn btn-light me-2 mb-2">
                                            {{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}
                                        </a>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
