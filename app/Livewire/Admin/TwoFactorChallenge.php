<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallenge extends Component
{
    public string $code = '';
    public string $recoveryCode = '';
    public bool $usingRecoveryCode = false;

    protected $rules = [
        'code' => 'required_without:recoveryCode|nullable|digits:6',
        'recoveryCode' => 'required_without:code|nullable|string',
    ];

    public function verify()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        $throttleKey = '2fa-challenge|' . $user->id . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError($this->usingRecoveryCode ? 'recoveryCode' : 'code', "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik.");
            return;
        }

        if ($this->usingRecoveryCode) {
            $this->verifyRecoveryCode($user, $throttleKey);
        } else {
            $this->verifyTotp($user, $throttleKey);
        }
    }

    protected function verifyTotp($user, string $throttleKey): void
    {
        RateLimiter::hit($throttleKey);
        $this->validateOnly('code', ['code' => 'required|digits:6']);

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->two_factor_secret,
            $this->code,
            2 // allow 2-window tolerance (60 seconds window)
        );

        if (! $valid) {
            $this->addError('code', 'Kode OTP tidak valid atau sudah kedaluwarsa.');
            return;
        }

        RateLimiter::clear($throttleKey);
        $this->completeVerification();
    }

    protected function verifyRecoveryCode($user, string $throttleKey): void
    {
        RateLimiter::hit($throttleKey);
        $this->validateOnly('recoveryCode', ['recoveryCode' => 'required|string']);

        $recoveryCodes = $user->two_factor_recovery_codes ?? [];

        $matchedIndex = null;
        foreach ($recoveryCodes as $index => $storedCode) {
            if (hash_equals(trim($storedCode), trim($this->recoveryCode))) {
                $matchedIndex = $index;
                break;
            }
        }

        if ($matchedIndex === null) {
            $this->addError('recoveryCode', 'Recovery code tidak valid.');
            return;
        }

        // Invalidate the used recovery code (single-use)
        unset($recoveryCodes[$matchedIndex]);
        $user->update(['two_factor_recovery_codes' => array_values($recoveryCodes)]);

        RateLimiter::clear($throttleKey);
        $this->completeVerification();
    }

    protected function completeVerification(): void
    {
        session()->put('two_factor_verified', true);

        $intended = session()->pull('two_factor_intended', route('admin.dashboard'));

        $this->redirect($intended, navigate: false);
    }

    public function toggleMode()
    {
        $this->usingRecoveryCode = ! $this->usingRecoveryCode;
        $this->code = '';
        $this->recoveryCode = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.two-factor-challenge')
            ->layout('layouts.admin', ['title' => 'Two-Factor Authentication']);
    }
}
