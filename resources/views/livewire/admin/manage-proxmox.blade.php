<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> pvesh get /nodes/pve-01/qemu
            </div>
            <p class="text-slate-400">Browse and manage Proxmox VMs & Containers</p>
        </div>
        <button 
            wire:click="refreshList"
            wire:loading.attr="disabled"
            class="flex items-center space-x-2 px-4 py-2 bg-cyan-500/20 text-cyan-400 rounded-lg border border-cyan-500/30 hover:bg-cyan-500/30 transition-all"
        >
            <i data-lucide="refresh-cw" class="w-4 h-4" wire:loading.class="animate-spin" wire:target="refreshList"></i>
            <span>Refresh</span>
        </button>
    </div>

    <!-- Flash Message -->
    @if(session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-lg text-emerald-400 flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Loading State -->
    <div wire:loading wire:target="refreshList" class="mb-6 p-4 bg-cyan-500/10 border border-cyan-500/20 rounded-lg text-cyan-400 flex items-center space-x-2">
        <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Fetching data from Proxmox API...</span>
    </div>

    @if(empty($resources))
        <!-- Empty State -->
        <div class="glass-card p-12 text-center">
            <i data-lucide="server-off" class="w-16 h-16 text-slate-600 mx-auto mb-4"></i>
            <h3 class="text-xl font-semibold text-white mb-2">No Resources Found</h3>
            <p class="text-slate-400 mb-4">Could not connect to Proxmox API or no VMs/containers found.</p>
            <p class="text-slate-500 text-sm font-mono">Check PROXMOX_HOST, PROXMOX_TOKEN_ID, PROXMOX_TOKEN_SECRET in .env</p>
        </div>
    @else
        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @php
                $total = count($resources);
                $running = collect($resources)->where('is_running', true)->count();
                $vms = collect($resources)->where('type', 'qemu')->count();
                $lxcs = collect($resources)->where('type', 'lxc')->count();
            @endphp
            <div class="glass-card p-4 text-center">
                <p class="text-2xl font-bold font-mono text-white">{{ $total }}</p>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Total</p>
            </div>
            <div class="glass-card p-4 text-center">
                <p class="text-2xl font-bold font-mono text-green-400">{{ $running }}</p>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Running</p>
            </div>
            <div class="glass-card p-4 text-center">
                <p class="text-2xl font-bold font-mono text-cyan-400">{{ $vms }}</p>
                <p class="text-xs text-slate-500 uppercase tracking-wider">VMs</p>
            </div>
            <div class="glass-card p-4 text-center">
                <p class="text-2xl font-bold font-mono text-purple-400">{{ $lxcs }}</p>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Containers</p>
            </div>
        </div>

        <!-- Resources Table -->
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-700/50">
                            <th class="text-left p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">VMID</th>
                            <th class="text-left p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="text-left p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="text-left p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">CPU</th>
                            <th class="text-left p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">Memory</th>
                            <th class="text-left p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">Uptime</th>
                            <th class="text-center p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">Home Lab</th>
                            <th class="text-center p-4 text-xs font-mono text-slate-500 uppercase tracking-wider">Projects</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources as $resource)
                            <tr class="border-b border-slate-700/30 hover:bg-slate-800/30 transition-colors">
                                <!-- VMID -->
                                <td class="p-4">
                                    <span class="font-mono text-sm text-cyan-400">{{ $resource['vmid'] }}</span>
                                </td>

                                <!-- Name -->
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <i data-lucide="{{ $resource['type'] === 'qemu' ? 'monitor' : 'container' }}" class="w-4 h-4 text-slate-400"></i>
                                        <span class="text-sm font-medium text-white">{{ $resource['name'] }}</span>
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="p-4">
                                    <span class="px-2 py-1 text-xs font-mono rounded
                                        {{ $resource['type'] === 'qemu' 
                                            ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' 
                                            : 'bg-purple-500/20 text-purple-400 border border-purple-500/30' 
                                        }}">
                                        {{ $resource['type_label'] }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full {{ $resource['is_running'] ? 'bg-green-500 status-dot' : 'bg-red-500' }}"></span>
                                        <span class="text-xs font-mono uppercase {{ $resource['is_running'] ? 'text-green-400' : 'text-red-400' }}">
                                            {{ $resource['status'] }}
                                        </span>
                                    </div>
                                </td>

                                <!-- CPU -->
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-16 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-cyan-500 rounded-full" style="width: {{ min($resource['cpu'], 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-mono text-slate-400">{{ $resource['cpu'] }}%</span>
                                    </div>
                                </td>

                                <!-- Memory -->
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-16 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-purple-500 rounded-full" style="width: {{ min($resource['memory'], 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-mono text-slate-400">{{ $resource['memory'] }}%</span>
                                    </div>
                                </td>

                                <!-- Uptime -->
                                <td class="p-4">
                                    <span class="text-xs font-mono text-slate-400">{{ $resource['uptime'] }}</span>
                                </td>

                                <!-- Home Lab Toggle -->
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button 
                                            wire:click="toggleHomelab({{ $resource['vmid'] }}, '{{ addslashes($resource['name']) }}', '{{ $resource['type_label'] }}')"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900
                                                {{ $this->isOnHomelab($resource['vmid']) 
                                                    ? 'bg-emerald-500' 
                                                    : 'bg-slate-700' 
                                                }}"
                                        >
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                                {{ $this->isOnHomelab($resource['vmid']) 
                                                    ? 'translate-x-6' 
                                                    : 'translate-x-1' 
                                                }}"></span>
                                        </button>

                                        @if($this->isOnHomelab($resource['vmid']))
                                            <button 
                                                wire:click="startEditAlias({{ $resource['vmid'] }})"
                                                class="p-1 text-slate-500 hover:text-emerald-400 transition-colors"
                                                title="Edit Alias"
                                            >
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <!-- Projects Toggle -->
                                <td class="p-4 text-center">
                                    <button 
                                        wire:click="toggleLanding({{ $resource['vmid'] }}, '{{ addslashes($resource['name']) }}', '{{ $resource['type_label'] }}')"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900
                                            {{ $this->isLinked($resource['vmid']) 
                                                ? 'bg-cyan-500' 
                                                : 'bg-slate-700' 
                                            }}"
                                    >
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                            {{ $this->isLinked($resource['vmid']) 
                                                ? 'translate-x-6' 
                                                : 'translate-x-1' 
                                            }}"></span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 flex items-center space-x-6 text-xs text-slate-500">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                <span>Home Lab = Shown in "Home Lab Infrastructure" section</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-cyan-500"></div>
                <span>Projects = Shown in "Projects & Labs" section</span>
            </div>
        </div>
    @endif

    <!-- Edit Alias Modal -->
    @if($editingVmid)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
            <div class="glass-card w-full max-w-md p-6 border-cyan-500/30">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-mono font-bold text-white">Edit Display Name</h3>
                    <button wire:click="cancelEdit" class="text-slate-500 hover:text-white transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-mono text-slate-500 uppercase tracking-wider mb-2">Original Name</label>
                        <div class="p-3 bg-slate-800/50 border border-slate-700 rounded-lg text-slate-400 font-mono">
                            {{ collect($resources)->firstWhere('vmid', $editingVmid)['name'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <label for="newAlias" class="block text-xs font-mono text-slate-500 uppercase tracking-wider mb-2">Alias (Display Name)</label>
                        <input 
                            type="text" 
                            id="newAlias" 
                            wire:model="newAlias"
                            placeholder="Enter display name..."
                            class="w-full bg-slate-800/50 border border-slate-700 rounded-lg p-3 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all font-mono"
                            wire:keydown.enter="saveAlias"
                        >
                        <p class="mt-2 text-[10px] text-slate-500 font-mono">
                            Leave empty to use original name. This name will appear on the landing page.
                        </p>
                    </div>

                    <div class="flex items-center space-x-3 mt-8">
                        <button 
                            wire:click="saveAlias"
                            class="flex-1 py-3 bg-cyan-600 hover:bg-cyan-500 text-white rounded-lg font-mono font-bold transition-all"
                        >
                            Save Alias
                        </button>
                        <button 
                            wire:click="cancelEdit"
                            class="flex-1 py-3 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg font-mono transition-all"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
