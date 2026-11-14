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
        if (!Schema::hasTable('kecamatans')) {
            Schema::create('kecamatans', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->string('kode')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->decimal('luas_total', 12, 2)->nullable();
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('kelurahans', 'kecamatan_id')) {
            Schema::table('kelurahans', function (Blueprint $table) {
                $table->foreignId('kecamatan_id')->nullable()->after('id')->constrained('kecamatans')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('kelurahans', 'kecamatan_id')) {
            Schema::table('kelurahans', function (Blueprint $table) {
                $table->dropForeign(['kecamatan_id']);
                $table->dropColumn('kecamatan_id');
            });
        }

        Schema::dropIfExists('kecamatans');
    }
};
