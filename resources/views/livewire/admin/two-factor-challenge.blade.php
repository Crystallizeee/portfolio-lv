<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="glass-card p-8">
            <!-- Terminal Header -->
            <div class="flex items-center space-x-2 mb-8">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <span class="ml-4 font-mono text-sm text-slate-400">admin@portfolio:~$ 2fa --verify</span>
            </div>

            <!-- Icon & Title -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <div class="relative">
                        <!-- Shield icon with glow -->
                        <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center" style="box-shadow: 0 0 20px rgba(6,182,212,0.2);">
                            <svg class="w-8 h-8 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 10c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.249-8.25-3.286z" />
                            </svg>
                        </div>
                        <!-- Pulse ring -->
                        <div class="absolute inset-0 rounded-2xl border border-cyan-400/20 animate-ping"></div>
                    </div>
                </div>
                <div class="terminal-text font-mono text-sm mb-2">
                    <span class="text-slate-500">$</span> authenticate --2fa
                </div>
                <h1 class="text-2xl font-bold text-white font-mono">Two-Factor Auth</h1>
                <p class="text-slate-400 text-sm mt-2 font-mono">
                    @if(! $usingRecoveryCode)
                        Masukkan kode 6-digit dari aplikasi authenticator kamu.
                    @else
                        Masukkan salah satu recovery code cadangan kamu.
                    @endif
                </p>
            </div>

            <!-- Verify Form -->
            <form wire:submit="verify" class="space-y-6">
                @if(! $usingRecoveryCode)
                    <!-- TOTP Code Input -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-400 mb-2">
                            <span class="terminal-text">otp_code:</span>
                        </label>
                        <input
                            wire:model="code"
                            type="text"
                            id="code"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            maxlength="6"
                            autofocus
                            class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-colors font-mono text-center text-2xl tracking-[0.5em]"
                            placeholder="······"
                        >
                        @error('code')
                            <span class="text-red-400 text-sm mt-1 block font-mono">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <!-- Recovery Code Input -->
                    <div>
                        <label for="recoveryCode" class="block text-sm font-medium text-slate-400 mb-2">
                            <span class="terminal-text">recovery_code:</span>
                        </label>
                        <input
                            wire:model="recoveryCode"
                            type="text"
                            id="recoveryCode"
                            autocomplete="off"
                            autofocus
                            class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors font-mono text-center text-sm tracking-widest"
                            placeholder="xxxx-xxxx-xxxx-xxxx"
                        >
                        @error('recoveryCode')
                            <span class="text-red-400 text-sm mt-1 block font-mono">{{ $message }}</span>
                        @enderror
                        <p class="text-amber-400/70 text-xs mt-2 font-mono">
                            ⚠ Recovery code hanya bisa digunakan sekali.
                        </p>
                    </div>
                @endif

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-lg font-medium transition-all duration-200 font-mono flex items-center justify-center space-x-2
                        {{ $usingRecoveryCode
                            ? 'bg-amber-500/20 border border-amber-500/50 text-amber-400 hover:bg-amber-500/30 hover:border-amber-400'
                            : 'bg-cyan-500/20 border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/30 hover:border-cyan-400' }}"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <span wire:loading.remove>
                        <span class="text-slate-500">$</span> verify --execute
                    </span>
                    <span wire:loading class="flex items-center space-x-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Verifying...</span>
                    </span>
                </button>
            </form>

            <!-- Toggle between TOTP / Recovery Code -->
            <div class="mt-6 text-center">
                <button
                    wire:click="toggleMode"
                    class="text-slate-500 hover:text-cyan-400 text-sm transition-colors font-mono"
                >
                    @if(! $usingRecoveryCode)
                        🔑 Gunakan recovery code
                    @else
                        📱 Gunakan authenticator app
                    @endif
                </button>
            </div>

            <!-- Back to Login -->
            <div class="mt-3 text-center">
                <a href="{{ route('admin.login') }}" class="text-slate-600 hover:text-slate-400 text-xs transition-colors font-mono">
                    ← Kembali ke login
                </a>
            </div>
        </div>
    </div>
</div>
