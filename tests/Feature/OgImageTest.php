<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class OgImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_og_image_generation_with_path_traversal_is_sanitized()
    {
        $type = "post..";
        $slug = "test_traversal";
        $response = $this->get("/og-image/{$type}/{$slug}");
        $response->assertStatus(404);
    }

    public function test_og_image_generation_with_malicious_type_defaults_safely()
    {
        $type = "invalid!!!";
        $slug = "anything";
        $response = $this->get("/og-image/{$type}/{$slug}");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_og_image_generation_with_slug_sanitization()
    {
        $userId = DB::table('users')->insertGetId([
            'name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('posts')->insert([
            'user_id' => $userId, 'slug' => 'test-post', 'title' => 'Test Post',
            'content' => 'Test Content', 'published_at' => now(),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // "test-post!!!" sanitizes to "test-post"
        $response = $this->get("/og-image/post/test-post!!!");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }
}
