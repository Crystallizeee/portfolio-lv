<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ManageProjects extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;
    public bool $showDeleteModal = false;

    // Form fields
    public string $title = '';
    public string $slug = '';
    public string $description = '';
    public string $challenge = '';
    public string $solution = '';
    public string $results = '';
    public string $status = 'online';
    public string $type = 'Personal';
    public string $tech_stack = ''; 
    public array $gallery = []; // Array of image URLs
    public string $url = '';
    
    // Uploads
    public $new_gallery_images = [];

    // SEO Fields
    public string $seo_title = '';
    public string $seo_description = '';
    public string $seo_keywords = '';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . $this->editingId,
            'description' => 'required|string',
            'challenge' => 'nullable|string',
            'solution' => 'nullable|string',
            'results' => 'nullable|string',
            'status' => 'required|in:online,offline,maintenance',
            'type' => 'required|string',
            'tech_stack' => 'required|string',
            'url' => 'nullable|url',
            'gallery' => 'nullable|array',
            'new_gallery_images.*' => 'image|max:2048', // Validation for each image
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
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

    public function openEditModal(int $id)
    {
        $project = Project::findOrFail($id);
        
        $this->editingId = $id;
        $this->title = $project->title;
        $this->slug = $project->slug ?? '';
        $this->description = $project->description;
        $this->challenge = $project->challenge ?? '';
        $this->solution = $project->solution ?? '';
        $this->results = $project->results ?? '';
        $this->status = $project->status;
        $this->type = $project->type;
        $this->tech_stack = is_array($project->tech_stack) 
            ? implode(', ', $project->tech_stack) 
            : $project->tech_stack;
        $this->gallery = is_array($project->gallery)
            ? $project->gallery
            : ($project->gallery ? explode(',', $project->gallery) : []);
        $this->url = $project->url ?? '';

        // Load SEO
        $this->seo_title = $project->seo->title ?? '';
        $this->seo_description = $project->seo->description ?? '';
        $this->seo_keywords = $project->seo->keywords ?? '';
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function removeGalleryImage($index)
    {
        if (isset($this->gallery[$index])) {
            // Optional: Delete file from storage if you want to clean up immediately
            // But usually safer to keep until save, or just remove from array
            // If it's a URL in storage, maybe delete? 
            // For now, just remove from the list. 
            // If we want to delete valid storage files:
            /*
            $path = str_replace('/storage/', '', $this->gallery[$index]);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            */
            unset($this->gallery[$index]);
            $this->gallery = array_values($this->gallery); // Re-index array
        }
    }

    public function save()
    {
        $this->validate();
        
        // Handle new gallery uploads
        if (!empty($this->new_gallery_images)) {
            foreach ($this->new_gallery_images as $image) {
                $path = $image->store('projects', 'public');
                $this->gallery[] = Storage::url($path);
            }
        }

        $techStackArray = array_map('trim', explode(',', $this->tech_stack));

        $data = [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'description' => $this->description,
            'challenge' => $this->challenge,
            'solution' => $this->solution,
            'results' => $this->results,
            'status' => $this->status,
            'type' => $this->type,
            'tech_stack' => $techStackArray,
            'gallery' => $this->gallery, // Save array directly (casted in Model)
            'url' => $this->url ?: null,
        ];

        if ($this->isEditing && $this->editingId) {
            $project = Project::findOrFail($this->editingId);
            $project->update($data);
            $project->seo()->updateOrCreate(
                [],
                [
                    'title' => $this->seo_title,
                    'description' => $this->seo_description,
                    'keywords' => $this->seo_keywords,
                ]
            );
            session()->flash('message', 'Project updated successfully!');
        } else {
            $project = Project::create($data);
            $project->seo()->create([
                'title' => $this->seo_title,
                'description' => $this->seo_description,
                'keywords' => $this->seo_keywords,
            ]);
            session()->flash('message', 'Project created successfully!');
        }

        $this->closeModal();
    }

    public function delete(int $id)
    {
        $project = Project::findOrFail($id);
        
        // Delete gallery images
        if ($project->gallery) {
            foreach ($project->gallery as $image) {
                $path = str_replace('/storage/', '', $image);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        
        $project->delete();
        session()->flash('message', 'Project deleted successfully!');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->description = '';
        $this->challenge = '';
        $this->solution = '';
        $this->results = '';
        $this->status = 'online';
        $this->type = '';
        $this->tech_stack = '';
        $this->gallery = [];
        $this->url = '';
        $this->seo_title = '';
        $this->seo_description = '';
        $this->seo_keywords = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.manage-projects', [
            'projects' => Project::orderBy('created_at', 'desc')->get(),
        ])->layout('layouts.admin', ['title' => 'Manage Projects']);
    }
}
