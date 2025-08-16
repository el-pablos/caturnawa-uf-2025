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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('competitions', 'price_unas_student')) {
                $table->decimal('price_unas_student', 10, 2)->default(150000)->after('price');
            }
            if (!Schema::hasColumn('competitions', 'price_external_student')) {
                $table->decimal('price_external_student', 10, 2)->default(200000)->after('price_unas_student');
            }
            
            // Upload requirements (only if not exists)
            if (!Schema::hasColumn('competitions', 'upload_requirements')) {
                $table->json('upload_requirements')->nullable()->after('is_team_competition');
            }
            if (!Schema::hasColumn('competitions', 'document_requirements')) {
                $table->json('document_requirements')->nullable()->after('upload_requirements');
            }
            
            // Timeline details (only if not exists)
            if (!Schema::hasColumn('competitions', 'submission_start')) {
                $table->datetime('submission_start')->nullable()->after('registration_end');
            }
            if (!Schema::hasColumn('competitions', 'submission_end')) {
                $table->datetime('submission_end')->nullable()->after('submission_start');
            }
            if (!Schema::hasColumn('competitions', 'judging_start')) {
                $table->datetime('judging_start')->nullable()->after('submission_end');
            }
            if (!Schema::hasColumn('competitions', 'judging_end')) {
                $table->datetime('judging_end')->nullable()->after('judging_start');
            }
            if (!Schema::hasColumn('competitions', 'announcement_date')) {
                $table->datetime('announcement_date')->nullable()->after('judging_end');
            }
            
            // Contact person untuk payment finish (only if not exists)
            if (!Schema::hasColumn('competitions', 'contact_person_name')) {
                $table->string('contact_person_name')->nullable()->after('announcement_date');
            }
            if (!Schema::hasColumn('competitions', 'contact_person_whatsapp')) {
                $table->string('contact_person_whatsapp')->nullable()->after('contact_person_name');
            }
            
            // Kompetisi settings (only if not exists)
            if (!Schema::hasColumn('competitions', 'guidelines')) {
                $table->text('guidelines')->nullable()->after('contact_person_whatsapp');
            }
            if (!Schema::hasColumn('competitions', 'submission_formats')) {
                $table->json('submission_formats')->nullable()->after('guidelines');
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
                'price_unas_student',
                'price_external_student',
                'min_team_members',
                'is_team_competition',
                'upload_requirements',
                'document_requirements',
                'submission_start',
                'submission_end',
                'judging_start',
                'judging_end',
                'announcement_date',
                'contact_person_name',
                'contact_person_whatsapp',
                'guidelines',
                'submission_formats'
            ]);
        });
    }
};
