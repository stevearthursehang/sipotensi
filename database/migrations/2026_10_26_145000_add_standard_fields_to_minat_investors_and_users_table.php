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
        // 1. Tambah field kontak & instansi pada users jika belum ada
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'telepon')) {
                $table->string('telepon', 30)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'perusahaan')) {
                $table->string('perusahaan', 255)->nullable()->after('telepon');
            }
            if (!Schema::hasColumn('users', 'jabatan')) {
                $table->string('jabatan', 100)->nullable()->after('perusahaan');
            }
        });

        // 2. Tambah field standar BKPM/DPMPTSP pada minat_investors
        Schema::table('minat_investors', function (Blueprint $table) {
            if (!Schema::hasColumn('minat_investors', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('minat_investors', 'jabatan')) {
                $table->string('jabatan', 100)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('minat_investors', 'jenis_investor')) {
                $table->string('jenis_investor', 50)->default('pmdn')->after('perusahaan'); // pmdn, pma, perorangan
            }
            if (!Schema::hasColumn('minat_investors', 'asal_negara')) {
                $table->string('asal_negara', 100)->nullable()->after('jenis_investor');
            }
            if (!Schema::hasColumn('minat_investors', 'nib')) {
                $table->string('nib', 50)->nullable()->after('asal_negara');
            }
            if (!Schema::hasColumn('minat_investors', 'rentang_modal')) {
                $table->string('rentang_modal', 100)->nullable()->after('nib');
            }
            if (!Schema::hasColumn('minat_investors', 'nilai_komitmen')) {
                $table->decimal('nilai_komitmen', 15, 2)->nullable()->after('rentang_modal');
            }
            if (!Schema::hasColumn('minat_investors', 'model_kerjasama')) {
                $table->string('model_kerjasama', 50)->nullable()->after('nilai_komitmen'); // kpbu, sewa_lahan, joint_venture, mandiri_100, lainnya
            }
            if (!Schema::hasColumn('minat_investors', 'timeline_realisasi')) {
                $table->string('timeline_realisasi', 50)->nullable()->after('model_kerjasama'); // segera, 6_12_bulan, studi_kelayakan
            }
            if (!Schema::hasColumn('minat_investors', 'kebutuhan_fasilitasi')) {
                $table->json('kebutuhan_fasilitasi')->nullable()->after('timeline_realisasi');
            }
            if (!Schema::hasColumn('minat_investors', 'dokumen_loi')) {
                $table->string('dokumen_loi', 255)->nullable()->after('pesan');
            }
            if (!Schema::hasColumn('minat_investors', 'status')) {
                $table->string('status', 50)->default('diajukan')->after('dokumen_loi'); // diajukan, sedang_ditinjau, jadwal_audiensi, disetujui, ditolak
            }
            if (!Schema::hasColumn('minat_investors', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('status');
            }
            if (!Schema::hasColumn('minat_investors', 'tanggal_tindak_lanjut')) {
                $table->timestamp('tanggal_tindak_lanjut')->nullable()->after('catatan_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('minat_investors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'jabatan',
                'jenis_investor',
                'asal_negara',
                'nib',
                'rentang_modal',
                'nilai_komitmen',
                'model_kerjasama',
                'timeline_realisasi',
                'kebutuhan_fasilitasi',
                'dokumen_loi',
                'status',
                'catatan_admin',
                'tanggal_tindak_lanjut',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telepon', 'perusahaan', 'jabatan']);
        });
    }
};

