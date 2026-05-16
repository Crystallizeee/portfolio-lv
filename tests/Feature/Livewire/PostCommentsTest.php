<?php

namespace Tests\Feature\Livewire;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostCommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully()
    {
        $user = User::factory()->create();
        $post = Post::create(['title' => 'Test Post', 'slug' => 'test-post', 'content' => 'Content', 'user_id' => $user->id, 'published_at' => now()]);
        Livewire::test(\App\Livewire\PostComments::class, ['post' => $post])
            ->assertStatus(200);
    }
}
