<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="glass-card p-8">
            <!-- Terminal Header -->
            <div class="flex items-center space-x-2 mb-8">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <span class="ml-4 font-mono text-sm text-slate-400">admin@portfolio:~</span>
            </div>

            <!-- Title -->
            <div class="text-center mb-8">
                <div class="terminal-text font-mono text-sm mb-2">
                    <span class="text-slate-500">$</span> sudo authenticate
                </div>
                <h1 class="text-2xl font-bold text-white font-mono">Admin Login</h1>
            </div>

            <!-- Login Form -->
            <form wire:submit="login" class="space-y-6">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-400 mb-2">
                        <span class="terminal-text">email:</span>
                    </label>
                    <input 
                        wire:model="email"
                        type="email" 
                        id="email"
                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-colors font-mono"
                        placeholder="admin@example.com"
                    >
                    @error('email')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-400 mb-2">
                        <span class="terminal-text">password:</span>
                    </label>
                    <input 
                        wire:model="password"
                        type="password" 
                        id="password"
                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-colors font-mono"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        wire:model="remember"
                        type="checkbox" 
                        id="remember"
                        class="w-4 h-4 bg-slate-800 border-slate-600 rounded text-cyan-400 focus:ring-cyan-400 focus:ring-offset-0"
                    >
                    <label for="remember" class="ml-2 text-sm text-slate-400">Ingat saya</label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full py-3 px-4 bg-cyan-500/20 border border-cyan-500/50 rounded-lg text-cyan-400 font-medium hover:bg-cyan-500/30 hover:border-cyan-400 transition-all duration-200 font-mono flex items-center justify-center space-x-2"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <span wire:loading.remove>
                        <span class="text-slate-500">$</span> login --execute
                    </span>
                    <span wire:loading class="flex items-center space-x-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Authenticating...</span>
                    </span>
                </button>
            </form>

            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-slate-500 hover:text-cyan-400 text-sm transition-colors">
                    ← Kembali ke Portfolio
                </a>
            </div>
        </div>
    </div>
</div>
