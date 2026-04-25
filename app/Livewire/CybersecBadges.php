<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CybersecProfile;

class CybersecBadges extends Component
{
    public $profiles = [];

    public function mount()
    {
        $this->profiles = CybersecProfile::visible()->get();
    }

    public function render()
    {
        return view('livewire.cybersec-badges');
    }
}
