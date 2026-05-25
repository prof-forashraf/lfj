<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_returns_healthy()
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'healthy',
        ]);
        $this->assertArrayHasKey('services', $response->json());
        $this->assertArrayHasKey('system_health', $response->json());
    }
}
