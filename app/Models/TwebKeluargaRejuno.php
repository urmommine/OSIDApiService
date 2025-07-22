<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwebKeluargaRejuno extends Model
{
    protected $table = 'tweb_keluarga';
    protected $primaryKey = 'id';
   protected $connection = 'db_rejuno';

    protected $fillable = [
        'id',
        'no_kk',
        'nik_kepala',
        'tgl_daftar',
        'kelas_sosial',
        'tgl_cetak_kk',
        'alamat',
        'id_cluster',
        'updated_at',
        'updated_by',
    ];

    public function kepalaKeluarga()
    {
        return $this->hasOne(TwebPendudukRejuno::class, 'id_kk')->kepalaKeluarga();
    }

    public function anggota()
    {
        return $this->hasMany(TwebPendudukRejuno::class, 'id_kk')
            ->where('status_dasar', 1)
            ->orderBy('kk_level')
            ->orderBy('tanggal_lahir'); // Pastikan nama kolom sesuai
    }

    public function cluster()
    {
        return $this->belongsTo(TwebWilClusterRejuno::class, 'id_cluster', 'id');
    }

    public function scopeStatusAktif($query)
    {
        return $query->whereHas('kepalaKeluarga', function($q) {
            $q->where('status_dasar', 1);
        });
    }
} 