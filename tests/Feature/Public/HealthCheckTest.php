<?php

namespace Tests\Feature\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test health check endpoint returns healthy status
     */
    public function test_health_check_returns_healthy_status()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'healthy',
        ]);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'services' => [
                'database',
                'storage',
            ],
        ]);
    }

    /**
     * Test health check includes database status
     */
    public function test_health_check_includes_database_status()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertJsonPath('services.database', 'healthy');
    }

    /**
     * Test health check includes storage status
     */
    public function test_health_check_includes_storage_status()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertJsonPath('services.storage', 'healthy');
    }

    /**
     * Test health check includes timestamp
     */
    public function test_health_check_includes_timestamp()
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertJsonStructure(['timestamp']);
    }

    /**
     * Test sitemap XML endpoint returns XML
     */
    public function test_sitemap_returns_xml()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    /**
     * Test robots.txt endpoint returns text
     */
    public function test_robots_txt_returns_text()
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Test CSRF token endpoint returns token
     */
    public function test_csrf_token_endpoint_returns_token()
    {
        $response = $this->get('/csrf-token');

        $response->assertStatus(200);
        $response->assertJsonStructure(['csrf_token']);
    }

    /**
     * Test CSRF token is valid
     */
    public function test_csrf_token_is_valid()
    {
        $response = $this->get('/csrf-token');

        $response->assertStatus(200);
        $token = $response->json('csrf_token');
        
        $this->assertNotEmpty($token);
        $this->assertIsString($token);
    }
}

