<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwebWilClusterRejuno extends Model
{
    protected $table = 'tweb_wil_clusterdesa';
    protected $connection = 'db_rejuno';
    protected $fillable = [
        'id',
        'rt',
        'rw', 
        'dusun',
        'id_kepala',
        'lat',
        'lng',
        'zoom',
        'map_tipe',
        'path',
        'enabled',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the penduduk that belong to this cluster
     */
    public function penduduk()
    {
        return $this->hasMany(TwebPendudukRejuno::class, 'id_cluster', 'id');
    }

    /**
     * Get the kepala desa (village head) information
     */
    public function kepala()
    {
        return $this->belongsTo(TwebPendudukRejuno::class, 'id_kepala', 'id');
    }
} 