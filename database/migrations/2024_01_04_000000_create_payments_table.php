<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel payments
 * 
 * Membuat tabel pembayaran dengan integrasi Midtrans
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->decimal('gross_amount', 10, 2);
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('fraud_status')->nullable();
            $table->string('status_code')->nullable();
            $table->text('status_message')->nullable();
            $table->string('payment_code')->nullable();
            $table->string('pdf_url')->nullable();
            $table->string('finish_redirect_url')->nullable();
            $table->text('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
