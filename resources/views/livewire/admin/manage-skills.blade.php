<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ls -la /skills
            </div>
            <p class="text-slate-400">Kelola skill dan keahlian Anda</p>
        </div>
        <button 
            wire:click="openCreateModal"
            class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/50 rounded-xl text-green-400 hover:from-green-500/30 hover:to-emerald-500/30 hover:border-green-400 transition-all duration-300 shadow-lg shadow-green-500/10"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span class="font-medium">Tambah Skill</span>
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/50 rounded-xl text-green-400 flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Skills Table -->
    <div class="glass-card overflow-hidden shadow-xl shadow-black/20">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-slate-800/80 to-slate-700/80">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Skill</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse ($skills as $skill)
                    <tr class="hover:bg-slate-700/30 transition-all duration-200 group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                @if($skill->icon)
                                    <i data-lucide="{{ $skill->icon }}" class="w-5 h-5 text-cyan-400"></i>
                                @endif
                                <span class="text-white font-medium group-hover:text-green-400 transition-colors">{{ $skill->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1.5 text-xs font-mono bg-gradient-to-r from-slate-700/80 to-slate-600/80 rounded-lg text-slate-200 border border-slate-600/50">{{ $skill->category }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-24 h-2 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full" style="width: {{ $skill->level }}%"></div>
                                </div>
                                <span class="text-cyan-400 text-xs font-mono">{{ $skill->level }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <code class="text-slate-400 text-sm">{{ $skill->icon ?? '-' }}</code>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end space-x-1">
                                <button 
                                    wire:click="openEditModal({{ $skill->id }})"
                                    class="p-2.5 text-slate-400 hover:text-green-400 hover:bg-green-500/10 rounded-lg transition-all duration-200"
                                    title="Edit"
                                >
                                    <i data-lucide="edit-3" class="w-4 h-4 pointer-events-none"></i>
                                </button>
                                <button 
                                    wire:click="delete({{ $skill->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus skill ini?"
                                    class="p-2.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all duration-200"
                                    title="Delete"
                                >
                                    <i data-lucide="trash-2" class="w-4 h-4 pointer-events-none"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-700/50 flex items-center justify-center">
                                <i data-lucide="cpu" class="w-8 h-8 opacity-50"></i>
                            </div>
                            <p class="text-lg font-medium mb-1">Belum ada skill</p>
                            <p class="text-sm">Klik "Tambah Skill" untuk memulai.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 10)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <!-- Backdrop -->
            <div 
                class="fixed inset-0 bg-black/95 backdrop-blur-sm"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="closeModal"
            ></div>
            
            <!-- Modal Content -->
            <div 
                class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <div class="bg-slate-800 border border-slate-600/50 shadow-2xl shadow-green-500/10 overflow-hidden rounded-xl">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 border-b border-slate-600/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-500 shadow-lg shadow-red-500/50"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500 shadow-lg shadow-yellow-500/50"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500 shadow-lg shadow-green-500/50"></div>
                                </div>
                                <span class="font-mono text-sm text-slate-300">
                                    {{ $isEditing ? '~/edit-skill.sh' : '~/new-skill.sh' }}
                                </span>
                            </div>
                            <button 
                                wire:click="closeModal" 
                                class="w-8 h-8 rounded-lg bg-slate-700/50 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-600/50 transition-all duration-200"
                            >
                                X
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-white mb-1">
                                {{ $isEditing ? 'Edit Skill' : 'Tambah Skill Baru' }}
                            </h3>
                            <p class="text-slate-400 text-sm">
                                {{ $isEditing ? 'Update informasi skill Anda' : 'Isi detail skill yang ingin ditambahkan' }}
                            </p>
                        </div>

                        <!-- Form -->
                        <form wire:submit="save" class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="type" class="w-4 h-4 text-green-400"></i>
                                            <span>Name</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="name"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-green-400 transition-all duration-200"
                                        placeholder="Nama skill..."
                                    >
                                    @error('name') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="folder" class="w-4 h-4 text-green-400"></i>
                                            <span>Category</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="category"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-green-400 transition-all duration-200"
                                        placeholder="GRC, Technical, Tools..."
                                    >
                                    @error('category') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <!-- Level -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="bar-chart-2" class="w-4 h-4 text-green-400"></i>
                                            <span>Level (%)</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="level"
                                        type="number" 
                                        min="0"
                                        max="100"
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-green-400 transition-all duration-200"
                                        placeholder="80"
                                    >
                                    @error('level') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Icon -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="image" class="w-4 h-4 text-green-400"></i>
                                            <span>Icon (Lucide)</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="icon"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-green-400 transition-all duration-200"
                                        placeholder="shield, code..."
                                    >
                                    @error('icon') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="arrow-up-down" class="w-4 h-4 text-green-400"></i>
                                            <span>Sort Order</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="sort_order"
                                        type="number" 
                                        min="0"
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-green-400 transition-all duration-200"
                                        placeholder="0"
                                    >
                                    @error('sort_order') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-700/50">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="px-5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl text-white font-medium hover:from-green-400 hover:to-emerald-400 transition-all duration-200 shadow-lg shadow-green-500/25 flex items-center space-x-2"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-wait"
                                >
                                    <span wire:loading.remove class="flex items-center space-x-2">
                                        <i data-lucide="{{ $isEditing ? 'check' : 'plus' }}" class="w-4 h-4"></i>
                                        <span>{{ $isEditing ? 'Update Skill' : 'Simpan Skill' }}</span>
                                    </span>
                                    <span wire:loading class="flex items-center space-x-2">
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Menyimpan...</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
