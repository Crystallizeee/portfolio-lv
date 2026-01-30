<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Education;

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
    
    // Avatar
    public $avatar;
    public $newAvatar;
    
    // Password fields
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

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
        $this->loadEducations();
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
        ]);

        session()->flash('profile_success', 'Profile updated successfully!');
    }

    public function updateAvatar()
    {
        $this->validate([
            'newAvatar' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $this->newAvatar->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        
        $this->avatar = $path;
        $this->newAvatar = null;

        session()->flash('avatar_success', 'Avatar updated successfully!');
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);
        $this->avatar = null;

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

    public function render()
    {
        return view('livewire.admin.profile-settings')
            ->layout('layouts.admin', ['title' => 'Profile Settings']);
    }
}
