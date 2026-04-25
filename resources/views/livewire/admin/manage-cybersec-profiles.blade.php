<div>
    {{-- Success Flash --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-white font-mono flex items-center">
                <i data-lucide="shield" class="w-6 h-6 mr-3 text-cyan-400"></i>
                Cybersecurity Training Profiles
            </h2>
            <p class="text-sm text-slate-500 mt-1">Manage your TryHackMe & LetsDefend profiles displayed on the portfolio.</p>
        </div>
    </div>

    {{-- Quick Add Buttons --}}
    @php
        $existingPlatforms = collect($profiles)->pluck('platform')->toArray();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        {{-- TryHackMe Card --}}
        <div class="glass-card p-5 group hover:border-[#88cc14]/30 transition-all duration-300 relative overflow-hidden">
            <div class="h-1 absolute top-0 left-0 right-0" style="background: linear-gradient(90deg, #88cc14, transparent);"></div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(136, 204, 20, 0.1); border: 1px solid rgba(136, 204, 20, 0.2);">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#88cc14" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">TryHackMe</h3>
                        <p class="text-xs text-slate-500">Offensive security labs & CTFs</p>
                    </div>
                </div>
                @if(in_array('tryhackme', $existingPlatforms))
                    @php $thmProfile = collect($profiles)->firstWhere('platform', 'tryhackme'); @endphp
                    <button wire:click="edit({{ $thmProfile['id'] }})" 
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                            style="background: rgba(136, 204, 20, 0.1); color: #88cc14; border: 1px solid rgba(136, 204, 20, 0.3);">
                        <i data-lucide="pencil" class="w-4 h-4 inline mr-1"></i> Edit
                    </button>
                @else
                    <button wire:click="openForm('tryhackme')" 
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:scale-105"
                            style="background: rgba(136, 204, 20, 0.15); color: #88cc14; border: 1px solid rgba(136, 204, 20, 0.3);">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Add
                    </button>
                @endif
            </div>
        </div>

        {{-- LetsDefend Card --}}
        <div class="glass-card p-5 group hover:border-[#1e88e5]/30 transition-all duration-300 relative overflow-hidden">
            <div class="h-1 absolute top-0 left-0 right-0" style="background: linear-gradient(90deg, #1e88e5, transparent);"></div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(30, 136, 229, 0.1); border: 1px solid rgba(30, 136, 229, 0.2);">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#1e88e5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                            <path d="M7 8h2m2 0h2m2 0h2M7 11h10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">LetsDefend</h3>
                        <p class="text-xs text-slate-500">SOC analyst training & simulations</p>
                    </div>
                </div>
                @if(in_array('letsdefend', $existingPlatforms))
                    @php $ldProfile = collect($profiles)->firstWhere('platform', 'letsdefend'); @endphp
                    <button wire:click="edit({{ $ldProfile['id'] }})" 
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                            style="background: rgba(30, 136, 229, 0.1); color: #1e88e5; border: 1px solid rgba(30, 136, 229, 0.3);">
                        <i data-lucide="pencil" class="w-4 h-4 inline mr-1"></i> Edit
                    </button>
                @else
                    <button wire:click="openForm('letsdefend')" 
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all hover:scale-105"
                            style="background: rgba(30, 136, 229, 0.15); color: #1e88e5; border: 1px solid rgba(30, 136, 229, 0.3);">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Add
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Edit/Add Form --}}
    @if($showForm)
    <div class="glass-card p-6 mb-8">
        @php
            $isTHM = $form['platform'] === 'tryhackme';
            $accentColor = $isTHM ? '#88cc14' : '#1e88e5';
            $accentRgb = $isTHM ? '136, 204, 20' : '30, 136, 229';
        @endphp

        <div class="h-1 -mx-6 -mt-6 mb-6 rounded-t-xl" style="background: linear-gradient(90deg, {{ $accentColor }}, transparent);"></div>

        <h3 class="text-lg font-semibold text-white font-mono mb-6 flex items-center">
            <i data-lucide="{{ $isTHM ? 'shield' : 'monitor' }}" class="w-5 h-5 mr-2" style="color: {{ $accentColor }};"></i>
            {{ $editingId ? 'Edit' : 'Add' }} {{ $isTHM ? 'TryHackMe' : 'LetsDefend' }} Profile
        </h3>

        <form wire:submit="save" class="space-y-6">
            {{-- Platform selector (hidden if editing) --}}
            @if(!$editingId)
            <div>
                <label class="block text-sm text-slate-400 mb-2">Platform</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" wire:click="$set('form.platform', 'tryhackme')"
                            class="p-3 rounded-xl text-sm font-medium border text-center transition-all {{ $form['platform'] === 'tryhackme' ? 'border-[#88cc14]/50 bg-[#88cc14]/10 text-[#88cc14]' : 'border-slate-700 bg-slate-800/50 text-slate-400 hover:border-slate-600' }}">
                        TryHackMe
                    </button>
                    <button type="button" wire:click="$set('form.platform', 'letsdefend')"
                            class="p-3 rounded-xl text-sm font-medium border text-center transition-all {{ $form['platform'] === 'letsdefend' ? 'border-[#1e88e5]/50 bg-[#1e88e5]/10 text-[#1e88e5]' : 'border-slate-700 bg-slate-800/50 text-slate-400 hover:border-slate-600' }}">
                        LetsDefend
                    </button>
                </div>
            </div>
            @endif

            {{-- Username & Profile URL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Username <span class="text-red-400">*</span></label>
                    <input type="text" 
                           wire:model="form.username"
                           placeholder="{{ $isTHM ? 'TryHackMe username' : 'LetsDefend username' }}"
                           class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:outline-none transition-colors"
                           style="focus: border-color: {{ $accentColor }};"
                    >
                    @error('form.username') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    <p class="text-xs text-slate-600 mt-1">
                        {{ $isTHM ? 'Profile URL: tryhackme.com/p/{username}' : 'Profile URL: app.letsdefend.io/user/{username}' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Custom Profile URL <span class="text-slate-600">(optional)</span></label>
                    <input type="url" 
                           wire:model="form.profile_url"
                           placeholder="Auto-generated if empty"
                           class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                    >
                    @error('form.profile_url') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Rank --}}
            <div>
                <label class="block text-sm text-slate-400 mb-1">Rank / Level</label>
                <input type="text" 
                       wire:model="form.rank"
                       placeholder="{{ $isTHM ? 'e.g. 0x8 [Hacker]' : 'e.g. SOC Analyst' }}"
                       class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                >
            </div>

            {{-- Stats Grid --}}
            <div>
                <label class="block text-sm text-slate-400 mb-3 font-mono uppercase tracking-wider">Stats</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">{{ $isTHM ? 'Rooms Completed' : 'Labs Completed' }}</label>
                        <input type="number" wire:model="form.rooms_completed" min="0"
                               class="w-full px-3 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors text-center font-mono">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Badges</label>
                        <input type="number" wire:model="form.badges_count" min="0"
                               class="w-full px-3 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors text-center font-mono">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Points</label>
                        <input type="number" wire:model="form.points" min="0"
                               class="w-full px-3 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors text-center font-mono">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Streak</label>
                        <input type="number" wire:model="form.streak" min="0"
                               class="w-full px-3 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors text-center font-mono">
                    </div>
                </div>
            </div>

            {{-- Top Percent --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Top % Ranking</label>
                    <input type="text" wire:model="form.top_percent"
                           placeholder="e.g. 5%"
                           class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center space-x-3 cursor-pointer py-2">
                        <input type="checkbox" wire:model="form.is_visible" 
                               class="w-5 h-5 rounded bg-slate-800 border-slate-600 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-0">
                        <span class="text-sm text-slate-400">Show on portfolio</span>
                    </label>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between pt-4 border-t border-slate-700/50">
                <button type="button" wire:click="$set('showForm', false)" 
                        class="px-4 py-2 text-slate-400 hover:text-white transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 rounded-lg text-white font-medium transition-all hover:scale-105"
                        style="background: {{ $accentColor }};">
                    {{ $editingId ? 'Update Profile' : 'Add Profile' }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Existing Profiles List --}}
    <div class="space-y-4">
        @forelse($profiles as $profile)
            @php
                $isTHM = $profile['platform'] === 'tryhackme';
                $accentColor = $isTHM ? '#88cc14' : '#1e88e5';
                $accentRgb = $isTHM ? '136, 204, 20' : '30, 136, 229';
                $platformName = $isTHM ? 'TryHackMe' : 'LetsDefend';
            @endphp

            <div class="glass-card p-5 relative group transition-all hover:border-slate-600 overflow-hidden">
                <div class="h-0.5 absolute top-0 left-0 right-0" style="background: linear-gradient(90deg, {{ $accentColor }}, transparent);"></div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        {{-- Platform Icon --}}
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                             style="background: rgba({{ $accentRgb }}, 0.1); border: 1px solid rgba({{ $accentRgb }}, 0.2);">
                            @if($isTHM)
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="{{ $accentColor }}" stroke-width="1.5">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path d="M9 12l2 2 4-4"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="{{ $accentColor }}" stroke-width="1.5">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <line x1="8" y1="21" x2="16" y2="21"/>
                                    <line x1="12" y1="17" x2="12" y2="21"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div>
                            <div class="flex items-center space-x-2">
                                <h4 class="font-bold text-white">{{ $platformName }}</h4>
                                <span class="text-xs font-mono text-slate-500">@{{ $profile['username'] }}</span>
                                @if(!$profile['is_visible'])
                                    <span class="px-2 py-0.5 text-[10px] rounded bg-slate-700 text-slate-400 font-mono">HIDDEN</span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-4 mt-1 text-xs text-slate-500 font-mono">
                                @if($profile['rank'])
                                    <span>Rank: <span style="color: {{ $accentColor }};">{{ $profile['rank'] }}</span></span>
                                @endif
                                @if($profile['rooms_completed'] > 0)
                                    <span>{{ $isTHM ? 'Rooms' : 'Labs' }}: {{ $profile['rooms_completed'] }}</span>
                                @endif
                                @if($profile['badges_count'] > 0)
                                    <span>Badges: {{ $profile['badges_count'] }}</span>
                                @endif
                                @if($profile['points'] > 0)
                                    <span>Points: {{ number_format($profile['points']) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="toggleVisibility({{ $profile['id'] }})"
                                class="p-2 rounded-lg transition-colors {{ $profile['is_visible'] ? 'text-slate-400 hover:text-yellow-400 hover:bg-yellow-500/10' : 'text-slate-600 hover:text-emerald-400 hover:bg-emerald-500/10' }}"
                                title="{{ $profile['is_visible'] ? 'Hide' : 'Show' }}">
                            <i data-lucide="{{ $profile['is_visible'] ? 'eye' : 'eye-off' }}" class="w-4 h-4"></i>
                        </button>
                        <button wire:click="edit({{ $profile['id'] }})"
                                class="p-2 text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 rounded-lg transition-colors"
                                title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <button wire:click="delete({{ $profile['id'] }})"
                                wire:confirm="Are you sure you want to delete this profile?"
                                class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                                title="Delete">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 border-2 border-dashed border-slate-700 rounded-xl text-slate-500">
                <i data-lucide="shield-off" class="w-12 h-12 mx-auto mb-3 text-slate-600"></i>
                <p class="font-mono text-sm">No cybersecurity profiles added yet.</p>
                <p class="text-xs mt-1">Click the <span class="text-cyan-400">+ Add</span> button on a platform above to get started.</p>
            </div>
        @endforelse
    </div>
</div>
