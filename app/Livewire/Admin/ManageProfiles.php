<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\JobProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ManageProfiles extends Component
{
    public $profiles;
    public $editingProfileId = null;
    
    // Form fields
    public $name;
    public $professional_title;
    public $summary;
    public $about_grc_list;
    public $about_tech_list;

    public $isModalOpen = false;

    public function mount()
    {
        $this->loadProfiles();
    }

    public function loadProfiles()
    {
        $this->profiles = JobProfile::where('user_id', Auth::id())->orderBy('name')->get();
    }

    public function createProfile()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function editProfile($id)
    {
        $profile = JobProfile::findOrFail($id);
        $this->editingProfileId = $profile->id;
        $this->name = $profile->name;
        $this->professional_title = $profile->professional_title;
        $this->summary = $profile->summary;
        $this->about_grc_list = is_array($profile->about_grc_list) ? implode("\n", $profile->about_grc_list) : '';
        $this->about_tech_list = is_array($profile->about_tech_list) ? implode("\n", $profile->about_tech_list) : '';
        $this->isModalOpen = true;
    }

    public function saveProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'professional_title' => 'required|string|max:255',
            'summary' => 'required|string',
            'about_grc_list' => 'nullable|string',
            'about_tech_list' => 'nullable|string',
        ]);

        $grcList = array_filter(array_map('trim', explode("\n", $this->about_grc_list)));
        $techList = array_filter(array_map('trim', explode("\n", $this->about_tech_list)));

        if ($this->editingProfileId) {
            JobProfile::findOrFail($this->editingProfileId)->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'professional_title' => $this->professional_title,
                'summary' => $this->summary,
                'about_grc_list' => $grcList,
                'about_tech_list' => $techList,
            ]);
            session()->flash('message', 'Profile updated successfully.');
        } else {
            JobProfile::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'professional_title' => $this->professional_title,
                'summary' => $this->summary,
                'about_grc_list' => $grcList,
                'about_tech_list' => $techList,
                'is_landing_page' => false,
            ]);
            session()->flash('message', 'Profile created successfully.');
        }

        $this->closeModal();
        $this->loadProfiles();
    }

    public function deleteProfile($id)
    {
        $profile = JobProfile::findOrFail($id);
        if ($profile->is_landing_page) {
            session()->flash('error', 'Cannot delete the active landing page profile.');
            return;
        }
        $profile->delete();
        $this->loadProfiles();
        session()->flash('message', 'Profile deleted successfully.');
    }

    public function setAsLandingPage($id)
    {
        // Set all to false
        JobProfile::where('user_id', Auth::id())->update(['is_landing_page' => false]);
        
        // Set selected to true
        $profile = JobProfile::findOrFail($id);
        $profile->update(['is_landing_page' => true]);
        
        $this->loadProfiles();
        
        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('portfolio_owner');
        
        session()->flash('message', 'Landing page profile updated.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['editingProfileId', 'name', 'professional_title', 'summary', 'about_grc_list', 'about_tech_list']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.manage-profiles')
            ->layout('layouts.admin', ['title' => 'Manage Profiles']);
    }
}
