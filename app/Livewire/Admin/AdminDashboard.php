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
        // ⚡ Bolt Optimization: Cache dashboard aggregate queries (counts) for 5 minutes (300s).
        // This prevents 4 expensive sequential database COUNT() queries from executing every
        // time the admin dashboard is loaded, significantly improving page render speed
        // while trading off slight real-time accuracy for performance.
        $this->projectsCount = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_projects_count', 300, fn() => Project::count());
        $this->onlineProjects = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_online_projects_count', 300, fn() => Project::where('status', 'online')->count());
        $this->experiencesCount = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_experiences_count', 300, fn() => Experience::count());
        $this->cvDownloads = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_cv_downloads_' . Auth::id(), 300, fn() => Analytics::getTotal(Auth::id(), 'cv_download'));
        $this->profileViews = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_profile_views_count', 300, fn() => SiteVisit::count());

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

        // ⚡ Bolt Optimization: Cache chart aggregate queries for 5 minutes (300s).
        // This prevents 2 expensive group-by queries from executing on every
        // dashboard load, further improving performance for the admin view.
        $userId = Auth::id();
        $viewsData = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_views_data', 300, function () use ($startDate, $endDate) {
            return SiteVisit::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('date(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');
        });

        $downloadsData = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_downloads_data_' . $userId, 300, function () use ($userId, $startDate, $endDate) {
            return Analytics::where('user_id', $userId)
                ->where('type', 'cv_download')
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->selectRaw('date, sum(count) as count')
                ->groupBy('date')
                ->pluck('count', 'date');
        });

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
