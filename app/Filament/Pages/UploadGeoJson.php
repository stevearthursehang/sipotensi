<?php

namespace App\Filament\Pages;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use UnitEnum;
use ZipArchive;

class UploadGeoJson extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master GIS';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cloud-arrow-up';

    protected static ?string $navigationLabel = 'Upload GeoJSON';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.upload-geo-json';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $user = Auth::user();
        $isKelurahanAdmin = $user && $user->kelurahan_id && !$user->hasRole('super_admin');

        return $schema
            ->components([
                Section::make('Upload Peta Spasial (GeoJSON / ZIP Folder)')
                    ->description('Mendukung file GeoJSON tunggal (.json, .geojson) ATAU arsip ZIP (.zip) berisi kumpulan file GeoJSON kelurahan/kampung. Otomatis mendeteksi batas polygon wilayah, menghitung centroid koordinat (lat, lng), luas hektar, dan menghubungkan ke data kecamatan.')
                    ->schema([
                        Select::make('target_mode')
                            ->label('Mode Import')
                            ->options([
                                'auto' => 'Otomatis (Import / Update berdasarkan atribut nama & kecamatan dalam file)',
                                'specific' => 'Pilih Kelurahan Tertentu (Perbarui polygon kelurahan tunggal yang dipilih)',
                            ])
                            ->default($isKelurahanAdmin ? 'specific' : 'auto')
                            ->disabled((bool) $isKelurahanAdmin)
                            ->reactive()
                            ->required(),

                        Select::make('kelurahan_id')
                            ->label('Target Kelurahan')
                            ->options(Kelurahan::pluck('nama', 'id'))
                            ->default($isKelurahanAdmin ? $user->kelurahan_id : null)
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => $get('target_mode') === 'specific')
                            ->required(fn ($get) => $get('target_mode') === 'specific'),

                        FileUpload::make('geojson_file')
                            ->label('File GeoJSON atau Arsip ZIP')
                            ->acceptedFileTypes([
                                'application/json',
                                'application/geo+json',
                                'text/plain',
                                'application/zip',
                                'application/x-zip-compressed',
                                'multipart/x-zip',
                            ])
                            ->maxSize(51200) // 50MB
                            ->required()
                            ->directory('geojson-temp')
                            ->visibility('private')
                            ->helperText('Format yang didukung: .json, .geojson, atau .zip yang berisi folder file-file GeoJSON.')
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $formData = $this->form->getState();
        $relativePath = $formData['geojson_file'];
        $filePath = Storage::disk('local')->path($relativePath);

        if (!file_exists($filePath)) {
            Notification::make()
                ->title('Gagal')
                ->body('File yang diunggah tidak ditemukan di server.')
                ->danger()
                ->send();
            return;
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $targetMode = $formData['target_mode'] ?? 'auto';
        $specificKelurahanId = $formData['kelurahan_id'] ?? null;
        $user = Auth::user();

        $processedCount = 0;
        $errorCount = 0;
        $totalFiles = 0;

        if ($extension === 'zip') {
            // Proses file ZIP
            $zip = new ZipArchive();
            if ($zip->open($filePath) === true) {
                $extractDir = Storage::disk('local')->path('geojson-temp/extract_' . uniqid());
                if (!is_dir($extractDir)) {
                    mkdir($extractDir, 0777, true);
                }

                $zip->extractTo($extractDir);
                $zip->close();

                // Cari semua file .json dan .geojson secara rekursif
                $jsonFiles = [];
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($extractDir));
                foreach ($iterator as $item) {
                    if ($item->isFile() && in_array(strtolower($item->getExtension()), ['json', 'geojson'])) {
                        $jsonFiles[] = $item->getPathname();
                    }
                }

                $totalFiles = count($jsonFiles);

                foreach ($jsonFiles as $jFile) {
                    $content = file_get_contents($jFile);
                    $json = json_decode($content, true);
                    if (!$json) {
                        $errorCount++;
                        continue;
                    }

                    $fileName = basename($jFile, '.' . pathinfo($jFile, PATHINFO_EXTENSION));
                    $features = $this->extractFeatures($json);

                    foreach ($features as $feature) {
                        if ($this->processSingleFeature($feature, $targetMode, $specificKelurahanId, $user, $fileName)) {
                            $processedCount++;
                        } else {
                            $errorCount++;
                        }
                    }
                }

                // Bersihkan direktori ekstraksi
                File::deleteDirectory($extractDir);
            } else {
                Notification::make()
                    ->title('Gagal Membuka ZIP')
                    ->body('File arsip ZIP tidak dapat dibuka atau rusak.')
                    ->danger()
                    ->send();
                Storage::disk('local')->delete($relativePath);
                return;
            }
        } else {
            // Proses file tunggal .json / .geojson
            $content = file_get_contents($filePath);
            $json = json_decode($content, true);

            if (!$json) {
                Notification::make()
                    ->title('Format Tidak Valid')
                    ->body('Konten file GeoJSON tidak valid atau korup.')
                    ->danger()
                    ->send();
                Storage::disk('local')->delete($relativePath);
                return;
            }

            $fileName = basename($filePath, '.' . pathinfo($filePath, PATHINFO_EXTENSION));
            $features = $this->extractFeatures($json);

            foreach ($features as $feature) {
                if ($this->processSingleFeature($feature, $targetMode, $specificKelurahanId, $user, $fileName)) {
                    $processedCount++;
                } else {
                    $errorCount++;
                }
            }
            $totalFiles = 1;
        }

        // Hapus file temp upload
        Storage::disk('local')->delete($relativePath);

        if ($processedCount > 0) {
            $msg = $extension === 'zip'
                ? "Berhasil memproses {$processedCount} batas wilayah dari {$totalFiles} file GeoJSON dalam ZIP."
                : "Berhasil memproses {$processedCount} batas wilayah kelurahan.";

            if ($errorCount > 0) {
                $msg .= " ({$errorCount} entri dilewati/tidak valid).";
            }

            Notification::make()
                ->title('Import GeoJSON Berhasil')
                ->body($msg)
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Gagal Memproses')
                ->body('Tidak ada data polygon yang berhasil diproses. Pastikan file berisi geometri Polygon / MultiPolygon yang valid.')
                ->danger()
                ->send();
        }

        $this->form->fill();
    }

    private function extractFeatures(array $geojson): array
    {
        if (isset($geojson['type']) && $geojson['type'] === 'FeatureCollection' && isset($geojson['features'])) {
            return $geojson['features'];
        }

        if (isset($geojson['type']) && $geojson['type'] === 'Feature') {
            return [$geojson];
        }

        return [];
    }

    private function processSingleFeature(array $feature, string $targetMode, ?int $specificKelurahanId, $user, string $fileFallbackName): bool
    {
        if (!isset($feature['geometry']) || !isset($feature['geometry']['type'])) {
            return false;
        }

        $geomType = $feature['geometry']['type'];
        if (!in_array($geomType, ['Polygon', 'MultiPolygon'])) {
            return false;
        }

        $geomJson = json_encode($feature['geometry']);

        try {
            // Hitung Centroid Lat, Lng & Luas Area Ha dengan PostGIS 2D
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
            $areaHa = $stats[0]->area_ha ?? null;

            if ($targetMode === 'specific' && $specificKelurahanId) {
                $kelurahan = Kelurahan::find($specificKelurahanId);
                if (!$kelurahan) return false;

                if ($lat) $kelurahan->latitude = round((float) $lat, 8);
                if ($lng) $kelurahan->longitude = round((float) $lng, 8);
                if ($areaHa) $kelurahan->luas_wilayah = (float) $areaHa;
                $kelurahan->save();

                DB::statement("
                    UPDATE kelurahans 
                    SET geom = ST_Multi(ST_Force2D(ST_CollectionExtract(ST_MakeValid(ST_SetSRID(ST_GeomFromGeoJSON(?), 4326)), 3)))
                    WHERE id = ?
                ", [$geomJson, $kelurahan->id]);

                return true;
            }

            // Mode Otomatis
            $props = $feature['properties'] ?? [];
            $nama = trim($props['nama'] ?? $props['WADMKD'] ?? $props['name'] ?? $props['NAMOBJ'] ?? $fileFallbackName);
            $kecamatanNama = trim($props['kecamatan'] ?? $props['WADMKC'] ?? $props['district'] ?? 'Tahuna');
            $luasWh = !empty($props['LUASWH']) ? (float) $props['LUASWH'] : ($areaHa ? (float) $areaHa : null);

            // Hubungkan atau buat Kecamatan
            $kecamatan = Kecamatan::firstOrCreate(
                ['nama' => $kecamatanNama],
                ['deskripsi' => "Kecamatan {$kecamatanNama}"]
            );

            $kelurahan = Kelurahan::firstOrNew(['nama' => $nama]);
            $kelurahan->kecamatan_id = $kecamatan->id;
            $kelurahan->kecamatan = $kecamatanNama;

            if ($luasWh) {
                $kelurahan->luas_wilayah = round((float) $luasWh, 2);
            }
            if ($lat) {
                $kelurahan->latitude = round((float) $lat, 8);
            }
            if ($lng) {
                $kelurahan->longitude = round((float) $lng, 8);
            }

            if (!$kelurahan->exists) {
                $kelurahan->alamat_kantor = "Kantor Kelurahan/Kampung {$nama}, Kec. {$kecamatanNama}";
                $kelurahan->jumlah_penduduk = (int) ($props['jumlah_penduduk'] ?? rand(600, 2500));
                $kelurahan->tenaga_kerja_tersedia = (int) ($props['tenaga_kerja_tersedia'] ?? rand(250, 1100));
                $kelurahan->akses_jalan = true;
                $kelurahan->listrik = true;
                $kelurahan->air_bersih = true;
                $kelurahan->internet = true;
                $kelurahan->created_by = $user?->id;
            }

            $kelurahan->save();

            // Simpan Geometri PostGIS
            DB::statement("
                UPDATE kelurahans 
                SET geom = ST_Multi(ST_Force2D(ST_CollectionExtract(ST_MakeValid(ST_SetSRID(ST_GeomFromGeoJSON(?), 4326)), 3)))
                WHERE id = ?
            ", [$geomJson, $kelurahan->id]);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
