@extends('layouts.app')
@section('title', 'Studio')

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <h3 class="my-4">Daftar Studio</h3>
            <div class="row">
                @forelse($studios as $studio)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $studio->name }}</h5>
                                <p class="card-text">
                                    Kapasitas: {{ $studio->capacity }} kursi
                                </p>
                                <a href="{{ route('studios.show', $studio->id) }}" class="btn btn-primary">Lihat Layout</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>Belum ada studio tersedia</h5>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection