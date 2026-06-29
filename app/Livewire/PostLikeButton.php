<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\IpAnonymizer;
use Illuminate\Support\Facades\RateLimiter;

class PostLikeButton extends Component
{
    public $post;
    public $likesCount;
    public $hasLiked;

    public function mount($post)
    {
        $this->post = $post;
        $this->updateLikeStatus();
    }

    public function toggleLike()
    {
        $ipHash = IpAnonymizer::hashRequest();

        // Rate limiting: max 10 like toggles per IP per minute
        $rateLimitKey = 'like:' . $ipHash;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return;
        }
        RateLimiter::hit($rateLimitKey, 60);

        $like = \App\Models\PostLike::where('post_id', $this->post->id)
                                    ->where('ip_hash', $ipHash)
                                    ->first();

        if ($like) {
            $like->delete();
        } else {
            \App\Models\PostLike::create([
                'post_id' => $this->post->id,
                'ip_hash' => $ipHash,
            ]);
        }

        $this->updateLikeStatus();
    }

    private function updateLikeStatus()
    {
        $this->likesCount = $this->post->likes()->count();
        $this->hasLiked = $this->post->likes()->where('ip_hash', IpAnonymizer::hashRequest())->exists();
    }

    public function render()
    {
        return view('livewire.post-like-button');
    }
}
