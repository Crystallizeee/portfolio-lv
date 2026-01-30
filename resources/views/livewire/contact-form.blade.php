<section id="contact" class="py-20 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-purple-600/10 blur-[100px] rounded-full"></div>
    <div class="absolute bottom-0 left-0 w-1/4 h-1/4 bg-cyan-600/10 blur-[100px] rounded-full"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12">
            <span class="terminal-text font-mono text-sm mb-4 block">
                <span class="text-slate-500">$</span> ./contact_info.sh
            </span>
            <h2 class="font-mono text-3xl md:text-4xl font-bold text-white mb-4">
                Get In <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">Touch</span>
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Interested in working together or have a question? Reach out to me directly.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Email -->
            <a href="mailto:{{ $user->email }}" class="glass-card p-6 group hover:translate-y-[-5px] transition-all duration-300 border border-slate-700/50 hover:border-cyan-500/50">
                <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center mb-4 group-hover:bg-cyan-500/20 transition-colors">
                    <i data-lucide="mail" class="w-6 h-6 text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Email</h3>
                <p class="text-slate-400 text-sm break-all">{{ $user->email }}</p>
            </a>

            <!-- LinkedIn -->
            @if($user->linkedin)
            <a href="{{ $user->linkedin }}" target="_blank" class="glass-card p-6 group hover:translate-y-[-5px] transition-all duration-300 border border-slate-700/50 hover:border-blue-500/50">
                <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center mb-4 group-hover:bg-blue-500/20 transition-colors">
                    <i data-lucide="linkedin" class="w-6 h-6 text-slate-400 group-hover:text-blue-400 transition-colors"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">LinkedIn</h3>
                <p class="text-slate-400 text-sm">Connect Professionally</p>
            </a>
            @endif

            <!-- GitHub -->
            @if($user->github)
            <a href="{{ $user->github }}" target="_blank" class="glass-card p-6 group hover:translate-y-[-5px] transition-all duration-300 border border-slate-700/50 hover:border-purple-500/50">
                <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center mb-4 group-hover:bg-purple-500/20 transition-colors">
                    <i data-lucide="github" class="w-6 h-6 text-slate-400 group-hover:text-purple-400 transition-colors"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">GitHub</h3>
                <p class="text-slate-400 text-sm">Check my Code</p>
            </a>
            @endif
        </div>
    </div>
</section>
