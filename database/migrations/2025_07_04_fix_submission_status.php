<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix existing submission status based on is_final field
        DB::statement("
            UPDATE submissions 
            SET status = CASE 
                WHEN is_final = 1 AND submitted_at IS NOT NULL THEN 'submitted'
                WHEN is_final = 0 OR submitted_at IS NULL THEN 'draft'
                ELSE COALESCE(status, 'draft')
            END
            WHERE status IS NULL OR status = ''
        ");
        
        // Ensure all submissions have a status
        DB::statement("
            UPDATE submissions 
            SET status = 'draft' 
            WHERE status IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
