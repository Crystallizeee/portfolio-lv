<section id="cybersec-training" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> cat cybersec-training.json
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                Cybersecurity Training
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Continuous learning through hands-on labs, CTF challenges, and SOC simulations.
            </p>
        </div>

        @if($profiles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            @foreach($profiles as $profile)
                @php
                    $isTHM = $profile->platform === 'tryhackme';
                    $platformColor = $isTHM ? '#88cc14' : '#1e88e5';
                    $platformColorRgb = $isTHM ? '136, 204, 20' : '30, 136, 229';
                    $gradientFrom = $isTHM ? 'from-[#88cc14]/10' : 'from-[#1e88e5]/10';
                    $gradientTo = $isTHM ? 'to-[#88cc14]/5' : 'to-[#1e88e5]/5';
                    $borderHover = $isTHM ? 'hover:border-[#88cc14]/50' : 'hover:border-[#1e88e5]/50';
                @endphp

                <div class="glass-card group transition-all duration-500 {{ $borderHover }} relative overflow-hidden"
                     style="--platform-color: {{ $platformColor }}; --platform-rgb: {{ $platformColorRgb }};">
                    
                    {{-- Glow effect on hover --}}
                    <div class="absolute -inset-px rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"
                         style="background: linear-gradient(135deg, rgba({{ $platformColorRgb }}, 0.15), transparent 50%);"></div>
                    
                    {{-- Top accent bar --}}
                    <div class="h-1 rounded-t-xl" style="background: linear-gradient(90deg, {{ $platformColor }}, transparent);"></div>

                    <div class="p-6 relative z-10">
                        {{-- Platform Header --}}
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                {{-- Platform Icon --}}
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center border transition-all duration-300 group-hover:scale-110"
                                     style="background: rgba({{ $platformColorRgb }}, 0.1); border-color: rgba({{ $platformColorRgb }}, 0.2);">
                                    @if($isTHM)
                                        {{-- TryHackMe shield icon --}}
                                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="{{ $platformColor }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                            <path d="M9 12l2 2 4-4"/>
                                        </svg>
                                    @else
                                        {{-- LetsDefend SOC icon --}}
                                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="{{ $platformColor }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                            <line x1="8" y1="21" x2="16" y2="21"/>
                                            <line x1="12" y1="17" x2="12" y2="21"/>
                                            <path d="M7 8h2m2 0h2m2 0h2M7 11h10"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-mono text-lg font-bold text-white group-hover:transition-colors" style="--tw-text-opacity: 1;">
                                        {{ $profile->platform_name }}
                                    </h3>
                                    <p class="text-xs font-mono text-slate-500">
                                        {{ '@' . $profile->username }}
                                    </p>
                                </div>
                            </div>

                            {{-- Rank Badge --}}
                            @if($profile->rank)
                            <div class="px-3 py-1.5 rounded-lg text-xs font-mono font-bold border transition-all duration-300"
                                 style="background: rgba({{ $platformColorRgb }}, 0.1); border-color: rgba({{ $platformColorRgb }}, 0.3); color: {{ $platformColor }};">
                                {{ $profile->rank }}
                            </div>
                            @endif
                        </div>

                        {{-- TryHackMe Badge Image --}}
                        @if($isTHM && $profile->badge_image_url)
                        <div class="mb-6 rounded-xl overflow-hidden bg-slate-800/50 border border-slate-700/30 p-3">
                            <img 
                                src="{{ $profile->badge_image_url }}" 
                                alt="TryHackMe Badge for {{ $profile->username }}"
                                class="w-full h-auto rounded-lg"
                                loading="lazy"
                                onerror="this.parentElement.style.display='none'"
                            >
                        </div>
                        @endif

                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            @if($profile->rooms_completed > 0)
                            <div class="p-3 rounded-xl bg-slate-800/40 border border-slate-700/30 text-center group/stat hover:border-slate-600/50 transition-all">
                                <div class="text-xl font-bold font-mono transition-colors"
                                     style="color: {{ $platformColor }};">
                                    {{ number_format($profile->rooms_completed) }}
                                </div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-wider mt-1 font-mono">
                                    {{ $isTHM ? 'Rooms' : 'Labs' }}
                                </div>
                            </div>
                            @endif

                            @if($profile->badges_count > 0)
                            <div class="p-3 rounded-xl bg-slate-800/40 border border-slate-700/30 text-center group/stat hover:border-slate-600/50 transition-all">
                                <div class="text-xl font-bold font-mono text-yellow-400">
                                    {{ number_format($profile->badges_count) }}
                                </div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-wider mt-1 font-mono">Badges</div>
                            </div>
                            @endif

                            @if($profile->points > 0)
                            <div class="p-3 rounded-xl bg-slate-800/40 border border-slate-700/30 text-center group/stat hover:border-slate-600/50 transition-all">
                                <div class="text-xl font-bold font-mono text-purple-400">
                                    {{ number_format($profile->points) }}
                                </div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-wider mt-1 font-mono">Points</div>
                            </div>
                            @endif

                            @if($profile->streak > 0)
                            <div class="p-3 rounded-xl bg-slate-800/40 border border-slate-700/30 text-center group/stat hover:border-slate-600/50 transition-all">
                                <div class="text-xl font-bold font-mono text-orange-400">
                                    🔥 {{ $profile->streak }}
                                </div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-wider mt-1 font-mono">Streak</div>
                            </div>
                            @endif

                            @if($profile->top_percent)
                            <div class="p-3 rounded-xl bg-slate-800/40 border border-slate-700/30 text-center group/stat hover:border-slate-600/50 transition-all col-span-2">
                                <div class="text-xl font-bold font-mono text-emerald-400">
                                    Top {{ $profile->top_percent }}
                                </div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-wider mt-1 font-mono">Global Ranking</div>
                            </div>
                            @endif
                        </div>

                        {{-- View Profile Link --}}
                        <a href="{{ $profile->profile_url ?? $profile->generated_profile_url }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="flex items-center justify-center w-full px-4 py-3 rounded-xl text-sm font-mono font-medium border transition-all duration-300 group/btn"
                           style="color: {{ $platformColor }}; border-color: rgba({{ $platformColorRgb }}, 0.3); background: rgba({{ $platformColorRgb }}, 0.05);"
                           onmouseover="this.style.background='rgba({{ $platformColorRgb }}, 0.15)'; this.style.borderColor='rgba({{ $platformColorRgb }}, 0.5)'; this.style.boxShadow='0 0 20px rgba({{ $platformColorRgb }}, 0.15)';"
                           onmouseout="this.style.background='rgba({{ $platformColorRgb }}, 0.05)'; this.style.borderColor='rgba({{ $platformColorRgb }}, 0.3)'; this.style.boxShadow='none';">
                            <span>View Profile</span>
                            <i data-lucide="external-link" class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="glass-card p-12 text-center max-w-lg mx-auto">
            <i data-lucide="shield" class="w-16 h-16 text-slate-600 mx-auto mb-4"></i>
            <h3 class="text-xl font-semibold text-white mb-2">No Training Profiles</h3>
            <p class="text-slate-400">Cybersecurity training platform profiles will appear here when added.</p>
        </div>
        @endif
    </div>
</section>
