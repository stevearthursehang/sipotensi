<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropColumn(['latitude', 'longitude', 'luas_wilayah']);

            // Tambah kolom baru
            $table->string('alamat_kantor')->nullable()->after('kecamatan');
            $table->boolean('akses_jalan')->default(false)->after('tenaga_kerja_tersedia');
            $table->boolean('listrik')->default(false)->after('akses_jalan');
            $table->boolean('air_bersih')->default(false)->after('listrik');
            $table->boolean('internet')->default(false)->after('air_bersih');
            $table->unsignedBigInteger('created_by')->nullable()->after('internet');

            // Foreign key
            $table->foreign('created_by')->references('id')->on('users');

            // Ubah geom menjadi geometry PostGIS
            $table->dropColumn('geom');
        });

        // Tambah kolom geom sebagai geometry menggunakan raw SQL
        DB::statement('ALTER TABLE kelurahans ADD COLUMN geom GEOMETRY(Polygon, 4326)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['created_by']);

            // Drop kolom baru
            $table->dropColumn(['alamat_kantor', 'akses_jalan', 'listrik', 'air_bersih', 'internet', 'created_by']);

            // Tambah kembali kolom lama
            $table->decimal('latitude', 10, 8)->after('tenaga_kerja_tersedia');
            $table->decimal('longitude', 11, 8)->after('latitude');
            $table->decimal('luas_wilayah', 10, 2)->after('nama');

            // Drop geom geometry dan tambah kembali sebagai text
            DB::statement('ALTER TABLE kelurahans DROP COLUMN geom');
            $table->text('geom')->nullable()->after('longitude');
        });
    }
};
