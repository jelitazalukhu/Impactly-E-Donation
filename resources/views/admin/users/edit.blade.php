@extends('layouts.dashboard')

@section('title', 'Edit Pengguna - Admin')
@section('heading', 'Edit Data Pengguna')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        <div class="mb-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pengguna
            </a>
        </div>

        <div class="panel p-4 p-md-5 mb-4">
            <h1 class="h4 fw-bold text-main mb-1">Edit Pengguna: {{ $user->name }}</h1>
            <p class="text-muted small mb-4">Peran: <strong>{{ ucfirst($user->role) }}</strong></p>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-bold">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if($user->role === 'penggalang')
                    <div class="mb-3">
                        <label for="organization_name" class="form-label fw-bold">Nama Lembaga / Yayasan / Organisasi</label>
                        <input type="text" name="organization_name" id="organization_name" class="form-control @error('organization_name') is-invalid @enderror" 
                               value="{{ old('organization_name', $user->organization_name) }}">
                        @error('organization_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label fw-bold">Status Akun</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_active" id="active1" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="active1">Aktif (Dapat Login)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_active" id="active0" value="0" {{ ! old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label text-danger fw-semibold" for="active0">Dinonaktifkan (Ditolak saat Login)</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-teal btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
