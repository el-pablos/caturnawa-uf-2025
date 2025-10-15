<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Submission;
use App\Models\Score;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected $registration;

    protected function setUp(): void
    {
        parent::setUp();
        
        $user = User::factory()->create();
        $competition = Competition::factory()->create();
        $this->registration = Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $competition->id,
        ]);
    }

    /** @test */
    public function it_can_create_a_submission()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'title' => 'Test Submission',
        ]);

        $this->assertDatabaseHas('submissions', [
            'registration_id' => $this->registration->id,
            'title' => 'Test Submission',
        ]);
    }

    /** @test */
    public function it_belongs_to_registration()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
        ]);

        $this->assertInstanceOf(Registration::class, $submission->registration);
        $this->assertEquals($this->registration->id, $submission->registration->id);
    }

    /** @test */
    public function it_has_scores_relationship()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
        ]);
        
        Score::factory()->count(3)->create([
            'registration_id' => $this->registration->id,
        ]);

        $this->assertCount(3, $submission->scores);
    }

    /** @test */
    public function it_casts_is_final_to_boolean()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'is_final' => 1,
        ]);

        $this->assertIsBool($submission->is_final);
        $this->assertTrue($submission->is_final);
    }

    /** @test */
    public function it_casts_submitted_at_to_datetime()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'submitted_at' => now(),
        ]);

        $this->assertInstanceOf(Carbon::class, $submission->submitted_at);
    }

    /** @test */
    public function it_can_mark_as_final()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'is_final' => false,
        ]);

        $submission->submit();

        $this->assertTrue($submission->fresh()->is_final);
        $this->assertNotNull($submission->fresh()->submitted_at);
    }

    /** @test */
    public function it_can_check_if_late()
    {
        $competition = Competition::factory()->create([
            'submission_deadline' => Carbon::now()->subDays(1),
        ]);
        $registration = Registration::factory()->create([
            'competition_id' => $competition->id,
        ]);
        $submission = Submission::factory()->create([
            'registration_id' => $registration->id,
            'submitted_at' => Carbon::now(),
        ]);

        $this->assertTrue($submission->isOverdue());
    }

    /** @test */
    public function it_can_check_if_not_late()
    {
        $competition = Competition::factory()->create([
            'submission_deadline' => Carbon::now()->addDays(1),
        ]);
        $registration = Registration::factory()->create([
            'competition_id' => $competition->id,
        ]);
        $submission = Submission::factory()->create([
            'registration_id' => $registration->id,
            'submitted_at' => Carbon::now(),
        ]);

        $this->assertFalse($submission->isOverdue());
    }

    /** @test */
    public function it_can_get_average_score()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
        ]);

        Score::factory()->create([
            'registration_id' => $this->registration->id,
            'criteria_scores' => ['a' => 80, 'b' => 80, 'c' => 80],
            'total_score' => 80,
            'is_final' => true,
        ]);
        Score::factory()->create([
            'registration_id' => $this->registration->id,
            'criteria_scores' => ['a' => 90, 'b' => 90, 'c' => 90],
            'total_score' => 90,
            'is_final' => true,
        ]);

        $average = $submission->getAverageScore();

        $this->assertEquals(85, $average);
    }

    /** @test */
    public function it_returns_zero_when_no_scores()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
        ]);

        $average = $submission->getAverageScore();
        
        $this->assertEquals(0, $average);
    }

    /** @test */
    public function it_can_get_file_url()
    {
        $filename = 'test-file.pdf';
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'files' => [
                [
                    'filename' => $filename,
                    'original_name' => 'test.pdf',
                    'path' => 'submissions/' . $filename,
                    'size' => 1024,
                    'mime_type' => 'application/pdf',
                    'uploaded_at' => now()->toDateTimeString(),
                ]
            ],
        ]);

        $url = $submission->getFileUrl($filename);

        $this->assertStringContainsString($filename, $url);
        $this->assertStringContainsString('download/submission', $url);
    }

    /** @test */
    public function it_scopes_final_submissions()
    {
        Submission::factory()->count(3)->create([
            'registration_id' => $this->registration->id,
            'is_final' => true,
        ]);
        Submission::factory()->count(2)->create([
            'registration_id' => $this->registration->id,
            'is_final' => false,
        ]);

        $finalSubmissions = Submission::final()->get();
        
        $this->assertCount(3, $finalSubmissions);
    }

    /** @test */
    public function it_scopes_draft_submissions()
    {
        Submission::factory()->count(3)->create([
            'registration_id' => $this->registration->id,
            'is_final' => true,
        ]);
        Submission::factory()->count(2)->create([
            'registration_id' => $this->registration->id,
            'is_final' => false,
        ]);

        $draftSubmissions = Submission::draft()->get();
        
        $this->assertCount(2, $draftSubmissions);
    }

    /** @test */
    public function it_can_get_submission_status()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'is_final' => true,
            'submitted_at' => Carbon::now(),
        ]);

        $status = $submission->getStatus();
        
        $this->assertEquals('submitted', $status);
    }

    /** @test */
    public function it_can_check_if_editable()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'is_final' => false,
        ]);

        $this->assertTrue($submission->isEditable());
    }

    /** @test */
    public function it_cannot_edit_final_submission()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'is_final' => true,
        ]);

        $this->assertFalse($submission->isEditable());
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'registration_id',
            'title',
            'description',
            'files',
            'file_size',
            'is_final',
            'submitted_at',
            'status',
            'submission_notes',
        ];

        $submission = new Submission();

        foreach ($fillable as $attribute) {
            $this->assertContains($attribute, $submission->getFillable());
        }
    }

    /** @test */
    public function it_can_get_file_size_formatted()
    {
        $submission = Submission::factory()->create([
            'registration_id' => $this->registration->id,
            'file_size' => 2048000, // 2MB (increased to ensure MB format)
        ]);

        $formatted = $submission->getFileSizeFormatted();

        $this->assertStringContainsString('MB', $formatted);
    }
}

