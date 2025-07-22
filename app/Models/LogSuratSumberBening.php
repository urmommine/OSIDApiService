<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSuratSumberBening extends Model
{
    protected $table = 'log_surat';
    protected $primaryKey = 'id';

    protected $connection = 'db_sumberbening';

    public $timestamps = false;

    public const KONSEP  = 0;
    public const CETAK   = 1;
    public const TOLAK   = -1;
    public const PERIKSA = 0;
    public const TERIMA  = 1;


    protected $fillable = [
        'id',
        'id_format_surat',
        'id_pend',
        'id_pamong',
        'nama_pamong',
        'nama_jabatan',
        'id_user',
        'tanggal',
        'bulan',
        'tahun',
        'no_surat',
        'nama_surat',
    ];

    public function penduduk()
    {
        return $this->belongsTo(TwebPendudukSumberBening::class, 'id_pend');
    }

    public function scopeStatus(mixed $query, mixed $value = 1)
    {
        return $query->where('status', $value);
    }
} 