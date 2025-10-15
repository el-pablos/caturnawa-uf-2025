<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CheckRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles for testing
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'finance']);
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);
    }

    /**
     * Test unauthenticated user is redirected to login
     */
    public function test_unauthenticated_user_redirected_to_login()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test superadmin can access admin routes
     */
    public function test_superadmin_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('superadmin');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test admin can access admin routes
     */
    public function test_admin_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test finance can access admin routes
     */
    public function test_finance_can_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('finance');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test juri cannot access admin routes
     */
    public function test_juri_cannot_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('juri');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test peserta cannot access admin routes
     */
    public function test_peserta_cannot_access_admin_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test juri can access juri routes
     */
    public function test_juri_can_access_juri_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('juri');

        $response = $this->actingAs($user)->get('/juri/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test peserta cannot access juri routes
     */
    public function test_peserta_cannot_access_juri_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($user)->get('/juri/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test peserta can access peserta routes
     */
    public function test_peserta_can_access_peserta_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($user)->get('/peserta/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test admin cannot access peserta routes
     */
    public function test_admin_cannot_access_peserta_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/peserta/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test user without role is redirected to login
     */
    public function test_user_without_role_redirected_to_login()
    {
        $user = User::factory()->create();
        // No role assigned

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test middleware checks multiple roles correctly
     */
    public function test_middleware_checks_multiple_roles()
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $finance = User::factory()->create();
        $finance->assignRole('finance');

        // All should be able to access admin dashboard
        $this->actingAs($superadmin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($finance)->get('/admin/dashboard')->assertStatus(200);
    }

    /**
     * Test access denied returns appropriate response
     */
    public function test_access_denied_returns_appropriate_response()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Should be either redirect or 403
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test superadmin cannot access juri routes
     */
    public function test_superadmin_cannot_access_juri_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('superadmin');

        $response = $this->actingAs($user)->get('/juri/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test juri cannot access admin dashboard
     */
    public function test_juri_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $user->assignRole('juri');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }

    /**
     * Test peserta cannot access admin dashboard
     */
    public function test_peserta_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Can be either redirect or 403 forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Expected redirect (302) or forbidden (403), got ' . $response->status()
        );
    }
}

