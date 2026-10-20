<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $table = 'campaigns';
    protected $primaryKey = 'id_campaign';

    protected $fillable = [
        'id_user', 'id_category', 'title', 'description', 'image',
        'target_amount', 'deadline', 'status', 'rejection_note',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'deadline'      => 'date',
    ];

    /* ------------------------------ Scope ------------------------------ */

    /** Campaign yang tampil publik: status aktif dan deadline belum lewat. */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif')->whereDate('deadline', '>=', now()->toDateString());
    }

    /**
     * Menambahkan kolom hitungan "terkumpul" dan "jumlah_donasi"
     * (dari donasi paid) dalam satu query. Pakai: Campaign::withProgress()->get()
     */
    public function scopeWithProgress(Builder $query): Builder
    {
        return $query
            ->withSum('paidDonations as terkumpul', 'amount')
            ->withCount('paidDonations as jumlah_donasi');
    }

    /* ----------------------------- Accessor ---------------------------- */

    /** Dana terkumpul. Jika belum dihitung lewat withProgress(), dihitung saat diminta. */
    public function getTerkumpulAttribute($value): float
    {
        if ($value === null && ! array_key_exists('terkumpul', $this->attributes)) {
            $value = $this->paidDonations()->sum('amount');
        }

        return (float) ($value ?? 0);
    }

    public function getJumlahDonasiAttribute($value): int
    {
        if ($value === null && ! array_key_exists('jumlah_donasi', $this->attributes)) {
            $value = $this->paidDonations()->count();
        }

        return (int) ($value ?? 0);
    }

    /** Persentase asli (bisa lebih dari 100). */
    public function getPersenAttribute(): int
    {
        $target = (float) $this->target_amount;

        return $target > 0 ? (int) round($this->terkumpul / $target * 100) : 0;
    }

    /** Persentase untuk lebar progress bar (maksimal 100). */
    public function getProgressAttribute(): int
    {
        return min(100, $this->persen);
    }

    public function getSisaHariAttribute(): int
    {
        return max(0, (int) today()->diffInDays($this->deadline, false));
    }

    /** True jika campaign tidak lagi menerima donasi. */
    public function getBerakhirAttribute(): bool
    {
        return $this->status !== 'aktif' || $this->deadline->lt(today());
    }

    /** Ikon Bootstrap Icons sesuai nama kategori (dipakai saat campaign belum punya gambar). */
    public function getIconAttribute(): string
    {
        $name = strtolower($this->category->name ?? '');

        return match (true) {
            str_contains($name, 'bencana')    => 'bi-lightning-charge',
            str_contains($name, 'ibadah'),
            str_contains($name, 'masjid')     => 'bi-building',
            str_contains($name, 'panti')      => 'bi-people',
            str_contains($name, 'pendidikan') => 'bi-mortarboard',
            str_contains($name, 'kesehatan')  => 'bi-heart-pulse',
            default                           => 'bi-heart',
        };
    }

    /* ----------------------------- Relasi ------------------------------ */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'id_campaign', 'id_campaign');
    }

    /** Hanya donasi berstatus paid (dasar progress bar dan top donatur). */
    public function paidDonations(): HasMany
    {
        return $this->donations()->where('status', 'paid');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class, 'id_campaign', 'id_campaign');
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(FundDistribution::class, 'id_campaign', 'id_campaign');
    }
}
