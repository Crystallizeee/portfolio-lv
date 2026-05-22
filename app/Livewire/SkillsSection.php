<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Skill;
use Illuminate\Support\Facades\Cache;

class SkillsSection extends Component
{
    public function render()
    {
        // ⚡ Bolt Optimization: Cache the landing page skills query and grouping
        // to prevent redundant DB hits and collection operations on every render.
        $skillsByCategory = Cache::remember('skills_landing', 3600, function () {
            $skills = Skill::orderBy('category')->orderBy('sort_order')->get();
            return $skills->groupBy('category');
        });
        
        return view('livewire.skills-section', [
            'skillsByCategory' => $skillsByCategory,
        ]);
    }
}
