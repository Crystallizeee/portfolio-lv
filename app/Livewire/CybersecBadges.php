<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CybersecProfile;

class CybersecBadges extends Component
{
    public $profiles = [];

    public function mount()
    {
        // ⚡ Bolt Optimization: Cache the cybersec profiles query to prevent redundant DB hits
        // on the heavily trafficked landing page. Reduces response time and DB load.
        $this->profiles = \Illuminate\Support\Facades\Cache::remember('cybersec_profiles_landing', 3600, function () {
            return CybersecProfile::visible()->get();
        });
    }

    public function render()
    {
        return view('livewire.cybersec-badges');
    }
}
