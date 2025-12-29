<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ls -la /projects
            </div>
            <p class="text-slate-400">Kelola project dan home lab Anda</p>
        </div>
        <button 
            wire:click="openCreateModal"
            class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/50 rounded-xl text-cyan-400 hover:from-cyan-500/30 hover:to-blue-500/30 hover:border-cyan-400 transition-all duration-300 shadow-lg shadow-cyan-500/10"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span class="font-medium">Tambah Project</span>
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/50 rounded-xl text-green-400 flex items-center space-x-3 animate-pulse">
            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Projects Table -->
    <div class="glass-card overflow-hidden shadow-xl shadow-black/20">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-slate-800/80 to-slate-700/80">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Tech Stack</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse ($projects as $project)
                    <tr class="hover:bg-slate-700/30 transition-all duration-200 group">
                        <td class="px-6 py-5">
                            <div class="text-white font-semibold group-hover:text-cyan-400 transition-colors">{{ $project->title }}</div>
                            <div class="text-slate-500 text-sm mt-1 truncate max-w-xs">{{ Str::limit($project->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1.5 text-xs font-mono bg-gradient-to-r from-slate-700/80 to-slate-600/80 rounded-lg text-slate-200 border border-slate-600/50">{{ $project->type }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @if ($project->status === 'online')
                                <span class="flex items-center text-green-400 text-sm font-medium">
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-400 mr-2 status-dot shadow-lg shadow-green-500/50"></span>
                                    Online
                                </span>
                            @else
                                <span class="flex items-center text-red-400 text-sm font-medium">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-400 mr-2"></span>
                                    Offline
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach (array_slice($project->tech_stack ?? [], 0, 3) as $tech)
                                    <span class="px-2 py-1 text-xs bg-cyan-500/15 text-cyan-400 rounded-md border border-cyan-500/30">{{ $tech }}</span>
                                @endforeach
                                @if (count($project->tech_stack ?? []) > 3)
                                    <span class="px-2 py-1 text-xs text-slate-500 bg-slate-700/50 rounded-md">+{{ count($project->tech_stack) - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end space-x-1">
                                <button 
                                    wire:click="openEditModal({{ $project->id }})"
                                    class="p-2.5 text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 rounded-lg transition-all duration-200"
                                    title="Edit"
                                >
                                    <i data-lucide="edit-3" class="w-4 h-4 pointer-events-none"></i>
                                </button>
                                <button 
                                    wire:click="delete({{ $project->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus project ini?"
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
                                <i data-lucide="folder-open" class="w-8 h-8 opacity-50"></i>
                            </div>
                            <p class="text-lg font-medium mb-1">Belum ada project</p>
                            <p class="text-sm">Klik "Tambah Project" untuk memulai.</p>
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
                class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <div class="bg-solid-800 border border-solid-600/50 shadow-2xl shadow-cyan-500/10 overflow-hidden rounded-xl">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 border-b border-solid-600/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-500 shadow-lg shadow-red-500/50"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500 shadow-lg shadow-yellow-500/50"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500 shadow-lg shadow-green-500/50"></div>
                                </div>
                                <span class="font-mono text-sm text-slate-300">
                                    {{ $isEditing ? '~/edit-project.sh' : '~/new-project.sh' }}
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
                                {{ $isEditing ? 'Edit Project' : 'Tambah Project Baru' }}
                            </h3>
                            <p class="text-slate-400 text-sm">
                                {{ $isEditing ? 'Update informasi project Anda' : 'Isi detail project yang ingin ditambahkan' }}
                            </p>
                        </div>

                        <!-- Form -->
                        <form wire:submit="save" class="space-y-5">
                            <!-- Title -->
                            <div class="group">
                                <label class="block text-sm font-medium text-slate-300 mb-2">
                                    <span class="flex items-center space-x-2">
                                        <i data-lucide="type" class="w-4 h-4 text-cyan-400"></i>
                                        <span>Title</span>
                                    </span>
                                </label>
                                <input 
                                    wire:model="title"
                                    type="text" 
                                    class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-800 transition-all duration-200"
                                    placeholder="Nama project..."
                                >
                                @error('title') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">
                                    <span class="flex items-center space-x-2">
                                        <i data-lucide="align-left" class="w-4 h-4 text-cyan-400"></i>
                                        <span>Description</span>
                                    </span>
                                </label>
                                <textarea 
                                    wire:model="description"
                                    rows="3"
                                    class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-800 transition-all duration-200 resize-none"
                                    placeholder="Deskripsi project..."
                                ></textarea>
                                @error('description') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Type -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="tag" class="w-4 h-4 text-cyan-400"></i>
                                            <span>Type</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="type"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-800 transition-all duration-200"
                                        placeholder="Home Lab, Script..."
                                    >
                                    @error('type') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="activity" class="w-4 h-4 text-cyan-400"></i>
                                            <span>Status</span>
                                        </span>
                                    </label>
                                    <div class="flex space-x-3">
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" wire:model="status" value="online" class="hidden peer">
                                            <div class="px-4 py-3.5 rounded-xl border-2 border-slate-600/50 text-center peer-checked:border-green-400 peer-checked:bg-green-500/10 peer-checked:text-green-400 text-slate-400 transition-all duration-200 hover:border-slate-500">
                                                <span class="flex items-center justify-center space-x-2">
                                                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                                    <span>Online</span>
                                                </span>
                                            </div>
                                        </label>
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" wire:model="status" value="offline" class="hidden peer">
                                            <div class="px-4 py-3.5 rounded-xl border-2 border-slate-600/50 text-center peer-checked:border-red-400 peer-checked:bg-red-500/10 peer-checked:text-red-400 text-slate-400 transition-all duration-200 hover:border-slate-500">
                                                <span class="flex items-center justify-center space-x-2">
                                                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                                    <span>Offline</span>
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tech Stack -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">
                                    <span class="flex items-center space-x-2">
                                        <i data-lucide="layers" class="w-4 h-4 text-cyan-400"></i>
                                        <span>Tech Stack</span>
                                        <span class="text-slate-500 font-normal">(pisahkan dengan koma)</span>
                                    </span>
                                </label>
                                <input 
                                    wire:model="tech_stack"
                                    type="text" 
                                    class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-800 transition-all duration-200"
                                    placeholder="Python, Docker, PostgreSQL..."
                                >
                                @error('tech_stack') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- URL -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">
                                    <span class="flex items-center space-x-2">
                                        <i data-lucide="link" class="w-4 h-4 text-cyan-400"></i>
                                        <span>URL</span>
                                        <span class="text-slate-500 font-normal">(opsional)</span>
                                    </span>
                                </label>
                                <input 
                                    wire:model="url"
                                    type="url" 
                                    class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-800 transition-all duration-200"
                                    placeholder="https://..."
                                >
                                @error('url') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
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
                                    class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl text-white font-medium hover:from-cyan-400 hover:to-blue-400 transition-all duration-200 shadow-lg shadow-cyan-500/25 flex items-center space-x-2"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-wait"
                                >
                                    <span wire:loading.remove class="flex items-center space-x-2">
                                        <i data-lucide="{{ $isEditing ? 'check' : 'plus' }}" class="w-4 h-4"></i>
                                        <span>{{ $isEditing ? 'Update Project' : 'Simpan Project' }}</span>
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
