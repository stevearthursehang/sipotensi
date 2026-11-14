<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeoJsonImportController extends Controller
{
    public function import(Request $request)
    {
        ini_set('max_execution_time', 300); // Increase timeout for large imports

        $request->validate([
            'geojson_file' => 'required|file|mimes:json,txt|max:15360',
        ]);

        $file = $request->file('geojson_file');
        $content = file_get_contents($file->getRealPath());
        $geojson = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'message' => 'File GeoJSON tidak valid: ' . json_last_error_msg(),
            ];
        }

        if (!isset($geojson['type']) || !in_array($geojson['type'], ['FeatureCollection', 'Feature'])) {
            return [
                'success' => false,
                'message' => 'File harus berupa FeatureCollection atau Feature GeoJSON.',
            ];
        }

        $features = $geojson['type'] === 'FeatureCollection' ? ($geojson['features'] ?? []) : [$geojson];

        if (empty($features)) {
            return [
                'success' => false,
                'message' => 'Tidak ada features dalam GeoJSON.',
            ];
        }

        $imported = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($features as $feature) {
                $result = $this->processFeature($feature);
                if ($result['success']) {
                    $imported++;
                } else {
                    $errors[] = $result['message'];
                }
            }

            DB::commit();

            $message = "Berhasil import {$imported} kelurahan.";
            if (!empty($errors)) {
                $message .= " Peringatan: " . implode('; ', array_slice($errors, 0, 3));
            }

            return [
                'success' => true,
                'message' => $message,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeoJSON Import Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage(),
            ];
        }
    }

    public function updateKelurahan(Request $request)
    {
        $request->validate([
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'geojson_file' => 'required|file|mimes:json,txt|max:15360',
        ]);

        $kelurahanId = $request->kelurahan_id;
        $file = $request->file('geojson_file');
        $content = file_get_contents($file->getRealPath());
        $geojson = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'message' => 'File GeoJSON tidak valid: ' . json_last_error_msg(),
            ];
        }

        $features = $geojson['type'] === 'FeatureCollection' ? ($geojson['features'] ?? []) : [$geojson];

        if (empty($features)) {
            return [
                'success' => false,
                'message' => 'GeoJSON tidak memiliki feature geometry.',
            ];
        }

        $feature = $features[0];

        if (!isset($feature['geometry']) || !isset($feature['geometry']['type'])) {
            return [
                'success' => false,
                'message' => 'Feature tidak memiliki geometry yang valid.',
            ];
        }

        $geometry = $feature['geometry'];

        if (!in_array($geometry['type'], ['Polygon', 'MultiPolygon'])) {
            return [
                'success' => false,
                'message' => 'Geometry harus berupa Polygon atau MultiPolygon.',
            ];
        }

        $geomJson = json_encode($geometry);
        $geomSql = "ST_Multi(ST_SetSRID(ST_GeomFromGeoJSON('{$geomJson}'), 4326))";

        $isValid = DB::select("SELECT ST_IsValid({$geomSql}) as is_valid")[0]->is_valid ?? false;
        if (!$isValid) {
            return [
                'success' => false,
                'message' => 'Geometry tidak valid. Periksa koordinat dan struktur polygon.',
            ];
        }

        $centroid = DB::select("SELECT ST_Y(ST_Centroid({$geomSql})) as lat, ST_X(ST_Centroid({$geomSql})) as lng")[0] ?? null;
        $area = DB::select("SELECT ST_Area(ST_Transform({$geomSql}, 3857)) / 10000 as area_ha")[0] ?? null;

        $kelurahan = Kelurahan::find($kelurahanId);
        $updateData = ['geom' => DB::raw($geomSql)];

        if ($centroid && $centroid->lat) {
            $updateData['latitude'] = round((float) $centroid->lat, 8);
            $updateData['longitude'] = round((float) $centroid->lng, 8);
        }
        if ($area && $area->area_ha) {
            $updateData['luas_wilayah'] = round((float) $area->area_ha, 2);
        }

        $kelurahan->update($updateData);

        return [
            'success' => true,
            'message' => "Berhasil update geometry kelurahan {$kelurahan->nama}",
        ];
    }

    private function processFeature(array $feature): array
    {
        if (!isset($feature['geometry']) || !isset($feature['geometry']['type'])) {
            return [
                'success' => false,
                'message' => 'Feature tidak memiliki geometry.',
            ];
        }

        $geometry = $feature['geometry'];
        if (!in_array($geometry['type'], ['Polygon', 'MultiPolygon'])) {
            return [
                'success' => false,
                'message' => 'Tipe geometry harus Polygon atau MultiPolygon.',
            ];
        }

        $properties = $feature['properties'] ?? [];

        $nama = $properties['nama'] ?? $properties['WADMKD'] ?? $properties['name'] ?? $properties['NAMOBJ'] ?? null;
        $kecamatan = $properties['kecamatan'] ?? $properties['WADMKC'] ?? $properties['district'] ?? 'Wilayah Kepulauan';
        $luas_wilayah = $properties['luas_wilayah'] ?? $properties['LUASWH'] ?? $properties['area'] ?? null;
        $jumlah_penduduk = $properties['jumlah_penduduk'] ?? $properties['population'] ?? 0;
        $tenaga_kerja_tersedia = $properties['tenaga_kerja_tersedia'] ?? $properties['workforce'] ?? 0;

        if (!$nama) {
            return [
                'success' => false,
                'message' => 'Nama kelurahan tidak ditemukan dalam properties.',
            ];
        }

        $geomJson = json_encode($geometry);
        $geomSql = "ST_Multi(ST_SetSRID(ST_GeomFromGeoJSON('{$geomJson}'), 4326))";

        $centroid = DB::select("SELECT ST_Y(ST_Centroid({$geomSql})) as lat, ST_X(ST_Centroid({$geomSql})) as lng")[0] ?? null;
        $latitude = $properties['latitude'] ?? ($centroid ? round((float) $centroid->lat, 8) : null);
        $longitude = $properties['longitude'] ?? ($centroid ? round((float) $centroid->lng, 8) : null);

        if (!$luas_wilayah) {
            $areaRow = DB::select("SELECT ST_Area(ST_Transform({$geomSql}, 3857)) / 10000 as area_ha")[0] ?? null;
            if ($areaRow && $areaRow->area_ha) {
                $luas_wilayah = round((float) $areaRow->area_ha, 2);
            }
        }

        Kelurahan::updateOrCreate(
            ['nama' => trim($nama)],
            [
                'kecamatan' => trim($kecamatan),
                'luas_wilayah' => $luas_wilayah,
                'jumlah_penduduk' => (int) $jumlah_penduduk,
                'tenaga_kerja_tersedia' => (int) $tenaga_kerja_tersedia,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'geom' => DB::raw($geomSql),
            ]
        );

        return [
            'success' => true,
            'message' => "Berhasil import {$nama}",
        ];
    }
}
