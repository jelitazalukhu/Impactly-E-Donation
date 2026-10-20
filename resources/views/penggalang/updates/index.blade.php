@extends('layouts.dashboard')

@section('title', 'Kelola Berita Perkembangan - Penggalang')
@section('heading', 'Berita Perkembangan Campaign')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Berita Perkembangan</h1>
        <p class="text-muted small mb-0">Publikasikan kabar terbaru, dokumentasi kegiatan, dan progres penggunaan donasi.</p>
    </div>
    <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#createUpdateModal">
        <i class="bi bi-plus-lg me-1"></i> Tulis Berita Baru
    </button>
</div>

{{-- Filter Campaign Dropdown --}}
<div class="panel p-3 mb-4">
    <form method="GET" action="{{ route('penggalang.updates.index') }}" class="row g-2 align-items-center">
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
                <a href="{{ route('penggalang.updates.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        @endif
    </form>
</div>

{{-- Timeline Berita --}}
<div class="panel p-4">
    @if($updates->isEmpty())
        <div class="text-center py-5 px-3">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-newspaper"></i></div>
            <div class="fw-semibold text-main">Belum ada berita perkembangan yang diposting.</div>
            <p class="text-muted small mb-3">Posting kabar berkala meningkatkan kepercayaan donatur dan transparansi program Anda.</p>
            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#createUpdateModal">
                <i class="bi bi-pencil-square me-1"></i> Buat Berita Pertama
            </button>
        </div>
    @else
        <div class="timeline-list">
            @foreach($updates as $u)
                <div class="timeline-item pb-4">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <div>
                            <span class="badge bg-soft-sage text-primary-green small fw-bold mb-1">{{ $u->campaign->title }}</span>
                            <h2 class="h6 fw-bold text-main mb-0">{{ $u->title }}</h2>
                            <div class="small text-muted">{{ $u->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editUpdateModal{{ $u->id_campaign_update }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('penggalang.updates.destroy', $u) }}" onsubmit="return confirm('Hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="text-main mt-2 mb-2">{!! nl2br(e($u->content)) !!}</div>

                    @if($u->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $u->image) }}" alt="{{ $u->title }}" class="rounded-3 border" style="max-height: 220px;">
                        </div>
                    @endif
                </div>

                {{-- Modal Edit Update --}}
                <div class="modal fade" id="editUpdateModal{{ $u->id_campaign_update }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('penggalang.updates.update', $u) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Berita Perkembangan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Judul Berita <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ $u->title }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Isi Berita / Kabar Terbaru <span class="text-danger">*</span></label>
                                        <textarea name="content" rows="5" class="form-control" required>{{ $u->content }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Ganti Foto Dokumentasi</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @if($u->image)
                                            <div class="small text-muted mt-1">Foto saat ini tersimpan. Biarkan kosong jika tidak diubah.</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-teal btn-sm">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($updates->hasPages())
            <div class="p-3 border-top mt-3">
                {{ $updates->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Modal Create Update --}}
<div class="modal fade" id="createUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('penggalang.updates.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tulis Berita Perkembangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Campaign <span class="text-danger">*</span></label>
                        <select name="id_campaign" class="form-select" required>
                            <option value="">-- Pilih Campaign --</option>
                            @foreach($campaigns as $camp)
                                <option value="{{ $camp->id_campaign }}" {{ (string)$selectedId === (string)$camp->id_campaign ? 'selected' : '' }}>
                                    {{ $camp->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Penyaluran Paket Sembako Tahap 1 Selesai" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Isi Berita / Laporan Progres <span class="text-danger">*</span></label>
                        <textarea name="content" rows="5" class="form-control" placeholder="Ceritakan progres terkini program, kondisi penerima manfaat, dan bagaimana donasi digunakan..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Foto Dokumentasi Kegiatan</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text small text-muted">Format JPG, PNG, WEBP. Maks 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-teal btn-sm"><i class="bi bi-send-fill me-1"></i> Publikasikan Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
