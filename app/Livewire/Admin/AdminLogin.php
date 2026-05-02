<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminLogin extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();

            // If user has 2FA enabled, redirect to the TOTP challenge page
            if ($user->hasTwoFactorEnabled()) {
                // Clear any stale 2FA verification from previous sessions
                session()->forget('two_factor_verified');

                // Store the intended destination so we can redirect after 2FA
                session()->put('two_factor_intended', route('admin.dashboard'));

                return redirect()->route('admin.two-factor');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        $this->addError('email', 'Kredensial yang diberikan tidak cocok dengan data kami.');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function render()
    {
        return view('livewire.admin.admin-login')
            ->layout('layouts.admin', ['title' => 'Login']);
    }
}
