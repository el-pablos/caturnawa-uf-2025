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
            // Bilingual support
            if (!Schema::hasColumn('competitions', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }
            if (!Schema::hasColumn('competitions', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            
            // Phase pricing system
            if (!Schema::hasColumn('competitions', 'phase1_price')) {
                $table->decimal('phase1_price', 10, 2)->nullable()->after('early_bird_price');
            }
            if (!Schema::hasColumn('competitions', 'phase1_deadline')) {
                $table->datetime('phase1_deadline')->nullable()->after('early_bird_deadline');
            }
            
            // Additional timeline fields
            if (!Schema::hasColumn('competitions', 'webinar_date')) {
                $table->datetime('webinar_date')->nullable()->after('phase1_deadline');
            }
            if (!Schema::hasColumn('competitions', 'round1_date')) {
                $table->datetime('round1_date')->nullable()->after('webinar_date');
            }
            if (!Schema::hasColumn('competitions', 'round2_date')) {
                $table->datetime('round2_date')->nullable()->after('round1_date');
            }
            if (!Schema::hasColumn('competitions', 'semifinal_date')) {
                $table->datetime('semifinal_date')->nullable()->after('round2_date');
            }
            if (!Schema::hasColumn('competitions', 'final_date')) {
                $table->datetime('final_date')->nullable()->after('semifinal_date');
            }
            if (!Schema::hasColumn('competitions', 'technical_meeting')) {
                $table->datetime('technical_meeting')->nullable()->after('final_date');
            }
            if (!Schema::hasColumn('competitions', 'result_announcement')) {
                $table->datetime('result_announcement')->nullable()->after('technical_meeting');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn([
                'name_en',
                'description_en',
                'phase1_price',
                'phase1_deadline',
                'webinar_date',
                'round1_date',
                'round2_date',
                'semifinal_date',
                'final_date',
                'technical_meeting',
                'result_announcement'
            ]);
        });
    }
};
