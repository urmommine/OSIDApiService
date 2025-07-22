<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSumberBening extends Model
{
    protected $table = 'program';
    protected $primaryKey = 'id';
    protected $connection = 'db_sumberbening';

    protected $fillable = [
        'id',
        'nama',
        'sasaran',
        'ndesc',
        'sdate',
        'edate',
        'userid',
        'status',
        'asaldana',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
} 