<?php

namespace App\Livewire;

use App\Models\Experience;
use Livewire\Component;

class ExperienceTimeline extends Component
{
    public function render()
    {
        $experiences = Experience::orderBy('sort_order', 'asc')->get();
        
        return view('livewire.experience-timeline', [
            'experiences' => $experiences,
        ]);
    }
}
