<?php

namespace App\Livewire\Admin;

use App\Models\JobApplication;
use Livewire\Component;
use Livewire\WithPagination;

class JobTracker extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;
    public $deleteId = null;
    public $showDeleteModal = false;

    // Form Fields
    public $company = '';
    public $position = '';
    public $status = 'applied';
    public $applied_date = '';
    public $salary = '';
    public $link = '';
    public $notes = '';

    // Search & Filter
    public $search = '';
    public $filterStatus = '';

    protected $queryString = ['search', 'filterStatus'];

    protected function rules()
    {
        return [
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'required|in:applied,interview,offer,rejected',
            'applied_date' => 'nullable|date',
            'salary' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->applied_date = now()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $job = JobApplication::findOrFail($id);

        $this->editingId = $id;
        $this->company = $job->company;
        $this->position = $job->position;
        $this->status = $job->status;
        $this->applied_date = $job->applied_date ? $job->applied_date->format('Y-m-d') : '';
        $this->salary = $job->salary;
        $this->link = $job->link;
        $this->notes = $job->notes;

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
            'position' => $this->position,
            'status' => $this->status,
            'applied_date' => $this->applied_date ?: null,
            'salary' => $this->salary,
            'link' => $this->link,
            'notes' => $this->notes,
        ];

        if ($this->isEditing && $this->editingId) {
            JobApplication::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Job application updated successfully!');
        } else {
            JobApplication::create($data);
            session()->flash('message', 'Job application added successfully!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        JobApplication::findOrFail($id)->delete();
        session()->flash('message', 'Job application deleted successfully!');
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->company = '';
        $this->position = '';
        $this->status = 'applied';
        $this->applied_date = now()->format('Y-m-d');
        $this->salary = '';
        $this->link = '';
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $jobs = JobApplication::query()
            ->when($this->search, function ($query) {
                $query->where('company', 'like', '%' . $this->search . '%')
                    ->orWhere('position', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.admin.job-tracker', [
            'jobs' => $jobs,
            'statuses' => JobApplication::statuses(),
        ])->layout('layouts.admin', ['title' => 'Job Tracker']);
    }
}
