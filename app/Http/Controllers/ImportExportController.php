<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\PotensiInvestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use OpenSpout\Writer\XLSX\Options as XlsxOptions;

class ImportExportController extends Controller
{
    public function exportKelurahans()
    {
        $kelurahans = Kelurahan::with('potensiInvestasis')->orderBy('nama', 'asc')->get();

        $headers = [
            'No',
            'Nama Kelurahan / Kampung',
            'Kecamatan',
            'Total Peluang Investasi',
            'Total Nilai Investasi (Rp)',
            'Skala Prioritas Tertinggi',
            'Akses Jalan',
            'Jaringan Listrik',
            'Air Bersih',
            'Internet & 4G'
        ];

        $columnWidths = [
            1 => 6,
            2 => 26,
            3 => 20,
            4 => 18,
            5 => 26,
            6 => 18,
            7 => 14,
            8 => 14,
            9 => 14,
            10 => 14,
        ];

        $rows = [];
        foreach ($kelurahans as $i => $kelurahan) {
            $totalNilai = $kelurahan->potensiInvestasis->sum(function ($p) {
                return $p->nilai_estimasi_investasi ?? $p->nilai_estimasi ?? 0;
            });

            $rows[] = [
                $i + 1,
                $kelurahan->nama,
                $kelurahan->kecamatan ?? '-',
                $kelurahan->potensiInvestasis->count() . ' Peluang',
                'Rp ' . number_format($totalNilai, 0, ',', '.'),
                ucfirst($this->getHighestScale($kelurahan->potensiInvestasis) ?? '-'),
                $kelurahan->akses_jalan ? 'Tersedia' : 'Belum Ada',
                $kelurahan->listrik ? 'Tersedia' : 'Belum Ada',
                $kelurahan->air_bersih ? 'Tersedia' : 'Belum Ada',
                $kelurahan->internet ? 'Tersedia' : 'Belum Ada',
            ];
        }

        $filename = 'Data_Wilayah_Kelurahan_Sangihe_' . date('Ymd');

        return $this->exportToXlsx($headers, $rows, $filename . '.xlsx', $columnWidths);
    }

    public function exportPotensiInvestasis(Request $request)
    {
        $query = PotensiInvestasi::with('kelurahan')->orderBy('id', 'asc');

        // Apply filters if provided
        if ($request->filled('jenis_potensi')) {
            $query->where('jenis_potensi', $request->jenis_potensi);
        }

        if ($request->filled('skala_peluang')) {
            $query->where('skala_peluang', $request->skala_peluang);
        }

        if ($request->filled('status_lahan')) {
            $query->where('status_lahan', $request->status_lahan);
        }

        if ($request->filled('kecamatan')) {
            $query->whereHas('kelurahan', function ($q) use ($request) {
                $q->where('kecamatan', $request->kecamatan);
            });
        }

        $potensiInvestasis = $query->get();

        $headers = [
            'No',
            'Nama Peluang Investasi',
            'Sektor Investasi',
            'Komoditas Unggulan',
            'Skala Peluang',
            'Estimasi Nilai Investasi (Rp)',
            'Estimasi ROI / Thn',
            'Status Kesiapan Lahan',
            'Kesiapan Regulasi & Izin',
            'Kampung / Kelurahan',
            'Kecamatan',
            'Akses Jalan',
            'Jaringan Listrik',
            'Air Bersih',
            'Internet & 4G',
            'Jarak ke Pelabuhan',
            'Jarak ke Bandara',
            'Ringkasan Deskripsi Potensi',
            'Tanggal Pembaruan Data'
        ];

        $columnWidths = [
            1 => 6,    // No
            2 => 34,   // Nama Peluang
            3 => 22,   // Sektor
            4 => 24,   // Komoditas
            5 => 14,   // Skala
            6 => 25,   // Nilai Investasi
            7 => 14,   // ROI
            8 => 22,   // Status Lahan
            9 => 26,   // Status Perizinan
            10 => 20,  // Kelurahan
            11 => 18,  // Kecamatan
            12 => 14,  // Akses Jalan
            13 => 14,  // Listrik
            14 => 14,  // Air Bersih
            15 => 15,  // Internet 4G
            16 => 16,  // Jarak Pelabuhan
            17 => 16,  // Jarak Bandara
            18 => 50,  // Deskripsi
            19 => 16,  // Tanggal
        ];

        $lahanMap = [
            'tersedia' => 'Tersedia Siap Pakai',
            'dalam_proses' => 'Dalam Pembebasan / Proses',
            'tidak_tersedia' => 'Belum Tersedia',
            'milik_masyarakat' => 'Lahan Masyarakat / Kerjasama'
        ];

        $izinMap = [
            'sudah_izin' => 'Izin Lengkap (Clean & Clear)',
            'sudah' => 'Izin Lengkap (Clean & Clear)',
            'proses' => 'Dalam Proses Fasilitasi',
            'dalam_proses' => 'Dalam Proses Fasilitasi',
            'belum' => 'Belum Diajukan',
            'belum_izin' => 'Belum Diajukan'
        ];

        $rows = [];
        foreach ($potensiInvestasis as $i => $potensi) {
            // Clean description: strip HTML tags and normalize whitespace (removes rogue newlines that break CSV/Excel rows)
            $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags($potensi->deskripsi ?? '')));

