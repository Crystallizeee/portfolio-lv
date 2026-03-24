<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CvDownloadApiTest extends TestCase
{
    use RefreshDatabase;

    private string $validToken = 'test-api-token-123';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.cv_api.token' => $this->validToken]);
    }

    public function test_returns_401_without_token(): void
    {
        $response = $this->getJson('/api/cv/download');

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_returns_401_with_invalid_token(): void
    {
        $response = $this->getJson('/api/cv/download', [
            'Authorization' => 'Bearer wrong-token',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_returns_pdf_with_valid_token(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123456',
            'address' => 'Test Address',
            'linkedin' => 'linkedin.com/in/test',
            'github' => 'github.com/test',
            'website' => 'test.com',
            'summary' => 'A test summary',
        ]);

        $response = $this->getJson('/api/cv/download', [
            'Authorization' => 'Bearer ' . $this->validToken,
        ]);

        $response->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_supports_locale_parameter(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->getJson('/api/cv/download?locale=id', [
            'Authorization' => 'Bearer ' . $this->validToken,
        ]);

        $response->assertStatus(200);
    }
}
