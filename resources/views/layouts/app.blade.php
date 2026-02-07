<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $seo->description ?? 'Benidictus Tri Wibowo - Hybrid GRC & Technical Practitioner specializing in ISO 27001 and Offensive Security' }}">
    <meta name="keywords" content="{{ $seo->keywords ?? 'Cybersecurity, GRC, ISO 27001, Penetration Testing, Laravel, Portfolio' }}">
    <meta property="og:image" content="{{ $seo->og_image ?? '' }}">
    
    <title>{{ $title ?? ($seo->title ?? 'Benidictus Tri Wibowo | Cybersecurity & ICT Risk Professional') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|jetbrains-mono:400,500,600,700" rel="stylesheet" />

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

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
<body class="bg-[var(--color-cyber-dark)] text-slate-300 antialiased scanline-effect">
    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 z-50 glass-card border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-2">
                    <span class="terminal-text font-mono text-lg">~/benidictus</span>
                    <span class="text-slate-500 cursor-blink">_</span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}#about" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">About</a>
                    <a href="{{ route('home') }}#experience" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Experience</a>
                    <a href="{{ route('home') }}#lab" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Home Lab</a>
                    <a href="{{ route('home') }}#projects" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Projects</a>
                    <a href="{{ route('blog.index') }}" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Blog</a>
                    <a href="{{ route('home') }}#contact" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Contact</a>
                </div>
                
                <!-- Mobile Menu Button & Command Palette -->
                <div class="flex items-center space-x-4">
                    <button 
                        @click="$dispatch('toggle-command-palette')"
                        class="hidden md:flex items-center space-x-2 px-3 py-1.5 glass-card text-xs text-slate-400 hover:text-cyan-400 transition-colors"
                    >
                        <kbd class="font-mono">Ctrl</kbd>
                        <span>+</span>
                        <kbd class="font-mono">K</kbd>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="md:hidden p-2 text-slate-400 hover:text-cyan-400 transition-colors"
                    >
                        <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenuOpen"></i>
                        <i data-lucide="x" class="w-6 h-6" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            @click.away="mobileMenuOpen = false"
            class="md:hidden border-t border-slate-700/50 bg-[var(--color-cyber-dark)]/95 backdrop-blur-md"
            x-cloak
        >
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('home') }}#about" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 transition-all">About</a>
                <a href="{{ route('home') }}#experience" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 transition-all">Experience</a>
                <a href="{{ route('home') }}#lab" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 transition-all">Home Lab</a>
                <a href="{{ route('home') }}#projects" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 transition-all">Projects</a>
                <a href="{{ route('blog.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 transition-all">Blog</a>
                <a href="{{ route('home') }}#contact" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 transition-all">Contact</a>
                
                <div class="pt-4 border-t border-slate-700/50 mt-4">
                    <button 
                        @click="$dispatch('toggle-command-palette'); mobileMenuOpen = false"
                        class="w-full flex items-center justify-between px-3 py-2 text-base font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 transition-all"
                    >
                        <span>Command Palette</span>
                        <div class="flex items-center space-x-1 text-xs text-slate-500">
                             <kbd class="font-mono border border-slate-600 rounded px-1">Ctrl</kbd>
                             <span>+</span>
                             <kbd class="font-mono border border-slate-600 rounded px-1">K</kbd>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-700/50 py-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="terminal-text font-mono text-sm">
                    <span class="text-slate-500">></span> built_with: 
                    <span class="text-cyan-400">Laravel</span> | 
                    <span class="text-cyan-400">Livewire</span> | 
                    <span class="text-cyan-400">Tailwind</span>
                </div>
                <div class="text-slate-500 text-sm">
                    © {{ date('Y') }} Benidictus Tri Wibowo. All rights reserved.
                </div>
            </div>
        </div>
    </footer>



    <!-- Command Palette Modal -->
    <div 
        x-data="commandPalette()" 
        x-show="open" 
        x-cloak
        @toggle-command-palette.window="toggle()"
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-[100] overflow-y-auto"
    >
        <!-- Backdrop -->
        <div 
            class="fixed inset-0 bg-black/70 backdrop-blur-sm" 
            @click="open = false"
        ></div>
        
        <!-- Modal -->
        <div class="fixed inset-x-4 top-24 md:inset-x-auto md:left-1/2 md:-translate-x-1/2 md:w-full md:max-w-xl">
            <div class="glass-card glow-cyan overflow-hidden">
                <!-- Terminal Header -->
                <div class="flex items-center space-x-2 px-4 py-3 border-b border-slate-700/50">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="ml-4 font-mono text-sm text-slate-400">terminal</span>
                </div>
                
                <!-- Input -->
                <div class="p-4">
                    <div class="flex items-center space-x-3">
                        <span class="terminal-text font-mono text-sm">user@portfolio:~$</span>
                        <input 
                            x-ref="commandInput"
                            x-model="command"
                            @keydown.enter="handleEnter()"
                            type="text" 
                            placeholder="Type a command..."
                            class="flex-1 bg-transparent border-none outline-none text-slate-200 font-mono text-sm placeholder-slate-500"
                        >
                    </div>
                </div>
                
                <!-- Suggestions -->
                <div class="border-t border-slate-700/50 max-h-64 overflow-y-auto">
                    <template x-for="suggestion in suggestions" :key="suggestion.name">
                        <button 
                            @click="execute(suggestion.name)"
                            class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-700/50 transition-colors"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="terminal-text font-mono text-sm" x-text="suggestion.name"></span>
                            </div>
                            <span class="text-slate-500 text-sm" x-text="suggestion.label"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 1000);
        });

        // Scroll to Top
        const scrollBtn = document.getElementById('scrollToTop');
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollBtn.classList.remove('opacity-0', 'invisible');
            } else {
                scrollBtn.classList.add('opacity-0', 'invisible');
            }
        };

        scrollBtn.addEventListener('click', function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    </script>
    
    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-[100] bg-[var(--color-cyber-dark)] flex items-center justify-center transition-opacity duration-500">
        <div class="text-center">
            <div class="terminal-text text-xl font-mono mb-4">
                <span class="text-green-400">➜</span> <span class="text-cyan-400">~/system</span> <span class="typing-effect">loading_modules...</span>
            </div>
            <div class="w-48 h-1 bg-slate-800 rounded-full overflow-hidden mx-auto">
                <div class="h-full bg-cyan-400 w-1/2 animate-pulse"></div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 z-40 p-3 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/50 rounded-full text-cyan-400 transition-all duration-300 opacity-0 invisible backdrop-blur-sm">
        <i data-lucide="arrow-up" class="w-6 h-6"></i>
    </button>
</body>
</html>
