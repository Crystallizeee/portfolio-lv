<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Require the user seeder to create the portfolio owner
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
