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
        Schema::table('submissions', function (Blueprint $table) {
            // Tambah kolom status
            $table->enum('status', ['draft', 'submitted', 'overdue', 'scored'])->default('draft')->after('is_final');
            
            // Hapus kolom competition_id karena sudah ada relasi melalui registration
            $table->dropForeign(['competition_id']);
            $table->dropColumn('competition_id');
            
            // Hapus kolom file_count karena bisa dihitung dari files array
            $table->dropColumn('file_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->integer('file_count')->default(0);
            
            // Hapus kolom status
            $table->dropColumn('status');
        });
    }
};
