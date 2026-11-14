<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelurahan;
use App\Models\PotensiInvestasi;
use App\Models\MinatInvestor;
use App\Models\Infrastruktur;
use App\Models\SektorInvestasi;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Sektor Investasi
        $sektors = [
            ['nama' => 'Pertanian', 'slug' => 'pertanian', 'deskripsi' => 'Sektor pertanian dan perkebunan'],
            ['nama' => 'Perikanan', 'slug' => 'perikanan', 'deskripsi' => 'Sektor perikanan dan aquaculture'],
            ['nama' => 'Peternakan', 'slug' => 'peternakan', 'deskripsi' => 'Sektor peternakan dan livestock'],
            ['nama' => 'Industri', 'slug' => 'industri', 'deskripsi' => 'Sektor industri manufaktur'],
            ['nama' => 'Pariwisata', 'slug' => 'pariwisata', 'deskripsi' => 'Sektor pariwisata dan hospitality'],
        ];

        foreach ($sektors as $sektor) {
            SektorInvestasi::firstOrCreate(
                ['slug' => $sektor['slug']],
                $sektor
            );
        }

        // Get all existing kelurahan and create dummy data for them
        $kelurahans = Kelurahan::all();

        foreach ($kelurahans as $kelurahan) {
            // Create PotensiInvestasi for each kelurahan
            $sektor = SektorInvestasi::inRandomOrder()->first();
            $potensi = PotensiInvestasi::firstOrCreate(
                ['kelurahan_id' => $kelurahan->id],
                [
                    'nama' => 'Potensi Investasi ' . $kelurahan->nama,
                    'alamat' => 'Jl. Utama ' . $kelurahan->nama,
                    'latitude' => rand(3, 4) + (rand(0, 999) / 1000),
                    'longitude' => rand(125, 126) + (rand(0, 999) / 1000),
                    'jenis_potensi' => $sektor->nama,
                    'komoditas_unggulan' => 'Jagung, Padi',
                    'skala_peluang' => ['kecil', 'menengah', 'besar'][rand(0, 2)],
                    'nilai_estimasi_investasi' => rand(100000000, 1000000000),
                    'roi_estimasi' => rand(10, 30),
                    'status_lahan' => 'tersedia',
                    'status_perizinan' => 'sudah_izin',
                    'deskripsi' => 'Potensi investasi yang menjanjikan di ' . $kelurahan->nama,
                    'foto_utama' => 'potensi-investasi/foto-utama/sample.jpg',
                    'galeri_foto' => ['potensi-investasi/foto/sample1.jpg', 'potensi-investasi/foto/sample2.jpg'],
                ]
            );

            // Create MinatInvestor
            MinatInvestor::firstOrCreate(
                ['email' => 'investor' . $kelurahan->id . '@example.com'],
                [
                    'nama' => 'Investor ' . $kelurahan->nama,
                    'telepon' => '08123456789' . $kelurahan->id,
                    'perusahaan' => 'PT ' . $kelurahan->nama . ' Invest',
                    'pesan' => 'Tertarik dengan potensi investasi di ' . $kelurahan->nama,
                    'potensi_investasi_id' => $potensi->id,
                    'kelurahan_id' => $kelurahan->id,
                ]
            );

            // Create Infrastruktur
            Infrastruktur::firstOrCreate(
                ['kelurahan_id' => $kelurahan->id, 'jenis' => 'Jalan'],
                [
                    'nama' => $kelurahan->nama,
                    'deskripsi' => 'Jalan utama di ' . $kelurahan->nama,
                    'latitude' => rand(3, 4) + (rand(0, 999) / 1000),
                    'longitude' => rand(125, 126) + (rand(0, 999) / 1000),
                ]
            );
        }
    }
}
