<section id="skills" class="py-16 bg-slate-900/50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-10">
            <span class="terminal-text font-mono text-sm mb-3 block">
                <span class="text-slate-500">$</span> cat skills.json
            </span>
            <h2 class="font-mono text-2xl md:text-3xl font-bold text-white mb-2">
                Skills & Expertise
            </h2>
        </div>

        @if($skillsByCategory->count() > 0)
            <!-- Centered Flex Skills - Fixed width cards -->
            <div class="flex flex-wrap justify-center gap-2">
                @foreach($skillsByCategory as $category => $skills)
                    @foreach($skills as $skill)
                        <div class="glass-card px-3 py-2 flex items-center space-x-2 group hover:border-cyan-500/50 transition-all duration-200">
                            <!-- Icon -->
                            <div class="w-6 h-6 rounded bg-cyan-500/10 flex items-center justify-center flex-shrink-0 group-hover:bg-cyan-500/20 transition-colors">
                                @if($skill->icon)
                                    <i data-lucide="{{ $skill->icon }}" class="w-3.5 h-3.5 text-cyan-400"></i>
                                @else
                                    <i data-lucide="code" class="w-3.5 h-3.5 text-cyan-400"></i>
                                @endif
                            </div>
                            <!-- Skill Name -->
                            <span class="text-slate-300 text-sm group-hover:text-cyan-400 transition-colors whitespace-nowrap">{{ $skill->name }}</span>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <div class="glass-card p-8 text-center">
                <p class="text-slate-500 text-sm">No skills added yet.</p>
            </div>
        @endif
    </div>
</section>
