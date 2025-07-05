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
        Schema::table('competitions', function (Blueprint $table) {
            $table->datetime('registration_deadline')->after('registration_end')->nullable();
            $table->datetime('round1_date')->after('registration_deadline')->nullable();
            $table->datetime('semifinal_date')->after('round1_date')->nullable();
            $table->datetime('final_date')->after('semifinal_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn(['registration_deadline', 'round1_date', 'semifinal_date', 'final_date']);
        });
    }
};
