@extends('layouts.dashboard')

@section('title', 'Moderasi Konten - Admin')
@section('heading', 'Moderasi Konten Platform')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Moderasi Konten</h1>
        <p class="text-muted small mb-0">Nonaktifkan campaign, berita, atau penyaluran dana yang melanggar ketentuan atau bermasalah.</p>
    </div>
</div>

{{-- Navigation Tabs --}}
<ul class="nav nav-tabs mb-4" id="modTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'campaign' ? 'active' : '' }}" href="{{ route('admin.moderation.index', ['tab' => 'campaign']) }}">
            <i class="bi bi-megaphone me-1"></i> Moderasi Campaign ({{ $campaigns->total() }})
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'update' ? 'active' : '' }}" href="{{ route('admin.moderation.index', ['tab' => 'update']) }}">
            <i class="bi bi-newspaper me-1"></i> Moderasi Berita ({{ $updates->total() }})
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'distribution' ? 'active' : '' }}" href="{{ route('admin.moderation.index', ['tab' => 'distribution']) }}">
            <i class="bi bi-send-check me-1"></i> Moderasi Penyaluran Dana ({{ $distributions->total() }})
        </a>
    </li>
</ul>

<div class="panel">
    @if($tab === 'campaign')
        {{-- Tabel Moderasi Campaign --}}
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Penggalang</th>
                        <th>Target Dana</th>
                        <th>Status</th>
                        <th class="text-end">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $camp)
                        <tr>
                            <td>
                                <a href="{{ route('campaigns.show', $camp) }}" target="_blank" class="fw-bold text-main text-decoration-none">
                                    {{ $camp->title }}
                                </a>
                            </td>
                            <td>{{ $camp->owner->name }}</td>
                            <td class="fw-bold text-primary-green">{{ \App\Support\Rupiah::format($camp->target_amount) }}</td>
                            <td>
                                <span class="badge-status s-{{ $camp->status }}">
                                    {{ ucfirst($camp->status) }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <form method="POST" action="{{ route('admin.moderation.campaign', $camp) }}" class="d-inline" onsubmit="return confirm('Ubah status moderasi campaign ini?')">
                                    @csrf
                                    @if($camp->status === 'nonaktif')
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-check-circle me-1"></i> Aktifkan Kembali
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-slash-circle me-1"></i> Nonaktifkan (Takedown)
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($campaigns->hasPages())
            <div class="p-3 border-top">{{ $campaigns->links() }}</div>
        @endif

    @elseif($tab === 'update')
        {{-- Tabel Moderasi Berita --}}
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Judul Berita</th>
                        <th>Campaign</th>
                        <th>Tanggal</th>
                        <th>Visibilitas</th>
                        <th class="text-end">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($updates as $u)
                        <tr>
                            <td>
                                <div class="fw-bold text-main">{{ $u->title }}</div>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($u->content, 60) }}</div>
                            </td>
                            <td>{{ $u->campaign->title }}</td>
                            <td class="small">{{ $u->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge-status s-aktif"><i class="bi bi-eye"></i> Tampil Publik</span>
                                @else
                                    <span class="badge-status s-failed"><i class="bi bi-eye-slash"></i> Disembunyikan</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <form method="POST" action="{{ route('admin.moderation.update', $u) }}" class="d-inline">
                                    @csrf
                                    @if($u->is_active)
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-eye-slash me-1"></i> Sembunyikan
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-eye me-1"></i> Tampilkan
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($updates->hasPages())
            <div class="p-3 border-top">{{ $updates->links() }}</div>
        @endif

    @else
        {{-- Tabel Moderasi Penyaluran Dana --}}
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Penerima</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Visibilitas</th>
                        <th class="text-end">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distributions as $d)
                        <tr>
                            <td>{{ $d->campaign->title }}</td>
                            <td class="fw-semibold">{{ $d->recipient }}</td>
                            <td class="fw-bold text-primary-green">{{ \App\Support\Rupiah::format($d->amount) }}</td>
                            <td class="small">{{ \Illuminate\Support\Str::limit($d->description, 50) }}</td>
                            <td>
                                @if($d->is_active)
                                    <span class="badge-status s-aktif"><i class="bi bi-eye"></i> Tampil Publik</span>
                                @else
                                    <span class="badge-status s-failed"><i class="bi bi-eye-slash"></i> Disembunyikan</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <form method="POST" action="{{ route('admin.moderation.distribution', $d) }}" class="d-inline">
                                    @csrf
                                    @if($d->is_active)
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-eye-slash me-1"></i> Sembunyikan
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-eye me-1"></i> Tampilkan
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($distributions->hasPages())
            <div class="p-3 border-top">{{ $distributions->links() }}</div>
        @endif
    @endif
</div>
@endsection
