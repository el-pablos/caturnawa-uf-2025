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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Notification type preferences (enabled/disabled)
            $table->boolean('registration_notifications')->default(true);
            $table->boolean('payment_notifications')->default(true);
            $table->boolean('submission_notifications')->default(true);
            $table->boolean('scoring_notifications')->default(true);
            $table->boolean('certificate_notifications')->default(true);
            $table->boolean('announcement_notifications')->default(true);
            $table->boolean('reminder_notifications')->default(true);
            $table->boolean('admin_notifications')->default(true);

            // Email frequency settings
            // Options: instant, daily, weekly, disabled
            $table->string('email_frequency')->default('instant');

            // Digest settings
            $table->time('digest_time')->nullable(); // Time to send daily/weekly digest
            $table->string('digest_day')->nullable(); // Day for weekly digest (monday, tuesday, etc.)

            // Channel preferences
            $table->boolean('email_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->boolean('push_enabled')->default(false);

            // Additional settings
            $table->boolean('marketing_emails')->default(false);
            $table->boolean('newsletter')->default(false);

            $table->timestamps();

            // Ensure one preference record per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
