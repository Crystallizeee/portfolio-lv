<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GitHubService;

class GithubContributions extends Component
{
    public ?array $contributions = null;
    public ?array $stats = null;
    public bool $loaded = false;

    public function mount()
    {
        $github = app(GitHubService::class);

        if ($github->isConfigured()) {
            $this->contributions = $github->getContributions();
            $this->stats = $github->getProfileStats();
        }

        $this->loaded = true;
    }

    /**
     * Get the contribution level (0-4) based on count, mimicking GitHub's logic.
     */
    public function getLevel(int $count): int
    {
        if ($count === 0) return 0;
        if ($count <= 3) return 1;
        if ($count <= 6) return 2;
        if ($count <= 9) return 3;
        return 4;
    }

    public function render()
    {
        return view('livewire.github-contributions');
    }
}
