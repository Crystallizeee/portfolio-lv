<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Certificate;

class CertificatesSection extends Component
{
    public $certificates;

    public function mount()
    {
        // ⚡ Bolt Optimization: Cache the certificates query to prevent redundant DB hits
        $this->certificates = \Illuminate\Support\Facades\Cache::remember('certificates_landing', 3600, function () {
            return Certificate::orderBy('sort_order')->get();
        });
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
