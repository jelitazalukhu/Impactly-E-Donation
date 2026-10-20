@extends('layouts.dashboard')

@section('title', 'Tinjau Verifikasi - ' . $campaign->title)
@section('heading', 'Tinjau Pengajuan Campaign')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Verifikasi
            </a>
            <span class="badge-status s-menunggu"><i class="bi bi-clock"></i> Status: Menunggu Verifikasi</span>
        </div>

        <div class="panel p-4 p-md-5 mb-4">
            {{-- Foto Cover --}}
            <div class="detail-cover mb-4">
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-100 h-100 object-fit-cover">
                @else
                    <div class="thumb-placeholder"><i class="bi {{ $campaign->icon }}"></i></div>
                @endif
            </div>

            <div class="mb-2"><span class="cat-badge">{{ $campaign->category->name ?? 'Umum' }}</span></div>
            <h1 class="h3 fw-bold text-main mb-3">{{ $campaign->title }}</h1>

            {{-- Info Penggalang & Parameter --}}
            <div class="row g-3 p-3 rounded-3 bg-light border border-sage mb-4">
                <div class="col-sm-6">
                    <div class="small text-muted">Penggalang Dana:</div>
                    <div class="fw-bold text-main">{{ $campaign->owner->name }}</div>
                    @if($campaign->owner->organization_name)
                        <div class="small text-primary-green fw-semibold"><i class="bi bi-building me-1"></i>{{ $campaign->owner->organization_name }}</div>
                    @endif
                    <div class="small text-muted">{{ $campaign->owner->email }} | {{ $campaign->owner->phone ?? 'Tanpa nomor telepon' }}</div>
                </div>
                <div class="col-sm-3">
                    <div class="small text-muted">Target Dana:</div>
                    <div class="fw-bold text-primary-green fs-5">{{ \App\Support\Rupiah::format($campaign->target_amount) }}</div>
                </div>
                <div class="col-sm-3">
                    <div class="small text-muted">Batas Waktu:</div>
                    <div class="fw-bold text-main">{{ $campaign->deadline->translatedFormat('d F Y') }}</div>
                    <div class="small text-muted">{{ $campaign->sisa_hari }} hari lagi</div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <h2 class="h5 fw-bold text-main mb-2">Deskripsi & Rencana Penggunaan Dana</h2>
            <div class="p-3 rounded-3 bg-white border border-sage text-main mb-4">
                {!! nl2br(e($campaign->description)) !!}
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top">
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-1"></i> Tolak Campaign
                </button>
                <form method="POST" action="{{ route('admin.verifications.approve', $campaign) }}" onsubmit="return confirm('Setujui campaign ini agar dapat tampil publik dan menerima donasi?')">
                    @csrf
                    <button type="submit" class="btn btn-teal">
                        <i class="bi bi-check-circle-fill me-1"></i> Setujui & Aktifkan Campaign
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Modal Reject with mandatory reason --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.verifications.reject', $campaign) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Tolak Pengajuan Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Tuliskan alasan penolakan secara jelas. Penggalang dana dapat membaca alasan ini untuk memperbaiki dan mengajukan ulang campaign miliknya.</p>
                    <div class="mb-3">
                        <label for="rejection_note" class="form-label small fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_note" id="rejection_note" rows="4" class="form-control" placeholder="Contoh: Dokumen perizinan yayasan belum terlampir jelas, harap lengkapi deskripsi kontak pengurus..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-x-lg me-1"></i> Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
