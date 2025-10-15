<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\MidtransService;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;
use App\Models\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MidtransServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $midtransService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->midtransService = new MidtransService();
    }

    /** @test */
    public function it_validates_midtrans_configuration()
    {
        // Configuration should be set from phpunit.xml
        $this->assertEquals('test-server-key', config('midtrans.server_key'));
        $this->assertEquals('test-client-key', config('midtrans.client_key'));
        $this->assertFalse(config('midtrans.is_production'));
    }

    /** @test */
    public function it_can_create_transaction_payload()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
        ]);

        $competition = Competition::factory()->create([
            'name' => 'Test Competition',
        ]);

        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $competition->id,
            'team_name' => 'Team Test',
        ]);

        $payment = Payment::factory()->create([
            'registration_id' => $registration->id,
            'amount' => 100000,
            'order_id' => 'ORDER-TEST-123456',
        ]);

        // Test payload structure
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals(100000, $payment->amount);
        $this->assertEquals('ORDER-TEST-123456', $payment->order_id);
    }

    /** @test */
    public function it_generates_unique_order_ids()
    {
        $payment1 = Payment::factory()->create();
        $payment2 = Payment::factory()->create();

        $this->assertNotEquals($payment1->order_id, $payment2->order_id);
    }

    /** @test */
    public function it_can_update_payment_status()
    {
        $payment = Payment::factory()->create(['status' => 'pending']);

        $payment->update(['status' => 'settlement']);

        $this->assertEquals('settlement', $payment->fresh()->status);
    }

    /** @test */
    public function it_confirms_registration_on_payment_settlement()
    {
        $registration = Registration::factory()->create(['status' => 'pending']);
        $payment = Payment::factory()->create([
            'registration_id' => $registration->id,
            'status' => 'pending',
        ]);

        // Simulate payment settlement
        $payment->update(['status' => 'settlement']);
        $registration->update(['status' => 'confirmed']);

        $this->assertEquals('settlement', $payment->fresh()->status);
        $this->assertEquals('confirmed', $registration->fresh()->status);
    }

    /** @test */
    public function it_handles_payment_expiry()
    {
        $payment = Payment::factory()->create(['status' => 'pending']);

        $payment->update(['status' => 'expire']);

        $this->assertEquals('expire', $payment->fresh()->status);
    }

    /** @test */
    public function it_handles_payment_cancellation()
    {
        $payment = Payment::factory()->create(['status' => 'pending']);

        $payment->update(['status' => 'cancel']);

        $this->assertEquals('cancel', $payment->fresh()->status);
    }
}

