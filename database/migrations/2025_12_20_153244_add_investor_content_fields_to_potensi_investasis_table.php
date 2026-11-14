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
        Schema::table('potensi_investasis', function (Blueprint $table) {
            $table->json('videos')->nullable()->after('galeri_foto');
            $table->json('pdfs')->nullable()->after('videos');
            $table->enum('status_kesiapan_lahan', ['siap', 'dalam_persiapan', 'belum_siap'])->nullable()->after('pdfs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potensi_investasis', function (Blueprint $table) {
            $table->dropColumn(['videos', 'pdfs', 'status_kesiapan_lahan']);
        });
    }
};
