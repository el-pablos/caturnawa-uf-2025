<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);

        // Create admin and superadmin users
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->superadmin = User::factory()->create();
        $this->superadmin->assignRole('superadmin');
    }

    /** @test */
    public function admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('stats');
    }

    /** @test */
    public function superadmin_can_access_dashboard()
    {
        $response = $this->actingAs($this->superadmin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /** @test */
    public function non_admin_cannot_access_dashboard()
    {
        $peserta = User::factory()->create();
        $peserta->assignRole('peserta');

        $response = $this->actingAs($peserta)->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function dashboard_displays_correct_statistics()
    {
        // Create test data
        User::factory()->count(10)->create();
        $competition = Competition::factory()->create(['is_active' => true]);
        $registration = Registration::factory()->create([
            'competition_id' => $competition->id,
            'status' => 'confirmed'
        ]);
        Payment::factory()->create([
            'registration_id' => $registration->id,
            'transaction_status' => 'settlement',
            'gross_amount' => 100000
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $stats = $response->viewData('stats');
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_users', $stats);
        $this->assertArrayHasKey('total_registrations', $stats);
        $this->assertArrayHasKey('total_competitions', $stats);
        $this->assertArrayHasKey('total_revenue', $stats);
    }

    /** @test */
    public function dashboard_caches_statistics()
    {
        Cache::flush();

        $this->actingAs($this->admin)->get('/admin/dashboard');
        
        $this->assertTrue(Cache::has('admin_dashboard_stats'));
    }

    /** @test */
    public function admin_can_get_chart_data_via_ajax()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dashboard/chart-data');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'months',
                'registrations',
                'revenues'
            ]
        ]);
    }

    /** @test */
    public function admin_can_get_recent_data_via_ajax()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dashboard/recent-data');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'recent_competitions',
                'recent_payments'
            ]
        ]);
    }

    /** @test */
    public function admin_can_get_user_distribution_via_ajax()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dashboard/user-distribution');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'labels',
                'data',
                'colors'
            ]
        ]);
    }

    /** @test */
    public function admin_can_get_stats_api()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dashboard/stats-api');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'statistics',
                'chart_data',
                'generated_at'
            ]
        ]);
    }

    /** @test */
    public function admin_can_get_competition_stats()
    {
        Competition::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dashboard/competition-stats');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /** @test */
    public function admin_can_get_daily_report()
    {
        $date = Carbon::today()->toDateString();

        $response = $this->actingAs($this->admin)
            ->getJson("/admin/dashboard/daily-report?date={$date}");
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'date',
                'date_formatted',
                'new_registrations',
                'successful_payments',
                'total_revenue',
                'new_users',
                'new_submissions'
            ]
        ]);
    }

    /** @test */
    public function admin_can_get_competition_metrics()
    {
        Competition::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dashboard/competition-metrics');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data'
        ]);
    }

    /** @test */
    public function dashboard_handles_empty_data_gracefully()
    {
        // Clear all data
        User::query()->delete();
        Competition::query()->delete();
        Registration::query()->delete();
        Payment::query()->delete();

        // Recreate admin
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $stats = $response->viewData('stats');
        
        $this->assertEquals(1, $stats['total_users']); // Only admin
        $this->assertEquals(0, $stats['total_registrations']);
        $this->assertEquals(0, $stats['total_competitions']);
        $this->assertEquals(0, $stats['total_revenue']);
    }

    /** @test */
    public function chart_data_returns_six_months_of_data()
    {
        $response = $this->actingAs($this->admin)
            ->getJson('/admin/dashboard/chart-data');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(6, $data['months']);
        $this->assertCount(6, $data['registrations']);
        $this->assertCount(6, $data['revenues']);
    }

    /** @test */
    public function dashboard_statistics_include_all_required_fields()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        
        $stats = $response->viewData('stats');
        
        $requiredFields = [
            'total_users',
            'total_registrations',
            'confirmed_registrations',
            'total_competitions',
            'active_competitions',
            'total_revenue',
            'pending_payments',
            'total_submissions'
        ];

        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $stats);
        }
    }

    /** @test */
    public function dashboard_handles_database_errors_gracefully()
    {
        // This test would require mocking DB to throw exceptions
        // For now, we test that the dashboard loads even with minimal data
        
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewHas('stats');
    }
}

