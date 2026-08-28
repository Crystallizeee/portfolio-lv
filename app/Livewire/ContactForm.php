<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ContactForm extends Component
{
    public $user;

    public function mount()
    {
        // ⚡ Bolt Optimization: Cache the user portfolio owner query to prevent redundant DB hits
        // on the heavily trafficked landing page. Reduces response time and DB load.
        $this->user = Cache::remember('portfolio_owner', 3600, function () {
            return User::getPortfolioOwner();
        });
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
