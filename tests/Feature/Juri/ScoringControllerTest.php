<?php

namespace Tests\Feature\Juri;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Submission;
use App\Models\Score;
use App\Models\CompetitionJudge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class ScoringControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $juri;
    protected $peserta;
    protected $competition;
    protected $registration;
    protected $submission;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);

        // Create users
        $this->juri = User::factory()->create();
        $this->juri->assignRole('juri');

        $this->peserta = User::factory()->create();
        $this->peserta->assignRole('peserta');

        // Create competition
        $this->competition = Competition::factory()->create([
            'name' => 'Test Competition',
            'is_active' => true,
            'competition_start' => Carbon::now()->subDays(1),
            'competition_end' => Carbon::now()->addDays(30),
        ]);

        // Assign juri to competition
        CompetitionJudge::create([
            'competition_id' => $this->competition->id,
            'user_id' => $this->juri->id,
        ]);

        // Create registration
        $this->registration = Registration::factory()->create([
            'user_id' => $this->peserta->id,
            'competition_id' => $this->competition->id,
            'status' => 'confirmed',
        ]);

        // Create submission
        $this->submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'is_final' => true,
        ]);
    }

    /** @test */
    public function juri_can_access_scoring_index()
    {
        $response = $this->actingAs($this->juri)->get('/juri/scoring');
        
        $response->assertStatus(200);
        $response->assertViewIs('juri.scoring.index');
        $response->assertViewHas(['competitions', 'submissions']);
    }

    /** @test */
    public function non_juri_cannot_access_scoring_index()
    {
        $response = $this->actingAs($this->peserta)->get('/juri/scoring');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function juri_only_sees_assigned_competitions()
    {
        // Create another competition without assigning juri
        $otherCompetition = Competition::factory()->create([
            'is_active' => true,
            'competition_start' => Carbon::now()->subDays(1),
        ]);

        $response = $this->actingAs($this->juri)->get('/juri/scoring');
        
        $competitions = $response->viewData('competitions');
        
        $this->assertCount(1, $competitions);
        $this->assertEquals($this->competition->id, $competitions->first()->id);
    }

    /** @test */
    public function juri_can_view_competition_submissions()
    {
        $response = $this->actingAs($this->juri)
            ->get("/juri/scoring/competition/{$this->competition->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('juri.scoring.competition');
        $response->assertViewHas(['competition', 'submissions']);
    }

    /** @test */
    public function juri_can_view_submission_detail()
    {
        $response = $this->actingAs($this->juri)
            ->get("/juri/scoring/submission/{$this->submission->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('juri.scoring.show');
        $response->assertViewHas('submission');
    }

    /** @test */
    public function juri_can_access_scoring_form()
    {
        $response = $this->actingAs($this->juri)
            ->get("/juri/scoring/submission/{$this->submission->id}/score");

        $response->assertStatus(200);
        $response->assertViewIs('juri.scoring.form');
        $response->assertViewHas(['submission', 'competition']);
    }

    /** @test */
    public function juri_can_submit_score()
    {
        $scoreData = [
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'criteria_scores' => [
                'originality' => 85,
                'methodology' => 90,
                'impact' => 88,
            ],
            'total_score' => 87.67,
            'feedback' => 'Great work!',
            'is_final' => true,
        ];

        $response = $this->actingAs($this->juri)
            ->post("/juri/scoring/submission/{$this->submission->id}/score", $scoreData);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('scores', [
            'registration_id' => $this->registration->id,
            'jury_id' => $this->juri->id,
            'is_final' => true,
        ]);
    }

    /** @test */
    public function score_submission_requires_total_score()
    {
        $scoreData = [
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'feedback' => 'Great work!',
        ];

        $response = $this->actingAs($this->juri)
            ->post("/juri/scoring/submission/{$this->submission->id}/score", $scoreData);
        
        $response->assertSessionHasErrors('total_score');
    }

    /** @test */
    public function total_score_must_be_between_0_and_100()
    {
        $scoreData = [
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'total_score' => 150, // Invalid
            'is_final' => true,
        ];

        $response = $this->actingAs($this->juri)
            ->post("/juri/scoring/submission/{$this->submission->id}/score", $scoreData);
        
        $response->assertSessionHasErrors('total_score');
    }

    /** @test */
    public function juri_can_update_existing_score()
    {
        // Create initial score
        $score = Score::create([
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'jury_id' => $this->juri->id,
            'total_score' => 80,
            'is_final' => false,
        ]);

        $updateData = [
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'total_score' => 90,
            'feedback' => 'Updated feedback',
            'is_final' => true,
        ];

        $response = $this->actingAs($this->juri)
            ->put("/juri/scoring/score/{$score->id}", $updateData);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $score->refresh();
        $this->assertEquals(90, $score->total_score);
        $this->assertEquals('Updated feedback', $score->feedback);
        $this->assertTrue($score->is_final);
    }

    /** @test */
    public function juri_cannot_score_submission_from_unassigned_competition()
    {
        // Create competition without assigning juri
        $otherCompetition = Competition::factory()->create();
        $otherRegistration = Registration::factory()->create([
            'competition_id' => $otherCompetition->id,
            'status' => 'confirmed',
        ]);
        $otherSubmission = Submission::factory()->create([
            'registration_id' => $otherRegistration->id,
            'is_final' => true,
        ]);

        $response = $this->actingAs($this->juri)
            ->get("/juri/scoring/submission/{$otherSubmission->id}/score");
        
        $response->assertStatus(403);
    }

    /** @test */
    public function juri_can_view_scoring_statistics()
    {
        // Create some scores
        Score::factory()->count(3)->create([
            'jury_id' => $this->juri->id,
            'competition_id' => $this->competition->id,
            'is_final' => true,
        ]);

        $response = $this->actingAs($this->juri)->get('/juri/scoring');
        
        $response->assertStatus(200);
        // Statistics should be available in the view
    }

    /** @test */
    public function juri_can_save_draft_score()
    {
        $scoreData = [
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'total_score' => 85,
            'is_final' => false, // Draft
        ];

        $response = $this->actingAs($this->juri)
            ->post("/juri/scoring/submission/{$this->submission->id}/score", $scoreData);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('scores', [
            'registration_id' => $this->registration->id,
            'jury_id' => $this->juri->id,
            'is_final' => false,
        ]);
    }

    /** @test */
    public function juri_can_view_their_submitted_scores()
    {
        Score::create([
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'jury_id' => $this->juri->id,
            'total_score' => 85,
            'is_final' => true,
        ]);

        $response = $this->actingAs($this->juri)
            ->get('/juri/scoring/my-scores');
        
        $response->assertStatus(200);
        $response->assertViewIs('juri.scoring.my-scores');
    }

    /** @test */
    public function scoring_index_displays_pending_submissions_count()
    {
        // Create additional submissions without scores
        $registration2 = Registration::factory()->create([
            'competition_id' => $this->competition->id,
            'status' => 'confirmed',
        ]);
        Submission::factory()->create([
            'registration_id' => $registration2->id,
            'is_final' => true,
        ]);

        $response = $this->actingAs($this->juri)->get('/juri/scoring');
        
        $response->assertStatus(200);
        // Should show pending submissions count
    }
}

