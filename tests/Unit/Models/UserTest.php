<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
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
    public function user_has_fillable_attributes()
    {
        $user = new User();

        $this->assertContains('name', $user->getFillable());
        $this->assertContains('email', $user->getFillable());
        $this->assertContains('password', $user->getFillable());
        $this->assertContains('phone', $user->getFillable());
        $this->assertContains('institution', $user->getFillable());
        $this->assertContains('avatar', $user->getFillable());
    }

    /** @test */
    public function user_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plaintext-password',
        ]);

        $this->assertNotEquals('plaintext-password', $user->password);
        $this->assertTrue(Hash::check('plaintext-password', $user->password));
    }

    /** @test */
    public function user_has_registrations_relationship()
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $user->registrations()
        );
    }



    /** @test */
    public function user_can_be_assigned_role()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $this->assertTrue($user->hasRole('peserta'));
    }

    /** @test */
    public function user_can_have_multiple_roles()
    {
        $user = User::factory()->create();
        $user->assignRole(['admin', 'juri']);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('juri'));
    }

    /** @test */
    public function user_email_is_unique()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => 'test@example.com']);
    }

    /** @test */
    public function user_has_hidden_attributes()
    {
        $user = User::factory()->create([
            'password' => 'secret',
            'remember_token' => 'token123',
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    /** @test */
    public function user_email_is_cast_to_datetime()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }
}

