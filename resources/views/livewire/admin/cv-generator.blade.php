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
                        <label class="block text-sm font-medium text-slate-300 mb-2">GitHub URL</label>
                        <input wire:model="github" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-purple-400 focus:outline-none focus:bg-slate-800 transition-colors">
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
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-pink-400"></i>
                        <span>Education</span>
                    </h3>
                    <button wire:click="addEducation" class="text-xs px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center space-x-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Add Education</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    @foreach($educations as $index => $education)
                        <div class="p-5 bg-slate-800/40 rounded-xl border border-slate-700/50 relative group transition-all hover:border-slate-600 hover:bg-slate-800/60">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-pink-500/10 flex items-center justify-center">
                                        <i data-lucide="university" class="w-5 h-5 text-pink-400"></i>
                                    </div>
                                    <span class="text-sm font-mono text-slate-500">Education #{{ $index + 1 }}</span>
                                </div>
                                <button wire:click="removeEducation({{ $index }})" class="text-slate-500 hover:text-red-400 transition-colors p-2 hover:bg-red-500/10 rounded-lg" title="Remove">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-400 mb-1.5">School / University</label>
                                    <input wire:model="educations.{{ $index }}.school" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-sm text-white focus:border-pink-400 focus:outline-none focus:bg-slate-800 transition-all placeholder-slate-600" placeholder="e.g. University of Indonesia">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Degree / Major</label>
                                    <input wire:model="educations.{{ $index }}.degree" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-sm text-white focus:border-pink-400 focus:outline-none focus:bg-slate-800 transition-all placeholder-slate-600" placeholder="e.g. Bachelor of Computer Science">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Year</label>
                                    <input wire:model="educations.{{ $index }}.year" type="text" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-sm text-white focus:border-pink-400 focus:outline-none focus:bg-slate-800 transition-all placeholder-slate-600" placeholder="e.g. 2019 - 2023">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Thesis / Final Project (Optional)</label>
                                    <textarea wire:model="educations.{{ $index }}.thesis" rows="2" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-sm text-white focus:border-pink-400 focus:outline-none focus:bg-slate-800 transition-all placeholder-slate-600 resize-none" placeholder="e.g. Analysis of..."></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if(count($educations) === 0)
                        <div class="text-center py-8 border-2 border-dashed border-slate-700 rounded-xl text-slate-500">
                            No education added yet. Click "Add Education" to start.
                        </div>
                    @endif
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
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Description (Optional)</label>
                                    <textarea wire:model="certifications.{{ $index }}.description" rows="2" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-yellow-400 focus:outline-none resize-none" placeholder="What did you learn?"></textarea>
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

            <!-- Manual Languages -->
            @if(!$useDbLanguages)
            <div class="glass-card p-6 rounded-xl border border-slate-700/50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                        <i data-lucide="languages" class="w-5 h-5 text-green-400"></i>
                        <span>Languages</span>
                    </h3>
                    <button wire:click="addManualLanguage" class="text-xs px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center space-x-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Add</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    @foreach($manualLanguages as $index => $lang)
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700 relative group transition-all hover:border-slate-600">
                            <button wire:click="removeManualLanguage({{ $index }})" class="absolute top-2 right-2 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Language</label>
                                    <input wire:model="manualLanguages.{{ $index }}.name" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-green-400 focus:outline-none" placeholder="e.g. English">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Level</label>
                                    <input wire:model="manualLanguages.{{ $index }}.level" type="text" class="w-full px-3 py-2 bg-slate-900 border border-slate-600 rounded text-sm text-white focus:border-green-400 focus:outline-none" placeholder="e.g. Fluent">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar / Settings -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-xl border border-slate-700/50 sticky top-6">
                <h3 class="text-lg font-bold text-white mb-4">Configuration</h3>
                
                <div class="space-y-4">
                    <!-- Language Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-400 mb-2">CV Language</label>
                        <select wire:model.live="locale" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 text-sm focus:ring-cyan-500 focus:border-cyan-500 p-2.5">
                            <option value="en">English</option>
                            <option value="id">Bahasa Indonesia</option>
                        </select>
                    </div>

                    <!-- Experiences Toggle -->
                    <div 
                        wire:click="$toggle('useDbExperiences')"
                        class="p-4 rounded-xl border cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $useDbExperiences ? 'bg-purple-500/10 border-purple-500/50' : 'bg-slate-800/30 border-slate-700 hover:border-slate-600' }}"
                    >
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg {{ $useDbExperiences ? 'bg-purple-500' : 'bg-slate-700 group-hover:bg-slate-600' }} flex items-center justify-center transition-colors">
                                    <i data-lucide="briefcase" class="w-5 h-5 {{ $useDbExperiences ? 'text-white' : 'text-slate-400' }}"></i>
                                </div>
                                <div>
                                    <div class="font-bold {{ $useDbExperiences ? 'text-white' : 'text-slate-300' }}">Experiences</div>
                                    <div class="text-xs {{ $useDbExperiences ? 'text-purple-200' : 'text-slate-500' }}">From database</div>
                                </div>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $useDbExperiences ? 'border-purple-400 bg-purple-400' : 'border-slate-600' }}">
                                @if($useDbExperiences) <i data-lucide="check" class="w-3 h-3 text-slate-900 font-bold"></i> @endif
                            </div>
                        </div>
                    </div>

                    <!-- Skills Toggle -->
                    <div 
                        wire:click="$toggle('useDbSkills')"
                        class="p-4 rounded-xl border cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $useDbSkills ? 'bg-cyan-500/10 border-cyan-500/50' : 'bg-slate-800/30 border-slate-700 hover:border-slate-600' }}"
                    >
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg {{ $useDbSkills ? 'bg-cyan-500' : 'bg-slate-700 group-hover:bg-slate-600' }} flex items-center justify-center transition-colors">
                                    <i data-lucide="cpu" class="w-5 h-5 {{ $useDbSkills ? 'text-white' : 'text-slate-400' }}"></i>
                                </div>
                                <div>
                                    <div class="font-bold {{ $useDbSkills ? 'text-white' : 'text-slate-300' }}">Skills</div>
                                    <div class="text-xs {{ $useDbSkills ? 'text-cyan-200' : 'text-slate-500' }}">From database</div>
                                </div>
                            </div>
                             <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $useDbSkills ? 'border-cyan-400 bg-cyan-400' : 'border-slate-600' }}">
                                @if($useDbSkills) <i data-lucide="check" class="w-3 h-3 text-slate-900 font-bold"></i> @endif
                            </div>
                        </div>
                    </div>

                    <!-- Education Toggle -->
                    <div 
                        wire:click="$toggle('useDbEducations')"
                        class="p-4 rounded-xl border cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $useDbEducations ? 'bg-pink-500/10 border-pink-500/50' : 'bg-slate-800/30 border-slate-700 hover:border-slate-600' }}"
                    >
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg {{ $useDbEducations ? 'bg-pink-500' : 'bg-slate-700 group-hover:bg-slate-600' }} flex items-center justify-center transition-colors">
                                    <i data-lucide="graduation-cap" class="w-5 h-5 {{ $useDbEducations ? 'text-white' : 'text-slate-400' }}"></i>
                                </div>
                                <div>
                                    <div class="font-bold {{ $useDbEducations ? 'text-white' : 'text-slate-300' }}">Education</div>
                                    <div class="text-xs {{ $useDbEducations ? 'text-pink-200' : 'text-slate-500' }}">From Profile</div>
                                </div>
                            </div>
                             <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $useDbEducations ? 'border-pink-400 bg-pink-400' : 'border-slate-600' }}">
                                @if($useDbEducations) <i data-lucide="check" class="w-3 h-3 text-slate-900 font-bold"></i> @endif
                            </div>
                        </div>
                    </div>



                    <!-- Languages Toggle -->
                    <div 
                        wire:click="$toggle('useDbLanguages')"
                        class="p-4 rounded-xl border cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $useDbLanguages ? 'bg-green-500/10 border-green-500/50' : 'bg-slate-800/30 border-slate-700 hover:border-slate-600' }}"
                    >
                         <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg {{ $useDbLanguages ? 'bg-green-500' : 'bg-slate-700 group-hover:bg-slate-600' }} flex items-center justify-center transition-colors">
                                    <i data-lucide="languages" class="w-5 h-5 {{ $useDbLanguages ? 'text-white' : 'text-slate-400' }}"></i>
                                </div>
                                <div>
                                    <div class="font-bold {{ $useDbLanguages ? 'text-white' : 'text-slate-300' }}">Languages</div>
                                    <div class="text-xs {{ $useDbLanguages ? 'text-green-200' : 'text-slate-500' }}">From Profile</div>
                                </div>
                            </div>
                             <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $useDbLanguages ? 'border-green-400 bg-green-400' : 'border-slate-600' }}">
                                @if($useDbLanguages) <i data-lucide="check" class="w-3 h-3 text-slate-900 font-bold"></i> @endif
                            </div>
                        </div>
                    </div>
                    <div 
                        wire:click="$toggle('useDbCertifications')"
                        class="p-4 rounded-xl border cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $useDbCertifications ? 'bg-yellow-500/10 border-yellow-500/50' : 'bg-slate-800/30 border-slate-700 hover:border-slate-600' }}"
                    >
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg {{ $useDbCertifications ? 'bg-yellow-500' : 'bg-slate-700 group-hover:bg-slate-600' }} flex items-center justify-center transition-colors">
                                    <i data-lucide="award" class="w-5 h-5 {{ $useDbCertifications ? 'text-white' : 'text-slate-400' }}"></i>
                                </div>
                                <div>
                                    <div class="font-bold {{ $useDbCertifications ? 'text-white' : 'text-slate-300' }}">Certifications</div>
                                    <div class="text-xs {{ $useDbCertifications ? 'text-yellow-200' : 'text-slate-500' }}">From Certificates</div>
                                </div>
                            </div>
                             <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $useDbCertifications ? 'border-yellow-400 bg-yellow-400' : 'border-slate-600' }}">
                                @if($useDbCertifications) <i data-lucide="check" class="w-3 h-3 text-slate-900 font-bold"></i> @endif
                            </div>
                        </div>
                    </div>

                    <!-- Projects Toggle -->
                    <div 
                        wire:click="$toggle('useDbProjects')"
                        class="p-4 rounded-xl border cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $useDbProjects ? 'bg-blue-500/10 border-blue-500/50' : 'bg-slate-800/30 border-slate-700 hover:border-slate-600' }}"
                    >
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg {{ $useDbProjects ? 'bg-blue-500' : 'bg-slate-700 group-hover:bg-slate-600' }} flex items-center justify-center transition-colors">
                                    <i data-lucide="folder-git-2" class="w-5 h-5 {{ $useDbProjects ? 'text-white' : 'text-slate-400' }}"></i>
                                </div>
                                <div>
                                    <div class="font-bold {{ $useDbProjects ? 'text-white' : 'text-slate-300' }}">Projects</div>
                                    <div class="text-xs {{ $useDbProjects ? 'text-blue-200' : 'text-slate-500' }}">From Manage Projects</div>
                                </div>
                            </div>
                             <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $useDbProjects ? 'border-blue-400 bg-blue-400' : 'border-slate-600' }}">
                                @if($useDbProjects) <i data-lucide="check" class="w-3 h-3 text-slate-900 font-bold"></i> @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-2 border-t border-slate-700/50">
                        <button 
                            wire:click="generatePdf"
                            class="w-full flex items-center justify-center space-x-2 px-5 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl text-white font-bold hover:from-cyan-400 hover:to-blue-400 transition-all duration-300 shadow-lg shadow-cyan-500/20 hover:scale-[1.02]"
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
