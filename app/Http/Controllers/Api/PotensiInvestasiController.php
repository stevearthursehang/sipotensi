<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PotensiInvestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PotensiInvestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = PotensiInvestasi::with('kelurahan')
            ->whereHas('kelurahan', function ($q) {
                $q->whereNotNull('geom');
            });

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ILIKE', '%' . $search . '%')
                  ->orWhere('jenis_potensi', 'ILIKE', '%' . $search . '%')
                  ->orWhere('keterangan', 'ILIKE', '%' . $search . '%')
                  ->orWhereHas('kelurahan', function ($subQ) use ($search) {
                      $subQ->where('nama', 'ILIKE', '%' . $search . '%')
                           ->orWhere('kecamatan', 'ILIKE', '%' . $search . '%');
                  });
            });
        }

        // Filter infrastruktur
        if ($request->has('infrastruktur')) {
            $infrastruktur = $request->input('infrastruktur');
            $query->whereHas('kelurahan', function ($q) use ($infrastruktur) {
                foreach ($infrastruktur as $infra) {
                    $q->where($infra, true);
                }
            });
        }

        // Filter kecamatan
        if ($request->has('kecamatan') && $request->kecamatan) {
            $query->whereHas('kelurahan', function ($q) use ($request) {
                $q->where('kecamatan', $request->input('kecamatan'));
            });
        }

        // Filter jenis_potensi
        if ($request->has('jenis_potensi') && $request->jenis_potensi) {
            $query->where('jenis_potensi', $request->input('jenis_potensi'));
        }

        // Filter skala_peluang
        if ($request->has('skala_peluang') && $request->skala_peluang) {
            $query->where('skala_peluang', $request->input('skala_peluang'));
        }

        // Filter luas wilayah
        if ($request->has('luas_min')) {
            $query->whereHas('kelurahan', function ($q) use ($request) {
                $q->where('luas_wilayah', '>=', $request->input('luas_min'));
            });
        }
        if ($request->has('luas_max')) {
            $query->whereHas('kelurahan', function ($q) use ($request) {
                $q->where('luas_wilayah', '<=', $request->input('luas_max'));
            });
        }

        // Spatial bounds filter
        if ($request->has('north') && $request->has('south') && $request->has('east') && $request->has('west')) {
            $north = $request->input('north');
            $south = $request->input('south');
            $east = $request->input('east');
            $west = $request->input('west');
            $query->whereBetween('latitude', [$south, $north])
                  ->whereBetween('longitude', [$west, $east]);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'nama');
        $sortDir = $request->input('sort_direction', 'asc');
        if ($sortBy === 'nama') {
            $query->join('kelurahans', 'potensi_investasis.kelurahan_id', '=', 'kelurahans.id')
                  ->select('potensi_investasis.*')
                  ->orderBy('kelurahans.nama', $sortDir);
        } elseif ($sortBy === 'skala_peluang') {
            $query->orderBy('skala_peluang', $sortDir);
        }

        $potensi = $query->get();

        return response()->json($potensi);
    }

    public function geojson(Request $request)
    {
        $query = PotensiInvestasi::with('kelurahan')
            ->whereHas('kelurahan', function ($q) {
                $q->whereNotNull('geom');
            });

        // Apply same filters as index
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ILIKE', '%' . $search . '%')
                  ->orWhere('jenis_potensi', 'ILIKE', '%' . $search . '%')
                  ->orWhere('keterangan', 'ILIKE', '%' . $search . '%')
                  ->orWhereHas('kelurahan', function ($subQ) use ($search) {
                      $subQ->where('nama', 'ILIKE', '%' . $search . '%')
                            ->orWhere('kecamatan', 'ILIKE', '%' . $search . '%');
                  });
            });
        }

        // Filter infrastruktur
        if ($request->has('infrastruktur')) {
            $infrastruktur = $request->input('infrastruktur');
            $query->whereHas('kelurahan', function ($q) use ($infrastruktur) {
                foreach ($infrastruktur as $infra) {
                    $q->where($infra, true);
                }
            });
        }

        // Filter kecamatan
        if ($request->has('kecamatan') && $request->kecamatan) {
            $query->whereHas('kelurahan', function ($q) use ($request) {
                $q->where('kecamatan', $request->input('kecamatan'));
            });
        }

        // Filter jenis_potensi
        if ($request->has('jenis_potensi') && $request->jenis_potensi) {
            $query->where('jenis_potensi', $request->input('jenis_potensi'));
        }

        // Filter skala_peluang
        if ($request->has('skala_peluang') && $request->skala_peluang) {
            $query->where('skala_peluang', $request->input('skala_peluang'));
        }

        // Filter luas wilayah
        if ($request->has('luas_min')) {
            $query->whereHas('kelurahan', function ($q) use ($request) {
                $q->where('luas_wilayah', '>=', $request->input('luas_min'));
            });
        }
        if ($request->has('luas_max')) {
            $query->whereHas('kelurahan', function ($q) use ($request) {
                $q->where('luas_wilayah', '<=', $request->input('luas_max'));
            });
        }

        // Spatial bounds filter
        if ($request->has('north') && $request->has('south') && $request->has('east') && $request->has('west')) {
            $north = $request->input('north');
            $south = $request->input('south');
            $east = $request->input('east');
            $west = $request->input('west');
            $query->whereBetween('latitude', [$south, $north])
                  ->whereBetween('longitude', [$west, $east]);
        }

        $potensi = $query->get();

        $features = $potensi->map(function ($item) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$item->longitude, $item->latitude],
                ],
                'properties' => [
                    'id' => $item->id,
                    'nama' => $item->kelurahan->nama,
                    'jenis_potensi' => $item->jenis_potensi,
                    'skala_peluang' => $item->skala_peluang,
                    'luas_wilayah' => $item->kelurahan->luas_wilayah,
                    'infrastruktur' => [
                        'akses_jalan' => $item->kelurahan->akses_jalan,
                        'listrik' => $item->kelurahan->listrik,
                        'air_bersih' => $item->kelurahan->air_bersih,
                        'internet' => $item->kelurahan->internet,
                    ],
                ],
            ];
        });

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        return response()->json($geojson);
    }
}
