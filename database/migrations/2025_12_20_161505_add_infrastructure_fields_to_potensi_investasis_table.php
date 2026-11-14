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
            $table->boolean('akses_jalan')->default(false)->after('status_perizinan');
            $table->boolean('listrik')->default(false)->after('akses_jalan');
            $table->boolean('air_bersih')->default(false)->after('listrik');
            $table->boolean('internet')->default(false)->after('air_bersih');
            $table->decimal('jarak_pelabuhan', 8, 2)->nullable()->after('internet');
            $table->decimal('jarak_bandara', 8, 2)->nullable()->after('jarak_pelabuhan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potensi_investasis', function (Blueprint $table) {
            $table->dropColumn(['akses_jalan', 'listrik', 'air_bersih', 'internet', 'jarak_pelabuhan', 'jarak_bandara']);
        });
    }
};
