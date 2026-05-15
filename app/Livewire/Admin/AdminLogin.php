<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AdminLogin extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected function throttleKey()
    {
        return mb_strtolower($this->email) . '|' . request()->ip();
    }

    public function login()
    {
        $this->validate();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($this->throttleKey());
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

        RateLimiter::hit($this->throttleKey());
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
