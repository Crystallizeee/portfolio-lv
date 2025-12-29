<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Skill;

class SkillsSection extends Component
{
    public function render()
    {
        $skills = Skill::orderBy('category')->orderBy('sort_order')->get();
        
        // Group skills by category
        $skillsByCategory = $skills->groupBy('category');
        
        return view('livewire.skills-section', [
            'skillsByCategory' => $skillsByCategory,
        ]);
    }
}
