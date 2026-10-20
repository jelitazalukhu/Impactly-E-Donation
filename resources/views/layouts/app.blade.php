<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') | Impactly</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/edonation.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg site-nav sticky-top">
    <div class="container">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-heart-fill" aria-hidden="true"></i></span>
            Impactly
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto gap-lg-2">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ request()->routeIs('home') ? '#hero' : route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ request()->routeIs('home') ? '#campaigns' : route('home') . '#campaigns' }}">Campaign</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ request()->routeIs('home') ? '#top-donors' : route('home') . '#top-donors' }}">Top Donatur</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ request()->routeIs('home') ? '#cara-kerja' : route('home') . '#cara-kerja' }}">Cara Kerja</a></li>
                @else
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}" href="{{ route('campaigns.index') }}">Semua Campaign</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('top-donors') ? 'active' : '' }}" href="{{ route('top-donors') }}">Top Donatur</a></li>
                    @if(auth()->user()->role === 'donatur')
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('donations.history') ? 'active' : '' }}" href="{{ route('donations.history') }}">Riwayat Donasi</a></li>
                    @endif
                @endguest
            </ul>
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-teal">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-teal">Daftar</a>
                @endguest
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-teal dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1" aria-hidden="true"></i>{{ \Illuminate\Support\Str::limit(auth()->user()->name, 18) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if(auth()->user()->role === 'donatur')
                                <li><a class="dropdown-item" href="{{ route('donations.history') }}"><i class="bi bi-clock-history me-2" aria-hidden="true"></i>Riwayat Donasi</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>Dashboard</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2" aria-hidden="true"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container pt-3">
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    </div>
@endif
@if(session('error'))
    <div class="container pt-3">
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    </div>
@endif

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="brand text-white mb-2"><span class="brand-mark" style="background:#fff;color:var(--color-primary)"><i class="bi bi-heart-fill" aria-hidden="true"></i></span>Impactly</div>
                <p class="mb-0" style="max-width: 28em;">Platform donasi online yang transparan. Setiap campaign dilengkapi berita dan laporan penyaluran dana.</p>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold text-white mb-2">Jelajahi</div>
                <div><a href="{{ route('campaigns.index') }}">Semua campaign</a></div>
                <div><a href="{{ route('top-donors') }}">Top donatur</a></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold text-white mb-2">Akun</div>
                @guest
                    <div><a href="{{ route('login') }}">Masuk</a></div>
                    <div><a href="{{ route('register') }}">Daftar</a></div>
                @endguest
                @auth
                    <div><a href="{{ route('dashboard') }}">Halaman saya</a></div>
                @endauth
            </div>
        </div>
        <hr style="border-color: rgba(255,255,255,.18)">
        <div class="small">&copy; {{ date('Y') }} Impactly. Proyek UTS E-Application, D3 Teknik Informatika, Universitas Sumatera Utara.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
