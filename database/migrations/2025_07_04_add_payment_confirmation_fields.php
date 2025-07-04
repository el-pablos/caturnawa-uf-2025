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
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(false)->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('is_confirmed');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->after('confirmed_at');
            $table->text('confirmation_notes')->nullable()->after('confirmed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['is_confirmed', 'confirmed_at', 'confirmed_by', 'confirmation_notes']);
        });
    }
};
