<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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

    <!-- Recent Activity Section -->
    <div class="glass-card p-6">
        <div class="flex items-center space-x-2 mb-6">
            <i data-lucide="terminal" class="w-5 h-5 text-cyan-400"></i>
            <h2 class="text-lg font-semibold text-white font-mono">System Status</h2>
        </div>
        
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
</div>
