<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class ContactForm extends Component
{
    public $user;

    public function mount()
    {
        $this->user = User::getPortfolioOwner();
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
