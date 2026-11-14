<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'latitude',
        'longitude',
        'luas_total',
        'deskripsi',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'luas_total' => 'float',
    ];

    public function kelurahans(): HasMany
    {
        return $this->hasMany(Kelurahan::class);
    }

    public function getJumlahKelurahanAttribute(): int
    {
        return $this->kelurahans()->count();
    }

    /**
     * Hitung otomatis koordinat pusat (centroid) dan total luas dari kelurahan yang terelasi
     */
    public function recalculateCentroidAndArea(): void
    {
        $stats = $this->kelurahans()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw('AVG(latitude) as avg_lat, AVG(longitude) as avg_lng, SUM(luas_wilayah) as sum_luas')
            ->first();

        if ($stats && $stats->avg_lat && $stats->avg_lng) {
            $this->update([
                'latitude' => round((float) $stats->avg_lat, 8),
                'longitude' => round((float) $stats->avg_lng, 8),
                'luas_total' => round((float) ($stats->sum_luas ?? 0), 2),
            ]);
        }
    }

    public function __toString(): string
    {
        return $this->nama;
    }
}

