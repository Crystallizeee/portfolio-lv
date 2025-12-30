<div wire:poll.30s="refreshStatus" id="lab" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> htop --no-color
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                Home Lab Infrastructure
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Live monitoring of my self-hosted infrastructure and services.
            </p>
        </div>
        
        <!-- Server Cards Grid - Same as Projects -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($servers as $server)
                <div class="glass-card p-6 hover:glow-cyan transition-all duration-300 group relative overflow-hidden">
                    <!-- Background Glow Effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <!-- Header -->
                    <div class="relative flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-lg bg-slate-700/50 flex items-center justify-center group-hover:bg-cyan-400/20 transition-colors">
                                <i data-lucide="{{ $server['icon'] }}" class="w-6 h-6 text-cyan-400"></i>
                            </div>
                            <div>
                                <h3 class="font-mono text-lg font-semibold text-white group-hover:text-cyan-400 transition-colors">
                                    {{ $server['name'] }}
                                </h3>
                                <span class="text-xs text-slate-500 font-mono">{{ $server['node'] }}</span>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="flex items-center space-x-2 px-3 py-1 rounded-full
                            @if($server['color'] === 'green') bg-green-500/20 border border-green-500/30
                            @elseif($server['color'] === 'red') bg-red-500/20 border border-red-500/30
                            @else bg-cyan-500/20 border border-cyan-500/30
                            @endif
                        ">
                            <span class="w-2 h-2 rounded-full status-dot
                                @if($server['color'] === 'green') bg-green-500
                                @elseif($server['color'] === 'red') bg-red-500
                                @else bg-cyan-500
                                @endif
                            "></span>
                            <span class="text-xs font-mono uppercase
                                @if($server['color'] === 'green') text-green-400
                                @elseif($server['color'] === 'red') text-red-400
                                @else text-cyan-400
                                @endif
                            ">{{ $server['status'] }}</span>
                        </div>
                    </div>
                    
                    <!-- Metrics -->
                    <div class="relative space-y-3 mb-6">
                        <!-- CPU -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-400">CPU Usage</span>
                                <span class="font-mono text-cyan-400">{{ $server['cpu'] }}</span>
                            </div>
                            @php $cpuValue = floatval(str_replace('%', '', $server['cpu'])); @endphp
                            <div class="h-2 bg-slate-700/50 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-cyan-500 to-cyan-400 rounded-full" 
                                     style="width: {{ $server['cpu'] !== '-' && $server['cpu'] !== 'N/A' ? min($cpuValue, 100) : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Memory -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-400">Memory</span>
                                <span class="font-mono text-purple-400">{{ $server['memory'] }}</span>
                            </div>
                            @php $memValue = floatval(str_replace('%', '', $server['memory'])); @endphp
                            <div class="h-2 bg-slate-700/50 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-purple-400 rounded-full" 
                                     style="width: {{ $server['memory'] !== '-' && $server['memory'] !== 'N/A' ? min($memValue, 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tech Stack like tags -->
                    <div class="relative flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs font-mono bg-slate-700/50 text-slate-300 rounded border border-slate-600/50">
                            <i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>{{ $server['uptime'] }}
                        </span>
                        <span class="px-2 py-1 text-xs font-mono bg-slate-700/50 text-slate-300 rounded border border-slate-600/50">
                            Proxmox
                        </span>
                        <span class="px-2 py-1 text-xs font-mono bg-slate-700/50 text-slate-300 rounded border border-slate-600/50">
                            Tailscale
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
