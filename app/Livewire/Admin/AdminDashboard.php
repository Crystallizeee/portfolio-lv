<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Analytics;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    public int $projectsCount = 0;
    public int $onlineProjects = 0;
    public int $experiencesCount = 0;
    public int $cvDownloads = 0;
    public int $profileViews = 0;
    public array $chartData = [];
    public $recentVisitors = [];

    public function mount()
    {
        $this->projectsCount = Project::count();
        $this->onlineProjects = Project::where('status', 'online')->count();
        $this->experiencesCount = Experience::count();
        $this->cvDownloads = Analytics::getTotal(Auth::id(), 'cv_download');
        $this->profileViews = Analytics::getTotal(Auth::id(), 'profile_view');

        $this->prepareChartData();

        $this->recentVisitors = Analytics::where('user_id', Auth::id())
            ->where('type', 'profile_view')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
    }

    private function prepareChartData()
    {
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->format('Y-m-d');
        });

        $views = [];
        $downloads = [];

        foreach ($days as $date) {
            $views[] = Analytics::where('user_id', Auth::id())
                ->where('type', 'profile_view')
                ->where('date', $date)
                ->sum('count');

            $downloads[] = Analytics::where('user_id', Auth::id())
                ->where('type', 'cv_download')
                ->where('date', $date)
                ->sum('count');
        }

        $this->chartData = [
            'labels' => $days->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray(),
            'views' => $views,
            'downloads' => $downloads,
        ];
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
