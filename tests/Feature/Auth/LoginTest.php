<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);
    }

    /** @test */
    public function user_can_view_login_page()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('peserta');

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function user_cannot_login_with_nonexistent_email()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function login_requires_email()
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function login_requires_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function authenticated_user_cannot_access_login_page()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/peserta/dashboard');
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_redirected_to_admin_dashboard_after_login()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function juri_redirected_to_juri_dashboard_after_login()
    {
        $juri = User::factory()->create([
            'email' => 'juri@example.com',
            'password' => Hash::make('password123'),
        ]);
        $juri->assignRole('juri');

        $this->post('/login', [
            'email' => 'juri@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($juri);
    }

    /** @test */
    public function peserta_redirected_to_peserta_dashboard_after_login()
    {
        $peserta = User::factory()->create([
            'email' => 'peserta@example.com',
            'password' => Hash::make('password123'),
        ]);
        $peserta->assignRole('peserta');

        $this->post('/login', [
            'email' => 'peserta@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($peserta);
    }
}

