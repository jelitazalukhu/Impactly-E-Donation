@extends('layouts.app')

@section('title', 'Riwayat Donasi Saya - Impactly')

@section('content')
<div class="container py-4 py-lg-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-main mb-1">Riwayat Donasi Saya</h1>
            <p class="text-muted small mb-0">Pantau seluruh catatan donasi, status pembayaran, dan sertifikat penghargaan Anda.</p>
        </div>
        <div class="panel px-3 py-2 bg-soft-sage border-sage">
            <span class="text-muted small">Total Berdonasi:</span>
            <span class="fw-bold fs-5 text-primary-green ms-1">{{ \App\Support\Rupiah::format($totalPaid) }}</span>
        </div>
    </div>

    {{-- Filter Nav Tabs --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('donations.history') }}" class="chip {{ $current === 'all' ? 'active' : '' }}">
            Semua <span class="count">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('donations.history', ['status' => 'paid']) }}" class="chip {{ $current === 'paid' ? 'active' : '' }}">
            Berhasil <span class="count">{{ $counts['paid'] }}</span>
        </a>
        <a href="{{ route('donations.history', ['status' => 'pending']) }}" class="chip {{ $current === 'pending' ? 'active' : '' }}">
            Menunggu <span class="count">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('donations.history', ['status' => 'failed']) }}" class="chip {{ $current === 'failed' ? 'active' : '' }}">
            Gagal <span class="count">{{ $counts['failed'] }}</span>
        </a>
    </div>

    {{-- Content List --}}
    <div class="panel">
        @if($donations->isEmpty())
            <div class="text-center py-5 px-3">
                <div class="fs-1 text-muted mb-2"><i class="bi bi-inbox"></i></div>
                <div class="fw-semibold text-main">Belum ada catatan donasi.</div>
                <p class="text-muted small mb-3">Mari mulai berbagi kebaikan dengan berdonasi pada campaign yang sedang membutuhkan bantuan.</p>
                <a href="{{ route('campaigns.index') }}" class="btn btn-teal">
                    <i class="bi bi-search me-1"></i> Jelajahi Campaign
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Campaign</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $don)
                            <tr>
                                <td class="text-nowrap small text-muted">
                                    {{ $don->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td>
                                    <a href="{{ route('campaigns.show', $don->campaign) }}" class="fw-bold text-main d-block">
                                        {{ $don->campaign->title }}
                                    </a>
                                    <span class="small text-muted">{{ $don->campaign->category->name ?? 'Umum' }}</span>
                                    @if($don->message)
                                        <div class="small fst-italic text-muted mt-1">&ldquo;{{ \Illuminate\Support\Str::limit($don->message, 60) }}&rdquo;</div>
                                    @endif
                                </td>
                                <td class="fw-bold text-nowrap text-primary-green">
                                    {{ \App\Support\Rupiah::format($don->amount) }}
                                    @if($don->is_anonymous)
                                        <div class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">Anonim</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-status s-{{ $don->status }}">
                                        @if($don->status === 'paid')
                                            <i class="bi bi-check-circle-fill"></i> Berhasil
                                        @elseif($don->status === 'pending')
                                            <i class="bi bi-clock"></i> Menunggu
                                        @elseif($don->status === 'failed')
                                            <i class="bi bi-x-circle-fill"></i> Gagal
                                        @else
                                            {{ ucfirst($don->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    @if($don->status === 'pending')
                                        <a href="{{ route('donasi.payment', $don) }}" class="btn btn-teal btn-sm">
                                            <i class="bi bi-credit-card me-1"></i> Bayar
                                        </a>
                                    @elseif($don->status === 'paid' && $don->certificate)
                                        <a href="{{ route('donations.certificate.download', $don->certificate) }}" class="btn btn-outline-teal btn-sm" title="Unduh Sertifikat PDF">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Sertifikat
                                        </a>
                                    @else
                                        <a href="{{ route('campaigns.show', $don->campaign) }}" class="btn btn-outline-secondary btn-sm">
                                            Lihat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($donations->hasPages())
                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $donations->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
