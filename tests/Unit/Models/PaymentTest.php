<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function payment_belongs_to_registration()
    {
        $payment = Payment::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $payment->registration()
        );
        
        $this->assertInstanceOf(Registration::class, $payment->registration);
    }



    /** @test */
    public function payment_has_fillable_attributes()
    {
        $payment = new Payment();

        $this->assertContains('registration_id', $payment->getFillable());
        $this->assertContains('order_id', $payment->getFillable());
        $this->assertContains('gross_amount', $payment->getFillable());
        $this->assertContains('amount', $payment->getFillable());
        $this->assertContains('transaction_status', $payment->getFillable());
        $this->assertContains('status', $payment->getFillable());
    }

    /** @test */
    public function payment_generates_unique_order_id()
    {
        $payment1 = Payment::factory()->create();
        $payment2 = Payment::factory()->create();

        $this->assertNotEquals($payment1->order_id, $payment2->order_id);
        $this->assertNotEmpty($payment1->order_id);
        $this->assertNotEmpty($payment2->order_id);
    }

    /** @test */
    public function payment_status_defaults_to_pending()
    {
        $payment = Payment::factory()->create();

        $this->assertEquals('pending', $payment->transaction_status);
    }

    /** @test */
    public function payment_status_can_be_updated_to_settlement()
    {
        $payment = Payment::factory()->create(['transaction_status' => 'pending']);

        $payment->update(['transaction_status' => 'settlement']);

        $this->assertEquals('settlement', $payment->transaction_status);
    }

    /** @test */
    public function payment_status_can_be_updated_to_expire()
    {
        $payment = Payment::factory()->create(['transaction_status' => 'pending']);

        $payment->update(['transaction_status' => 'expire']);

        $this->assertEquals('expire', $payment->transaction_status);
    }

    /** @test */
    public function payment_status_can_be_updated_to_cancel()
    {
        $payment = Payment::factory()->create(['transaction_status' => 'pending']);

        $payment->update(['transaction_status' => 'cancel']);

        $this->assertEquals('cancel', $payment->transaction_status);
    }

    /** @test */
    public function payment_casts_amount_to_decimal()
    {
        $payment = Payment::factory()->create(['amount' => 100000]);

        $this->assertIsNumeric($payment->amount);
        $this->assertEquals(100000, $payment->amount);
    }

    /** @test */
    public function payment_casts_midtrans_response_to_array()
    {
        $payment = Payment::factory()->create([
            'midtrans_response' => ['payment_type' => 'bank_transfer'],
        ]);

        $this->assertIsArray($payment->midtrans_response);
        $this->assertEquals('bank_transfer', $payment->midtrans_response['payment_type']);
    }
}

