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
            // SPC-specific fields based on SPC.122 - KEBUTUHAN WEBSITE (IT).pdf
            $table->string('alamat_lengkap')->nullable(); // Alamat Lengkap
            $table->string('no_whatsapp_aktif')->nullable(); // Nomor WhatsApp Aktif
            $table->string('asal_perguruan_tinggi')->nullable(); // Asal Perguruan Tinggi
            $table->string('fakultas')->nullable(); // Fakultas
            $table->string('program_studi')->nullable(); // Program Studi
            $table->string('npm')->nullable(); // NPM (Nomor Pokok Mahasiswa)
            
            // Work submission fields - Formulir Pengumpulan Karya
            $table->string('judul_karya')->nullable(); // Judul Karya
            $table->text('deskripsi_karya')->nullable(); // Deskripsi Karya
            $table->string('teknologi_yang_digunakan')->nullable(); // Teknologi Yang Digunakan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_lengkap',
                'no_whatsapp_aktif', 
                'asal_perguruan_tinggi',
                'fakultas',
                'program_studi',
                'npm',
                'judul_karya',
                'deskripsi_karya',
                'teknologi_yang_digunakan'
            ]);
        });
    }
};
