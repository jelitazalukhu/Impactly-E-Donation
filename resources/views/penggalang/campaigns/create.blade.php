@extends('layouts.dashboard')

@section('title', 'Buat Campaign Baru - Penggalang')
@section('heading', 'Buat Campaign Penggalangan Dana')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('penggalang.campaigns.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Campaign
            </a>
        </div>

        <div class="panel p-4 p-md-5 mb-4">
            <h1 class="h4 fw-bold text-main mb-2">Formulir Penggalangan Dana</h1>
            <p class="text-muted small mb-4">Isi data campaign secara lengkap dan transparan agar cepat diverifikasi oleh admin.</p>

            <form method="POST" action="{{ route('penggalang.campaigns.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Judul Campaign --}}
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold">Judul Campaign <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                           placeholder="Contoh: Bantu Renovasi Asrama Panti Asuhan Harapan" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    {{-- Kategori --}}
                    <div class="col-md-6">
                        <label for="id_category" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="id_category" id="id_category" class="form-select @error('id_category') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_category }}" {{ old('id_category') == $cat->id_category ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_category')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Target Dana --}}
                    <div class="col-md-6">
                        <label for="target_amount" class="form-label fw-bold">Target Dana (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-sage">Rp</span>
                            <input type="number" name="target_amount" id="target_amount" class="form-control @error('target_amount') is-invalid @enderror" 
                                   placeholder="Contoh: 50000000" min="10000" step="10000" value="{{ old('target_amount') }}" required>
                        </div>
                        @error('target_amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    {{-- Deadline --}}
                    <div class="col-md-6">
                        <label for="deadline" class="form-label fw-bold">Batas Waktu (Deadline) <span class="text-danger">*</span></label>
                        <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" 
                               min="{{ now()->addDay()->toDateString() }}" value="{{ old('deadline') }}" required>
                        <div class="form-text small text-muted">Tanggal terakhir campaign dapat menerima donasi publik.</div>
                        @error('deadline')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload Cover Image --}}
                    <div class="col-md-6">
                        <label for="image" class="form-label fw-bold">Foto Sampul Campaign</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text small text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Deskripsi & Rincian Penggunaan Dana <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="7" class="form-control @error('description') is-invalid @enderror" 
                              placeholder="Ceritakan latar belakang penggalangan dana, tujuan, rencana penggunaan dana, dan penerima manfaat secara jelas..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top">
                    <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark me-1"></i> Simpan sebagai Draft
                    </button>
                    <button type="submit" name="action" value="submit" class="btn btn-teal">
                        <i class="bi bi-send-fill me-1"></i> Simpan & Ajukan Verifikasi
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
