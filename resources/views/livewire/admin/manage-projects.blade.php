<div x-data="{ showDeleteModal: false, deleteId: null }">
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
                            <div class="flex items-center justify-end space-x-2">
                                <button 
                                    wire:click="openEditModal({{ $project->id }})"
                                    class="px-3 py-1.5 text-xs text-cyan-400 hover:bg-cyan-500/20 rounded-lg transition-all duration-200 border border-cyan-500/30"
                                >
                                    Edit
                                </button>
                                <button 
                                    @click="deleteId = {{ $project->id }}; showDeleteModal = true"
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
                class="fixed inset-0 bg-black/50 backdrop-blur-md"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="closeModal"
            ></div>
            
            <!-- Modal Content -->
            <div 
                class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <div class="bg-slate-900 border border-slate-700 shadow-2xl shadow-cyan-500/10 overflow-hidden rounded-xl">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 border-b border-slate-700">
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
                                <i data-lucide="x" class="w-4 h-4"></i>
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
                        <form wire:submit="save" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="type" class="w-4 h-4 text-cyan-400"></i>
                                            <span>Title</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model.live="title"
                                        type="text" 
                                        class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
                                        placeholder="Nama project..."
                                    >
                                    @error('title') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Slug -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">
                                        <span class="flex items-center space-x-2">
                                            <i data-lucide="link" class="w-4 h-4 text-cyan-400"></i>
                                            <span>Slug</span>
                                        </span>
                                    </label>
                                    <input 
                                        wire:model="slug"
                                        type="text" 
                                        class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-slate-300 placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all font-mono text-sm"
                                        placeholder="project-slug"
                                    >
                                    @error('slug') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-300">
                                    <span class="flex items-center space-x-2">
                                        <i data-lucide="align-left" class="w-4 h-4 text-cyan-400"></i>
                                        <span>Description</span>
                                    </span>
                                </label>
                                <textarea 
                                    wire:model="description"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all resize-none"
                                    placeholder="Deskripsi singkat project..."
                                ></textarea>
                                @error('description') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Case Study Section -->
                            <div class="border-t border-slate-800 pt-4 mt-4">
                                <h4 class="text-sm font-semibold text-slate-300 mb-4 flex items-center space-x-2">
                                    <i data-lucide="book-open" class="w-4 h-4 text-cyan-400"></i>
                                    <span>Case Study Details</span>
                                </h4>
                                
                                <div class="space-y-4">
                                    <!-- Challenge -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-400">The Challenge</label>
                                        <textarea 
                                            wire:model="challenge"
                                            rows="3"
                                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
                                            placeholder="What was the problem?"
                                        ></textarea>
                                    </div>

                                    <!-- Solution -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-400">The Solution</label>
                                        <textarea 
                                            wire:model="solution"
                                            rows="3"
                                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
                                            placeholder="How did you solve it?"
                                        ></textarea>
                                    </div>

                                    <!-- Results -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-400">The Results</label>
                                        <textarea 
                                            wire:model="results"
                                            rows="3"
                                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
                                            placeholder="What was the outcome?"
                                        ></textarea>
                                    </div>

                                    <!-- Gallery -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-400">Gallery Images</label>
                                        
                                        <!-- Upload Box -->
                                        <div class="flex items-center justify-center w-full">
                                            <label for="gallery-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-700 border-dashed rounded-lg cursor-pointer bg-slate-950 hover:bg-slate-900 transition-colors relative overflow-hidden group">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i data-lucide="image-plus" class="w-8 h-8 mb-3 text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                                                    <p class="text-sm text-slate-400"><span class="font-semibold">Click to upload</span> multiple images</p>
                                                    <p class="text-xs text-slate-500">Max 2MB per image</p>
                                                </div>
                                                <input id="gallery-upload" wire:model="new_gallery_images" type="file" multiple class="hidden" accept="image/*" />
                                            </label>
                                        </div>
                                        <div wire:loading wire:target="new_gallery_images" class="text-xs text-cyan-400 animate-pulse">Uploading...</div>
                                        @error('new_gallery_images.*') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror

                                        <!-- Images Grid -->
                                        @if (!empty($gallery) || !empty($new_gallery_images))
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                                <!-- Existing Images -->
                                                @foreach ($gallery as $index => $image)
                                                    <div class="relative group aspect-video rounded-lg overflow-hidden border border-slate-700">
                                                        <img src="{{ $image }}" class="w-full h-full object-cover">
                                                        <button 
                                                            type="button"
                                                            wire:click="removeGalleryImage({{ $index }})"
                                                            class="absolute top-1 right-1 bg-red-500/80 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                                                        >
                                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                        </button>
                                                    </div>
                                                @endforeach

                                                <!-- New Upload Previews -->
                                                @if ($new_gallery_images)
                                                    @foreach ($new_gallery_images as $newImage)
                                                        <div class="relative aspect-video rounded-lg overflow-hidden border border-cyan-500/50">
                                                            <img src="{{ $newImage->temporaryUrl() }}" class="w-full h-full object-cover opacity-80">
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <span class="bg-black/50 text-white text-[10px] px-2 py-1 rounded-full">New</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-800">
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
                                        class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
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
                                            <div class="px-4 py-3 rounded-xl border border-slate-700 text-center peer-checked:border-green-400 peer-checked:bg-green-500/10 peer-checked:text-green-400 text-slate-400 transition-all duration-200 hover:border-slate-600 bg-slate-950">
                                                <span class="flex items-center justify-center space-x-2">
                                                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                                    <span>Online</span>
                                                </span>
                                            </div>
                                        </label>
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" wire:model="status" value="offline" class="hidden peer">
                                            <div class="px-4 py-3 rounded-xl border border-slate-700 text-center peer-checked:border-red-400 peer-checked:bg-red-500/10 peer-checked:text-red-400 text-slate-400 transition-all duration-200 hover:border-slate-600 bg-slate-950">
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
                                    class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
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
                                    class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
                                    placeholder="https://..."
                                >
                                @error('url') <span class="text-red-400 text-sm mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Actions -->
                            <div class="border-t border-slate-800 pt-4 mt-6">
                                <h4 class="text-sm font-semibold text-slate-300 mb-4 flex items-center space-x-2">
                                    <i data-lucide="search" class="w-4 h-4 text-cyan-400"></i>
                                    <span>SEO Settings (Optional)</span>
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">SEO Title</label>
                                        <input wire:model="seo_title" type="text" 
                                            class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-400 transition-colors"
                                            placeholder="Leave empty to use project title">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">SEO Description</label>
                                        <textarea wire:model="seo_description" rows="2" 
                                            class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-400 transition-colors"
                                            placeholder="Leave empty to use project description"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Keywords</label>
                                        <input wire:model="seo_keywords" type="text" 
                                            class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-cyan-400 transition-colors"
                                            placeholder="comma, separated, keywords">
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-800">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="px-5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all duration-200"
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

    <!-- Delete Confirmation Modal -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center"
    >
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>
        <div class="relative bg-slate-900 rounded-lg p-6 border border-red-500/30 shadow-2xl w-[320px]" >
            <h3 class="text-xl font-bold text-white mb-2 text-center text-red-500">Hapus Project?</h3>
            <p class="text-slate-400 text-sm mb-6 text-center">Tindakan ini tidak dapat dibatalkan. Project akan dihapus permanen.</p>
            <div class="flex justify-center space-x-3">
                <button 
                    @click="showDeleteModal = false" 
                    class="px-5 py-2 text-sm text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition-all"
                >
                    Batal
                </button>
                <button 
                    @click="$wire.delete(deleteId); showDeleteModal = false" 
                    class="px-5 py-2 text-sm bg-red-600 hover:bg-red-500 text-white rounded-lg transition-all shadow-lg shadow-red-600/20"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
