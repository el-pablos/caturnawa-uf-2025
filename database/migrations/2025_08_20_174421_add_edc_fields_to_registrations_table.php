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
            // EDC-specific fields based on REVISION 5 OF EDC REGISTRATION FORM UNAS FEST 2025.pdf
            $table->string('faculty_major')->nullable(); // Faculty/Major
            $table->string('npm_nim')->nullable(); // NPM/NIM
            $table->string('agency_origin')->nullable(); // Agency Origin
            $table->string('full_address')->nullable(); // Full Address
            $table->string('whatsapp_number')->nullable(); // WhatsApp Number
            $table->string('group_name')->nullable(); // Group name related to UNAS FEST 2025 theme
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'faculty_major',
                'npm_nim',
                'agency_origin',
                'full_address',
                'whatsapp_number',
                'group_name'
            ]);
        });
    }
};
