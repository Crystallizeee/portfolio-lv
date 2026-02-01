<div>
    <div class="max-w-4xl mx-auto">
        <!-- Input Section -->
        <div class="glass-card p-6 mb-8 entrance-animate">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center space-x-2">
                <i data-lucide="sparkles" class="w-6 h-6 text-cyan-400"></i>
                <span>Generate Cover Letter with Gemini AI</span>
            </h3>

            <div class="space-y-6">
                <!-- Job URL -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Job Vacancy Link (LinkedIn, Glints, etc.)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="link" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="url" wire:model="jobUrl" 
                               class="block w-full pl-10 pr-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/50 transition-all font-mono text-sm"
                               placeholder="https://www.linkedin.com/jobs/view/...">
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="h-px flex-1 bg-white/5"></div>
                    <span class="text-[10px] font-mono text-slate-600 uppercase tracking-widest">OR</span>
                    <div class="h-px flex-1 bg-white/5"></div>
                </div>

                <!-- Manual Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Job Description / Requirements</label>
                    <textarea wire:model="manualJobDescription" rows="6"
                              class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/50 transition-all text-sm h-32"
                              placeholder="Paste the job requirements here if the link doesn't work..."></textarea>
                </div>

                @if($errorMessage)
                    <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 text-sm flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        <span>{{ $errorMessage }}</span>
                    </div>
                @endif

                <button wire:click="generate" wire:loading.attr="disabled"
                        class="w-full py-4 px-6 bg-gradient-to-r from-cyan-600 to-blue-700 hover:from-cyan-500 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/20 active:scale-95 transition-all duration-300 flex items-center justify-center space-x-2 group">
                    <span wire:loading.remove wire:target="generate" class="flex items-center space-x-2">
                        <i data-lucide="wand-2" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                        <span>Generate Cover Letter</span>
                    </span>
                    <span wire:loading wire:target="generate" class="flex items-center space-x-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Analyzing Job & CV...</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Result Section -->
        <div x-show="$wire.coverLetter" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
             class="glass-card p-8 mb-8 relative group">
            <div class="absolute top-4 right-4 flex items-center space-x-2">
                <button @click="navigator.clipboard.writeText($wire.coverLetter); alert('Copied to clipboard!')" 
                        class="p-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-lg transition-all"
                        title="Copy to Clipboard">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                </button>
            </div>

            <h4 class="text-xs font-mono text-cyan-500/70 tracking-widest uppercase mb-6 flex items-center">
                <i data-lucide="file-text" class="w-3 h-3 mr-2"></i>
                Draft Generated Content
            </h4>

            <div class="prose prose-invert max-w-none">
                <div class="text-slate-200 leading-relaxed whitespace-pre-wrap font-serif text-lg">
                    {{ $this->coverLetter }}
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between">
                <p class="text-[10px] text-slate-500 font-mono italic">Produced by Gemini 2.0 Flash • Optimized for your profile</p>
                <div class="flex space-x-2">
                     <span class="px-2 py-1 rounded bg-cyan-500/10 text-cyan-400 text-[10px] font-mono border border-cyan-500/20">Customized</span>
                     <span class="px-2 py-1 rounded bg-purple-500/10 text-purple-400 text-[10px] font-mono border border-purple-500/20">Fast Mode</span>
                </div>
            </div>
        </div>
    </div>
</div>
