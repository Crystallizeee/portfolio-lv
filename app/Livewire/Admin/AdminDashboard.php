<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Project;
use App\Models\Experience;

class AdminDashboard extends Component
{
    public int $projectsCount = 0;
    public int $onlineProjects = 0;
    public int $experiencesCount = 0;

    public function mount()
    {
        $this->projectsCount = Project::count();
        $this->onlineProjects = Project::where('status', 'online')->count();
        $this->experiencesCount = Experience::count();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
