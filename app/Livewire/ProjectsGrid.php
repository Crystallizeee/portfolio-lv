<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectsGrid extends Component
{
    public function render()
    {
        $projects = Project::all();
        
        return view('livewire.projects-grid', [
            'projects' => $projects,
        ]);
    }
}
