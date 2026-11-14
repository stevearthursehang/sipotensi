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
        Schema::create('minat_investors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email');
            $table->string('telepon', 20);
            $table->string('perusahaan')->nullable();
            $table->text('pesan')->nullable();
            $table->foreignId('potensi_investasi_id')->constrained('potensi_investasis')->onDelete('cascade');
            $table->foreignId('kelurahan_id')->nullable()->constrained('kelurahans')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minat_investors');
    }
};
