@extends('layouts.dashboard')

@section('title', 'Pantau Penyaluran Dana - Admin')
@section('heading', 'Transparansi Penyaluran Dana Platform')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Transparansi Penyaluran Dana</h1>
        <p class="text-muted small mb-0">Pantau perbandingan total donasi yang masuk dengan realisasi penyaluran dana oleh penggalang.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value text-primary-green">{{ \App\Support\Rupiah::short($totalCollected) }}</div>
            <div class="stat-label">Total Dana Masuk Platform</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon text-info bg-light"><i class="bi bi-send-check text-info"></i></div>
            <div class="stat-value text-info">{{ \App\Support\Rupiah::short($totalDisbursed) }}</div>
            <div class="stat-label">Total Dana Disalurkan</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon text-warning bg-light"><i class="bi bi-hourglass-split text-warning"></i></div>
            <div class="stat-value text-warning">{{ \App\Support\Rupiah::short($totalUndisbursed) }}</div>
            <div class="stat-label">Total Belum Disalurkan</div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th style="min-width: 250px;">Campaign & Penggalang</th>
                    <th>Dana Terkumpul</th>
                    <th>Sudah Disalurkan</th>
                    <th>Sisa Belum Disalurkan</th>
                    <th>Laporan Riwayat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $camp)
                    @php
                        $disbursed = (float) $camp->distributions->sum('amount');
                        $sisa = max(0, $camp->terkumpul - $disbursed);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('campaigns.show', $camp) }}" target="_blank" class="fw-bold text-main text-decoration-none d-block">
                                {{ $camp->title }}
                            </a>
                            <span class="small text-muted">Oleh: {{ $camp->owner->organization_name ?? $camp->owner->name }}</span>
                        </td>
                        <td class="fw-bold text-primary-green text-nowrap">
                            {{ \App\Support\Rupiah::format($camp->terkumpul) }}
                        </td>
                        <td class="fw-bold text-info text-nowrap">
                            {{ \App\Support\Rupiah::format($disbursed) }}
                            <div class="small text-muted">{{ $camp->distributions->count() }} kali penyaluran</div>
                        </td>
                        <td class="fw-bold text-warning text-nowrap">
                            {{ \App\Support\Rupiah::format($sisa) }}
                        </td>
                        <td class="text-nowrap">
                            <button type="button" class="btn btn-outline-teal btn-sm" data-bs-toggle="modal" data-bs-target="#distModal{{ $camp->id_campaign }}">
                                <i class="bi bi-receipt me-1"></i> Rincian ({{ $camp->distributions->count() }})
                            </button>

                            {{-- Modal Detail Penyaluran --}}
                            <div class="modal fade" id="distModal{{ $camp->id_campaign }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Riwayat Penyaluran: {{ $camp->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-0">
                                            @if($camp->distributions->isEmpty())
                                                <div class="text-center py-4 text-muted">Belum ada penyaluran dana yang dicatat oleh penggalang.</div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table mb-0 align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <th>Penerima</th>
                                                                <th>Keterangan</th>
                                                                <th class="text-end">Nominal</th>
                                                                <th>Bukti</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($camp->distributions as $d)
                                                                <tr>
                                                                    <td class="small">{{ $d->distributed_at->translatedFormat('d M Y') }}</td>
                                                                    <td class="fw-semibold">{{ $d->recipient }}</td>
                                                                    <td class="small">{{ $d->description }}</td>
                                                                    <td class="text-end fw-bold text-primary-green">{{ \App\Support\Rupiah::format($d->amount) }}</td>
                                                                    <td>
                                                                        @if($d->proof_file)
                                                                            <a href="{{ asset('storage/' . $d->proof_file) }}" target="_blank" class="small btn btn-outline-teal btn-sm py-0">Lihat</a>
                                                                        @else
                                                                            <span class="text-muted small">-</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($campaigns->hasPages())
        <div class="p-3 border-top">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>
@endsection
