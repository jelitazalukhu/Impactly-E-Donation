<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignUpdate extends Model
{
    protected $table = 'campaign_updates';
    protected $primaryKey = 'id_campaign_update';

    protected $fillable = ['id_campaign', 'title', 'content', 'image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'id_campaign', 'id_campaign');
    }
}
