@php
    $role = auth()->user()->role;

    // [label, ikon, nama route]
    $menu = $role === 'admin'
        ? [
            ['Dashboard',            'bi-speedometer2', 'admin.dashboard'],
            ['Verifikasi Campaign',  'bi-patch-check',  'admin.verifications.index'],
            ['Kelola User',          'bi-people',       'admin.users.index'],
            ['Kelola Kategori',      'bi-tags',         'admin.categories.index'],
            ['Pantau Donasi',        'bi-cash-stack',   'admin.donations.index'],
            ['Penyaluran Dana',      'bi-send-check',   'admin.distributions.index'],
            ['Moderasi Konten',      'bi-shield-check', 'admin.moderation.index'],
        ]
        : [
            ['Dashboard',            'bi-speedometer2', 'penggalang.dashboard'],
            ['Campaign Saya',        'bi-megaphone',    'penggalang.campaigns.index'],
            ['Berita',               'bi-newspaper',    'penggalang.updates.index'],
            ['Penyaluran Dana',      'bi-send-check',   'penggalang.distributions.index'],
            ['Donatur',              'bi-people',       'penggalang.donors.index'],
        ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | Impactly</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/edonation.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="dash-shell">
    <aside class="dash-side">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-heart-fill" aria-hidden="true"></i></span>
            Impactly
        </a>
        <nav aria-label="Menu dashboard">
            <ul class="dash-menu">
                @foreach($menu as [$label, $icon, $routeName])
                    <li>
                        @if($routeName && \Illuminate\Support\Facades\Route::has($routeName))
                            <a href="{{ route($routeName) }}" class="{{ request()->routeIs($routeName . '*') ? 'active' : '' }}">
                                <i class="bi {{ $icon }}" aria-hidden="true"></i>{{ $label }}
                            </a>
                        @else
                            <span class="disabled" title="Segera tersedia">
                                <i class="bi {{ $icon }}" aria-hidden="true"></i>{{ $label }}
                            </span>
                        @endif
                    </li>
                @endforeach
                <li class="mt-2 pt-2 border-top border-secondary">
                    <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i>Profil Saya
                    </a>
                </li>
            </ul>
        </nav>
        <div class="mt-auto pt-4">
            <a href="{{ route('home') }}" class="d-block small mb-2 text-white-50 text-decoration-none">
                <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Lihat situs publik
            </a>
        </div>
    </aside>

    <div class="dash-main">
        <header class="dash-top">
            <div class="fw-bold fs-5 text-main">@yield('heading', 'Dashboard')</div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold lh-sm text-main">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">
                        @if($role === 'admin')
                            <span class="badge bg-danger">Administrator</span>
                        @else
                            <span class="badge bg-soft-sage text-primary-green">Penggalang Dana</span>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>Keluar</button>
                </form>
            </div>
        </header>

        @if(session('success'))
            <div class="px-4 pt-3">
                <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="px-4 pt-3">
                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            </div>
        @endif

        <main class="dash-body">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
