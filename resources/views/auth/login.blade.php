@extends('layouts.auth')
@section('title', 'Login')

@push('css')
    {{--  CSS Only for this page  --}}
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @if(Session::has('error'))
                                <div class="mb-3">
                                    <p class="text-danger text-center">{{ Session::get('error')  }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <button class="btn btn-primary w-100">Login</button>
                                <p class="text-center">No have account? <a href="{{ route('register')  }}" class="btn-link">Register here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{--  JS Only for this page  --}}
@endpush
