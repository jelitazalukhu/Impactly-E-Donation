@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran - ' . $donation->campaign->title)

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">

            {{-- Checkout Header --}}
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-4 pb-3 border-bottom border-sage">
                <div>
                    <span class="badge px-2.5 py-1.5 rounded-pill mb-1" style="background: var(--color-accent); color: var(--color-primary); font-size: 0.78rem; font-weight: 700;">
                        <i class="bi bi-shield-lock-fill me-1"></i> Checkout Terverifikasi & Aman
                    </span>
                    <h1 class="h4 fw-bold text-main mb-0">Pembayaran Donasi</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted">Batas waktu bayar:</span>
                    <span class="badge bg-warning text-dark px-2.5 py-1.5 font-monospace fw-bold">
                        <i class="bi bi-clock me-1"></i> 23:59:59
                    </span>
                </div>
            </div>

            <div class="row g-4">
                {{-- Kolom Kanan (Desktop): Ringkasan Tagihan & Rincian Donasi --}}
                <div class="col-lg-5 order-lg-2">
                    <div class="panel p-4 sticky-side">
                        <h2 class="h6 text-muted text-uppercase fw-bold letter-spacing-1 mb-3">Ringkasan Donasi</h2>

                        <div class="d-flex gap-3 align-items-center pb-3 mb-3 border-bottom border-sage">
                            <div class="campaign-thumb flex-shrink-0 rounded-3" style="width: 70px; height: 70px; overflow: hidden; background: var(--color-accent);">
                                @if($donation->campaign->image)
                                    <img src="{{ asset('storage/' . $donation->campaign->image) }}" alt="{{ $donation->campaign->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 fs-2" style="color: var(--color-primary);">
                                        <i class="bi {{ $donation->campaign->icon ?? 'bi-heart' }}"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="cat-badge mb-1" style="font-size: 0.72rem;">{{ $donation->campaign->category->name ?? 'Kemanusiaan' }}</span>
                                <div class="fw-bold text-main small lh-sm">{{ $donation->campaign->title }}</div>
                            </div>
                        </div>

                        <div class="mb-2 pb-2 border-bottom border-sage">
                            <div class="d-flex justify-content-between small py-1">
                                <span class="text-muted">Nomor Transaksi</span>
                                <span class="font-monospace fw-semibold text-main">{{ $payment->order_id }}</span>
                            </div>
                            <div class="d-flex justify-content-between small py-1">
                                <span class="text-muted">Nama Donatur</span>
                                <span class="fw-semibold text-main">{{ $donation->displayName() }}</span>
                            </div>
                            @if($donation->is_anonymous)
                                <div class="d-flex justify-content-between small py-1">
                                    <span class="text-muted">Privasi</span>
                                    <span class="badge bg-secondary">Anonim</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between small py-1">
                                <span class="text-muted">Biaya Layanan</span>
                                <span class="fw-semibold text-success">Rp 0 (Gratis)</span>
                            </div>
                        </div>

                        <div class="pt-2 mb-3">
                            <div class="small text-muted mb-1">Total Pembayaran</div>
                            <div class="fs-2 fw-bold text-main" style="color: var(--color-primary) !important;">
                                {{ \App\Support\Rupiah::format($donation->amount) }}
                            </div>
                        </div>

                        @if($donation->amount >= $certMin)
                            <div class="p-3 rounded-3 bg-soft-blue border border-info-subtle small d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-award-fill fs-4" style="color: var(--color-primary)"></i>
                                <span>Donasi ini berhak mendapatkan <strong>Sertifikat Digital Resmi</strong> setelah pembayaran diselesaikan.</span>
                            </div>
                        @endif

                        <div class="p-3 rounded-3 bg-light border border-sage small text-muted">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            Dana Anda disalurkan 100% secara transparan dan dapat dipantau melalui laporan kabar terbaru campaign.
                        </div>
                    </div>
                </div>

                {{-- Kolom Kiri: Pilihan Metode Pembayaran --}}
                <div class="col-lg-7 order-lg-1">
                    <form method="POST" action="{{ route('donasi.simulate', $donation) }}" id="paymentForm">
                        @csrf

                        <div class="panel p-4 mb-4">
                            <h2 class="h5 fw-bold text-main mb-3">
                                <i class="bi bi-credit-card-2-front me-2 text-primary"></i>Pilih Metode Pembayaran
                            </h2>

                            {{-- Kategori 1: QRIS --}}
                            <div class="mb-4">
                                <div class="fw-bold small text-muted text-uppercase letter-spacing-1 mb-2">QRIS (Semua Pembayaran QR)</div>
                                <div class="payment-method-card mb-2" onclick="selectMethod(this, 'QRIS Instant')">
                                    <input type="radio" name="method" value="QRIS" checked class="form-check-input me-1" id="m_qris">
                                    <div class="method-icon">
                                        <i class="bi bi-qr-code-scan"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-main">QRIS (Semua Bank & Dompet Digital)</div>
                                        <div class="small text-muted">BCA, Mandiri, GoPay, OVO, ShopeePay, DANA, LinkAja</div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success fw-bold">Instan</span>
                                </div>
                            </div>

                            {{-- Kategori 2: E-Wallet --}}
                            <div class="mb-4">
                                <div class="fw-bold small text-muted text-uppercase letter-spacing-1 mb-2">E-Wallet (Dompet Digital)</div>
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <div class="payment-method-card" onclick="selectMethod(this, 'ShopeePay')">
                                            <input type="radio" name="method" value="ShopeePay" class="form-check-input me-1" id="m_shopee">
                                            <div class="method-icon"><i class="bi bi-bag-fill text-danger"></i></div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-main small">ShopeePay</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Verifikasi Otomatis</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="payment-method-card" onclick="selectMethod(this, 'DANA')">
                                            <input type="radio" name="method" value="DANA" class="form-check-input me-1" id="m_dana">
                                            <div class="method-icon"><i class="bi bi-wallet2 text-primary"></i></div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-main small">DANA</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Verifikasi Otomatis</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="payment-method-card" onclick="selectMethod(this, 'GoPay')">
                                            <input type="radio" name="method" value="GoPay" class="form-check-input me-1" id="m_gopay">
                                            <div class="method-icon"><i class="bi bi-phone text-success"></i></div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-main small">GoPay</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Verifikasi Otomatis</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="payment-method-card" onclick="selectMethod(this, 'OVO')">
                                            <input type="radio" name="method" value="OVO" class="form-check-input me-1" id="m_ovo">
                                            <div class="method-icon"><i class="bi bi-coin text-warning"></i></div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-main small">OVO</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Verifikasi Otomatis</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kategori 3: Transfer Bank (Virtual Account) --}}
                            <div class="mb-4">
                                <div class="fw-bold small text-muted text-uppercase letter-spacing-1 mb-2">Virtual Account Bank (Transfer Bebas Antre)</div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="payment-method-card" onclick="selectMethod(this, 'VA BCA')">
                                        <input type="radio" name="method" value="BCA Virtual Account" class="form-check-input me-1" id="m_bca">
                                        <div class="method-icon"><i class="bi bi-bank"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-main small">BCA Virtual Account</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Dicek Otomatis 24 Jam</div>
                                        </div>
                                        <span class="badge bg-light text-muted border">BCA</span>
                                    </div>

                                    <div class="payment-method-card" onclick="selectMethod(this, 'VA Mandiri')">
                                        <input type="radio" name="method" value="Mandiri Virtual Account" class="form-check-input me-1" id="m_mandiri">
                                        <div class="method-icon"><i class="bi bi-bank2"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-main small">Mandiri Virtual Account (Livin')</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Dicek Otomatis 24 Jam</div>
                                        </div>
                                        <span class="badge bg-light text-muted border">Mandiri</span>
                                    </div>

                                    <div class="payment-method-card" onclick="selectMethod(this, 'VA BNI')">
                                        <input type="radio" name="method" value="BNI Virtual Account" class="form-check-input me-1" id="m_bni">
                                        <div class="method-icon"><i class="bi bi-building"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-main small">BNI Virtual Account</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Dicek Otomatis 24 Jam</div>
                                        </div>
                                        <span class="badge bg-light text-muted border">BNI</span>
                                    </div>

                                    <div class="payment-method-card" onclick="selectMethod(this, 'VA BRI')">
                                        <input type="radio" name="method" value="BRI Virtual Account (BRIVA)" class="form-check-input me-1" id="m_bri">
                                        <div class="method-icon"><i class="bi bi-cash-coin"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-main small">BRI Virtual Account (BRIVA)</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Dicek Otomatis 24 Jam</div>
                                        </div>
                                        <span class="badge bg-light text-muted border">BRI</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Kategori 4: Kartu Kredit / Debit --}}
                            <div class="mb-4">
                                <div class="fw-bold small text-muted text-uppercase letter-spacing-1 mb-2">Kartu Kredit / Debit Online</div>
                                <div class="payment-method-card" onclick="selectMethod(this, 'Kartu Kredit')">
                                    <input type="radio" name="method" value="Kartu Kredit / Debit (Visa/Mastercard)" class="form-check-input me-1" id="m_cc">
                                    <div class="method-icon"><i class="bi bi-credit-card text-primary"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-main small">Kartu Kredit / Debit Online</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Visa, Mastercard, JCB — Dilindungi 3D Secure</div>
                                    </div>
                                    <span class="badge bg-light text-muted border">Visa / MC</span>
                                </div>
                            </div>

                            {{-- Petunjuk Pembayaran Dinamis --}}
                            <div id="methodDetailBox" class="p-3 rounded-3 bg-light border border-sage mb-4">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-info-circle-fill text-primary"></i>
                                    <span class="fw-bold text-main small" id="detailTitle">Instruksi Pembayaran QRIS</span>
                                </div>
                                <p class="small text-muted mb-0" id="detailDesc">
                                    Setelah menekan tombol <strong>Lanjutkan</strong>, kode QR resmi akan diproses dan pembayaran langsung dikonfirmasi secara seketika.
                                </p>
                            </div>

                            {{-- Tombol Aksi: Persis 2 Tombol Sesuai Arahan --}}
                            <div class="d-flex flex-column gap-2 mt-4 pt-2">
                                <button type="submit" name="status" value="paid" class="btn btn-teal w-100 py-3 fw-bold fs-6 shadow-sm">
                                    <i class="bi bi-shield-check me-2"></i> Lanjutkan
                                </button>
                                <button type="submit" name="status" value="pending" class="btn btn-outline-secondary w-100 py-2.5 fw-semibold">
                                    <i class="bi bi-bookmark me-2"></i> Simpan dan Bayar Nanti
                                </button>
                            </div>

                            {{-- Link Batal Transaksi (Discreet) --}}
                            <div class="text-center mt-3 pt-3 border-top border-sage">
                                <button type="submit" name="status" value="failed" class="btn btn-link text-muted btn-sm text-decoration-none">
                                    <i class="bi bi-x-circle me-1"></i> Batalkan Donasi
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectMethod(card, title) {
    const radio = card.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }
    document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');

    const detailTitle = document.getElementById('detailTitle');
    const detailDesc = document.getElementById('detailDesc');
    if (detailTitle && detailDesc) {
        detailTitle.textContent = 'Instruksi ' + title;
        if (title.includes('QRIS')) {
            detailDesc.innerHTML = 'Setelah menekan <strong>Lanjutkan</strong>, kode QR akan diproses dan transaksi Anda diverifikasi otomatis tanpa perlu upload bukti transfer.';
        } else if (title.includes('VA') || title.includes('Virtual')) {
            detailDesc.innerHTML = 'Nomor Virtual Account khusus donasi Anda akan diproses secara real-time. Anda dapat melanjutkannya melalui ATM, Mobile Banking, atau Internet Banking.';
        } else if (title.includes('Kartu')) {
            detailDesc.innerHTML = 'Pembayaran diproses dengan enkripsi 256-bit dan otentikasi OTP 3D Secure dari bank penerbit kartu Anda.';
        } else {
            detailDesc.innerHTML = 'Sistem akan memproses pembayaran melalui aplikasi ' + title + ' Anda secara otomatis dan aman.';
        }
    }
}
</script>
@endpush
