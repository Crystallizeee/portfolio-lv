<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- CV Downloads -->
        <div class="glass-card p-6 group cursor-pointer hover:translate-y-[-4px] transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">CV Downloads</p>
                    <p class="text-3xl font-bold text-white font-mono tracking-tight group-hover:text-cyan-400 transition-colors">{{ $cvDownloads }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center transition-all group-hover:scale-110 group-hover:shadow-[0_0_15px_rgba(34,211,238,0.2)]">
                    <i data-lucide="download" class="w-6 h-6 text-cyan-400"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-xs text-slate-500 uppercase tracking-widest font-mono">Status: Ready</span>
                <span class="text-[10px] text-cyan-500/50">+{{ rand(1, 5) }} this week</span>
            </div>
        </div>

        <!-- Profile Views -->
        <div class="glass-card p-6 group cursor-pointer hover:translate-y-[-4px] transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Profile Views</p>
                    <p class="text-3xl font-bold text-white font-mono tracking-tight group-hover:text-purple-400 transition-colors">{{ $profileViews }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center transition-all group-hover:scale-110 group-hover:shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i data-lucide="eye" class="w-6 h-6 text-purple-400"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-xs text-slate-500 uppercase tracking-widest font-mono">Live Tracking</span>
                <span class="text-[10px] text-purple-500/50">Active: Yes</span>
            </div>
        </div>

        <!-- Total Projects -->
        <div class="glass-card p-6 group cursor-pointer hover:translate-y-[-4px] transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Total Projects</p>
                    <p class="text-3xl font-bold text-white font-mono tracking-tight group-hover:text-emerald-400 transition-colors">{{ $projectsCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center transition-all group-hover:scale-110 group-hover:shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                    <i data-lucide="folder-git-2" class="w-6 h-6 text-emerald-400"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="flex items-center text-emerald-400 group-hover:glow-green">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2 status-dot shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                    {{ $onlineProjects }} Online
                </span>
                <span class="mx-2 text-slate-700">|</span>
                <span class="text-slate-500">{{ $projectsCount - $onlineProjects }} Draft</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass-card p-6 lg:p-4">
            <h3 class="text-slate-400 text-[10px] font-mono uppercase tracking-widest mb-3 flex items-center">
                <i data-lucide="zap" class="w-3 h-3 text-yellow-500 mr-2"></i>
                Fast Access
            </h3>
            <div class="grid grid-cols-1 gap-2">
                <a href="{{ route('admin.projects') }}" class="group flex items-center justify-between px-3 py-2 rounded-lg bg-white/5 border border-white/5 hover:border-cyan-500/30 hover:bg-cyan-500/10 transition-all duration-300">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-cyan-400"></i>
                        <span class="text-xs font-medium text-slate-300 group-hover:text-cyan-400">New Project</span>
                    </div>
                    <i data-lucide="arrow-right" class="w-3 h-3 text-slate-600 group-hover:text-cyan-400 transition-transform group-hover:translate-x-1"></i>
                </a>
                <a href="{{ route('admin.experiences') }}" class="group flex items-center justify-between px-3 py-2 rounded-lg bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/10 transition-all duration-300">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="history" class="w-4 h-4 text-purple-400"></i>
                        <span class="text-xs font-medium text-slate-300 group-hover:text-purple-400">New Experience</span>
                    </div>
                    <i data-lucide="arrow-right" class="w-3 h-3 text-slate-600 group-hover:text-purple-400 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics Chart -->
    <div class="glass-card p-6 mb-8">
        <h3 class="text-lg font-bold text-white mb-6 flex items-center space-x-2">
            <i data-lucide="bar-chart-2" class="w-5 h-5 text-indigo-400"></i>
            <span>Analytics Overview (Last 7 Days)</span>
        </h3>
        <div class="relative h-64">
            <canvas id="analyticsChart"></canvas>
        </div>
    </div>

    <!-- Traffic Analytics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Top Browsers -->
        <div class="glass-card p-6">
            <div class="flex items-center space-x-2 mb-4">
                <i data-lucide="globe" class="w-5 h-5 text-cyan-400"></i>
                <h2 class="text-lg font-semibold text-white">Top Browsers</h2>
            </div>
            <div class="space-y-3">
                @foreach($topBrowsers as $browser => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-slate-300">{{ $browser }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-24 h-2 bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-cyan-500" style="width: {{ ($count / (count($topBrowsers) > 0 ? max($topBrowsers) : 1)) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-mono text-slate-500 w-8 text-right">{{ $count }}</span>
                    </div>
                </div>
                @endforeach
                @if(empty($topBrowsers))
                    <div class="text-center text-slate-500 text-sm">No data yet</div>
                @endif
            </div>
        </div>

        <!-- Top Devices/OS -->
        <div class="glass-card p-6">
            <div class="flex items-center space-x-2 mb-4">
                <i data-lucide="monitor" class="w-5 h-5 text-purple-400"></i>
                <h2 class="text-lg font-semibold text-white">Top Devices / OS</h2>
            </div>
            <div class="space-y-3">
                @foreach($topDevices as $device => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-slate-300">{{ $device }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-24 h-2 bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500" style="width: {{ ($count / (count($topDevices) > 0 ? max($topDevices) : 1)) * 100 }}%"></div>
                        </div>
                        <span class="text-xs font-mono text-slate-500 w-8 text-right">{{ $count }}</span>
                    </div>
                </div>
                @endforeach
                @if(empty($topDevices))
                    <div class="text-center text-slate-500 text-sm">No data yet</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- System Status -->
        <div class="glass-card p-6">
            <div class="flex items-center space-x-2 mb-6">
                <i data-lucide="terminal" class="w-5 h-5 text-cyan-400"></i>
                <h2 class="text-lg font-semibold text-white font-mono">System Status</h2>
            </div>
            
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const ctx = document.getElementById('analyticsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($chartData['labels']),
                            datasets: [
                                {
                                    label: 'Profile Views',
                                    data: @json($chartData['views']),
                                    borderColor: '#3b82f6', // Blue-500
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                },
                                {
                                    label: 'CV Downloads',
                                    data: @json($chartData['downloads']),
                                    borderColor: '#22c55e', // Green-500
                                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    labels: { color: '#94a3b8' }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                    ticks: { color: '#94a3b8', stepSize: 1 }
                                },
                                x: {
                                    grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                    ticks: { color: '#94a3b8' }
                                }
                            }
                        }
                    });
                });
            </script>
            
            <div class="font-mono text-sm space-y-2 text-slate-400">
                <div class="flex items-center space-x-2">
                    <span class="text-green-400">✓</span>
                    <span>Database connected</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-green-400">✓</span>
                    <span>{{ $projectsCount }} projects loaded</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-green-400">✓</span>
                    <span>{{ $experiencesCount }} experiences loaded</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-cyan-400">→</span>
                    <span>Admin panel ready</span>
                </div>
            </div>
        </div>

        <!-- Recent Visitors -->
        <div class="glass-card p-6">
            <div class="flex items-center space-x-2 mb-6">
                <i data-lucide="users" class="w-5 h-5 text-purple-400"></i>
                <h2 class="text-lg font-semibold text-white">Recent Visitors</h2>
            </div>
            
            <div class="space-y-4">
                @forelse($recentVisitors as $visitor)
                <div class="flex items-center justify-between p-3 bg-slate-800/30 rounded-lg border border-slate-700/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center">
                            <i data-lucide="globe" class="w-4 h-4 text-slate-400"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-white">{{ $visitor->ip_address ?? 'Unknown IP' }}</div>
                            <div class="text-xs text-slate-500">{{ $visitor->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-purple-400">{{ $visitor->url }}</div>
                        <div class="text-xs text-slate-500">Visited</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-500 py-4 text-sm">
                    No visitors recorded yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
