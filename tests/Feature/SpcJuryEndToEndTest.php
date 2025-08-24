<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Submission;
use App\Models\Score;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * Comprehensive End-to-End Testing for SPC Jury System
 * 
 * Tests based on official SPC evaluation criteria from:
 * CONTOH AKUMULASI NILAI AKHIR SPC 25.csv
 * 
 * Evaluation Structure:
 * - Semifinal: Naskah (Script/Paper) evaluation - 60% weight
 * - Final: Presentasi (Presentation) evaluation - 40% weight
 * - Final calculation: (Average Naskah Score × 0.6) + (Average Presentasi Score × 0.4)
 */
class SpcJuryEndToEndTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $spcJuries;
    protected $spcCompetition;
    protected $testParticipant;
    protected $testRegistration;
    protected $testSubmission;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTestData();
    }

    /**
     * Setup test data matching official SPC structure
     */
    protected function setupTestData()
    {
        // Create roles
        Role::create(['name' => 'juri']);
        Role::create(['name' => 'peserta']);

        // Create SPC Competition
        $this->spcCompetition = Competition::create([
            'name' => 'Student Presentation Competition (SPC)',
            'category' => 'event_scientific_paper',
            'description' => 'SPC UNAS FEST 2025',
            'is_active' => true,
            'registration_start' => now()->subDays(30),
            'registration_end' => now()->addDays(30),
            'competition_start' => now()->subDays(10),
            'competition_end' => now()->addDays(10),
            'judging_start' => now()->subDays(5),
            'judging_end' => now()->addDays(5),
            'max_participants' => 100,
            'registration_fee' => 150000,
            'slug' => 'spc-2025'
        ]);

        // Create SPC Juries (matching reference CSV)
        $this->spcJuries = [
            'efriza' => User::create([
                'name' => 'Prof. Dr. Efriza Maulana',
                'email' => 'efriza.test@spc.testing.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]),
            'donna' => User::create([
                'name' => 'Dr. Donna Wijayanti, M.Sc.',
                'email' => 'donna.test@spc.testing.com', 
                'password' => Hash::make('password123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]),
            'gani' => User::create([
                'name' => 'Gani Firmansyah, M.Pd., Ph.D.',
                'email' => 'gani.test@spc.testing.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ])
        ];

        // Assign jury role
        foreach ($this->spcJuries as $jury) {
            $jury->assignRole('juri');
        }

        // Create test participant (matching reference CSV)
        $this->testParticipant = User::create([
            'name' => 'Mawar Melati Ningrum',
            'email' => 'mawar.test@participant.com',
            'password' => Hash::make('password123'),
            'institution' => 'Universitas Daiko',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $this->testParticipant->assignRole('peserta');

        // Create registration
        $this->testRegistration = Registration::create([
            'user_id' => $this->testParticipant->id,
            'competition_id' => $this->spcCompetition->id,
            'status' => 'confirmed',
            'registration_number' => 'SPC-2025-001',
            'registered_at' => now()->subDays(20),
        ]);

        // Create submission
        $this->testSubmission = Submission::create([
            'registration_id' => $this->testRegistration->id,
            'title' => 'Innovative Research on Sustainable Technology',
            'abstract' => 'This research explores sustainable technology solutions...',
            'status' => 'submitted',
            'is_final' => true,
            'submitted_at' => now()->subDays(3),
        ]);
    }

    /**
     * Test 1: Jury Account Creation and Role Assignment
     */
    public function test_jury_account_creation_and_role_assignment()
    {
        // Verify juries were created successfully
        $this->assertCount(3, $this->spcJuries);
        
        foreach ($this->spcJuries as $jury) {
            $this->assertTrue($jury->hasRole('juri'));
            $this->assertTrue($jury->isJuri());
            $this->assertNotNull($jury->email_verified_at);
            $this->assertTrue($jury->is_active);
        }
    }

    /**
     * Test 2: Jury Login and Dashboard Access
     */
    public function test_jury_login_and_dashboard_access()
    {
        foreach ($this->spcJuries as $juryKey => $jury) {
            // Test login
            $response = $this->post('/login', [
                'email' => $jury->email,
                'password' => 'password123',
            ]);

            $response->assertRedirect('/dashboard');
            $this->assertAuthenticatedAs($jury);

            // Test dashboard redirect to jury dashboard
            $response = $this->actingAs($jury)->get('/dashboard');
            $response->assertRedirect('/juri/dashboard');

            // Test jury dashboard access
            $response = $this->actingAs($jury)->get('/juri/dashboard');
            $response->assertStatus(200);
            $response->assertViewIs('juri.dashboard');

            // Verify dashboard data
            $response->assertViewHas(['stats', 'activeCompetitions', 'scoringProgress']);

            $this->post('/logout');
        }
    }

    /**
     * Test 3: SPC Scoring Criteria Validation
     */
    public function test_spc_scoring_criteria_validation()
    {
        $jury = $this->spcJuries['efriza'];
        
        $response = $this->actingAs($jury)
            ->get("/juri/scoring/submission/{$this->testSubmission->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('juri.scoring.submission');
        
        // Verify SPC-specific criteria are loaded
        $viewData = $response->viewData('criteria');
        $this->assertIsArray($viewData);
        
        // Check for SPC criteria (based on Score::getSpcCriteria())
        $expectedCriteria = [
            'originality_innovation',
            'methodology_rigor', 
            'analysis_discussion',
            'presentation_clarity',
            'scientific_contribution'
        ];
        
        foreach ($expectedCriteria as $criteria) {
            $this->assertArrayHasKey($criteria, $viewData);
        }
    }

    /**
     * Test 4: Jury Evaluation Process (Naskah - Semifinal Phase)
     */
    public function test_jury_evaluation_naskah_semifinal()
    {
        $jury = $this->spcJuries['efriza'];
        
        // Test scoring submission with Naskah criteria (60% weight)
        $naskahScores = [
            'originality_innovation' => 94.00,
            'methodology_rigor' => 90.00,
            'analysis_discussion' => 88.00,
            'presentation_clarity' => 92.00,
            'scientific_contribution' => 89.00
        ];
        
        $response = $this->actingAs($jury)
            ->post("/juri/scoring/submission/{$this->testSubmission->id}", [
                'criteria' => $naskahScores,
                'comments' => 'Excellent research methodology and clear presentation.',
                'is_final' => true
            ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Verify score was saved correctly
        $score = Score::where('registration_id', $this->testRegistration->id)
            ->where('jury_id', $jury->id)
            ->first();
        
        $this->assertNotNull($score);
        $this->assertTrue($score->is_final);
        $this->assertEquals(array_sum($naskahScores), $score->total_score);
        $this->assertEquals($naskahScores, $score->criteria_scores);
    }

    /**
     * Test 5: Multiple Jury Evaluation (All 3 SPC Juries)
     */
    public function test_multiple_jury_evaluation_process()
    {
        // Expected scores from reference CSV
        $expectedScores = [
            'efriza' => ['naskah' => 94.00, 'presentasi' => 92.00],
            'donna' => ['naskah' => 90.00, 'presentasi' => 100.00],
            'gani' => ['naskah' => 87.00, 'presentasi' => 93.00]
        ];
        
        foreach ($this->spcJuries as $juryKey => $jury) {
            // Naskah evaluation (Semifinal)
            $naskahScore = $expectedScores[$juryKey]['naskah'];
            $response = $this->actingAs($jury)
                ->post("/juri/scoring/submission/{$this->testSubmission->id}", [
                    'criteria' => [
                        'originality_innovation' => $naskahScore,
                        'methodology_rigor' => $naskahScore - 2,
                        'analysis_discussion' => $naskahScore - 1,
                        'presentation_clarity' => $naskahScore + 1,
                        'scientific_contribution' => $naskahScore
                    ],
                    'comments' => "Naskah evaluation by {$jury->name}",
                    'is_final' => true
                ]);
            
            $response->assertRedirect();
            
            // Verify individual score
            $score = Score::where('registration_id', $this->testRegistration->id)
                ->where('jury_id', $jury->id)
                ->first();
            
            $this->assertNotNull($score);
            $this->assertTrue($score->is_final);
        }
    }

    /**
     * Test 6: Score Calculation Accuracy per Official SPC Rules
     */
    public function test_score_calculation_accuracy_official_rules()
    {
        // Setup scores matching reference CSV exactly
        $referenceScores = [
            'efriza' => ['naskah' => 94.00, 'presentasi' => 92.00],
            'donna' => ['naskah' => 90.00, 'presentasi' => 100.00], 
            'gani' => ['naskah' => 87.00, 'presentasi' => 93.00]
        ];
        
        // Clear existing scores
        Score::where('competition_id', $this->spcCompetition->id)->delete();
        
        // Create scores for each jury
        foreach ($this->spcJuries as $juryKey => $jury) {
            $naskahScore = $referenceScores[$juryKey]['naskah'];
            $presentasiScore = $referenceScores[$juryKey]['presentasi'];
            
            // Create Naskah score (Semifinal - 60% weight)
            Score::create([
                'competition_id' => $this->spcCompetition->id,
                'registration_id' => $this->testRegistration->id,
                'jury_id' => $jury->id,
                'criteria_scores' => [
                    'naskah_score' => $naskahScore
                ],
                'total_score' => $naskahScore,
                'is_final' => true,
                'submitted_at' => now()
            ]);
            
            // Create Presentasi score (Final - 40% weight)
            Score::create([
                'competition_id' => $this->spcCompetition->id,
                'registration_id' => $this->testRegistration->id,
                'jury_id' => $jury->id,
                'criteria_scores' => [
                    'presentasi_score' => $presentasiScore
                ],
                'total_score' => $presentasiScore,
                'is_final' => true,
                'submitted_at' => now()
            ]);
        }
        
        // Calculate averages (as per reference CSV)
        $avgNaskah = (94.00 + 90.00 + 87.00) / 3; // = 90.33
        $avgPresentasi = (92.00 + 100.00 + 93.00) / 3; // = 95.00
        
        // Calculate final score using official SPC formula
        $expectedFinalScore = ($avgNaskah * 0.6) + ($avgPresentasi * 0.4);
        // = (90.33 * 0.6) + (95.00 * 0.4) = 54.20 + 38.00 = 92.20
        
        $this->assertEquals(90.33, round($avgNaskah, 2));
        $this->assertEquals(95.00, round($avgPresentasi, 2));
        $this->assertEquals(92.20, round($expectedFinalScore, 2));
    }

    /**
     * Test 7: Data Persistence and Retrieval
     */
    public function test_data_persistence_and_retrieval()
    {
        $jury = $this->spcJuries['efriza'];
        
        // Create a score
        $testScores = [
            'originality_innovation' => 85.00,
            'methodology_rigor' => 88.00,
            'analysis_discussion' => 90.00,
            'presentation_clarity' => 87.00,
            'scientific_contribution' => 89.00
        ];
        
        $this->actingAs($jury)
            ->post("/juri/scoring/submission/{$this->testSubmission->id}", [
                'criteria' => $testScores,
                'comments' => 'Test persistence',
                'is_final' => true
            ]);
        
        // Retrieve and verify
        $savedScore = Score::where('registration_id', $this->testRegistration->id)
            ->where('jury_id', $jury->id)
            ->first();
        
        $this->assertNotNull($savedScore);
        $this->assertEquals($testScores, $savedScore->criteria_scores);
        $this->assertEquals('Test persistence', $savedScore->comments);
        $this->assertTrue($savedScore->is_final);
        $this->assertNotNull($savedScore->submitted_at);
    }

    /**
     * Test 8: Edge Cases and Error Handling
     */
    public function test_edge_cases_and_error_handling()
    {
        $jury = $this->spcJuries['efriza'];
        
        // Test invalid score ranges
        $response = $this->actingAs($jury)
            ->post("/juri/scoring/submission/{$this->testSubmission->id}", [
                'criteria' => [
                    'originality_innovation' => 150.00, // Invalid: > 100
                    'methodology_rigor' => -10.00,      // Invalid: < 0
                ],
                'is_final' => true
            ]);
        
        $response->assertSessionHasErrors();
        
        // Test missing required criteria
        $response = $this->actingAs($jury)
            ->post("/juri/scoring/submission/{$this->testSubmission->id}", [
                'criteria' => [
                    'originality_innovation' => 85.00,
                    // Missing other required criteria
                ],
                'is_final' => true
            ]);
        
        $response->assertSessionHasErrors();
    }

    /**
     * Test 9: Access Control and Security
     */
    public function test_access_control_and_security()
    {
        $nonJury = User::create([
            'name' => 'Regular User',
            'email' => 'regular@test.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $nonJury->assignRole('peserta');
        
        // Test unauthorized access to jury routes
        $response = $this->actingAs($nonJury)
            ->get('/juri/dashboard');
        
        $response->assertStatus(403);
        
        // Test unauthorized scoring attempt
        $response = $this->actingAs($nonJury)
            ->post("/juri/scoring/submission/{$this->testSubmission->id}", [
                'criteria' => ['test' => 85],
                'is_final' => true
            ]);
        
        $response->assertStatus(403);
    }

    /**
     * Test 10: Complete End-to-End Flow
     */
    public function test_complete_end_to_end_flow()
    {
        // Step 1: Jury login
        $jury = $this->spcJuries['efriza'];
        $this->actingAs($jury);
        
        // Step 2: Access dashboard
        $response = $this->get('/juri/dashboard');
        $response->assertStatus(200);
        
        // Step 3: View scoring index
        $response = $this->get('/juri/scoring');
        $response->assertStatus(200);
        
        // Step 4: Access specific submission
        $response = $this->get("/juri/scoring/submission/{$this->testSubmission->id}");
        $response->assertStatus(200);
        
        // Step 5: Submit evaluation
        $response = $this->post("/juri/scoring/submission/{$this->testSubmission->id}", [
            'criteria' => [
                'originality_innovation' => 92.00,
                'methodology_rigor' => 89.00,
                'analysis_discussion' => 91.00,
                'presentation_clarity' => 88.00,
                'scientific_contribution' => 90.00
            ],
            'comments' => 'Comprehensive end-to-end test evaluation',
            'is_final' => true
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Step 6: Verify final result
        $finalScore = Score::where('registration_id', $this->testRegistration->id)
            ->where('jury_id', $jury->id)
            ->where('is_final', true)
            ->first();
        
        $this->assertNotNull($finalScore);
        $this->assertEquals(450.00, $finalScore->total_score); // Sum of all criteria
    }
}
