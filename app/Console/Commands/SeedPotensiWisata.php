<?php

namespace App\Console\Commands;

use App\Models\Kelurahan;
use App\Models\PotensiInvestasi;
use App\Models\SektorInvestasi;
use Illuminate\Console\Command;

class SeedPotensiWisata extends Command
{
    protected $signature = 'seed:potensi-wisata';
    protected $description = 'Seed potensi pariwisata, perikanan, dan potensi unggulan Kepulauan Sangihe';

    public function handle(): int
    {
        $this->info("Menyiapkan Sektor Investasi & Potensi Wisata Unggulan Sangihe...");

        // Ensure sectors exist
        $sektors = [
            ['nama' => 'Pariwisata', 'slug' => 'pariwisata', 'deskripsi' => 'Peluang investasi wisata bahari, ekowisata alam, geowisata gunung api, dan wisata budaya adat Sangihe.'],
            ['nama' => 'Perikanan', 'slug' => 'perikanan', 'deskripsi' => 'Peluang perikanan tangkap laut dalam, budidaya laut, pengolahan tuna, cakalang, dan rumput laut.'],
            ['nama' => 'Pertanian', 'slug' => 'pertanian', 'deskripsi' => 'Pengembangan komoditas perkebunan pala Sangihe, cengkih, kelapa, dan tanaman pangan lokal.'],
            ['nama' => 'Peternakan', 'slug' => 'peternakan', 'deskripsi' => 'Pengembangan peternakan sapi, unggas, dan industri pakan lokal terpadu.'],
            ['nama' => 'Industri', 'slug' => 'industri', 'deskripsi' => 'Industri hilirisasi perikanan, pengolahan rempah-rempah ekspor, dan kerajinan serat abaka.'],
            ['nama' => 'Energi', 'slug' => 'energi', 'deskripsi' => 'Pembangkit listrik tenaga surya (PLTS), mikrohidro, dan energi baru terbarukan di kepulauan.'],
        ];

        foreach ($sektors as $s) {
            SektorInvestasi::firstOrCreate(['slug' => $s['slug']], $s);
        }

        // List of Authentic Sangihe Investment Opportunities (focus on Tourism & Marine)
        $potensiList = [
            [
                'kelurahan_nama' => 'Dalako Bembanehe',
                'fallback_kec' => 'Tatoareng',
                'nama' => 'Wisata Bahari Gunung Api Bawah Laut Mahangetang (Banua Wuhu)',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Diving Gunung Api Bawah Laut & Marine Resort',
                'skala_peluang' => 'besar',
                'nilai_estimasi_investasi' => 5000000000,
                'roi_estimasi' => 18.5,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Destinasi wisata bawah laut langka bertaraf internasional. Fenomena gelembung gas hangat di kedalaman 6 meter dengan terumbu karang tropis yang mempesona. Sangat prospektif untuk resort selam eksklusif dan ekowisata bahari terpadu.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 12.0,
                'jarak_bandara' => 45.0,
            ],
            [
                'kelurahan_nama' => 'Biru',
                'fallback_kec' => 'Tabukan Tengah',
                'nama' => 'Kawasan Wisata Bahari Pantai Pasir Putih Pananualeng',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Resort Tepi Pantai, Glamping & Watersport',
                'skala_peluang' => 'menengah',
                'nilai_estimasi_investasi' => 3500000000,
                'roi_estimasi' => 16.0,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Garis pantai pasir putih landai dengan panorama teluk eksotis dan perairan tenang jernih. Lokasi sangat ideal untuk pengembangan eco-resort tepi pantai, gazebo glamping, restoran kuliner ikan bakar khas pesisir, serta wahana watersport banana boat dan kano.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 18.0,
                'jarak_bandara' => 32.0,
            ],
            [
                'kelurahan_nama' => 'Angges',
                'fallback_kec' => 'Tahuna Barat',
                'nama' => 'Ekowisata Hutan Lindung & Jalur Trekking Gunung Awu',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Geowisata Vulkanik, Birdwatching & Eco-Lodge',
                'skala_peluang' => 'menengah',
                'nilai_estimasi_investasi' => 2500000000,
                'roi_estimasi' => 14.5,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Gunung api aktif tertinggi di Sangihe (1.320 mdpl) dengan kawah aktif dan panorama 360 derajat ke laut lepas. Habitat burung endemik Seriwang Sangihe (Eutrichomyias rowleyi). Potensi mencakup pos pendakian ramah lingkungan, eco-lodge pegunungan, dan jalur wisata edukasi vulkanologi.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 8.0,
                'jarak_bandara' => 25.0,
            ],
            [
                'kelurahan_nama' => 'Bukide',
                'fallback_kec' => 'Nusa Tabukan',
                'nama' => 'Wisata Bahari Gugusan Kepulauan Nusa Tabukan',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Island Hopping, Snorkeling & Homestay Apung',
                'skala_peluang' => 'besar',
                'nilai_estimasi_investasi' => 4200000000,
                'roi_estimasi' => 17.5,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'dalam_proses',
                'deskripsi' => 'Gugusan pulau kecil karang berpasir putih dikelilingi laguna toska air jernih. Titik penyelaman kaya biota laut, penyu, dan lumba-lumba. Peluang investasi mencakup pembangunan villa terapung (water bungalow) dan penyewaan kapal pesiar island hopping.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 15.0,
                'jarak_bandara' => 38.0,
            ],
            [
                'kelurahan_nama' => 'Bebalang',
                'fallback_kec' => 'Manganitu Selatan',
                'nama' => 'Ekowisata Air Terjun Bertingkat Kadadima',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Wisata Alam Air Terjun, Outbound & Flying Fox',
                'skala_peluang' => 'kecil',
                'nilai_estimasi_investasi' => 1800000000,
                'roi_estimasi' => 15.0,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Air terjun alami tiga tingkat dengan debit air melimpah di tengah keasrian hutan tropis Sangihe. Sangat potensial untuk sarana rekreasi keluarga, jalur jembatan gantung kanopi, wahana outbound flying fox, dan kafe rimba alami.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 20.0,
                'jarak_bandara' => 50.0,
            ],
            [
                'kelurahan_nama' => 'Apengsembeka',
                'fallback_kec' => 'Tahuna',
                'nama' => 'Pusat Wisata Budaya Sangihe & Galeri Kreatif Serat Abaka',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Cultural Tourism, Souvenir Hub & Kuliner Sangihe',
                'skala_peluang' => 'menengah',
                'nilai_estimasi_investasi' => 2200000000,
                'roi_estimasi' => 16.5,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Pusat kebudayaan etnis Sangihe (Upacara Adat Tulude, Musik Bambu, Tarian Gunde) dan galeri oleh-oleh olahan cakalang fufu, kue sagu kenari, serta kerajinan anyaman serat pohon pisang abaka berkualitas ekspor.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 3.0,
                'jarak_bandara' => 18.0,
            ],
            [
                'kelurahan_nama' => 'Batunderang',
                'fallback_kec' => 'Manganitu Selatan',
                'nama' => 'Wisata Tebing Karst & Marine Adventure Camp Batunderang',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Sea Kayaking, Rock Climbing & Sunset Viewpoint',
                'skala_peluang' => 'menengah',
                'nilai_estimasi_investasi' => 2800000000,
                'roi_estimasi' => 15.5,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Formasi tebing batu cadas dan gua karang eksotis yang menjorok ke perairan laut selatan. Sangat diminati wisatawan petualang untuk kegiatan kayak laut, panjat tebing pantai, dan spot foto panorama matahari terbenam.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 22.0,
                'jarak_bandara' => 52.0,
            ],
            [
                'kelurahan_nama' => 'Kawio',
                'fallback_kec' => 'Kepulauan Marore',
                'nama' => 'Kawasan Wisata Bahari & Sport Fishing Pulau Perbatasan Marore',
                'jenis_potensi' => 'Pariwisata',
                'komoditas_unggulan' => 'Sport Fishing Laut Dalam & Wisata Bahari Terluar',
                'skala_peluang' => 'menengah',
                'nilai_estimasi_investasi' => 3000000000,
                'roi_estimasi' => 19.0,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Kawasan bahari pulau terluar utara Indonesia yang berbatasan langsung dengan perairan internasional. Merupakan jalur migrasi ikan pelagis besar (Yellowfin Tuna, Black Marlin, Giant Trevally). Sangat potensial untuk armada sport fishing marina dan penginapan nelayan modern.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 2.0,
                'jarak_bandara' => 90.0,
            ],
            [
                'kelurahan_nama' => 'Dagho',
                'fallback_kec' => 'Tamako',
                'nama' => 'Sentra Industri Pengolahan & Cold Storage Ikan Tuna Terpadu',
                'jenis_potensi' => 'Perikanan',
                'komoditas_unggulan' => 'Ikan Tuna Sirip Kuning & Ikan Cakalang',
                'skala_peluang' => 'besar',
                'nilai_estimasi_investasi' => 8500000000,
                'roi_estimasi' => 22.0,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Pelabuhan Perikanan Dagho di Tamako merupakan basis perikanan tangkap terbesar di Sangihe yang berhadapan langsung dengan WPPNRI 716 Laut Sulawesi. Membutuhkan investasi unit pengolahan fillet tuna loin beku ekspor, cold storage 500 ton, dan pabrik es batu modern.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 1.0,
                'jarak_bandara' => 35.0,
            ],
            [
                'kelurahan_nama' => 'Barangka',
                'fallback_kec' => 'Manganitu',
                'nama' => 'Perkebunan Organik & Hilirisasi Ekspor Pala Sangihe (Myristica)',
                'jenis_potensi' => 'Pertanian',
                'komoditas_unggulan' => 'Biji Pala & Fuli Pala Kualitas Ekspor',
                'skala_peluang' => 'besar',
                'nilai_estimasi_investasi' => 6000000000,
                'roi_estimasi' => 20.0,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Pala Sangihe telah memiliki Indikasi Geografis (IG) resmi dengan aroma dan kandungan minyak atsiri terbaik dunia. Peluang meliputi modernisasi pengeringan biji pala, pengolahan fuli minyak atsiri untuk kosmetik/farmasi, serta sirup daging buah pala.',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 14.0,
                'jarak_bandara' => 30.0,
            ],
            [
                'kelurahan_nama' => 'Kawaluso',
                'fallback_kec' => 'Kendahe',
                'nama' => 'Budidaya Rumput Laut (Eucheuma Cottonii) & Sentra Pengeringan',
                'jenis_potensi' => 'Perikanan',
                'komoditas_unggulan' => 'Rumput Laut Kering & Tepung Karaginan',
                'skala_peluang' => 'menengah',
                'nilai_estimasi_investasi' => 2800000000,
                'roi_estimasi' => 17.0,
                'status_lahan' => 'tersedia',
                'status_perizinan' => 'sudah_izin',
                'deskripsi' => 'Kondisi arus laut dan salinitas perairan Kendahe sangat subur untuk perkembangbiakan rumput laut Eucheuma cottonii tanpa pupuk kimia. Peluang investasi berupa gudang pengeringan berstandar ekspor dan industri antara pengolahan tepung karaginan (ATC).',
                'akses_jalan' => true,
                'listrik' => true,
                'air_bersih' => true,
                'internet' => true,
                'jarak_pelabuhan' => 10.0,
                'jarak_bandara' => 22.0,
            ],
        ];

        $inserted = 0;
        foreach ($potensiList as $p) {
            // Find kelurahan by name or fallback kecamatan
            $kelurahan = Kelurahan::where('nama', 'ilike', '%' . $p['kelurahan_nama'] . '%')->first();
            if (!$kelurahan) {
                $kelurahan = Kelurahan::where('kecamatan', 'ilike', '%' . $p['fallback_kec'] . '%')->first();
            }
            if (!$kelurahan) {
                $kelurahan = Kelurahan::first();
            }

            if (!$kelurahan) {
                $this->warn("Belum ada data kelurahan.");
                break;
            }

            $potensi = PotensiInvestasi::firstOrNew(['nama' => $p['nama']]);
            $potensi->kelurahan_id = $kelurahan->id;
            $potensi->jenis_potensi = $p['jenis_potensi'];
            $potensi->komoditas_unggulan = $p['komoditas_unggulan'];
            $potensi->skala_peluang = $p['skala_peluang'];
            $potensi->nilai_estimasi_investasi = $p['nilai_estimasi_investasi'];
            $potensi->roi_estimasi = $p['roi_estimasi'];
            $potensi->status_lahan = $p['status_lahan'];
            $potensi->status_perizinan = $p['status_perizinan'];
            $potensi->deskripsi = $p['deskripsi'];
            $potensi->akses_jalan = $p['akses_jalan'];
            $potensi->listrik = $p['listrik'];
            $potensi->air_bersih = $p['air_bersih'];
            $potensi->internet = $p['internet'];
            $potensi->jarak_pelabuhan = $p['jarak_pelabuhan'];
            $potensi->jarak_bandara = $p['jarak_bandara'];

            // Coordinates: inherit from kelurahan or add slight offset
            $potensi->latitude = $kelurahan->latitude ?: 3.58;
            $potensi->longitude = $kelurahan->longitude ?: 125.58;

            $potensi->save();
            $inserted++;
            $this->line(" [OK] Potensi: {$p['nama']} ({$p['jenis_potensi']}) -> {$kelurahan->nama}, {$kelurahan->kecamatan}");
        }

        $this->info("Berhasil menambahkan {$inserted} titik potensi investasi unggulan (Pariwisata, Perikanan, Pertanian)!");
        return 0;
    }
}

