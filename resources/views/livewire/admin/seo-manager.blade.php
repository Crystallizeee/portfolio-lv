<div>
    <div class="glass-card p-6">
        <div class="flex items-center space-x-3 mb-6">
            <div class="p-3 rounded-lg bg-cyan-500/20">
                <i data-lucide="search" class="w-6 h-6 text-cyan-400"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Global SEO Settings</h2>
                <p class="text-sm text-slate-400">Define default meta tags for the entire site.</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-500/10 border border-green-500/50 text-green-400 p-4 rounded-lg mb-6 flex items-center space-x-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Meta Title -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-300">Default Meta Title</label>
                    <input type="text" wire:model="title" 
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-colors"
                        placeholder="e.g. My Portfolio">
                    @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Keywords -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-300">Default Keywords</label>
                    <input type="text" wire:model="keywords" 
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-colors"
                        placeholder="e.g. portfolio, developer, laravel">
                    @error('keywords') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Meta Description -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-300">Default Meta Description</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-colors"
                        placeholder="A brief description of your site..."></textarea>
                    @error('description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- OG Image URL -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-medium text-slate-300">Default OG Image URL</label>
                    <input type="text" wire:model="og_image" 
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-colors"
                        placeholder="https://example.com/og-image.jpg">
                    @error('og_image') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <!-- Canonical URL -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-300">Canonical URL Base</label>
                    <input type="text" wire:model="canonical_url" 
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-colors"
                        placeholder="https://mysite.com">
                    @error('canonical_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <!-- Indexable -->
                <div class="flex items-center space-x-3 pt-8">
                    <input type="checkbox" wire:model="indexable" id="indexable"
                        class="w-5 h-5 rounded border-slate-700 bg-slate-800 text-cyan-500 focus:ring-offset-slate-900">
                    <label for="indexable" class="text-sm font-medium text-slate-300">Allow Search Engines to Index Site</label>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-700/50">
                <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white font-medium rounded-lg shadow-lg shadow-cyan-500/20 transition-all transform hover:scale-[1.02]">
                    <span wire:loading.remove>Save Settings</span>
                    <span wire:loading class="flex items-center space-x-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        <span>Saving...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
