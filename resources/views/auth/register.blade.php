@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Impactly')

@section('content')
<div class="auth-wrap py-5">
    <div class="container">
        <div class="auth-card mx-auto shadow-sm" style="max-width: 580px; padding: 2.2rem 2.5rem;">
            <div class="text-center mb-4">
                <span class="badge px-3 py-1.5 rounded-pill mb-2" style="background: var(--color-accent); color: var(--color-primary); font-size: 0.78rem; font-weight: 700;">
                    <i class="bi bi-person-plus-fill me-1"></i> Registrasi Akun
                </span>
                <h1 class="h3 fw-bold text-main mb-1">Daftar ke Impactly</h1>
                <p class="text-muted small mb-0">Pilih peran Anda dan mulai langkah kebaikan hari ini.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                {{-- Pilihan Peran --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Daftar Sebagai <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <label class="role-option p-2.5 h-100 d-flex align-items-center">
                                <input type="radio" name="role" value="donatur" class="form-check-input me-2 mt-0" {{ old('role', 'donatur') === 'donatur' ? 'checked' : '' }}>
                                <div>
                                    <span class="fw-bold text-main d-block small"><i class="bi bi-heart-fill text-danger me-1"></i>Donatur</span>
                                    <span class="d-block text-muted" style="font-size: 0.74rem;">Salurkan donasi & peroleh sertifikat</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="role-option p-2.5 h-100 d-flex align-items-center">
                                <input type="radio" name="role" value="penggalang" class="form-check-input me-2 mt-0" {{ old('role') === 'penggalang' ? 'checked' : '' }}>
                                <div>
                                    <span class="fw-bold text-main d-block small"><i class="bi bi-megaphone-fill text-primary me-1"></i>Penggalang Dana</span>
                                    <span class="d-block text-muted" style="font-size: 0.74rem;">Buka & kelola program campaign</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Nama Lengkap --}}
                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap Anda" autocomplete="name" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Bidang Organisasi (Khusus Penggalang) --}}
                <div class="mb-3" id="orgField" style="display: none;">
                    <label for="organization_name" class="form-label small fw-bold text-muted">Nama Lembaga / Yayasan / Komunitas <span class="text-danger">*</span></label>
                    <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name') }}" class="form-control @error('organization_name') is-invalid @enderror" placeholder="Contoh: Yayasan Peduli Sesama">
                    @error('organization_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Email & Telepon --}}
                <div class="row g-2 mb-3">
                    <div class="col-md-7">
                        <label for="email" class="form-label small fw-bold text-muted">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" autocomplete="email" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-5">
                        <label for="phone" class="form-label small fw-bold text-muted">Nomor WhatsApp / HP</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08123456789" autocomplete="tel">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Password & Konfirmasi --}}
                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label small fw-bold text-muted">Password <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 karakter" autocomplete="new-password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label small fw-bold text-muted">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" autocomplete="new-password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-teal w-100 py-2.5 fw-bold fs-6 shadow-sm">
                    <i class="bi bi-person-check-fill me-1"></i> Buat Akun Sekarang
                </button>
            </form>

            <p class="text-center text-muted small mt-4 mb-0">
                Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold" style="color: var(--color-primary)">Masuk ke Akun</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var radios = document.querySelectorAll('input[name="role"]');
        var orgField = document.getElementById('orgField');
        function toggleOrg() {
            var selected = document.querySelector('input[name="role"]:checked');
            orgField.style.display = (selected && selected.value === 'penggalang') ? 'block' : 'none';
        }
        radios.forEach(function (r) { r.addEventListener('change', toggleOrg); });
        toggleOrg();
    })();
</script>
@endpush
