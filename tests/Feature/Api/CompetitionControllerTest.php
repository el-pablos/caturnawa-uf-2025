<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class CompetitionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);

        // Create user
        $this->user = User::factory()->create();
        $this->user->assignRole('peserta');
    }

    /** @test */
    public function it_returns_all_competitions()
    {
        Competition::factory()->count(3)->create();

        $response = $this->getJson('/api/competitions');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'category',
                    'status',
                    'is_team',
                    'max_participants',
                    'price',
                ]
            ]
        ]);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_filters_competitions_by_category()
    {
        Competition::factory()->create(['category' => 'debate']);
        Competition::factory()->create(['category' => 'essay']);
        Competition::factory()->create(['category' => 'debate']);

        $response = $this->getJson('/api/competitions?category=debate');
        
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_filters_competitions_by_status()
    {
        Competition::factory()->create(['status' => 'open']);
        Competition::factory()->create(['status' => 'closed']);
        Competition::factory()->create(['status' => 'open']);

        $response = $this->getJson('/api/competitions?status=open');
        
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_searches_competitions_by_name()
    {
        Competition::factory()->create(['name' => 'Debate Competition']);
        Competition::factory()->create(['name' => 'Essay Writing']);
        Competition::factory()->create(['name' => 'Debate Tournament']);

        $response = $this->getJson('/api/competitions?search=Debate');
        
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_searches_competitions_by_description()
    {
        Competition::factory()->create([
            'name' => 'Competition A',
            'description' => 'This is about debate'
        ]);
        Competition::factory()->create([
            'name' => 'Competition B',
            'description' => 'This is about essay'
        ]);

        $response = $this->getJson('/api/competitions?search=debate');
        
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_returns_competition_details()
    {
        $competition = Competition::factory()->create([
            'name' => 'Test Competition',
            'description' => 'Test Description',
            'category' => 'debate',
            'price' => 100000,
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'description',
                'category',
                'status',
                'is_team',
                'max_participants',
                'price',
                'early_bird_price',
                'rules',
                'prizes',
                'requirements',
                'registrations_count',
                'confirmed_registrations_count',
                'days_left',
                'timeline',
            ]
        ]);
        $response->assertJson([
            'success' => true,
            'data' => [
                'name' => 'Test Competition',
                'category' => 'debate',
                'price' => 100000,
            ]
        ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_competition()
    {
        $response = $this->getJson('/api/competitions/99999');
        
        $response->assertStatus(404);
    }

    /** @test */
    public function it_includes_registration_counts_in_competition_details()
    {
        $competition = Competition::factory()->create();
        
        Registration::factory()->count(5)->create([
            'competition_id' => $competition->id,
            'status' => 'confirmed',
        ]);
        Registration::factory()->count(3)->create([
            'competition_id' => $competition->id,
            'status' => 'pending',
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");
        
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'registrations_count' => 8,
                'confirmed_registrations_count' => 5,
            ]
        ]);
    }

    /** @test */
    public function it_returns_competitions_ordered_by_name()
    {
        Competition::factory()->create(['name' => 'Zebra Competition']);
        Competition::factory()->create(['name' => 'Alpha Competition']);
        Competition::factory()->create(['name' => 'Beta Competition']);

        $response = $this->getJson('/api/competitions');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertEquals('Alpha Competition', $data[0]['name']);
        $this->assertEquals('Beta Competition', $data[1]['name']);
        $this->assertEquals('Zebra Competition', $data[2]['name']);
    }

    /** @test */
    public function it_includes_early_bird_pricing_information()
    {
        $competition = Competition::factory()->create([
            'price' => 100000,
            'early_bird_price' => 75000,
            'early_bird_deadline' => Carbon::now()->addDays(7),
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'price' => 100000,
                'early_bird_price' => 75000,
            ]
        ]);
        $this->assertNotNull($response->json('data.early_bird_end'));
    }

    /** @test */
    public function it_includes_competition_dates()
    {
        $competition = Competition::factory()->create([
            'registration_start' => Carbon::now(),
            'registration_end' => Carbon::now()->addDays(30),
            'competition_start' => Carbon::now()->addDays(40),
            'competition_end' => Carbon::now()->addDays(50),
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");
        
        $response->assertStatus(200);
        $this->assertNotNull($response->json('data.registration_start'));
        $this->assertNotNull($response->json('data.registration_end'));
        $this->assertNotNull($response->json('data.competition_start'));
        $this->assertNotNull($response->json('data.competition_end'));
    }

    /** @test */
    public function it_includes_team_information()
    {
        $competition = Competition::factory()->create([
            'is_team_competition' => true,
            'max_participants' => 5,
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'is_team' => true,
                'max_participants' => 5,
            ]
        ]);
    }

    /** @test */
    public function it_can_combine_multiple_filters()
    {
        Competition::factory()->create([
            'category' => 'debate',
            'status' => 'open',
            'name' => 'Debate Competition 1'
        ]);
        Competition::factory()->create([
            'category' => 'debate',
            'status' => 'closed',
            'name' => 'Debate Competition 2'
        ]);
        Competition::factory()->create([
            'category' => 'essay',
            'status' => 'open',
            'name' => 'Essay Competition'
        ]);

        $response = $this->getJson('/api/competitions?category=debate&status=open');
        
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_returns_empty_array_when_no_competitions_found()
    {
        $response = $this->getJson('/api/competitions?category=nonexistent');
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => []
        ]);
    }

    /** @test */
    public function it_includes_days_left_calculation()
    {
        $competition = Competition::factory()->create([
            'registration_end' => Carbon::now()->addDays(10),
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('days_left', $response->json('data'));
    }

    /** @test */
    public function it_includes_timeline_information()
    {
        $competition = Competition::factory()->create([
            'round1_date' => Carbon::now()->addDays(30),
            'semifinal_date' => Carbon::now()->addDays(40),
            'final_date' => Carbon::now()->addDays(50),
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('timeline', $response->json('data'));
    }
}

