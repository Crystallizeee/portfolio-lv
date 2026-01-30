<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class ManageCertificates extends Component
{
    public $certificates = [];
    public $showModal = false;
    public $editingId = null;

    public $form = [
        'name' => '',
        'issuer' => '',
        'year' => '',
        'credential_id' => '',
        'credential_url' => '',
        'description' => '',
    ];

    public function mount()
    {
        $this->loadCertificates();
    }

    public function loadCertificates()
    {
        $this->certificates = Certificate::where('user_id', Auth::id())
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
            'issuer' => '',
            'year' => '',
            'credential_id' => '',
            'credential_url' => '',
            'description' => '',
        ];
    }

    public function edit($id)
    {
        $cert = Certificate::find($id);
        $this->editingId = $id;
        $this->form = [
            'name' => $cert->name,
            'issuer' => $cert->issuer,
            'year' => $cert->year,
            'credential_id' => $cert->credential_id,
            'credential_url' => $cert->credential_url,
            'description' => $cert->description,
        ];
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.issuer' => 'required|string|max:255',
            'form.year' => 'required|string|max:50',
            'form.credential_id' => 'nullable|string|max:255',
            'form.credential_url' => 'nullable|url|max:500',
            'form.description' => 'nullable|string|max:2000',
        ]);

        if ($this->editingId) {
            Certificate::find($this->editingId)->update([
                'name' => $this->form['name'],
                'issuer' => $this->form['issuer'],
                'year' => $this->form['year'],
                'credential_id' => $this->form['credential_id'],
                'credential_url' => $this->form['credential_url'],
                'description' => $this->form['description'],
            ]);
            session()->flash('success', 'Certificate updated successfully!');
        } else {
            Certificate::create([
                'user_id' => Auth::id(),
                'name' => $this->form['name'],
                'issuer' => $this->form['issuer'],
                'year' => $this->form['year'],
                'credential_id' => $this->form['credential_id'],
                'credential_url' => $this->form['credential_url'],
                'description' => $this->form['description'],
                'sort_order' => count($this->certificates),
            ]);
            session()->flash('success', 'Certificate added successfully!');
        }

        $this->closeModal();
        $this->loadCertificates();
    }

    public function delete($id)
    {
        Certificate::find($id)->delete();
        $this->loadCertificates();
        session()->flash('success', 'Certificate deleted successfully!');
    }

    public function render()
    {
        return view('livewire.admin.manage-certificates')
            ->layout('layouts.admin', ['title' => 'Manage Certificates']);
    }
}
