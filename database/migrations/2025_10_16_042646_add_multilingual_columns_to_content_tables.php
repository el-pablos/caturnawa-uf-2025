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
        // Add multilingual columns to FAQs table
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('question_en', 500)->nullable()->after('question');
            $table->string('question_id', 500)->nullable()->after('question_en');
            $table->text('answer_en')->nullable()->after('answer');
            $table->text('answer_id')->nullable()->after('answer_en');
        });

        // Add multilingual columns to Terms and Conditions table
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_id')->nullable()->after('title_en');
            $table->text('content_en')->nullable()->after('content');
            $table->text('content_id')->nullable()->after('content_en');
        });

        // Add multilingual columns to Competition Timelines table
        Schema::table('competition_timelines', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_id')->nullable()->after('title_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove multilingual columns from FAQs table
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['question_en', 'question_id', 'answer_en', 'answer_id']);
        });

        // Remove multilingual columns from Terms and Conditions table
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_id', 'content_en', 'content_id']);
        });

        // Remove multilingual columns from Competition Timelines table
        Schema::table('competition_timelines', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_id']);
        });
    }
};
