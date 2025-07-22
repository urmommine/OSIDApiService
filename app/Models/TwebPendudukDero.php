<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TwebPendudukDero extends Model
{
    protected $table = 'tweb_penduduk';
    protected $primaryKey = 'id';
   protected $connection = 'db_dero'; // <--- Tambahkan ini
    
    protected $fillable = [
        "id",
        "config_id",
        "nama",
        "nik",
        "id_kk",
        "kk_level",
        "id_rtm",
        "rtm_level",
        "sex",
        "tempatlahir",
        "agama_id",
        "pendidikan_kk_id",
        "pendidikan_sedang_id",
        "pekerjaan_id",
        "status_kawin",
        "warganegara_id",
        "dokumen_pasport",
        "dokumen_kitas",
        "ayah_nik",
        "ibu_nik",
        "nama_ayah",
        "nama_ibu",
        "foto",
        "golongan_darah_id",
        "id_cluster",
        "status",
        "alamat_sebelumnya",
        "alamat_sekarang",
        "status_dasar",
        "hamil",
        "cacat_id",
        "sakit_menahun_id",
        "akta_lahir",
        "akta_perkawinan",
        "tanggalperkawinan",
        "akta_perceraian",
        "tanggalperceraian",
        "cara_kb_id",
        "telepon",
        "tanggal_akhir_paspor",
        "no_kk_sebelumnya",
        "ktp_el",
        "status_rekam",
        "waktu_lahir",
        "tempat_dilahirkan",
        "jenis_kelahiran",
        "kelahiran_anak_ke",
        "penolong_kelahiran",
        "berat_lahir",
        "panjang_lahir",
        "tag_id_card",
        "id_asuransi",
        "no_asuransi",
        "email",
        "email_token",
        "email_tgl_kadaluarsa",
        "email_tgl_verifikasi",
        "telegram",
        "telegram_token",
        "telegram_tgl_kadaluarsa",
        "telegram_tgl_verifikasi",
        "bahasa_id",
        "ket",
        "negara_asal",
        "tempat_cetak_ktp",
        "tanggal_cetak_ktp",
        "suku",
        "bpjs_ketenagakerjaan",
        "hubung_warga",
        "created_at",
        "created_by",
        "updated_at",
        "update_by"
    ];

    protected $casts = [
        'tanggalperkawinan' => 'date',
        'tanggalperceraian' => 'date',
        'tanggal_akhir_paspor' => 'date',
        'waktu_lahir' => 'datetime',
        'tanggal_cetak_ktp' => 'date',
        'email_tgl_kadaluarsa' => 'datetime',
        'email_tgl_verifikasi' => 'datetime',
        'telegram_tgl_kadaluarsa' => 'datetime',
        'telegram_tgl_verifikasi' => 'datetime',
        'hamil' => 'boolean',
        'ktp_el' => 'boolean',
    ];

    public function keluarga()
    {
        return $this->belongsTo(TwebKeluargaDero::class, 'id_kk')->withDefault()->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }
    /**
     * Get the cluster information for this penduduk
     */
    public function cluster()
    {
        return $this->belongsTo(TwebWilClusterDero::class, 'id_cluster', 'id');
    }

    public function logSurat(): HasMany
    {
        return $this->hasMany(LogSuratDero::class, 'id_pend');
    }

    public function scopeStatus($query, $value = 1)
    {
        return $query->where('status_dasar', $value);
    }

    public function scopeKepalaKeluarga($query)
    {
        return $query->where('kk_level', 1); // SHDKEnum::KEPALA_KELUARGA = 1
    }
}
