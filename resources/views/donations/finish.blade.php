@extends('layouts.app')

@section('title', 'Status Pembayaran - Impactly')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            @if($donation->status === 'paid')
                {{-- Pembayaran Berhasil --}}
                <div class="panel p-4 p-md-5 text-center mb-4 shadow-sm">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                              style="width: 80px; height: 80px; background-color: #D1FAE5; color: var(--color-success); font-size: 2.5rem;">
                            <i class="bi bi-check-lg"></i>
                        </span>
                    </div>

                    <h1 class="h3 fw-bold text-main mb-2">Pembayaran Berhasil!</h1>
                    <p class="text-muted mb-4">Terima kasih atas kebaikan dan kepedulian Anda. Setiap rupiah yang Anda salurkan membawa harapan besar bagi mereka yang membutuhkan.</p>

                    <div class="p-3 rounded-3 bg-light border border-sage text-start mb-4">
                        <div class="d-flex justify-content-between py-1.5 border-bottom border-sage">
                            <span class="text-muted small">Nominal Donasi</span>
                            <span class="fw-bold fs-5" style="color: var(--color-primary);">{{ \App\Support\Rupiah::format($donation->amount) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1.5 border-bottom border-sage">
                            <span class="text-muted small">Campaign</span>
                            <span class="fw-semibold text-main text-end" style="max-width: 60%;">{{ $donation->campaign->title }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1.5 border-bottom border-sage">
                            <span class="text-muted small">Atas Nama</span>
                            <span class="fw-semibold text-main">{{ $donation->displayName() }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1.5">
                            <span class="text-muted small">Waktu Transaksi</span>
                            <span class="text-muted small">{{ optional($donation->paid_at)->translatedFormat('d F Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    {{-- Section Sertifikat Digital jika ada --}}
                    @if($donation->certificate)
                        <div class="p-4 rounded-3 border mb-4 text-start" style="background: var(--color-accent); border-color: var(--color-secondary-accent) !important;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="fs-1" style="color: var(--color-primary);"><i class="bi bi-award-fill"></i></div>
                                <div class="flex-grow-1">
                                    <h2 class="h6 fw-bold text-main mb-1">Sertifikat Donatur Digital Resmi Telah Terbit!</h2>
                                    <p class="small text-muted mb-3">Sebagai wujud apresiasi, sertifikat penghargaan resmi atas nama Anda telah siap diunduh.</p>
                                    <a href="{{ route('donations.certificate.download', $donation->certificate) }}" class="btn btn-teal btn-sm px-3">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh Sertifikat (PDF)
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a href="{{ route('home') }}" class="btn btn-teal">
                            <i class="bi bi-house me-1"></i> Kembali ke Beranda
                        </a>
                        <a href="{{ route('donations.history') }}" class="btn btn-outline-teal">
                            <i class="bi bi-clock-history me-1"></i> Riwayat Donasi
                        </a>
                        <a href="{{ route('campaigns.show', $donation->campaign) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i> Lihat Campaign
                        </a>
                    </div>
                </div>

            @else
                {{-- Pembayaran Gagal / Dibatalkan --}}
                <div class="panel p-4 p-md-5 text-center mb-4 shadow-sm">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                              style="width: 80px; height: 80px; background-color: #FEE2E2; color: var(--color-danger); font-size: 2.5rem;">
                            <i class="bi bi-x-lg"></i>
                        </span>
                    </div>

                    <h1 class="h3 fw-bold text-main mb-2">Pembayaran Belum Berhasil</h1>
                    <p class="text-muted mb-4">Transaksi tidak dapat diproses atau telah dibatalkan. Anda dapat mengulangi pembayaran kapan saja.</p>

                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-1"></i> Kembali ke Beranda
                        </a>
                        <a href="{{ route('donasi.payment', $donation) }}" class="btn btn-teal">
                            <i class="bi bi-arrow-clockwise me-1"></i> Coba Bayar Lagi
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
