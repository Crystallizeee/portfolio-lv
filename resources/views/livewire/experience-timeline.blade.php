<div id="experience" class="py-20 bg-slate-900/50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> cat /var/log/career.log
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                Professional Experience
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                A journey through GRC, Quality Assurance, and Security.
            </p>
        </div>
        
        <!-- Timeline -->
        <div class="relative">
            <!-- Vertical Line -->
            <div class="absolute left-0 md:left-1/2 transform md:-translate-x-px top-0 bottom-0 w-0.5 bg-gradient-to-b from-cyan-500 via-cyan-500/50 to-transparent"></div>
            
            @foreach($experiences as $index => $experience)
                <div class="relative mb-12 last:mb-0">
                    <!-- Timeline Dot -->
                    <div class="absolute left-0 md:left-1/2 transform -translate-x-1/2 w-4 h-4 rounded-full border-2 border-cyan-500 bg-slate-900 z-10 glow-cyan"></div>
                    
                    <!-- Content Card -->
                    <div class="ml-8 md:ml-0 md:w-1/2 {{ $index % 2 === 0 ? 'md:pr-12' : 'md:pl-12 md:ml-auto' }}">
                        <div class="glass-card p-6 hover:glow-cyan transition-all duration-300">
                            <!-- Type Badge -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 rounded-full text-xs font-mono uppercase tracking-wider
                                    {{ $experience->type === 'GRC' ? 'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30' : 'bg-purple-500/20 text-purple-400 border border-purple-500/30' }}
                                ">
                                    {{ $experience->type }}
                                </span>
                                <span class="text-sm text-slate-500 font-mono">{{ $experience->date_range }}</span>
                            </div>
                            
                            <!-- Role & Company -->
                            <h3 class="font-mono text-xl font-semibold text-white mb-2">
                                {{ $experience->role }}
                            </h3>
                            <div class="flex items-center text-cyan-400 mb-4">
                                <i data-lucide="building-2" class="w-4 h-4 mr-2"></i>
                                <span class="font-medium">{{ $experience->company }}</span>
                            </div>
                            
                            <!-- Description -->
                            <p class="text-slate-400 text-sm leading-relaxed">
                                {{ $experience->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
