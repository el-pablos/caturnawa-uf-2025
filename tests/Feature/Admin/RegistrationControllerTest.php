<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class RegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $peserta;
    protected $competition;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);

        // Create users
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->peserta = User::factory()->create();
        $this->peserta->assignRole('peserta');

        // Create competition
        $this->competition = Competition::factory()->create([
            'name' => 'Test Competition',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_can_access_registrations_index()
    {
        $response = $this->actingAs($this->admin)->get('/admin/registrations');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.registrations.index');
        $response->assertViewHas(['registrations', 'competitions', 'stats']);
    }

    /** @test */
    public function non_admin_cannot_access_registrations_index()
    {
        $response = $this->actingAs($this->peserta)->get('/admin/registrations');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function registrations_index_displays_statistics()
    {
        Registration::factory()->count(5)->create([
            'competition_id' => $this->competition->id,
            'status' => 'pending'
        ]);
        Registration::factory()->count(3)->create([
            'competition_id' => $this->competition->id,
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/registrations');
        
        $stats = $response->viewData('stats');
        
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('pending', $stats);
        $this->assertArrayHasKey('confirmed', $stats);
        $this->assertArrayHasKey('cancelled', $stats);
        $this->assertEquals(8, $stats['total']);
        $this->assertEquals(5, $stats['pending']);
        $this->assertEquals(3, $stats['confirmed']);
    }

    /** @test */
    public function registrations_can_be_filtered_by_status()
    {
        Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'pending'
        ]);
        Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/registrations?status=pending');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function registrations_can_be_filtered_by_competition()
    {
        $competition2 = Competition::factory()->create();
        
        Registration::factory()->create([
            'competition_id' => $this->competition->id
        ]);
        Registration::factory()->create([
            'competition_id' => $competition2->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/admin/registrations?competition_id={$this->competition->id}");
        
        $response->assertStatus(200);
    }

    /** @test */
    public function registrations_can_be_searched_by_user_name()
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $user->assignRole('peserta');
        
        Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $this->competition->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/registrations?search=John');
        
        $response->assertStatus(200);
        $response->assertSee('John Doe');
    }

    /** @test */
    public function admin_can_view_registration_details()
    {
        $registration = Registration::factory()->create([
            'competition_id' => $this->competition->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/admin/registrations/{$registration->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.registrations.show');
        $response->assertViewHas('registration');
    }

    /** @test */
    public function admin_can_confirm_registration()
    {
        $registration = Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/registrations/{$registration->id}/confirm");
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $registration->refresh();
        $this->assertEquals('confirmed', $registration->status);
    }

    /** @test */
    public function admin_can_cancel_registration()
    {
        $registration = Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/registrations/{$registration->id}/cancel");
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $registration->refresh();
        $this->assertEquals('cancelled', $registration->status);
    }

    /** @test */
    public function admin_can_re_enable_cancelled_registration()
    {
        $registration = Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'cancelled'
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/registrations/{$registration->id}/re-enable");
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $registration->refresh();
        $this->assertEquals('pending', $registration->status);
    }

    /** @test */
    public function admin_can_export_registrations_to_excel()
    {
        Registration::factory()->count(5)->create([
            'competition_id' => $this->competition->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/registrations/export/excel');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function admin_can_export_registrations_to_pdf()
    {
        Registration::factory()->count(5)->create([
            'competition_id' => $this->competition->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/registrations/export/pdf');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function registrations_index_is_paginated()
    {
        Registration::factory()->count(25)->create([
            'competition_id' => $this->competition->id
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/registrations');
        
        $registrations = $response->viewData('registrations');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $registrations);
    }

    /** @test */
    public function admin_can_bulk_confirm_registrations()
    {
        $registration1 = Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'pending'
        ]);
        $registration2 = Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/registrations/bulk-confirm', [
                'registration_ids' => [$registration1->id, $registration2->id]
            ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $registration1->refresh();
        $registration2->refresh();
        $this->assertEquals('confirmed', $registration1->status);
        $this->assertEquals('confirmed', $registration2->status);
    }

    /** @test */
    public function admin_can_view_registration_payment_details()
    {
        $registration = Registration::factory()->create([
            'competition_id' => $this->competition->id
        ]);
        $payment = Payment::factory()->create([
            'registration_id' => $registration->id,
            'transaction_status' => 'settlement'
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/admin/registrations/{$registration->id}");
        
        $response->assertStatus(200);
        $response->assertSee($payment->order_id);
    }
}

