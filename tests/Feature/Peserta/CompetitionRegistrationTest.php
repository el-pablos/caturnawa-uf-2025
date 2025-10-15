<?php

namespace Tests\Feature\Peserta;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class CompetitionRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'peserta']);
        
        // Fake storage
        Storage::fake('public');
    }

    /** @test */
    public function peserta_can_view_available_competitions()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        Competition::factory()->count(3)->create(['status' => 'active']);

        $response = $this->actingAs($user)->get('/peserta/competitions');

        $response->assertStatus(200);
    }

    /** @test */
    public function peserta_can_view_competition_details()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $competition = Competition::factory()->create([
            'slug' => 'spc-2025',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get("/peserta/competitions/{$competition->slug}");

        $response->assertStatus(200);
    }

    /** @test */
    public function peserta_can_register_for_competition()
    {
        $user = User::factory()->create([
            'phone' => '081234567890',
            'institution' => 'Universitas Test',
            'participant_status' => 'Mahasiswa Unas',
        ]);
        $user->assignRole('peserta');

        // Mock DynamicFormService to return empty requirements
        $this->mock(\App\Services\DynamicFormService::class, function ($mock) {
            $mock->shouldReceive('getCompetitionRequirements')->andReturn(collect([]));
            $mock->shouldReceive('generateFormHTML')->andReturn('');
            $mock->shouldReceive('processFormData')->andReturn([]);
            $mock->shouldReceive('buildValidationRules')->andReturn(['rules' => [], 'messages' => []]);
        });

        // Mock RegistrationValidationService
        $this->mock(\App\Services\RegistrationValidationService::class, function ($mock) {
            $mock->shouldReceive('checkRegistrationConflicts')->andReturn([]);
        });

        // Create team competition (as originally intended)
        $competition = Competition::factory()->create([
            'slug' => 'test-competition-2025',
            'name' => 'Test Team Competition 2025',
            'status' => 'active',
            'category' => 'event_debate',
            'type' => 'team',
            'is_team_competition' => true,
            'min_team_members' => 2,
            'max_team_members' => 5,
        ]);

        // Create fake files for team members
        Storage::fake('public');

        $registrationData = [
            'team_name' => 'Team Awesome',
            'phone' => '081234567890',
            'institution' => 'Universitas Test',
            'gender' => 'male',
            'participant_category' => 'unas_student',
            'team_members' => [
                [
                    'name' => 'Member 1',
                    'email' => 'member1@test.com',
                    'phone' => '081234567891',
                    'foto' => UploadedFile::fake()->image('member1.jpg'),
                ],
                [
                    'name' => 'Member 2',
                    'email' => 'member2@test.com',
                    'phone' => '081234567892',
                    'foto' => UploadedFile::fake()->image('member2.jpg'),
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(
            "/peserta/competitions/{$competition->slug}/register",
            $registrationData
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'competition_id' => $competition->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function registration_requires_team_name()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        // Ensure this is a team competition
        $competition = Competition::factory()->create([
            'slug' => 'team-comp-2025',
            'type' => 'team',
            'is_team_competition' => true,
        ]);

        $response = $this->actingAs($user)->post(
            "/peserta/competitions/{$competition->slug}/register",
            [
                'institution' => 'Universitas Test',
                'gender' => 'male',
            ]
        );

        $response->assertSessionHasErrors('team_name');
    }

    /** @test */
    public function peserta_cannot_register_for_inactive_competition()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        $competition = Competition::factory()->create([
            'slug' => 'spc-2025',
            'status' => 'inactive',
        ]);

        // Test accessing competition detail page (show)
        $response = $this->actingAs($user)->get("/peserta/competitions/{$competition->slug}");
        $response->assertStatus(403);

        // Test posting registration
        $response2 = $this->actingAs($user)->post("/peserta/competitions/{$competition->slug}/register", [
            'team_name' => 'Test Team',
            'gender' => 'male',
        ]);
        $response2->assertStatus(403);
    }

    /** @test */
    public function peserta_can_view_own_registrations()
    {
        $user = User::factory()->create();
        $user->assignRole('peserta');

        Registration::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/peserta/registrations');

        $response->assertStatus(200);
    }

    /** @test */
    public function peserta_cannot_view_other_users_registrations()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('peserta');
        
        $user2 = User::factory()->create();
        $user2->assignRole('peserta');

        $registration = Registration::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->get("/peserta/registrations/{$registration->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function registration_status_defaults_to_pending()
    {
        $registration = Registration::factory()->create();

        $this->assertEquals('pending', $registration->status);
    }

    /** @test */
    public function guest_cannot_register_for_competition()
    {
        $competition = Competition::factory()->create(['slug' => 'spc-2025']);

        $response = $this->post("/peserta/competitions/{$competition->slug}/register", [
            'team_name' => 'Team Test',
        ]);

        $response->assertRedirect('/login');
    }
}

