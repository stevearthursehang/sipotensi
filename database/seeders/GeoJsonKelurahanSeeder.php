<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class GeoJsonKelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $geojsonPath = storage_path('app/public/uploads/geojson');
        
        if (!File::isDirectory($geojsonPath)) {
            $this->command->error("GeoJSON directory not found: {$geojsonPath}");
            return;
        }

        $files = File::files($geojsonPath);
        
        if (empty($files)) {
            $this->command->error("No GeoJSON files found in: {$geojsonPath}");
            return;
        }

        $this->command->info("Found " . count($files) . " GeoJSON files to process...");
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($files as $file) {
            try {
                $content = File::get($file->getPathname());
                $geojson = json_decode($content, true);
                
                if (!$geojson || !isset($geojson['properties']) || !isset($geojson['geometry'])) {
                    $this->command->warn("Invalid GeoJSON format in: {$file->getFilename()}");
                    $errorCount++;
                    continue;
                }

                $properties = $geojson['properties'];
                $geometry = $geojson['geometry'];
                
                // Extract kelurahan name from properties
                $nama = $properties['WADMKD'] ?? $properties['NAMOBJ'] ?? pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $kecamatan = $properties['WADMKC'] ?? 'Unknown';
                
                // Generate dummy data for other fields
                $jumlahPenduduk = rand(1000, 50000);
                $tenagaKerjaTersedia = (int)($jumlahPenduduk * (rand(30, 60) / 100));
                
                // Remove Z dimension from coordinates if present
                $geometry = $this->removeZDimension($geometry);
                
                // Convert Polygon to MultiPolygon if needed
                if ($geometry['type'] === 'Polygon') {
                    $geometry = [
                        'type' => 'MultiPolygon',
                        'coordinates' => [$geometry['coordinates']]
                    ];
                }
                
                // Convert geometry to JSON format for PostGIS
                $geometryJson = json_encode($geometry);
                
                // Check if kelurahan already exists
                $existing = Kelurahan::where('nama', $nama)->first();
                
                if ($existing) {
                    // Update existing record
                    DB::statement("
                        UPDATE kelurahans 
                        SET 
                            kecamatan = ?,
                            jumlah_penduduk = ?,
                            tenaga_kerja_tersedia = ?,
                            akses_jalan = ?,
                            listrik = ?,
                            air_bersih = ?,
                            internet = ?,
                            geom = ST_SetSRID(ST_GeomFromGeoJSON(?), 4326),
                            updated_at = NOW()
                        WHERE nama = ?
                    ", [
                        $kecamatan,
                        $jumlahPenduduk,
                        $tenagaKerjaTersedia,
                        rand(0, 1) ? true : false,
                        rand(0, 1) ? true : false,
                        rand(0, 1) ? true : false,
                        rand(0, 1) ? true : false,
                        $geometryJson,
                        $nama
                    ]);
                    
                    $this->command->info("Updated: {$nama}");
                } else {
                    // Insert new record
                    DB::statement("
                        INSERT INTO kelurahans (nama, kecamatan, jumlah_penduduk, tenaga_kerja_tersedia, akses_jalan, listrik, air_bersih, internet, geom, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ST_SetSRID(ST_GeomFromGeoJSON(?), 4326), NOW(), NOW())
                    ", [
                        $nama,
                        $kecamatan,
                        $jumlahPenduduk,
                        $tenagaKerjaTersedia,
                        rand(0, 1) ? true : false,
                        rand(0, 1) ? true : false,
                        rand(0, 1) ? true : false,
                        rand(0, 1) ? true : false,
                        $geometryJson
                    ]);
                    
                    $this->command->info("Created: {$nama}");
                }
                
                $successCount++;
                
            } catch (\Exception $e) {
                $this->command->error("Error processing {$file->getFilename()}: " . $e->getMessage());
                Log::error("GeoJSON Seeder Error: " . $e->getMessage(), [
                    'file' => $file->getFilename(),
                    'trace' => $e->getTraceAsString()
                ]);
                $errorCount++;
            }
        }

        $this->command->newLine();
        $this->command->info("Seeding completed!");
        $this->command->info("Success: {$successCount}");
        $this->command->info("Errors: {$errorCount}");
    }

    /**
     * Remove Z dimension from geometry coordinates
     */
    private function removeZDimension(array $geometry): array
    {
        if (isset($geometry['coordinates'])) {
            $geometry['coordinates'] = $this->removeZFromCoordinates($geometry['coordinates']);
        }
        return $geometry;
    }

    /**
     * Recursively remove Z dimension from coordinates array
     */
    private function removeZFromCoordinates(array $coordinates): array
    {
        // Check if this is a coordinate point (array of numbers)
        if (isset($coordinates[0]) && is_numeric($coordinates[0])) {
            // Return only first two elements (x, y), removing z if present
            return array_slice($coordinates, 0, 2);
        }
        
        // Otherwise, recursively process nested arrays
        return array_map(function ($item) {
            if (is_array($item)) {
                return $this->removeZFromCoordinates($item);
            }
            return $item;
        }, $coordinates);
    }
}
