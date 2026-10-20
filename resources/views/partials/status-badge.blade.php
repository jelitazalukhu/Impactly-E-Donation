<span class="badge-status s-{{ $status }}">
    @if(in_array($status, ['aktif', 'paid']))
        <i class="bi bi-check-circle-fill"></i>
    @elseif(in_array($status, ['menunggu', 'pending']))
        <i class="bi bi-clock-fill"></i>
    @elseif(in_array($status, ['ditolak', 'failed']))
        <i class="bi bi-x-circle-fill"></i>
    @elseif(in_array($status, ['draft', 'expired', 'nonaktif']))
        <i class="bi bi-dash-circle"></i>
    @elseif($status === 'selesai')
        <i class="bi bi-flag-fill"></i>
    @endif
    {{ ucfirst($status) }}
</span>
