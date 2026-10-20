<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundDistribution extends Model
{
    protected $table = 'fund_distributions';
    protected $primaryKey = 'id_distribution';

    protected $fillable = [
        'id_campaign', 'amount', 'recipient', 'description', 'proof_file', 'distributed_at', 'is_active',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'distributed_at' => 'date',
        'is_active'      => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'id_campaign', 'id_campaign');
    }
}
