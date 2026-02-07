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

    <!-- PWA Settings -->
    <meta name="theme-color" content="#0a0f1d">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
</head>
<body class="bg-[var(--color-cyber-dark)] text-slate-300 antialiased min-h-screen">
    <div class="flex h-screen bg-cyber-darker overflow-hidden font-sans selection:bg-cyan-500/30" 
         x-data="{ 
            sidebarOpen: false, 
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            toggleCollapse() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            }
         }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-md md:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        @auth
        <aside class="fixed inset-y-0 left-0 z-50 glass-card flex flex-col transition-all duration-500 ease-in-out md:static md:my-4 md:ml-4 md:rounded-2xl flex-shrink-0 overflow-hidden rounded-none"
               :class="{
                    'translate-x-0 !bg-slate-950 md:!bg-transparent': sidebarOpen,
                    '-translate-x-full md:translate-x-0': !sidebarOpen,
                    'w-64': !sidebarCollapsed,
                    'w-20': sidebarCollapsed
               }">
            <!-- Logo area -->
            <div class="h-16 flex items-center px-6 border-b border-white/5 shrink-0 overflow-hidden">
                <div class="flex items-center space-x-3 shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center glow-cyan shrink-0">
                        <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="terminal-text font-mono text-base tracking-tighter truncate transition-all duration-300" x-show="!sidebarCollapsed" x-transition:enter="delay-200">ADMIN PANEL</span>
                </div>
                <!-- Desktop Minimize Toggle -->
                <button type="button" @click="toggleCollapse()" class="hidden md:flex ml-auto p-1.5 text-slate-500 hover:text-cyan-400 transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4 transition-transform duration-500" :class="sidebarCollapsed ? 'rotate-180' : ''"></i>
                </button>
                <!-- Mobile Close Button -->
                <button type="button" @click="sidebarOpen = false" class="md:hidden ml-auto p-2 text-slate-400 hover:text-white transition-all hover:rotate-90 cursor-pointer">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar overflow-x-hidden">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                        ['route' => 'admin.projects', 'icon' => 'folder-git-2', 'label' => 'Projects'],
                        ['route' => 'admin.posts', 'icon' => 'file-text', 'label' => 'Blog Posts'],
                        ['route' => 'admin.experiences', 'icon' => 'briefcase', 'label' => 'Experiences'],
                        ['route' => 'admin.skills', 'icon' => 'cpu', 'label' => 'Skills'],
                        ['route' => 'admin.cv-generator', 'icon' => 'file-text', 'label' => 'CV Generator'],
                        ['route' => 'admin.ai-cover-letter', 'icon' => 'sparkles', 'label' => 'AI Cover Letter'],
                        ['route' => 'admin.certificates', 'icon' => 'award', 'label' => 'Certificates'],
                        ['route' => 'admin.languages', 'icon' => 'languages', 'label' => 'Languages'],
                        ['route' => 'admin.activity-logs', 'icon' => 'activity', 'label' => 'Activity Logs'],
                        ['route' => 'admin.seo', 'icon' => 'search', 'label' => 'SEO Manager'],
                        ['route' => 'admin.backup', 'icon' => 'database-backup', 'label' => 'Backup & Restore'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-300 {{ request()->routeIs($item['route']) ? 'bg-cyan-500/15 text-cyan-400 shadow-[inset_0_0_12px_rgba(34,211,238,0.1)]' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}"
                       :class="sidebarCollapsed ? 'justify-center space-x-0 px-0' : 'space-x-3 px-4'">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 shrink-0"></i>
                        <span class="font-medium text-[0.925rem] truncate transition-all duration-300" x-show="!sidebarCollapsed" x-transition:enter="delay-200">{{ $item['label'] }}</span>
                        @if(request()->routeIs($item['route']))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(34,211,238,0.6)] shrink-0" x-show="!sidebarCollapsed"></div>
                        @endif
                    </a>
                @endforeach
            </nav>
            
            <!-- Footer/Profile Section -->
            <div class="p-4 border-t border-white/5 bg-white/[0.02] rounded-b-xl shrink-0 overflow-hidden">
                <div class="flex items-center group transition-all duration-300" :class="sidebarCollapsed ? 'justify-center space-x-0' : 'space-x-3'">
                    <a href="{{ route('admin.profile') }}" class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-700 to-slate-800 flex items-center justify-center border border-white/10 transition-all" :class="sidebarCollapsed ? 'w-8 h-8' : 'w-10 h-10'">
                            <i data-lucide="user" class="w-5 h-5 text-slate-300 transition-all" :class="sidebarCollapsed ? 'w-4 h-4' : 'w-5 h-5'"></i>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-[3px] border-[#0a0f1d] rounded-full status-dot"></div>
                    </a>
                    <div class="min-w-0 flex-1 transition-all duration-300" x-show="!sidebarCollapsed" x-transition:enter="delay-200">
                        <p class="text-sm font-semibold text-slate-200 truncate leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[0.65rem] font-mono text-cyan-500/70 tracking-widest uppercase truncate">System Admin</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" class="shrink-0" x-show="!sidebarCollapsed">
                        @csrf
                        <button type="submit" class="p-2 text-slate-500 hover:text-rose-400 transition-all hover:scale-110 cursor-pointer">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
                <!-- Mini Logout for Collapsed Mode -->
                <div class="mt-4 flex justify-center" x-show="sidebarCollapsed">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-1.5 text-slate-600 hover:text-rose-400 transition-colors cursor-pointer">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        @endauth
        
        <!-- Main Content Wrapper -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden relative transition-all duration-500">
            @auth
            <!-- Glass Header -->
            <header class="h-16 mx-4 mt-4 glass-card flex items-center justify-between px-6 z-30 transition-all duration-500">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Burger -->
                    <button type="button" @click="sidebarOpen = true" class="md:hidden p-2 text-slate-400 hover:text-white transition-all bg-white/5 rounded-lg active:scale-95">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    
                    <div class="flex space-x-2 items-center">
                        <!-- Mobile/Tablet Toggle Collapse (Hidden on very small mobile if preferred, but useful for tablet) -->
                        <button type="button" @click="toggleCollapse()" class="p-1.5 text-slate-500 hover:text-cyan-400 transition-colors rounded-lg bg-white/5 md:hidden">
                            <i data-lucide="maximize-2" class="w-4 h-4" x-show="sidebarCollapsed"></i>
                            <i data-lucide="minimize-2" class="w-4 h-4" x-show="!sidebarCollapsed"></i>
                        </button>
                        
                        <div class="flex flex-col">
                            <h1 class="text-sm font-mono text-cyan-500/80 tracking-tight leading-none mb-1 hidden sm:block">SYSTEM.EXEC //</h1>
                            <h2 class="text-lg font-bold text-white tracking-tight leading-none">{{ $title ?? 'Dashboard' }}</h2>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" target="_blank" 
                       class="hidden sm:flex items-center space-x-2 px-4 py-2 rounded-lg bg-white/5 border border-white/5 text-slate-300 hover:text-cyan-400 hover:bg-white/10 hover:border-cyan-500/30 transition-all duration-300 text-sm font-medium group">
                        <i data-lucide="external-link" class="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        <span>Live View</span>
                    </a>
                    
                    <div class="h-8 w-px bg-white/5 hidden sm:block"></div>
                    
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                        <span class="text-[10px] font-mono text-slate-500 tracking-tighter uppercase hidden lg:block">Status: Online</span>
                    </div>
                </div>
            </header>
            @endauth
            
            <!-- Scrollable Page Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 custom-scrollbar scroll-smooth">
                <div class="max-w-7xl mx-auto space-y-6">
                    <!-- Entrance Animation for Content -->
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" 
                         x-show="show"
                         x-transition:enter="transition ease-out duration-700 delay-100"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
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
