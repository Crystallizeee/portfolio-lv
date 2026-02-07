<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ManagePosts extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;
    public $deleteId = null;
    public $showDeleteModal = false;

    // Form fields
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $status = 'draft';
    public $published_at = '';
    
    // Image Upload
    public $featured_image = ''; // Existing image URL/Path
    public $new_featured_image = null; // Temporary upload

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $this->editingId,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'new_featured_image' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function updatedTitle($value)
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $post = Post::findOrFail($id);
        
        $this->editingId = $id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->status = $post->status;
        $this->published_at = $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '';
        $this->featured_image = $post->featured_image;

        $this->isEditing = true;
        $this->showModal = true;
        
        // Dispatch event to refresh EasyMDE content
        $this->dispatch('refresh-markdown', content: $this->content);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->published_at ?: null,
        ];

        // Handle Image Upload
        if ($this->new_featured_image) {
            $path = $this->new_featured_image->store('posts', 'public');
            $data['featured_image'] = Storage::url($path);

            // Delete old image if editing and replacing
            if ($this->isEditing && $this->featured_image) {
                $oldPath = str_replace('/storage/', '', $this->featured_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        if ($this->isEditing && $this->editingId) {
            Post::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Post updated successfully!');
        } else {
            Post::create($data);
            session()->flash('message', 'Post created successfully!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        
        // Delete image if exists
        if ($post->featured_image) {
            $oldPath = str_replace('/storage/', '', $post->featured_image);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
        
        $post->delete();
        session()->flash('message', 'Post deleted successfully!');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->excerpt = '';
        $this->content = '';
        $this->status = 'draft';
        $this->published_at = '';
        $this->featured_image = '';
        $this->new_featured_image = null;
        $this->resetErrorBag();
        
        // Reset EasyMDE
        $this->dispatch('refresh-markdown', content: '');
    }

    public function render()
    {
        return view('livewire.admin.manage-posts', [
            'posts' => Post::orderBy('created_at', 'desc')->paginate(10),
        ])->layout('layouts.admin', ['title' => 'Manage Posts']);
    }
}
