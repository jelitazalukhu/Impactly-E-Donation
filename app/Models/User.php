<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    // Password disimpan sebagai hash. Panggil Hash::make() saat membuat atau mengubah password.
    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'organization_name', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isPenggalang(): bool { return $this->role === 'penggalang'; }
    public function isDonatur(): bool    { return $this->role === 'donatur'; }

    /** Campaign milik penggalang. */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'id_user', 'id_user');
    }

    /** Donasi milik donatur. */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'id_user', 'id_user');
    }
}
