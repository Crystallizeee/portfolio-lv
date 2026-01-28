<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ./generate-cv.sh --interactive
            </div>
            <p class="text-slate-400">Buat CV profesional dari data portfolio Anda</p>
        </div>
        <button 
            wire:click="generatePdf"
            class="hidden md:flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/50 rounded-xl text-cyan-400 hover:from-cyan-500/30 hover:to-blue-500/30 hover:border-cyan-400 transition-all duration-300 shadow-lg shadow-cyan-500/10"
            wire:loading.class="opacity-50 cursor-wait"
        >
            <i wire:loading.remove data-lucide="download" class="w-4 h-4"></i>
            <i wire:loading data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
            <span class="font-medium">Generate PDF</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="glass-card p-6 rounded-xl border border-slate-700/50">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center space-x-2">
                    <i data-lucide="user" class="w-5 h-5 text-purple-400"></i>
                    <span>Personal Information</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                        <input wire:model="name" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors">
                        @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                        <input wire:model="email" type="email" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors">
                        @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Phone</label>
                        <input wire:model="phone" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Address</label>
                        <input wire:model="address" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">LinkedIn URL</label>
                        <input wire:model="linkedin" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Website / Portfolio</label>
                        <input wire:model="website" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Professional Summary</label>
                    <textarea wire:model="summary" rows="4" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors resize-none"></textarea>
                </div>
            </div>

            <!-- Education -->
            <div class="glass-card p-6 rounded-xl border border-slate-700/50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-pink-400"></i>
                        <span>Education</span>
                    </h3>
                    <button wire:click="addEducation" class="text-xs px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center space-x-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Add</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    @foreach($educations as $index => $education)
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700 relative group transition-all hover:border-slate-600">
                            <button wire:click="removeEducation({{ $index }})" class="absolute top-2 right-2 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-400 mb-1">School / University</label>
                                    <input wire:model="educations.{{ $index }}.school" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-pink-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Year</label>
                                    <input wire:model="educations.{{ $index }}.year" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-pink-400 focus:outline-none" placeholder="e.g. 2019 - 2023">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Degree / Major</label>
                                    <input wire:model="educations.{{ $index }}.degree" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-pink-400 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

             <!-- Certifications -->
             <div class="glass-card p-6 rounded-xl border border-slate-700/50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                        <i data-lucide="award" class="w-5 h-5 text-yellow-400"></i>
                        <span>Certifications</span>
                    </h3>
                    <button wire:click="addCertification" class="text-xs px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center space-x-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Add</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    @foreach($certifications as $index => $cert)
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700 relative group transition-all hover:border-slate-600">
                            <button wire:click="removeCertification({{ $index }})" class="absolute top-2 right-2 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Certification Name</label>
                                    <input wire:model="certifications.{{ $index }}.name" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-yellow-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Issuer</label>
                                    <input wire:model="certifications.{{ $index }}.issuer" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-yellow-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Year</label>
                                    <input wire:model="certifications.{{ $index }}.year" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-yellow-400 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Manual Experience -->
            @if(!$useDbExperiences)
            <div class="glass-card p-6 rounded-xl border border-slate-700/50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                        <i data-lucide="briefcase" class="w-5 h-5 text-cyan-400"></i>
                        <span>Work Experience</span>
                    </h3>
                    <button wire:click="addManualExperience" class="text-xs px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center space-x-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Add</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    @foreach($manualExperiences as $index => $exp)
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700 relative group transition-all hover:border-slate-600">
                            <button wire:click="removeManualExperience({{ $index }})" class="absolute top-2 right-2 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Company</label>
                                    <input wire:model="manualExperiences.{{ $index }}.company" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-cyan-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Role</label>
                                    <input wire:model="manualExperiences.{{ $index }}.role" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-cyan-400 focus:outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Date Range</label>
                                    <input wire:model="manualExperiences.{{ $index }}.date_range" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-cyan-400 focus:outline-none" placeholder="e.g. Jan 2022 - Present">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Description</label>
                                    <textarea wire:model="manualExperiences.{{ $index }}.description" rows="3" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-cyan-400 focus:outline-none resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Manual Skills -->
            @if(!$useDbSkills)
            <div class="glass-card p-6 rounded-xl border border-slate-700/50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                        <i data-lucide="cpu" class="w-5 h-5 text-green-400"></i>
                        <span>Skills</span>
                    </h3>
                    <button wire:click="addManualSkill" class="text-xs px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center space-x-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Add</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($manualSkills as $index => $skill)
                            <div class="relative group">
                                <input wire:model="manualSkills.{{ $index }}.name" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-green-400 focus:outline-none" placeholder="Skill name">
                                <button wire:click="removeManualSkill({{ $index }})" class="absolute top-1/2 -translate-y-1/2 right-2 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar / Settings -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-xl border border-slate-700/50 sticky top-6">
                <h3 class="text-lg font-bold text-white mb-4">Configuration</h3>
                
                <div class="space-y-4">
                    <!-- Experiences Toggle -->
                    <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-lg border border-slate-700">
                        <div>
                            <div class="font-medium text-slate-200">Experiences</div>
                            <div class="text-xs text-slate-400">Import from database</div>
                        </div>
                        <button 
                            wire:click="$toggle('useDbExperiences')" 
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-slate-900 {{ $useDbExperiences ? 'bg-purple-500' : 'bg-slate-700' }}"
                        >
                            <span class="translate-x-1 inline-block h-4 w-4 transform rounded-full bg-white transition {{ $useDbExperiences ? 'translate-x-6' : 'translate-x-1' }}"/>
                        </button>
                    </div>
                    
                    @if($useDbExperiences)
                    <div class="text-xs text-slate-400 px-2">
                        <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                        Uses existing data from Manage Experiences.
                    </div>
                    @endif

                    <!-- Skills Toggle -->
                    <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-lg border border-slate-700">
                        <div>
                            <div class="font-medium text-slate-200">Skills</div>
                            <div class="text-xs text-slate-400">Import from database</div>
                        </div>
                        <button 
                            wire:click="$toggle('useDbSkills')" 
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-slate-900 {{ $useDbSkills ? 'bg-purple-500' : 'bg-slate-700' }}"
                        >
                            <span class="translate-x-1 inline-block h-4 w-4 transform rounded-full bg-white transition {{ $useDbSkills ? 'translate-x-6' : 'translate-x-1' }}"/>
                        </button>
                    </div>

                    <div class="pt-6 border-t border-slate-700">
                        <button 
                            wire:click="generatePdf"
                            class="w-full flex items-center justify-center space-x-2 px-5 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl text-white font-bold hover:from-cyan-400 hover:to-blue-400 transition-all duration-300 shadow-lg shadow-cyan-500/20"
                        >
                            <i data-lucide="download" class="w-5 h-5"></i>
                            <span>Generate CV</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
