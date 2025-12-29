<div x-data="{ showDeleteModal: false, deleteId: null }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ls -la /experiences
            </div>
            <p class="text-slate-400">Kelola pengalaman kerja Anda</p>
        </div>
        <button 
            wire:click="openCreateModal"
            class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/50 rounded-xl text-purple-400 hover:from-purple-500/30 hover:to-pink-500/30 hover:border-purple-400 transition-all duration-300 shadow-lg shadow-purple-500/10"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span class="font-medium">Tambah Experience</span>
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

    <!-- Experiences Table -->
    <div class="glass-card overflow-hidden shadow-xl shadow-black">
        <table class="w-full">
            <thead class="bg-gradient-to-r">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Company & Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse ($experiences as $experience)
                    <tr class="hover:bg-slate-700/30 transition-all duration-200 group">
                        <td class="px-6 py-5">
                            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-700/80 to-slate-600/80 flex items-center justify-center text-slate-300 font-mono text-sm font-bold border border-slate-600/50">
                                {{ $experience->sort_order }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-white font-semibold group-hover:text-purple-400 transition-colors">{{ $experience->company }}</div>
                            <div class="text-cyan-400 text-sm mt-1 flex items-center space-x-1">
                                <i data-lucide="user" class="w-3 h-3"></i>
                                <span>{{ $experience->role }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1.5 text-xs font-mono bg-gradient-to-r from-purple-500/20 to-pink-500/20 text-purple-300 rounded-lg border border-purple-500/30">{{ $experience->type }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-slate-300 text-sm font-mono flex items-center space-x-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-500"></i>
                                <span>{{ $experience->date_range }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button 
                                    wire:click="openEditModal({{ $experience->id }})"
                                    class="px-3 py-1.5 text-xs text-purple-400 hover:bg-purple-500/20 rounded-lg transition-all duration-200 border border-purple-500/30"
                                >
                                    Edit
                                </button>
                                <button 
                                    @click="deleteId = {{ $experience->id }}; showDeleteModal = true"
                                    class="px-3 py-1.5 text-xs text-red-400 hover:bg-red-500/20 rounded-lg transition-all duration-200 border border-red-500/30"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-700/50 flex items-center justify-center">
                                <i data-lucide="briefcase" class="w-8 h-8 opacity-50"></i>
                            </div>
                            <p class="text-lg font-medium mb-1">Belum ada experience</p>
                            <p class="text-sm">Klik "Tambah Experience" untuk memulai.</p>
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
                class="fixed inset-0 bg-black/80 backdrop-blur-sm"
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
                <div class="bg-slate-800 border border-slate-600/50 shadow-2xl shadow-purple-500/10 overflow-hidden rounded-xl">
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
                                    {{ $isEditing ? '~/edit-experience.sh' : '~/new-experience.sh' }}
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
                                {{ $isEditing ? 'Edit Experience' : 'Tambah Experience Baru' }}
                            </h3>
                            <p class="text-slate-400 text-sm">
                                {{ $isEditing ? 'Update informasi pengalaman kerja Anda' : 'Isi detail pengalaman kerja yang ingin ditambahkan' }}
                            </p>
                        </div>

                        <!-- Form -->
                        <form wire:submit="save" class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Company -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="building-2" class="w-4 h-4 text-purple-400"></i>
                                            <span>Company</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="company"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-400 focus:bg-slate-800 transition-all duration-200"
                                        placeholder="Nama perusahaan..."
                                    >
                                    @error('company') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Role -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="user-circle" class="w-4 h-4 text-purple-400"></i>
                                            <span>Role</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="role"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-400 focus:bg-slate-800 transition-all duration-200"
                                        placeholder="Posisi Anda..."
                                    >
                                    @error('role') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <!-- Type -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="tag" class="w-4 h-4 text-purple-400"></i>
                                            <span>Type</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="type"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-400 focus:bg-slate-800 transition-all duration-200"
                                        placeholder="GRC, QA, Dev..."
                                    >
                                    @error('type') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Date Range -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="calendar-range" class="w-4 h-4 text-purple-400"></i>
                                            <span>Period</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="date_range"
                                        type="text" 
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-400 focus:bg-slate-800 transition-all duration-200"
                                        placeholder="2023 - Present"
                                    >
                                    @error('date_range') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="arrow-up-down" class="w-4 h-4 text-purple-400"></i>
                                            <span>Sort Order</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="sort_order"
                                        type="number" 
                                        min="0"
                                        class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-400 focus:bg-slate-800 transition-all duration-200"
                                        placeholder="1"
                                    >
                                    @error('sort_order') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">
                                    <span class="flex items-center space-x-2">
                                        <i data-lucide="file-text" class="w-4 h-4 text-purple-400"></i>
                                        <span>Description</span>
                                    </span>
                                </label>
                                <textarea 
                                    wire:model="description"
                                    rows="4"
                                    class="w-full px-4 py-3.5 bg-slate-900 border-2 border-slate-600 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-400 focus:bg-slate-800 transition-all duration-200 resize-none"
                                    placeholder="Deskripsi pekerjaan dan tanggung jawab Anda..."
                                ></textarea>
                                @error('description') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
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
                                    class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl text-white font-medium hover:from-purple-400 hover:to-pink-400 transition-all duration-200 shadow-lg shadow-purple-500/25 flex items-center space-x-2"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-wait"
                                >
                                    <span wire:loading.remove class="flex items-center space-x-2">
                                        <i data-lucide="{{ $isEditing ? 'check' : 'plus' }}" class="w-4 h-4"></i>
                                        <span>{{ $isEditing ? 'Update Experience' : 'Simpan Experience' }}</span>
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

    <!-- Delete Confirmation Modal -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center"
    >
        <div class="fixed inset-0 bg-black/80" @click="showDeleteModal = false"></div>
        <div class="relative bg-slate-800 rounded-lg p-4 border border-red-500/30 shadow-2xl" style="width: 280px;">
            <p class="text-white text-sm mb-4 text-center">Hapus item ini?</p>
            <div class="flex justify-center space-x-2">
                <button 
                    @click="showDeleteModal = false" 
                    class="px-4 py-1.5 text-xs text-slate-400 hover:text-white bg-slate-700 hover:bg-slate-600 rounded transition-all"
                >
                    Batal
                </button>
                <button 
                    @click="$wire.delete(deleteId); showDeleteModal = false" 
                    class="px-4 py-1.5 text-xs bg-red-500 hover:bg-red-600 text-white rounded transition-all"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
