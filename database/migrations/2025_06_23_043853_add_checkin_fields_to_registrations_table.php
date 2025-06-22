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
            $table->timestamp('checked_in_at')->nullable()->after('confirmed_at');
            $table->unsignedBigInteger('checked_in_by')->nullable()->after('checked_in_at');
            $table->timestamp('re_enabled_at')->nullable()->after('checked_in_by');
            $table->unsignedBigInteger('re_enabled_by')->nullable()->after('re_enabled_at');

            $table->foreign('checked_in_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('re_enabled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['checked_in_by']);
            $table->dropForeign(['re_enabled_by']);
            $table->dropColumn(['checked_in_at', 'checked_in_by', 're_enabled_at', 're_enabled_by']);
        });
    }
};
