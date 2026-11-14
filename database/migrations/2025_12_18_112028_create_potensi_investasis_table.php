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
        Schema::create('potensi_investasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelurahan_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('jenis_potensi');
            $table->string('komoditas_unggulan');
            $table->enum('skala_peluang', ['kecil', 'menengah', 'besar']);
            $table->decimal('nilai_estimasi_investasi', 15, 2);
            $table->decimal('roi_estimasi', 5, 2)->nullable();
            $table->enum('status_lahan', ['tersedia', 'tidak_tersedia', 'dalam_proses']);
            $table->enum('status_perizinan', ['sudah_izin', 'belum_izin', 'dalam_proses']);
            $table->text('deskripsi')->nullable();
            $table->json('analisis_swot')->nullable();
            $table->string('foto_utama')->nullable();
            $table->json('galeri_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potensi_investasis');
    }
};
