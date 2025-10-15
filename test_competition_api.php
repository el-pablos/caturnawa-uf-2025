<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompetitionControllerDebugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function debug_api_response()
    {
        Competition::factory()->count(3)->create();

        $response = $this->getJson('/api/competitions');
        
        echo "\n\nStatus: " . $response->getStatusCode() . "\n";
        echo "Content: " . $response->getContent() . "\n\n";
        
        $response->assertStatus(200);
    }
}

