@extends('layouts.app')

@section('title', 'Form Donasi - ' . $campaign->title)

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('campaigns.show', $campaign) }}">{{ \Illuminate\Support\Str::limit($campaign->title, 25) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Donasi</li>
                </ol>
            </nav>

            <div class="panel p-4 p-md-5 mb-4 shadow-sm">
                {{-- Header Singkat Campaign --}}
                <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                    <div style="width: 72px; height: 54px; border-radius: 8px; overflow: hidden; flex-shrink: 0;" class="bg-soft-sage">
                        @if($campaign->image)
                            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-primary-green fs-4">
                                <i class="bi {{ $campaign->icon }}"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="small text-muted">Anda akan berdonasi untuk:</div>
                        <h1 class="h6 mb-0 text-main fw-bold">{{ $campaign->title }}</h1>
                    </div>
                </div>

                {{-- Banner Sertifikat --}}
                <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-4">
                    <i class="bi bi-award-fill fs-5 text-success"></i>
                    <div class="small">
                        Donasi minimal <strong>{{ \App\Support\Rupiah::format($certMin) }}</strong> berhak mendapatkan <strong>Sertifikat Digital Resmi</strong> berformat PDF.
                    </div>
                </div>

                <form method="POST" action="{{ route('donasi.store', $campaign) }}" id="donationForm">
                    @csrf

                    {{-- Pilihan Nominal Cepat --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Nominal Donasi</label>
                        <div class="row g-2 mb-2">
                            @foreach($quickValues as $val)
                                <div class="col-4 col-sm-4">
                                    <button type="button" class="w-100 donation-chip btn-nominal" data-val="{{ $val }}">
                                        {{ \App\Support\Rupiah::format($val) }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Input Nominal Bebas --}}
                    <div class="mb-4">
                        <label for="amount" class="form-label fw-bold">Atau Masukkan Nominal Bebas (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white fw-bold border-sage text-main">Rp</span>
                            <input type="number" name="amount" id="amount" class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                                   placeholder="Contoh: 100000" min="1000" step="1000" value="{{ old('amount', 50000) }}" required>
                        </div>
                        <div class="form-text small text-muted">Nominal bebas tanpa batasan kaku (minimal Rp 1.000).</div>
                        @error('amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pesan / Doa --}}
                    <div class="mb-4">
                        <label for="message" class="form-label fw-bold">Doa atau Pesan Kebaikan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="message" id="message" rows="3" class="form-control @error('message') is-invalid @enderror" 
                                  placeholder="Tuliskan doa atau pesan penyemangat untuk penerima manfaat...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Donasi Anonim --}}
                    <div class="mb-4 p-3 rounded-3 bg-light border border-sage">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" id="is_anonymous" {{ old('is_anonymous') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-main" for="is_anonymous">
                                Sembunyikan nama saya (Donasi sebagai <em>Anonim</em>)
                            </label>
                            <div class="form-text small text-muted mt-1">Nama Anda tidak akan ditampilkan di daftar donatur publik untuk menjaga keikhlasan.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-donate w-100 py-3 fs-5">
                        <i class="bi bi-shield-check me-1"></i> Lanjut ke Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-nominal').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-nominal').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('amount').value = this.dataset.val;
    });
});

document.getElementById('amount').addEventListener('input', function() {
    const cur = parseInt(this.value);
    document.querySelectorAll('.btn-nominal').forEach(b => {
        if (parseInt(b.dataset.val) === cur) {
            b.classList.add('active');
        } else {
            b.classList.remove('active');
        }
    });
});
</script>
@endpush
