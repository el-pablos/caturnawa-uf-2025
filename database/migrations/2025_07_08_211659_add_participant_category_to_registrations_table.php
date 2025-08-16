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
            $table->enum('participant_category', ['unas_student', 'external_student', 'high_school_student'])
                  ->default('external_student')
                  ->after('education_level');
            $table->string('pricing_phase')->nullable()->after('participant_category');
            $table->decimal('original_price', 10, 2)->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['participant_category', 'pricing_phase', 'original_price']);
        });
    }
};
