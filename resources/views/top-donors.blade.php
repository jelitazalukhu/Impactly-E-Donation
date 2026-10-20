@extends('layouts.app')

@section('title', 'Top Donatur')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    {{-- Header --}}
    <div class="text-center mb-5">
        <span class="badge px-3 py-2 rounded-pill fw-bold mb-2 d-inline-flex align-items-center gap-1" style="background: var(--color-accent); color: var(--color-primary); border: 1px solid #D5E1ED;">
            <i class="bi bi-trophy-fill text-warning"></i> Papan Apresiasi Donatur
        </span>
        <h1 class="h2 fw-bold text-main mb-2">Top Donatur Kebaikan</h1>
        <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
            Peringkat dihitung berdasarkan akumulasi total donasi yang berhasil disalurkan. Donatur yang memilih privasi akan tampil sebagai <strong>Anonim</strong>.
        </p>
    </div>

    @if($donors->isEmpty())
        <div class="panel p-5 text-center text-muted">
            <i class="bi bi-trophy fs-1 text-muted" aria-hidden="true"></i>
            <h3 class="h5 fw-bold text-main mt-3">Belum Ada Donasi Terkonfirmasi</h3>
            <p class="small text-muted mb-4">Jadilah orang pertama yang menebarkan senyum dan kebaikan hari ini.</p>
            <a href="{{ route('campaigns.index') }}" class="btn btn-teal">
                <i class="bi bi-compass me-1"></i> Jelajahi Campaign
            </a>
        </div>
    @else
        {{-- Podium Top 3 --}}
        @php
            $top1 = $donors->get(0);
            $top2 = $donors->get(1);
            $top3 = $donors->get(2);
            $restDonors = $donors->slice(3);
        @endphp

        <div class="podium-wrap mb-5">
            {{-- Juara 2 (Perak) --}}
            @if($top2)
                <div class="podium-item podium-item-2">
                    <div class="podium-avatar podium-badge-silver">
                        <i class="bi bi-award-fill podium-crown"></i>
                        <span>{{ strtoupper(substr($top2->display_name, 0, 1)) }}</span>
                    </div>
                    <div class="fw-bold text-main text-truncate w-100 px-1">{{ $top2->display_name }}</div>
                    <div class="text-muted small mb-1">{{ $top2->jumlah }}x donasi</div>
                    <div class="fw-bold small mb-2" style="color: var(--color-primary)">{{ \App\Support\Rupiah::format($top2->total) }}</div>
                    <div class="podium-pillar">
                        <span class="fs-4 fw-bold">2</span>
                        <span class="small opacity-75">Perak</span>
                    </div>
                </div>
            @endif

            {{-- Juara 1 (Emas) --}}
            @if($top1)
                <div class="podium-item podium-item-1">
                    <div class="podium-avatar podium-badge-gold">
                        <i class="bi bi-star-fill podium-crown text-warning"></i>
                        <span>{{ strtoupper(substr($top1->display_name, 0, 1)) }}</span>
                    </div>
                    <div class="fw-bold text-main text-truncate w-100 px-1">{{ $top1->display_name }}</div>
                    <div class="text-muted small mb-1">{{ $top1->jumlah }}x donasi</div>
                    <div class="fw-bold fs-6 mb-2" style="color: var(--color-primary)">{{ \App\Support\Rupiah::format($top1->total) }}</div>
                    <div class="podium-pillar">
                        <i class="bi bi-trophy-fill fs-5 mb-1"></i>
                        <span class="fs-3 fw-bold">1</span>
                        <span class="small opacity-75">Juara 1</span>
                    </div>
                </div>
            @endif

            {{-- Juara 3 (Perunggu) --}}
            @if($top3)
                <div class="podium-item podium-item-3">
                    <div class="podium-avatar podium-badge-bronze">
                        <i class="bi bi-award podium-crown"></i>
                        <span>{{ strtoupper(substr($top3->display_name, 0, 1)) }}</span>
                    </div>
                    <div class="fw-bold text-main text-truncate w-100 px-1">{{ $top3->display_name }}</div>
                    <div class="text-muted small mb-1">{{ $top3->jumlah }}x donasi</div>
                    <div class="fw-bold small mb-2" style="color: var(--color-primary)">{{ \App\Support\Rupiah::format($top3->total) }}</div>
                    <div class="podium-pillar">
                        <span class="fs-4 fw-bold">3</span>
                        <span class="small opacity-75">Perunggu</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Peringkat Selanjutnya --}}
        @if($restDonors->isNotEmpty())
            <div class="panel shadow-sm">
                <div class="panel-pad pb-2 border-bottom">
                    <h2 class="h6 fw-bold text-main mb-0"><i class="bi bi-list-ol me-2 text-primary"></i>Peringkat Donatur Lainnya</h2>
                </div>
                <div>
                    @foreach($restDonors as $i => $donor)
                        <div class="rank-row">
                            <span class="rank-no">{{ $i + 1 }}</span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-main">{{ $donor->display_name }}</div>
                                <div class="text-muted small">{{ $donor->jumlah }} kali berdonasi</div>
                            </div>
                            <div class="fw-bold fs-6" style="color: var(--color-primary)">
                                {{ \App\Support\Rupiah::format($donor->total) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
