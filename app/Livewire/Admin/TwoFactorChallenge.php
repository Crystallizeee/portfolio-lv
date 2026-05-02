<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
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

        if ($this->usingRecoveryCode) {
            $this->verifyRecoveryCode($user);
        } else {
            $this->verifyTotp($user);
        }
    }

    protected function verifyTotp($user): void
    {
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

        $this->completeVerification();
    }

    protected function verifyRecoveryCode($user): void
    {
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
