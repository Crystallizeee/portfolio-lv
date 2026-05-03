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

    <!-- Marked.js for Markdown rendering -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
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
        const initLucide = () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        };
        initLucide();
        document.addEventListener('livewire:navigated', initLucide);
        Livewire.hook('commit', ({ succeed }) => {
            succeed(() => queueMicrotask(initLucide));
        });

        // Scroll to Top
        const scrollBtn = document.getElementById('scrollToTop');
        if (scrollBtn) {
            window.addEventListener('scroll', function () {
                if (document.documentElement.scrollTop > 20) {
                    scrollBtn.classList.remove('opacity-0', 'invisible');
                } else {
                    scrollBtn.classList.add('opacity-0', 'invisible');
                }
            });
            scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        }
    </script>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-28 z-40 p-3 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/50 rounded-full text-cyan-400 transition-all duration-300 opacity-0 invisible backdrop-blur-sm">
        <i data-lucide="arrow-up" class="w-6 h-6"></i>
    </button>

    <!-- AI Chatbot Widget -->
    <div x-data="chatWidget" class="fixed bottom-8 right-8 z-50">
        <!-- Chat Window -->
        <div 
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="mb-4 w-[380px] sm:w-[440px] glass-card border border-slate-700/50 rounded-2xl overflow-hidden shadow-2xl shadow-cyan-500/10"
            x-cloak
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-3 bg-slate-900/80 border-b border-slate-700/50">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                        <i data-lucide="bot" class="w-4 h-4 text-white"></i>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-white">AI Assistant</div>
                        <div class="text-[10px] text-emerald-400 flex items-center space-x-1">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            <span>Online</span>
                        </div>
                    </div>
                </div>
                <button @click="isOpen = false" class="p-1.5 hover:bg-slate-700/50 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-4 h-4 text-slate-400"></i>
                </button>
            </div>

            <!-- Messages -->
            <div 
                x-ref="messagesContainer"
                class="h-[450px] overflow-y-auto p-4 space-y-4 custom-scrollbar"
                style="background: linear-gradient(180deg, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.85) 100%);"
            >
                <template x-for="(msg, i) in messages" :key="i">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div 
                            :class="msg.role === 'user' 
                                ? 'bg-cyan-500/20 border border-cyan-500/30 text-cyan-100 rounded-2xl rounded-br-md' 
                                : 'bg-slate-800/80 border border-slate-700/50 text-slate-200 rounded-2xl rounded-bl-md'"
                            class="max-w-[85%] px-3.5 py-2.5 text-sm leading-relaxed prose prose-invert prose-sm"
                            x-html="msg.role === 'bot' ? marked.parse(msg.text) : msg.text"
                        ></div>
                    </div>
                </template>

                <!-- Typing Indicator -->
                <div x-show="isLoading" class="flex justify-start">
                    <div class="bg-slate-800/80 border border-slate-700/50 rounded-2xl rounded-bl-md px-4 py-3">
                        <div class="flex space-x-1.5">
                            <span class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestion Chips (shown only before first user message) -->
            <div 
                x-show="messages.filter(m => m.role === 'user').length === 0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="px-3 pb-2 flex flex-wrap gap-1.5"
            >
                <template x-for="chip in [
                    'What are Beni\'s main skills?',
                    'Tell me about his experience',
                    'What projects has he built?',
                    'What certifications does he have?',
                    'How can I contact Beni?'
                ]" :key="chip">
                    <button
                        @click="userInput = chip; sendMessage()"
                        class="px-2.5 py-1 text-[10px] bg-slate-800/70 border border-slate-700/60 rounded-full text-slate-400 hover:text-cyan-300 hover:border-cyan-500/40 hover:bg-slate-800 transition-all duration-200 leading-none"
                        x-text="chip"
                    ></button>
                </template>
            </div>

            <!-- Input -->
            <div class="px-4 py-3 border-t border-slate-700/50 bg-slate-900/60">
                <form @submit.prevent="sendMessage()" class="flex items-center space-x-2">
                    <input 
                        x-model="userInput"
                        type="text"
                        placeholder="Ask about my skills, experience..."
                        maxlength="500"
                        class="flex-1 bg-slate-800/60 border border-slate-700/50 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/20 transition-all"
                        :disabled="isLoading"
                    >
                    <button 
                        type="submit"
                        :disabled="isLoading || !userInput.trim()"
                        class="p-2.5 bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/30 rounded-xl text-cyan-400 transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                    >
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </form>
                <div class="text-[10px] text-slate-600 mt-1.5 text-center">Powered by AI · Responses may not always be accurate</div>
            </div>
        </div>

        <!-- Floating Button -->
        <button 
            @click="toggleChat()"
            class="group relative w-14 h-14 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 shadow-lg shadow-cyan-500/25 hover:shadow-cyan-500/40 transition-all duration-300 hover:scale-110 flex items-center justify-center"
        >
            <i data-lucide="message-circle" class="w-6 h-6 text-white" x-show="!isOpen"></i>
            <i data-lucide="x" class="w-6 h-6 text-white" x-show="isOpen" x-cloak></i>
            
            <!-- Notification dot -->
            <span x-show="!hasInteracted && !isOpen" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-[var(--color-cyber-dark)] animate-pulse"></span>
        </button>
    </div>

</body>
</html>
