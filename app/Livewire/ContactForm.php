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
        if (!$this->user) {
            $this->user = new \stdClass();
            $this->user->email = null;
            $this->user->linkedin = null;
            $this->user->github = null;
            $this->user->contact_title = null;
            $this->user->contact_subtitle = null;
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
