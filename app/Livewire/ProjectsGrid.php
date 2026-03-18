<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectsGrid extends Component
{
    public function render()
    {
        $projects = Project::where('show_on_landing', true)->get();
        
        return view('livewire.projects-grid', [
            'projects' => $projects,
        ]);
    }
}