            $nilai = $potensi->nilai_estimasi_investasi ?? $potensi->nilai_estimasi ?? 0;
            $nilaiFormatted = 'Rp ' . number_format($nilai, 0, ',', '.');
            $roiFormatted = $potensi->roi_estimasi ? number_format($potensi->roi_estimasi, 1, ',', '.') . '%' : '-';

            $statusLahan = $lahanMap[$potensi->status_lahan] ?? ucwords(str_replace('_', ' ', $potensi->status_lahan ?? '-'));
            $statusIzin = $izinMap[$potensi->status_perizinan] ?? ucwords(str_replace('_', ' ', $potensi->status_perizinan ?? '-'));

            $jalan = ($potensi->akses_jalan || ($potensi->kelurahan && $potensi->kelurahan->akses_jalan)) ? 'Tersedia' : 'Belum Ada';
            $listrik = ($potensi->listrik || ($potensi->kelurahan && $potensi->kelurahan->listrik)) ? 'Tersedia' : 'Belum Ada';
            $air = ($potensi->air_bersih || ($potensi->kelurahan && $potensi->kelurahan->air_bersih)) ? 'Tersedia' : 'Belum Ada';
            $net = ($potensi->internet || ($potensi->kelurahan && $potensi->kelurahan->internet)) ? 'Tersedia' : 'Belum Ada';

            $pelabuhan = $potensi->jarak_pelabuhan ? number_format($potensi->jarak_pelabuhan, 1, ',', '.') . ' Km' : '-';
            $bandara = $potensi->jarak_bandara ? number_format($potensi->jarak_bandara, 1, ',', '.') . ' Km' : '-';

