<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\CompetitionCategory;
use App\Models\CompetitionRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompetitionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function competition_has_fillable_attributes()
    {
        $competition = new Competition();
        
        $this->assertContains('name', $competition->getFillable());
        $this->assertContains('slug', $competition->getFillable());
        $this->assertContains('description', $competition->getFillable());
        $this->assertContains('status', $competition->getFillable());
    }

    /** @test */
    public function competition_has_registrations_relationship()
    {
        $competition = Competition::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $competition->registrations()
        );
    }

    /** @test */
    public function competition_slug_is_unique()
    {
        Competition::factory()->create(['slug' => 'spc-2025']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Competition::factory()->create(['slug' => 'spc-2025']);
    }

    /** @test */
    public function competition_uses_soft_deletes()
    {
        $competition = Competition::factory()->create();
        $competition->delete();

        $this->assertSoftDeleted('competitions', ['id' => $competition->id]);
        
        // Can still find with trashed
        $this->assertNotNull(Competition::withTrashed()->find($competition->id));
    }

    /** @test */
    public function competition_casts_dates_correctly()
    {
        $competition = Competition::factory()->create([
            'registration_start' => now(),
            'registration_end' => now()->addDays(30),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $competition->registration_start);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $competition->registration_end);
    }

    /** @test */
    public function competition_can_have_multiple_registrations()
    {
        $competition = Competition::factory()->create();
        
        Registration::factory()->count(3)->create([
            'competition_id' => $competition->id,
        ]);

        $this->assertCount(3, $competition->registrations);
    }

    /** @test */
    public function competition_has_category_attribute()
    {
        $competition = Competition::factory()->create(['category' => 'event_dcc']);

        $this->assertEquals('event_dcc', $competition->category);
    }

    /** @test */
    public function competition_has_type_attribute()
    {
        $competition = Competition::factory()->create(['type' => 'team']);

        $this->assertEquals('team', $competition->type);
    }

    /** @test */
    public function competition_status_can_be_active_or_inactive()
    {
        $activeCompetition = Competition::factory()->create(['status' => 'active']);
        $inactiveCompetition = Competition::factory()->create(['status' => 'inactive']);

        $this->assertEquals('active', $activeCompetition->status);
        $this->assertEquals('inactive', $inactiveCompetition->status);
    }
}

