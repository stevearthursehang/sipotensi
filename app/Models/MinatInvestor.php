<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinatInvestor extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'email',
        'telepon',
        'perusahaan',
        'jenis_investor',
        'asal_negara',
        'nib',
        'rentang_modal',
        'nilai_komitmen',
        'model_kerjasama',
        'timeline_realisasi',
        'kebutuhan_fasilitasi',
        'pesan',
        'dokumen_loi',
        'status',
        'catatan_admin',
        'tanggal_tindak_lanjut',
        'potensi_investasi_id',
        'kelurahan_id',
    ];

    protected $casts = [
        'kebutuhan_fasilitasi' => 'array',
        'nilai_komitmen' => 'decimal:2',
        'tanggal_tindak_lanjut' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function potensiInvestasi(): BelongsTo
    {
        return $this->belongsTo(PotensiInvestasi::class);
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }
}
