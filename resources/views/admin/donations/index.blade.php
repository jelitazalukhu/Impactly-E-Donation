@extends('layouts.dashboard')

@section('title', 'Pantau Seluruh Donasi - Admin')
@section('heading', 'Pantau Seluruh Transaksi Donasi')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Seluruh Transaksi Donasi</h1>
        <p class="text-muted small mb-0">Pantau seluruh aliran dana donasi masuk, gateway status, dan rincian transaksi.</p>
    </div>
    <div class="panel px-3 py-2 bg-soft-sage border-sage">
        <span class="text-muted small">Total Donasi Lunas:</span>
        <span class="fw-bold fs-5 text-primary-green ms-1">{{ \App\Support\Rupiah::format($totalPaid) }}</span>
        <span class="text-muted small">({{ $totalCount }} transaksi)</span>
    </div>
</div>

{{-- Filter Status & Campaign --}}
<div class="panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.donations.index') }}" class="row g-2 align-items-center">
        <div class="col-sm-4 col-md-3">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status Pembayaran</option>
                <option value="paid" {{ $currentStatus === 'paid' ? 'selected' : '' }}>Paid (Berhasil)</option>
                <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                <option value="failed" {{ $currentStatus === 'failed' ? 'selected' : '' }}>Failed (Gagal / Batal)</option>
            </select>
        </div>
        <div class="col-sm-5 col-md-4">
            <select name="campaign" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Campaign</option>
                @foreach($campaigns as $camp)
                    <option value="{{ $camp->id_campaign }}" {{ (string)$selectedId === (string)$camp->id_campaign ? 'selected' : '' }}>
                        {{ $camp->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-teal btn-sm">Filter</button>
            @if($currentStatus !== 'all' || $selectedId)
                <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- Tabel Donasi --}}
<div class="panel">
    @if($donations->isEmpty())
        <div class="text-center py-5 px-3">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-cash-stack"></i></div>
            <div class="fw-semibold text-main">Tidak ada catatan donasi yang cocok.</div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Order ID / Waktu</th>
                        <th>Donatur</th>
                        <th>Campaign</th>
                        <th>Nominal</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donations as $don)
                        @php $payment = $don->payments->first(); @endphp
                        <tr>
                            <td>
                                <div class="font-monospace small fw-bold text-main">{{ $payment->order_id ?? 'DON-' . $don->id_donation }}</div>
                                <div class="small text-muted">{{ $don->created_at->translatedFormat('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-main">{{ $don->displayName() }}</div>
                                <div class="small text-muted">{{ $don->donor->email ?? '-' }}</div>
                                @if($don->is_anonymous)
                                    <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">Anonim</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('campaigns.show', $don->campaign) }}" target="_blank" class="text-main fw-semibold text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($don->campaign->title, 40) }}
                                </a>
                            </td>
                            <td class="fw-bold text-primary-green text-nowrap">
                                {{ \App\Support\Rupiah::format($don->amount) }}
                            </td>
                            <td class="small">
                                {{ $payment->method ?? 'Payment Gateway' }}
                                @if($payment && $payment->gateway_status)
                                    <div class="font-monospace text-muted" style="font-size: 0.72rem;">{{ $payment->gateway_status }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status s-{{ $don->status }}">
                                    @if($don->status === 'paid')
                                        <i class="bi bi-check-circle-fill"></i> Paid
                                    @elseif($don->status === 'pending')
                                        <i class="bi bi-clock"></i> Pending
                                    @elseif($don->status === 'failed')
                                        <i class="bi bi-x-circle-fill"></i> Failed
                                    @else
                                        {{ ucfirst($don->status) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($donations->hasPages())
            <div class="p-3 border-top">
                {{ $donations->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
