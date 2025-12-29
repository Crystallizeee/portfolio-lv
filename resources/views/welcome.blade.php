<x-layouts.app>
    {{-- Hero Section --}}
    <livewire:hero-section />
    
    {{-- About Section --}}
    <section id="about" class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="terminal-text font-mono text-sm mb-4 block">
                    <span class="text-slate-500">$</span> cat /home/beni/about.md
                </span>
                <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                    About Me
                </h2>
            </div>
            
            <div class="glass-card p-8">
                <div class="prose prose-invert max-w-none">
                    <p class="text-slate-300 text-lg leading-relaxed mb-6">
                        I'm a <span class="text-cyan-400 font-semibold">Hybrid GRC & Technical Practitioner</span> 
                        with expertise bridging the gap between compliance frameworks and hands-on security testing.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div class="glass-card p-4">
                            <div class="flex items-center space-x-3 mb-3">
                                <i data-lucide="shield" class="w-5 h-5 text-cyan-400"></i>
                                <h3 class="font-mono text-lg font-semibold text-white">GRC Expertise</h3>
                            </div>
                            <ul class="text-slate-400 text-sm space-y-2">
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>ISO 27001 Implementation</li>
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>ICT Risk Assessment</li>
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>Security Policy Development</li>
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>Vendor Risk Management</li>
                            </ul>
                        </div>
                        
                        <div class="glass-card p-4">
                            <div class="flex items-center space-x-3 mb-3">
                                <i data-lucide="terminal" class="w-5 h-5 text-cyan-400"></i>
                                <h3 class="font-mono text-lg font-semibold text-white">Technical Skills</h3>
                            </div>
                            <ul class="text-slate-400 text-sm space-y-2">
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>Penetration Testing</li>
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>SIEM Implementation</li>
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>Infrastructure Automation</li>
                                <li class="flex items-center"><i data-lucide="check" class="w-4 h-4 mr-2 text-green-400"></i>Container Security</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- Server Status / Home Lab Section --}}
    <livewire:server-status />
    
    {{-- Experience Timeline --}}
    <livewire:experience-timeline />
    
    {{-- Skills Section --}}
    <livewire:skills-section />
    
    {{-- Projects Grid --}}
    <livewire:projects-grid />
</x-layouts.app>
