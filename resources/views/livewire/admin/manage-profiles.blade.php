<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ./manage-profiles.sh --list
            </div>
            <p class="text-slate-400">Buat dan kelola variasi profil untuk kebutuhan rekrutmen yang berbeda.</p>
        </div>
        <button 
            wire:click="createProfile"
            class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/50 rounded-xl text-cyan-400 hover:from-cyan-500/30 hover:to-blue-500/30 hover:border-cyan-400 transition-all duration-300 shadow-lg shadow-cyan-500/10"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span class="font-medium">Add Profile Variant</span>
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

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/50 rounded-xl text-red-400 flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            </div>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Profiles Table -->
    <div class="glass-card overflow-hidden shadow-xl shadow-black/20">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-slate-800/80 to-slate-700/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Profile Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Professional Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Public Link</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-300 uppercase tracking-wider">Landing Page</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($profiles as $profile)
                    <tr class="hover:bg-slate-700/30 transition-all duration-200 group">
                        <td class="px-6 py-5">
                            <div class="text-white font-semibold group-hover:text-cyan-400 transition-colors">{{ $profile->name }}</div>
                            <div class="text-slate-500 text-xs font-mono mt-1">ID: {{ $profile->id }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-slate-300 text-sm truncate max-w-xs">{{ $profile->professional_title }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <a href="/p/{{ $profile->slug }}" target="_blank" class="flex items-center space-x-1.5 text-cyan-400 hover:text-cyan-300 text-sm transition-colors group/link">
                                <span class="underline decoration-cyan-500/30 group-hover/link:decoration-cyan-400">/p/{{ $profile->slug }}</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5 opacity-50 group-hover/link:opacity-100"></i>
                            </a>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($profile->is_landing_page)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 mr-2 status-dot"></span>
                                    Active
                                </span>
                            @else
                                <button 
                                    wire:click="setAsLandingPage({{ $profile->id }})" 
                                    class="text-slate-500 hover:text-cyan-400 text-xs font-medium px-3 py-1 rounded-lg hover:bg-cyan-500/10 border border-transparent hover:border-cyan-500/30 transition-all"
                                >
                                    Set Active
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <button 
                                    wire:click="editProfile({{ $profile->id }})" 
                                    class="px-3 py-1.5 text-xs text-cyan-400 hover:bg-cyan-500/20 rounded-lg transition-all duration-200 border border-cyan-500/30"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="deleteProfile({{ $profile->id }})" 
                                    class="px-3 py-1.5 text-xs text-red-400 hover:bg-red-500/20 rounded-lg transition-all duration-200 border border-red-500/30"
                                    onclick="return confirm('Are you sure you want to delete this profile? This action is permanent.') || event.stopImmediatePropagation()"
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
                                <i data-lucide="user" class="w-8 h-8 opacity-50"></i>
                            </div>
                            <p class="text-lg font-medium mb-1 text-slate-300">Belum ada variasi profil</p>
                            <p class="text-sm">Klik "Add Profile Variant" untuk membuat profil pertama Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 10)"
    >
        <!-- Backdrop -->
        <div 
            class="fixed inset-0 bg-black/60 backdrop-blur-md transition-opacity duration-300"
            :class="show ? 'opacity-100' : 'opacity-0'"
            wire:click="closeModal"
        ></div>
        
        <!-- Modal Content -->
        <div 
            class="relative w-full max-w-2xl transform transition-all duration-300"
            :class="show ? 'opacity-100 scale-100 translate-y-0' : 'opacity-0 scale-95 translate-y-4'"
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
                                {{ $editingProfileId ? '~/edit-profile.sh' : '~/new-profile.sh' }}
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
                            {{ $editingProfileId ? 'Edit Profile Variant' : 'Create New Profile Variant' }}
                        </h3>
                        <p class="text-slate-400 text-sm">
                            Sesuaikan judul dan ringkasan untuk kebutuhan lamaran pekerjaan tertentu.
                        </p>
                    </div>

                    <form wire:submit.prevent="saveProfile" class="space-y-6">
                        <div class="space-y-4">
                            <!-- Profile Name -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-300 flex items-center space-x-2">
                                    <i data-lucide="bookmark" class="w-4 h-4 text-cyan-400"></i>
                                    <span>Profile Name (Internal Reference)</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="name" 
                                    class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
                                    placeholder="e.g. Cybersecurity Role, Backend Dev Role..."
                                >
                                @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Professional Title -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-300 flex items-center space-x-2">
                                    <i data-lucide="briefcase" class="w-4 h-4 text-cyan-400"></i>
                                    <span>Professional Title</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="professional_title" 
                                    class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all"
                                    placeholder="e.g. ICT Security Professional & Software Engineer"
                                >
                                @error('professional_title') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Summary -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-300 flex items-center space-x-2">
                                    <i data-lucide="align-left" class="w-4 h-4 text-cyan-400"></i>
                                    <span>Summary (Markdown/HTML supported)</span>
                                </label>
                                <textarea 
                                    wire:model="summary" 
                                    rows="4" 
                                    class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:bg-slate-900 transition-all resize-none"
                                    placeholder="Briefly describe your expertise for this role..."
                                ></textarea>
                                @error('summary') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Skills Lists -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300 flex items-center space-x-2">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-purple-400"></i>
                                        <span>Skills List (Left)</span>
                                    </label>
                                    <textarea 
                                        wire:model="about_grc_list" 
                                        rows="4" 
                                        class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-purple-400 transition-all resize-none"
                                        placeholder="One skill per line..."
                                    ></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300 flex items-center space-x-2">
                                        <i data-lucide="cpu" class="w-4 h-4 text-emerald-400"></i>
                                        <span>Skills List (Right)</span>
                                    </label>
                                    <textarea 
                                        wire:model="about_tech_list" 
                                        rows="4" 
                                        class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-400 transition-all resize-none"
                                        placeholder="One skill per line..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-slate-800">
                            <button 
                                type="button" 
                                wire:click="closeModal" 
                                class="px-5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all duration-200"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-8 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl text-white font-medium hover:from-cyan-400 hover:to-blue-400 transition-all duration-200 shadow-lg shadow-cyan-500/25 flex items-center space-x-2"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove class="flex items-center space-x-2">
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    <span>{{ $editingProfileId ? 'Update Profile' : 'Save Profile' }}</span>
                                </span>
                                <span wire:loading class="flex items-center space-x-2">
                                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Processing...</span>
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
