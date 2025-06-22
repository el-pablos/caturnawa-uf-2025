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
            $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
            $table->string('cancelled_reason')->nullable()->after('cancelled_at');
            $table->timestamp('reopened_at')->nullable()->after('cancelled_reason');
            $table->unsignedBigInteger('reopened_by')->nullable()->after('reopened_at');

            $table->foreign('reopened_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['reopened_by']);
            $table->dropColumn(['cancelled_at', 'cancelled_reason', 'reopened_at', 'reopened_by']);
        });
    }
};
