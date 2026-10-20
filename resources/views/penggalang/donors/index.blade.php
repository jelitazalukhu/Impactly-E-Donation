@extends('layouts.dashboard')

@section('title', 'Daftar Donatur - Penggalang')
@section('heading', 'Daftar Donatur')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Daftar Donatur</h1>
        <p class="text-muted small mb-0">Daftar para donatur yang telah menyalurkan bantuan pada program-program Anda.</p>
    </div>
    <div class="panel px-3 py-2 bg-soft-sage border-sage">
        <span class="text-muted small">Total Dana Masuk:</span>
        <span class="fw-bold fs-5 text-primary-green ms-1">{{ \App\Support\Rupiah::format($totalPaid) }}</span>
        <span class="text-muted small">({{ $totalCount }} donasi)</span>
    </div>
</div>

{{-- Filter Campaign Dropdown --}}
<div class="panel p-3 mb-4">
    <form method="GET" action="{{ route('penggalang.donors.index') }}" class="row g-2 align-items-center">
        <div class="col-auto"><label class="form-label small fw-bold text-muted mb-0">Filter Campaign:</label></div>
        <div class="col-md-5">
            <select name="campaign" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Campaign Saya</option>
                @foreach($campaigns as $camp)
                    <option value="{{ $camp->id_campaign }}" {{ (string)$selectedId === (string)$camp->id_campaign ? 'selected' : '' }}>
                        {{ $camp->title }}
                    </option>
                @endforeach
            </select>
        </div>
        @if($selectedId)
            <div class="col-auto">
                <a href="{{ route('penggalang.donors.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        @endif
    </form>
</div>

{{-- Tabel Donatur --}}
<div class="panel">
    @if($donations->isEmpty())
        <div class="text-center py-5 px-3">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-people"></i></div>
            <div class="fw-semibold text-main">Belum ada donatur pada campaign ini.</div>
            <p class="text-muted small mb-0">Donasi yang telah dibayar (paid) akan otomatis tercatat di sini.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Waktu Pembayaran</th>
                        <th>Donatur</th>
                        <th>Campaign</th>
                        <th>Nominal</th>
                        <th>Doa / Pesan Kebaikan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $don)
                        <tr>
                            <td class="text-nowrap small text-muted">
                                {{ optional($don->paid_at ?? $don->created_at)->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td>
                                <div class="fw-bold text-main">{{ $don->displayName() }}</div>
                                @if($don->is_anonymous)
                                    <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">Anonim</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('campaigns.show', $don->campaign) }}" class="text-main text-decoration-none fw-semibold">
                                    {{ $don->campaign->title }}
                                </a>
                            </td>
                            <td class="fw-bold text-primary-green text-nowrap">
                                {{ \App\Support\Rupiah::format($don->amount) }}
                            </td>
                            <td>
                                @if($don->message)
                                    <div class="small fst-italic text-muted">&ldquo;{{ $don->message }}&rdquo;</div>
                                @else
                                    <span class="text-muted small">-</span>
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
@endsection
