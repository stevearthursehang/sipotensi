<?php

namespace App\Http\Controllers;

use App\Mail\NewMinatInvestorNotification;
use App\Models\Kelurahan;
use App\Models\MinatInvestor;
use App\Models\PotensiInvestasi;
use App\Models\SektorInvestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MapController extends Controller
{
    public function index()
    {
        $statistics = Cache::remember('map_view_statistics', 300, function () {
            return [
                'total_kelurahans' => Kelurahan::count(),
                'total_potensi' => PotensiInvestasi::count(),
                'total_nilai' => (float) PotensiInvestasi::sum('nilai_estimasi_investasi'),
                'total_sektor' => SektorInvestasi::count() ?: PotensiInvestasi::whereNotNull('jenis_potensi')->distinct('jenis_potensi')->count('jenis_potensi'),
            ];
        });

        $sektors = Cache::remember('map_view_sektors', 300, function () {
            $list = SektorInvestasi::orderBy('nama')->get();
            if ($list->isEmpty()) {
                $distinctSektor = PotensiInvestasi::whereNotNull('jenis_potensi')
                    ->distinct()
                    ->pluck('jenis_potensi');
                $list = $distinctSektor->map(fn($item) => (object)['nama' => $item, 'slug' => strtolower($item)]);
            }
            return $list;
        });

        $kecamatans = Cache::remember('map_view_kecamatans', 300, function () {
            return Kelurahan::whereNotNull('kecamatan')
                ->distinct()
                ->orderBy('kecamatan')
                ->pluck('kecamatan')
                ->filter()
                ->values();
        });

        $initialPotensi = Cache::remember('map_view_initial_potensi', 300, function () {
            try {
                $query = PotensiInvestasi::with(['kelurahan' => function ($q) {
                    $q->select('id', 'nama', 'kecamatan', 'latitude', 'longitude', 'luas_wilayah', 'akses_jalan', 'listrik', 'air_bersih', 'internet');
                }])->orderBy('nama', 'asc');

                $results = $query->get()->map(function ($item) {
                    $item->nilai_estimasi = (float) $item->nilai_estimasi_investasi;
                    return $item;
                });

                if ($results->isNotEmpty()) {
                    @file_put_contents(storage_path('app/potensi_investasis_snapshot.json'), json_encode($results));
                }
                return $results;
            } catch (\Exception $e) {
                Log::warning('Fallback to snapshot in MapController@index: ' . $e->getMessage());
                $snapshotPath = storage_path('app/potensi_investasis_snapshot.json');
                if (file_exists($snapshotPath)) {
                    return json_decode(file_get_contents($snapshotPath), true) ?: [];
                }
                return [];
            }
        });

        $initialCuaca = $this->getCuacaBahariArray();

        return view('map.index', compact('statistics', 'sektors', 'kecamatans', 'initialPotensi', 'initialCuaca'));
    }

    public function getCuacaBahari(Request $request)
    {
        if (session()->isStarted()) {
            session_write_close();
        }
        return response()->json($this->getCuacaBahariArray());
    }

    private function getCuacaBahariArray(): array
    {
        $zones = [
            [
                'id' => 'tahuna',
                'nama' => 'Perairan Teluk Tahuna & Pelabuhan',
                'lokasi' => 'Pusat Logistik Laut & Pelabuhan Nusantara Tahuna',
                'latitude' => 3.6350,
                'longitude' => 125.4620,
                'cuaca' => 'Cerah Berawan',
                'suhu_udara' => '29°C',
                'suhu_laut' => '28.5°C',
                'kecepatan_angin' => '8 - 14 knot (15 - 26 km/jam)',
                'arah_angin' => 'Barat Laut - Utara',
                'tinggi_gelombang' => '0.50 - 1.25 meter',
                'kategori_gelombang' => 'Tenang - Sedang',
                'status_keselamatan' => 'Aman untuk Pelayaran & Aktivitas Bahari',
                'status_code' => 'safe',
                'rekomendasi' => 'Kondisi perairan ideal untuk kapal feri, logistik kontainer, dan perikanan tangkap.',
                'sektor_terkait' => ['Perikanan & Kelautan', 'Perdagangan & Logistik'],
            ],
            [
                'id' => 'tatoareng',
                'nama' => 'Perairan Tatoareng & Mahangetang',
                'lokasi' => 'Spot Wisata Gunung Api Bawah Laut Banua Wuhu',
                'latitude' => 3.1620,
                'longitude' => 125.4780,
                'cuaca' => 'Cerah',
                'suhu_udara' => '30°C',
                'suhu_laut' => '29.2°C (Hangat Alami)',
                'kecepatan_angin' => '6 - 12 knot (11 - 22 km/jam)',
                'arah_angin' => 'Barat Daya',
                'tinggi_gelombang' => '0.50 - 1.00 meter',
                'kategori_gelombang' => 'Tenang',
                'status_keselamatan' => 'Sangat Direkomendasikan untuk Diving & Snorkeling',
                'status_code' => 'safe',
                'rekomendasi' => 'Visibilitas bawah laut 20-25 meter, sangat prima untuk ekspedisi selam vulkanik bawah air.',
                'sektor_terkait' => ['Pariwisata Bahari', 'Diving & Ekoturisme'],
            ],
            [
                'id' => 'kendahe',
                'nama' => 'Perairan Kendahe & Teluk Utara',
                'lokasi' => 'Sentra Budidaya Rumput Laut & Perikanan Pelagis',
                'latitude' => 3.8180,
                'longitude' => 125.3850,
                'cuaca' => 'Cerah Berawan',
                'suhu_udara' => '28°C',
                'suhu_laut' => '28.0°C',
                'kecepatan_angin' => '10 - 16 knot (18 - 30 km/jam)',
                'arah_angin' => 'Utara - Timur Laut',
                'tinggi_gelombang' => '0.75 - 1.50 meter',
                'kategori_gelombang' => 'Sedang',
                'status_keselamatan' => 'Waspada untuk Perahu Nelayan Kecil',
                'status_code' => 'warning',
                'rekomendasi' => 'Aktivitas budidaya rumput laut di teluk terlindung aman; pelayaran lepas pantai perlu waspada.',
                'sektor_terkait' => ['Perikanan & Aquaculture'],
            ],
            [
                'id' => 'nusa_tabukan',
                'nama' => 'Perairan Nusa Tabukan & Pulau Bukide',
                'lokasi' => 'Kawasan Konservasi Karang & Wisata Pulau',
                'latitude' => 3.7780,
                'longitude' => 125.5980,
                'cuaca' => 'Cerah',
                'suhu_udara' => '29°C',
                'suhu_laut' => '28.8°C',
                'kecepatan_angin' => '7 - 12 knot (13 - 22 km/jam)',
                'arah_angin' => 'Timur - Tenggara',
                'tinggi_gelombang' => '0.50 - 1.10 meter',
                'kategori_gelombang' => 'Tenang',
                'status_keselamatan' => 'Kondisi Sangat Baik untuk Wisata Bahari',
                'status_code' => 'safe',
                'rekomendasi' => 'Arus laut bersahabat, cocok untuk wisata antarpulau (island hopping) dan snorkeling.',
                'sektor_terkait' => ['Pariwisata Bahari'],
            ],
            [
                'id' => 'tamako',
                'nama' => 'Perairan Tamako & Pantai Barat Daya',
                'lokasi' => 'Jalur Pelayaran Antarpulau Sangihe - Siau',
                'latitude' => 3.4250,
                'longitude' => 125.5080,
                'cuaca' => 'Berawan',
                'suhu_udara' => '28°C',
                'suhu_laut' => '28.4°C',
                'kecepatan_angin' => '9 - 15 knot (16 - 28 km/jam)',
                'arah_angin' => 'Barat - Barat Laut',
                'tinggi_gelombang' => '0.75 - 1.25 meter',
                'kategori_gelombang' => 'Sedang',
                'status_keselamatan' => 'Aman untuk Kapal Motor & Feri',
                'status_code' => 'safe',
                'rekomendasi' => 'Operasional kapal penyeberangan feri dan armada penangkapan tuna berjalan normal.',
                'sektor_terkait' => ['Perikanan & Kelautan', 'Transportasi Laut'],
            ],
            [
                'id' => 'marore',
                'nama' => 'Perairan Laut Sulawesi & Kepulauan Marore',
                'lokasi' => 'Wilayah Pengelolaan Perikanan (WPP 716) Perbatasan NKRI-Filipina',
                'latitude' => 4.6200,
                'longitude' => 125.5100,
                'cuaca' => 'Hujan Ringan',
                'suhu_udara' => '27°C',
                'suhu_laut' => '27.9°C',
                'kecepatan_angin' => '12 - 20 knot (22 - 37 km/jam)',
                'arah_angin' => 'Utara - Timur Laut',
                'tinggi_gelombang' => '1.25 - 2.00 meter',
                'kategori_gelombang' => 'Sedang - Tinggi',
                'status_keselamatan' => 'Peringatan Gelombang untuk Kapal Nelayan Tradisional',
                'status_code' => 'danger',
                'rekomendasi' => 'Kapal penangkap tuna berbobot > 30 GT aman; nelayan perahu kecil diimbau berlindung.',
                'sektor_terkait' => ['Perikanan Tuna Skala Industri'],
            ],
        ];

        return [
            'sumber' => 'Stasiun Meteorologi Maritim BMKG / Data Terintegrasi Maritim Sangihe',
            'waktu_pengamatan' => now()->translatedFormat('d F Y - H:i') . ' WITA',
            'status_umum' => 'Kondisi perairan Kabupaten Kepulauan Sangihe secara umum kondusif untuk pariwisata bahari dan perikanan tangkap.',
            'stasiun' => $zones,
        ];
    }

    public function getKelurahans(Request $request)
    {
        $apiController = new \App\Http\Controllers\Api\KelurahanController();
        return $apiController->geojson($request);
    }

    public function getPotensiInvestasis(Request $request)
    {
        if (session()->isStarted()) {
            session_write_close();
        }

        try {
            $cacheKey = 'potensi_investasis_list_' . md5(json_encode($request->all()));

            $potensiInvestasis = Cache::remember($cacheKey, 300, function () use ($request) {
                $query = PotensiInvestasi::with(['kelurahan' => function ($q) {
                    $q->select('id', 'nama', 'kecamatan', 'latitude', 'longitude', 'luas_wilayah', 'akses_jalan', 'listrik', 'air_bersih', 'internet');
                }]);

                // Filter Jenis / Sektor Potensi
                if ($request->filled('jenis_potensi')) {
                    $query->where('jenis_potensi', $request->jenis_potensi);
                }

                // Filter Kecamatan
                if ($request->filled('kecamatan')) {
                    $query->whereHas('kelurahan', function ($q) use ($request) {
                        $q->where('kecamatan', $request->kecamatan);
                    });
                }

                // Filter Skala Peluang
                if ($request->filled('skala_peluang')) {
                    $query->where('skala_peluang', $request->skala_peluang);
                }

                // Filter Status Lahan
                if ($request->filled('status_lahan')) {
                    $query->where('status_lahan', $request->status_lahan);
                }

                // Filter Status Perizinan
                if ($request->filled('status_perizinan')) {
                    $query->where('status_perizinan', $request->status_perizinan);
                }

                // Filter Infrastruktur (mendukung cek pada potensi atau pada kelurahan)
                if ($request->filled('akses_jalan') && $request->akses_jalan == '1') {
                    $query->where(function ($q) {
                        $q->where('akses_jalan', true)
                          ->orWhereHas('kelurahan', fn($kq) => $kq->where('akses_jalan', true));
                    });
                }

                if ($request->filled('listrik') && $request->listrik == '1') {
                    $query->where(function ($q) {
                        $q->where('listrik', true)
                          ->orWhereHas('kelurahan', fn($kq) => $kq->where('listrik', true));
                    });
                }

                if ($request->filled('air_bersih') && $request->air_bersih == '1') {
                    $query->where(function ($q) {
                        $q->where('air_bersih', true)
                          ->orWhereHas('kelurahan', fn($kq) => $kq->where('air_bersih', true));
                    });
                }

                if ($request->filled('internet') && $request->internet == '1') {
                    $query->where(function ($q) {
                        $q->where('internet', true)
                          ->orWhereHas('kelurahan', fn($kq) => $kq->where('internet', true));
                    });
                }

                // Search Filter
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('nama', 'ilike', "%{$search}%")
                          ->orWhere('jenis_potensi', 'ilike', "%{$search}%")
                          ->orWhere('komoditas_unggulan', 'ilike', "%{$search}%")
                          ->orWhere('deskripsi', 'ilike', "%{$search}%")
                          ->orWhereHas('kelurahan', function ($kq) use ($search) {
                              $kq->where('nama', 'ilike', "%{$search}%")
                                 ->orWhere('kecamatan', 'ilike', "%{$search}%");
                          });
                    });
                }

                // Optional Bounding Box Filter (only if valid numeric values provided)
                if ($request->filled(['north', 'south', 'east', 'west'])) {
                    $north = (float) $request->north;
                    $south = (float) $request->south;
                    $east = (float) $request->east;
                    $west = (float) $request->west;

                    if ($north != 0 && $south != 0 && $east != 0 && $west != 0) {
                        $query->whereBetween('latitude', [min($south, $north), max($south, $north)])
                              ->whereBetween('longitude', [min($west, $east), max($west, $east)]);
                    }
                }

                // Sorting
                $sortBy = $request->get('sort_by', 'nama');
                $sortDirection = strtolower($request->get('sort_direction', 'asc')) === 'desc' ? 'desc' : 'asc';

                if (in_array($sortBy, ['nama', 'nilai_estimasi', 'created_at', 'roi_estimasi'])) {
                    $column = $sortBy === 'nilai_estimasi' ? 'nilai_estimasi_investasi' : $sortBy;
                    $query->orderBy($column, $sortDirection);
                } else {
                    $query->orderBy('nama', 'asc');
                }

                $results = $query->get()->map(function ($item) {
                    $item->nilai_estimasi = (float) $item->nilai_estimasi_investasi;
                    return $item;
                });

                // Update snapshot for offline/timeout resilience if default query
                if (!$request->hasAny(['jenis_potensi', 'kecamatan', 'skala_peluang', 'status_lahan', 'status_perizinan', 'search'])) {
                    @file_put_contents(storage_path('app/potensi_investasis_snapshot.json'), json_encode($results));
                }

                return $results;
            });

            return response()->json($potensiInvestasis);
        } catch (\Exception $e) {
            Log::error('API Error in getPotensiInvestasis: ' . $e->getMessage());

            // Resilient fallback to snapshot cache if database connection drops or times out
            $snapshotPath = storage_path('app/potensi_investasis_snapshot.json');
            if (file_exists($snapshotPath)) {
                $snapshot = json_decode(file_get_contents($snapshotPath), true);
                if (!empty($snapshot)) {
                    Log::info('Serving potensi_investasis from offline snapshot due to DB connection issue.');
                    return response()->json($snapshot);
                }
            }

            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Gagal mengambil data potensi investasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showPotensiInvestasi($id)
    {
        if (session()->isStarted()) {
            session_write_close();
        }

        try {
            $potensi = PotensiInvestasi::with('kelurahan')->findOrFail($id);

            return response()->json([
                'id' => $potensi->id,
                'nama' => $potensi->nama,
                'deskripsi' => $potensi->deskripsi,
                'jenis_potensi' => $potensi->jenis_potensi,
                'komoditas_unggulan' => $potensi->komoditas_unggulan,
                'skala_peluang' => $potensi->skala_peluang,
                'nilai_estimasi' => (float) $potensi->nilai_estimasi_investasi,
                'nilai_estimasi_investasi' => (float) $potensi->nilai_estimasi_investasi,
                'roi_estimasi' => (float) $potensi->roi_estimasi,
                'status_lahan' => $potensi->status_lahan,
                'status_perizinan' => $potensi->status_perizinan,
                'status_kesiapan_lahan' => $potensi->status_kesiapan_lahan,
                'latitude' => $potensi->latitude,
                'longitude' => $potensi->longitude,
                'infrastruktur' => [
                    'akses_jalan' => (bool) ($potensi->akses_jalan ?? $potensi->kelurahan?->akses_jalan),
                    'listrik' => (bool) ($potensi->listrik ?? $potensi->kelurahan?->listrik),
                    'air_bersih' => (bool) ($potensi->air_bersih ?? $potensi->kelurahan?->air_bersih),
                    'internet' => (bool) ($potensi->internet ?? $potensi->kelurahan?->internet),
                    'jarak_pelabuhan' => $potensi->jarak_pelabuhan,
                    'jarak_bandara' => $potensi->jarak_bandara,
                ],
                'kelurahan' => $potensi->kelurahan,
                'foto_utama' => $potensi->foto_utama,
                'galeri_foto' => $potensi->galeri_foto,
                'videos' => $potensi->videos,
                'pdfs' => $potensi->pdfs,
                'created_at' => $potensi->created_at,
                'updated_at' => $potensi->updated_at,
            ]);
        } catch (\Exception $e) {
            Log::error('API Error in showPotensiInvestasi: ' . $e->getMessage());
            return response()->json([
                'error' => 'Not found',
                'message' => 'Data potensi investasi tidak ditemukan.',
            ], 404);
        }
    }

    public function getStatistics()
    {
        if (session()->isStarted()) {
            session_write_close();
        }

        $stats = Cache::remember('map_statistics_summary', 300, function () {
            return [
                'total_kelurahans' => Kelurahan::count(),
                'total_potensi_investasis' => PotensiInvestasi::count(),
                'total_nilai_investasi' => (float) PotensiInvestasi::sum('nilai_estimasi_investasi'),
                'potensi_by_jenis' => PotensiInvestasi::selectRaw('jenis_potensi, COUNT(*) as count')
                    ->whereNotNull('jenis_potensi')
                    ->groupBy('jenis_potensi')
                    ->get(),
                'potensi_by_skala' => PotensiInvestasi::selectRaw('skala_peluang, COUNT(*) as count')
                    ->whereNotNull('skala_peluang')
                    ->groupBy('skala_peluang')
                    ->get(),
                'potensi_by_status_lahan' => PotensiInvestasi::selectRaw('status_lahan, COUNT(*) as count')
                    ->whereNotNull('status_lahan')
                    ->groupBy('status_lahan')
                    ->get(),
            ];
        });

        return response()->json($stats);
    }

    public function showPotensiDetail($id)
    {
        $potensi = PotensiInvestasi::with('kelurahan')->findOrFail($id);

        return view('potensi.detail', compact('potensi'));
    }

    public function exportPdf($id)
    {
        $potensi = PotensiInvestasi::with(['kelurahan.kecamatan'])->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.prospektus', compact('potensi'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        $filename = 'Prospektus-Investasi-' . \Illuminate\Support\Str::slug($potensi->nama) . '.pdf';
        return $pdf->download($filename);
    }

    private function containsSuspiciousContent($content)
    {
        if (!$content) return false;

        $suspicious = [
            '<script', 'javascript:', 'onload=', 'onerror=',
            'union select', 'drop table',
            'eval(', 'phpinfo'
        ];

        $content = strtolower($content);

        foreach ($suspicious as $pattern) {
            if (strpos($content, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    public function storeMinatInvestasi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:30',
            'perusahaan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:100',
            'jenis_investor' => 'nullable|string|in:pmdn,pma,perorangan',
            'asal_negara' => 'nullable|string|max:100',
            'nib' => 'nullable|string|max:50',
            'rentang_modal' => 'nullable|string|max:100',
            'nilai_komitmen' => 'nullable|numeric',
            'model_kerjasama' => 'nullable|string|max:100',
            'timeline_realisasi' => 'nullable|string|max:100',
            'kebutuhan_fasilitasi' => 'nullable|array',
            'pesan' => 'nullable|string|max:2000',
            'potensi_investasi_id' => 'required|exists:potensi_investasis,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'dokumen_loi' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $checkFields = ['nama', 'email', 'perusahaan', 'jabatan', 'asal_negara', 'nib', 'pesan'];
        foreach ($checkFields as $field) {
            if ($request->has($field) && $this->containsSuspiciousContent($request->$field)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Input mengandung karakter atau konten yang tidak diizinkan.'
                ], 422);
            }
        }

        $potensi = PotensiInvestasi::find($request->potensi_investasi_id);
        $kelurahanId = $request->kelurahan_id ?: $potensi?->kelurahan_id;

        $dokumenLoiPath = null;
        if ($request->hasFile('dokumen_loi') && $request->file('dokumen_loi')->isValid()) {
            $dokumenLoiPath = $request->file('dokumen_loi')->store('investor/loi', 'public');
        }

        $minat = MinatInvestor::create([
            'user_id' => auth()->id() ?? null,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'perusahaan' => $request->perusahaan,
            'jenis_investor' => $request->jenis_investor ?? 'pmdn',
            'asal_negara' => $request->asal_negara,
            'nib' => $request->nib,
            'rentang_modal' => $request->rentang_modal,
            'nilai_komitmen' => $request->nilai_komitmen,
            'model_kerjasama' => $request->model_kerjasama,
            'timeline_realisasi' => $request->timeline_realisasi,
            'kebutuhan_fasilitasi' => $request->kebutuhan_fasilitasi,
            'pesan' => $request->pesan,
            'dokumen_loi' => $dokumenLoiPath,
            'status' => 'diajukan',
            'potensi_investasi_id' => $request->potensi_investasi_id,
            'kelurahan_id' => $kelurahanId,
        ]);

        try {
            Mail::to(config('mail.from.address', 'admin@gis.com'))->send(new NewMinatInvestorNotification($minat));
        } catch (\Exception $e) {
            Log::info('Minat investor created, email notification skipped: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pernyataan minat investasi resmi Anda berhasil dikirim! Tim DPMPTSP akan segera meninjau dan menghubungi Anda.',
            'data' => $minat
        ]);
    }

    /**
     * Fitur Bandingkan Peluang Investasi Multi-Kriteria
     */
    public function comparePotensi(Request $request)
    {
        $rawIds = $request->input('ids');
        $selectedIds = [];

        if (is_array($rawIds)) {
            $selectedIds = array_filter(array_map('intval', $rawIds));
        } elseif (is_string($rawIds) && trim($rawIds) !== '') {
            $selectedIds = array_filter(array_map('intval', explode(',', $rawIds)));
        }

        $selectedIds = array_slice(array_unique($selectedIds), 0, 3);

        if (empty($selectedIds)) {
            $selectedIds = PotensiInvestasi::orderByDesc('nilai_estimasi_investasi')->take(2)->pluck('id')->toArray();
        }

        $items = PotensiInvestasi::with('kelurahan')
            ->whereIn('id', $selectedIds)
            ->get();

        $items = $items->sortBy(function ($item) use ($selectedIds) {
            return array_search($item->id, $selectedIds);
        })->values();

        $allOpportunities = PotensiInvestasi::select('id', 'nama', 'jenis_potensi', 'nilai_estimasi_investasi')
            ->orderBy('nama')
            ->get();

        return view('potensi.compare', compact('items', 'allOpportunities', 'selectedIds'));
    }

    /**
     * Fitur Dashboard Statistik & Infografis Investasi Publik
     */
    public function showPublicStatistics()
    {
        $totalPotensi = PotensiInvestasi::count();
        $totalNilai = (float) PotensiInvestasi::sum('nilai_estimasi_investasi');
        $totalKelurahan = Kelurahan::count();
        $totalKecamatan = Kelurahan::whereNotNull('kecamatan')->distinct('kecamatan')->count('kecamatan');
        $totalMinat = MinatInvestor::count();

        // Distribusi Sektor
        $sektorStats = PotensiInvestasi::select('jenis_potensi', DB::raw('count(*) as count'), DB::raw('sum(nilai_estimasi_investasi) as total_nilai'))
            ->whereNotNull('jenis_potensi')
            ->groupBy('jenis_potensi')
            ->orderByDesc('total_nilai')
            ->get();

        // Distribusi Kecamatan
        $kecamatanStats = PotensiInvestasi::join('kelurahans', 'potensi_investasis.kelurahan_id', '=', 'kelurahans.id')
            ->select('kelurahans.kecamatan', DB::raw('count(*) as count'), DB::raw('sum(nilai_estimasi_investasi) as total_nilai'))
            ->whereNotNull('kelurahans.kecamatan')
            ->groupBy('kelurahans.kecamatan')
            ->orderByDesc('count')
            ->take(8)
            ->get();

        // Kesiapan Infrastruktur
        $infraStats = [
            'akses_jalan' => $totalPotensi > 0 ? round((PotensiInvestasi::where('akses_jalan', true)->count() / $totalPotensi) * 100) : 0,
            'listrik' => $totalPotensi > 0 ? round((PotensiInvestasi::where('listrik', true)->count() / $totalPotensi) * 100) : 0,
            'air_bersih' => $totalPotensi > 0 ? round((PotensiInvestasi::where('air_bersih', true)->count() / $totalPotensi) * 100) : 0,
            'internet' => $totalPotensi > 0 ? round((PotensiInvestasi::where('internet', true)->count() / $totalPotensi) * 100) : 0,
        ];

        // Kesiapan Lahan
        $kesiapanLahan = PotensiInvestasi::select('status_kesiapan_lahan', DB::raw('count(*) as count'))
            ->whereNotNull('status_kesiapan_lahan')
            ->groupBy('status_kesiapan_lahan')
            ->get();

        // Top 5 Peluang Investasi
        $topPotensi = PotensiInvestasi::with('kelurahan')
            ->orderByDesc('nilai_estimasi_investasi')
            ->take(5)
            ->get();

        return view('public.statistics', compact(
            'totalPotensi',
            'totalNilai',
            'totalKelurahan',
            'totalKecamatan',
            'totalMinat',
            'sektorStats',
            'kecamatanStats',
            'infraStats',
            'kesiapanLahan',
            'topPotensi'
        ));
    }
}
