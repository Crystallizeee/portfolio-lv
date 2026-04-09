<?php

namespace App\Livewire;

use Livewire\Component;

class PostComments extends Component
{
    public $post;
    public $name = '';
    public $content = '';

    protected $rules = [
        'name' => 'required|min:2|max:50',
        'content' => 'required|min:5|max:1000',
    ];

    public function mount($post)
    {
        $this->post = $post;
    }

    public function addComment()
    {
        $this->validate();

        \App\Models\Comment::create([
            'post_id' => $this->post->id,
            'name' => $this->name,
            'content' => $this->content,
        ]);

        $this->name = '';
        $this->content = '';

        session()->flash('message', 'Komentar berhasil ditambahkan.');
    }

    public function render()
    {
        return view('livewire.post-comments', [
            'comments' => $this->post->comments()->latest()->get()
        ]);
    }
}
