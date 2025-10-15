<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class ScoreTest extends TestCase
{
    use RefreshDatabase;

    protected $jury;
    protected $registration;
    protected $competition;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::create(['name' => 'juri']);
        
        $this->jury = User::factory()->create();
        $this->jury->assignRole('juri');
        
        $this->competition = Competition::factory()->create();
        $this->registration = Registration::factory()->create([
            'competition_id' => $this->competition->id,
        ]);
    }

    /** @test */
    public function it_can_create_a_score()
    {
        $score = Score::factory()->create([
            'registration_id' => $this->registration->id,
            'competition_id' => $this->competition->id,
            'jury_id' => $this->jury->id,
            'criteria_scores' => [
                'innovation' => 85,
                'creativity' => 85,
                'technical' => 85,
            ],
            'total_score' => 85,
        ]);

        $this->assertDatabaseHas('scores', [
            'registration_id' => $this->registration->id,
            'jury_id' => $this->jury->id,
        ]);

        $this->assertEquals(85, $score->fresh()->total_score);
    }

    /** @test */
    public function it_belongs_to_registration()
    {
        $score = Score::factory()->create([
            'registration_id' => $this->registration->id,
        ]);

        $this->assertInstanceOf(Registration::class, $score->registration);
        $this->assertEquals($this->registration->id, $score->registration->id);
    }

    /** @test */
    public function it_belongs_to_competition()
    {
        $score = Score::factory()->create([
            'competition_id' => $this->competition->id,
        ]);

        $this->assertInstanceOf(Competition::class, $score->competition);
        $this->assertEquals($this->competition->id, $score->competition->id);
    }

    /** @test */
    public function it_belongs_to_jury()
    {
        $score = Score::factory()->create([
            'jury_id' => $this->jury->id,
        ]);

        $this->assertInstanceOf(User::class, $score->jury);
        $this->assertEquals($this->jury->id, $score->jury->id);
    }

    /** @test */
    public function it_casts_is_final_to_boolean()
    {
        $score = Score::factory()->create([
            'is_final' => 1,
        ]);

        $this->assertIsBool($score->is_final);
        $this->assertTrue($score->is_final);
    }

    /** @test */
    public function it_casts_criteria_scores_to_array()
    {
        $criteriaScores = [
            'originality' => 85,
            'methodology' => 90,
            'impact' => 88,
        ];

        $score = Score::factory()->create([
            'criteria_scores' => $criteriaScores,
        ]);

        $this->assertIsArray($score->fresh()->criteria_scores);
        $this->assertEquals($criteriaScores, $score->fresh()->criteria_scores);
    }

    /** @test */
    public function it_can_mark_as_final()
    {
        $score = Score::factory()->create([
            'is_final' => false,
        ]);

        $score->markAsFinal();

        $this->assertTrue($score->fresh()->is_final);
    }

    /** @test */
    public function it_can_calculate_total_from_criteria()
    {
        $criteriaScores = [
            'originality' => 80,
            'methodology' => 90,
            'impact' => 85,
        ];

        $score = Score::factory()->create([
            'criteria_scores' => $criteriaScores,
        ]);

        $total = $score->calculateTotalFromCriteria();

        $this->assertEquals(85, $total); // Average of 80, 90, 85
    }

    /** @test */
    public function it_scopes_final_scores()
    {
        Score::factory()->count(3)->create([
            'is_final' => true,
        ]);
        Score::factory()->count(2)->create([
            'is_final' => false,
        ]);

        $finalScores = Score::final()->get();
        
        $this->assertCount(3, $finalScores);
    }

    /** @test */
    public function it_scopes_draft_scores()
    {
        Score::factory()->count(3)->create([
            'is_final' => true,
        ]);
        Score::factory()->count(2)->create([
            'is_final' => false,
        ]);

        $draftScores = Score::draft()->get();
        
        $this->assertCount(2, $draftScores);
    }

    /** @test */
    public function it_scopes_scores_by_jury()
    {
        $otherJury = User::factory()->create();
        $otherJury->assignRole('juri');
        
        Score::factory()->count(3)->create([
            'jury_id' => $this->jury->id,
        ]);
        Score::factory()->count(2)->create([
            'jury_id' => $otherJury->id,
        ]);

        $juryScores = Score::byJury($this->jury->id)->get();
        
        $this->assertCount(3, $juryScores);
    }

    /** @test */
    public function it_scopes_scores_by_competition()
    {
        $otherCompetition = Competition::factory()->create();
        
        Score::factory()->count(3)->create([
            'competition_id' => $this->competition->id,
        ]);
        Score::factory()->count(2)->create([
            'competition_id' => $otherCompetition->id,
        ]);

        $competitionScores = Score::byCompetition($this->competition->id)->get();
        
        $this->assertCount(3, $competitionScores);
    }

    /** @test */
    public function it_validates_score_range()
    {
        $score = Score::factory()->create([
            'total_score' => 85,
        ]);

        $this->assertGreaterThanOrEqual(0, $score->total_score);
        $this->assertLessThanOrEqual(100, $score->total_score);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'registration_id',
            'competition_id',
            'jury_id',
            'total_score',
            'criteria_scores',
            'comments',
            'is_final',
        ];

        $score = new Score();

        foreach ($fillable as $attribute) {
            $this->assertContains($attribute, $score->getFillable());
        }
    }

    /** @test */
    public function it_can_get_grade_letter()
    {
        $scoreAPlus = Score::factory()->create([
            'criteria_scores' => ['a' => 95, 'b' => 95, 'c' => 95],
            'total_score' => 95
        ]);
        $scoreA = Score::factory()->create([
            'criteria_scores' => ['a' => 85, 'b' => 85, 'c' => 85],
            'total_score' => 85
        ]);
        $scoreBPlus = Score::factory()->create([
            'criteria_scores' => ['a' => 75, 'b' => 75, 'c' => 75],
            'total_score' => 75
        ]);
        $scoreBMinus = Score::factory()->create([
            'criteria_scores' => ['a' => 65, 'b' => 65, 'c' => 65],
            'total_score' => 65
        ]);
        $scoreCMinus = Score::factory()->create([
            'criteria_scores' => ['a' => 50, 'b' => 50, 'c' => 50],
            'total_score' => 50
        ]);

        $this->assertEquals('A+', $scoreAPlus->fresh()->getGradeLetter());
        $this->assertEquals('A', $scoreA->fresh()->getGradeLetter());
        $this->assertEquals('B+', $scoreBPlus->fresh()->getGradeLetter());
        $this->assertEquals('B-', $scoreBMinus->fresh()->getGradeLetter());
        $this->assertEquals('C-', $scoreCMinus->fresh()->getGradeLetter());
    }

    /** @test */
    public function it_can_check_if_passing()
    {
        $passingScore = Score::factory()->create([
            'criteria_scores' => ['a' => 75, 'b' => 75, 'c' => 75],
            'total_score' => 75
        ]);
        $failingScore = Score::factory()->create([
            'criteria_scores' => ['a' => 50, 'b' => 50, 'c' => 50],
            'total_score' => 50
        ]);

        $this->assertTrue($passingScore->fresh()->isPassing());
        $this->assertFalse($failingScore->fresh()->isPassing());
    }

    /** @test */
    public function it_can_get_criteria_score_by_name()
    {
        $criteriaScores = [
            'originality' => 85,
            'methodology' => 90,
            'impact' => 88,
        ];

        $score = Score::factory()->create([
            'criteria_scores' => $criteriaScores,
        ]);

        $this->assertEquals(85, $score->fresh()->getCriteriaScore('originality'));
        $this->assertEquals(90, $score->fresh()->getCriteriaScore('methodology'));
        $this->assertEquals(88, $score->fresh()->getCriteriaScore('impact'));
    }

    /** @test */
    public function it_returns_null_for_non_existent_criteria()
    {
        $score = Score::factory()->create([
            'criteria_scores' => ['originality' => 85],
        ]);

        $this->assertNull($score->fresh()->getCriteriaScore('nonexistent'));
    }

    /** @test */
    public function it_can_update_criteria_score()
    {
        $score = Score::factory()->create([
            'criteria_scores' => ['originality' => 85],
        ]);

        $score->updateCriteriaScore('originality', 90);

        $this->assertEquals(90, $score->fresh()->getCriteriaScore('originality'));
    }

    /** @test */
    public function it_can_get_average_score_for_registration()
    {
        $jury2 = User::factory()->create();
        $jury2->assignRole('juri');

        Score::factory()->create([
            'registration_id' => $this->registration->id,
            'jury_id' => $this->jury->id,
            'criteria_scores' => ['a' => 80, 'b' => 80, 'c' => 80],
            'total_score' => 80,
            'is_final' => true,
        ]);
        Score::factory()->create([
            'registration_id' => $this->registration->id,
            'jury_id' => $jury2->id,
            'criteria_scores' => ['a' => 90, 'b' => 90, 'c' => 90],
            'total_score' => 90,
            'is_final' => true,
        ]);

        $average = Score::getAverageForRegistration($this->registration->id);

        $this->assertEquals(85, $average);
    }
}

