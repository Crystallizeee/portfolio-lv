<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Skill;

class SkillsSection extends Component
{
    public function render()
    {
        // ⚡ Bolt Optimization: Cache the skills query to prevent redundant DB hits
        $skills = \Illuminate\Support\Facades\Cache::remember('skills_landing', 3600, function () {
            return Skill::orderBy('category')->orderBy('sort_order')->get();
        });
        
        // Group skills by category
        $skillsByCategory = $skills->groupBy('category');
        
        return view('livewire.skills-section', [
            'skillsByCategory' => $skillsByCategory,
        ]);
    }
}
