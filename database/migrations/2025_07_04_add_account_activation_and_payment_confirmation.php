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
        // Update users table untuk sistem aktivasi akun
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_account_active')->default(false)->after('email_verified_at');
            $table->timestamp('account_activated_at')->nullable()->after('is_account_active');
            $table->unsignedBigInteger('activated_by')->nullable()->after('account_activated_at');
            $table->text('activation_notes')->nullable()->after('activated_by');
            
            $table->foreign('activated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Update payments table untuk sistem konfirmasi pembayaran
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(false)->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('is_confirmed');
            $table->unsignedBigInteger('confirmed_by')->nullable()->after('confirmed_at');
            $table->text('confirmation_notes')->nullable()->after('confirmed_by');
            
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('set null');
        });

        // Tambah tabel untuk settings sistem
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('system_settings')->insert([
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Mode maintenance website',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'registration_open',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Status buka tutup pendaftaran',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Maaf, website sedang dalam masa pemeliharaan. Silakan coba lagi nanti.',
                'type' => 'string',
                'description' => 'Pesan maintenance mode',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'registration_closed_message',
                'value' => 'Pendaftaran sedang ditutup. Silakan tunggu periode selanjutnya.',
                'type' => 'string',
                'description' => 'Pesan pendaftaran ditutup',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['activated_by']);
            $table->dropColumn(['is_account_active', 'account_activated_at', 'activated_by', 'activation_notes']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['is_confirmed', 'confirmed_at', 'confirmed_by', 'confirmation_notes']);
        });

        Schema::dropIfExists('system_settings');
    }
};
