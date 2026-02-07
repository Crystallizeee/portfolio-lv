<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $globalSeo = \App\Models\SeoMetadata::where('model_type', 'global')->first();
        $seoTitle = $title ?? $globalSeo?->title ?? 'Benidictus Tri Wibowo | Cybersecurity & ICT Risk Professional';
        $seoDescription = $description ?? $globalSeo?->description ?? 'Hybrid GRC & Technical Practitioner specializing in ISO 27001 and Offensive Security';
        $seoKeywords = $keywords ?? $globalSeo?->keywords ?? 'cybersecurity, grc, penetration testing, laravel';
        $seoImage = $og_image ?? $globalSeo?->og_image ?? asset('images/og-default.jpg');
        $seoCanonical = $canonical_url ?? $globalSeo?->canonical_url ?? url()->current();
        $seoIndex = $globalSeo?->indexable ?? true;
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="robots" content="{{ $seoIndex ? 'index, follow' : 'noindex, nofollow' }}">
    <link rel="canonical" href="{{ $seoCanonical }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

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
                    <a href="{{ route('home') }}#about" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">About</a>
                    <a href="{{ route('home') }}#experience" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Experience</a>
                    <a href="{{ route('home') }}#lab" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Home Lab</a>
                    <a href="{{ route('home') }}#projects" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Projects</a>
                    <a href="{{ route('blog.index') }}" class="text-slate-400 hover:text-cyan-400 transition-colors font-medium">Blog</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button 
                        @click="$dispatch('toggle-command-palette')"
                        class="hidden md:flex items-center space-x-2 px-3 py-1.5 glass-card text-xs text-slate-400 hover:text-cyan-400 transition-colors"
                    >
                        <kbd class="font-mono">Ctrl</kbd>
                        <span>+</span>
                        <kbd class="font-mono">K</kbd>
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
    </script>
</body>
</html>
