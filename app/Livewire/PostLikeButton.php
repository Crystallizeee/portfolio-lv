<?php

namespace App\Livewire;

use Livewire\Component;

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
        $ip = request()->ip();
        $like = \App\Models\PostLike::where('post_id', $this->post->id)
                                    ->where('ip_address', $ip)
                                    ->first();

        if ($like) {
            $like->delete();
        } else {
            \App\Models\PostLike::create([
                'post_id' => $this->post->id,
                'ip_address' => $ip,
            ]);
        }

        $this->updateLikeStatus();
    }

    private function updateLikeStatus()
    {
        $this->likesCount = $this->post->likes()->count();
        $this->hasLiked = $this->post->likes()->where('ip_address', request()->ip())->exists();
    }

    public function render()
    {
        return view('livewire.post-like-button');
    }
}
