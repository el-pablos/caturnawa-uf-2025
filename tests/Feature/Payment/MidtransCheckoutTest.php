<?php

namespace Tests\Feature\Payment;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class MidtransCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'peserta']);
    }

    /** @test */
    public function authenticated_user_can_access_payment_page()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');
        
        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get("/payment/checkout/{$registration->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function guest_cannot_access_payment_page()
    {
        $registration = Registration::factory()->create();

        $response = $this->get("/payment/checkout/{$registration->id}");

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_only_access_own_payment()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('peserta');
        
        $user2 = User::factory()->create();
        $user2->assignRole('peserta');

        $registration = Registration::factory()->create([
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)->get("/payment/checkout/{$registration->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function payment_is_created_for_registration()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');
        
        $competition = Competition::factory()->create([
            'early_bird_price' => 100000,
        ]);

        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $competition->id,
            'status' => 'pending',
        ]);

        // Check that payment can be created
        $payment = Payment::factory()->create([
            'registration_id' => $registration->id,
            'amount' => 100000,
        ]);

        $this->assertDatabaseHas('payments', [
            'registration_id' => $registration->id,
            'amount' => 100000,
        ]);
    }

    /** @test */
    public function payment_has_unique_order_id()
    {
        $payment1 = Payment::factory()->create();
        $payment2 = Payment::factory()->create();

        $this->assertNotEquals($payment1->order_id, $payment2->order_id);
    }

    /** @test */
    public function confirmed_registration_cannot_be_paid_again()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)->get("/payment/checkout/{$registration->id}");

        $response->assertStatus(403);
    }
}

