@extends('layouts.dashboard')

@section('title', 'Laporan Penyaluran Dana - Penggalang')
@section('heading', 'Transparansi Penyaluran Dana')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Laporan Penyaluran Dana</h1>
        <p class="text-muted small mb-0">Catat dan publikasikan setiap rupiah donasi yang disalurkan beserta bukti nyata bagi donatur.</p>
    </div>
    <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#createDistModal">
        <i class="bi bi-plus-lg me-1"></i> Catat Penyaluran Dana
    </button>
</div>

{{-- Ringkasan Statistik Penyaluran --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-wallet-fill"></i></div>
            <div class="stat-value text-primary-green">{{ \App\Support\Rupiah::short($totalCollected) }}</div>
            <div class="stat-label">Total Dana Masuk (Paid)</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon text-info bg-light"><i class="bi bi-send-check-fill text-info"></i></div>
            <div class="stat-value text-info">{{ \App\Support\Rupiah::short($totalDisbursed) }}</div>
            <div class="stat-label">Total Dana Disalurkan</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon text-warning bg-light"><i class="bi bi-hourglass-split text-warning"></i></div>
            <div class="stat-value text-warning">{{ \App\Support\Rupiah::short($remaining) }}</div>
            <div class="stat-label">Sisa Dana Belum Disalurkan</div>
        </div>
    </div>
</div>

{{-- Filter Campaign Dropdown --}}
<div class="panel p-3 mb-4">
    <form method="GET" action="{{ route('penggalang.distributions.index') }}" class="row g-2 align-items-center">
        <div class="col-auto"><label class="form-label small fw-bold text-muted mb-0">Filter Campaign:</label></div>
        <div class="col-md-5">
            <select name="campaign" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Campaign Saya</option>
                @foreach($campaigns as $camp)
                    <option value="{{ $camp->id_campaign }}" {{ (string)$selectedId === (string)$camp->id_campaign ? 'selected' : '' }}>
                        {{ $camp->title }} (Sisa: {{ \App\Support\Rupiah::short($camp->terkumpul - $camp->distributions->sum('amount')) }})
                    </option>
                @endforeach
            </select>
        </div>
        @if($selectedId)
            <div class="col-auto">
                <a href="{{ route('penggalang.distributions.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        @endif
    </form>
</div>

{{-- Tabel Penyaluran Dana --}}
<div class="panel">
    @if($distributions->isEmpty())
        <div class="text-center py-5 px-3">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-send-check"></i></div>
            <div class="fw-semibold text-main">Belum ada laporan penyaluran dana.</div>
            <p class="text-muted small mb-3">Catat penyaluran dana segera setelah donasi diberikan kepada penerima manfaat.</p>
            <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#createDistModal">
                <i class="bi bi-plus-circle me-1"></i> Catat Penyaluran Dana Pertama
            </button>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Campaign</th>
                        <th>Penerima Manfaat</th>
                        <th>Keterangan</th>
                        <th class="text-end">Nominal Disalurkan</th>
                        <th>Bukti</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distributions as $d)
                        <tr>
                            <td class="text-nowrap small text-muted">
                                {{ $d->distributed_at->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <a href="{{ route('campaigns.show', $d->campaign) }}" class="fw-bold text-main d-block">
                                    {{ $d->campaign->title }}
                                </a>
                            </td>
                            <td class="fw-semibold text-main">{{ $d->recipient }}</td>
                            <td class="small">{{ $d->description }}</td>
                            <td class="text-end fw-bold text-primary-green text-nowrap">
                                {{ \App\Support\Rupiah::format($d->amount) }}
                            </td>
                            <td>
                                @if($d->proof_file)
                                    <a href="{{ asset('storage/' . $d->proof_file) }}" target="_blank" class="btn btn-outline-teal btn-sm py-0 px-2" title="Lihat Bukti Nota/Foto">
                                        <i class="bi bi-file-earmark-image me-1"></i> Bukti
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editDistModal{{ $d->id_distribution }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('penggalang.distributions.destroy', $d) }}" onsubmit="return confirm('Hapus laporan penyaluran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit Penyaluran --}}
                        <div class="modal fade" id="editDistModal{{ $d->id_distribution }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('penggalang.distributions.update', $d) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Penyaluran Dana</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Nominal Penyaluran (Rp) <span class="text-danger">*</span></label>
                                                <input type="number" name="amount" class="form-control" value="{{ (int)$d->amount }}" min="1000" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Penerima Manfaat <span class="text-danger">*</span></label>
                                                <input type="text" name="recipient" class="form-control" value="{{ $d->recipient }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Tanggal Penyaluran <span class="text-danger">*</span></label>
                                                <input type="date" name="distributed_at" class="form-control" value="{{ $d->distributed_at->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Keterangan / Alokasi <span class="text-danger">*</span></label>
                                                <textarea name="description" rows="3" class="form-control" required>{{ $d->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Ganti Bukti (Foto/Nota/Kuitansi)</label>
                                                <input type="file" name="proof_file" class="form-control" accept="image/*,.pdf">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-teal btn-sm">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($distributions->hasPages())
            <div class="p-3 border-top">
                {{ $distributions->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Modal Create Penyaluran --}}
<div class="modal fade" id="createDistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('penggalang.distributions.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Catat Penyaluran Dana Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Campaign <span class="text-danger">*</span></label>
                        <select name="id_campaign" class="form-select" required>
                            <option value="">-- Pilih Campaign --</option>
                            @foreach($campaigns as $camp)
                                <option value="{{ $camp->id_campaign }}" {{ (string)$selectedId === (string)$camp->id_campaign ? 'selected' : '' }}>
                                    {{ $camp->title }} (Dana Terkumpul: {{ \App\Support\Rupiah::format($camp->terkumpul) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nominal yang Disalurkan (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" placeholder="Contoh: 15000000" min="1000" step="1000" required>
                        <div class="form-text small text-muted">Nominal tidak boleh melebihi sisa dana yang sudah terkumpul.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Penerima Manfaat <span class="text-danger">*</span></label>
                        <input type="text" name="recipient" class="form-control" placeholder="Contoh: Pengurus Masjid Al-Ikhlas / 24 Siswa Yatim" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Penyaluran <span class="text-danger">*</span></label>
                        <input type="date" name="distributed_at" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keterangan / Rincian Penyaluran <span class="text-danger">*</span></label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Jelaskan untuk keperluan apa dana ini disalurkan secara transparan..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Bukti Penyaluran (Foto/Kuitansi/Nota)</label>
                        <input type="file" name="proof_file" class="form-control" accept="image/*,.pdf">
                        <div class="form-text small text-muted">Format JPG, PNG, PDF. Maks 3MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-teal btn-sm"><i class="bi bi-check-circle me-1"></i> Simpan & Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
