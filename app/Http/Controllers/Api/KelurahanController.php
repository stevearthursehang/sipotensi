<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class KelurahanController extends Controller
{
    /**
     * Path to the precompiled GeoJSON file for Sangihe Kelurahans
     */
    public static function getCompiledGeoJsonPath(): string
    {
        return storage_path('app/public/sangihe_kelurahans.geojson');
    }

    /**
     * Regenerate the precompiled GeoJSON FeatureCollection
     */
    public static function regenerateCompiledGeoJson(): array
    {
        $geojsonDir = storage_path('app/public/uploads/geojson');

        $kelurahans = Kelurahan::select([
            'id', 'nama', 'kecamatan', 'alamat_kantor', 'jumlah_penduduk', 
            'tenaga_kerja_tersedia', 'luas_wilayah', 'latitude', 'longitude',
            'akses_jalan', 'listrik', 'air_bersih', 'internet'
        ])->with(['potensiInvestasis' => function($q) {
            $q->select([
                'id', 'kelurahan_id', 'nama', 'jenis_potensi', 'komoditas_unggulan',
                'skala_peluang', 'nilai_estimasi_investasi', 'roi_estimasi',
                'deskripsi', 'status_lahan', 'status_perizinan'
            ]);
        }])->orderBy('nama', 'asc')->get();

        $features = [];
        foreach ($kelurahans as $k) {
            $candidates = [
                $k->nama . '.json',
                trim($k->nama) . "'.json",
                trim($k->nama) . "_.json",
                $k->nama . ' ' . $k->kecamatan . '.json',
            ];
            if (stripos($k->kecamatan, 'Tabukan Utara') !== false) {
                $candidates[] = $k->nama . ' Tabut.json';
            }
            if (stripos($k->kecamatan, 'Manganitu') !== false) {
                $candidates[] = $k->nama . ' Manganitu.json';
            }

            $geometry = null;
            foreach ($candidates as $cand) {
                $path = $geojsonDir . DIRECTORY_SEPARATOR . $cand;
                if (file_exists($path)) {
                    $raw = json_decode(file_get_contents($path), true);
                    if (isset($raw['geometry'])) {
                        $geometry = $raw['geometry'];
                        break;
                    }
                }
            }

            if ($geometry) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        'id' => $k->id,
                        'nama' => $k->nama,
                        'kecamatan' => $k->kecamatan,
                        'alamat_kantor' => $k->alamat_kantor,
                        'jumlah_penduduk' => $k->jumlah_penduduk,
                        'tenaga_kerja_tersedia' => $k->tenaga_kerja_tersedia,
                        'luas_wilayah' => (float) $k->luas_wilayah,
                        'latitude' => (float) $k->latitude,
                        'longitude' => (float) $k->longitude,
                        'infrastruktur' => [
                            'akses_jalan' => (bool) $k->akses_jalan,
                            'listrik' => (bool) $k->listrik,
                            'air_bersih' => (bool) $k->air_bersih,
                            'internet' => (bool) $k->internet,
                        ],
                        'potensi_investasi' => $k->potensiInvestasis->map(function ($potensi) {
                            return [
                                'id' => $potensi->id,
                                'nama' => $potensi->nama,
                                'jenis_potensi' => $potensi->jenis_potensi,
                                'komoditas_unggulan' => $potensi->komoditas_unggulan,
                                'skala_peluang' => $potensi->skala_peluang,
                                'nilai_estimasi' => (float) $potensi->nilai_estimasi_investasi,
                                'roi_estimasi' => (float) $potensi->roi_estimasi,
                                'keterangan' => $potensi->deskripsi,
                                'status' => $potensi->status_lahan,
                                'status_perizinan' => $potensi->status_perizinan,
                            ];
                        })->values()->all(),
                    ],
                ];
            }
        }

        $featureCollection = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        $compiledPath = self::getCompiledGeoJsonPath();
        @file_put_contents($compiledPath, json_encode($featureCollection));

        return $featureCollection;
    }

    /**
     * Get GeoJSON data for frontend map (blazing fast cached implementation)
     */
    public function geojson(Request $request)
    {
        // Release session lock immediately for read-only GeoJSON endpoint
        if (session()->isStarted()) {
            session_write_close();
        }

        $compiledPath = self::getCompiledGeoJsonPath();

        // If no query filters are applied, return the precompiled file directly (<5ms)
        $hasFilters = $request->filled('kecamatan') || 
                      $request->filled('sektor') || 
                      $request->filled('infrastruktur') || 
                      $request->filled('luas_min') || 
                      $request->filled('luas_max') || 
                      $request->filled('sort_by');

        if (!$hasFilters && file_exists($compiledPath)) {
            return response()->file($compiledPath, [
                'Content-Type' => 'application/json',
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        // Get base FeatureCollection from file or regenerate
        if (file_exists($compiledPath)) {
            $data = json_decode(file_get_contents($compiledPath), true);
        } else {
            $data = self::regenerateCompiledGeoJson();
        }

        if (!$hasFilters) {
            return response()->json($data);
        }

        // Apply filters in-memory (sub-millisecond execution)
        $features = $data['features'] ?? [];

        if ($request->filled('kecamatan')) {
            $kec = strtolower($request->kecamatan);
            $features = array_filter($features, function ($f) use ($kec) {
                return strtolower($f['properties']['kecamatan'] ?? '') === $kec;
            });
        }

        if ($request->filled('sektor')) {
            $sektor = strtolower($request->sektor);
            $features = array_filter($features, function ($f) use ($sektor) {
                $potensis = $f['properties']['potensi_investasi'] ?? [];
                foreach ($potensis as $p) {
                    if (strtolower($p['jenis_potensi'] ?? '') === $sektor) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($request->filled('infrastruktur') && is_array($request->infrastruktur)) {
            $infras = $request->infrastruktur;
            $features = array_filter($features, function ($f) use ($infras) {
                $fInfra = $f['properties']['infrastruktur'] ?? [];
                foreach ($infras as $infra) {
                    if (empty($fInfra[$infra])) {
                        return false;
                    }
                }
                return true;
            });
        }

        if ($request->filled('luas_min')) {
            $min = (float) $request->luas_min;
            $features = array_filter($features, fn($f) => ($f['properties']['luas_wilayah'] ?? 0) >= $min);
        }

        if ($request->filled('luas_max')) {
            $max = (float) $request->luas_max;
            $features = array_filter($features, fn($f) => ($f['properties']['luas_wilayah'] ?? 0) <= $max);
        }

        // Sorting in-memory
        if ($request->filled('sort_by')) {
            $sortBy = $request->get('sort_by');
            $sortOrder = strtolower($request->get('sort_order', 'asc')) === 'desc' ? 'desc' : 'asc';

            usort($features, function ($a, $b) use ($sortBy, $sortOrder) {
                $valA = $a['properties'][$sortBy] ?? null;
                $valB = $b['properties'][$sortBy] ?? null;

                if ($valA == $valB) return 0;
                $cmp = ($valA < $valB) ? -1 : 1;
                return ($sortOrder === 'desc') ? -$cmp : $cmp;
            });
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => array_values($features),
        ]);
    }

    /**
     * Get list of kelurahans (without geometry) for dropdowns
     */
    public function index(Request $request)
    {
        $query = Kelurahan::select('id', 'nama', 'kecamatan', 'latitude', 'longitude', 'luas_wilayah');

        if ($request->has('kecamatan') && $request->kecamatan) {
            $query->where('kecamatan', $request->kecamatan);
        }

        return response()->json($query->orderBy('nama')->get());
    }

    /**
     * Get detail of a kelurahan
     */
    public function show($id)
    {
        $kelurahan = Kelurahan::with(['potensiInvestasis', 'infrastrukturs'])->findOrFail($id);

        return response()->json($kelurahan);
    }
}
