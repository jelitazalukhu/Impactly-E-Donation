<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id_payment';

    protected $fillable = [
        'id_donation', 'order_id', 'transaction_id', 'method', 'gateway_status',
    ];

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class, 'id_donation', 'id_donation');
    }
}
