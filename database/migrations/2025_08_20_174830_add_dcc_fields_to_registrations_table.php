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
            // DCC-specific fields based on B.Ing Infografis.pdf and Short Video.pdf
            // For high school students (SMA/MAN/SMK) in JABODETABEK area
            $table->string('school_name')->nullable(); // Name of school institution
            $table->string('school_type')->nullable(); // SMA/MAN/SMK type
            $table->string('jabodetabek_area')->nullable(); // Area in JABODETABEK
            $table->string('student_id_card_number')->nullable(); // Student ID Card number
            $table->string('participant_phone')->nullable(); // Participant phone number
            $table->json('team_member_details')->nullable(); // 3 team members with name, institution, phone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'school_name',
                'school_type',
                'jabodetabek_area',
                'student_id_card_number',
                'participant_phone',
                'team_member_details'
            ]);
        });
    }
};
