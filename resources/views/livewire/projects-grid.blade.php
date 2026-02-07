<div id="projects" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> ls -la ~/projects
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                Projects & Labs
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Personal projects, automation scripts, and security lab environments.
            </p>
        </div>
        
        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($projects as $project)
                <div class="glass-card p-6 hover:glow-cyan transition-all duration-300 group relative overflow-hidden flex flex-col h-full">
                    <!-- Background Glow Effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <!-- Header -->
                    <div class="relative flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-lg bg-slate-700/50 flex items-center justify-center group-hover:bg-cyan-400/20 transition-colors">
                                @if($project->type === 'Home Lab')
                                    <i data-lucide="server" class="w-6 h-6 text-cyan-400"></i>
                                @else
                                    <i data-lucide="code-2" class="w-6 h-6 text-cyan-400"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-mono text-lg font-semibold text-white group-hover:text-cyan-400 transition-colors">
                                    <a href="{{ route('projects.show', $project->slug ?? $project->id) }}" class="focus:outline-none">
                                        <span class="absolute inset-0"></span>
                                        {{ $project->title }}
                                    </a>
                                </h3>
                                <span class="text-xs text-slate-500 font-mono">{{ $project->type }}</span>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="flex items-center space-x-2 px-3 py-1 rounded-full
                            {{ $project->status === 'online' ? 'bg-green-500/20 border border-green-500/30' : 'bg-slate-500/20 border border-slate-500/30' }}
                        ">
                            <span class="w-2 h-2 rounded-full status-dot
                                {{ $project->status === 'online' ? 'bg-green-500' : 'bg-slate-500' }}
                            "></span>
                            <span class="text-xs font-mono uppercase
                                {{ $project->status === 'online' ? 'text-green-400' : 'text-slate-400' }}
                            ">{{ $project->status }}</span>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="relative text-slate-400 text-sm leading-relaxed mb-6 flex-grow">
                        {{ $project->description }}
                    </p>
                    
                    <!-- Tech Stack -->
                    <div class="relative flex flex-wrap gap-2 mb-6">
                        @foreach($project->tech_stack as $tech)
                            <span class="px-2 py-1 text-xs font-mono bg-slate-700/50 text-slate-300 rounded border border-slate-600/50 hover:border-cyan-500/50 hover:text-cyan-400 transition-colors">
                                {{ $tech }}
                            </span>
                        @endforeach
                    </div>
                    
                    <!-- Links -->
                    <div class="relative mt-auto pt-4 border-t border-slate-700/50 flex items-center justify-between z-10">
                        <a href="{{ route('projects.show', $project->slug ?? $project->id) }}" class="inline-flex items-center text-sm text-slate-300 hover:text-cyan-400 font-medium transition-colors">
                            <span>Read Case Study</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                        </a>

                        @if($project->url)
                            <a href="{{ $project->url }}" target="_blank" class="inline-flex items-center text-sm text-cyan-400 hover:text-cyan-300 font-mono transition-colors">
                                <i data-lucide="external-link" class="w-4 h-4 mr-2"></i>
                                Live Demo
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
