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
            // Add missing columns that are referenced in the application
            $table->decimal('prize_amount', 15, 2)->default(0)->after('prizes');
            $table->enum('type', ['individual', 'team', 'group'])->default('individual')->after('category');
            $table->text('short_description')->nullable()->after('description');
            $table->string('contact_person')->nullable()->after('short_description');
            $table->string('contact_email')->nullable()->after('contact_person');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->text('terms_conditions')->nullable()->after('rules');
            $table->json('judging_criteria')->nullable()->after('terms_conditions');
            $table->string('certificate_template')->nullable()->after('judging_criteria');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->integer('view_count')->default(0)->after('is_featured');
            $table->integer('registration_count')->default(0)->after('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn([
                'prize_amount',
                'type',
                'short_description',
                'contact_person',
                'contact_email',
                'contact_phone',
                'terms_conditions',
                'judging_criteria',
                'certificate_template',
                'is_featured',
                'view_count',
                'registration_count'
            ]);
        });
    }
};
