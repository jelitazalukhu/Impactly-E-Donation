@extends('layouts.dashboard')

@section('title', 'Verifikasi Campaign - Admin')
@section('heading', 'Verifikasi Pengajuan Campaign')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Verifikasi Pengajuan Campaign</h1>
        <p class="text-muted small mb-0">Tinjau kelayakan dan keaslian program sebelum ditampilkan ke publik dan menerima donasi.</p>
    </div>
    <span class="badge bg-warning text-dark px-3 py-2 fs-6">
        <i class="bi bi-clock-history me-1"></i> {{ $campaigns->total() }} Menunggu Verifikasi
    </span>
</div>

<div class="panel">
    @if($campaigns->isEmpty())
        <div class="text-center py-5 px-3">
            <div class="fs-1 text-success mb-2"><i class="bi bi-patch-check-fill"></i></div>
            <div class="fw-semibold text-main fs-5">Semua Pengajuan Telah Ditinjau!</div>
            <p class="text-muted small mb-0">Saat ini tidak ada campaign yang sedang menunggu verifikasi.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="min-width: 280px;">Campaign & Penggalang</th>
                        <th>Kategori</th>
                        <th>Target Dana</th>
                        <th>Batas Waktu</th>
                        <th>Tanggal Pengajuan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $c)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 54px; height: 42px; border-radius: 6px; overflow: hidden; flex-shrink: 0;" class="bg-soft-sage">
                                        @if($c->image)
                                            <img src="{{ asset('storage/' . $c->image) }}" alt="{{ $c->title }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-primary-green fs-5">
                                                <i class="bi {{ $c->icon }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-main">{{ $c->title }}</div>
                                        <div class="small text-muted">
                                            Penggalang: <strong>{{ $c->owner->organization_name ?? $c->owner->name }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="cat-badge">{{ $c->category->name ?? 'Umum' }}</span></td>
                            <td class="fw-bold text-primary-green">{{ \App\Support\Rupiah::format($c->target_amount) }}</td>
                            <td class="small">{{ $c->deadline->translatedFormat('d M Y') }}</td>
                            <td class="small text-muted">{{ $c->updated_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.verifications.show', $c) }}" class="btn btn-teal btn-sm">
                                    <i class="bi bi-search me-1"></i> Tinjau & Verifikasi
                                </a>
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
    @endif
</div>
@endsection
