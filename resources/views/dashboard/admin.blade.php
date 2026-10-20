@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('heading', 'Dashboard Admin Platform')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Ringkasan Platform Impactly</h1>
        <p class="text-muted small mb-0">Pantau performa menyeluruh, verifikasi campaign, dan kontrol kualitas platform.</p>
    </div>
    @if($stats['menunggu'] > 0)
        <a href="{{ route('admin.verifications.index') }}" class="btn btn-warning text-dark fw-bold">
            <i class="bi bi-clock-history me-1"></i> {{ $stats['menunggu'] }} Campaign Menunggu Verifikasi
        </a>
    @endif
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-value text-primary-green">{{ \App\Support\Rupiah::short($stats['dana']) }}</div>
            <div class="stat-label">Total Donasi Lunas (Platform)</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon text-info bg-light"><i class="bi bi-megaphone-fill text-info"></i></div>
            <div class="stat-value text-info">{{ $stats['campaign'] }}</div>
            <div class="stat-label">Total Seluruh Campaign</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon text-warning bg-light"><i class="bi bi-hourglass-split text-warning"></i></div>
            <div class="stat-value text-warning">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Perlu Verifikasi Admin</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value text-main">{{ $stats['donatur'] + $stats['penggalang'] }}</div>
            <div class="stat-label">{{ $stats['donatur'] }} Donatur, {{ $stats['penggalang'] }} Penggalang</div>
        </div>
    </div>
</div>

{{-- Grafik Platform --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="panel p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold text-main mb-0"><i class="bi bi-bar-chart-line me-2 text-primary-green"></i>Tren Donasi Masuk Platform</h2>
                <span class="badge bg-soft-sage text-primary-green">Bulan ke Bulan</span>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="adminTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel p-4 h-100">
            <h2 class="h6 fw-bold text-main mb-3"><i class="bi bi-pie-chart me-2 text-primary-green"></i>Distribusi Campaign</h2>
            <div style="height: 190px; position: relative;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-3 pt-2 border-top">
                @foreach($statusCounts as $st => $cnt)
                    <div class="d-flex justify-content-between align-items-center small py-1">
                        @include('partials.status-badge', ['status' => $st])
                        <span class="fw-bold">{{ $cnt }} campaign</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Campaign Menunggu Verifikasi --}}
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <h2 class="h6 fw-bold text-main mb-0"><i class="bi bi-patch-check me-2 text-warning"></i>Menunggu Verifikasi</h2>
                <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline-teal btn-sm">Lihat Semua</a>
            </div>
            @if($pendingCampaigns->isEmpty())
                <div class="text-center text-muted py-5 px-3">
                    <i class="bi bi-check-all fs-2 text-success d-block mb-1"></i>
                    Tidak ada pengajuan yang tertunda.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr><th>Campaign</th><th>Target</th><th class="text-end">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($pendingCampaigns as $pc)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-main">{{ \Illuminate\Support\Str::limit($pc->title, 35) }}</div>
                                        <div class="small text-muted">{{ $pc->owner->organization_name ?? $pc->owner->name }}</div>
                                    </td>
                                    <td class="text-primary-green fw-bold text-nowrap">{{ \App\Support\Rupiah::short($pc->target_amount) }}</td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('admin.verifications.show', $pc) }}" class="btn btn-teal btn-sm py-1">
                                            Tinjau
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Donasi Terbaru --}}
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <h2 class="h6 fw-bold text-main mb-0"><i class="bi bi-cash-stack me-2 text-primary-green"></i>Donasi Terbaru</h2>
                <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-teal btn-sm">Lihat Semua</a>
            </div>
            @if($recentDonations->isEmpty())
                <div class="text-center text-muted py-5 px-3">Belum ada catatan donasi.</div>
            @else
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr><th>Donatur</th><th>Nominal</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @foreach($recentDonations as $d)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-main">{{ $d->displayName() }}</div>
                                        <div class="small text-muted">{{ \Illuminate\Support\Str::limit($d->campaign->title ?? '-', 28) }}</div>
                                    </td>
                                    <td class="text-primary-green fw-bold text-nowrap">{{ \App\Support\Rupiah::format($d->amount) }}</td>
                                    <td>@include('partials.status-badge', ['status' => $d->status])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
// Trend Bar Chart
const trendCtx = document.getElementById('adminTrendChart');
if (trendCtx) {
    const labels = @json($chartLabels);
    const data = @json($chartValues);

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['Belum ada data'],
            datasets: [{
                label: 'Donasi Platform (Rp)',
                data: data.length ? data : [0],
                borderColor: '#166534',
                backgroundColor: 'rgba(22, 101, 52, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#166534',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value/1000000) + 'jt';
                            return value;
                        }
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

// Doughnut Status Chart
const statusCtx = document.getElementById('statusChart');
if (statusCtx) {
    const statusObj = @json($statusCounts);
    const sLabels = Object.keys(statusObj).map(s => s.toUpperCase());
    const sData = Object.values(statusObj);

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: sLabels,
            datasets: [{
                data: sData,
                backgroundColor: ['#166534', '#D97706', '#2563EB', '#DC2626', '#102419', '#647568'],
                borderWidth: 2,
                borderColor: '#FFFFFF',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12 } }
            }
        }
    });
}
</script>
@endpush
