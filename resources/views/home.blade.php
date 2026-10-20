@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

@if(!Auth::check() || Auth::user()->role !== 'donatur')
{{-- ===== Hero Guest: Full Photo Background dengan Overlay Aksen Biru/Putih Lembut ===== --}}
<section class="hero" id="hero">
    <div class="container py-4">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10 col-xl-9">
                <span class="badge px-3 py-2 rounded-pill fw-bold mb-3 d-inline-flex align-items-center gap-2 shadow-xs" style="background: rgba(255, 255, 255, 0.95); color: var(--color-primary); border: 1px solid #D5E1ED;">
                    <i class="bi bi-shield-check"></i> Platform Donasi Transparan & Terpercaya
                </span>
                <h1 class="mb-3 display-5 fw-bold text-main">
                    Setiap <em>pemberian</em> kecil, membawa <em>perubahan</em> besar.
                </h1>
                <p class="lead mb-4 mx-auto" style="max-width: 660px;">
                    Pilih campaign, tentukan nominal donasi, dan pantau progresnya secara transparan.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#campaigns" class="btn btn-teal btn-lg px-4 shadow-sm">Donasi Sekarang</a>
                    <a href="#cara-kerja" class="btn btn-outline-teal btn-lg px-4" style="background: rgba(255, 255, 255, 0.9);">Cara kerja</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== Statistik platform ===== --}}
<section class="container" style="margin-top: -1.75rem; position: relative; z-index: 5;">
    <div class="stat-strip">
        <div class="row g-0">
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-value">{{ \App\Support\Rupiah::short($stats['total_dana']) }}</div>
                <div class="stat-label">Total dana terkumpul</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-value">{{ number_format($stats['jumlah_donatur'], 0, ',', '.') }}</div>
                <div class="stat-label">Jumlah donatur</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-value">{{ number_format($stats['campaign_aktif'], 0, ',', '.') }}</div>
                <div class="stat-label">Campaign aktif</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-value">{{ \App\Support\Rupiah::short($stats['dana_tersalurkan']) }}</div>
                <div class="stat-label">Dana tersalurkan</div>
            </div>
        </div>
    </div>
</section>

@else

{{-- ===== Halaman Donatur: Banner Selamat Datang (Tanpa Aksen Biru Samping) + List Campaign yang Diikuti ===== --}}
<section class="container py-4" id="donatur-home">
    {{-- Banner Selamat Datang: Bersih, Rapi, Tanpa Border Samping --}}
    <div class="panel p-4 mb-4 bg-white border border-sage shadow-xs">
        <div class="row align-items-center g-3">
            <div class="col-md-7">
                <div class="small text-muted mb-1">Selamat datang kembali,</div>
                <h1 class="h3 fw-bold text-main mb-1">{{ Auth::user()->name }}</h1>
                <p class="text-muted small mb-0">Terima kasih telah terus menjadi lentera kebaikan bagi saudara kita yang membutuhkan.</p>
            </div>
            <div class="col-md-5">
                <div class="d-flex justify-content-md-end gap-3 text-center">
                    <div class="p-2 px-3 rounded-3 bg-light border border-sage shadow-xs">
                        <div class="fw-bold fs-5" style="color: var(--color-primary)">{{ \App\Support\Rupiah::format($donorStats['total_donasi'] ?? 0) }}</div>
                        <div class="small text-muted" style="font-size:0.75rem;">Total Donasi Anda</div>
                    </div>
                    <div class="p-2 px-3 rounded-3 bg-light border border-sage shadow-xs">
                        <div class="fw-bold text-main fs-5">{{ $donorStats['campaign_count'] ?? 0 }}</div>
                        <div class="small text-muted" style="font-size:0.75rem;">Campaign Diikuti</div>
                    </div>
                    <div class="p-2 px-3 rounded-3 bg-light border border-sage shadow-xs">
                        <div class="fw-bold text-main fs-5">{{ $donorStats['cert_count'] ?? 0 }}</div>
                        <div class="small text-muted" style="font-size:0.75rem;">Sertifikat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. Campaign yang Diikuti oleh Donatur: Bentuk LIST Kompak --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h2 class="section-title"><i class="bi bi-heart-fill text-danger me-2"></i>Campaign yang Anda Ikuti</h2>
                <p class="text-muted mb-0">Pantau perkembangan dan penyaluran dana langsung pada program yang Anda dukung.</p>
            </div>
            @if(!$myCampaigns->isEmpty())
                <a href="{{ route('donations.history') }}" class="fw-semibold text-decoration-none">Lihat Riwayat Donasi &rarr;</a>
            @endif
        </div>

        @if($myCampaigns->isEmpty())
            <div class="panel p-4 text-center py-5">
                <div class="fs-1 text-muted mb-2"><i class="bi bi-bag-heart"></i></div>
                <h3 class="h5 fw-bold text-main">Anda belum mengikuti campaign apapun</h3>
                <p class="text-muted small mb-3" style="max-width: 480px; margin: 0 auto;">
                    Dukungan dan uluran tangan Anda membawa senyum dan harapan baru. Temukan campaign yang ingin Anda dukung hari ini.
                </p>
                <a href="{{ route('campaigns.index') }}" class="btn btn-teal">
                    <i class="bi bi-compass me-1"></i> Jelajahi Semua Campaign
                </a>
            </div>
        @else
            {{-- List View yang Kompak dan Rapi --}}
            <div class="panel p-0 overflow-hidden shadow-xs border border-sage">
                @foreach($myCampaigns as $campaign)
                    <div class="p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom {{ $loop->last ? 'border-bottom-0' : '' }} hover-bg-subtle" style="background: #FFFFFF;">
                        <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width: 0;">
                            <div class="rounded-3 flex-shrink-0 overflow-hidden" style="width: 68px; height: 68px; background: var(--color-accent);">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 fs-3" style="color: var(--color-primary);">
                                        <i class="bi {{ $campaign->icon ?? 'bi-heart' }}"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="cat-badge" style="font-size: 0.72rem; padding: 0.15rem 0.55rem;">{{ $campaign->category->name ?? 'Umum' }}</span>
                                    <span class="text-muted small">&bull; {{ $campaign->owner->organization_name ?? $campaign->owner->name ?? '-' }}</span>
                                </div>
                                <h3 class="h6 fw-bold mb-1 text-truncate">
                                    <a href="{{ route('campaigns.show', $campaign) }}" class="text-main text-decoration-none">{{ $campaign->title }}</a>
                                </h3>
                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span class="fw-bold" style="color: var(--color-primary);">{{ \App\Support\Rupiah::format($campaign->terkumpul) }}</span>
                                    <span>dari {{ \App\Support\Rupiah::short($campaign->target_amount) }}</span>
                                    <span class="fw-semibold text-primary">({{ $campaign->persen }}%)</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 flex-shrink-0 mt-2 mt-md-0">
                            <div class="d-none d-lg-block" style="width: 130px;">
                                <div class="progress-track" style="height: 6px;">
                                    <div class="progress-fill {{ $campaign->persen >= 100 ? 'done' : '' }}" style="width: {{ $campaign->progress }}%"></div>
                                </div>
                            </div>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-outline-teal btn-sm px-3 text-nowrap">
                                Pantau Progres <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif

