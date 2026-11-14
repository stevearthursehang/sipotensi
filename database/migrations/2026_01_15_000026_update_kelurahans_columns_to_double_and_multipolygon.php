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
            $table->double('latitude')->nullable()->after('tenaga_kerja_tersedia');
            $table->double('longitude')->nullable()->after('latitude');
            $table->double('luas_wilayah')->nullable()->after('longitude'); // dalam hektar
        });

        // Ubah geom menjadi MultiPolygon
        DB::statement('ALTER TABLE kelurahans ALTER COLUMN geom TYPE GEOMETRY(MultiPolygon, 4326)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'luas_wilayah']);
        });

        // Kembali ke Polygon
        DB::statement('ALTER TABLE kelurahans ALTER COLUMN geom TYPE GEOMETRY(Polygon, 4326)');
    }
};
