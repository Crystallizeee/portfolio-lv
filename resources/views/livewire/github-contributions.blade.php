<div id="github" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> gh contribution-graph --user={{ config('services.github.username', 'Crystallizeee') }}
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                GitHub Activity
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                My open‑source contribution footprint over the past year.
            </p>
        </div>

        @if($contributions)
        <div class="glass-card p-6 md:p-8 group hover:glow-cyan transition-all duration-300">
            <!-- Stats Row -->
            @if($stats)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="text-center p-3 rounded-xl bg-slate-800/40 border border-slate-700/30">
                    <div class="text-2xl font-bold font-mono text-cyan-400">
                        {{ number_format($contributions['total']) }}
                    </div>
                    <div class="text-xs text-slate-500 uppercase tracking-wider mt-1">Contributions</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-800/40 border border-slate-700/30">
                    <div class="text-2xl font-bold font-mono text-purple-400">
                        {{ $stats['repos'] }}
                    </div>
                    <div class="text-xs text-slate-500 uppercase tracking-wider mt-1">Repositories</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-800/40 border border-slate-700/30">
                    <div class="text-2xl font-bold font-mono text-yellow-400">
                        {{ $stats['stars'] }}
                    </div>
                    <div class="text-xs text-slate-500 uppercase tracking-wider mt-1">Stars Earned</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-800/40 border border-slate-700/30">
                    <div class="text-2xl font-bold font-mono text-emerald-400">
                        {{ $stats['followers'] }}
                    </div>
                    <div class="text-xs text-slate-500 uppercase tracking-wider mt-1">Followers</div>
                </div>
            </div>
            @endif

            <!-- Contribution Heatmap -->
            <div class="overflow-x-auto custom-scrollbar pb-2">
                <div class="min-w-[750px]">
                    <!-- Month Labels -->
                    <div class="flex mb-2" style="margin-left: 32px;">
                        @php
                            $monthLabels = [];
                            $lastMonth = '';
                            foreach ($contributions['weeks'] as $idx => $week) {
                                $d = $week['contributionDays'][0]['date'] ?? null;
                                if ($d) {
                                    $m = \Carbon\Carbon::parse($d)->format('M');
                                    if ($m !== $lastMonth) {
                                        $monthLabels[$idx] = $m;
                                        $lastMonth = $m;
                                    }
                                }
                            }
                        @endphp
                        <div class="flex w-full relative" style="gap: 3px; height: 14px;">
                            @foreach($contributions['weeks'] as $idx => $week)
                                <div class="shrink-0 relative" style="width: 10px;">
                                    @if(isset($monthLabels[$idx]))
                                        <span class="text-[10px] font-mono text-slate-500 absolute top-0 left-0 whitespace-nowrap">{{ $monthLabels[$idx] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Day Labels + Grid -->
                    <div class="flex">
                        <!-- Day of week labels -->
                        <div class="flex flex-col justify-between mr-2 py-[2px]" style="height: 90px;">
                            <span class="text-[10px] font-mono text-slate-500 leading-none">&nbsp;</span>
                            <span class="text-[10px] font-mono text-slate-500 leading-none">Mon</span>
                            <span class="text-[10px] font-mono text-slate-500 leading-none">&nbsp;</span>
                            <span class="text-[10px] font-mono text-slate-500 leading-none">Wed</span>
                            <span class="text-[10px] font-mono text-slate-500 leading-none">&nbsp;</span>
                            <span class="text-[10px] font-mono text-slate-500 leading-none">Fri</span>
                            <span class="text-[10px] font-mono text-slate-500 leading-none">&nbsp;</span>
                        </div>

                        <!-- Heatmap Grid -->
                        <div class="flex" style="gap: 3px;">
                            @foreach($contributions['weeks'] as $week)
                                <div class="flex flex-col" style="gap: 3px;">
                                    @foreach($week['contributionDays'] as $day)
                                        @php
                                            $level = $this->getLevel($day['contributionCount']);
                                            
                                            $bgColors = [
                                                0 => '#1e293b99',
                                                1 => '#164e6399',
                                                2 => '#0e749099',
                                                3 => '#06b6d4b3',
                                                4 => '#22d3ee',
                                            ];
                                            
                                            $borderColors = [
                                                0 => '#3341554d',
                                                1 => '#155e7566',
                                                2 => '#0891b266',
                                                3 => '#22d3ee80',
                                                4 => '#67e8f999',
                                            ];

                                            $glows = [
                                                0 => 'none',
                                                1 => 'none',
                                                2 => 'none',
                                                3 => '0 0 4px rgba(34,211,238,0.15)',
                                                4 => '0 0 8px rgba(34,211,238,0.4)',
                                            ];
                                        @endphp
                                        <div
                                            class="rounded-[2px] border transition-all duration-200 hover:scale-150 hover:z-10 relative group/cell cursor-pointer"
                                            style="width: 10px; height: 10px; background-color: {{ $bgColors[$level] }}; border-color: {{ $borderColors[$level] }}; box-shadow: {{ $glows[$level] }};"
                                            title="{{ $day['date'] }}: {{ $day['contributionCount'] }} contribution{{ $day['contributionCount'] !== 1 ? 's' : '' }}"
                                        >
                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-900 border border-slate-700 rounded text-[10px] font-mono text-slate-300 whitespace-nowrap opacity-0 group-hover/cell:opacity-100 transition-opacity pointer-events-none z-20">
                                                <span class="text-cyan-400 font-bold">{{ $day['contributionCount'] }}</span> on {{ \Carbon\Carbon::parse($day['date'])->format('M j, Y') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center justify-between mt-4">
                        <a href="https://github.com/{{ config('services.github.username') }}" 
                           target="_blank"
                           class="text-xs font-mono text-slate-500 hover:text-cyan-400 transition-colors flex items-center space-x-2">
                            <i data-lucide="github" class="w-4 h-4"></i>
                            <span>{{ '@' . config('services.github.username') }}</span>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                        <div class="flex items-center space-x-2">
                            <span class="text-[10px] font-mono text-slate-500">Less</span>
                            <div class="flex" style="gap: 3px;">
                                <div class="rounded-[2px] border border-[#3341554d]" style="width: 10px; height: 10px; background-color: #1e293b99;"></div>
                                <div class="rounded-[2px] border border-[#155e7566]" style="width: 10px; height: 10px; background-color: #164e6399;"></div>
                                <div class="rounded-[2px] border border-[#0891b266]" style="width: 10px; height: 10px; background-color: #0e749099;"></div>
                                <div class="rounded-[2px] border border-[#22d3ee80]" style="width: 10px; height: 10px; background-color: #06b6d4b3;"></div>
                                <div class="rounded-[2px] border border-[#67e8f999]" style="width: 10px; height: 10px; background-color: #22d3ee;"></div>
                            </div>
                            <span class="text-[10px] font-mono text-slate-500">More</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Languages -->
            @if($stats && !empty($stats['languages']))
            <div class="mt-8 pt-6 border-t border-slate-700/30">
                <h3 class="text-xs font-mono text-slate-500 uppercase tracking-wider mb-4">Top Languages</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($stats['languages'] as $langName => $langData)
                        <div class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-slate-800/40 border border-slate-700/30">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $langData['color'] }}"></span>
                            <span class="text-sm font-mono text-slate-300">{{ $langName }}</span>
                            <span class="text-xs font-mono text-slate-500">{{ $langData['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @else
            <div class="glass-card p-12 text-center">
                <i data-lucide="github" class="w-16 h-16 text-slate-600 mx-auto mb-4"></i>
                <h3 class="text-xl font-semibold text-white mb-2">GitHub Not Connected</h3>
                <p class="text-slate-400">Set GITHUB_TOKEN and GITHUB_USERNAME in your .env to display contribution graph.</p>
            </div>
        @endif
    </div>
</div>