{{-- ===== Kategori ===== --}}
<section class="container mt-5">
    <h2 class="section-title">Pilih kategori</h2>
    <p class="text-muted">Temukan campaign sesuai kepedulian Anda.</p>
    <div class="d-flex flex-wrap gap-2">
        @foreach($categories as $cat)
            <a href="{{ route('campaigns.index', ['kategori' => $cat->id_category]) }}" class="chip">
                {{ $cat->name }} <span class="count">{{ $cat->aktif_count }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- ===== Campaign Terbaru (Bentuk Card Kotak, Muat 4 Card per Baris) ===== --}}
<section class="container mt-5" id="campaigns">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h2 class="section-title">
                {{ (Auth::check() && Auth::user()->role === 'donatur') ? 'Rekomendasi Campaign Lainnya' : 'Campaign terbaru' }}
            </h2>
            <p class="text-muted mb-0">Semua campaign sudah diverifikasi admin secara transparan.</p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="fw-semibold text-decoration-none">Lihat semua &rarr;</a>
    </div>

    @if($campaigns->isEmpty())
        <div class="panel panel-pad text-center text-muted py-5">Belum ada campaign aktif. Silakan kembali lagi nanti.</div>
    @else
        <div class="row g-3">
            @foreach($campaigns as $campaign)
                <div class="col-sm-6 col-lg-3">
                    @include('partials.campaign-card', ['campaign' => $campaign])
                </div>
            @endforeach
        </div>
    @endif
</section>

{{-- ===== Top donatur ===== --}}
<section class="container mt-5" id="top-donors">
    <div class="row g-4">
        <div class="col-lg-5">
            <h2 class="section-title">Top donatur</h2>
            <p class="text-muted">Terima kasih kepada para donatur dengan kontribusi terbesar. Donatur yang memilih anonim tampil sebagai Anonim.</p>
            <a href="{{ route('top-donors') }}" class="btn btn-outline-teal">Lihat peringkat lengkap &rarr;</a>
        </div>
        <div class="col-lg-7">
            <div class="panel">
                @forelse($topDonors as $i => $donor)
                    <div class="rank-row">
                        <span class="rank-no">{{ $i + 1 }}</span>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $donor->display_name }}</div>
                            <div class="text-muted small">{{ $donor->jumlah }} kali donasi</div>
                        </div>
                        <div class="fw-bold" style="color: var(--color-primary)">{{ \App\Support\Rupiah::format($donor->total) }}</div>
                    </div>
                @empty
                    <div class="panel-pad text-center text-muted">Belum ada donasi yang berhasil.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- ===== Cara kerja (urutan langkah) ===== --}}
<section class="container mt-5 mb-5" id="cara-kerja">
    <h2 class="section-title">Cara kerja</h2>
    <p class="text-muted">Tiga langkah dari memilih campaign sampai menerima sertifikat.</p>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="panel panel-pad h-100">
                <span class="step-no mb-3">1</span>
                <h3 class="h5">Pilih campaign</h3>
                <p class="text-muted mb-0">Telusuri campaign, baca ceritanya, dan cek laporan penyaluran dananya.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-pad h-100">
                <span class="step-no mb-3">2</span>
                <h3 class="h5">Donasi dan bayar</h3>
                <p class="text-muted mb-0">Isi nominal sesuai kemampuan, lalu bayar lewat metode yang tersedia.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-pad h-100">
                <span class="step-no mb-3">3</span>
                <h3 class="h5">Dapatkan sertifikat</h3>
                <p class="text-muted mb-0">Donasi yang mencapai batas tertentu mendapat sertifikat digital yang bisa diunduh.</p>
            </div>
        </div>
    </div>
</section>

@endsection
