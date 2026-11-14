<?php

namespace App\Console\Commands;

use App\Models\Kelurahan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSangiheKelurahans extends Command
{
    protected $signature = 'import:sangihe-kelurahans {--path= : Optional custom path to geojson directory}';
    protected $description = 'Import semua kelurahan dan kampung se-Kabupaten Kepulauan Sangihe dari file GeoJSON';

    public function handle(): int
    {
        $dir = $this->option('path') ?: storage_path('app/public/uploads/geojson');
        if (!is_dir($dir)) {
            $this->error("Directory not found: {$dir}");
            return 1;
        }

        $files = glob("{$dir}/*.json");
        $this->info("Ditemukan " . count($files) . " file GeoJSON kelurahan/desa.");

        $success = 0;
        $failed = 0;

        foreach ($files as $index => $file) {
            $fileName = basename($file);
            $content = file_get_contents($file);
            $json = json_decode($content, true);

            if (!$json) {
                $this->warn("Skipped {$fileName}: Invalid JSON.");
                $failed++;
                continue;
            }

            // Extract feature
            $feature = null;
            if (isset($json['type']) && $json['type'] === 'Feature') {
                $feature = $json;
            } elseif (isset($json['features'][0])) {
                $feature = $json['features'][0];
            }

            if (!$feature || !isset($feature['geometry'])) {
                $this->warn("Skipped {$fileName}: No geometry found.");
                $failed++;
                continue;
            }

            $props = $feature['properties'] ?? [];
            $nama = trim($props['NAMOBJ'] ?? $props['WADMKD'] ?? basename($file, '.json'));
            $kecamatan = trim($props['WADMKC'] ?? 'Tahuna');
            $luasWh = !empty($props['LUASWH']) ? (float) $props['LUASWH'] : null;

            $geomJson = json_encode($feature['geometry']);

            try {
                // Calculate Centroid & Luas with PostGIS
                $stats = DB::select("
                    WITH geom_sub AS (
                        SELECT ST_Multi(ST_Force2D(ST_CollectionExtract(ST_MakeValid(ST_SetSRID(ST_GeomFromGeoJSON(?), 4326)), 3))) AS g
                    )
                    SELECT 
                        ST_Y(ST_Centroid(g)) AS lat,
                        ST_X(ST_Centroid(g)) AS lng,
                        ROUND((ST_Area(ST_Transform(g, 3857)) / 10000)::numeric, 2) AS area_ha
                    FROM geom_sub
                ", [$geomJson]);

                $lat = $stats[0]->lat ?? null;
                $lng = $stats[0]->lng ?? null;
                $calcArea = $stats[0]->area_ha ?? null;

                $finalLuas = $luasWh ?: ($calcArea ?: 100.0);

                // Upsert Kelurahan
                $kelurahan = Kelurahan::firstOrNew(['nama' => $nama]);

                $kelurahan->kecamatan = $kecamatan;
                $kelurahan->luas_wilayah = round((float) $finalLuas, 2);
                $kelurahan->latitude = $lat ? round((float) $lat, 8) : null;
                $kelurahan->longitude = $lng ? round((float) $lng, 8) : null;

                if (!$kelurahan->exists) {
                    $kelurahan->alamat_kantor = "Kantor Kelurahan/Kampung {$nama}, Kec. {$kecamatan}";
                    $kelurahan->jumlah_penduduk = rand(600, 2800);
                    $kelurahan->tenaga_kerja_tersedia = rand(250, 1200);
                    $kelurahan->akses_jalan = true;
                    $kelurahan->listrik = true;
                    $kelurahan->air_bersih = true;
                    $kelurahan->internet = true;
                }

                // Save model without geom first to get ID
                $kelurahan->save();

                // Update geometry directly with ST_Force2D
                DB::statement("
                    UPDATE kelurahans 
                    SET geom = ST_Multi(ST_Force2D(ST_CollectionExtract(ST_MakeValid(ST_SetSRID(ST_GeomFromGeoJSON(?), 4326)), 3)))
                    WHERE id = ?
                ", [$geomJson, $kelurahan->id]);

                $success++;
                $this->line(" [OK] ({$success}/" . count($files) . ") {$nama} (Kec. {$kecamatan})");
            } catch (\Throwable $e) {
                $this->error(" [ERR] {$nama}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->info("\n=== SELESAI ===");
        $this->info("Berhasil diimpor/diperbarui: {$success} kelurahan/kampung.");
        if ($failed > 0) {
            $this->warn("Gagal: {$failed} file.");
        }

        return 0;
    }
}
