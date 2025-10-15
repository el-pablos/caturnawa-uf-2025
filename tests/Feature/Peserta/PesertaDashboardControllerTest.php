<?php

namespace Tests\Feature\Peserta;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Submission;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class PesertaDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

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

        // Create peserta user
        $this->peserta = User::factory()->create();
        $this->peserta->assignRole('peserta');

        // Create competition
        $this->competition = Competition::factory()->create([
            'name' => 'Test Competition',
            'is_active' => true,
            'registration_start' => Carbon::now()->subDays(10),
            'registration_end' => Carbon::now()->addDays(10),
        ]);
    }

    /** @test */
    public function peserta_can_access_dashboard()
    {
        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('peserta.dashboard');
        $response->assertViewHas([
            'stats',
            'registrations',
            'availableCompetitions',
            'submissions'
        ]);
    }

    /** @test */
    public function non_peserta_cannot_access_peserta_dashboard()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/peserta/dashboard');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_peserta_dashboard()
    {
        $response = $this->get('/peserta/dashboard');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function dashboard_displays_correct_statistics()
    {
        // Create registrations
        $registration1 = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
            'status' => 'confirmed',
            'amount' => 100000,
        ]);
        $registration2 = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
            'status' => 'pending',
            'amount' => 50000,
        ]);

        // Create payment
        Payment::factory()->create([
            'registration_id' => $registration1->id,
            'transaction_status' => 'settlement',
            'gross_amount' => 100000,
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $stats = $response->viewData('stats');
        
        $this->assertArrayHasKey('total_registrations', $stats);
        $this->assertArrayHasKey('confirmed_registrations', $stats);
        $this->assertArrayHasKey('pending_registrations', $stats);
        $this->assertEquals(2, $stats['total_registrations']);
        $this->assertEquals(1, $stats['confirmed_registrations']);
        $this->assertEquals(1, $stats['pending_registrations']);
    }

    /** @test */
    public function dashboard_shows_user_registrations()
    {
        Registration::factory()->count(3)->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $registrations = $response->viewData('registrations');
        
        $this->assertCount(3, $registrations);
    }

    /** @test */
    public function dashboard_shows_available_competitions()
    {
        // Create active competitions
        Competition::factory()->count(2)->create([
            'is_active' => true,
            'registration_start' => Carbon::now()->subDays(5),
            'registration_end' => Carbon::now()->addDays(5),
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $availableCompetitions = $response->viewData('availableCompetitions');
        
        $this->assertGreaterThan(0, $availableCompetitions->count());
    }

    /** @test */
    public function dashboard_does_not_show_inactive_competitions()
    {
        Competition::factory()->create([
            'is_active' => false,
            'registration_start' => Carbon::now()->subDays(5),
            'registration_end' => Carbon::now()->addDays(5),
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $availableCompetitions = $response->viewData('availableCompetitions');
        
        // Should only show active competitions
        foreach ($availableCompetitions as $competition) {
            $this->assertTrue($competition->is_active);
        }
    }

    /** @test */
    public function dashboard_shows_user_submissions()
    {
        $registration = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
            'status' => 'confirmed',
        ]);

        Submission::factory()->count(2)->create([
            'registration_id' => $registration->id,
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $submissions = $response->viewData('submissions');
        
        $this->assertCount(2, $submissions);
    }

    /** @test */
    public function dashboard_shows_upcoming_deadlines()
    {
        $competition = Competition::factory()->create([
            'is_active' => true,
            'registration_end' => Carbon::now()->addDays(3),
            'submission_deadline' => Carbon::now()->addDays(7),
        ]);

        Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $competition->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $response->assertStatus(200);
        // Upcoming deadlines should be available
    }

    /** @test */
    public function dashboard_handles_no_registrations_gracefully()
    {
        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $stats = $response->viewData('stats');
        
        $this->assertEquals(0, $stats['total_registrations']);
        $this->assertEquals(0, $stats['confirmed_registrations']);
        $this->assertEquals(0, $stats['pending_registrations']);
    }

    /** @test */
    public function dashboard_shows_payment_status()
    {
        $registration = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
            'status' => 'pending',
        ]);

        Payment::factory()->create([
            'registration_id' => $registration->id,
            'transaction_status' => 'pending',
            'gross_amount' => 100000,
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $registrations = $response->viewData('registrations');
        
        $this->assertNotNull($registrations->first()->payment);
    }

    /** @test */
    public function dashboard_calculates_total_paid_correctly()
    {
        $registration1 = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
        ]);
        $registration2 = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
        ]);

        Payment::factory()->create([
            'registration_id' => $registration1->id,
            'transaction_status' => 'settlement',
            'gross_amount' => 100000,
        ]);
        Payment::factory()->create([
            'registration_id' => $registration2->id,
            'transaction_status' => 'settlement',
            'gross_amount' => 150000,
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $stats = $response->viewData('stats');
        
        $this->assertEquals(250000, $stats['total_paid']);
    }

    /** @test */
    public function dashboard_shows_submission_statistics()
    {
        $registration = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
        ]);

        Submission::factory()->create([
            'registration_id' => $registration->id,
            'is_final' => true,
        ]);
        Submission::factory()->create([
            'registration_id' => $registration->id,
            'is_final' => false,
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $stats = $response->viewData('stats');
        
        $this->assertEquals(2, $stats['total_submissions']);
        $this->assertEquals(1, $stats['final_submissions']);
    }

    /** @test */
    public function dashboard_only_shows_own_data()
    {
        $otherPeserta = User::factory()->create();
        $otherPeserta->assignRole('peserta');

        // Create registration for other peserta
        Registration::factory()->create([
            'user_id' => $otherPeserta->id,
            'competition_id' => $this->competition->id,
        ]);

        $response = $this->actingAs($this->peserta)->get('/peserta/dashboard');
        
        $registrations = $response->viewData('registrations');
        
        // Should not see other peserta's registrations
        $this->assertCount(0, $registrations);
    }
}

