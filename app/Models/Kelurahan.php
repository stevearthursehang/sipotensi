<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Kelurahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kecamatan_id',
        'nama',
        'kode_wilayah',
        'kecamatan',
        'alamat_kantor',
        'jumlah_penduduk',
        'tenaga_kerja_tersedia',
        'akses_jalan',
        'listrik',
        'air_bersih',
        'internet',
        'geom',
        'latitude',
        'longitude',
        'luas_wilayah',
        'created_by',
    ];

    protected $casts = [
        'akses_jalan' => 'boolean',
        'listrik' => 'boolean',
        'air_bersih' => 'boolean',
        'internet' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'luas_wilayah' => 'decimal:2',
    ];

    // Accessor untuk luas wilayah dalam hektar
    public function getLuasWilayahAttribute($value)
    {
        if ($value !== null && $value > 0) {
            return (float) $value;
        }

        // Jika kolom luas_wilayah kosong tapi ada geom di database, hitung via PostGIS
        if ($this->exists && $this->id) {
            try {
                $result = DB::select("SELECT ST_Area(ST_Transform(geom, 3857)) / 10000 as area_ha FROM kelurahans WHERE id = ? AND geom IS NOT NULL", [$this->id]);
                if (!empty($result) && isset($result[0]->area_ha) && $result[0]->area_ha !== null) {
                    return round((float) $result[0]->area_ha, 2);
                }
            } catch (\Throwable $e) {
                // Abaikan jika query spasial gagal
            }
        }

        return $value ? (float) $value : null;
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function potensiInvestasis(): HasMany
    {
        return $this->hasMany(PotensiInvestasi::class);
    }

    public function infrastrukturs(): HasMany
    {
        return $this->hasMany(Infrastruktur::class);
    }

    public function minatInvestors(): HasMany
    {
        return $this->hasMany(MinatInvestor::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::saving(function ($kelurahan) {
            if ($kelurahan->kecamatan_id) {
                $kec = $kelurahan->kecamatan()->first();
                if ($kec) {
                    // Pastikan teks kecamatan selalu selaras dengan nama kecamatan terkait
                    $kelurahan->attributes['kecamatan'] = $kec->nama;

                    // Auto-fill koordinat dari kecamatan jika tidak diisi secara manual
                    if (empty($kelurahan->latitude) && !empty($kec->latitude)) {
                        $kelurahan->latitude = $kec->latitude;
                    }
                    if (empty($kelurahan->longitude) && !empty($kec->longitude)) {
                        $kelurahan->longitude = $kec->longitude;
                    }
                }
            }
        });

        static::saved(function ($kelurahan) {
            Cache::flush();
            try {
                \App\Http\Controllers\Api\KelurahanController::regenerateCompiledGeoJson();
            } catch (\Throwable $e) {}
            if ($kelurahan->kecamatan_id) {
                $kec = $kelurahan->kecamatan()->first();
                if ($kec) {
                    $kec->recalculateCentroidAndArea();
                }
            }
        });

        static::deleted(function ($kelurahan) {
            Cache::flush();
            try {
                \App\Http\Controllers\Api\KelurahanController::regenerateCompiledGeoJson();
            } catch (\Throwable $e) {}
            if ($kelurahan->kecamatan_id) {
                $kec = $kelurahan->kecamatan()->first();
                if ($kec) {
                    $kec->recalculateCentroidAndArea();
                }
            }
        });
    }
}
