<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Experience;

class ManageExperiences extends Component
{
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields
    public string $company = '';
    public string $role = '';
    public string $type = 'GRC';
    public string $date_range = '';
    public string $description = '';
    public int $sort_order = 0;

    protected function rules()
    {
        return [
            'company' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'date_range' => 'required|string|max:100',
            'description' => 'required|string',
            'sort_order' => 'required|integer|min:0',
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
        $experience = Experience::findOrFail($id);
        
        $this->editingId = $experience->id;
        $this->company = $experience->company;
        $this->role = $experience->role;
        $this->type = $experience->type;
        $this->date_range = $experience->date_range;
        $this->description = $experience->description;
        $this->sort_order = $experience->sort_order;
        
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

        $data = [
            'company' => $this->company,
            'role' => $this->role,
            'type' => $this->type,
            'date_range' => $this->date_range,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
        ];

        if ($this->isEditing && $this->editingId) {
            Experience::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Experience berhasil diupdate!');
        } else {
            Experience::create($data);
            session()->flash('message', 'Experience berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function delete(int $id)
    {
        Experience::findOrFail($id)->delete();
        session()->flash('message', 'Experience berhasil dihapus!');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->company = '';
        $this->role = '';
        $this->type = 'GRC';
        $this->date_range = '';
        $this->description = '';
        $this->sort_order = 0;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.manage-experiences', [
            'experiences' => Experience::orderBy('sort_order')->get(),
        ])->layout('layouts.admin', ['title' => 'Manage Experiences']);
    }
}
