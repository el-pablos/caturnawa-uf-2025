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
            $table->boolean('is_locked')->default(false)->after('status');
            $table->string('lock_reason')->nullable()->after('is_locked');
            $table->timestamp('locked_at')->nullable()->after('lock_reason');
            $table->foreignId('locked_by')->nullable()->constrained('users')->onDelete('set null')->after('locked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['locked_by']);
            $table->dropColumn(['is_locked', 'lock_reason', 'locked_at', 'locked_by']);
        });
    }
};
