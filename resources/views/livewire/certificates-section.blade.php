<div id="certifications" class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> ls -la /usr/local/certs
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                Professional Certifications
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Verified credentials demonstrating expertise in cybersecurity, compliance, and systems engineering.
            </p>
        </div>
        
        <!-- Certificates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($certificates as $cert)
                <div class="glass-card group overflow-hidden rounded-2xl flex flex-col hover:shadow-[0_0_30px_-5px_rgba(234,179,8,0.2)] hover:border-yellow-500/30 transition-all duration-500">
                    <!-- Image Hero Section -->
                    <div class="relative w-full aspect-video overflow-hidden bg-slate-800">
                        @if($cert->image)
                            <img 
                                src="{{ Storage::url($cert->image) }}" 
                                alt="{{ $cert->name }}" 
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out"
                                loading="lazy"
                            >
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-700 ease-out">
                                <i data-lucide="award" class="w-16 h-16 text-yellow-500/50"></i>
                            </div>
                        @endif
                        <!-- Subtle gradient overlay to blend image with content -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                    </div>
                    
                    <!-- Content Area -->
                    <div class="p-6 relative z-10 flex flex-col flex-grow -mt-10">
                        <!-- Issuer Badge -->
                        <div class="mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 backdrop-blur-sm shadow-lg shadow-yellow-500/5">
                                {{ $cert->issuer }} &bull; {{ $cert->year }}
                            </span>
                        </div>

                        <!-- Certificate Name -->
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-yellow-400 transition-colors duration-300">
                            {{ $cert->name }}
                        </h3>

                        <!-- Description -->
                        @if($cert->description)
                            <p class="text-sm text-slate-400 leading-relaxed mb-6 line-clamp-3">
                                {{ $cert->description }}
                            </p>
                        @else
                            <div class="mb-6"></div>
                        @endif

                        <!-- Footer Area (Always at bottom) -->
                        <div class="mt-auto pt-4 border-t border-slate-700/50 flex flex-col space-y-3">
                            @if($cert->credential_id)
                                <div class="flex items-center text-xs text-slate-500">
                                    <i data-lucide="hash" class="w-3.5 h-3.5 mr-1.5"></i>
                                    <span class="font-mono">{{ $cert->credential_id }}</span>
                                </div>
                            @endif

                            @if($cert->credential_url)
                                <a 
                                    href="{{ $cert->credential_url }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center w-full px-4 py-2 bg-slate-800 hover:bg-yellow-500/20 text-slate-300 hover:text-yellow-400 border border-slate-700 hover:border-yellow-500/50 rounded-lg text-sm font-medium transition-all duration-300 group/btn"
                                >
                                    <span>Verify Credential</span>
                                    <i data-lucide="external-link" class="w-4 h-4 ml-2 transform group-hover/btn:-translate-y-0.5 group-hover/btn:translate-x-0.5 transition-transform"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
