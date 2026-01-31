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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="bg-[var(--color-cyber-dark)] text-slate-300 antialiased scanline-effect">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-card border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-2">
                    <span class="terminal-text font-mono text-lg">~/benidictus</span>
                    <span class="text-slate-500 cursor-blink">_</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#about" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">About</a>
                    <a href="#experience" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Experience</a>
                    <a href="#lab" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Home Lab</a>
                    <a href="#projects" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Projects</a>
                    <a href="#contact" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Contact</a>
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