            $rows[] = [
                $i + 1,
                $potensi->nama,
                $potensi->jenis_potensi ?? '-',
                $potensi->komoditas_unggulan ?? '-',
                ucfirst($potensi->skala_peluang ?? '-'),
                $nilaiFormatted,
                $roiFormatted,
                $statusLahan,
                $statusIzin,
                $potensi->kelurahan->nama ?? '-',
                $potensi->kelurahan->kecamatan ?? '-',
                $jalan,
                $listrik,
                $air,
                $net,
                $pelabuhan,
                $bandara,
                $cleanDesc,
                $potensi->created_at ? $potensi->created_at->format('d/m/Y') : '-'
            ];
        }

        $filename = 'Direktori_Potensi_Investasi_Sangihe_' . date('Ymd');
        if ($request->filled('jenis_potensi')) {
            $filename .= '_' . str_replace(' ', '_', $request->jenis_potensi);
        }

        if ($request->get('format') === 'csv') {
            return $this->arrayToFormattedCsv($headers, $rows, $filename . '.csv');
        }

        return $this->exportToXlsx($headers, $rows, $filename . '.xlsx', $columnWidths);
    }

    private function exportToXlsx(array $headers, array $rows, string $filename, array $columnWidths = [])
    {
        $options = new XlsxOptions();

        $tempDir = storage_path('framework/cache');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }
        if (@is_writable($tempDir)) {
            $options->setTempFolder($tempDir);
        }

        foreach ($columnWidths as $colIndex => $width) {
            $options->setColumnWidth((float)$width, (int)$colIndex);
        }

        $writer = new XlsxWriter($options);
        $tempFilePath = storage_path('framework/cache/' . uniqid('export_', true) . '.xlsx');

        try {
            $writer->openToFile($tempFilePath);

            $headerStyle = (new Style())
                ->setFontBold()
                ->setFontSize(11)
                ->setFontColor(Color::WHITE)
                ->setBackgroundColor('0F172A')
                ->setCellAlignment(CellAlignment::CENTER);

            $dataStyle = (new Style())
                ->setFontSize(10);

            $writer->addRow(Row::fromValues($headers, $headerStyle));

            foreach ($rows as $row) {
                $writer->addRow(Row::fromValues($row, $dataStyle));
            }

            $writer->close();

            return response()->download($tempFilePath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            if (isset($writer)) {
                try { $writer->close(); } catch (\Throwable $ignored) {}
            }
            if (file_exists($tempFilePath)) {
                @unlink($tempFilePath);
            }

            // Fallback to formatted CSV if XLSX encounters environment restriction
            return $this->arrayToFormattedCsv($headers, $rows, str_replace('.xlsx', '.csv', $filename));
        }
    }

    private function arrayToFormattedCsv(array $headers, array $rows, string $filename)
    {
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM so Excel opens with proper encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $headers, ',');

            foreach ($rows as $row) {
                $sanitizedRow = array_map(function ($val) {
                    if (is_string($val)) {
                        return trim(preg_replace('/\s+/', ' ', $val));
                    }
                    return $val;
                }, $row);
                fputcsv($file, $sanitizedRow, ',');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function importPotensiInvestasis(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('temp');

        try {
            $data = $this->csvToArray(Storage::path($path));
            $results = $this->processImportData($data);

            Storage::delete($path);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diimpor',
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Storage::delete($path);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function exportTemplate()
    {
        $template = [
            [
                'nama' => 'Contoh Potensi Investasi',
                'deskripsi' => 'Deskripsi potensi investasi',
                'jenis_potensi' => 'Pertanian & Perkebunan',
                'komoditas_unggulan' => 'Padi, Jagung',
                'skala_peluang' => 'besar',
                'nilai_estimasi' => 1000000000,
                'roi_estimasi' => 25.5,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah',
                'akses_jalan' => 'baik',
                'listrik' => 'ada',
                'air_bersih' => 'ada',
                'internet' => 'ada',
                'jarak_pelabuhan' => 50,
                'jarak_bandara' => 30,
                'kelurahan_nama' => 'Nama Kelurahan',
            ]
        ];

        return $this->arrayToCsv($template, 'template_import_potensi_investasi.csv');
    }

    private function getHighestScale($potensiInvestasis)
    {
        $scales = ['kecil', 'menengah', 'besar'];
        $highestScale = null;

        foreach ($potensiInvestasis as $potensi) {
            if ($potensi->skala_peluang) {
                $currentIndex = array_search($potensi->skala_peluang, $scales);
                $highestIndex = $highestScale ? array_search($highestScale, $scales) : -1;

                if ($currentIndex > $highestIndex) {
                    $highestScale = $potensi->skala_peluang;
                }
            }
        }

        return $highestScale;
    }

    private function arrayToCsv($data, $filename)
    {
        if (empty($data)) {
            return response('No data to export', 400);
        }

        $headers = array_keys($data[0]);

        $callback = function () use ($data, $headers) {
            $file = fopen('php://output', 'w');

            // Write BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write headers
            fputcsv($file, $headers, ';');

            // Write data
            foreach ($data as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function csvToArray($filename, $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception('File tidak dapat dibaca');
        }

        $header = null;
        $data = [];

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    private function processImportData($data)
    {
        $results = [
            'imported' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        DB::beginTransaction();

        try {
            foreach ($data as $index => $row) {
                try {
                    // Validate required fields
                    $validator = Validator::make($row, [
                        'nama' => 'required|string|max:255',
                        'jenis_potensi' => 'required|string',
                        'kelurahan_nama' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        $results['errors'][] = "Baris " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                        $results['skipped']++;
                        continue;
                    }

                    // Find or create kelurahan
                    $kelurahan = Kelurahan::firstOrCreate(
                        ['nama' => trim($row['kelurahan_nama'])],
                        ['nama' => trim($row['kelurahan_nama'])]
                    );

                    // Create potensi investasi
                    PotensiInvestasi::create([
                        'nama' => trim($row['nama']),
                        'deskripsi' => trim($row['deskripsi'] ?? ''),
                        'jenis_potensi' => trim($row['jenis_potensi']),
                        'komoditas_unggulan' => trim($row['komoditas_unggulan'] ?? ''),
                        'skala_peluang' => trim($row['skala_peluang'] ?? 'kecil'),
                        'nilai_estimasi' => is_numeric($row['nilai_estimasi']) ? (float)$row['nilai_estimasi'] : 0,
                        'roi_estimasi' => is_numeric($row['roi_estimasi']) ? (float)$row['roi_estimasi'] : null,
                        'status_lahan' => trim($row['status_lahan'] ?? 'tidak_tersedia'),
                        'status_perizinan' => trim($row['status_perizinan'] ?? 'belum'),
                        'akses_jalan' => trim($row['akses_jalan'] ?? 'kurang'),
                        'listrik' => trim($row['listrik'] ?? 'tidak_ada'),
                        'air_bersih' => trim($row['air_bersih'] ?? 'tidak_ada'),
                        'internet' => trim($row['internet'] ?? 'tidak_ada'),
                        'jarak_pelabuhan' => is_numeric($row['jarak_pelabuhan']) ? (float)$row['jarak_pelabuhan'] : null,
                        'jarak_bandara' => is_numeric($row['jarak_bandara']) ? (float)$row['jarak_bandara'] : null,
                        'kelurahan_id' => $kelurahan->id,
                    ]);

                    $results['imported']++;

                } catch (\Exception $e) {
                    $results['errors'][] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                    $results['skipped']++;
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $results;
    }
}
