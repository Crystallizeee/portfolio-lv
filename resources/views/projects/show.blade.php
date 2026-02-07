<x-layouts.app 
    :title="$project->seo?->title ?? $project->title" 
    :description="$project->seo?->description ?? $project->description"
    :keywords="$project->seo?->keywords"
>
    <div class="pt-32 pb-24 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-cyan-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumb -->
            <div class="flex items-center space-x-2 text-sm font-mono text-cyan-400 mb-8 animate-fade-in-up">
                <a href="{{ url('/#projects') }}" class="hover:underline">~/projects</a>
                <span class="text-slate-600">/</span>
                <span class="text-slate-400">{{ $project->slug ?? $project->id }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Sidebar Info -->
                <div class="lg:col-span-4 space-y-8 animate-fade-in-up delay-100">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                            {{ $project->title }}
                        </h1>
                        <span class="inline-block px-3 py-1 bg-slate-800 text-cyan-400 rounded-lg text-sm font-mono border border-slate-700/50">
                            {{ $project->type }}
                        </span>
                    </div>

                    <div class="glass-card p-6 rounded-2xl border border-slate-700/50">
                        <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Tech Stack</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($project->tech_stack ?? [] as $tech)
                                <span class="px-3 py-1.5 bg-slate-800/50 text-slate-300 rounded-lg text-sm border border-slate-700/50">
                                    {{ $tech }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    @if($project->url)
                        <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer" 
                           class="flex items-center justify-center w-full px-6 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl text-white font-semibold hover:from-cyan-400 hover:to-blue-400 transition-all shadow-lg shadow-cyan-500/25 group">
                            <span>Visit Live Site</span>
                            <i data-lucide="external-link" class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endif
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-8 space-y-12 animate-fade-in-up delay-200">
                    <!-- Overview -->
                    <section class="prose prose-invert prose-lg max-w-none">
                        <p class="lead text-xl text-slate-300">
                            {{ $project->description }}
                        </p>
                    </section>

                    <!-- Case Study Sections -->
                    @if($project->challenge || $project->solution || $project->results)
                        <div class="grid gap-8">
                            @if($project->challenge)
                                <div class="glass-card p-8 rounded-2xl border-l-4 border-red-500 bg-slate-900/50">
                                    <h3 class="text-2xl font-bold text-white mb-4 flex items-center">
                                        <i data-lucide="target" class="w-6 h-6 mr-3 text-red-500"></i>
                                        The Challenge
                                    </h3>
                                    <div class="prose prose-invert text-slate-400">
                                        {!! Str::markdown($project->challenge) !!}
                                    </div>
                                </div>
                            @endif

                            @if($project->solution)
                                <div class="glass-card p-8 rounded-2xl border-l-4 border-yellow-500 bg-slate-900/50">
                                    <h3 class="text-2xl font-bold text-white mb-4 flex items-center">
                                        <i data-lucide="lightbulb" class="w-6 h-6 mr-3 text-yellow-500"></i>
                                        The Solution
                                    </h3>
                                    <div class="prose prose-invert text-slate-400">
                                        {!! Str::markdown($project->solution) !!}
                                    </div>
                                </div>
                            @endif

                            @if($project->results)
                                <div class="glass-card p-8 rounded-2xl border-l-4 border-green-500 bg-slate-900/50">
                                    <h3 class="text-2xl font-bold text-white mb-4 flex items-center">
                                        <i data-lucide="trophy" class="w-6 h-6 mr-3 text-green-500"></i>
                                        The Results
                                    </h3>
                                    <div class="prose prose-invert text-slate-400">
                                        {!! Str::markdown($project->results) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Gallery -->
                    @if($project->gallery && count($project->gallery) > 0)
                        <section>
                            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <i data-lucide="image" class="w-6 h-6 mr-3 text-cyan-400"></i>
                                Project Gallery
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($project->gallery as $image)
                                    <div class="group relative aspect-video rounded-xl overflow-hidden border border-slate-700/50 cursor-zoom-in hover:border-cyan-500/50 transition-colors">
                                        <img src="{{ trim($image) }}" alt="Project screenshot" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <i data-lucide="maximize-2" class="w-8 h-8 text-white"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
