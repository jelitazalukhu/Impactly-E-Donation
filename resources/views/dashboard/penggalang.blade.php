@extends('layouts.dashboard')

@section('title', 'Dashboard Penggalang')
@section('heading', 'Dashboard Penggalang Dana')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Halo, {{ auth()->user()->name }}</h1>
        <p class="text-muted small mb-0">Selamat datang di panel penggalang dana. Pantau performa program dan transparansi Anda.</p>
    </div>
    <a href="{{ route('penggalang.campaigns.create') }}" class="btn btn-teal">
        <i class="bi bi-plus-lg me-1"></i> Buat Campaign Baru
    </a>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value text-primary-green">{{ \App\Support\Rupiah::short($stats['terkumpul']) }}</div>
            <div class="stat-label">Dana Terkumpul (Paid)</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon text-info bg-light"><i class="bi bi-send-check text-info"></i></div>
            <div class="stat-value text-info">{{ \App\Support\Rupiah::short($stats['disalurkan']) }}</div>
            <div class="stat-label">Telah Disalurkan</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-megaphone"></i></div>
            <div class="stat-value text-main">{{ $stats['aktif'] }} <span class="fs-6 text-muted fw-normal">/ {{ $stats['campaign'] }}</span></div>
            <div class="stat-label">Campaign Aktif (dari Total)</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon text-warning bg-light"><i class="bi bi-clock-history text-warning"></i></div>
            <div class="stat-value text-warning">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Chart.js Grafik Donasi Bulanan --}}
    <div class="col-lg-7">
        <div class="panel p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold text-main mb-0"><i class="bi bi-graph-up me-2 text-primary-green"></i>Tren Donasi Masuk</h2>
                <span class="badge bg-soft-sage text-primary-green">Bulan ke Bulan</span>
            </div>
            <div style="height: 240px; position: relative;">
                <canvas id="donorChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Aksi Cepat / Quick Links --}}
    <div class="col-lg-5">
        <div class="panel p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <h2 class="h6 fw-bold text-main mb-3"><i class="bi bi-lightning-charge me-2 text-primary-green"></i>Aksi Cepat</h2>
                <div class="list-group list-group-flush border-0">
                    <a href="{{ route('penggalang.campaigns.create') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 px-0 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle-fill text-primary-green fs-5"></i>
                            <div>
                                <div class="fw-bold text-main small">Buat Campaign Baru</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Mulai galang dana untuk program bantuan</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('penggalang.updates.index') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 px-0 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-newspaper text-info fs-5"></i>
                            <div>
                                <div class="fw-bold text-main small">Tulis Berita Perkembangan</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Beri kabar update kondisi program ke donatur</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('penggalang.distributions.index') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 px-0">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-send-check text-warning fs-5"></i>
                            <div>
                                <div class="fw-bold text-main small">Catat Penyaluran Dana</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Upload bukti penyaluran dan transparansi</div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                </div>
            </div>
            <div class="p-3 rounded-3 bg-light border border-sage mt-3">
                <div class="small fw-semibold text-main mb-1"><i class="bi bi-info-circle me-1 text-primary-green"></i>Tips Penggalangan:</div>
                <div class="text-muted" style="font-size: 0.8rem;">Campaign yang rutin memposting berita perkembangan dan kuitansi penyaluran dana 3x lebih cepat mencapai target donasi!</div>
            </div>
        </div>
    </div>
</div>

{{-- Campaign Terbaru --}}
<div class="panel">
    <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
        <h2 class="h5 fw-bold text-main mb-0">Campaign Saya</h2>
        <a href="{{ route('penggalang.campaigns.index') }}" class="btn btn-outline-teal btn-sm">Lihat Semua</a>
    </div>
    @if($campaigns->isEmpty())
        <div class="text-center text-muted py-5 px-3">
            <i class="bi bi-megaphone fs-1 d-block mb-2"></i>
            Anda belum memiliki campaign. Klik tombol <strong>Buat Campaign Baru</strong> di atas untuk memulai.
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Judul Campaign</th>
                        <th>Status</th>
                        <th>Dana Terkumpul</th>
                        <th>Progress</th>
                        <th>Deadline</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $c)
                        <tr>
                            <td>
                                <a href="{{ route('campaigns.show', $c) }}" class="fw-bold text-main text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($c->title, 45) }}
                                </a>
                                <div class="text-muted small">{{ $c->category->name ?? '-' }}</div>
                            </td>
                            <td>@include('partials.status-badge', ['status' => $c->status])</td>
                            <td class="text-nowrap fw-bold text-primary-green">
                                {{ \App\Support\Rupiah::format($c->terkumpul) }}
                                <div class="text-muted small fw-normal">dari {{ \App\Support\Rupiah::short($c->target_amount) }}</div>
                            </td>
                            <td style="min-width: 120px;">
                                <div class="progress-track mb-1">
                                    <div class="progress-fill {{ $c->persen >= 100 ? 'done' : '' }}" style="width: {{ $c->progress }}%;"></div>
                                </div>
                                <div class="small text-muted">{{ $c->persen }}%</div>
                            </td>
                            <td class="text-nowrap small">{{ $c->deadline->translatedFormat('d M Y') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('penggalang.campaigns.edit', $c) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('donorChart');
if (ctx) {
    const labels = @json($chartLabels);
    const data = @json($chartValues);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['Belum ada data'],
            datasets: [{
                label: 'Dana Donasi Masuk (Rp)',
                data: data.length ? data : [0],
                backgroundColor: 'rgba(22, 101, 52, 0.75)',
                borderColor: '#166534',
                borderWidth: 1.5,
                borderRadius: 6,
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
                            if (value >= 1000) return 'Rp ' + (value/1000) + 'rb';
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
</script>
@endpush
