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
        Schema::table('competitions', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'draft', 'completed'])->default('active')->after('is_active');
        });
        
        // Update existing records based on is_active column
        DB::statement("UPDATE competitions SET status = CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
