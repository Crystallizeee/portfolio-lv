<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

class ManageLanguages extends Component
{
    public $languages = [];
    public $showModal = false;
    public $editingId = null;

    public $form = [
        'name' => '',
        'level' => '',
    ];

    public function mount()
    {
        $this->loadLanguages();
    }

    public function loadLanguages()
    {
        $this->languages = Language::where('user_id', Auth::id())
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->form = [
            'name' => '',
            'level' => '',
        ];
    }

    public function edit($id)
    {
        $lang = Language::find($id);
        $this->editingId = $id;
        $this->form = [
            'name' => $lang->name,
            'level' => $lang->level,
        ];
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.level' => 'required|string|max:100',
        ]);

        if ($this->editingId) {
            Language::find($this->editingId)->update([
                'name' => $this->form['name'],
                'level' => $this->form['level'],
            ]);
            session()->flash('success', 'Language updated successfully!');
        } else {
            Language::create([
                'user_id' => Auth::id(),
                'name' => $this->form['name'],
                'level' => $this->form['level'],
                'sort_order' => count($this->languages),
            ]);
            session()->flash('success', 'Language added successfully!');
        }

        $this->closeModal();
        $this->loadLanguages();
    }

    public function delete($id)
    {
        Language::find($id)->delete();
        $this->loadLanguages();
        session()->flash('success', 'Language deleted successfully!');
    }

    public function render()
    {
        return view('livewire.admin.manage-languages')
            ->layout('layouts.admin', ['title' => 'Manage Languages']);
    }
}
