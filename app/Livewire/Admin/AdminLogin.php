<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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

        $throttleKey = Str::transliterate(Str::lower($this->email).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.");
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($throttleKey);

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

        RateLimiter::hit($throttleKey, 60); // 60 seconds lockout
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
