<div class="card campaign-card h-100">
    <a href="{{ route('campaigns.show', $campaign) }}" class="campaign-thumb" aria-label="{{ $campaign->title }}">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" loading="lazy">
        @else
            <div class="thumb-placeholder"><i class="bi {{ $campaign->icon }}" aria-hidden="true"></i></div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <div class="mb-2"><span class="cat-badge">{{ $campaign->category->name ?? 'Umum' }}</span></div>
        <h3 class="card-title h6 mb-1"><a href="{{ route('campaigns.show', $campaign) }}">{{ $campaign->title }}</a></h3>
        <div class="text-muted small mb-3">{{ $campaign->owner->organization_name ?? $campaign->owner->name ?? '-' }}</div>

        <div class="mt-auto">
            <div class="progress-track mb-2" role="progressbar" aria-valuenow="{{ $campaign->progress }}" aria-valuemin="0" aria-valuemax="100" aria-label="Progress donasi">
                <div class="progress-fill {{ $campaign->persen >= 100 ? 'done' : '' }}" style="width: {{ $campaign->progress }}%"></div>
            </div>
            <div class="d-flex justify-content-between align-items-baseline">
                <div>
                    <div class="fw-bold">{{ \App\Support\Rupiah::format($campaign->terkumpul) }}</div>
                    <div class="text-muted small">terkumpul dari {{ \App\Support\Rupiah::short($campaign->target_amount) }}</div>
                </div>
                <div class="fw-bold" style="color: var(--color-primary)">{{ $campaign->persen }}%</div>
            </div>
            <div class="d-flex justify-content-between text-muted small mt-2 pt-2 border-top">
                <span><i class="bi bi-people me-1" aria-hidden="true"></i>{{ $campaign->jumlah_donasi }} donasi</span>
                <span><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $campaign->sisa_hari }} hari lagi</span>
            </div>
        </div>
    </div>
</div>
