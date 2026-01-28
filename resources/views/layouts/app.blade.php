<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Benidictus Tri Wibowo - Hybrid GRC & Technical Practitioner specializing in ISO 27001 and Offensive Security">
    
    <title>{{ $title ?? 'Benidictus Tri Wibowo | Cybersecurity & ICT Risk Professional' }}</title>

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
    </script>
</body>
</html>
