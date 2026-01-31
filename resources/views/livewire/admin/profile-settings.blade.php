<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Avatar Section --}}
        <div class="lg:col-span-1">
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-white font-mono mb-4 flex items-center">
                    <i data-lucide="camera" class="w-5 h-5 mr-2 text-cyan-400"></i>
                    Avatar
                </h3>

                <div class="flex flex-col items-center">
                    {{-- Avatar Preview with Upload Overlay --}}
                    <div class="relative group cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                        {{-- Main Avatar Image --}}
                        <div class="w-40 h-40 rounded-full overflow-hidden bg-slate-700 relative ring-4 ring-slate-700 group-hover:ring-cyan-500/50 shadow-lg shadow-black/50 group-hover:shadow-cyan-500/20 transition-all duration-300 mx-auto" style="width: 10rem; height: 10rem;">
                            @if($newAvatar && $newAvatar->isPreviewable())
                                <img src="{{ $newAvatar->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                            @elseif($avatar)
                                <img src="{{ Storage::url($avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-800">
                                    <i data-lucide="user" class="w-16 h-16 text-slate-500 group-hover:text-slate-400 transition-colors"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-opacity duration-300 backdrop-blur-[2px]">
                            <i data-lucide="camera" class="w-8 h-8 text-white mb-2"></i>
                            <span class="text-xs font-mono text-white font-medium">CHANGE</span>
                        </div>

                        {{-- Glow Effect --}}
                        <div class="absolute -inset-4 bg-cyan-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 -z-10 transition-opacity duration-500"></div>
                    </div>

                    {{-- Hidden Upload Input --}}
                    <div class="w-full text-center mt-4">
                        <input 
                            type="file" 
                            wire:model="newAvatar" 
                            accept="image/*"
                            class="hidden"
                            id="avatar-input"
                        >
                        <p class="text-xs text-slate-500 font-mono">
                            Click avatar to upload
                        </p>
                        @error('newAvatar') 
                            <span class="text-sm text-red-400 mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    @if($newAvatar)
                        <button 
                            wire:click="updateAvatar" 
                            class="w-full mt-3 py-2 px-4 bg-cyan-500 hover:bg-cyan-600 rounded-lg text-white font-medium transition-colors"
                        >
                            Save Avatar
                        </button>
                    @endif

                    @if($avatar)
                        <button 
                            wire:click="removeAvatar" 
                            wire:confirm="Are you sure you want to remove your avatar?"
                            class="w-full mt-3 py-2 px-4 bg-red-500/20 hover:bg-red-500/30 rounded-lg text-red-400 font-medium transition-colors"
                        >
                            Remove Avatar
                        </button>
                    @endif

                    @if(session('avatar_success'))
                        <div class="mt-3 text-sm text-green-400">
                            {{ session('avatar_success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Profile & Password Section --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Profile Information --}}
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-white font-mono mb-4 flex items-center">
                    <i data-lucide="user-pen" class="w-5 h-5 mr-2 text-cyan-400"></i>
                    Profile Information
                </h3>

                <form wire:submit="updateProfile" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Name</label>
                            <input 
                                type="text" 
                                wire:model="name"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                            >
                            @error('name') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Email</label>
                            <input 
                                type="email" 
                                wire:model="email"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                            >
                            @error('email') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Phone</label>
                            <input 
                                type="text" 
                                wire:model="phone"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                            >
                            @error('phone') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        {{-- Website --}}
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Website</label>
                            <input 
                                type="url" 
                                wire:model="website"
                                placeholder="https://"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                            >
                            @error('website') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- LinkedIn --}}
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">LinkedIn</label>
                        <input 
                            type="url" 
                            wire:model="linkedin"
                            placeholder="https://linkedin.com/in/username"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                        >
                        @error('linkedin') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    {{-- GitHub --}}
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">GitHub</label>
                        <input 
                            type="url" 
                            wire:model="github"
                            placeholder="https://github.com/username"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                        >
                        @error('github') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Address</label>
                        <input 
                            type="text" 
                            wire:model="address"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                        >
                        @error('address') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    {{-- Summary --}}
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Bio / Summary</label>
                        <textarea 
                            wire:model="summary"
                            rows="4"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors resize-none"
                        ></textarea>
                        @error('summary') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        @if(session('profile_success'))
                            <span class="text-sm text-green-400">{{ session('profile_success') }}</span>
                        @else
                            <span></span>
                        @endif
                        <button 
                            type="submit"
                            class="py-2 px-6 bg-cyan-500 hover:bg-cyan-600 rounded-lg text-white font-medium transition-colors"
                        >
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>

            {{-- Password Update --}}
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-white font-mono mb-4 flex items-center">
                    <i data-lucide="lock" class="w-5 h-5 mr-2 text-cyan-400"></i>
                    Update Password
                </h3>

                <form wire:submit="updatePassword" class="space-y-4">
                    {{-- Current Password --}}
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Current Password</label>
                        <input 
                            type="password" 
                            wire:model="current_password"
                            class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                        >
                        @error('current_password') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- New Password --}}
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">New Password</label>
                            <input 
                                type="password" 
                                wire:model="new_password"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                            >
                            @error('new_password') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Confirm New Password</label>
                            <input 
                                type="password" 
                                wire:model="new_password_confirmation"
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                            >
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        @if(session('password_success'))
                            <span class="text-sm text-green-400">{{ session('password_success') }}</span>
                        @else
                            <span></span>
                        @endif
                        <button 
                            type="submit"
                            class="py-2 px-6 bg-cyan-500 hover:bg-cyan-600 rounded-lg text-white font-medium transition-colors"
                        >
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Education Section --}}
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-white font-mono mb-4 flex items-center">
                    <i data-lucide="graduation-cap" class="w-5 h-5 mr-2 text-pink-400"></i>
                    Education
                </h3>

                {{-- Education Form --}}
                <form wire:submit="saveEducation" class="mb-4 p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-slate-400 mb-1">School / University</label>
                            <input 
                                type="text" 
                                wire:model="educationForm.school"
                                placeholder="e.g. University of Indonesia"
                                class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-pink-400 focus:outline-none transition-colors"
                            >
                            @error('educationForm.school') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Year</label>
                            <input 
                                type="text" 
                                wire:model="educationForm.year"
                                placeholder="e.g. 2019 - 2023"
                                class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-pink-400 focus:outline-none transition-colors"
                            >
                            @error('educationForm.year') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm text-slate-400 mb-1">Degree / Major</label>
                        <input 
                            type="text" 
                            wire:model="educationForm.degree"
                            placeholder="e.g. Bachelor of Computer Science"
                            class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-pink-400 focus:outline-none transition-colors"
                        >
                        @error('educationForm.degree') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm text-slate-400 mb-1">Thesis / Final Project (Optional)</label>
                        <textarea 
                            wire:model="educationForm.thesis"
                            rows="2"
                            placeholder="e.g. Analysis of security vulnerabilities in banking systems..."
                            class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-pink-400 focus:outline-none transition-colors resize-none"
                        ></textarea>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        @if($editingEducationId)
                            <button 
                                type="button"
                                wire:click="resetEducationForm"
                                class="py-2 px-4 text-slate-400 hover:text-white transition-colors"
                            >
                                Cancel
                            </button>
                        @else
                            <span></span>
                        @endif
                        <button 
                            type="submit"
                            class="py-2 px-6 bg-pink-500 hover:bg-pink-600 rounded-lg text-white font-medium transition-colors"
                        >
                            {{ $editingEducationId ? 'Update' : 'Add Education' }}
                        </button>
                    </div>
                </form>

                @if(session('education_success'))
                    <div class="mb-4 text-sm text-green-400">{{ session('education_success') }}</div>
                @endif

                {{-- Education List --}}
                <div class="space-y-4">
                    @foreach($educations as $edu)
                        <div class="p-5 bg-slate-800/40 rounded-xl border border-slate-700/50 relative group transition-all hover:border-slate-600 hover:bg-slate-800/60">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-pink-500/10 flex items-center justify-center">
                                        <i data-lucide="university" class="w-5 h-5 text-pink-400"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white">{{ $edu['school'] }}</div>
                                        <div class="text-xs text-slate-500 font-mono">Education</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button 
                                        wire:click="editEducation({{ $edu['id'] }})"
                                        class="p-2 text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button 
                                        wire:click="deleteEducation({{ $edu['id'] }})"
                                        wire:confirm="Are you sure you want to delete this education?"
                                        class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors"
                                        title="Delete"
                                    >
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 pl-[3.25rem]">
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Degree / Major</div>
                                    <div class="text-sm text-slate-300">{{ $edu['degree'] }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 mb-1">Year</div>
                                    <div class="text-sm text-slate-300 font-mono">{{ $edu['year'] }}</div>
                                </div>
                                @if(!empty($edu['thesis']))
                                <div class="md:col-span-2 mt-2 pt-2 border-t border-slate-700/50">
                                    <div class="text-xs text-slate-500 mb-1">Thesis</div>
                                    <div class="text-sm text-slate-300 italic">"{{ $edu['thesis'] }}"</div>
                                </div>
                                @endif

                            </div>
                        </div>
                    @endforeach

                    @if(count($educations) === 0)
                        <div class="text-center py-8 border-2 border-dashed border-slate-700 rounded-xl text-slate-500">
                            No education added yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
