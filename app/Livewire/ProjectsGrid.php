<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectsGrid extends Component
{
    public function render()
    {
        // ⚡ Bolt Optimization: Cache the landing page projects query to prevent redundant DB hits
        // on the heavily trafficked landing page. Reduces response time and DB load.
        $projects = \Illuminate\Support\Facades\Cache::remember('projects_landing', 3600, function () {
            return Project::where('show_on_landing', true)->get();
        });
        
        return view('livewire.projects-grid', [
            'projects' => $projects,
        ]);
    }
}
