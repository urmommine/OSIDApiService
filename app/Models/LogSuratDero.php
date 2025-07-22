<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSuratDero extends Model
{
    protected $table = 'log_surat';
    protected $primaryKey = 'id';

    protected $connection = 'db_dero';

    public $timestamps = false;



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
        return $this->belongsTo(TwebPendudukDero::class, 'id_pend');
    }

    public function scopeStatus(mixed $query, mixed $value = 1)
    {
        return $query->where('status', $value);
    }
} 