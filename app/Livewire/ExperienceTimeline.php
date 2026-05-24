<?php

namespace App\Livewire;

use App\Models\Experience;
use Livewire\Component;

class ExperienceTimeline extends Component
{
    public function render()
    {
        // ⚡ Bolt Optimization: Cache the experiences query to prevent redundant DB hits
        // on the heavily trafficked landing page. Reduces response time and DB load.
        $experiences = \Illuminate\Support\Facades\Cache::remember('experiences_landing', 3600, function () {
            return Experience::orderBy('sort_order', 'asc')->get();
        });
        
        return view('livewire.experience-timeline', [
            'experiences' => $experiences,
        ]);
    }
}
