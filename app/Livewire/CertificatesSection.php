<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Certificate;

class CertificatesSection extends Component
{
    public $certificates;

    public function mount()
    {
        $this->certificates = Certificate::orderBy('sort_order')->get();
    }

    public function render()
    {
        if ($this->certificates->isEmpty()) {
            return <<<'HTML'
                <div></div>
            HTML;
        }

        return view('livewire.certificates-section');
    }
}
