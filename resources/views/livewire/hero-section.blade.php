<div class="min-h-screen flex items-center justify-center pt-16 relative overflow-hidden">
    <!-- Background Grid Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: linear-gradient(var(--color-cyber-cyan) 1px, transparent 1px), linear-gradient(90deg, var(--color-cyber-cyan) 1px, transparent 1px); background-size: 50px 50px;"></div>
    </div>
    
    <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
        <!-- Terminal-style greeting -->
        <div class="mb-8">
            <span class="terminal-text font-mono text-sm md:text-base">
                <span class="text-slate-500">$</span> whoami
            </span>
        </div>
        
        <!-- Main Name -->
        <h1 class="font-mono text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 tracking-tight">
            {{ $name }}
        </h1>
        
        <!-- Typewriter Effect -->
        <div 
            x-data="typewriter()" 
            class="h-12 flex items-center justify-center"
        >
            <span class="text-xl md:text-2xl text-slate-400 font-mono">
                <span class="text-cyan-400">&gt;</span>
                <span x-text="currentText"></span>
                <span class="cursor-blink text-cyan-400">|</span>
            </span>
        </div>
        
        <!-- Brief Description -->
        <p class="mt-8 text-slate-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
            Hybrid GRC & Technical Practitioner specializing in 
            <span class="text-cyan-400">ISO 27001</span> compliance and 
            <span class="text-cyan-400">Offensive Security</span> assessments.
        </p>
        
        <!-- CTAs -->
        <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
            <a 
                href="#lab" 
                class="inline-flex items-center justify-center px-8 py-4 glass-card glow-cyan text-cyan-400 font-mono font-medium hover:bg-cyan-400/10 transition-all duration-300 group"
            >
                <i data-lucide="server" class="w-5 h-5 mr-2"></i>
                View Infrastructure
                <i data-lucide="arrow-down" class="w-4 h-4 ml-2 group-hover:translate-y-1 transition-transform"></i>
            </a>
            
            <a 
                href="#experience" 
                class="inline-flex items-center justify-center px-8 py-4 border border-slate-600 text-slate-300 font-mono font-medium hover:border-cyan-400 hover:text-cyan-400 transition-all duration-300 rounded-lg"
            >
                <i data-lucide="briefcase" class="w-5 h-5 mr-2"></i>
                View Experience
            </a>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <i data-lucide="chevrons-down" class="w-6 h-6 text-slate-500"></i>
        </div>
    </div>
</div>
