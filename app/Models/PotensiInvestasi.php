<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PotensiInvestasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelurahan_id',
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'jenis_potensi',
        'komoditas_unggulan',
        'skala_peluang',
        'nilai_estimasi_investasi',
        'roi_estimasi',
        'status_lahan',
        'status_perizinan',
        'deskripsi',
        'analisis_swot',
        'foto_utama',
        'galeri_foto',
        'videos',
        'pdfs',
        'status_kesiapan_lahan',
        'akses_jalan',
        'listrik',
        'air_bersih',
        'internet',
        'jarak_pelabuhan',
        'jarak_bandara',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'nilai_estimasi_investasi' => 'decimal:2',
        'roi_estimasi' => 'decimal:2',
        'jarak_pelabuhan' => 'decimal:2',
        'jarak_bandara' => 'decimal:2',
        'akses_jalan' => 'boolean',
        'listrik' => 'boolean',
        'air_bersih' => 'boolean',
        'internet' => 'boolean',
        'analisis_swot' => 'array',
        'galeri_foto' => 'array',
        'videos' => 'array',
        'pdfs' => 'array',
    ];

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function minatInvestors(): HasMany
    {
        return $this->hasMany(MinatInvestor::class);
    }

    public function sektor(): BelongsTo
    {
        return $this->belongsTo(SektorInvestasi::class, 'jenis_potensi', 'nama');
    }

    public function getNilaiEstimasiAttribute()
    {
        return $this->nilai_estimasi_investasi;
    }

    public function setNilaiEstimasiAttribute($value)
    {
        $this->attributes['nilai_estimasi_investasi'] = $value;
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::flush();
            try {
                \App\Http\Controllers\Api\KelurahanController::regenerateCompiledGeoJson();
            } catch (\Throwable $e) {}
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::flush();
            try {
                \App\Http\Controllers\Api\KelurahanController::regenerateCompiledGeoJson();
            } catch (\Throwable $e) {}
        });
    }
}
