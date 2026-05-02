<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Education;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class ProfileSettings extends Component
{
    use WithFileUploads;

    // Profile fields
    public $name;
    public $email;
    public $phone;
    public $address;
    public $linkedin;
    public $github;
    public $website;
    public $summary;
    public $contact_title;
    public $contact_subtitle;
    public $about_grc_list;
    public $about_tech_list;
    public $professional_title;
    public $titleOptions = [];
    public $isCustomTitle = false;
    
    // Avatar
    public $avatar;
    public $newAvatar;
    
    // Password fields
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    // Two-Factor Authentication
    public bool $showTwoFactorSetup = false;
    public string $twoFactorQrCode = '';
    public string $twoFactorSetupKey = '';
    public string $twoFactorConfirmCode = '';
    public bool $twoFactorEnabled = false;
    public array $recoveryCodes = [];

    // Education
    public $educations = [];
    public $editingEducationId = null;
    public $educationForm = [
        'school' => '',
        'degree' => '',
        'year' => '',
        'thesis' => '',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->linkedin = $user->linkedin;
        $this->github = $user->github;
        $this->website = $user->website;
        $this->summary = $user->summary;
        $this->avatar = $user->avatar;
        $this->contact_title = $user->contact_title ?? 'Get In Touch';
        $this->contact_subtitle = $user->contact_subtitle;
        $this->about_grc_list = is_array($user->about_grc_list) ? implode("\n", $user->about_grc_list) : '';
        $this->about_tech_list = is_array($user->about_tech_list) ? implode("\n", $user->about_tech_list) : '';
        $this->professional_title = $user->professional_title;
        $this->titleOptions = \App\Models\User::PROFESSIONAL_TITLES;
        
        // Check if current title is in predefined list
        if ($this->professional_title && !in_array($this->professional_title, $this->titleOptions)) {
            $this->isCustomTitle = true;
        }

        $this->loadEducations();

        // Load 2FA state
        $this->twoFactorEnabled = $user->hasTwoFactorEnabled();
    }

    public function loadEducations()
    {
        $this->educations = Education::where('user_id', Auth::id())
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public function saveEducation()
    {
        $this->validate([
            'educationForm.school' => 'required|string|max:255',
            'educationForm.degree' => 'required|string|max:255',
            'educationForm.year' => 'required|string|max:50',
            'educationForm.thesis' => 'nullable|string|max:1000',
        ]);

        if ($this->editingEducationId) {
            Education::find($this->editingEducationId)->update([
                'school' => $this->educationForm['school'],
                'degree' => $this->educationForm['degree'],
                'year' => $this->educationForm['year'],
                'thesis' => $this->educationForm['thesis'],
            ]);
        } else {
            Education::create([
                'user_id' => Auth::id(),
                'school' => $this->educationForm['school'],
                'degree' => $this->educationForm['degree'],
                'year' => $this->educationForm['year'],
                'thesis' => $this->educationForm['thesis'],
                'sort_order' => count($this->educations),
            ]);
        }

        $this->resetEducationForm();
        $this->loadEducations();
        session()->flash('education_success', 'Education saved successfully!');
    }

    public function editEducation($id)
    {
        $education = Education::find($id);
        $this->editingEducationId = $id;
        $this->educationForm = [
            'school' => $education->school,
            'degree' => $education->degree,
            'year' => $education->year,
            'thesis' => $education->thesis,
        ];
    }

    public function deleteEducation($id)
    {
        Education::find($id)->delete();
        $this->loadEducations();
        session()->flash('education_success', 'Education deleted successfully!');
    }

    public function resetEducationForm()
    {
        $this->editingEducationId = null;
        $this->educationForm = ['school' => '', 'degree' => '', 'year' => '', 'thesis' => ''];
    }

    public function updatedProfessionalTitle($value)
    {
        if ($value === 'custom') {
            $this->isCustomTitle = true;
            $this->professional_title = '';
        } else {
            $this->isCustomTitle = false;
        }
    }

    public function switchToDropdown()
    {
        $this->isCustomTitle = false;
        $this->professional_title = $this->titleOptions[0];
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'summary' => 'nullable|string|max:2000',
            'contact_title' => 'required|string|max:255',
            'contact_subtitle' => 'nullable|string|max:1000',
            'about_grc_list' => 'nullable|string',
            'about_tech_list' => 'nullable|string',
            'professional_title' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'linkedin' => $this->linkedin,
            'github' => $this->github,
            'website' => $this->website,
            'summary' => $this->summary,
            'contact_title' => $this->contact_title,
            'contact_subtitle' => $this->contact_subtitle,
            'about_grc_list' => array_filter(array_map('trim', explode("\n", $this->about_grc_list))),
            'about_tech_list' => array_filter(array_map('trim', explode("\n", $this->about_tech_list))),
            'professional_title' => $this->professional_title,
        ]);

        \Illuminate\Support\Facades\Cache::forget('portfolio_owner');

        session()->flash('profile_success', 'Profile updated successfully!');
    }

    public function updateAvatar()
    {
        $this->validate([
            'newAvatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar
        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $path = $this->newAvatar->storeAs(
            'avatars', 
            $this->newAvatar->hashName(),
            'public'
        );
        $user->update(['avatar' => Storage::url($path)]);
        
        \Illuminate\Support\Facades\Cache::forget('portfolio_owner');

        $this->avatar = Storage::url($path);
        $this->newAvatar = null;

        session()->flash('avatar_success', 'Avatar updated successfully!');
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $user->update(['avatar' => null]);
        $this->avatar = null;

        \Illuminate\Support\Facades\Cache::forget('portfolio_owner');

        session()->flash('avatar_success', 'Avatar removed successfully!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_success', 'Password updated successfully!');
    }

    // ─────────────────────────────────────────────
    // Two-Factor Authentication Methods
    // ─────────────────────────────────────────────

    public function enableTwoFactor()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        // Generate a new secret key
        $secret = $google2fa->generateSecretKey();

        // Store the secret (not yet confirmed)
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => null,
            'two_factor_recovery_codes' => null,
        ]);

        // Generate the QR Code as SVG
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $this->twoFactorQrCode = base64_encode($writer->writeString($qrCodeUrl));
        $this->twoFactorSetupKey = $secret;
        $this->showTwoFactorSetup = true;
        $this->twoFactorConfirmCode = '';
    }

    public function confirmTwoFactor()
    {
        $this->validate([
            'twoFactorConfirmCode' => 'required|digits:6',
        ], [
            'twoFactorConfirmCode.required' => 'Masukkan kode OTP dari authenticator kamu.',
            'twoFactorConfirmCode.digits' => 'Kode OTP harus 6 digit.',
        ]);

        $user = Auth::user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->two_factor_secret,
            $this->twoFactorConfirmCode,
            2
        );

        if (! $valid) {
            $this->addError('twoFactorConfirmCode', 'Kode OTP tidak valid. Pastikan waktu perangkat kamu sudah sinkron.');
            return;
        }

        // Generate 8 recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = strtoupper(substr(bin2hex(random_bytes(8)), 0, 4)) . '-' .
                               strtoupper(substr(bin2hex(random_bytes(8)), 0, 4)) . '-' .
                               strtoupper(substr(bin2hex(random_bytes(8)), 0, 4)) . '-' .
                               strtoupper(substr(bin2hex(random_bytes(8)), 0, 4));
        }

        $user->update([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $recoveryCodes,
        ]);

        // Mark session as 2FA verified (so current session isn't kicked out)
        session()->put('two_factor_verified', true);

        $this->twoFactorEnabled = true;
        $this->showTwoFactorSetup = false;
        $this->recoveryCodes = $recoveryCodes;
        $this->twoFactorConfirmCode = '';

        session()->flash('twofactor_success', '2FA berhasil diaktifkan! Simpan recovery codes kamu di tempat yang aman.');
    }

    public function disableTwoFactor()
    {
        $user = Auth::user();

        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $this->twoFactorEnabled = false;
        $this->showTwoFactorSetup = false;
        $this->recoveryCodes = [];
        $this->twoFactorQrCode = '';
        $this->twoFactorSetupKey = '';

        session()->flash('twofactor_success', '2FA berhasil dinonaktifkan.');
    }

    public function regenerateRecoveryCodes()
    {
        $user = Auth::user();

        if (! $user->hasTwoFactorEnabled()) {
            return;
        }

        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = strtoupper(substr(bin2hex(random_bytes(8)), 0, 4)) . '-' .
                               strtoupper(substr(bin2hex(random_bytes(8)), 0, 4)) . '-' .
                               strtoupper(substr(bin2hex(random_bytes(8)), 0, 4)) . '-' .
                               strtoupper(substr(bin2hex(random_bytes(8)), 0, 4));
        }

        $user->update(['two_factor_recovery_codes' => $recoveryCodes]);
        $this->recoveryCodes = $recoveryCodes;

        session()->flash('twofactor_success', 'Recovery codes baru telah dibuat. Simpan di tempat yang aman!');
    }

    public function showRecoveryCodes()
    {
        $user = Auth::user();
        $this->recoveryCodes = $user->two_factor_recovery_codes ?? [];
    }

    public function render()
    {
        return view('livewire.admin.profile-settings')
            ->layout('layouts.admin', ['title' => 'Profile Settings']);
    }
}
