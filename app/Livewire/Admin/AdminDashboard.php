<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Analytics;
use App\Models\SiteVisit;
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
    public $topBrowsers = [];
    public $topDevices = [];

    public function mount()
    {
        $this->projectsCount = Project::count();
        $this->onlineProjects = Project::where('status', 'online')->count();
        $this->experiencesCount = Experience::count();
        $this->cvDownloads = Analytics::getTotal(Auth::id(), 'cv_download');
        $this->profileViews = SiteVisit::count();

        $this->prepareChartData();
        $this->analyzeTraffic();

        $this->recentVisitors = SiteVisit::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    private function analyzeTraffic()
    {
        // ⚡ Bolt Optimization: Cache the traffic analysis to prevent 500 loop iterations
        // and expensive Agent parsing on every dashboard reload.
        $trafficData = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_traffic_analysis', 3600, function () {
            $visits = SiteVisit::orderBy('created_at', 'desc')->take(500)->get();
            $agent = new \Jenssegers\Agent\Agent();
            
            $browsers = [];
            $devices = [];

            foreach ($visits as $visit) {
                if (empty($visit->user_agent)) continue;

                $agent->setUserAgent($visit->user_agent);

                $browser = $agent->browser();
                $platform = $agent->platform();

                $browser = $browser ?: 'Unknown';
                $platform = $platform ?: 'Unknown';

                if (!isset($browsers[$browser])) $browsers[$browser] = 0;
                $browsers[$browser]++;

                if (!isset($devices[$platform])) $devices[$platform] = 0;
                $devices[$platform]++;
            }

            arsort($browsers);
            arsort($devices);

            return [
                'topBrowsers' => array_slice($browsers, 0, 5, true),
                'topDevices' => array_slice($devices, 0, 5, true),
            ];
        });

        $this->topBrowsers = $trafficData['topBrowsers'];
        $this->topDevices = $trafficData['topDevices'];
    }

    private function prepareChartData()
    {
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->format('Y-m-d');
        });

        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // ⚡ Bolt Optimization: Group by date instead of querying inside loop to avoid N+1 queries
        $viewsData = SiteVisit::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('date(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $downloadsData = Analytics::where('user_id', Auth::id())
            ->where('type', 'cv_download')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->selectRaw('date, sum(count) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $views = [];
        $downloads = [];

        foreach ($days as $date) {
            $views[] = $viewsData->get($date, 0);
            $downloads[] = $downloadsData->get($date, 0);
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
