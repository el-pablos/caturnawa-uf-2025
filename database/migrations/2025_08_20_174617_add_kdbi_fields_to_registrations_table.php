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
        Schema::table('registrations', function (Blueprint $table) {
            // KDBI-specific fields based on REVISI 3 FORM REGISTRASI KDBI UNAS FEST 2025.pdf
            $table->string('fakultas_prodi')->nullable(); // Fakultas/Prodi (Indonesian version)
            $table->string('asal_instansi')->nullable(); // Asal Instansi
            $table->string('alamat_lengkap_kdbi')->nullable(); // Alamat Lengkap (separate from SPC)
            $table->string('no_whatsapp_kdbi')->nullable(); // No WhatsApp (separate from SPC)
            $table->string('nama_kelompok')->nullable(); // Nama kelompok wajib berkaitan dengan tema UNAS FEST 2025
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'fakultas_prodi',
                'asal_instansi',
                'alamat_lengkap_kdbi',
                'no_whatsapp_kdbi',
                'nama_kelompok'
            ]);
        });
    }
};
