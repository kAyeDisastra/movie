<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name')  }}</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    Film Hari Ini
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('upcoming.movies') }}">
                    Film Mendatang
                </a>
            </li>
            @auth
            <li class="nav-item">
                <a class="nav-link" href="{{ route('studios') }}">
                    Studio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transactions') }}">
                    Riwayat Transaksi
                </a>
            </li>
            @endauth
        </ul>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
            @endauth
        </ul>
    </div>
</nav>
