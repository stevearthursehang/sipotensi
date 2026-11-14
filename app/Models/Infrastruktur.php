<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Infrastruktur extends Model
{
    protected $fillable = [
        'kelurahan_id',
        'nama',
        'jenis',
        'deskripsi',
        'latitude',
        'longitude',
        'geom',
    ];

    protected $casts = [
        'geom' => 'string',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }
}
