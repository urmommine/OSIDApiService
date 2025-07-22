<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TwebPendudukDero;
use App\Models\TwebPendudukRejuno;
use App\Models\TwebPendudukSumberBening;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // <-- missing include fixed
use Illuminate\Support\Facades\DB;


class TwebPendudukController extends Controller
{

    protected function resolvePendudukModel($desa)
    {
        switch (strtolower($desa)) {
            case 'sumberbening':
                return TwebPendudukSumberBening::class;
            case 'dero':
                return TwebPendudukDero::class;
            case 'rejuno':
                return TwebPendudukRejuno::class;
            // Tambahkan case lain sesuai kebutuhan
            default:
                return null;
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $desa): JsonResponse
    {
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        try {
            $query = $model::query()->where('status_dasar', 1); // hanya yang hidup
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }
            $perPage = min($request->get('per_page', 15), 100); // Maksimal 100 per halaman
            $penduduk = $query->paginate($perPage);
            return response()->json([
                'status' => 'success',
                'message' => 'Data penduduk berhasil diambil',
                'data' => $penduduk->items(),
                'pagination' => [
                    'current_page' => $penduduk->currentPage(),
                    'last_page' => $penduduk->lastPage(),
                    'per_page' => $penduduk->perPage(),
                    'total' => $penduduk->total(),
                    'from' => $penduduk->firstItem(),
                    'to' => $penduduk->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data penduduk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

       /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $desa): JsonResponse
    {
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'nik' => 'required|string|unique:tweb_penduduk,nik|size:16',
                'sex' => 'required|in:1,2',
                'tempatlahir' => 'required|string|max:100',
                'agama_id' => 'required|integer',
                'pendidikan_kk_id' => 'required|integer',
                'pekerjaan_id' => 'required|integer',
                'status_kawin' => 'required|integer',
                'warganegara_id' => 'required|integer',
                'golongan_darah_id' => 'required|integer',
                'id_cluster' => 'required|integer',
                'status_dasar' => 'required|integer',
                'alamat_sekarang' => 'required|string',
                'email' => 'nullable|email|unique:tweb_penduduk,email',
                'telepon' => 'nullable|string|max:20',
            ]);
            $penduduk = $model::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Data penduduk berhasil ditambahkan',
                'data' => $penduduk
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan data penduduk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($desa, $id): JsonResponse
    {
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        $data = $model::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data penduduk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data penduduk berhasil ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $desa, $id): JsonResponse
    {
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        $penduduk = $model::find($id);
        if (!$penduduk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data penduduk tidak ditemukan'
            ], 404);
        }
        try {
            $validated = $request->validate([
                'nama' => 'sometimes|required|string|max:100',
                'nik' => 'sometimes|required|string|unique:tweb_penduduk,nik,' . $penduduk->id . '|size:16',
                'sex' => 'sometimes|required|in:1,2',
                'tempatlahir' => 'sometimes|required|string|max:100',
                'agama_id' => 'sometimes|required|integer',
                'pendidikan_kk_id' => 'sometimes|required|integer',
                'pekerjaan_id' => 'sometimes|required|integer',
                'status_kawin' => 'sometimes|required|integer',
                'warganegara_id' => 'sometimes|required|integer',
                'golongan_darah_id' => 'sometimes|required|integer',
                'id_cluster' => 'sometimes|required|integer',
                'status_dasar' => 'sometimes|required|integer',
                'alamat_sekarang' => 'sometimes|required|string',
                'email' => 'nullable|email|unique:tweb_penduduk,email,' . $penduduk->id,
                'telepon' => 'nullable|string|max:20',
            ]);

            $penduduk->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Data penduduk berhasil diperbarui',
                'data' => $penduduk->fresh()
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data penduduk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($desa, $id): JsonResponse
    {
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        $penduduk = $model::find($id);
        if (!$penduduk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data penduduk tidak ditemukan'
            ], 404);
        }
        try {
            $penduduk->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data penduduk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data penduduk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search($desa, string $keyword): JsonResponse
    {
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        try {
            $penduduk = $model::where('nama', 'like', "%{$keyword}%")
                ->orWhere('nik', 'like', "%{$keyword}%")
                ->orWhere('alamat_sekarang', 'like', "%{$keyword}%")
                ->paginate(15);

            return response()->json([
                'status' => 'success',
                'message' => 'Hasil pencarian penduduk',
                'data' => $penduduk->items(),
                'pagination' => [
                    'current_page' => $penduduk->currentPage(),
                    'last_page' => $penduduk->lastPage(),
                    'per_page' => $penduduk->perPage(),
                    'total' => $penduduk->total(),
                    'keyword' => $keyword
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mencari data penduduk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByNik($desa, string $nik): JsonResponse
    {
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        try {
            $penduduk = $model::where('nik', $nik)->first();

            if (!$penduduk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data penduduk dengan NIK tersebut tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data penduduk berhasil ditemukan',
                'data' => $penduduk
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mencari data penduduk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getClustersDetailed(Request $request): JsonResponse
    {
        $desa = $request->get('desa');
        $model = $this->resolvePendudukModel($desa);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Desa tidak dikenali'], 404);
        }
        // Get the connection name from the model
        $connection = (new $model)->getConnectionName();
        try {
            $clusters = DB::connection($connection)->table('tweb_penduduk as p')
                ->join('tweb_wil_clusterdesa as c', 'p.id_cluster', '=', 'c.id')
                ->select(
                    'c.id as id_cluster',
                    'c.dusun',
                    'c.rt',
                    'c.rw',
                    DB::raw('COUNT(p.id) as total_penduduk'),
                    DB::raw('COUNT(CASE WHEN p.sex = 1 THEN 1 END) as laki_laki'),
                    DB::raw('COUNT(CASE WHEN p.sex = 2 THEN 1 END) as perempuan'),
                    DB::raw('COUNT(CASE WHEN p.status_dasar = 1 THEN 1 END) as hidup'),
                    DB::raw('COUNT(CASE WHEN p.status_dasar = 2 THEN 1 END) as mati'),
                    DB::raw('COUNT(CASE WHEN p.status_dasar = 3 THEN 1 END) as pindah'),
                    DB::raw('COUNT(CASE WHEN p.status_dasar = 4 THEN 1 END) as hilang')
                )
                ->groupBy('c.id', 'c.dusun', 'c.rt', 'c.rw')
                ->orderBy('c.dusun')
                ->orderBy('c.rw')
                ->orderBy('c.rt')
                ->get();

            $totalSum = $clusters->sum('total_penduduk');
            $totalLakiLaki = $clusters->sum('laki_laki');
            $totalPerempuan = $clusters->sum('perempuan');

            return response()->json([
                'status' => 'success',
                'message' => 'Data detail cluster berhasil diambil',
                'data' => [
                    'clusters' => $clusters,
                    'summary' => [
                        'total_clusters' => $clusters->count(),
                        'total_penduduk' => $totalSum,
                        'total_laki_laki' => $totalLakiLaki,
                        'total_perempuan' => $totalPerempuan,
                        'average_per_cluster' => $clusters->count() > 0 ? round($totalSum / $clusters->count(), 2) : 0,
                        'gender_ratio' => $totalPerempuan > 0 ? round($totalLakiLaki / $totalPerempuan, 2) : 0
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data detail cluster',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllPendudukFromAllDesa(): JsonResponse
    {
        try {
            $modelsPath = app_path('Models');
            $files = glob($modelsPath . '/TwebPenduduk*.php');
            $data = [];
            foreach ($files as $file) {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $fullClass = "App\\Models\\$className";
                $desa = strtolower(str_replace('TwebPenduduk', '', $className));
                if (!class_exists($fullClass)) {
                    // Skip jika model tidak ditemukan
                    continue;
                }
                try {
                    $modelInstance = new $fullClass;
                    $connection = $modelInstance->getConnectionName();
                    // Query hanya yang status_dasar = 1
                    $penduduk = $fullClass::where('status_dasar', 1)->get();
                    $clusterClass = str_replace('TwebPenduduk', 'TwebWilCluster', $className);
                    $clusterFullClass = "App\\Models\\$clusterClass";
                    $idClusters = $penduduk->pluck('id_cluster')->unique()->filter();
                    $jumlahDusun = 0;
                    if (class_exists($clusterFullClass) && $idClusters->count() > 0) {
                        try {
                            $jumlahDusun = $clusterFullClass::on($connection)
                                ->whereIn('id', $idClusters)
                                ->distinct('dusun')
                                ->count('dusun');
                        } catch (\Throwable $e) {
                            $jumlahDusun = 0;
                        }
                    }
                    // Hitung jumlah keluarga aktif menggunakan log_keluarga
                    $jumlahKeluarga = 0;
                    try {
                        $date = date('Y-m-d');
                        $configId = 1;
                        $tgl = date('Y-m-d', strtotime($date . ' + 1 day'));
                        $sqlRaw = "SELECT MAX(id) as id FROM log_keluarga WHERE id_kk IS NOT NULL AND config_id = $configId AND tgl_peristiwa < '$tgl' GROUP BY id_kk";
                        $jumlahKeluarga = DB::connection($connection)
                            ->table('tweb_keluarga')
                            ->select('tweb_keluarga.id')
                            ->join('tweb_penduduk', 'tweb_keluarga.nik_kepala', '=', 'tweb_penduduk.id', 'LEFT')
                            ->join('log_keluarga', 'log_keluarga.id_kk', '=', 'tweb_keluarga.id', 'LEFT')
                            ->where('tweb_penduduk.kk_level', '1')
                            ->where('tweb_penduduk.status_dasar', '1')
                            ->whereNotIn('log_keluarga.id_peristiwa', [2, 3, 4])
                            ->join(DB::raw("({$sqlRaw}) as log"), 'log.id', '=', 'log_keluarga.id')
                            ->count();
                    } catch (\Throwable $e) {
                        $jumlahKeluarga = 0;
                    }
                    // Hitung jumlah surat dari model LogSurat{Desa} jika ada
                    $logSuratClass = "App\\Models\\LogSurat" . ucfirst($desa);
                    $jumlahSurat = null;
                    if (class_exists($logSuratClass)) {
                        try {
                            $jumlahSurat = $logSuratClass::on($connection)->count();
                        } catch (\Throwable $e) {
                            $jumlahSurat = null;
                        }
                    }
                    // Hitung jumlah kelompok berdasarkan tipe
                    $kelompokClass = "App\\Models\\Kelompok" . ucfirst($desa);
                    $jumlahKelompok = null;
                    if (class_exists($kelompokClass)) {
                        try {
                            $jumlahKelompok = $kelompokClass::on($connection)->count();
                        } catch (\Throwable $e) {
                            $jumlahKelompok = null;
                        }
                    }
                    // Hitung jumlah RTM (rumah tangga) dari model Rtm{Desa} jika ada
                    $rtmClass = "App\\Models\\Rtm" . ucfirst($desa);
                    $jumlahRtm = null;
                    if (class_exists($rtmClass)) {
                        try {
                            $jumlahRtm = $rtmClass::on($connection)->status()->count();
                        } catch (\Throwable $e) {
                            $jumlahRtm = null;
                        }
                    }
                    // Hitung jumlah program (bantuan) dari model Program{Desa} berdasarkan sasaran
                    $programClass = "App\\Models\\Program" . ucfirst($desa);
                    $jumlahProgram = null;
                    if (class_exists($programClass)) {
                        try {
                            $jumlahProgram = $programClass::on($connection)
                                ->whereNotNull('sasaran')
                                ->distinct('sasaran')
                                ->count('sasaran');
                        } catch (\Throwable $e) {
                            $jumlahProgram = null;
                        }
                    }
                    // Hitung jumlah layanan mandiri aktif dari model PendudukMandiri{Desa}
                    $mandiriClass = "App\\Models\\PendudukMandiri" . ucfirst($desa);
                    $jumlahLayananMandiri = null;
                    if (class_exists($mandiriClass)) {
                        try {
                            $jumlahLayananMandiri = $mandiriClass::status(1)->count();
                        } catch (\Throwable $e) {
                            $jumlahLayananMandiri = null;
                        }
                    }
                    $data[$desa] = [
                        'total_penduduk' => $penduduk->count(),
                        'jumlah_dusun' => $jumlahDusun,
                        'jumlah_keluarga' => $jumlahKeluarga,
                        'jumlah_surat' => $jumlahSurat,
                        'jumlah_kelompok' => $jumlahKelompok,
                        'jumlah_rtm' => $jumlahRtm,
                        'jumlah_program' => $jumlahProgram,
                        'jumlah_layanan_mandiri' => $jumlahLayananMandiri,
                    ];
                } catch (\Throwable $e) {
                    // Jika error pada desa tertentu, skip dan lanjutkan desa lain
                    $data[$desa] = [
                        'error' => $e->getMessage()
                    ];
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Summary penduduk dari semua desa berhasil diambil',
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
