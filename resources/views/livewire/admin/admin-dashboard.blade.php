<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- CV Downloads -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">CV Downloads</p>
                    <p class="text-3xl font-bold text-white font-mono">{{ $cvDownloads }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-500/20 flex items-center justify-center">
                    <i data-lucide="download" class="w-6 h-6 text-green-400"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-slate-500">
                Total CV generated
            </div>
        </div>
        <!-- Profile Views -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Profile Views</p>
                    <p class="text-3xl font-bold text-white font-mono">{{ $profileViews }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <i data-lucide="eye" class="w-6 h-6 text-blue-400"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-slate-500">
                Portfolio page visits
            </div>
        </div>
        <!-- Total Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Total Projects</p>
                    <p class="text-3xl font-bold text-white font-mono">{{ $projectsCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                    <i data-lucide="folder-git-2" class="w-6 h-6 text-cyan-400"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="flex items-center text-green-400">
                    <span class="w-2 h-2 rounded-full bg-green-400 mr-2 status-dot"></span>
                    {{ $onlineProjects }} Online
                </span>
                <span class="mx-2 text-slate-600">|</span>
                <span class="text-slate-500">{{ $projectsCount - $onlineProjects }} Offline</span>
            </div>
        </div>

        <!-- Total Experiences -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Total Experiences</p>
                    <p class="text-3xl font-bold text-white font-mono">{{ $experiencesCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <i data-lucide="briefcase" class="w-6 h-6 text-purple-400"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-slate-500">
                Career timeline entries
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 text-sm font-medium">Quick Actions</p>
                <i data-lucide="zap" class="w-5 h-5 text-yellow-400"></i>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.projects') }}" class="block w-full py-2 px-4 text-left text-sm bg-slate-700/50 hover:bg-cyan-500/20 rounded-lg text-slate-300 hover:text-cyan-400 transition-colors">
                    + Tambah Project Baru
                </a>
                <a href="{{ route('admin.experiences') }}" class="block w-full py-2 px-4 text-left text-sm bg-slate-700/50 hover:bg-cyan-500/20 rounded-lg text-slate-300 hover:text-cyan-400 transition-colors">
                    + Tambah Experience Baru
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
                            <div class="text-xs text-slate-500">{{ $visitor->date->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-purple-400">{{ $visitor->count }}</div>
                        <div class="text-xs text-slate-500">Views</div>
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
