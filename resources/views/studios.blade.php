@extends('layouts.app')
@section('title', 'Studio')

@push('css')
<style>
:root{
    --bg-1: #0b1020;
    --bg-2: #0f1724;
    --accent1: #6c63ff;
    --accent2: #ff6b6b;
    --accent3: #00e0ff;
}
body{background:linear-gradient(180deg,#071021 0%, #07141c 100%);color:#e6eef8;font-family:Inter,system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial;min-height:100vh}
.studio-section { 
    background:linear-gradient(180deg,rgba(255,255,255,0.05),rgba(255,255,255,0.02));
    border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:2rem;
    backdrop-filter:blur(10px);margin-bottom:2rem;
    box-shadow:0 20px 40px rgba(2,6,23,0.8);
}
.film-item { 
    background:linear-gradient(180deg,rgba(255,255,255,0.03),rgba(255,255,255,0.01));
    border:1px solid rgba(255,255,255,0.04);border-radius:16px;overflow:hidden;
    margin:15px;min-width:320px;transition:all .3s ease;text-align:center;
}
.film-poster{width:100%;height:400px;overflow:hidden;position:relative}
.film-poster img{width:100%;height:100%;object-fit:cover}
.film-content{padding:1.5rem;text-align:center}
.time-badge{background:linear-gradient(90deg,var(--accent1),var(--accent3));color:#fff;padding:0.8rem 1.5rem;border-radius:999px;font-weight:700;font-size:1.1rem;display:inline-block;margin-top:1rem;box-shadow:0 5px 15px rgba(108,99,255,0.3)}
.film-item:hover{transform:translateY(-3px);border-color:rgba(0,224,255,0.2);box-shadow:0 10px 30px rgba(2,6,23,0.8)}
.film-grid { display: flex; flex-wrap: wrap; gap: 10px; }
.studio-section h4{color:#fff;font-size:1.5rem;font-weight:700;margin-bottom:1.5rem}
.film-item h6{color:#fff;font-size:1.1rem;font-weight:700;margin-bottom:.5rem}
.film-item .btn{background:linear-gradient(90deg,var(--accent1),var(--accent3));border:none;color:#fff;font-weight:700;transition:transform .2s ease}
.film-item .btn:hover{transform:translateY(-2px)}
.film-item .btn small{color:rgba(255,255,255,0.8)}
</style>
@endpush

@section('content')
    @include('components.navbar')

    <main class="content py-4">
        <div class="container">
            <h3 class="my-4">Daftar Studio</h3>
            
            @foreach($studios as $studio)
                <div class="studio-section p-4">
                    <h4 class="mb-3">{{ $studio->name }}</h4>
                    
                    @if($studio->schedules->count() > 0)
                        <div class="film-grid">
                            @php
                                $films = $studio->schedules->groupBy('film.title');
                            @endphp
                            
                            @foreach($films as $filmTitle => $schedules)
                                @foreach($schedules as $schedule)
                                <div class="film-item" onclick="window.location.href='{{ route('studios.show', $studio->id) }}?schedule_id={{ $schedule->id }}'" style="cursor:pointer">
                                    <div class="film-poster">
                                        <img src="{{ $schedule->film->poster_image ? Storage::url($schedule->film->poster_image) : asset('images/placeholder.svg') }}" alt="{{ $filmTitle }}">
                                    </div>
                                    <div class="film-content">
                                        <h6>{{ $filmTitle }}</h6>
                                        <div class="time-badge">{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}</div>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada jadwal film</p>
                    @endif
                </div>
            @endforeach
        </div>
    </main>
@endsection