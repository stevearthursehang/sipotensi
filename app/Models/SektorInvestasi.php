<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SektorInvestasi extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
    ];

    public function potensiInvestasis(): HasMany
    {
        return $this->hasMany(PotensiInvestasi::class, 'jenis_potensi', 'nama');
    }
}
