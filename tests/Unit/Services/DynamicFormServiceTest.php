<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\DynamicFormService;
use App\Models\Competition;
use App\Models\CompetitionRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DynamicFormServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $competition;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new DynamicFormService();
        $this->competition = Competition::factory()->create();
    }

    /** @test */
    public function it_can_get_competition_requirements()
    {
        CompetitionRequirement::factory()->count(3)->create([
            'competition_id' => $this->competition->id,
            'field_group' => 'personal_info',
        ]);

        $requirements = $this->service->getCompetitionRequirements($this->competition);
        
        $this->assertNotEmpty($requirements);
        $this->assertArrayHasKey('personal_info', $requirements->toArray());
    }

    /** @test */
    public function it_groups_requirements_by_field_group()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_group' => 'personal_info',
            'field_name' => 'name',
        ]);
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_group' => 'team_info',
            'field_name' => 'team_name',
        ]);

        $requirements = $this->service->getCompetitionRequirements($this->competition);
        
        $this->assertArrayHasKey('personal_info', $requirements->toArray());
        $this->assertArrayHasKey('team_info', $requirements->toArray());
    }

    /** @test */
    public function it_builds_validation_rules_for_required_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'name',
            'field_type' => 'text',
            'is_required' => true,
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertArrayHasKey('name', $rules);
        $this->assertStringContainsString('required', $rules['name']);
    }

    /** @test */
    public function it_builds_validation_rules_for_optional_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'nickname',
            'field_type' => 'text',
            'is_required' => false,
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertArrayHasKey('nickname', $rules);
        $this->assertStringContainsString('nullable', $rules['nickname']);
    }

    /** @test */
    public function it_adds_email_validation_for_email_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'email',
            'field_type' => 'email',
            'is_required' => true,
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertStringContainsString('email', $rules['email']);
    }

    /** @test */
    public function it_adds_numeric_validation_for_number_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'age',
            'field_type' => 'number',
            'is_required' => true,
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertStringContainsString('numeric', $rules['age']);
    }

    /** @test */
    public function it_adds_url_validation_for_url_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'website',
            'field_type' => 'url',
            'is_required' => true,
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertStringContainsString('url', $rules['website']);
    }

    /** @test */
    public function it_adds_file_validation_for_file_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'document',
            'field_type' => 'file',
            'is_required' => true,
            'validation_rules' => json_encode([
                'max_size' => 2048,
                'mimes' => ['pdf', 'doc', 'docx']
            ]),
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertStringContainsString('file', $rules['document']);
        $this->assertStringContainsString('max:2048', $rules['document']);
        $this->assertStringContainsString('mimes:pdf,doc,docx', $rules['document']);
    }

    /** @test */
    public function it_adds_max_length_validation_for_text_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'description',
            'field_type' => 'textarea',
            'is_required' => true,
            'validation_rules' => json_encode([
                'max_length' => 500
            ]),
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertStringContainsString('max:500', $rules['description']);
    }

    /** @test */
    public function it_adds_in_validation_for_select_fields()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'category',
            'field_type' => 'select',
            'is_required' => true,
            'field_options' => json_encode([
                'option1' => 'Option 1',
                'option2' => 'Option 2',
                'option3' => 'Option 3',
            ]),
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertStringContainsString('in:option1,option2,option3', $rules['category']);
    }

    /** @test */
    public function it_validates_checkbox_fields_as_array()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'interests',
            'field_type' => 'checkbox',
            'is_required' => false,
            'field_options' => json_encode([
                'sports' => 'Sports',
                'music' => 'Music',
                'art' => 'Art',
            ]),
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertStringContainsString('array', $rules['interests']);
        $this->assertArrayHasKey('interests.*', $rules);
    }

    /** @test */
    public function it_validates_data_against_competition_requirements()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'name',
            'field_type' => 'text',
            'is_required' => true,
        ]);
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'email',
            'field_type' => 'email',
            'is_required' => true,
        ]);

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $result = $this->service->validateData($this->competition, $data);
        
        $this->assertTrue($result);
    }

    /** @test */
    public function it_throws_validation_exception_for_invalid_data()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'email',
            'field_type' => 'email',
            'is_required' => true,
        ]);

        $data = [
            'email' => 'invalid-email', // Invalid email format
        ];

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        $this->service->validateData($this->competition, $data);
    }

    /** @test */
    public function it_handles_empty_requirements()
    {
        $requirements = $this->service->getCompetitionRequirements($this->competition);
        
        $this->assertEmpty($requirements);
    }

    /** @test */
    public function it_orders_requirements_by_order_index()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_group' => 'info',
            'field_name' => 'field3',
            'order_index' => 3,
        ]);
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_group' => 'info',
            'field_name' => 'field1',
            'order_index' => 1,
        ]);
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_group' => 'info',
            'field_name' => 'field2',
            'order_index' => 2,
        ]);

        $requirements = $this->service->getCompetitionRequirements($this->competition);
        
        $infoRequirements = $requirements['info'];
        $this->assertEquals('field1', $infoRequirements[0]->field_name);
        $this->assertEquals('field2', $infoRequirements[1]->field_name);
        $this->assertEquals('field3', $infoRequirements[2]->field_name);
    }

    /** @test */
    public function it_handles_json_encoded_validation_rules()
    {
        CompetitionRequirement::factory()->create([
            'competition_id' => $this->competition->id,
            'field_name' => 'document',
            'field_type' => 'file',
            'validation_rules' => '{"max_size":2048,"mimes":["pdf"]}', // JSON string
        ]);

        $rules = $this->service->buildValidationRules($this->competition);
        
        $this->assertArrayHasKey('document', $rules);
        $this->assertStringContainsString('max:2048', $rules['document']);
    }
}

