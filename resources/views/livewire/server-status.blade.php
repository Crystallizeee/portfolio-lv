<div wire:poll.10s="refreshStatus" id="lab" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> systemctl status --all
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                Home Lab Infrastructure
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Real-time status of my self-hosted infrastructure. 
                <span class="text-cyan-400 font-mono text-sm">(Auto-refreshes every 10s)</span>
            </p>
        </div>
        
        <!-- Status Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($servers as $server)
                <div class="glass-card p-6 hover:glow-cyan transition-all duration-300 group">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-slate-700/50 flex items-center justify-center group-hover:bg-cyan-400/20 transition-colors">
                                <i data-lucide="{{ $server['icon'] }}" class="w-5 h-5 text-cyan-400"></i>
                            </div>
                            <div>
                                <h3 class="font-mono font-semibold text-white">{{ $server['name'] }}</h3>
                                <p class="text-xs text-slate-500 font-mono">Node: {{ $server['node'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Status Indicator -->
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full status-dot 
                                @if($server['color'] === 'green') bg-green-500
                                @elseif($server['color'] === 'cyan') bg-cyan-500
                                @else bg-blue-500
                                @endif
                            "></span>
                            <span class="text-xs font-mono uppercase tracking-wider
                                @if($server['color'] === 'green') text-green-400
                                @elseif($server['color'] === 'cyan') text-cyan-400
                                @else text-blue-400
                                @endif
                            ">{{ $server['status'] }}</span>
                        </div>
                    </div>
                    
                    <!-- Metrics -->
                    <div class="border-t border-slate-700/50 pt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">{{ $server['metric_label'] }}</span>
                            <span class="font-mono text-lg text-white">{{ $server['metric_value'] }}</span>
                        </div>
                    </div>
                    
                    <!-- Terminal-style footer -->
                    <div class="mt-4 pt-4 border-t border-slate-700/50">
                        <code class="text-xs text-slate-500 font-mono">
                            <span class="text-green-400">●</span> Last checked: {{ now()->format('H:i:s') }}
                        </code>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Additional Info -->
        <div class="mt-12 text-center">
            <p class="text-sm text-slate-500 font-mono">
                <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                Infrastructure runs on Ryzen 5 1600 with 16GB RAM and Tailscale mesh networking.
            </p>
        </div>
    </div>
</div>
