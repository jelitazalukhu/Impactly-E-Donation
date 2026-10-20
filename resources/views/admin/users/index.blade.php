@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna - Admin')
@section('heading', 'Kelola Pengguna')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Daftar Pengguna</h1>
        <p class="text-muted small mb-0">Kelola akun donatur dan penggalang dana, aktivasi akun, dan moderasi akses.</p>
    </div>
</div>

{{-- Filter & Pencarian --}}
<div class="panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-sage"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" placeholder="Cari nama, email, organisasi..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-sm-3 col-md-2">
            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Peran</option>
                <option value="donatur" {{ $role === 'donatur' ? 'selected' : '' }}>Donatur ({{ $counts['donatur'] }})</option>
                <option value="penggalang" {{ $role === 'penggalang' ? 'selected' : '' }}>Penggalang ({{ $counts['penggalang'] }})</option>
            </select>
        </div>
        <div class="col-sm-3 col-md-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $status === '0' ? 'selected' : '' }}>Nonaktif ({{ $counts['inactive'] }})</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-teal btn-sm">Filter</button>
            @if($search || $role || $status !== null)
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- Tabel Pengguna --}}
<div class="panel">
    @if($users->isEmpty())
        <div class="text-center py-5 px-3">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-people"></i></div>
            <div class="fw-semibold text-main">Tidak ada pengguna yang sesuai dengan filter.</div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Kontak</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>
                                <div class="fw-bold text-main">{{ $u->name }}</div>
                                @if($u->organization_name)
                                    <div class="small text-primary-green"><i class="bi bi-building me-1"></i>{{ $u->organization_name }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $u->email }}</div>
                                <span class="small text-muted">{{ $u->phone ?? 'Tanpa telepon' }}</span>
                            </td>
                            <td>
                                @if($u->role === 'penggalang')
                                    <span class="badge bg-soft-sage text-primary-green fw-bold">Penggalang Dana</span>
                                @else
                                    <span class="badge bg-light text-muted border">Donatur</span>
                                @endif
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge-status s-aktif"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                                @else
                                    <span class="badge-status s-failed"><i class="bi bi-dash-circle-fill"></i> Dinonaktifkan</span>
                                @endif
                            </td>
                            <td class="small text-muted text-nowrap">
                                {{ $u->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-outline-secondary btn-sm" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            <i class="bi {{ $u->is_active ? 'bi-person-slash' : 'bi-person-check' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Akun">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
