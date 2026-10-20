<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id_category';

    protected $fillable = ['name', 'description'];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'id_category', 'id_category');
    }
}
