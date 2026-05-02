<div>
    {{-- Mandatory 2FA Warning Banner --}}
    @if(session('two_factor_required'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-red-500/10 border border-red-500/40 flex items-start space-x-3">
            <div class="flex-shrink-0 mt-0.5">
                <i data-lucide="shield-alert" class="w-5 h-5 text-red-400"></i>
            </div>
            <div>
                <div class="text-sm font-semibold text-red-400 font-mono mb-1">⚠ Two-Factor Authentication Wajib Diaktifkan</div>
                <div class="text-xs text-slate-400">Kamu tidak bisa mengakses admin panel sebelum mengaktifkan 2FA. Scroll ke bawah ke bagian <span class="text-cyan-400 font-mono">Two-Factor Authentication</span> dan klik <strong>Enable 2FA</strong> untuk melanjutkan.</div>
            </div>
        </div>
    @endif

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
                                <img src="{{ $avatar }}" alt="Avatar" class="w-full h-full object-cover">
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
                                class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                            >
                            @error('website') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Professional Title (for CV) --}}
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Professional Title (for CV)</label>
                        
                        @if(!$isCustomTitle)
                            <div class="flex space-x-2">
                                <select 
                                    wire:model.live="professional_title"
                                    class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                                >
                                    <option value="">Select a title...</option>
                                    @foreach($titleOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                    <option value="custom">-- Custom Title --</option>
                                </select>
                            </div>
                        @else
                            <div class="space-y-2">
                                <input 
                                    type="text" 
                                    wire:model="professional_title"
                                    placeholder="Enter custom professional title"
                                    class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                                >
                                <button 
                                    type="button" 
                                    wire:click="switchToDropdown"
                                    class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors flex items-center"
                                >
                                    <i data-lucide="list" class="w-3 h-3 mr-1"></i>
                                    Switch back to predefined titles
                                </button>
                            </div>
                        @endif
                        @error('professional_title') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
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

                    {{-- Homepage Customization --}}
                    <div class="pt-6 border-t border-slate-700/50 space-y-4">
                        <h4 class="text-sm font-mono text-cyan-400 font-semibold uppercase tracking-wider">Homepage Customization</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Contact Title --}}
                            <div>
                                <label class="block text-sm text-slate-400 mb-1">Contact Section Title</label>
                                <input 
                                    type="text" 
                                    wire:model="contact_title"
                                    class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                                >
                                @error('contact_title') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            {{-- Contact Subtitle --}}
                            <div>
                                <label class="block text-sm text-slate-400 mb-1">Contact Section Subtitle</label>
                                <input 
                                    type="text" 
                                    wire:model="contact_subtitle"
                                    class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors"
                                >
                                @error('contact_subtitle') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- GRC Skills List --}}
                            <div>
                                <label class="block text-sm text-slate-400 mb-1">GRC Expertise List (One per line)</label>
                                <textarea 
                                    wire:model="about_grc_list"
                                    rows="4"
                                    class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors resize-none font-mono text-xs"
                                ></textarea>
                                @error('about_grc_list') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tech Skills List --}}
                            <div>
                                <label class="block text-sm text-slate-400 mb-1">Technical Skills List (One per line)</label>
                                <textarea 
                                    wire:model="about_tech_list"
                                    rows="4"
                                    class="w-full px-4 py-2 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:border-cyan-400 focus:outline-none transition-colors resize-none font-mono text-xs"
                                ></textarea>
                                @error('about_tech_list') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>
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

            {{-- Two-Factor Authentication Section --}}
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-white font-mono mb-1 flex items-center">
                    <i data-lucide="shield-check" class="w-5 h-5 mr-2 text-cyan-400"></i>
                    Two-Factor Authentication (2FA)
                </h3>
                <p class="text-xs text-slate-500 font-mono mb-5">Tambahkan lapisan keamanan ekstra menggunakan TOTP (Google Authenticator, Authy, dll).</p>

                @if(session('twofactor_success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 text-sm font-mono flex items-center">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                        {{ session('twofactor_success') }}
                    </div>
                @endif

                @if(! $twoFactorEnabled && ! $showTwoFactorSetup)
                    {{-- State 1: 2FA not yet enabled --}}
                    <div class="flex items-center justify-between p-4 rounded-lg bg-slate-800/50 border border-slate-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center">
                                <i data-lucide="shield-off" class="w-5 h-5 text-slate-400"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-300">Status: <span class="text-slate-500 font-mono">Not Enabled</span></div>
                                <div class="text-xs text-slate-500">Akun kamu belum dilindungi oleh 2FA.</div>
                            </div>
                        </div>
                        <button
                            wire:click="enableTwoFactor"
                            wire:loading.attr="disabled"
                            class="py-2 px-5 bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/50 hover:border-cyan-400 rounded-lg text-cyan-400 text-sm font-mono font-medium transition-all duration-200 flex items-center space-x-2"
                        >
                            <i data-lucide="shield-plus" class="w-4 h-4"></i>
                            <span>Enable 2FA</span>
                        </button>
                    </div>

                @elseif($showTwoFactorSetup)
                    {{-- State 2: Setup mode — show QR Code --}}
                    <div class="space-y-5">
                        <div class="flex items-center space-x-2 text-amber-400 text-sm font-mono">
                            <i data-lucide="info" class="w-4 h-4 flex-shrink-0"></i>
                            <span>Scan QR code di bawah dengan authenticator app kamu, lalu masukkan kode 6-digit untuk mengkonfirmasi.</span>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            {{-- QR Code --}}
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-white rounded-xl inline-block" style="line-height: 0;">
                                    <img src="data:image/svg+xml;base64,{{ $twoFactorQrCode }}" alt="2FA QR Code" width="180" height="180">
                                </div>
                            </div>

                            <div class="flex-1 space-y-4">
                                {{-- Manual setup key --}}
                                <div>
                                    <label class="block text-xs text-slate-500 font-mono mb-1">Setup key (manual entry):</label>
                                    <div class="flex items-center space-x-2">
                                        <code class="flex-1 px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-cyan-300 font-mono text-sm tracking-widest break-all">{{ $twoFactorSetupKey }}</code>
                                    </div>
                                </div>

                                {{-- Confirm OTP --}}
                                <div>
                                    <label class="block text-sm text-slate-400 mb-1 font-mono">Konfirmasi kode OTP:</label>
                                    <input
                                        wire:model="twoFactorConfirmCode"
                                        type="text"
                                        inputmode="numeric"
                                        maxlength="6"
                                        placeholder="······"
                                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg text-white placeholder-slate-600 focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition-colors font-mono text-center text-2xl tracking-[0.5em]"
                                    >
                                    @error('twoFactorConfirmCode')
                                        <span class="text-red-400 text-xs mt-1 block font-mono">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="flex items-center space-x-3 pt-2">
                                    <button
                                        wire:click="confirmTwoFactor"
                                        wire:loading.attr="disabled"
                                        class="py-2 px-5 bg-cyan-500 hover:bg-cyan-600 rounded-lg text-white text-sm font-medium transition-colors"
                                    >
                                        Konfirmasi & Aktifkan
                                    </button>
                                    <button
                                        wire:click="$set('showTwoFactorSetup', false)"
                                        class="py-2 px-4 text-slate-400 hover:text-white text-sm transition-colors"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- State 3: 2FA is active --}}
                    <div class="space-y-5">
                        {{-- Active badge --}}
                        <div class="flex items-center justify-between p-4 rounded-lg bg-green-500/5 border border-green-500/30">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                                    <i data-lucide="shield-check" class="w-5 h-5 text-green-400"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-green-400 font-mono">✓ 2FA Aktif</div>
                                    <div class="text-xs text-slate-500">Login kamu dilindungi oleh TOTP authenticator.</div>
                                </div>
                            </div>
                            <button
                                wire:click="disableTwoFactor"
                                wire:confirm="Yakin ingin menonaktifkan 2FA? Akun kamu akan jadi kurang aman."
                                class="py-2 px-4 bg-red-500/20 hover:bg-red-500/30 border border-red-500/40 hover:border-red-400 rounded-lg text-red-400 text-xs font-mono transition-all duration-200"
                            >
                                Disable 2FA
                            </button>
                        </div>

                        {{-- Recovery Codes --}}
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="text-sm font-medium text-slate-300 font-mono">Recovery Codes</h4>
                                    <p class="text-xs text-slate-500">Simpan kode ini di tempat yang aman. Setiap kode hanya bisa digunakan sekali.</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if(count($recoveryCodes) === 0)
                                        <button
                                            wire:click="showRecoveryCodes"
                                            class="py-1.5 px-3 bg-slate-700 hover:bg-slate-600 rounded-lg text-slate-300 text-xs font-mono transition-colors flex items-center space-x-1"
                                        >
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                            <span>Lihat</span>
                                        </button>
                                    @endif
                                    <button
                                        wire:click="regenerateRecoveryCodes"
                                        wire:confirm="Recovery codes lama akan tidak valid. Lanjutkan?"
                                        class="py-1.5 px-3 bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/40 rounded-lg text-amber-400 text-xs font-mono transition-all flex items-center space-x-1"
                                    >
                                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                        <span>Regenerate</span>
                                    </button>
                                </div>
                            </div>

                            @if(count($recoveryCodes) > 0)
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($recoveryCodes as $code)
                                        <code class="px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 font-mono text-xs text-center tracking-widest">{{ $code }}</code>
                                    @endforeach
                                </div>
                                <p class="text-xs text-amber-400/70 font-mono mt-2">
                                    ⚠ Setelah halaman ini di-refresh, kode tidak akan ditampilkan lagi. Simpan sekarang!
                                </p>
                            @else
                                <div class="text-xs text-slate-600 font-mono italic text-center py-3 border border-dashed border-slate-700 rounded-lg">
                                    Klik "Lihat" untuk menampilkan recovery codes.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
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
