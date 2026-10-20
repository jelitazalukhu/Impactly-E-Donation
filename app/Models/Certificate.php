<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $table = 'certificates';
    protected $primaryKey = 'id_certificate';

    // Tabel ini hanya memakai issued_at, tanpa created_at dan updated_at.
    public $timestamps = false;

    protected $fillable = ['id_donation', 'certificate_number', 'file_path', 'issued_at'];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class, 'id_donation', 'id_donation');
    }
}
