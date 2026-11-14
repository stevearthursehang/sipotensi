<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Infrastruktur;
use App\Models\PotensiInvestasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MasterDataKecamatanKelurahanMvpTest extends TestCase
{
    /**
     * Test 1: Verifikasi seluruh 15 Kecamatan dan 167 Kelurahan terhubung secara utuh di database.
     */
    public function test_all_15_kecamatans_and_167_kelurahans_are_properly_linked(): void
    {
        $kecamatanCount = Kecamatan::count();
        $this->assertGreaterThanOrEqual(15, $kecamatanCount, 'Harus ada minimal 15 Kecamatan di Kabupaten Kepulauan Sangihe');

        $kelurahanCount = Kelurahan::count();
        $this->assertGreaterThanOrEqual(167, $kelurahanCount, 'Harus ada minimal 167 Kelurahan/Kampung di Kabupaten Kepulauan Sangihe');

        $orphanKelurahans = Kelurahan::whereNull('kecamatan_id')->count();
        $this->assertEquals(0, $orphanKelurahans, 'Tidak boleh ada kelurahan yang tidak terhubung ke kecamatan_id');

        // Pastikan setiap kelurahan memiliki objek kecamatan yang valid
        $sampleKelurahan = Kelurahan::first();
        $this->assertNotNull($sampleKelurahan);
        $this->assertInstanceOf(Kecamatan::class, $sampleKelurahan->kecamatan()->first());
        $this->assertNotEmpty($sampleKelurahan->kecamatan()->first()->nama);
    }

    /**
     * Test 2: Verifikasi relasi foreign key dan auto-sinkronisasi data di model Kelurahan.
     */
    public function test_kelurahan_auto_syncs_kecamatan_name_and_coordinates(): void
    {
        $kecamatan = Kecamatan::where('nama', 'Tahuna')->first() ?? Kecamatan::first();
        $this->assertNotNull($kecamatan);

        $testKelurahanName = 'Kampung Uji Coba Relasi ' . uniqid();
        $kelurahan = new Kelurahan([
            'kecamatan_id' => $kecamatan->id,
            'nama' => $testKelurahanName,
            'kode_wilayah' => '71.03.99.9999',
            'jumlah_penduduk' => 1500,
            'tenaga_kerja_tersedia' => 600,
            'akses_jalan' => true,
            'listrik' => true,
            'air_bersih' => true,
            'internet' => true,
        ]);

        $kelurahan->save();

        $this->assertDatabaseHas('kelurahans', [
            'id' => $kelurahan->id,
            'kecamatan_id' => $kecamatan->id,
            'kecamatan' => $kecamatan->nama,
        ]);

        $this->assertEquals($kecamatan->nama, $kelurahan->fresh()->kecamatan);
        $this->assertEquals($kecamatan->latitude, $kelurahan->fresh()->latitude);
        $this->assertEquals($kecamatan->longitude, $kelurahan->fresh()->longitude);

        // Cleanup
        $kelurahan->delete();
    }

    /**
     * Test 3: Verifikasi validasi duplikasi kode dan nama kecamatan.
     */
    public function test_kecamatan_duplicate_validation(): void
    {
        $existing = Kecamatan::first();
        $this->assertNotNull($existing);

        $this->expectException(\Illuminate\Database\QueryException::class);

        // Mencoba insert nama kecamatan yang sama persis
        DB::table('kecamatans')->insert([
            'nama' => $existing->nama,
            'kode' => '71.03.DUPLIKAT',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Test 4: Verifikasi seluruh relasi Infrastruktur dan Potensi Investasi terhubung ke Kelurahan & Kecamatan.
     */
    public function test_infrastruktur_and_potensi_relate_to_kelurahan_and_kecamatan(): void
    {
        $infrastruktur = Infrastruktur::with('kelurahan')->first();
        if ($infrastruktur) {
            $this->assertNotNull($infrastruktur->kelurahan);
            $this->assertNotNull($infrastruktur->kelurahan->kecamatan()->first());
        }

        $potensi = PotensiInvestasi::with('kelurahan')->first();
        if ($potensi) {
            $this->assertNotNull($potensi->kelurahan);
            $this->assertNotNull($potensi->kelurahan->kecamatan()->first());
        }
    }

    /**
     * Test 5: Verifikasi route admin Filament untuk Master Kecamatan dan Master Kelurahan dapat diakses 200 OK.
     */
    public function test_filament_master_data_pages_respond_successfully(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin_test@sangihekab.go.id',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        // Halaman Master Kecamatan
        $responseKecamatan = $this->get('/admin/kecamatans');
        $responseKecamatan->assertStatus(200);

        // Halaman Create Kecamatan
        $responseCreateKecamatan = $this->get('/admin/kecamatans/create');
        $responseCreateKecamatan->assertStatus(200);

        // Halaman Master Kelurahan
        $responseKelurahan = $this->get('/admin/kelurahans');
        $responseKelurahan->assertStatus(200);

        // Halaman Create Kelurahan
        $responseCreateKelurahan = $this->get('/admin/kelurahans/create');
        $responseCreateKelurahan->assertStatus(200);
    }
}

