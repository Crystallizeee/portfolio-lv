<?php

namespace App\Livewire;

use App\Models\Experience;
use Livewire\Component;

class ExperienceTimeline extends Component
{
    public function render()
    {
        // ⚡ Bolt Optimization: Cache the experience query to prevent redundant DB hits
        $experiences = \Illuminate\Support\Facades\Cache::remember('experiences_landing', 3600, function () {
            return Experience::orderBy('sort_order', 'asc')->get();
        });
        
        return view('livewire.experience-timeline', [
            'experiences' => $experiences,
        ]);
    }
}
