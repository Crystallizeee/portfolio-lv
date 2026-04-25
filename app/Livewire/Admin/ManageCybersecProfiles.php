<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\CybersecProfile;
use Illuminate\Support\Facades\Auth;

class ManageCybersecProfiles extends Component
{
    public $profiles = [];
    public $editingId = null;
    public $showForm = false;

    public $form = [
        'platform' => 'tryhackme',
        'username' => '',
        'profile_url' => '',
        'rank' => '',
        'rooms_completed' => 0,
        'badges_count' => 0,
        'streak' => 0,
        'points' => 0,
        'top_percent' => '',
        'is_visible' => true,
    ];

    public function mount()
    {
        $this->loadProfiles();
    }

    public function loadProfiles()
    {
        $this->profiles = CybersecProfile::where('user_id', Auth::id())
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public function openForm($platform = null)
    {
        $this->resetForm();
        if ($platform) {
            $this->form['platform'] = $platform;
        }
        $this->showForm = true;
    }

    public function edit($id)
    {
        $profile = CybersecProfile::find($id);
        if (!$profile) return;

        $this->editingId = $id;
        $this->form = [
            'platform' => $profile->platform,
            'username' => $profile->username,
            'profile_url' => $profile->profile_url ?? '',
            'rank' => $profile->rank ?? '',
            'rooms_completed' => $profile->rooms_completed,
            'badges_count' => $profile->badges_count,
            'streak' => $profile->streak,
            'points' => $profile->points,
            'top_percent' => $profile->top_percent ?? '',
            'is_visible' => $profile->is_visible,
        ];
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'form.platform' => 'required|in:tryhackme,letsdefend',
            'form.username' => 'required|string|max:255',
            'form.profile_url' => 'nullable|url|max:500',
            'form.rank' => 'nullable|string|max:255',
            'form.rooms_completed' => 'integer|min:0',
            'form.badges_count' => 'integer|min:0',
            'form.streak' => 'integer|min:0',
            'form.points' => 'integer|min:0',
            'form.top_percent' => 'nullable|string|max:50',
        ]);

        // Auto-generate profile URL if not provided
        $profileUrl = $this->form['profile_url'];
        if (empty($profileUrl)) {
            $profileUrl = match ($this->form['platform']) {
                'tryhackme' => "https://tryhackme.com/p/{$this->form['username']}",
                'letsdefend' => "https://app.letsdefend.io/user/{$this->form['username']}",
                default => null,
            };
        }

        $data = [
            'user_id' => Auth::id(),
            'platform' => $this->form['platform'],
            'username' => $this->form['username'],
            'profile_url' => $profileUrl,
            'rank' => $this->form['rank'] ?: null,
            'rooms_completed' => $this->form['rooms_completed'],
            'badges_count' => $this->form['badges_count'],
            'streak' => $this->form['streak'],
            'points' => $this->form['points'],
            'top_percent' => $this->form['top_percent'] ?: null,
            'is_visible' => $this->form['is_visible'],
        ];

        if ($this->editingId) {
            CybersecProfile::find($this->editingId)->update($data);
            session()->flash('success', 'Profile updated successfully!');
        } else {
            $data['sort_order'] = count($this->profiles);
            CybersecProfile::create($data);
            session()->flash('success', 'Profile added successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->loadProfiles();
    }

    public function delete($id)
    {
        CybersecProfile::find($id)?->delete();
        $this->loadProfiles();
        session()->flash('success', 'Profile deleted successfully!');
    }

    public function toggleVisibility($id)
    {
        $profile = CybersecProfile::find($id);
        if ($profile) {
            $profile->update(['is_visible' => !$profile->is_visible]);
            $this->loadProfiles();
        }
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->form = [
            'platform' => 'tryhackme',
            'username' => '',
            'profile_url' => '',
            'rank' => '',
            'rooms_completed' => 0,
            'badges_count' => 0,
            'streak' => 0,
            'points' => 0,
            'top_percent' => '',
            'is_visible' => true,
        ];
    }

    public function render()
    {
        return view('livewire.admin.manage-cybersec-profiles')
            ->layout('layouts.admin', ['title' => 'Cybersec Profiles']);
    }
}
