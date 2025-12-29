<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Skill;

class ManageSkills extends Component
{
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields
    public string $name = '';
    public string $category = '';
    public int $level = 80;
    public string $icon = '';
    public int $sort_order = 0;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'level' => 'required|integer|min:0|max:100',
            'icon' => 'nullable|string|max:50',
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
        $skill = Skill::findOrFail($id);
        
        $this->editingId = $skill->id;
        $this->name = $skill->name;
        $this->category = $skill->category;
        $this->level = $skill->level;
        $this->icon = $skill->icon ?? '';
        $this->sort_order = $skill->sort_order;
        
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
            'name' => $this->name,
            'category' => $this->category,
            'level' => $this->level,
            'icon' => $this->icon ?: null,
            'sort_order' => $this->sort_order,
        ];

        if ($this->isEditing && $this->editingId) {
            Skill::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Skill berhasil diupdate!');
        } else {
            Skill::create($data);
            session()->flash('message', 'Skill berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function delete(int $id)
    {
        Skill::findOrFail($id)->delete();
        session()->flash('message', 'Skill berhasil dihapus!');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->category = '';
        $this->level = 80;
        $this->icon = '';
        $this->sort_order = 0;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.manage-skills', [
            'skills' => Skill::orderBy('category')->orderBy('sort_order')->get(),
        ])->layout('layouts.admin', ['title' => 'Manage Skills']);
    }
}
