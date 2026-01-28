<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ./manage-certificates.sh
            </div>
            <p class="text-slate-400">Kelola sertifikasi profesional Anda</p>
        </div>
        <button 
            wire:click="openModal"
            class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/50 rounded-xl text-yellow-400 hover:from-yellow-500/30 hover:to-orange-500/30 hover:border-yellow-400 transition-all duration-300"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span class="font-medium">Add Certificate</span>
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Certificates Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($certificates as $cert)
            <div class="glass-card p-6 group hover:border-yellow-500/30 transition-all duration-300">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                        <i data-lucide="award" class="w-5 h-5 text-yellow-400"></i>
                    </div>
                    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button 
                            wire:click="edit({{ $cert['id'] }})"
                            class="p-2 text-slate-400 hover:text-cyan-400 transition-colors"
                        >
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <button 
                            wire:click="delete({{ $cert['id'] }})"
                            wire:confirm="Are you sure you want to delete this certificate?"
                            class="p-2 text-slate-400 hover:text-red-400 transition-colors"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-white mb-1">{{ $cert['name'] }}</h3>
                <p class="text-slate-400 text-sm mb-2">{{ $cert['issuer'] }}</p>
                <p class="text-slate-500 text-xs">{{ $cert['year'] }}</p>
                
                @if($cert['credential_id'])
                    <div class="mt-3 pt-3 border-t border-slate-700">
                        <p class="text-xs text-slate-500">
                            ID: <span class="text-slate-400 font-mono">{{ $cert['credential_id'] }}</span>
                        </p>
                    </div>
                @endif
                
                @if($cert['credential_url'])
                    <a 
                        href="{{ $cert['credential_url'] }}" 
                        target="_blank"
                        class="mt-3 inline-flex items-center space-x-1 text-xs text-cyan-400 hover:text-cyan-300 transition-colors"
                    >
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                        <span>View Credential</span>
                    </a>
                @endif
            </div>
        @empty
            <div class="col-span-full glass-card p-12 text-center">
                <i data-lucide="award" class="w-12 h-12 text-slate-600 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-slate-400 mb-2">No Certificates Yet</h3>
                <p class="text-slate-500 mb-4">Start adding your professional certifications</p>
                <button 
                    wire:click="openModal"
                    class="px-4 py-2 bg-yellow-500/20 border border-yellow-500/50 rounded-lg text-yellow-400 hover:bg-yellow-500/30 transition-colors"
                >
                    Add Your First Certificate
                </button>
            </div>
        @endforelse
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <div class="fixed inset-x-4 top-20 md:inset-x-auto md:left-1/2 md:-translate-x-1/2 md:w-full md:max-w-lg">
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white font-mono">
                        {{ $editingId ? 'Edit Certificate' : 'Add Certificate' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Certificate Name *</label>
                        <input 
                            type="text" 
                            wire:model="form.name"
                            placeholder="e.g. ISO 27001 Lead Implementer"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-yellow-400 focus:outline-none transition-colors"
                        >
                        @error('form.name') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Issuer *</label>
                            <input 
                                type="text" 
                                wire:model="form.issuer"
                                placeholder="e.g. PECB"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-yellow-400 focus:outline-none transition-colors"
                            >
                            @error('form.issuer') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Year *</label>
                            <input 
                                type="text" 
                                wire:model="form.year"
                                placeholder="e.g. 2024"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-yellow-400 focus:outline-none transition-colors"
                            >
                            @error('form.year') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Credential ID</label>
                        <input 
                            type="text" 
                            wire:model="form.credential_id"
                            placeholder="Optional"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-yellow-400 focus:outline-none transition-colors"
                        >
                        @error('form.credential_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Credential URL</label>
                        <input 
                            type="url" 
                            wire:model="form.credential_url"
                            placeholder="https://..."
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-yellow-400 focus:outline-none transition-colors"
                        >
                        @error('form.credential_url') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="py-2 px-4 text-slate-400 hover:text-white transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="py-2 px-6 bg-yellow-500 hover:bg-yellow-600 rounded-lg text-white font-medium transition-colors"
                        >
                            {{ $editingId ? 'Update' : 'Add Certificate' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
