<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RtmSumberBening extends Model
{
    protected $table = 'tweb_rtm';
    protected $primaryKey = 'id';
    protected $connection = 'db_sumberbening';

    protected $fillable = [
        'id',
        'nik_kepala',
        'no_kk',
        'tgl_daftar',
        'kelas_sosial',
        'bdt',
        'terdaftar_dtks',
    ];

    public function kepalaKeluarga()
    {
        return $this->hasOne(TwebPendudukSumberBening::class, 'id', 'nik_kepala');
    }

    public function anggota()
    {
        return $this->hasMany(TwebPendudukSumberBening::class, 'id_rtm', 'no_kk')->status();
    }

    public function scopeStatus($query)
    {
        return $query->whereHas('kepalaKeluarga', function($q) {
            $q->status()->where('rtm_level', 1);
        });
    }
} 