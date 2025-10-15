<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $superadmin;
    protected $admin;
    protected $peserta;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);

        // Create users
        $this->superadmin = User::factory()->create();
        $this->superadmin->assignRole('superadmin');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->peserta = User::factory()->create();
        $this->peserta->assignRole('peserta');
    }

    /** @test */
    public function superadmin_can_access_users_index()
    {
        $response = $this->actingAs($this->superadmin)->get('/admin/users');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas(['users', 'roles', 'stats']);
    }

    /** @test */
    public function admin_can_access_users_index()
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_users_index()
    {
        $response = $this->actingAs($this->peserta)->get('/admin/users');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function users_index_displays_statistics()
    {
        User::factory()->count(5)->create()->each(function ($user) {
            $user->assignRole('peserta');
        });

        $response = $this->actingAs($this->superadmin)->get('/admin/users');
        
        $stats = $response->viewData('stats');
        
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('active', $stats);
        $this->assertArrayHasKey('inactive', $stats);
        $this->assertGreaterThan(0, $stats['total']);
    }

    /** @test */
    public function users_can_be_filtered_by_role()
    {
        $response = $this->actingAs($this->superadmin)
            ->get('/admin/users?role=peserta');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function users_can_be_filtered_by_status()
    {
        $response = $this->actingAs($this->superadmin)
            ->get('/admin/users?status=active');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function users_can_be_searched()
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $user->assignRole('peserta');

        $response = $this->actingAs($this->superadmin)
            ->get('/admin/users?search=John');
        
        $response->assertStatus(200);
        $response->assertSee('John Doe');
    }

    /** @test */
    public function superadmin_can_access_create_user_form()
    {
        $response = $this->actingAs($this->superadmin)->get('/admin/users/create');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function superadmin_can_create_new_user()
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'peserta',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->superadmin)
            ->post('/admin/users', $userData);
        
        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'New User',
        ]);
    }

    /** @test */
    public function user_creation_requires_name()
    {
        $userData = [
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'peserta',
        ];

        $response = $this->actingAs($this->superadmin)
            ->post('/admin/users', $userData);
        
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function user_creation_requires_valid_email()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'peserta',
        ];

        $response = $this->actingAs($this->superadmin)
            ->post('/admin/users', $userData);
        
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_creation_requires_unique_email()
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'peserta',
        ];

        $response = $this->actingAs($this->superadmin)
            ->post('/admin/users', $userData);
        
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_creation_requires_password_confirmation()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
            'role' => 'peserta',
        ];

        $response = $this->actingAs($this->superadmin)
            ->post('/admin/users', $userData);
        
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function user_creation_requires_valid_role()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'invalid-role',
        ];

        $response = $this->actingAs($this->superadmin)
            ->post('/admin/users', $userData);
        
        $response->assertSessionHasErrors('role');
    }

    /** @test */
    public function created_user_is_auto_verified()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'peserta',
        ];

        $this->actingAs($this->superadmin)->post('/admin/users', $userData);
        
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user->email_verified_at);
    }

    /** @test */
    public function created_user_has_assigned_role()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'juri',
        ];

        $this->actingAs($this->superadmin)->post('/admin/users', $userData);
        
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('juri'));
    }

    /** @test */
    public function superadmin_can_view_user_details()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($this->superadmin)
            ->get("/admin/users/{$user->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
    }

    /** @test */
    public function superadmin_can_access_edit_user_form()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($this->superadmin)
            ->get("/admin/users/{$user->id}/edit");
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas(['user', 'roles']);
    }

    /** @test */
    public function superadmin_can_update_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        $user->assignRole('peserta');

        $updateData = [
            'name' => 'New Name',
            'email' => $user->email,
            'phone' => '081234567890',
            'role' => 'peserta',
        ];

        $response = $this->actingAs($this->superadmin)
            ->put("/admin/users/{$user->id}", $updateData);
        
        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    /** @test */
    public function superadmin_can_update_user_password()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $updateData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => 'peserta',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($this->superadmin)
            ->put("/admin/users/{$user->id}", $updateData);

        $response->assertRedirect('/admin/users');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /** @test */
    public function superadmin_can_delete_user()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $response = $this->actingAs($this->superadmin)
            ->delete("/admin/users/{$user->id}");

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function users_index_is_paginated()
    {
        User::factory()->count(25)->create()->each(function ($user) {
            $user->assignRole('peserta');
        });

        $response = $this->actingAs($this->superadmin)->get('/admin/users');

        $users = $response->viewData('users');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $users);
    }
}
