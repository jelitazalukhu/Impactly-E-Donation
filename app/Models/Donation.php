<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    protected $table = 'donations';
    protected $primaryKey = 'id_donation';

    protected $fillable = [
        'id_campaign', 'id_user', 'amount', 'message', 'is_anonymous', 'status', 'paid_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'is_anonymous' => 'boolean',
        'paid_at'      => 'datetime',
    ];

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'id_campaign', 'id_campaign');
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'id_donation', 'id_donation');
    }

    /** Nol atau satu sertifikat per donasi. */
    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class, 'id_donation', 'id_donation');
    }

    /** Nama yang ditampilkan di daftar publik. */
    public function displayName(): string
    {
        return $this->is_anonymous ? 'Anonim' : ($this->donor->name ?? '-');
    }
}
