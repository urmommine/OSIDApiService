<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendudukMandiriSumberBening extends Model
{
    protected $table = 'tweb_penduduk_mandiri';
    protected $primaryKey = 'id_pend';
    protected $connection = 'db_sumberbening';

    protected $fillable = [
        'pin',
        'last_login',
        'tanggal_buat',
        'id_pend',
        'aktif',
        'scan_ktp',
        'scan_kk',
        'foto_selfie',
        'ganti_pin',
        'email_verified_at',
        'remember_token',
        'updated_at',
    ];

    public function penduduk()
    {
        return $this->belongsTo(TwebPendudukSumberBening::class, 'id_pend');
    }

    public function dokumen()
    {
        // Jika ada model dokumen per desa, ganti dengan model yang sesuai
        return $this->belongsTo(DokumenSumberBening::class, 'id_pend');
    }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('aktif', $value);
    }
} 