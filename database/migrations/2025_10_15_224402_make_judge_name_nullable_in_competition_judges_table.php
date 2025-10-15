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
        Schema::table('competition_judges', function (Blueprint $table) {
            $table->string('judge_name')->nullable()->change();
            $table->string('expertise')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competition_judges', function (Blueprint $table) {
            $table->string('judge_name')->nullable(false)->change();
            $table->string('expertise')->nullable(false)->change();
        });
    }
};
