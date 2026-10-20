@extends(auth()->user()->role === 'donatur' ? 'layouts.app' : 'layouts.dashboard')

@section('title', 'Profil Pengguna - Impactly')
@section('heading', 'Profil Pengguna')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">

            {{-- Profile Header Card --}}
            <div class="panel p-4 mb-4 shadow-sm" style="background: linear-gradient(135deg, #FFFFFF 0%, var(--color-accent) 100%); border-left: 4px solid var(--color-primary);">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold text-white fs-3 flex-shrink-0"
                             style="width: 72px; height: 72px; background: linear-gradient(135deg, var(--color-primary), #1B2938);">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h1 class="h4 fw-bold text-main mb-0">{{ $user->name }}</h1>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Administrator</span>
                                @elseif($user->role === 'penggalang')
                                    <span class="badge px-2.5 py-1" style="background: var(--color-primary); color: #fff;">Penggalang Dana</span>
                                @else
                                    <span class="badge px-2.5 py-1" style="background: var(--color-secondary-accent); color: #fff;">Donatur</span>
                                @endif
                            </div>
                            <div class="text-muted small d-flex flex-wrap align-items-center gap-3">
                                <span><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>
                                @if($user->phone)
                                    <span><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</span>
                                @endif
                                <span><i class="bi bi-calendar-check me-1"></i>Bergabung {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        <span class="badge-status s-paid">
                            <i class="bi bi-patch-check-fill me-1"></i> Akun Terverifikasi
                        </span>
                    </div>
                </div>

                {{-- Quick Stats Pills --}}
                <div class="row g-2 mt-3 pt-3 border-top border-sage">
                    @if($user->role === 'donatur')
                        <div class="col-sm-4">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Total Donasi Diberikan</div>
                                <div class="fw-bold text-main fs-6" style="color: var(--color-primary) !important;">
                                    {{ \App\Support\Rupiah::format($user->donations()->where('status', 'paid')->sum('amount')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Transaksi Selesai</div>
                                <div class="fw-bold text-main fs-6">
                                    {{ $user->donations()->where('status', 'paid')->count() }} Kali
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Sertifikat Penghargaan</div>
                                <div class="fw-bold text-main fs-6">
                                    {{ $user->donations()->has('certificate')->count() }} Sertifikat
                                </div>
                            </div>
                        </div>
                    @elseif($user->role === 'penggalang')
                        <div class="col-sm-4">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Total Campaign Dikelola</div>
                                <div class="fw-bold text-main fs-6">
                                    {{ $user->campaigns()->count() }} Campaign
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Campaign Aktif Berjalan</div>
                                <div class="fw-bold text-main fs-6" style="color: var(--color-primary) !important;">
                                    {{ $user->campaigns()->where('status', 'aktif')->count() }} Program
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Lembaga / Yayasan</div>
                                <div class="fw-bold text-main fs-6 text-truncate">
                                    {{ $user->organization_name ?? 'Pribadi' }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-6">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Tingkat Otoritas</div>
                                <div class="fw-bold text-danger fs-6">Super Admin (Hak Akses Penuh)</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-2.5 px-3 rounded-3 bg-white border border-sage">
                                <div class="text-muted small" style="font-size: 0.75rem;">Status Keamanan</div>
                                <div class="fw-bold text-success fs-6"><i class="bi bi-shield-check me-1"></i>Sistem Terproteksi</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tabs / Dual Forms --}}
            <div class="row g-4">
                {{-- Form 1: Biodata & Kontak --}}
                <div class="col-md-7">
                    <div class="panel p-4 h-100 shadow-sm">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-sage">
                            <i class="bi bi-person-lines-fill text-primary fs-5"></i>
                            <h2 class="h6 fw-bold text-main mb-0">Informasi Pribadi & Kontak</h2>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label small fw-bold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-bold text-muted">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label small fw-bold text-muted">Nomor WhatsApp / HP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-sage text-muted"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           placeholder="Contoh: 081234567890" value="{{ old('phone', $user->phone) }}">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($user->role === 'penggalang')
                                <div class="mb-4">
                                    <label for="organization_name" class="form-label small fw-bold text-muted">Nama Organisasi / Lembaga / Yayasan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-sage text-muted"><i class="bi bi-buildings"></i></span>
                                        <input type="text" name="organization_name" id="organization_name" class="form-control @error('organization_name') is-invalid @enderror" 
                                               placeholder="Contoh: Yayasan Peduli Sesama" value="{{ old('organization_name', $user->organization_name) }}">
                                    </div>
                                    @error('organization_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="pt-3 border-top border-sage text-end">
                                <button type="submit" class="btn btn-teal">
                                    <i class="bi bi-check2-circle me-1"></i> Simpan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Form 2: Ganti Password --}}
                <div class="col-md-5">
                    <div class="panel p-4 h-100 shadow-sm">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-sage">
                            <i class="bi bi-shield-lock-fill text-primary fs-5"></i>
                            <h2 class="h6 fw-bold text-main mb-0">Keamanan & Password</h2>
                        </div>

                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label small fw-bold text-muted">Password Saat Ini <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-bold text-muted">Password Baru <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                <div class="form-text small text-muted" style="font-size: 0.72rem;">Minimal 8 karakter huruf dan angka.</div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label small fw-bold text-muted">Ulangi Password Baru <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>

                            <div class="pt-3 border-top border-sage text-end">
                                <button type="submit" class="btn btn-outline-teal">
                                    <i class="bi bi-key me-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
