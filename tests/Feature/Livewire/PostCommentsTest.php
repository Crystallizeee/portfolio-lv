<?php

namespace Tests\Feature\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class PostCommentsTest extends TestCase
{
    public function test_renders_successfully()
    {
        $user = User::first() ?? User::create(['name' => 'Test User', 'email' => 'test@test.com', 'password' => 'password']);
        $post = Post::create(['title' => 'Test Post', 'slug' => 'test-post', 'content' => 'Content', 'user_id' => $user->id, 'published_at' => now()]);
        Livewire::test(\App\Livewire\PostComments::class, ['post' => $post])
            ->assertStatus(200);
    }
}
