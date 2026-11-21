<div>
    <div class="max-w-4xl mx-auto">
        <!-- Input Section -->
        <div class="glass-card p-6 mb-8 entrance-animate">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h3 class="text-xl font-bold text-white flex items-center space-x-2">
                    <i data-lucide="sparkles" class="w-6 h-6 text-cyan-400"></i>
                    <span>AI Cover Letter Generator</span>
                </h3>

                <!-- Language Toggle -->
                <div class="inline-flex rounded-xl bg-slate-900/70 p-1 border border-slate-700/50 self-start">
                    <button type="button" wire:click="$set('language', 'id')"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $language === 'id' ? 'bg-cyan-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
                        Indonesia
                    </button>
                    <button type="button" wire:click="$set('language', 'en')"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $language === 'en' ? 'bg-cyan-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
                        English
                    </button>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Screenshot Upload -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">
                        Screenshot Loker (Opsional - PNG/JPG)
                    </label>
                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                        <label class="flex-1 w-full cursor-pointer border border-dashed border-slate-700 hover:border-cyan-500/60 bg-slate-900/40 rounded-xl p-4 transition-all flex items-center justify-center space-x-3">
                            <i data-lucide="image-plus" class="w-5 h-5 text-cyan-400 shrink-0"></i>
                            <span class="text-sm text-slate-300 truncate">
                                {{ $screenshot ? $screenshot->getClientOriginalName() : 'Pilih atau drop screenshot requirement loker...' }}
                            </span>
                            <input type="file" wire:model="screenshot" accept="image/*" class="hidden">
                        </label>
                        @if($screenshot)
                            <div class="relative shrink-0">
                                <img src="{{ $screenshot->temporaryUrl() }}" alt="Preview" class="h-16 w-24 object-cover rounded-lg border border-slate-700">
                                <button type="button" wire:click="$set('screenshot', null)"
                                        class="absolute -top-2 -right-2 w-5 h-5 rounded-full bg-rose-500 text-white text-xs flex items-center justify-center">×</button>
                            </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="screenshot" class="text-xs text-cyan-400 mt-1">Mengunggah screenshot...</div>
                </div>

                <!-- Job URL -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Job Vacancy Link (Opsional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="link" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="url" wire:model="jobUrl"
                               class="block w-full pl-10 pr-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/50 transition-all font-mono text-sm"
                               placeholder="https://www.linkedin.com/jobs/view/...">
                    </div>
                </div>

                <!-- Manual Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Job Description / Requirements (Opsional jika pakai screenshot)</label>
                    <textarea wire:model="manualJobDescription" rows="5"
                              class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/50 transition-all text-sm"
                              placeholder="Paste requirement loker di sini..."></textarea>
                </div>

                <!-- Signature Manager -->
                <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        @if($this->signaturePreviewUrl)
                            <div class="bg-white rounded-lg p-1.5 shrink-0">
                                <img src="{{ $this->signaturePreviewUrl }}" alt="TTD" class="h-10 w-auto">
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                                <i data-lucide="pen-tool" class="w-4 h-4 text-slate-400"></i>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-medium text-slate-200">Tanda Tangan PDF</div>
                            <div class="text-xs text-slate-500">
                                {{ $this->hasSignature ? 'Tersimpan di server & otomatis ditempel ke PDF.' : 'Upload PNG transparan untuk ditempel ke PDF.' }}
                            </div>
                            @if($signatureSavedMessage)
                                <div class="text-xs text-emerald-400 mt-0.5">{{ $signatureSavedMessage }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <label class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs text-slate-200 cursor-pointer transition-colors">
                            <span>{{ $this->hasSignature ? 'Ganti TTD' : 'Upload TTD' }}</span>
                            <input type="file" wire:model="signatureUpload" accept="image/png,image/jpeg,image/webp" class="hidden">
                        </label>
                        @if($this->hasSignature)
                            <button type="button" wire:click="removeSignature"
                                    class="px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-xs text-rose-400 transition-colors">
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>

                @if($errorMessage)
                    <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 text-sm flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
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
        @if($coverLetter)
        <div class="glass-card p-6 sm:p-8 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h4 class="text-xs font-mono text-cyan-500/80 tracking-widest uppercase flex items-center">
                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                    Draft Cover Letter (Bisa Diedit Sebelum Export)
                </h4>
                <div class="flex items-center space-x-2">
                    <button type="button" @click="navigator.clipboard.writeText($wire.coverLetter)"
                            class="px-3 py-2 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white rounded-lg text-xs font-medium transition-all flex items-center space-x-1.5">
                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                        <span>Copy</span>
                    </button>
                    <button type="button" wire:click="downloadPdf" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-all flex items-center space-x-1.5 shadow-lg shadow-emerald-500/20">
                        <i data-lucide="file-down" class="w-4 h-4"></i>
                        <span>Download PDF</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Nama Perusahaan</label>
                    <input type="text" wire:model="companyName"
                           class="w-full px-3 py-2 bg-slate-900/60 border border-slate-700/60 rounded-lg text-sm text-slate-200 focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Posisi / Role</label>
                    <input type="text" wire:model="jobTitle"
                           class="w-full px-3 py-2 bg-slate-900/60 border border-slate-700/60 rounded-lg text-sm text-slate-200 focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <textarea wire:model="coverLetter" rows="12"
                      class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700/60 rounded-xl text-slate-200 leading-relaxed text-sm focus:outline-none focus:border-cyan-500"></textarea>

            <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between text-[11px] text-slate-500">
                <span>Format PDF: A4 • Paragraf Justify • TTD + Nama</span>
                <span>Bahasa: {{ $language === 'en' ? 'English' : 'Indonesia' }}</span>
            </div>
        </div>
        @endif
    </div>
</div>
