<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Admin Panel' }} | Portfolio Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|jetbrains-mono:400,500,600,700" rel="stylesheet" />

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="bg-[var(--color-cyber-dark)] text-slate-300 antialiased min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @auth
        <aside class="w-64 glass-card border-r border-slate-700/50 flex flex-col z-10">
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-slate-700/50">
                <span class="terminal-text font-mono text-lg">~/admin</span>
                <span class="text-slate-500 cursor-blink ml-1">_</span>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.projects') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.projects') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="folder-git-2" class="w-5 h-5"></i>
                    <span class="font-medium">Projects</span>
                </a>
                
                <a href="{{ route('admin.experiences') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.experiences') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                    <span class="font-medium">Experiences</span>
                </a>
                
                <a href="{{ route('admin.skills') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.skills') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="cpu" class="w-5 h-5"></i>
                    <span class="font-medium">Skills</span>
                </a>

                <a href="{{ route('admin.cv-generator') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.cv-generator') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span class="font-medium">CV Generator</span>
                </a>

                <a href="{{ route('admin.certificates') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.certificates') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="award" class="w-5 h-5"></i>
                    <span class="font-medium">Certificates</span>
                </a>

                <a href="{{ route('admin.languages') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.languages') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="languages" class="w-5 h-5"></i>
                    <span class="font-medium">Languages</span>
                </a>

                <a href="{{ route('admin.activity-logs') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.activity-logs') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                    <span class="font-medium">Activity Log</span>
                </a>

                <a href="{{ route('admin.seo') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.seo') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    <span class="font-medium">SEO Manager</span>
                </a>

                <a href="{{ route('admin.backup') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.backup') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="database-backup" class="w-5 h-5"></i>
                    <span class="font-medium">Backup & Restore</span>
                </a>

                <a href="{{ route('admin.profile') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.profile') ? 'bg-cyan-500/10 text-cyan-400' : 'text-slate-400 hover:bg-slate-700/50 hover:text-cyan-400' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span class="font-medium">Profile Settings</span>
                </a>
            </nav>
            
            <!-- Footer -->
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center justify-between px-4 py-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-cyan-500/20 flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-cyan-400"></i>
                        </div>
                        <span class="text-sm text-slate-400">{{ auth()->user()->name }}</span>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        @endauth
        
        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            @auth
            <!-- Top Bar -->
            <header class="h-16 glass-card border-b border-slate-700/50 flex items-center justify-between px-8">
                <h1 class="font-mono text-xl text-white">{{ $title ?? 'Dashboard' }}</h1>
                <a href="{{ url('/') }}" target="_blank" class="flex items-center space-x-2 text-slate-400 hover:text-cyan-400 transition-colors text-sm">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    <span>View Portfolio</span>
                </a>
            </header>
            @endauth
            
            <!-- Page Content -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Reinitialize icons after Livewire updates
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
        
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                lucide.createIcons();
            });
            
            Livewire.hook('element.updated', (el, component) => {
                lucide.createIcons();
            });
        });
        
        // Also reinitialize on any DOM change
        document.addEventListener('livewire:load', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
