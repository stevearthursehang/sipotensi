<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kecamatan');
            $table->decimal('luas_wilayah', 10, 2); // dalam km²
            $table->integer('jumlah_penduduk');
            $table->integer('tenaga_kerja_tersedia');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->text('geom')->nullable(); // GeoJSON geometry as text
            $table->json('infrastruktur')->nullable(); // listrik, air, internet, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahans');
    }
};
