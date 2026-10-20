@extends('layouts.dashboard')

@section('title', 'Campaign Saya - Penggalang')
@section('heading', 'Kelola Campaign Saya')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Campaign Saya</h1>
        <p class="text-muted small mb-0">Kelola penggalangan dana, pantau verifikasi admin, dan laporkan transparansi penyaluran.</p>
    </div>
    <a href="{{ route('penggalang.campaigns.create') }}" class="btn btn-teal">
        <i class="bi bi-plus-lg me-1"></i> Buat Campaign Baru
    </a>
</div>

{{-- Filter Status Tabs --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('penggalang.campaigns.index') }}" class="chip {{ $current === 'all' ? 'active' : '' }}">
        Semua <span class="count">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('penggalang.campaigns.index', ['status' => 'draft']) }}" class="chip {{ $current === 'draft' ? 'active' : '' }}">
        Draft <span class="count">{{ $counts['draft'] }}</span>
    </a>
    <a href="{{ route('penggalang.campaigns.index', ['status' => 'menunggu']) }}" class="chip {{ $current === 'menunggu' ? 'active' : '' }}">
        Menunggu Verifikasi <span class="count">{{ $counts['menunggu'] }}</span>
    </a>
    <a href="{{ route('penggalang.campaigns.index', ['status' => 'aktif']) }}" class="chip {{ $current === 'aktif' ? 'active' : '' }}">
        Aktif <span class="count">{{ $counts['aktif'] }}</span>
    </a>
    <a href="{{ route('penggalang.campaigns.index', ['status' => 'ditolak']) }}" class="chip {{ $current === 'ditolak' ? 'active' : '' }}">
        Ditolak <span class="count">{{ $counts['ditolak'] }}</span>
    </a>
    <a href="{{ route('penggalang.campaigns.index', ['status' => 'selesai']) }}" class="chip {{ $current === 'selesai' ? 'active' : '' }}">
        Selesai <span class="count">{{ $counts['selesai'] }}</span>
    </a>
</div>

{{-- Daftar Campaign --}}
<div class="panel">
    @if($campaigns->isEmpty())
        <div class="text-center py-5 px-3">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-megaphone"></i></div>
            <div class="fw-semibold text-main">Belum ada campaign dalam kategori ini.</div>
            <p class="text-muted small mb-3">Mulai buat penggalangan dana untuk program kebaikan Anda sekarang.</p>
            <a href="{{ route('penggalang.campaigns.create') }}" class="btn btn-teal">
                <i class="bi bi-plus-circle me-1"></i> Buat Campaign Baru
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="min-width: 280px;">Campaign</th>
                        <th>Target & Terkumpul</th>
                        <th>Progress</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $c)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 56px; height: 42px; border-radius: 6px; overflow: hidden; flex-shrink: 0;" class="bg-soft-sage">
                                        @if($c->image)
                                            <img src="{{ asset('storage/' . $c->image) }}" alt="{{ $c->title }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-primary-green fs-5">
                                                <i class="bi {{ $c->icon }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('campaigns.show', $c) }}" class="fw-bold text-main d-block">
                                            {{ $c->title }}
                                        </a>
                                        <span class="small text-muted">{{ $c->category->name ?? 'Umum' }}</span>
                                    </div>
                                </div>

                                {{-- Alasan penolakan jika ditolak --}}
                                @if($c->status === 'ditolak' && $c->rejection_note)
                                    <div class="alert alert-danger py-1 px-2 mt-2 mb-0 small">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Alasan Penolakan:</strong> {{ $c->rejection_note }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-primary-green">{{ \App\Support\Rupiah::format($c->terkumpul) }}</div>
                                <div class="small text-muted">dari {{ \App\Support\Rupiah::format($c->target_amount) }}</div>
                            </td>
                            <td style="min-width: 130px;">
                                <div class="progress-track mb-1">
                                    <div class="progress-fill {{ $c->persen >= 100 ? 'done' : '' }}" style="width: {{ $c->progress }}%;"></div>
                                </div>
                                <div class="small fw-semibold text-muted">{{ $c->persen }}% ({{ $c->jumlah_donasi }} donasi)</div>
                            </td>
                            <td class="text-nowrap small">
                                <div>{{ $c->deadline->translatedFormat('d M Y') }}</div>
                                <span class="text-muted">{{ $c->sisa_hari }} hari lagi</span>
                            </td>
                            <td>
                                <span class="badge-status s-{{ $c->status }}">
                                    @if($c->status === 'aktif')
                                        <i class="bi bi-check-circle-fill"></i> Aktif
                                    @elseif($c->status === 'menunggu')
                                        <i class="bi bi-clock"></i> Menunggu Verifikasi
                                    @elseif($c->status === 'ditolak')
                                        <i class="bi bi-x-circle-fill"></i> Ditolak
                                    @elseif($c->status === 'draft')
                                        <i class="bi bi-file-earmark"></i> Draft
                                    @elseif($c->status === 'selesai')
                                        <i class="bi bi-flag-fill"></i> Selesai
                                    @else
                                        {{ ucfirst($c->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Kelola
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item" href="{{ route('campaigns.show', $c) }}" target="_blank"><i class="bi bi-eye me-2"></i>Lihat Halaman Publik</a></li>
                                        <li><a class="dropdown-item" href="{{ route('penggalang.campaigns.edit', $c) }}"><i class="bi bi-pencil me-2"></i>Edit Campaign</a></li>
                                        <li><a class="dropdown-item" href="{{ route('penggalang.updates.index', ['campaign' => $c->id_campaign]) }}"><i class="bi bi-newspaper me-2"></i>Kelola Berita</a></li>
                                        <li><a class="dropdown-item" href="{{ route('penggalang.distributions.index', ['campaign' => $c->id_campaign]) }}"><i class="bi bi-send-check me-2"></i>Penyaluran Dana</a></li>
                                        <li><a class="dropdown-item" href="{{ route('penggalang.donors.index', ['campaign' => $c->id_campaign]) }}"><i class="bi bi-people me-2"></i>Daftar Donatur</a></li>

                                        @if(in_array($c->status, ['draft', 'ditolak'], true))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('penggalang.campaigns.submit', $c) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-primary-green fw-semibold">
                                                        <i class="bi bi-send-fill me-2"></i>Ajukan Verifikasi
                                                    </button>
                                                </form>
                                            </li>
                                        @endif

                                        @if(! $c->paidDonations()->exists())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('penggalang.campaigns.destroy', $c) }}" onsubmit="return confirm('Hapus campaign ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
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
    @endif
</div>
@endsection
