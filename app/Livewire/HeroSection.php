<?php

namespace App\Livewire;

use Livewire\Component;

class HeroSection extends Component
{
    public string $name = 'Benidictus Tri Wibowo';
    
    public array $titles = [
        'ICT Risk Professional',
        'ISO 27001 Practitioner',
        'Offensive Security Enthusiast',
        'Home Lab Builder',
        'Blue Team Defender',
    ];

    public function render()
    {
        return view('livewire.hero-section');
    }
}
