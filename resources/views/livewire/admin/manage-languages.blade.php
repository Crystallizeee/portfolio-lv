<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ./manage-languages.sh
            </div>
            <p class="text-slate-400">Kelola kemampuan bahasa Anda</p>
        </div>
        <button 
            wire:click="openModal"
            class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/50 rounded-xl text-green-400 hover:from-green-500/30 hover:to-emerald-500/30 hover:border-green-400 transition-all duration-300"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span class="font-medium">Add Language</span>
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Languages Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($languages as $lang)
            <div class="glass-card p-6 group hover:border-green-500/30 transition-all duration-300">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                        <i data-lucide="languages" class="w-5 h-5 text-green-400"></i>
                    </div>
                    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button 
                            wire:click="edit({{ $lang['id'] }})"
                            class="p-2 text-slate-400 hover:text-cyan-400 transition-colors"
                        >
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <button 
                            wire:click="delete({{ $lang['id'] }})"
                            wire:confirm="Are you sure you want to delete this language?"
                            class="p-2 text-slate-400 hover:text-red-400 transition-colors"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-white mb-1">{{ $lang['name'] }}</h3>
                <div class="mt-2 inline-block px-2 py-1 bg-green-500/10 rounded text-xs text-green-400 border border-green-500/20">
                    {{ $lang['level'] }}
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card p-12 text-center">
                <i data-lucide="languages" class="w-12 h-12 text-slate-600 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-slate-400 mb-2">No Languages Yet</h3>
                <p class="text-slate-500 mb-4">Add languages you speak</p>
                <button 
                    wire:click="openModal"
                    class="px-4 py-2 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 hover:bg-green-500/30 transition-colors"
                >
                    Add Your First Language
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
                        {{ $editingId ? 'Edit Language' : 'Add Language' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Language Name *</label>
                        <input 
                            type="text" 
                            wire:model="form.name"
                            placeholder="e.g. English"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-green-400 focus:outline-none transition-colors"
                        >
                        @error('form.name') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Proficiency Level *</label>
                        <select 
                            wire:model="form.level"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-green-400 focus:outline-none transition-colors"
                        >
                            <option value="">Select Level</option>
                            <option value="Native">Native</option>
                            <option value="Fluent">Fluent</option>
                            <option value="Professional">Professional</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Basic">Basic</option>
                        </select>
                        @error('form.level') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
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
                            class="py-2 px-6 bg-green-500 hover:bg-green-600 rounded-lg text-white font-medium transition-colors"
                        >
                            {{ $editingId ? 'Update' : 'Add Language' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
