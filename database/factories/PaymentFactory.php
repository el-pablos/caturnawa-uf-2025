<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'order_id' => 'UF2025-' . date('dmYHis') . '-' . rand(100, 999),
            'gross_amount' => 100000,
            'amount' => 100000,
            'transaction_status' => 'pending',
            'status' => 'pending',
            'payment_type' => null,
            'payment_method' => null,
            'bank' => null,
            'va_number' => null,
            'transaction_id' => null,
            'fraud_status' => null,
            'status_code' => null,
            'status_message' => null,
            'payment_code' => null,
            'pdf_url' => null,
            'finish_redirect_url' => null,
            'snap_token' => null,
            'paid_at' => null,
            'expired_at' => now()->addDays(1),
            'midtrans_response' => null,
            'is_confirmed' => false,
            'confirmed_at' => null,
            'confirmed_by' => null,
            'confirmation_notes' => null,
        ];
    }

    public function settlement(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_status' => 'settlement',
            'status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'paid_at' => now(),
        ]);
    }

    public function expire(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_status' => 'expire',
            'status' => 'expire',
        ]);
    }

    public function cancel(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_status' => 'cancel',
            'status' => 'cancel',
        ]);
    }
}

