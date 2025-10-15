<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Registration;
use App\Models\User;
use App\Models\Competition;
use App\Models\Payment;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_belongs_to_user()
    {
        $registration = Registration::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $registration->user()
        );
        
        $this->assertInstanceOf(User::class, $registration->user);
    }

    /** @test */
    public function registration_belongs_to_competition()
    {
        $registration = Registration::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $registration->competition()
        );
        
        $this->assertInstanceOf(Competition::class, $registration->competition);
    }

    /** @test */
    public function registration_has_payment_relationship()
    {
        $registration = Registration::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasOne::class,
            $registration->payment()
        );
    }

    /** @test */
    public function registration_has_team_members_relationship()
    {
        $registration = Registration::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $registration->teamMembers()
        );
    }

    /** @test */
    public function registration_has_fillable_attributes()
    {
        $registration = new Registration();
        
        $this->assertContains('user_id', $registration->getFillable());
        $this->assertContains('competition_id', $registration->getFillable());
        $this->assertContains('team_name', $registration->getFillable());
        $this->assertContains('status', $registration->getFillable());
    }

    /** @test */
    public function registration_status_defaults_to_pending()
    {
        $registration = Registration::factory()->create();
        
        $this->assertEquals('pending', $registration->status);
    }

    /** @test */
    public function registration_can_be_confirmed()
    {
        $registration = Registration::factory()->create(['status' => 'pending']);
        
        $registration->update(['status' => 'confirmed']);
        
        $this->assertEquals('confirmed', $registration->status);
    }

    /** @test */
    public function registration_can_be_cancelled()
    {
        $registration = Registration::factory()->create(['status' => 'pending']);
        
        $registration->update(['status' => 'cancelled']);
        
        $this->assertEquals('cancelled', $registration->status);
    }

    /** @test */
    public function registration_can_be_locked()
    {
        $registration = Registration::factory()->create(['is_locked' => false]);
        
        $registration->update(['is_locked' => true]);
        
        $this->assertTrue($registration->is_locked);
    }



    /** @test */
    public function registration_casts_dynamic_data_field_to_array()
    {
        $registration = Registration::factory()->create([
            'dynamic_data' => ['key' => 'value'],
        ]);

        $this->assertIsArray($registration->dynamic_data);
        $this->assertEquals('value', $registration->dynamic_data['key']);
    }
}

