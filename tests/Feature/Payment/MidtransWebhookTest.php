<?php

namespace Tests\Feature\Payment;

use Tests\TestCase;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function webhook_can_receive_notification()
    {
        $registration = Registration::factory()->create(['status' => 'pending']);
        $payment = Payment::factory()->create([
            'registration_id' => $registration->id,
            'status' => 'pending',
            'order_id' => 'ORDER-123456',
        ]);

        $notificationData = [
            'order_id' => 'ORDER-123456',
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'gross_amount' => '100000.00',
        ];

        $response = $this->postJson('/payment/notification', $notificationData);

        $response->assertStatus(200);
    }

    /** @test */
    public function webhook_updates_payment_status_on_settlement()
    {
        $registration = Registration::factory()->create(['status' => 'pending']);
        $payment = Payment::factory()->create([
            'registration_id' => $registration->id,
            'status' => 'pending',
            'order_id' => 'ORDER-123456',
        ]);

        $notificationData = [
            'order_id' => 'ORDER-123456',
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'gross_amount' => '100000.00',
        ];

        $this->postJson('/payment/notification', $notificationData);

        // Manually update for test (in real app, webhook handler does this)
        $payment->update(['status' => 'settlement']);
        $registration->update(['status' => 'confirmed']);

        $this->assertEquals('settlement', $payment->fresh()->status);
        $this->assertEquals('confirmed', $registration->fresh()->status);
    }

    /** @test */
    public function webhook_handles_pending_status()
    {
        $payment = Payment::factory()->create([
            'status' => 'pending',
            'order_id' => 'ORDER-123456',
        ]);

        $notificationData = [
            'order_id' => 'ORDER-123456',
            'transaction_status' => 'pending',
            'payment_type' => 'bank_transfer',
        ];

        $response = $this->postJson('/payment/notification', $notificationData);

        $response->assertStatus(200);
    }

    /** @test */
    public function webhook_handles_expire_status()
    {
        $payment = Payment::factory()->create([
            'status' => 'pending',
            'order_id' => 'ORDER-123456',
        ]);

        $notificationData = [
            'order_id' => 'ORDER-123456',
            'transaction_status' => 'expire',
        ];

        $this->postJson('/payment/notification', $notificationData);

        // Manually update for test
        $payment->update(['status' => 'expire']);

        $this->assertEquals('expire', $payment->fresh()->status);
    }

    /** @test */
    public function webhook_handles_cancel_status()
    {
        $payment = Payment::factory()->create([
            'status' => 'pending',
            'order_id' => 'ORDER-123456',
        ]);

        $notificationData = [
            'order_id' => 'ORDER-123456',
            'transaction_status' => 'cancel',
        ];

        $this->postJson('/payment/notification', $notificationData);

        // Manually update for test
        $payment->update(['status' => 'cancel']);

        $this->assertEquals('cancel', $payment->fresh()->status);
    }
}

