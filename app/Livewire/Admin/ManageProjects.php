<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Project;

class ManageProjects extends Component
{
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields
    public string $title = '';
    public string $description = '';
    public string $status = 'online';
    public string $type = '';
    public string $tech_stack = '';
    public string $url = '';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:online,offline',
            'type' => 'required|string|max:100',
            'tech_stack' => 'required|string',
            'url' => 'nullable|url|max:255',
        ];
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
        
        $this->editingId = $project->id;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->status = $project->status;
        $this->type = $project->type;
        $this->tech_stack = is_array($project->tech_stack) 
            ? implode(', ', $project->tech_stack) 
            : $project->tech_stack;
        $this->url = $project->url ?? '';
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        $techStackArray = array_map('trim', explode(',', $this->tech_stack));

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'type' => $this->type,
            'tech_stack' => $techStackArray,
            'url' => $this->url ?: null,
        ];

        if ($this->isEditing && $this->editingId) {
            Project::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Project berhasil diupdate!');
        } else {
            Project::create($data);
            session()->flash('message', 'Project berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function delete(int $id)
    {
        Project::findOrFail($id)->delete();
        session()->flash('message', 'Project berhasil dihapus!');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->description = '';
        $this->status = 'online';
        $this->type = '';
        $this->tech_stack = '';
        $this->url = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.manage-projects', [
            'projects' => Project::orderBy('created_at', 'desc')->get(),
        ])->layout('layouts.admin', ['title' => 'Manage Projects']);
    }
}
