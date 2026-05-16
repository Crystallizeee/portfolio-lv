<?php

namespace Tests\Feature;

use App\Models\User;
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
        $user = User::factory()->create();
        \App\Models\JobProfile::create(['user_id' => $user->id, 'name' => 'Test', 'slug' => 'test-profile', 'is_landing_page' => true, 'about_grc_list' => [], 'about_tech_list' => []]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
