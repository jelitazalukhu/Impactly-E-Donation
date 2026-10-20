@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
@php
    $role = auth()->check() ? auth()->user()->role : null;
    $sisaDanaTerkumpul = max(0, $campaign->terkumpul - $disbursed);
@endphp

<div class="container py-4 py-lg-5">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campaign</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($campaign->title, 40) }}</li>
        </ol>
    </nav>

    @if($pratinjau)
        <div class="alert alert-warning">
            <strong>Pratinjau.</strong> Campaign ini berstatus <strong>{{ $campaign->status }}</strong> dan belum tampil ke publik.
            @if($campaign->status === 'ditolak' && $campaign->rejection_note)
                <div class="mt-1">Alasan penolakan: {{ $campaign->rejection_note }}</div>
            @endif
        </div>
    @endif

    <div class="row g-4 g-lg-5">

        {{-- ===== Kolom kiri: isi campaign ===== --}}
        <div class="col-lg-8">
            <div class="detail-cover mb-4">
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}">
                @else
                    <div class="thumb-placeholder"><i class="bi {{ $campaign->icon }}" aria-hidden="true"></i></div>
                @endif
            </div>

            <div class="mb-2"><span class="cat-badge">{{ $campaign->category->name ?? 'Umum' }}</span></div>
            <h1 class="h2 mb-3">{{ $campaign->title }}</h1>
            <div class="d-flex align-items-center gap-2 mb-4 text-muted">
                <i class="bi bi-person-circle fs-5" aria-hidden="true"></i>
                <span>Penggalang: <strong class="text-body">{{ $campaign->owner->organization_name ?? $campaign->owner->name ?? '-' }}</strong></span>
                @if($campaign->status === 'aktif')
                    <span class="badge-status s-aktif"><i class="bi bi-patch-check-fill me-1" aria-hidden="true"></i>Terverifikasi</span>
                @endif
            </div>

            <ul class="nav nav-tabs mb-4" id="detailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-desk" data-bs-toggle="tab" data-bs-target="#pane-desk" type="button" role="tab" aria-controls="pane-desk" aria-selected="true">Deskripsi</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-berita" data-bs-toggle="tab" data-bs-target="#pane-berita" type="button" role="tab" aria-controls="pane-berita" aria-selected="false">Berita <span class="text-muted fw-normal">({{ $updates->count() }})</span></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-dana" data-bs-toggle="tab" data-bs-target="#pane-dana" type="button" role="tab" aria-controls="pane-dana" aria-selected="false">Penyaluran Dana</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-donatur" data-bs-toggle="tab" data-bs-target="#pane-donatur" type="button" role="tab" aria-controls="pane-donatur" aria-selected="false">Donatur <span class="text-muted fw-normal">({{ $donorCount }})</span></button>
                </li>
            </ul>

            <div class="tab-content">

                {{-- Deskripsi --}}
                <div class="tab-pane fade show active" id="pane-desk" role="tabpanel" aria-labelledby="tab-desk" tabindex="0">
                    <div class="panel panel-pad">
                        {!! nl2br(e($campaign->description)) !!}
                    </div>
                </div>

                {{-- Berita --}}
                <div class="tab-pane fade" id="pane-berita" role="tabpanel" aria-labelledby="tab-berita" tabindex="0">
                    <div class="panel panel-pad">
                        @forelse($updates as $update)
                            <div class="timeline-item">
                                <div class="text-muted small">{{ $update->created_at->translatedFormat('d F Y') }}</div>
                                <h3 class="h6 mb-1">{{ $update->title }}</h3>
                                <div>{!! nl2br(e($update->content)) !!}</div>
                                @if($update->image)
                                    <img src="{{ asset('storage/' . $update->image) }}" alt="{{ $update->title }}" class="img-fluid rounded-3 mt-2" style="max-height: 260px;">
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Penggalang belum memposting berita perkembangan.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Penyaluran dana --}}
                <div class="tab-pane fade" id="pane-dana" role="tabpanel" aria-labelledby="tab-dana" tabindex="0">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <div class="stat-card"><div class="stat-value">{{ \App\Support\Rupiah::short($campaign->terkumpul) }}</div><div class="stat-label">Dana terkumpul</div></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-card"><div class="stat-value">{{ \App\Support\Rupiah::short($disbursed) }}</div><div class="stat-label">Sudah disalurkan</div></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-card"><div class="stat-value">{{ \App\Support\Rupiah::short($sisaDanaTerkumpul) }}</div><div class="stat-label">Belum disalurkan</div></div>
                        </div>
                    </div>
                    <div class="panel">
                        @if($distributions->isEmpty())
                            <div class="text-center text-muted py-4 px-3">Belum ada penyaluran dana yang dilaporkan.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead>
                                        <tr><th>Tanggal</th><th>Penerima</th><th>Keterangan</th><th class="text-end">Nominal</th><th>Bukti</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($distributions as $d)
                                            <tr>
                                                <td class="text-nowrap">{{ $d->distributed_at->translatedFormat('d M Y') }}</td>
                                                <td>{{ $d->recipient }}</td>
                                                <td>{{ $d->description }}</td>
                                                <td class="text-end fw-semibold text-nowrap">{{ \App\Support\Rupiah::format($d->amount) }}</td>
                                                <td>
                                                    @if($d->proof_file)
                                                        <a href="{{ asset('storage/' . $d->proof_file) }}" target="_blank" rel="noopener">Lihat</a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Donatur --}}
                <div class="tab-pane fade" id="pane-donatur" role="tabpanel" aria-labelledby="tab-donatur" tabindex="0">
                    <div class="panel">
                        @forelse($donations as $donation)
                            <div class="rank-row align-items-start">
                                <i class="bi bi-person-circle fs-4 text-muted" aria-hidden="true"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between gap-2">
                                        <span class="fw-semibold">{{ $donation->displayName() }}</span>
                                        <span class="fw-bold text-nowrap" style="color: var(--teal-dark)">{{ \App\Support\Rupiah::format($donation->amount) }}</span>
                                    </div>
                                    <div class="text-muted small">{{ optional($donation->paid_at)->diffForHumans() }}</div>
                                    @if($donation->message)
                                        <div class="mt-1 fst-italic">&ldquo;{{ $donation->message }}&rdquo;</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4 px-3">Belum ada donasi. Jadilah yang pertama.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Kolom kanan: ringkasan dan tombol donasi ===== --}}
        <div class="col-lg-4">
            <div class="panel panel-pad sticky-side">
                <div class="fs-3 fw-bold" style="color: var(--teal-dark)">{{ \App\Support\Rupiah::format($campaign->terkumpul) }}</div>
                <div class="text-muted mb-3">terkumpul dari target {{ \App\Support\Rupiah::format($campaign->target_amount) }}</div>

                <div class="progress-track mb-2" role="progressbar" aria-valuenow="{{ $campaign->progress }}" aria-valuemin="0" aria-valuemax="100" aria-label="Progress donasi">
                    <div class="progress-fill {{ $campaign->persen >= 100 ? 'done' : '' }}" style="width: {{ $campaign->progress }}%"></div>
                </div>
                <div class="fw-bold mb-3" style="color: var(--teal)">{{ $campaign->persen }}% tercapai</div>

                <div class="row text-center g-2 mb-3">
                    <div class="col-6">
                        <div class="border rounded-3 py-2">
                            <div class="fw-bold fs-5">{{ $donorCount }}</div>
                            <div class="text-muted small">Donatur</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded-3 py-2">
                            <div class="fw-bold fs-5">{{ $campaign->sisa_hari }}</div>
                            <div class="text-muted small">Hari lagi</div>
                        </div>
                    </div>
                </div>
                <div class="text-muted small mb-3"><i class="bi bi-calendar-event me-1" aria-hidden="true"></i>Berakhir {{ $campaign->deadline->translatedFormat('d F Y') }}</div>

                @if($campaign->berakhir)
                    <button type="button" class="btn btn-secondary w-100" disabled>Campaign sudah berakhir</button>
                @elseif($role === null || $role === 'donatur')
                    <a href="{{ route('donasi.create', $campaign) }}" class="btn btn-donate w-100">Donasi Sekarang</a>
                    @if($role === null)
                        <div class="text-muted small text-center mt-2">Anda akan diminta masuk terlebih dahulu.</div>
                    @endif
                @else
                    <div class="alert alert-light border small mb-0">Hanya akun donatur yang dapat berdonasi.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
