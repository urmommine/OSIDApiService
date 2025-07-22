<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelompokRejuno extends Model
{
    protected $table = 'kelompok';
    protected $primaryKey = 'id';
    protected $connection = 'db_rejuno';

    protected $fillable = [
        'id',
        'id_master',
        'id_ketua',
        'nama',
        'slug',
        'keterangan',
        'kode',
        'tipe',
    ];

    /**
     * Get the master group that this group belongs to
     */
    public function master()
    {
        return $this->belongsTo(KelompokMasterRejuno::class, 'id_master');
    }

    /**
     * Get the leader (ketua) of this group
     */
    public function ketua()
    {
        return $this->belongsTo(TwebPendudukRejuno::class, 'id_ketua');
    }

    /**
     * Get all members of this group
     */
    public function anggota()
    {
        return $this->hasMany(KelompokAnggotaRejuno::class, 'id_kelompok');
    }

    /**
     * Scope to filter by group type
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Scope to filter by master group
     */
    public function scopeMaster($query, $idMaster)
    {
        return $query->where('id_master', $idMaster);
    }
} 